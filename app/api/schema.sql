-- app.nicchon.com — tabela de links externos
-- Banco: niccho25_portfolio (Hostgator)
-- Rodar uma vez via phpMyAdmin do cPanel ou via painel admin do nicchon.com.

CREATE TABLE IF NOT EXISTS app_links (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug         VARCHAR(64)  NOT NULL UNIQUE,
  titulo       VARCHAR(120) NOT NULL,
  descricao    VARCHAR(240) NOT NULL DEFAULT '',
  url          TEXT         NOT NULL,
  categoria    VARCHAR(40)  NOT NULL DEFAULT 'Externo',
  cover_url    TEXT         NULL,
  ordem        INT          NOT NULL DEFAULT 0,
  ativo        TINYINT(1)   NOT NULL DEFAULT 1,
  created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_ativo_ordem (ativo, ordem),
  INDEX idx_categoria (categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seeds iniciais (substitui os ex-redirects ecommerce-cosméticos e ecommerce-jóias)
INSERT INTO app_links (slug, titulo, descricao, url, categoria, ordem) VALUES
  ('sanctius',       'Sanctius',      'Vestuário sacro católico — produto da Creatyze.',         'https://usesanctius.com',         'Marca',     10),
  ('emmunah',        'Emmunah',       'Joalheria — subdomínio histórico.',                       'https://emmunah.nicchon.com',     'Marca',     20),
  ('creatyze',       'Creatyze',      'Sociedade de branding + sistemas com Mikael Cayron.',     'https://creatyze.com',            'Marca',     30),
  ('ktask',          'KTask',         'Sistema de gestão de tarefas para estúdios criativos.',   'https://ktask.agenciakharis.com.br', 'Sistema', 40),
  ('nicchon',        'nicchon.com',   'Portfólio e blog.',                                       'https://nicchon.com',             'Subdomínio', 50),
  ('github',         'GitHub',        'Repositórios do Nicchon.',                                'https://github.com/nicchonsanchez', 'Externo', 60)
ON DUPLICATE KEY UPDATE titulo=VALUES(titulo);
