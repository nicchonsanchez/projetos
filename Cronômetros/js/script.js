var listaCronometros = {};

criarCronometro('Cronômetro', 60);

function criarBox(){
    $('#000-000-000').remove();

    let novoCronometro = `
        <article class="cronometro" id="000-000-000">
            <header>
                
                <div class="excluir" onclick="ativarCronometro('excluir')">&times;</div>
                <div class="titulo">
                    <input style="display: none;" type="text" name="novoNome" value="">
                    <h2>
                        <span>Cronômetro</span>
                        <i class="btn-editar fa fa-pencil-square-o" aria-hidden="true" onclick="ativarCronometro('editarNome')"></i>
                    </h2>
                </div>
                <!-- /.titulo -->
                <h4 class="subtitulo">Código: 000-000-000</h4>
                <!-- /.subtitulo -->
            </header>
            <section>
                <div class="tempo">
                    <input style="display: none;" type="text" name="novoNome" value="">
                    <span>60</span>
                    <i class="btn-editar fa fa-pencil-square-o" aria-hidden="true" onclick="ativarCronometro('editarTempo')"></i>
                </div>
                <div class="botoes">
                    <button onclick="ativarCronometro('iniciar')" class="iniciar">Iniciar</button>
                    <button onclick="ativarCronometro('pausar')" class="pausar">Pausar</button>
                    <button onclick="ativarCronometro('zerar')" class="zerar">Zerar</button>
                </div>
            </section>
        </article>
        <!-- /.cronometro -->
    `;

    $('#adicionarCronometro').before(novoCronometro);
    criarCronometro('Cronômetro', 60);
}

function criarCronometro(nome, tempo){

    gerarID();
    function gerarID() {
        let id = Math.floor(Math.random() * 10 ** 9);
        id = id.toString();
        id = id.padStart(9, '0'); // padStart serve para adicionar caracteres à esquerda de uma string até que ela atinja um determinado comprimento
        id = id.slice(0, 3) + '-' + id.slice(3, 6) + '-' + id.slice(6);

        adicionarID(id);
    }
    function adicionarID(id) {
        if (!listaCronometros.hasOwnProperty(id)) {

            let tempoAtual = tempo;
            let emExecucao = false;

            listaCronometros[id] = {
                'id': id,
                'nome': nome,
                'tempo': tempo,
                'tempoAtual': tempoAtual,
                'emExecucao': emExecucao,
                'cronometro': null
            };

            // Criando o cronômetro
            var cronometroInativo = $('.cronometros-grupo #000-000-000');
            cronometroInativo.attr('id', id);
            cronometroInativo.html(`
                <header>
                    <div class="excluir" onclick="excluir('${id}')">&times;</div>
                    <div class="titulo">
                        <input style="display: none;" type="text" name="novoNome" value="${nome}">
                        <h2>
                            <span>${nome}</span>
                            <i class="btn-editar fa fa-pencil-square-o" aria-hidden="true" onclick="editarNome('${id}')"></i>
                        </h2>
                    </div>
                    <!-- /.titulo -->
                    <h4 class="subtitulo">Código: ${id}</h4>
                    <!-- /.subtitulo -->
                </header>
                <section>
                    <div class="tempo">
                        <input style="display: none;" type="text" name="novoNome" value="${tempo}">
                        <span>${tempo}</span>
                        <i class="btn-editar fa fa-pencil-square-o" aria-hidden="true" onclick="editarTempo('${id}')"></i>
                    </div>
                    <div class="botoes">
                        <button onclick="iniciar('${id}')" class="iniciar">Iniciar</button>
                        <button onclick="parar('${id}')" class="pausar">Pausar</button>
                        <button onclick="zerar('${id}')" class="zerar">Zerar</button>
                    </div>
                </section>
            `);
        } else {
            gerarID();
        }
    }
}




/*
    AÇÕES
*/
function iniciar(id){
    let nome = listaCronometros[id].nome;
    let tempo = listaCronometros[id].tempo;
    let tempoAtual = listaCronometros[id].tempoAtual;
    let emExecucao = listaCronometros[id].emExecucao;
    let cronometro;

    if (!emExecucao) {
        emExecucao = true;
        listaCronometros[id].emExecucao = true;
        listaCronometros[id].cronometro = setInterval(() => {
            if (tempoAtual > 0) {
                tempoAtual--;
                atualizarCronometro(id, tempoAtual);
            } else {
                cronometro = "";
                listaCronometros[id].emExecucao = false;
                emExecucao = false;
            }
        }, 1000);
    }
}
function parar(id){
    listaCronometros[id].emExecucao = false;
    clearInterval(listaCronometros[id].cronometro);
}
function zerar(id){
    let tempo = listaCronometros[id].tempo;
    parar(id);
    atualizarCronometro(id ,tempo);
}

function atualizarCronometro(id, tempoAtualizado){
    listaCronometros[id].tempoAtual = tempoAtualizado;
    $(`#${id} .tempo span`).html(tempoAtualizado);
}


function excluir(id) {
    listaCronometros[id].emExecucao = false;
    clearInterval(listaCronometros[id].cronometro);

    if (listaCronometros.hasOwnProperty(id)) {
        delete listaCronometros[id];
        $(`#${id}`).remove();
        console.log(`Cronômetro com id ${id} excluído.`);
    } else {
        console.log(`Cronômetro com id ${id} não encontrado.`);
    }
}

function editarNome(id){
    let span = $(`#${id} .titulo span`);
    let input = $(`#${id} .titulo input`);

    span.hide();
    input.show().val(span.text()).focus();

    input.keyup((e) => {
        if (e.key === 'Enter') {
            span.show();
            input.hide();
            listaCronometros[id].nome = input.val();
            span.text(input.val());
        }
    });
}

function editarTempo(id){
    let span = $(`#${id} .tempo span`);
    let input = $(`#${id} .tempo input`);

    span.hide();
    input.show().val(listaCronometros[id].tempo).focus();

    input.keyup((e) => {
        if (e.key === 'Enter') {
            span.show();
            input.hide();
            let novoTempo = parseInt(input.val());
            if (!isNaN(novoTempo)) {
                listaCronometros[id].tempo = novoTempo;
                listaCronometros[id].tempoAtual = novoTempo;
                atualizarCronometro(id, novoTempo);
            } else {
                // Tratar caso em que o valor inserido não é um número
                console.log("Por favor, insira um valor numérico válido.");
            }
        }
    });
}