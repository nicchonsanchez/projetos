/*
    CRONÔMETROS - app.nicchon.com/cronometros
    HTML+CSS+jQuery, sem framework. Estado em listaCronometros indexado por
    ID único (XXX-XXX-XXX), persistido em localStorage entre recargas.
*/

var listaCronometros = {};
var STORAGE_KEY = 'cronometros:v2'; // v2 = formato novo com MM:SS, beep e persistência

/*
    BOOTSTRAP - executado quando o DOM tá pronto
*/

$(function () {
    // Tenta restaurar do localStorage; se não houver nada salvo, cria um padrão
    var salvos = carregarDoStorage();
    var ids = Object.keys(salvos);

    if (ids.length > 0) {
        ids.forEach(function (id) {
            criarCronometro(salvos[id].nome, salvos[id].tempo, id);
        });
    } else {
        criarCronometro('Cronômetro', 60);
    }
});


/*
    CRIAR / REMOVER
*/

// Chamado pelo card "+" — adiciona um cronômetro novo de 1 minuto
function criarBox(){
    criarCronometro('Cronômetro', 60);
}

// Renderiza um cronômetro no DOM. Se `idExistente` for passado, reusa esse ID
// (caso de reidratar do storage); senão gera um ID novo.
function criarCronometro(nome, tempo, idExistente){
    var id = idExistente || gerarID();

    listaCronometros[id] = {
        id: id,
        nome: nome,
        tempo: tempo,        // tempo original (referência pra zerar)
        tempoAtual: tempo,   // contagem em curso
        emExecucao: false,
        intervalRef: null    // referência do setInterval pra clearInterval
    };

    var html = `
        <article class="cronometro" id="${id}">
            <header>
                <button type="button" class="excluir" data-acao="excluir" data-id="${id}" aria-label="Excluir cronômetro">&times;</button>
                <div class="titulo">
                    <input type="text" name="novoNome" value="${escapar(nome)}" hidden>
                    <h2>
                        <span>${escapar(nome)}</span>
                        <button type="button" class="btn-editar" data-acao="editarNome" data-id="${id}" aria-label="Editar nome"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></button>
                    </h2>
                </div>
                <!-- /.titulo -->
                <p class="subtitulo">Código: ${id}</p>
                <!-- /.subtitulo -->
            </header>
            <section>
                <div class="tempo">
                    <input type="text" name="novoTempo" value="${formatarTempo(tempo)}" hidden>
                    <span>${formatarTempo(tempo)}</span>
                    <button type="button" class="btn-editar" data-acao="editarTempo" data-id="${id}" aria-label="Editar tempo"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></button>
                </div>
                <!-- /.tempo -->
                <div class="botoes">
                    <button type="button" data-acao="iniciar" data-id="${id}" class="iniciar">Iniciar</button>
                    <button type="button" data-acao="parar"   data-id="${id}" class="pausar">Pausar</button>
                    <button type="button" data-acao="zerar"   data-id="${id}" class="zerar">Zerar</button>
                </div>
                <!-- /.botoes -->
            </section>
        </article>
        <!-- /.cronometro -->
    `;

    $('#adicionarCronometro').before(html);
    salvarNoStorage();
}

// Gera ID único no formato XXX-XXX-XXX (~10⁹ possibilidades, colisão é praticamente nula)
function gerarID(){
    var id;
    do {
        var n = Math.floor(Math.random() * 10 ** 9).toString().padStart(9, '0'); // padStart adiciona zeros à esquerda
        id = n.slice(0, 3) + '-' + n.slice(3, 6) + '-' + n.slice(6);
    } while (listaCronometros.hasOwnProperty(id));
    return id;
}


/*
    AÇÕES
*/

// Inicia a contagem regressiva. Se já estiver rodando, ignora (não duplica).
function iniciar(id){
    var c = listaCronometros[id];
    if (!c || c.emExecucao) return;

    c.emExecucao = true;
    $(`#${id}`).addClass('em-execucao').removeClass('terminado');

    c.intervalRef = setInterval(function () {
        if (c.tempoAtual > 0) {
            c.tempoAtual--;
            atualizarCronometro(id, c.tempoAtual);
        } else {
            // Chegou a 0 — para o intervalo e dispara aviso (beep + flash visual)
            parar(id);
            avisarFim(id);
        }
    }, 1000);
}

// Pausa sem zerar — pode retomar do mesmo ponto com iniciar()
function parar(id){
    var c = listaCronometros[id];
    if (!c) return;
    c.emExecucao = false;
    clearInterval(c.intervalRef);
    c.intervalRef = null;
    $(`#${id}`).removeClass('em-execucao');
}

// Para e volta ao tempo original
function zerar(id){
    var c = listaCronometros[id];
    if (!c) return;
    parar(id);
    c.tempoAtual = c.tempo;
    atualizarCronometro(id, c.tempo);
    $(`#${id}`).removeClass('terminado');
}

// Remove o cronômetro de vez (lista + DOM + storage)
function excluir(id){
    parar(id);
    delete listaCronometros[id];
    $(`#${id}`).remove();
    salvarNoStorage();
}

// Só atualiza display visual — não mexe em estado de execução
function atualizarCronometro(id, tempoAtualizado){
    listaCronometros[id].tempoAtual = tempoAtualizado;
    $(`#${id} .tempo span`).text(formatarTempo(tempoAtualizado));
}


/*
    EDIÇÃO INLINE
    Usa .off().on() pra evitar acumular listeners se o user editar o mesmo
    campo várias vezes (bug do código antigo).
*/

function editarNome(id){
    var span  = $(`#${id} .titulo span`);
    var input = $(`#${id} .titulo input`);

    span.hide();
    input.show().val(span.text()).focus().select();

    input.off('keyup').on('keyup', function (e) {
        if (e.key === 'Enter') {
            var novo = input.val().trim() || 'Cronômetro';
            listaCronometros[id].nome = novo;
            span.text(novo);
            span.show();
            input.hide();
            salvarNoStorage();
        } else if (e.key === 'Escape') {
            span.show();
            input.hide();
        }
    });
}

// Aceita 3 formatos: "300" (segundos), "5:00" (MM:SS) ou "1:30:00" (HH:MM:SS)
function editarTempo(id){
    var c     = listaCronometros[id];
    var span  = $(`#${id} .tempo span`);
    var input = $(`#${id} .tempo input`);

    span.hide();
    input.show().val(formatarTempo(c.tempo)).focus().select();

    input.off('keyup').on('keyup', function (e) {
        if (e.key === 'Enter') {
            var parsed = parsearTempo(input.val());
            if (parsed > 0) {
                c.tempo = parsed;
                c.tempoAtual = parsed;
                atualizarCronometro(id, parsed);
                salvarNoStorage();
            }
            span.show();
            input.hide();
        } else if (e.key === 'Escape') {
            span.show();
            input.hide();
        }
    });
}


/*
    FORMATAÇÃO DE TEMPO
*/

// segundos → "MM:SS" (ou "H:MM:SS" se >= 1 hora)
function formatarTempo(segundos){
    segundos = Math.max(0, Math.floor(segundos));
    var h = Math.floor(segundos / 3600);
    var m = Math.floor((segundos % 3600) / 60);
    var s = segundos % 60;
    var mm = m.toString().padStart(2, '0');
    var ss = s.toString().padStart(2, '0');
    if (h > 0) return h + ':' + mm + ':' + ss;
    return mm + ':' + ss;
}

// "5:30", "1:30:00" ou "300" → total em segundos
function parsearTempo(texto){
    texto = texto.trim();
    if (texto === '') return 0;
    if (texto.indexOf(':') !== -1) {
        var partes = texto.split(':').map(function (p) { return parseInt(p, 10) || 0; });
        if (partes.length === 3) return partes[0] * 3600 + partes[1] * 60 + partes[2];
        if (partes.length === 2) return partes[0] * 60 + partes[1];
        return 0;
    }
    var n = parseInt(texto, 10);
    return isNaN(n) ? 0 : n;
}


/*
    AVISO DE FIM (3 beeps + flash visual)
    Web Audio gera o beep sem precisar de arquivo .mp3 hospedado.
*/

function avisarFim(id){
    var card = $(`#${id}`);
    card.addClass('terminado');
    setTimeout(function () { card.removeClass('terminado'); }, 4000);

    try {
        var ctx = new (window.AudioContext || window.webkitAudioContext)();
        // 3 beeps curtos (200ms cada, intervalo de 250ms) em frequência 880Hz (A5)
        for (var i = 0; i < 3; i++) {
            var osc  = ctx.createOscillator();
            var gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.value = 880;
            osc.type = 'sine';
            var start = ctx.currentTime + i * 0.25;
            // Envelope ADSR pra evitar "click" no início/fim
            gain.gain.setValueAtTime(0.0001, start);
            gain.gain.exponentialRampToValueAtTime(0.3, start + 0.01);
            gain.gain.exponentialRampToValueAtTime(0.0001, start + 0.18);
            osc.start(start);
            osc.stop(start + 0.2);
        }
    } catch (err) {
        // Web Audio não disponível em algum browser exótico — só o flash visual aparece
    }
}


/*
    PERSISTÊNCIA (localStorage)
    Salva só nome e tempo. Estado de execução não persiste (recarregar zera).
*/

function salvarNoStorage(){
    try {
        var snapshot = {};
        for (var id in listaCronometros) {
            var c = listaCronometros[id];
            snapshot[id] = { id: c.id, nome: c.nome, tempo: c.tempo };
        }
        localStorage.setItem(STORAGE_KEY, JSON.stringify(snapshot));
    } catch (e) {
        // Storage indisponível (modo privado, quota cheia) — ignorar silenciosamente
    }
}

function carregarDoStorage(){
    try {
        var raw = localStorage.getItem(STORAGE_KEY);
        return raw ? JSON.parse(raw) : {};
    } catch (e) {
        return {};
    }
}


/*
    UTILIDADES
*/

// Evita XSS no nome do cronômetro (input do user vai pra template string)
function escapar(s){
    return String(s == null ? '' : s)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}


/*
    EVENT DELEGATION (CSP-safe)
    Um único listener despacha todas as ações pelos atributos data-acao + data-id.
    Funciona pra elementos criados dinamicamente (delegação em document).
*/

$(document).on('click', '[data-acao]', function () {
    var acao = $(this).data('acao');
    var id   = $(this).data('id');
    switch (acao) {
        case 'adicionar':     criarBox();         break;
        case 'iniciar':       iniciar(id);        break;
        case 'parar':         parar(id);          break;
        case 'zerar':         zerar(id);          break;
        case 'excluir':       excluir(id);        break;
        case 'editarNome':    editarNome(id);     break;
        case 'editarTempo':   editarTempo(id);    break;
    }
});


/*
    TOGGLE DE TEMA (light/dark)
    Compartilha localStorage 'app-theme' com o agregador e os outros mini-apps.
*/

function toggleTheme(){
    var cur = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
    var nxt = cur === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', nxt);
    try { localStorage.setItem('app-theme', nxt); } catch (e) {}
}

$(document).on('click', '.theme-toggle', toggleTheme);
