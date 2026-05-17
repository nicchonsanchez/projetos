// app.nicchon.com — agregador
// Carrega mini-apps via /api/projects.php e links externos via /api/links.php
// Renderiza dois grids: "Mini-apps embutidos" e "Links externos".

const SVG_ARROW = `<svg class="card-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7"/><path d="M7 7h10v10"/></svg>`;

function makeCard(item) {
  const el = document.createElement("a");
  el.href = item.url;
  el.className = "card";
  if (item._orphan) el.classList.add("card-orphan");
  if (item.sub_deploy) el.classList.add("card-sub-deploy");
  if (item.external) {
    el.target = "_blank";
    el.rel = "noopener noreferrer";
  }
  el.innerHTML = `
    ${SVG_ARROW}
    <p class="card-category">${escapeHTML(item.categoria || "")}</p>
    <h3 class="card-title">${escapeHTML(item.titulo)}</h3>
    <p class="card-desc">${escapeHTML(item.descricao || "")}</p>
    <p class="card-meta">
      ${item.status ? `<span class="status-badge" title="${escapeHTML(item.status)}"></span>${escapeHTML(item.status)}` : ""}
      ${item.ano ? `· ${escapeHTML(item.ano)}` : ""}
      ${item.sub_deploy ? `· <span class="card-tag">Sub-deploy</span>` : ""}
      ${item._orphan ? `· <span class="card-tag card-tag-warn">Sem manifest</span>` : ""}
    </p>
  `;
  return el;
}

function escapeHTML(s) {
  return String(s ?? "").replace(/[&<>"']/g, c => ({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#39;"}[c]));
}

function render(grid, items, emptyMsg) {
  grid.removeAttribute("aria-busy");
  grid.innerHTML = "";
  if (!items || items.length === 0) {
    grid.innerHTML = `<p class="state-msg">${emptyMsg}</p>`;
    return;
  }
  for (const item of items) grid.appendChild(makeCard(item));
}

async function loadJSON(url) {
  try {
    const r = await fetch(url, { headers: { Accept: "application/json" } });
    if (!r.ok) throw new Error(`HTTP ${r.status}`);
    return await r.json();
  } catch (err) {
    console.warn(`Falha ao carregar ${url}:`, err);
    return null;
  }
}

async function init() {
  const [projects, links] = await Promise.all([
    loadJSON("api/projects.php"),
    loadJSON("api/links.php"),
  ]);

  const grid1 = document.getElementById("projetos-grid");
  const grid2 = document.getElementById("links-grid");

  render(grid1, projects, "Sem mini-apps cadastrados ainda.");
  render(grid2, links, "Sem links externos cadastrados ainda.");

  const updated = (projects && projects[0]?.atualizado_em) || new Date().toISOString().slice(0, 10);
  document.getElementById("last-update").textContent = updated;
}

document.addEventListener("DOMContentLoaded", init);

// =============================================================
// Toggle de tema (light/dark). Os 2 SVGs (lua/sol) ficam inline
// no HTML e o CSS decide qual mostrar via [data-theme="dark"].
// Aqui só precisamos alternar o atributo + persistir no storage.
// =============================================================
function toggleTheme() {
  const current = document.documentElement.getAttribute("data-theme") === "dark" ? "dark" : "light";
  const next = current === "dark" ? "light" : "dark";
  document.documentElement.setAttribute("data-theme", next);
  try { localStorage.setItem("app-theme", next); } catch (e) {}
}

// CSP `script-src 'self'` proíbe inline `onclick=`. Listener é o jeito.
document.addEventListener("DOMContentLoaded", () => {
  const btn = document.querySelector(".theme-toggle");
  if (btn) btn.addEventListener("click", toggleTheme);
});
