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
| `DB_NAME` | `niccho25_app_nicchon` (banco isolado do ecossistema app.nicchon.com) |
| `DB_USER` | usuário MySQL do `niccho25_app_nicchon` |
| `DB_PASS` | senha MySQL |
| `APP_LINKS_ADMIN_TOKEN` | bearer token pro CRUD de links |

---

## Banco

Tabela `app_links` no MySQL **`niccho25_app_nicchon`** (banco isolado do ecossistema app.nicchon.com — separado do `niccho25_portfolio` que tem dados sensíveis do painel nicchon.com). Schema em [`app/api/schema.sql`](app/api/schema.sql).

Decisão de segurança (2026-05-18): SQL injection numa página do `app.nicchon.com` não atinge cofre/cartões do painel principal. Cada ecossistema tem banco próprio.

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

## Como gerenciar os projetos do app.nicchon.com

### Adicionar um mini-app NOVO

```bash
# 1. Criar a pasta e os arquivos
mkdir mini-apps/meu-novo-app
cd mini-apps/meu-novo-app

# 2. Adicionar o código (HTML/PHP/JS — sem rebuild)
# ... edita index.html, etc.

# 3. Criar o manifest.json (obrigatório pra aparecer no agregador)
cat > manifest.json <<'EOF'
{
  "slug": "meu-novo-app",
  "titulo": "Meu Novo App",
  "categoria": "Site",
  "ano": "2026",
  "status": "Em produção",
  "descricao": "Descrição curta do que esse app faz."
}
EOF

# 4. Commit + push
git add .
git commit -m "feat(meu-novo-app): adicionar mini-app"
git push
```

Em ~30s o workflow deploya e ele aparece em `https://app.nicchon.com/meu-novo-app/`.

### Ocultar um mini-app SEM deletar

Edita o `manifest.json` da pasta:

```json
{
  "oculto": true,
  "slug": "meu-app",
  "titulo": "..."
}
```

Commit + push. Pasta continua deployada mas não aparece no agregador.

Pra deletar de vez: `rm -rf mini-apps/meu-app && git commit && git push`.

### Adicionar um SUB-DEPLOY (projeto com workflow próprio em outro repo)

Cenário: você tem um repo separado (ex: `convite-aniversario-naty`) cujo CI/CD deploya direto em `/app.nicchon.com/{slug}/`. Pra ele aparecer no agregador, crie:

```bash
# Neste repo, não no repo do sub-deploy
cat > app/external-manifests/meu-sub-deploy.json <<'EOF'
{
  "slug": "meu-sub-deploy",
  "titulo": "Meu Sub-Deploy",
  "categoria": "Site",
  "ano": "2026",
  "status": "Em produção",
  "descricao": "...",
  "url": "/meu-sub-deploy/",
  "repoUrl": "https://github.com/nicchonsanchez/meu-sub-deploy",
  "deploy_proprio": true
}
EOF
git add app/external-manifests/meu-sub-deploy.json
git commit -m "feat: declarar sub-deploy meu-sub-deploy"
git push
```

**Esqueceu de declarar?** O agregador detecta automaticamente. Vai aparecer um card com badge vermelha **"Sem manifest"** e seu nome no header HTTP `X-App-Orphans`. Você fica sabendo antes de qualquer cliente reclamar.

### Adicionar/remover LINK EXTERNO (outros domínios)

Sem UI hoje. Via API com Bearer token:

```bash
# Cadastrar (ou atualizar — UPSERT por slug)
curl -X POST https://app.nicchon.com/api/links.php \
  -H "Authorization: Bearer $APP_LINKS_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "slug": "meu-novo-link",
    "titulo": "Meu Novo Link",
    "descricao": "...",
    "url": "https://outro-dominio.com",
    "categoria": "Externo",
    "ordem": 100
  }'

# Desativar (soft-delete, mantém histórico)
curl -X DELETE "https://app.nicchon.com/api/links.php?slug=meu-novo-link" \
  -H "Authorization: Bearer $APP_LINKS_ADMIN_TOKEN"
```

**Futuro:** página admin em `nicchon.com/painel/app-links/` com CRUD visual. Não foi feita ainda — quando virar dor, criamos.

### Renomear um mini-app (mudar slug)

```bash
git mv mini-apps/old-slug mini-apps/new-slug
# Editar manifest.json: "slug": "new-slug"
git commit && git push
```

⚠️ Vai quebrar links existentes apontando pra `/old-slug/`. Se for público, adicione um redirect no `app/.htaccess`:

```apache
RewriteRule ^old-slug(/.*)?$ /new-slug$1 [R=301,L]
```

---

## Plano de implementação completo

Veja [`tarefas-md/app-nicchon-reformulacao.md`](tarefas-md/app-nicchon-reformulacao.md).

