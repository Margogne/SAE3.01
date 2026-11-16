<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Catalogue des Expérimentations - Le Mans Université</title>

  <!-- Inclusion de Tailwind CSS v4 via CDN -->
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

  <!-- Lien vers la feuille de style externe -->
  <link rel="stylesheet" href="../style/Partstyle.css" />
</head>
<body>

  <!-- Coque principale de l'application -->
  <div class="app-shell" data-node-id="281:2782">

    <!-- En-tête de navigation -->
    <header class="site-header" id="siteHeader" data-node-id="281:2908">
      <a class="brand" href="#projets" aria-label="Le Mans Université">
        <!-- Logo de l'université -->
        <img src="../assets/img/logoLemansBlancSurBleu.png" alt="Le Mans Université" />
      </a>

      <!-- Navigation principale -->
      <nav class="main-nav" aria-label="Navigation principale">
        <a class="nav-link" href="#dashboard">Dashboard</a>
        <a class="nav-link active" href="#projets">Projets</a>
        <a class="nav-link" href="#ressources">Ressources</a>
        <a class="nav-link" href="#messagerie">Messagerie</a>
      </nav>

      <!-- Actions de l'en-tête (Langue, Notifications, Profil) -->
      <div class="header-actions">
        
        <!-- Sélecteur de langue -->
        <div class="menu-anchor language-anchor">
          <button class="header-button language-button" id="langBtn" aria-expanded="false" aria-haspopup="menu">
            <span>FR</span>
            <span class="fa" style="font-size: 9px;">&#xf078;</span>
          </button>
          <div class="dropdown language-menu" id="langMenu" role="menu">
            <button class="dropdown-item selected" role="menuitem" data-lang="FR">FR <span class="fa"></span></button>
            <button class="dropdown-item" role="menuitem" data-lang="EN">EN</button>
            <button class="dropdown-item" role="menuitem" data-lang="ES">ES</button>
          </div>
        </div>

        <!-- Menu des notifications -->
        <div class="menu-anchor">
          <button class="header-button icon-button" id="notifBtn" aria-label="Notifications" aria-expanded="false" aria-haspopup="dialog">
            <span class="fa">&#xf0f3;</span>
          </button>
          <div class="dropdown notification-menu" id="notifMenu" role="dialog" aria-label="Notifications" style="display: none;">
            <h2>Notifications</h2>
            <div class="empty-notifications">
              <span class="notification-illustration">
                <span class="fa" style="font-size: 24px; color: #9ba8bd;">&#xf0a2;</span>
              </span>
              <strong>Aucune notification</strong>
              <p>Vous êtes à jour. Les nouvelles activités apparaîtront ici.</p>
            </div>
          </div>
        </div>

        <!-- Menu du profil utilisateur -->
        <div class="menu-anchor profile-anchor">
          <button class="profile-button" id="profileBtn" aria-expanded="false" aria-haspopup="menu">
            <span class="avatar">TL</span>
            <span class="profile-copy">
              <strong>T. Dubois</strong>
              <small>Participant</small>
            </span>
            <span class="fa profile-chevron" id="profileChevron">&#xf078;</span>
          </button>
          <div class="dropdown profile-menu" id="profileMenu" role="menu" style="display: none;">
            <div class="profile-menu-heading">
              <span class="avatar dark">TL</span>
              <span>
                <strong><?php echo $_SESSION['user_nom']. ' ' . $_SESSION['user_prenom']; ?> </strong>    <!-- a changer en une variable de BDD -->
                <small>Participant</small>
              </span>
            </div>
            <button class="dropdown-item" role="menuitem"><span class="fa">&#xf007;</span> Mon profil</button>
            <button class="dropdown-item" role="menuitem"><span class="fa">&#xf013;</span> Paramètres</button>
            <button class="dropdown-item danger" role="menuitem"><span class="fa">&#xf2f5;</span> Se déconnecter</button>
          </div>
        </div>

      </div>
    </header>

    <!-- NO CHECK AU DELA -->


    <!-- En-tête de la page -->
    <section class="page-heading" id="projets">
      <h1>
        <span class="fa">&#xf114;</span>
        Projets
      </h1>
    </section>

    <!-- Corps principal -->
    <main>
      <!-- Panneau de filtres et recherche -->
      <section class="filter-panel" aria-label="Filtres des projets">
        <h2>
          <span class="fa">&#xf0b0;</span>
          Filtrer &amp; Rechercher
        </h2>
        <div class="filter-grid">
          <label class="field">
            <span>Mot-clé / Titre</span>
            <span class="search-field">
              <span class="fa search-icon">&#xf002;</span>
              <input type="search" id="searchInput" placeholder="Rechercher un projet, un mot-clé..." />
            </span>
          </label>
          <label class="field">
            <span>Domaine de recherche</span>
            <select id="domainSelect">
              <option value="Tous">Tous les domaines</option>
              <option value="Robotique">Robotique</option>
              <option value="Réalité virtuelle">Réalité virtuelle</option>
              <option value="Langage">Langage</option>
            </select>
          </label>
          <label class="field">
            <span>Statut</span>
            <select id="statusSelect">
              <option value="Tous">Tous les statuts</option>
              <option value="En gestion">En gestion</option>
              <option value="Tests actifs">Tests actifs</option>
            </select>
          </label>
          <label class="field">
            <span>Mes projets</span>
            <span class="checkbox-field">
              <input type="checkbox" id="mineCheckbox" />
              <span>Afficher uniquement les miens</span>
            </span>
          </label>
        </div>
      </section>

      <!-- Grille dynamique des projets -->
      <section class="project-grid" id="projectGrid" aria-live="polite">
        <!-- Les cartes sont injectées dynamiquement via JavaScript -->
      </section>
    </main>

    <!-- Pied de page -->
    <footer>
      <span class="fa">&#xf19d;</span>
      <p>
        <strong>Le Mans Université</strong> • Participant - Catalogue des expérimentations
      </p>
    </footer>

    <!-- Modale de détail d'un projet (Masquée par défaut) -->
    <div class="modal-backdrop" id="projectModal" role="presentation" style="display: none;">
      <section class="project-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <button class="modal-close" id="closeModalBtn" aria-label="Fermer">×</button>
        <img id="modalImage" src="" alt="" />
        <div>
          <p class="project-category" id="modalCategory"></p>
          <h2 id="modalTitle"></h2>
          <p id="modalDescription"></p>
          <span class="owner" id="modalOwner">
            <span class="fa">&#xf2bc;</span>
            <span id="modalOwnerText"></span>
          </span>
        </div>
      </section>
    </div>

  </div>

  <!-- Script JavaScript pour la logique interactive et le filtrage -->
  <script>
    // Données des projets (inspirées de App.tsx)
    const projectsData = [
      {
        id: 1,
        category: "Robotique & Ergonomie",
        title: "Robot Porteur Humanoïde",
        description: "Étude de l'assistance physique sans infrastructure pour la réduction des troubles musculosquelettiques.",
        owner: "Dr. L. Oubahssi",
        status: "En gestion",
        statusColor: "#34d399",
        image: "https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=600&auto=format&fit=crop&q=80",
        domain: "Robotique",
        mine: true
      },
      {
        id: 2,
        category: "IHM & Réalité Virtuelle",
        title: "Interface VR & Ergonomie",
        description: "Évaluation de la charge mentale des opérateurs lors d'interactions en immersion 3D.",
        owner: "Prof. M. Jardin",
        status: "Tests actifs",
        statusColor: "#60a5fa",
        image: "https://images.unsplash.com/photo-1593508512255-86ab42a8e620?w=600&auto=format&fit=crop&q=80",
        domain: "Réalité virtuelle",
        mine: false
      },
      {
        id: 3,
        category: "TAL & Langage",
        title: "Analyse NLP & Questionnaires",
        description: "Extraction automatique de concepts et retour d'expérience qualitatif des apprenants.",
        owner: "Dr. A. Leroux",
        status: "En gestion",
        statusColor: "#34d399",
        image: "https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&auto=format&fit=crop&q=80",
        domain: "Langage",
        mine: true
      }
    ];

    // Éléments du DOM
    const searchInput = document.getElementById('searchInput');
    const domainSelect = document.getElementById('domainSelect');
    const statusSelect = document.getElementById('statusSelect');
    const mineCheckbox = document.getElementById('mineCheckbox');
    const projectGrid = document.getElementById('projectGrid');

    // Menus déroulants (Header)
    const langBtn = document.getElementById('langBtn');
    const langMenu = document.getElementById('langMenu');
    const notifBtn = document.getElementById('notifBtn');
    const notifMenu = document.getElementById('notifMenu');
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');
    const profileChevron = document.getElementById('profileChevron');

    // Modale
    const projectModal = document.getElementById('projectModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const modalImage = document.getElementById('modalImage');
    const modalCategory = document.getElementById('modalCategory');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    const modalOwnerText = document.getElementById('modalOwnerText');

    // Fonction de rendu des projets
    function renderProjects() {
      const query = searchInput.value.trim().toLowerCase();
      const domain = domainSelect.value;
      const status = statusSelect.value;
      const mineOnly = mineCheckbox.checked;

      const filtered = projectsData.filter(project => {
        const searchable = `${project.title} ${project.category} ${project.description} ${project.owner}`.toLowerCase();
        const matchesQuery = !query || searchable.includes(query);
        const matchesDomain = (domain === 'Tous' || project.domain === domain);
        const matchesStatus = (status === 'Tous' || project.status === status);
        const matchesMine = (!mineOnly || project.mine);

        return matchesQuery && matchesDomain && matchesStatus && matchesMine;
      });

      if (filtered.length === 0) {
        projectGrid.innerHTML = `
          <div class="no-results" style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #9ba8bd;">
            <span class="fa" style="font-size: 32px; margin-bottom: 12px; display: block;">&#xf002;</span>
            <strong>Aucun projet trouvé</strong>
            <p>Modifiez vos critères de recherche pour afficher d’autres projets.</p>
            <button id="resetFilters" style="margin-top: 12px; padding: 8px 16px; background: #c94a29; color: white; border: none; border-radius: 6px; cursor: pointer;">Réinitialiser les filtres</button>
          </div>
        `;
        document.getElementById('resetFilters').addEventListener('click', () => {
          searchInput.value = '';
          domainSelect.value = 'Tous';
          statusSelect.value = 'Tous';
          mineCheckbox.checked = false;
          renderProjects();
        });
        return;
      }

      projectGrid.innerHTML = filtered.map(project => `
        <article class="project-card" key="${project.id}">
          <div class="project-image">
            <img src="${project.image}" alt="${project.title}" />
            <span class="status-pill">
              <i style="background-color: ${project.statusColor}; width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 6px;"></i>
              ${project.status}
            </span>
          </div>
          <div class="project-content">
            <p class="project-category">${project.category}</p>
            <h3>${project.title}</h3>
            <p class="project-description">${project.description}</p>
            <div class="project-footer">
              <span class="owner">
                <span class="fa">&#xf2bc;</span>
                ${project.owner}
              </span>
              <button class="view-details-btn" data-id="${project.id}">
                Voir la fiche <span class="fa">&#xf061;</span>
              </button>
            </div>
          </div>
        </article>
      `).join('');

      // Attacher les écouteurs sur les boutons "Voir la fiche"
      document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
          const id = parseInt(e.currentTarget.getAttribute('data-id'));
          const proj = projectsData.find(p => p.id === id);
          if (proj) openModal(proj);
        });
      });
    }

    // Gestion de la modale
    function openModal(project) {
      modalImage.src = project.image;
      modalCategory.textContent = project.category;
      modalTitle.textContent = project.title;
      modalDescription.textContent = project.description;
      modalOwnerText.textContent = project.owner;
      projectModal.style.display = 'flex';
    }

    closeModalBtn.addEventListener('click', () => {
      projectModal.style.display = 'none';
    });

    projectModal.addEventListener('click', (e) => {
      if (e.target === projectModal) {
        projectModal.style.display = 'none';
      }
    });

    // Gestion des menus déroulants (Fermeture globale au clic extérieur)
    function closeAllMenus() {
      langMenu.style.display = 'none';
      notifMenu.style.display = 'none';
      profileMenu.style.display = 'none';
      langBtn.setAttribute('aria-expanded', 'false');
      notifBtn.setAttribute('aria-expanded', 'false');
      profileBtn.setAttribute('aria-expanded', 'false');
      profileChevron.classList.remove('closed');
    }

    langBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = langMenu.style.display === 'block';
      closeAllMenus();
      if (!isOpen) {
        langMenu.style.display = 'block';
        langBtn.setAttribute('aria-expanded', 'true');
      }
    });

    notifBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = notifMenu.style.display === 'block';
      closeAllMenus();
      if (!isOpen) {
        notifMenu.style.display = 'block';
        notifBtn.setAttribute('aria-expanded', 'true');
      }
    });

    profileBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = profileMenu.style.display === 'block';
      closeAllMenus();
      if (!isOpen) {
        profileMenu.style.display = 'block';
        profileBtn.setAttribute('aria-expanded', 'true');
        profileChevron.classList.add('closed');
      }
    });

    window.addEventListener('click', () => {
      closeAllMenus();
    });

    // Écouteurs sur les filtres
    searchInput.addEventListener('input', renderProjects);
    domainSelect.addEventListener('change', renderProjects);
    statusSelect.addEventListener('change', renderProjects);
    mineCheckbox.addEventListener('change', renderProjects);

    // Initialisation au chargement
    renderProjects();
  </script>

</body>
</html>