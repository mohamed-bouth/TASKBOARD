<x-app-layout>
    <div class="max-w-4xl mx-auto mt-10">
        
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Gestion de cherche</h1>
            <p class="text-sm text-gray-500">Liste de toutes des taches</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-md mb-6 space-y-4">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Recherche (Titre & Description)</label>
                <input type="text" id="searchInput" placeholder="Chercher une tâche..." 
                    class="w-full border-gray-300 border rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                    <select id="priorityFilter" class="w-full border-gray-300 border rounded-lg p-2">
                        <option value="">Toutes</option>
                        <option value="Haute">Haute</option>
                        <option value="Moyenne">Moyenne</option>
                        <option value="Basse">Basse</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select id="statusFilter" class="w-full border-gray-300 border rounded-lg p-2">
                        <option value="">Tous</option>
                        <option value="À faire">À faire</option>
                        <option value="En cours">En cours</option>
                        <option value="Terminé">Terminé</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button id="sortBtn" class="w-full flex justify-center items-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg border border-gray-300 transition">
                        <span>Date: <span id="sortLabel">Ascendant</span></span>
                        <svg id="sortIcon" class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                    </button>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button id="resetBtn" class="text-sm text-red-600 hover:text-red-800 font-medium underline">
                    Réinitialiser les filtres
                </button>
            </div>
        </div>

        <div id="taskList" class="space-y-4">
            </div>
        
        <p id="noResults" class="hidden text-center text-gray-500 mt-8">Aucune tâche trouvée.</p>
    </div>

    <script>
        // === DONNÉES STATIQUES (Exemple) ===
        const tasksData = [
            { title: "Réparer le serveur", description: "Le serveur de prod est lent", priority: "Haute", status: "En cours", date: "2024-02-10" },
            { title: "Acheter du café", description: "Il n'y a plus de café", priority: "Basse", status: "À faire", date: "2024-02-01" },
            { title: "Réunion client", description: "Discuter du projet Alpha", priority: "Moyenne", status: "Terminé", date: "2024-02-15" },
            { title: "Mettre à jour Laravel", description: "Passer à la version 11", priority: "Haute", status: "À faire", date: null }, // Pas de date
            { title: "Design UI", description: "Maquettes Figma", priority: "Moyenne", status: "En cours", date: "2024-02-05" },
        ];

        // État actuel
        let currentSortOrder = 'asc'; // 'asc' ou 'desc'

        // Éléments DOM
        const searchInput = document.getElementById('searchInput');
        const priorityFilter = document.getElementById('priorityFilter');
        const statusFilter = document.getElementById('statusFilter');
        const sortBtn = document.getElementById('sortBtn');
        const sortLabel = document.getElementById('sortLabel');
        const resetBtn = document.getElementById('resetBtn');
        const taskList = document.getElementById('taskList');
        const noResults = document.getElementById('noResults');

        // === FONCTIONS ===

        function renderTasks(tasks) {
            taskList.innerHTML = '';
            
            if (tasks.length === 0) {
                noResults.classList.remove('hidden');
                return;
            }
            noResults.classList.add('hidden');

            tasks.forEach(task => {
                const dateDisplay = task.date ? task.date : 'Pas de date limite';
                const dateClass = task.date ? 'text-gray-500' : 'text-gray-400 italic';
                
                // Couleurs basiques pour l'exemple
                let priorityColor = task.priority === 'Haute' ? 'text-red-600 bg-red-100' : (task.priority === 'Moyenne' ? 'text-orange-600 bg-orange-100' : 'text-green-600 bg-green-100');

                const html = `
                    <div class="bg-white p-4 rounded-lg shadow border-l-4 border-indigo-500 flex justify-between items-center hover:bg-gray-50 transition">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">${task.title}</h3>
                            <p class="text-gray-600 text-sm">${task.description}</p>
                            <div class="mt-2 flex space-x-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded ${priorityColor}">${task.priority}</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-700">${task.status}</span>
                            </div>
                        </div>
                        <div class="${dateClass} text-sm font-medium">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            ${dateDisplay}
                        </div>
                    </div>
                `;
                taskList.innerHTML += html;
            });
        }

        function filterAndSortTasks() {
            let filtered = tasksData.filter(task => {
                // 1. Recherche (Case insensitive)
                const searchText = searchInput.value.toLowerCase();
                const matchesSearch = task.title.toLowerCase().includes(searchText) || 
                                      task.description.toLowerCase().includes(searchText);

                // 2. Filtre Priorité
                const matchesPriority = priorityFilter.value === '' || task.priority === priorityFilter.value;

                // 3. Filtre Statut
                const matchesStatus = statusFilter.value === '' || task.status === statusFilter.value;

                return matchesSearch && matchesPriority && matchesStatus;
            });

            // 4. Tri par Date (Les sans date toujours en bas)
            filtered.sort((a, b) => {
                if (a.date === null && b.date === null) return 0;
                if (a.date === null) return 1; // a en bas
                if (b.date === null) return -1; // b en bas

                const dateA = new Date(a.date);
                const dateB = new Date(b.date);

                return currentSortOrder === 'asc' ? dateA - dateB : dateB - dateA;
            });

            renderTasks(filtered);
        }

        // === EVENTS ===

        // Ecouteurs pour la recherche et les filtres
        searchInput.addEventListener('input', filterAndSortTasks);
        priorityFilter.addEventListener('change', filterAndSortTasks);
        statusFilter.addEventListener('change', filterAndSortTasks);

        // Bouton Tri
        sortBtn.addEventListener('click', () => {
            currentSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
            sortLabel.textContent = currentSortOrder === 'asc' ? 'Ascendant' : 'Descendant';
            filterAndSortTasks();
        });

        // Bouton Reset
        resetBtn.addEventListener('click', () => {
            searchInput.value = '';
            priorityFilter.value = '';
            statusFilter.value = '';
            currentSortOrder = 'asc';
            sortLabel.textContent = 'Ascendant';
            filterAndSortTasks();
        });

        // Initialisation
        renderTasks(tasksData); // Afficher tout au début
        filterAndSortTasks(); // Appliquer le tri par défaut

    </script>
</x-app-layout>