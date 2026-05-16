# Reformulação e consolidação do `app.nicchon.com`

**Status:** Em planejamento — 2026-05-16.
**Autor da decisão:** Nicchon (com curadoria Claude).
**Escopo principal:** unificar `app.nicchon.com` + `projetos.nicchon.com` numa única vitrine de mini-apps com identidade visual coerente com `nicchon.com`, alimentada por catálogo declarado + DB de links externos, deployada via GitHub Actions a partir de pasta canônica única no notebook.

---

## 1. Escopo

### O que entra

- Auditoria cruzada de 4 fontes (notebook em 3 pastas + GitHub `nicchonsanchez/*` + Hostgator FTP).
- Consolidação dos mini-apps numa **pasta canônica única** no notebook: `c:\xampp\htdocs\Nicchon - Pessoal\projetos\` (já é o clone git de `nicchonsanchez/projetos`).
- Padronização de slugs (kebab-case, sem espaços, sem typos como "tersoura").
- Adição de `manifest.json` por mini-app (slug, titulo, categoria, status, descricao, ano, autor).
- Trazer projetos órfãos (covil, gaby/*, calculo/*) pro repo canônico.
- Estabelecer atalhos no Windows pra que `app.nicchon.com/` e `projetos.nicchon.com/` no notebook ainda permitam abrir cada mini-app a partir do lugar antigo (apontando pra pasta canônica).
- Página agregadora moderna em `app.nicchon.com` aplicando identidade visual do `nicchon.com` (pergaminho + bordô + azul-noite, Crimson Pro + Inter).
- Sistema MySQL pra **links externos dinâmicos** (sem precisar criar pasta no servidor pra cada novo redirect).
- Painel de administração — TBD se entra dentro do `nicchon.com/painel/` já existente ou separado em `app.nicchon.com/painel/`.
- GitHub Actions: push em `main` → deploy automático pra `/public_html/app.nicchon.com/` (caminho remoto a confirmar com auditoria FTP).
- Aposentadoria de `projetos.nicchon.com`: `.htaccess` redirect 301 → `app.nicchon.com`.

### O que está fora

- Tocar no `nicchon.com` (Next.js estático). Ele é só **referência visual** — código não é alterado.
- Migrar mini-apps embutidos pra Next.js. Eles ficam como HTML/PHP/JS puro dentro de `app.nicchon.com/{slug}/`.
- Reorganizar o `nicchon.com/jogos/` (vem do repo `nicchonsanchez/jogos`, fluxo de deploy próprio).
- Mexer em outros subdomínios (`me.nicchon.com`, `quimica.nicchon.com`, `insta.nicchon.com`, `dev.nicchon.com`).

---

## 2. Inventário consolidado

### 2.1. Fontes mapeadas

- **N1** = `c:\xampp\htdocs\Nicchon - Pessoal\projetos\` — clone git do `nicchonsanchez/projetos` (ahead 1 commit + 1 untracked).
- **N2** = `c:\xampp\htdocs\Nicchon - Pessoal\app.nicchon.com\` — PHP standalone, sem git.
- **N3** = `c:\xampp\htdocs\Nicchon - Pessoal\projetos.nicchon.com\projetos\` — PHP standalone, sem git.
- **N4** = `c:\xampp\htdocs\Aulas\Desenvolvimento web\` — fonte-mestra de cursos (Danki, Rocketseat). Tem 4 projetos Rocketseat 2025 atualizados.
- **GH** = `github.com/nicchonsanchez/*` — 17 repos relevantes.
- **🟡 SRV** = Hostgator FTP — auditoria em andamento neste turno.

### 2.2. Mini-apps reais identificados

| # | Slug canônico (proposto) | Versão fonte | Repo GH | Observação |
|---|---|---|---|---|
| 1 | `calculadora` | A diff N1 vs N3 | `nicchonsanchez/projetos` | Conteúdo divergiu — precisa merge manual |
| 2 | `carrinho-de-compras` | N1 ≡ N2 (a confirmar) | `nicchonsanchez/projetos` | — |
| 3 | `cronometros` | N1 (única) | `nicchonsanchez/projetos` | Existe só no clone+GH |
| 4 | `volume-arvores-isoladas` | N2/calculo/ | (não tem repo próprio) | Sub-app de cálculo florestal |
| 5 | `volume-cilindro` | N2/calculo/ | (não tem repo próprio) | Sub-app de cálculo |
| 6 | `volume-pilha-de-lenha` | N2/calculo/ | (não tem repo próprio) | Sub-app de cálculo |
| 7 | `facebook-login` | **N1 (clone, com untracked)** | `nicchonsanchez/projetos` | Tem `formulario.html` untracked + commit unpushed |
| 8 | `jokenpo` | N1 ≡ N2 | `nicchonsanchez/projetos` | Renomear, corrigir typo "tersoura"→"tesoura" |
| 9 | `landing-esboco` | N1 (única) | `nicchonsanchez/projetos` | — |
| 10 | `productrunt` | N1 ≡ N3 (a confirmar) | `nicchonsanchez/projetos` | — |
| 11 | `projeto-bootstrap` | N1 (única) | `nicchonsanchez/projetos` | — |
| 12 | `projeto-materialize` | N1 (única) | `nicchonsanchez/projetos` | — |
| 13 | `login-signup` | A diff N1 vs N3 | `nicchonsanchez/projetos` | Conteúdo divergiu |
| 14 | `covil-dos-mestres` | N2 (única) | **sem repo** | Criar repo OU manter só dentro de `projetos` |
| 15 | `botoes-coloridos` | N2/gaby/ | **sem repo** | Idem |
| 16 | `sistemas-operacionais` | N2/gaby/ | **sem repo** | Idem |
| 17 | `formulario-matricula-rocketseat` | N4 (2025-06) | `projeto-formulario-de-matricula--rocketseat` | Repo próprio — clonar como subpasta |
| 18 | `formulario-convite-rocketseat` | N4 (2025-08) | `projeto-formulario-de-convite--rocketseat` | Idem |
| 19 | `lp-local-turistico-rocketseat` | N4 (2025-06) | `projeto-lp-local-turistico--rocketseat` | Idem |
| 20 | `sorteador-de-numeros` | N4 (2025-11) | `sorteador-de-numeros` | Idem |

**Total: 20 mini-apps embutidos**.

### 2.3. Não-projetos (links externos OU arquivar)

| Item | Origem | Decisão |
|---|---|---|
| `ecommerce-cosméticos` (redirect → sanctius.co) | N3 + servidor | **Link externo no DB** (categoria "Marcas") |
| `ecommerce-jóias` (redirect → emmunah.nicchon.com) | N3 + servidor | **Link externo no DB** |
| `projeto-iptv` | servidor | **Preservar como histórico** — projeto de amigo do Nicchon, manter na pasta `arquivo/` |
| `slider` (componente, não app) | N2 + servidor | **Arquivar** |
| `teste.html` (rascunho) | N2 | **Deletar** |
| `Nicchon - Pessoal/GitHUB/` (pasta abandonada) | notebook | **Arquivar** todo o diretório |

### 2.4. Extras do servidor (descobertos no mirror — 9 itens)

Clarificação do Nicchon (2026-05-16): **maioria é projeto de aula**.

| Item no servidor | Categoria | Decisão |
|---|---|---|
| `arthur/` (WordPress completo) | Site secundário hospedado em subpasta | **Manter intocado** no servidor — não é mini-app, não entra no app.nicchon.com novo |
| `convite-naty/` | Deploy do `convite-aniversario-naty` (Next próprio) | **Manter intocado** — tem workflow FTP próprio |
| `mikaelcayron` | Site antigo do sócio (Nicchon fez, ele provavelmente não usa) | **Preservar como histórico** em `arquivo/` |
| `sanctius.co` | Antigo empreendimento do Nicchon | **Preservar como histórico** em `arquivo/` |
| `danki-code-1`, `danki-code-2`, `projeto-03-prof` | Aulas Danki Code | **Arquivar** |
| `cena-construção` | Aula/template | **Arquivar** |
| `email-marketing` | Template/aula | **Arquivar** |
| `ecommerce-carros`, `venda-de-carros`, `odontologia` | Provavelmente freelas antigos ou aulas | **Arquivar** (a confirmar caso a caso quando o mirror terminar) |
| `sorteador-de-numeros` (servidor) | Deploy do repo `nicchonsanchez/sorteador-de-numeros` | **Substituir** pela versão atualizada do GH (mais nova que a deployada) |

**Política "arquivo histórico"**: pasta `arquivo/` na raiz do repo `nicchonsanchez/projetos`, NÃO entra no agregador `app.nicchon.com`, mas fica no git pra preservação. Pode ter README explicando contexto de cada um.

---

## 3. Estrutura final proposta

### 3.1. No notebook (pasta canônica)

```
Nicchon - Pessoal/projetos/                      ← clone git nicchonsanchez/projetos
  README.md                                       ← lista todos os mini-apps
  .github/workflows/
    deploy-app-nicchon.yml                        ← builda + sobe FTP /public_html/app.nicchon.com/
  app/                                            ← shell do agregador (index.html + assets)
    index.html                                    ← agregador estático com identidade nicchon.com v2026
    assets/
      style.css                                   ← design tokens portados (pergaminho+bordô+azul-noite)
      app.js                                      ← lê /manifests.json + /api/links.php (DB)
    api/                                          ← endpoints PHP pra DB MySQL
      links.php                                   ← GET (lista) + POST (cria, autenticado)
      _config.php.template                        ← secrets injetados no deploy
  mini-apps/                                      ← projetos embutidos
    calculadora/
      index.html
      manifest.json                               ← {slug, titulo, categoria, ano, descricao, autor}
    carrinho-de-compras/
      ...
    covil-dos-mestres/
      ...
    ... (18 outros)
  tarefas-md/
    app-nicchon-reformulacao.md                   ← este arquivo
```

### 3.2. Atalhos no notebook (Windows symlinks)

Pra manter o workflow histórico funcionando localmente:

```
app.nicchon.com/                                  ← vira pasta com atalhos pra mini-apps
  index.php                                       ← redireciona pra http://localhost/Nicchon%20-%20Pessoal/projetos/app/
  jokenpo → ..\projetos\mini-apps\jokenpo         (symlink)
  calculadora → ..\projetos\mini-apps\calculadora (symlink)
  ...
projetos.nicchon.com/                             ← vira só index.php redirect
  index.php                                       ← redireciona pra app.nicchon.com
```

### 3.3. No servidor Hostgator

```
/public_html/app.nicchon.com/                     ← gerado pelo GitHub Actions
  index.html                                      (do app/ na origem)
  assets/
  api/
    links.php
    _config.php                                   (com secrets injetados)
  jokenpo/                                        (mini-apps/{slug}/ achatados)
  calculadora/
  ... (mais 18)
  .htaccess                                       (headers de segurança + redirects)
/public_html/projetos.nicchon.com/
  .htaccess                                       (redirect 301 → app.nicchon.com)
```

### 3.4. Banco MySQL

Tabela única `app_links` no banco `niccho25_portfolio` (mesmo do nicchon.com/painel):

```sql
CREATE TABLE app_links (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(64) UNIQUE NOT NULL,
  titulo VARCHAR(120) NOT NULL,
  url TEXT NOT NULL,
  categoria VARCHAR(40) NOT NULL,        -- 'Externo', 'Marca', 'Subdomínio'
  descricao VARCHAR(240),
  cover_url TEXT,
  ordem INT NOT NULL DEFAULT 0,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

Frontend lê `/api/links.php` (público, só ativos), painel admin escreve (autenticado).

---

## 4. Etapas

### Fase 0 — Auditoria (em andamento)

- [x] Mapear notebook (N1, N2, N3, N4)
- [x] Mapear GitHub
- [ ] Auditar Hostgator via FTP (este turno)
- [ ] Diff item-por-item entre N1 e N3 onde divergem (Calculadora, login-signup)

### Fase 1 — Limpeza do clone git (não-destrutivo no GH)

- [ ] Stage + commit `Facebook login/formulario.html` untracked
- [ ] Push do commit `Atualizei a meta tag description...` + novo commit pro GitHub `main`
- [ ] Backup tarball do estado pré-reorganização em `c:\xampp\htdocs\Nicchon - Pessoal\.backups\projetos-pre-2026-05-16.tar.gz`

### Fase 2 — Reorganização em branch dedicada

- [ ] `git checkout -b reorganizacao-2026`
- [ ] Renomear todas as pastas pra kebab-case via `git mv` (usar truque case-insensitive do Windows)
- [ ] Criar `mini-apps/` e mover pastas pra dentro
- [ ] Trazer covil + gaby/* + calculo/* do N2 pro `mini-apps/`
- [ ] Trazer Rocketseat (4 projetos) — adicionar como subdiretórios normais (não submodules, pra manter deploy simples)
- [ ] Adicionar `manifest.json` em cada `mini-apps/{slug}/`
- [ ] Criar `app/index.html` + `app/assets/style.css` aplicando identidade v2026
- [ ] Criar `app/api/links.php` (CRUD + leitura pública) + template

### Fase 3 — Atalhos no notebook

- [ ] Apagar conteúdo redundante de `Nicchon - Pessoal/app.nicchon.com/` (mover pra `.backups/` antes)
- [ ] Recriar `app.nicchon.com/` com atalhos (junctions ou .lnk) apontando pra `projetos/mini-apps/{slug}/`
- [ ] Aplicar mesma estratégia em `projetos.nicchon.com/`

### Fase 4 — CI/CD

- [ ] Criar `.github/workflows/deploy-app-nicchon.yml`
- [ ] Adicionar secrets necessárias no repo `nicchonsanchez/projetos`:
  - `FTP_HOST`, `FTP_USER`, `FTP_PASS`
  - `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` (banco MySQL Hostgator)
  - `PAINEL_USER`, `PAINEL_SENHA_HASH` (autenticação do CRUD)
- [ ] Limpar pasta `/public_html/app.nicchon.com/` no servidor manualmente UMA vez
- [ ] Primeiro deploy via workflow
- [ ] Configurar `/public_html/projetos.nicchon.com/.htaccess` com redirect 301

### Fase 5 — Banco MySQL + painel

- [ ] Rodar `CREATE TABLE app_links` no banco `niccho25_portfolio`
- [ ] Implementar `app/api/links.php` (read público + write autenticado via session do painel nicchon.com)
- [ ] Adicionar tela `Gerenciar links do app.nicchon.com` em `nicchon.com/painel/` apontando pra mesma API

### Fase 6 — Merge e arquivamento

- [ ] PR `reorganizacao-2026` → `main` no `nicchonsanchez/projetos`
- [ ] Renomear repo de `projetos` → `app-nicchon-com` (com redirect automático do GitHub) — **decisão TBD**
- [ ] Arquivar `Nicchon - Pessoal/GitHUB/` movendo pra `.backups/`
- [ ] Apagar `ftp-creds-nicchon.txt` do Temp depois do primeiro deploy bem-sucedido

---

## 5. Critérios de aceite

- [ ] `https://app.nicchon.com` carrega com identidade v2026 (pergaminho + bordô + azul-noite, Crimson + Inter).
- [ ] Todos os 20 mini-apps abrem por `https://app.nicchon.com/{slug}/`.
- [ ] Lista de mini-apps na home é gerada a partir de `manifest.json` por pasta (não scan de filesystem cego).
- [ ] Links externos (Sanctius, Emmunah, etc.) aparecem na home, vindos do MySQL.
- [ ] Painel em `nicchon.com/painel/links-app/` permite CRUD de links externos sem mexer em arquivo no servidor.
- [ ] `https://projetos.nicchon.com/qualquer-coisa` redireciona pra `https://app.nicchon.com/qualquer-coisa` (ou raiz).
- [ ] Push em `main` do `nicchonsanchez/projetos` (ou novo nome) dispara deploy automático em <2min.
- [ ] Atalhos no notebook (`app.nicchon.com/jokenpo` etc.) abrem o mini-app correto via Explorer.
- [ ] `nicchonsanchez/projetos` (GitHub) não tem mais commits unpushed / arquivos untracked.

---

## 6. Riscos e decisões

### Riscos

- **Renomear pastas no Windows case-insensitive**: `git mv "Calculadora" "calculadora"` pode ser silenciosamente ignorado. Mitigação: rename em 2 passos (`Calculadora` → `_calc_tmp` → `calculadora`) + `git config core.ignorecase false`.
- **Quebrar URLs externas**: pessoas podem ter link `projetos.nicchon.com/projetos/calculadora` salvo. Mitigação: redirect 301 preserva path quando possível.
- **Renomear repo no GitHub** quebra clones de outras máquinas. Mitigação: deixar pra última fase, depois de estabilizar tudo. Renome no GitHub gera redirect automático.
- **Conflito do `formulario.html` untracked**: o arquivo foi salvo em 2024 mas ficou só local. Pode estar incompleto. Mitigação: olhar conteúdo antes de commitar.
- **Atalhos do Windows em rede compartilhada / outras máquinas**: symlinks não viajam bem. Mitigação: atalhos são só conveniência local, não dependência funcional.

### Decisões já tomadas

| Decisão | Escolha | Motivação |
|---|---|---|
| Stack do `app.nicchon.com` | Estático (HTML/CSS/JS) + PHP só pra API de links | Coerência com hosting Hostgator shared, alinhado com Sanctius/AYA/Painel Nicchon |
| Pasta canônica | `Nicchon - Pessoal/projetos/` | Já é o clone git ativo, evita criar mais um lugar |
| Repo destino | `nicchonsanchez/projetos` (mantém nome inicialmente) | Reorganiza dentro, decide rename depois |
| Mini-apps órfãos (covil, gaby) | Subpastas dentro do mesmo repo | Evita multiplicar repos pra projetos pequenos |
| Rocketseat | Subpastas normais (não submodules) | Mantém deploy simples, evita gerenciar 4 submodules |
| Identidade visual | Portar tokens do nicchon.com v2026 | Coerência de marca pessoal |
| Atalhos | Symlinks via PowerShell | `.lnk` clássicos não funcionam em todos os contextos |

### Decisões abertas (TBD)

- Renomear repo `nicchonsanchez/projetos` → `nicchonsanchez/app-nicchon-com`? Hoje o nome é vago.
- Mini-apps órfãos (covil, gaby) ganham repo próprio em algum momento? Talvez quando crescerem.
- Painel admin: dentro do `nicchon.com/painel/` ou separado em `app.nicchon.com/painel/`? Mais simples reusar o do nicchon.com.

---

## 7. Aposentadorias

Ao fim desse plano:

- ❌ `projetos.nicchon.com` (subdomínio) — vira só `.htaccess` redirect.
- ❌ `Nicchon - Pessoal/GitHUB/` (pasta abandonada) — arquivada em `.backups/`.
- ❌ `slider/`, `teste.html` em `app.nicchon.com` — apagados.
- ❌ `projeto-iptv/` — arquivado (não é do Nicchon).
- ❌ `index.php` com scan de filesystem em `app.nicchon.com` e `projetos.nicchon.com` — substituído por agregador estático + API.
