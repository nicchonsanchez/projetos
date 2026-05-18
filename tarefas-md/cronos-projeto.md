# Cronos — Central de cronômetros multi-dispositivo

**Status:** Em planejamento — 2026-05-18.
**Codename:** `cronos` (a definir nome final).
**Origem:** dor real vivida pelo Nicchon como técnico químico. Hoje é projeto pessoal/portfólio — se laboratórios reais quiserem adotar, melhor.

---

## 1. Visão

Plataforma web pra criar, monitorar e compartilhar cronômetros entre múltiplos dispositivos, organizados por **sala**, **grupo** e **dono**. Pensado pra laboratórios químicos (ensaios com tempos críticos), mas aplicável a cozinha profissional, salões de beleza (química capilar), brewery artesanal, qualquer contexto multi-timing.

### Caso de uso real (motivador)

Nicchon é técnico em laboratório. Tem 3 ensaios paralelos em 3 salas diferentes (aquecimento na sala 1, repouso na sala 2, titulação na sala 3). Hoje precisaria:
- Lembrar dos 3 tempos
- Estar fisicamente perto pra ouvir o alarme
- Não pode sair pra outra coisa

Com o Cronos:
- Cria 3 cronômetros (1 por sala), cada um numa **TV da sala** correspondente
- Cria grupo "**Cron do Nicchon**" agregando os 3
- Abre esse grupo no **celular** — vê os 3 timers ao vivo, em qualquer lugar
- Outro técnico pode "trazer" o grupo da sala 1 pra TV da sala 2 (decisão dele)
- Quando termina, beep toca **na TV da sala** + **notification push** no celular do dono

---

## 2. Conceitos

| Entidade | Resumo | Exemplo |
|---|---|---|
| **Lab (tenant)** | Laboratório como unidade isolada | "Lab Reritiba" |
| **Usuário** | Técnico, admin do lab | "Nicchon", "Maria", "João" |
| **Sala** | Espaço físico (não obrigatório) | "Sala 1 — Aquecimento" |
| **Cronômetro** | Timer com ID único, dono, tempo | "Aquecimento sódio — 15min — dono: Nicchon" |
| **Grupo** | Coleção arbitrária de cronômetros (M:N) | "Cron do Nicchon" agrega 8 cronômetros espalhados |
| **Sessão de exibição** | Tela (TV ou celular) configurada pra exibir 1+ grupos | "TV Sala 1" exibe grupo "Sala 1" + grupo "Cron do Nicchon" |
| **Audit log** | Histórico de ações sobre cronômetros | "Maria iniciou cron-X em 14:32" |

### Relações importantes

- Cronômetro tem **1 dono** + **1 sala** (opcional)
- Cronômetro pode estar em **N grupos** (M:N)
- Sessão exibe **N grupos** (M:N)
- Audit log: cada ação (start/pause/reset/edit) gera entrada com `usuario_id`, `cronometro_id`, `acao`, `timestamp`

### Estado de um cronômetro

```
PARADO    → criado, nunca iniciou
RODANDO   → conta regressiva ativa
PAUSADO   → tempo congelado, pode retomar
ATRASADO  → contagem passou de 0 (rodando em negativo)
```

Transições válidas: `PARADO ↔ RODANDO`, `RODANDO → PAUSADO ↔ RODANDO`, `RODANDO → ATRASADO`, qualquer → `PARADO` (via reset).

---

## 3. Stack (constraint: Hostgator shared)

| Camada | Tech | Justificativa |
|---|---|---|
| **API** | PHP 8 + PDO + MySQL 8 | Funciona em shared hosting. Padrão dos outros projetos do Nicchon (Sanctius, AYA, Painel Nicchon). |
| **Real-time** | Polling HTTP curto (2-3s) + cálculo client-side | WebSocket não roda em PHP shared. Polling determinístico cobre o caso. |
| **Frontend** | HTML + CSS + jQuery + Vanilla JS | Coerente com app.nicchon.com. Sem build step. |
| **Frontend (TV)** | Mesma SPA, rota `/tv/:slug` com layout full-screen | Reusa código, sem app nativo |
| **Auth** | PHP sessions + cookies HttpOnly + SameSite | Padrão do nicchon.com/painel. JWT desnecessário. |
| **Beep mobile bg** | Service Worker + Web Push (VAPID) | Pra notificar quando celular está com tela bloqueada |
| **Persistência local** | localStorage pra cache + reidratação após F5 | Continua funcionando offline pra leitura |
| **Hosting** | Hostgator shared | Subdomínio `cron.nicchon.com` (ou outro) |
| **DB** | Compartilhado ou isolado | A decidir: usar `niccho25_portfolio` existente ou criar `niccho25_cronos` |

### Por que polling funciona

**Chave conceitual:** o cronômetro é **determinístico**. Dado `status=RODANDO` + `started_at=T0` + `duracao=D`, qualquer cliente calcula o tempo restante localmente como:

```js
tempoRestante = D - (Date.now() - T0)
```

Sem drift de `setInterval`, sem precisar de servidor pra "dizer" cada segundo. O servidor só guarda o **estado autoritativo** (status + timestamps). Cliente puxa esse estado a cada 2-3s (polling) e detecta mudanças (alguém pausou, alguém resetou). Entre polls, conta sozinho.

**Latência aceitável:** 2-3s pra propagar uma ação manual (pause/reset) entre dispositivos é OK pra contexto laboratório — não é videogame online.

**Otimização:** endpoint de polling aceita `?since=TIMESTAMP` e retorna só o que mudou desde então (`If-Modified-Since` ETag-style). Reduz banda.

---

## 4. Modelo de dados (SQL)

```sql
CREATE TABLE labs (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug          VARCHAR(64) UNIQUE NOT NULL,
  nome          VARCHAR(120) NOT NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE usuarios (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lab_id        INT UNSIGNED NOT NULL,
  email         VARCHAR(120) NOT NULL,
  senha_hash    VARCHAR(255) NOT NULL,
  nome          VARCHAR(120) NOT NULL,
  papel         ENUM('tecnico', 'admin') NOT NULL DEFAULT 'tecnico',
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_email_lab (lab_id, email),
  FOREIGN KEY (lab_id) REFERENCES labs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE salas (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lab_id        INT UNSIGNED NOT NULL,
  nome          VARCHAR(80) NOT NULL,
  ordem         INT NOT NULL DEFAULT 0,
  FOREIGN KEY (lab_id) REFERENCES labs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE cronometros (
  id                    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lab_id                INT UNSIGNED NOT NULL,
  sala_id               INT UNSIGNED NULL,
  dono_id               INT UNSIGNED NOT NULL,
  slug                  VARCHAR(12) UNIQUE NOT NULL,   -- /c/abc12xy (URL curta)
  nome                  VARCHAR(120) NOT NULL,
  duracao_ms            INT NOT NULL,
  status                ENUM('PARADO','RODANDO','PAUSADO') NOT NULL DEFAULT 'PARADO',
  started_at            TIMESTAMP(3) NULL,             -- quando iniciou (precisão ms)
  paused_remaining_ms   INT NULL,                       -- restante quando pausou
  created_at            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_lab (lab_id),
  INDEX idx_dono (dono_id),
  INDEX idx_sala (sala_id),
  INDEX idx_updated (updated_at),
  FOREIGN KEY (lab_id)  REFERENCES labs(id) ON DELETE CASCADE,
  FOREIGN KEY (sala_id) REFERENCES salas(id) ON DELETE SET NULL,
  FOREIGN KEY (dono_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE grupos (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lab_id        INT UNSIGNED NOT NULL,
  dono_id       INT UNSIGNED NOT NULL,
  slug          VARCHAR(12) UNIQUE NOT NULL,
  nome          VARCHAR(120) NOT NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (lab_id)  REFERENCES labs(id) ON DELETE CASCADE,
  FOREIGN KEY (dono_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE grupo_cronometros (
  grupo_id        INT UNSIGNED NOT NULL,
  cronometro_id   INT UNSIGNED NOT NULL,
  ordem           INT NOT NULL DEFAULT 0,
  PRIMARY KEY (grupo_id, cronometro_id),
  FOREIGN KEY (grupo_id)      REFERENCES grupos(id) ON DELETE CASCADE,
  FOREIGN KEY (cronometro_id) REFERENCES cronometros(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE sessoes (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lab_id        INT UNSIGNED NOT NULL,
  dono_id       INT UNSIGNED NOT NULL,
  slug          VARCHAR(12) UNIQUE NOT NULL,    -- /tv/abc12xy
  nome          VARCHAR(80) NOT NULL,           -- "TV Sala 1"
  modo          ENUM('tv','celular') NOT NULL DEFAULT 'celular',
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (lab_id) REFERENCES labs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE sessao_grupos (
  sessao_id   INT UNSIGNED NOT NULL,
  grupo_id    INT UNSIGNED NOT NULL,
  ordem       INT NOT NULL DEFAULT 0,
  PRIMARY KEY (sessao_id, grupo_id),
  FOREIGN KEY (sessao_id) REFERENCES sessoes(id) ON DELETE CASCADE,
  FOREIGN KEY (grupo_id)  REFERENCES grupos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE auditoria (
  id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cronometro_id   INT UNSIGNED NOT NULL,
  usuario_id      INT UNSIGNED NOT NULL,
  acao            ENUM('CRIAR','INICIAR','PAUSAR','RESETAR','EDITAR','EXCLUIR') NOT NULL,
  payload         JSON NULL,
  timestamp       DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  INDEX idx_cron_ts (cronometro_id, timestamp),
  FOREIGN KEY (cronometro_id) REFERENCES cronometros(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE push_subscriptions (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id        INT UNSIGNED NOT NULL,
  endpoint          TEXT NOT NULL,
  p256dh            VARCHAR(255) NOT NULL,
  auth_key          VARCHAR(255) NOT NULL,
  user_agent        VARCHAR(255) NULL,
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_endpoint (endpoint(255)),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 5. API REST (PHP endpoints)

Todos protegidos por session (exceto `/auth/*`). Multi-tenant via `lab_id` no usuário logado.

### Cronômetros

```
POST   /api/cronometros                # criar
GET    /api/cronometros                # listar (filtros: dono_id, sala_id, status)
GET    /api/cronometros/{slug}         # detalhe + estado atual
PATCH  /api/cronometros/{slug}/start   # iniciar (sets started_at = NOW, status RODANDO)
PATCH  /api/cronometros/{slug}/pause   # pausar (calcula paused_remaining_ms)
PATCH  /api/cronometros/{slug}/reset   # resetar (status PARADO, started_at NULL)
PATCH  /api/cronometros/{slug}         # editar nome/duracao/sala
DELETE /api/cronometros/{slug}         # remover
```

### Grupos

```
POST   /api/grupos
GET    /api/grupos                     # meus + grupos onde tenho cronometro
GET    /api/grupos/{slug}              # com cronometros e estados
POST   /api/grupos/{slug}/cronometros  # adicionar cronometro ao grupo
DELETE /api/grupos/{slug}/cronometros/{cron_slug}  # remover
```

### Sessões (TV / celular)

```
POST   /api/sessoes
GET    /api/sessoes/{slug}             # detalhe + grupos + cronometros + estados
POST   /api/sessoes/{slug}/grupos      # adicionar grupo à sessão
DELETE /api/sessoes/{slug}/grupos/{grupo_slug}
```

### Polling (endpoint chave)

```
GET    /api/sessoes/{slug}/poll?since={timestamp_ms}
```

Retorna:
```json
{
  "server_time": 1716054432123,
  "cronometros": [
    {
      "slug": "abc12",
      "nome": "Aquecimento",
      "status": "RODANDO",
      "started_at_ms": 1716054372000,
      "duracao_ms": 900000,
      "paused_remaining_ms": null,
      "dono_nome": "Nicchon",
      "updated_at_ms": 1716054372000
    },
    ...
  ]
}
```

Cliente compara `server_time` com `Date.now()` pra calcular offset de clock e ajustar. Polling rodando em 2-3s.

### Push notifications

```
POST   /api/push/subscribe             # registra endpoint VAPID do navegador
DELETE /api/push/unsubscribe
```

Quando cronômetro chega a 0, servidor (via cron de 1 min OU disparo em ação relevante) envia push pros donos cujo cronômetro acabou.

---

## 6. Fluxos críticos

### Compartilhar cronômetro

1. Técnico cria cronômetro em `/api/cronometros` — recebe `slug` de 8 caracteres
2. URL pública: `https://cron.nicchon.com/c/abc12xy`
3. Qualquer dispositivo abre essa URL → entra no polling do estado dele
4. Sem precisar de login pra **ver** (read-only). Pra **editar**, precisa logar e ser dono ou admin do lab.

### TV "puxa" grupo de outra sala

1. TV da Sala 2 está exibindo grupo "Sala 2"
2. Técnico abre painel admin no celular, edita a sessão "TV Sala 2"
3. Adiciona o grupo "Sala 1" via `POST /api/sessoes/{slug}/grupos`
4. No próximo poll (2-3s), a TV recebe atualização: agora exibe Sala 1 + Sala 2

### QR Code de pareamento

1. TV mostra QR code com URL da sessão (`/tv/abc12xy`)
2. Técnico aponta celular → abre a mesma sessão no celular
3. Agora celular acompanha o que a TV exibe (mas pode logar pra interagir)

### Beep + Push em background

1. Cronômetro chega a 0
2. Cliente (que está com tela aberta): toca Web Audio beep + flash visual
3. Cliente (com tela bloqueada / outra aba): Service Worker recebe push notification (cron PHP detecta cronômetros que acabaram nos últimos 60s e dispara push pros donos)
4. Trade-off: latência de até 60s no push background. Aceitável.

---

## 7. UI/UX por modo

### Modo Técnico (celular ou desktop)

- Hub com **"meus cronômetros"** + **"meus grupos"**
- Botão "+ Criar cronômetro" e "+ Criar grupo"
- Lista de cronômetros do lab inteiro (pode filtrar)
- Click no cronômetro → tela individual com controles iniciar/pausar/resetar

### Modo TV

- Rota `/tv/{slug}` em **full-screen** (CSS `:fullscreen`, `width: 100vw; height: 100vh`)
- Grid grande de cronômetros (3-4 colunas, números gigantes)
- Sem header, sem nav — só conteúdo
- QR code no canto pra pareamento
- Mantém tela ligada via Wake Lock API (se disponível)

### Modo Admin

- Painel pra gerenciar usuários, salas, grupos
- Audit log buscável
- Configurações do lab (nome, fuso horário, etc.)

---

## 8. Roadmap em 7 fases

Cada fase entrega valor sozinha. Pode parar em qualquer uma se mudar de prioridade.

| Fase | Escopo | Esforço | Valor entregue |
|---|---|---|---|
| **0. MVP atual** | Já existe em `app.nicchon.com/cronometros/` | ✅ pronto | 1 dispositivo, sem sync, sem multi-user |
| **1. Backend PHP+MySQL básico** | Schema do banco, CRUD cronômetros, endpoint polling sem auth | ~1 semana | Cronômetro acessível por URL `/c/{slug}` |
| **2. Multi-device sync** | Frontend polling 2-3s, cálculo determinístico client-side, mantém tudo em sync | ~3 dias | Compartilhar cronômetro entre TV + celular |
| **3. Auth + ownership** | Login session PHP, cadastro de lab, papel admin/técnico | ~3 dias | Time pode usar sem confusão de quem-fez-o-que |
| **4. Grupos** | M:N entre grupo e cronômetros, URL `/g/{slug}` compartilhável | ~1 semana | Caso "Cron do Nicchon" resolve |
| **5. Sessões + TV mode** | Rota `/tv/{slug}` full-screen, multi-grupos, QR code, Wake Lock | ~1 semana | TV vira central de monitoramento |
| **6. Push + Audit log** | Web Push (VAPID) quando cronômetro acaba, audit log buscável | ~5 dias | Notificação background + transparência total |
| **7. Multi-tenant + landing** | Cada lab isolado (já estará no DB desde fase 1), página de cadastro, marketing site mínimo | ~1 semana | Pronto pra mostrar/vender pra labs externos |

**Total fases 1-6 (uso interno completo): ~5 semanas focado.**
**Fase 7 (comercial): +1 semana.**

---

## 9. Decisões abertas

| # | Decisão | Opções |
|---|---|---|
| D1 | Nome do produto | "Cronos", "Tikker", "LabClock", "ChemTimer", "Tempero", outro |
| D2 | Domínio | `cron.nicchon.com` / `cronos.nicchon.com` / domínio próprio (`cronos.tools`?) |
| D3 | Banco MySQL | Compartilhar `niccho25_portfolio` (mesmo do app.nicchon.com) ou criar `niccho25_cronos` isolado |
| D4 | Repo GitHub | `nicchonsanchez/cronos` (novo repo) ou subpasta do `nicchonsanchez/projetos` |
| D5 | Auth: SSO com painel nicchon.com? | Stand-alone (login próprio) ou compartilhar session do painel principal |
| D6 | Listar como projeto no portfólio nicchon.com | Sim desde início (mesmo MVP), ou só quando entrar fase 3+ |
| D7 | Frontend: SPA ou multi-page PHP? | SPA jQuery (igual MVP) ou PHP renderiza páginas + jQuery pra interação |

---

## 10. Diferenciais técnicos pro portfólio

Coisas que vão aparecer como "decisões interessantes" no case study:

- **Polling determinístico + cálculo client-side**: servidor só guarda estado, cliente calcula o display. Funciona em PHP shared sem WebSocket.
- **Multi-tenant via lab_id desde o dia 1**: arquitetura escala pra SaaS sem refactor.
- **Wake Lock API**: TV não desliga sozinha enquanto exibe cronômetros.
- **Web Push (VAPID) com PHP**: alerta cronômetro acabou mesmo com tela bloqueada — coisa rara em hosting shared.
- **URL curta com slug de 8 chars**: shareability sem precisar UUID feio.
- **Audit log persistente**: rastreabilidade total pra contexto laboratorial onde quem-fez-o-que importa.

---

## 11. Critérios de aceite (por fase)

### Fase 1 (Backend MySQL básico)

- [ ] Schema criado no MySQL
- [ ] `POST /api/cronometros` cria cronômetro retornando slug
- [ ] `PATCH /api/cronometros/{slug}/start` inicia, marca `started_at`
- [ ] `GET /api/cronometros/{slug}` retorna estado calculado correto
- [ ] Sem auth nessa fase (próxima fase implementa)

### Fase 2 (Sync)

- [ ] Frontend `/c/{slug}` abre em qualquer dispositivo, mostra cronômetro
- [ ] 2 dispositivos no mesmo cronômetro vêem o mesmo tempo (latência <3s pra ações manuais)
- [ ] Refresh F5 mantém o estado (puxa do servidor, não localStorage)
- [ ] Beep dispara local quando tempo bate 0

### Fase 3 (Auth)

- [ ] Cadastro de lab + admin inicial
- [ ] Login técnicos (criados pelo admin)
- [ ] Cronômetros tem dono explícito
- [ ] Cronômetro só edita por dono ou admin do lab

### Demais fases — critérios serão detalhados em sub-arquivos `tarefas-md/cronos-fase-X.md` quando começarmos cada uma.
