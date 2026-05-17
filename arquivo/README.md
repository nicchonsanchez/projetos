# arquivo/

Projetos preservados como **histórico**. **Não entram** no deploy do `app.nicchon.com`.

Foram preservados no repo por valor afetivo, contexto histórico ou porque o código pertence a terceiros e merece registro.

## Conteúdo

### `mikaelcayron/`
Site antigo do **Mikael Cayron** (sócio na Creatyze). Construído por Nicchon Sanchez. Atualmente sem uso conhecido — preservado para referência.

### `sanctius-co-2024/`
Snapshot 2024 do antigo empreendimento **Sanctius** (cosméticos / produtos capilares). Marca foi reposicionada em 2025 como vestuário sacro católico — versão atual em [usesanctius.com](https://usesanctius.com).

### `projeto-iptv-rikelmy/`
Projeto **Supreme_TV** de Rikelmy Jhordhan (Copyright 2022, MIT License). Não é trabalho de Nicchon Sanchez — preservado como histórico de hospedagem antiga.

### `ecommerce-carros-antigo/`
Versão **HTML-only antiga** do site "Venda de Carros". Posteriormente refatorado pra PHP (versão atual está em `mini-apps/venda-de-carros/`). Preservado como histórico do refactor.

### `aulas/`
Projetos didáticos de cursos antigos — preservados como histórico de aprendizado.

- **`cena-construcao/`** — landing page simples ("Minha Landing Page"), exercício de HTML+CSS.
- **`danki-code-1/`** — "Projeto 01" do curso Front-End da Danki Code.

---

Pra evitar que essas pastas sejam servidas em produção, o workflow `deploy-app-nicchon.yml` copia apenas `app/` e `mini-apps/`. O `arquivo/` fica só no repo.
