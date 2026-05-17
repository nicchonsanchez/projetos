# projetos · app.nicchon.com

Repo-mãe do **app.nicchon.com**: vitrine consolidada de mini-apps, experimentos e estudos de Nicchon Sanchez, com identidade visual coerente com nicchon.com (v2026).

Substitui — e aposenta — `projetos.nicchon.com`.

---

## Estrutura

```
.
├── app/                    Shell do agregador (HTML + CSS + JS + API PHP)
│   ├── index.html          Home agregadora
│   ├── assets/
│   │   ├── style.css       Design tokens v2026 (pergaminho + bordô + azul-noite)
│   │   └── main.js         Fetch /api/projects.php + /api/links.php
│   ├── api/
│   │   ├── projects.php    Lista 3 fontes: mini-apps/, external-manifests/, e descobre órfãos no servidor
│   │   ├── links.php       CRUD MySQL de links externos (autenticado)
│   │   ├── links.php.template   Template com placeholders, injetado no deploy
│   │   └── schema.sql      DDL da tabela app_links
│   ├── external-manifests/ Declarações de SUB-DEPLOYS (outros repos entregam em /app.nicchon.com/{slug}/)
│   │   └── convite-naty.json
│   └── .htaccess           Headers de segurança + HTTPS forçado
│
├── mini-apps/              22 projetos embutidos (cada um com manifest.json)
│   ├── calculadora/
│   ├── jokenpo/
│   ├── ...
│   └── sorteador-de-numeros/
│
├── arquivo/                Projetos históricos preservados (NÃO entram no deploy)
│   ├── mikaelcayron/       Site antigo do sócio
│   ├── sanctius-co-2024/   Antigo empreendimento (snapshot 2024)
│   └── projeto-iptv-rikelmy/   Projeto de amigo, mantido como histórico
│
├── tarefas-md/             Planejamento da reformulação
└── .github/workflows/
    └── deploy-app-nicchon.yml   Build + FTP deploy automático em push
```

---

## Convenções

### `manifest.json` por mini-app

```json
{
  "slug": "jokenpo",
  "titulo": "Jokenpô",
  "categoria": "Jogos",
  "ano": "2023",
  "status": "Concluído",
  "descricao": "Pedra-papel-tesoura em JavaScript puro."
}
```

Campos:
- **slug**: kebab-case, deve casar com o nome da pasta.
- **categoria**: `Site` | `Sistema` | `Jogos` | `Estudo técnico` | `Marca`.
- **status**: `Em produção` | `Em desenvolvimento` | `Concluído` | `Arquivado`.
- **oculto** (opcional, default `false`): se `true`, não aparece no agregador.

Pra **ocultar** um projeto da home sem deletar:
```json
{ "oculto": true, "titulo": "...", ... }
```

### Pastas que NÃO são mini-apps

Coisa que **não tem `manifest.json`** é ignorada como mini-app pelo `projects.php`. Use isso pra rascunhos: crie a pasta, trabalhe, e adicione o manifest só quando estiver pronto.

---

## Os 3 tipos de "coisa" no app.nicchon.com

| Tipo | Onde mora o código | Onde declara | Onde aparece |
|---|---|---|---|
| **Mini-app embutido** (jokenpo, calculadora...) | `mini-apps/{slug}/` neste repo | `manifest.json` na própria pasta | `/{slug}/` no app, listado em "Projetos" |
| **Sub-deploy** (convite-naty, futuros) | outro repo, com workflow próprio que entrega em `/app.nicchon.com/{slug}/` no servidor | `app/external-manifests/{slug}.json` neste repo | mesma seção "Projetos", badge "Sub-deploy" |
| **Link externo** (Sanctius, KTask, GitHub...) | site/sistema em outro domínio | tabela MySQL `app_links` (admin via API) | seção "Links externos" |

### Quando criar um external-manifest

Cenário: o `convite-aniversario-naty` é projeto Next.js+PHP com workflow próprio (`SamKirkland/FTP-Deploy-Action`) que sobe direto pra `/app.nicchon.com/convite-naty/`. Esse código **não vive aqui** mas a URL aparece no app.nicchon.com.

Solução: `app/external-manifests/convite-naty.json` declara que o slug existe, com título/descrição/categoria. O `projects.php` mostra junto com os mini-apps embutidos.

```json
{
  "slug": "convite-naty",
  "titulo": "Convite — 15 anos da Natália",
  "categoria": "Site",
  "ano": "2026",
  "status": "Em produção",
  "descricao": "...",
  "url": "/convite-naty/",
  "repoUrl": "https://github.com/nicchonsanchez/convite-aniversario-naty",
  "deploy_proprio": true
}
```

### Auto-descoberta de órfãos

O `projects.php` faz uma 3ª passada varrendo pastas físicas em `/app.nicchon.com/` no servidor. Se achar uma pasta **sem manifest declarado** (nem em `mini-apps/` nem em `external-manifests/`), ela é incluída no agregador com:

- **Badge vermelha "Sem manifest"** no card
- **Style itálico + opacidade reduzida**
- **Header HTTP `X-App-Orphans: slug1, slug2`** pra monitoring

Isso impede que sub-deploys "invisíveis" sumam — você vê o card como aviso pra adicionar o manifest declarativo.

**Blacklist** (não considera órfã): `api`, `assets`, `external-manifests`, `redirects`, `arthur` (WP hospedado), `cgi-bin`, qualquer pasta começando com `_` ou `.`.

---

## Deploy

Push em `main` dispara o workflow `.github/workflows/deploy-app-nicchon.yml`:

1. Copia `app/` pra `dist/`
2. Achata cada `mini-apps/{slug}/` pra `dist/{slug}/`
3. Injeta secrets em `links.php.template` → `links.php`
4. Faz upload FTP pra `/app.nicchon.com/` no Hostgator

**Não deleta** `arthur/`, `convite-naty/` nem outros recursos preservados no servidor (deploy não tem `dangerous-clean-slate`).

### Secrets necessárias no repo

| Secret | Descrição |
|---|---|
| `FTP_HOST` | `ftp.nicchon.com` |
| `FTP_USER` | usuário do cPanel |
| `FTP_PASS` | senha FTP |
| `DB_HOST` | `localhost` |
| `DB_NAME` | `niccho25_portfolio` |
| `DB_USER` | usuário MySQL |
| `DB_PASS` | senha MySQL |
| `APP_LINKS_ADMIN_TOKEN` | bearer token pro CRUD de links |

---

## Banco

Tabela `app_links` no MySQL `niccho25_portfolio` (compartilhado com nicchon.com). Schema em [`app/api/schema.sql`](app/api/schema.sql).

Pra cadastrar um link externo novo via API:

```bash
curl -X POST https://app.nicchon.com/api/links.php \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"slug":"novo-link","titulo":"Novo","url":"https://exemplo.com","categoria":"Externo","ordem":100}'
```

Pra desativar:
```bash
curl -X DELETE "https://app.nicchon.com/api/links.php?slug=novo-link" \
  -H "Authorization: Bearer $TOKEN"
```

Painel admin via `nicchon.com/painel/links-app/` (a implementar — Fase 5 do plano).

---

## Identidade visual

Portada de [nicchon.com](https://nicchon.com) v2026 — paleta "Pergaminho + bordô + azul-noite":

| Token | Valor | Uso |
|---|---|---|
| `--color-bg` | `#F5F0E6` | Fundo |
| `--color-bordo` | `#5C1F1F` | CTAs, links, destaque |
| `--color-text` | `#1A2238` | Texto principal |

Tipografia: **Crimson Pro** (display serif) · **Inter** (sans) · **JetBrains Mono** (meta uppercase).

---

## Plano de implementação completo

Veja [`tarefas-md/app-nicchon-reformulacao.md`](tarefas-md/app-nicchon-reformulacao.md).
