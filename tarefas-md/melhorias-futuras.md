# Melhorias futuras — app.nicchon.com

Lista priorizada do que **podemos** fazer pra elevar o app. Avaliada honestamente em 2026-05-17, depois de entregar os fixes principais (1-5).

Os itens 1-5 já foram entregues:
- ✅ `cover_url` renderizado nos cards (imagem opcional via DB)
- ✅ Filtros por categoria (chips no topo do agregador)
- ✅ Página agregadora `calculos-florestais` agrupando os 3 volumes
- ✅ Convite-naty validado (Next.js OK, fonts custom)
- ✅ 404 customizado com identidade v2026

---

## 🔥 ALTA PRIORIDADE — fazer logo após terminar o LabClock

### 0. Auditoria funcional dos projetos internos do app.nicchon.com

**Por que é prioritário:** já descobrimos 4 mini-apps quebrados em testes ad-hoc (calculadora simples deletada, cronômetros sem CSS, cronômetros com onclick CSP-bloqueado, jokenpo + sorteador com mesma classe de bug). **Outros podem estar quebrados em silêncio.** Cada mini-app é um cartão público do portfólio — um quebrado destrói a percepção geral.

**Objetivo:** garantir que **TODOS** os mini-apps embutidos + sub-deploys do `app.nicchon.com` funcionam.

**Escopo:**

Pra cada uma das **19 entradas visíveis** no agregador + **1 sub-deploy** (`convite-naty`):

1. **Abrir no browser** em desktop e mobile
2. **Testar interação real** (não só HTTP 200): clicar nos botões, preencher forms, verificar resultados
3. **Olhar Console DevTools** (F12) — qualquer erro CSP, 404, JS exception, mixed content
4. **Documentar status** em planilha simples (slug | funciona? | nota)

**Output esperado:** lista clara de:
- 🟢 Funcionando
- 🟡 Funciona mas com bug visual/UX menor
- 🔴 Quebrado (não funciona ou erro grave)

**Padrões de bug a procurar (baseados nos que já encontrei):**
- `onclick=` inline → CSP bloqueia silenciosamente
- jQuery/FontAwesome via CDN externo → bloqueado por `script-src 'self'`
- CSS apontando pra arquivo inexistente (`../css/style.css` de hierarquia antiga)
- Imagens com URL absoluta `http://` em vez de relativa
- `theme-color` ainda apontando pra cor antiga (`#061e4e`, `#262626`)
- Texto/identidade visual fora do padrão v2026 (Open Sans em vez de Inter, fundos escuros antigos)

**Mini-apps a verificar (lista atual):**

| Slug | Tipo | Última atenção |
|---|---|---|
| `botoes-coloridos` | Estudo | Nunca testei após reorg |
| `calculos-florestais` | Hub | ✅ Testado e reestilizado |
| `carrinho-de-compras` | Estudo | Nunca testei |
| `covil-dos-mestres` | Site | Nunca testei (usa jQuery + jpgs) |
| `cronometros` | Estudo | ✅ Refatorado fase 2026-05-17 |
| `facebook-login` | Estudo | Nunca testei |
| `formulario-convite-rocketseat` | Estudo | Nunca testei (Rocketseat 2025) |
| `formulario-matricula-rocketseat` | Estudo | Nunca testei |
| `jokenpo` | Jogos | ✅ Corrigido onclick CSP |
| `landing-esboco` | Estudo | Nunca testei |
| `login-signup` | Estudo | Nunca testei |
| `lp-local-turistico-rocketseat` | Site | Nunca testei |
| `odontologia` | Site | Nunca testei (PHP completo) |
| `productrunt` | Site | Nunca testei depois reestilo |
| `projeto-bootstrap` | Estudo | Nunca testei |
| `projeto-materialize` | Estudo | Nunca testei |
| `sistemas-operacionais` | Estudo | Nunca testei |
| `sorteador-de-numeros` | Jogos | ✅ Corrigido onclick CSP |
| `venda-de-carros` | Site | Nunca testei (PHP completo) |
| `convite-naty` (sub-deploy) | Site | ✅ HTTP 200 + title checado |
| Cálculos individuais (3 volumes ocultos) | Estudo | ✅ Reestilizados + dark mode |

**~15 itens nunca testados em interação real.** Alta probabilidade de pelo menos 3-5 estarem com algum bug similar.

**Plano sugerido pra atacar:**

1. **Sweep automatizado**: script Python varre os HTMLs deployados + grep por padrões problemáticos (`onclick=`, `cdn.jsdelivr`, `cdnjs.cloudflare`, `googleapis.com/ajax/libs`, `http://` em assets, `#262626` em `theme-color`, fontes Open Sans, etc.)
2. **Lista priorizada de fixes** baseada no sweep
3. **Atacar em lote** (1 dia focado): fixes pequenos rápidos primeiro, casos complexos depois
4. **Decisão sobre legados sem valor**: alguns mini-apps são exercícios triviais de 2024 (botoes-coloridos, sistemas-operacionais). Se não vale consertar, decidir entre arquivar OU manter como "Arquivado" com badge claro.

**Esforço:** 1 dia focado (4-6h). Vale o investimento — portfólio é cara da casa.

---

## Pendências priorizadas

### Polish visual (pega depois)

#### 6. OG image por mini-app

**Problema:** compartilhar `app.nicchon.com/jokenpo` no WhatsApp mostra OG genérico do agregador. Cada mini-app não tem `og:image` próprio.

**Solução:** se `manifest.json` tem `cover_url`, render no `og:image` da página individual. Pra mini-apps sem cover, gerar dinamicamente via PHP com slug+titulo.

**Esforço:** 1h.

#### 7. Footer "última atualização" mostrando data real

**Problema:** hoje pega `atualizado_em` do primeiro projeto da lista — meio mentiroso. Pode mostrar "21 de Janeiro" quando o site foi atualizado ontem.

**Solução:** o workflow injeta `BUILD_DATE` numa env do `projects.php` no momento do deploy. JS renderiza essa data.

**Esforço:** 20min.

#### 8. `aria-pressed` dinâmico no theme-toggle

**Problema:** screen reader lê "alternar tema" sempre, sem dizer o estado atual.

**Solução:** após toggle, setar `aria-pressed="true"` em dark e `"false"` em light. Label muda também: "Tema atual: claro" / "Tema atual: escuro".

**Esforço:** 10min.

#### 9. Diferenciar categoria por cor de borda

**Problema:** todos os cards são iguais visualmente. Categoria só aparece como texto pequeno.

**Solução:** borda esquerda colorida sutil por categoria (sub-deploy já tem barra bordô — extender lógica):
- Site: borda azul-noite
- Sistema: borda bordô-soft
- Jogos: borda verde-musgo
- Estudo técnico: borda dim (cinza)

Cores discretas, ainda na paleta v2026. Não vira pixel-art.

**Esforço:** 30min (incluindo decidir paleta).

### Higiene técnica

#### 10. CSRF token no painel `app-links.php`

**Problema:** POST/DELETE sem token de origem. Se Nicchon estiver logado e visitar site malicioso que faça `<form action="https://nicchon.com/painel/app-links.php" method="post">...`, pode criar/apagar link sem perceber.

**Risco:** baixo (precisa estar logado), mas é fraqueza real e o painel já tem outras camadas de segurança.

**Solução:** gerar token na sessão, adicionar `<input type="hidden" name="_csrf" value="...">` no form, validar em todo POST/DELETE com `hash_equals`.

**Esforço:** 30min.

#### 11. Cache de 5min no `projects.php`

**Problema:** cada request faz `scandir()` em 2 diretórios + `is_dir()` + ler N `manifest.json`. Hoje OK pra 22 items. Em 200 items começa a doer.

**Solução:** cachear o resultado em arquivo (`/tmp/projects.cache.json`) com TTL de 5min. Invalidar manualmente se precisar.

**Esforço:** 30min.

#### 12. Investigar vulnerabilidade lodash (Dependabot)

**Problema:** Dependabot reportou vulnerabilidade em lodash. Eu nunca olhei.

**Plano:** olhar qual repo tem o aviso, qual pacote depende de lodash, se é dependência de build (devDep — risco baixo) ou de runtime, qual CVE. Decidir se atualizar ou ignorar.

**Esforço:** 15min pra triagem, mais 30min se precisar atualizar e testar.

#### 13. Smoke test automatizado pós-deploy

**Problema:** workflow termina como "success" mesmo se a página estiver retornando 500 ou HTML vazio. O FTP deploy só verifica conexão FTP, não a renderização.

**Solução:** após "Deploy via FTP", adicionar step `curl --fail https://app.nicchon.com/api/projects.php | jq 'length >= 22'` que falha se algo não bate. Mesmo pro convite-naty: `curl --fail https://app.nicchon.com/convite-naty/ -o /dev/null`.

**Esforço:** 20min.

#### 14. Lighthouse score

**Problema:** carrega 3 famílias de Google Fonts (Crimson Pro, Inter, JetBrains Mono). Provavelmente Lighthouse mobile fica em 70-80.

**Solução:**
- Adicionar `preload` no font principal (Inter)
- Reduzir pesos: hoje Crimson Pro carrega 400+500+600+700 — só precisa 500+600. Inter idem.
- Self-host as fonts (download woff2, servir do mesmo domínio)
- Adicionar `font-display: swap` se ainda não tem

**Esforço:** 1h-1h30 (download fonts é o mais demorado).

#### 16. Painel híbrido de gerenciamento dos mini-apps embutidos

**Problema:** hoje pra mudar ordem, título, descrição ou cover de um mini-app, é preciso editar `mini-apps/{slug}/manifest.json` + push. Fricção pequena com 22 items, mas vira incômodo se a coleção crescer.

**Não-solução (B no chat 2026-05-17):** migrar tudo pro DB. Cria armadilha de divergência (deletar pasta = mini-app fantasma; adicionar via Git não aparece).

**Solução:** painel `/painel/app-meta.php` com modelo **híbrido**.
- Manifest do Git continua sendo a **fonte base** (slug, URL imutável).
- Tabela MySQL `app_meta` permite **override opcional** de campos editáveis (ordem, descricao_alt, cover_url, oculto).
- `projects.php` faz merge: lê manifest → aplica overrides do DB se existir pro slug → retorna.
- Fallback gracioso: se DB falhar, app ainda funciona com valores do manifest puro.

**Schema sugerido:**
```sql
CREATE TABLE app_meta (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(64) NOT NULL UNIQUE,
  ordem INT NULL,
  titulo_alt VARCHAR(120) NULL,
  descricao_alt VARCHAR(240) NULL,
  cover_url TEXT NULL,
  oculto TINYINT(1) NULL,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Quando fazer:** quando o app passar de ~50 mini-apps OU quando precisar delegar admin pra outra pessoa OU quando frequência de ajuste de ordem/descrição passar de 1x/semana.

**Esforço:** ~2h (tabela + página painel + lógica de merge no `projects.php`).

---

#### 15. Atualizar `tarefas-md/app-nicchon-reformulacao.md`

**Problema:** doc viva, mas o que documentei lá divergiu do que entregamos (cover_url, filtros, hub florestais, etc. não estavam no plano).

**Solução:** revisar o tarefas-md/, marcar o que ficou pra trás como "decidido em runtime", adicionar a seção "Implementado de fato".

**Esforço:** 30min.

---

## Como fazer (uma sugestão)

Em blocos de ~1h-1h30:

- **Bloco "Polish visual"**: 6 + 9 (~1h30)
- **Bloco "A11y + UX detalhes"**: 7 + 8 (~30min)
- **Bloco "Segurança"**: 10 + 12 (~45min)
- **Bloco "Performance"**: 11 + 14 (~2h)
- **Bloco "Operação"**: 13 + 15 (~50min)

Total estimado: ~6h pra zerar tudo. Não tem ordem obrigatória — pega o que estiver te incomodando mais.
