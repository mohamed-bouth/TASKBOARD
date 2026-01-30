<x-app-layout>
    <div class="max-w-4xl mx-auto mt-10">

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
                        <option value="high">high</option>
                        <option value="medium">medium</option>
                        <option value="low">low</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select id="statusFilter" class="w-full border-gray-300 border rounded-lg p-2">
                        <option value="">Tous</option>
                        <option value="todo">todo</option>
                        <option value="in_progress">in progress</option>
                        <option value="done">done</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <select class="w-full border-gray-300 border rounded-lg p-2" name="expiry_date" id="dateFilter">
                        <option value="DESC">descending</option>
                        <option value="ASC">Ascending</option>
                    </select>
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
        let searchInput = document.querySelector("#searchInput");
        let priorityFilter = document.querySelector("#priorityFilter");
        let statusFilter = document.querySelector("#statusFilter");
        let dateFilter = document.querySelector("#dateFilter");
        let taskList = document.querySelector("#taskList");

        function render(data){
            taskList.innerHTML = ''
            data.forEach(task => {

                let proirity = ''
                let status = ''
                if(task.priority === 'high'){
                    proirity = 'bg-red-100'
                }else if(task.priority === 'medium'){
                    proirity = 'bg-yellow-100'
                }else{
                    proirity = 'bg-green-100'
                }

                if(task.status === 'todo'){
                    status = 'bg-gray-100'
                }else if(task.status === 'in_progress'){
                    status = 'bg-blue-100'
                }else{
                    status = 'bg-green-100'
                }

                const taskContainer = document.createElement("div");
                taskContainer.innerHTML = `
                    <div class="bg-white p-4 rounded-lg shadow border-l-4 border-indigo-500 flex justify-between items-center hover:bg-gray-50 transition">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">${task.title}</h3>
                            <p class="text-gray-600 text-sm">${task.description}</p>
                            <div class="mt-2 flex space-x-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded ${proirity}">${task.priority}</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 ${status}">${task.status}</span>
                            </div>
                        </div>
                        <div class=" text-sm font-medium">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                ${task.expiry_date}
                        </div>
                    </div>
                `;
                taskList.appendChild(taskContainer)
            });
        }

        function fetchTasks() {
            let searchValue = searchInput.value;
            let priorityValue = priorityFilter.value;
            let statusValue = statusFilter.value;
            let dateValue = dateFilter.value;

            let url = `{{ route('search.filter') }}?search=${searchValue}&priority=${priorityValue}&status=${statusValue}&expiry_date=${dateValue}`;

            console.log(url)

            fetch(url)

                .then(response => {
                    return response.json();
                })

                .then(data => {
                    console.log(data);
                    render(data);
                })
                .catch(err => console.error(err));

        }

        searchInput.addEventListener("input", () => {
            fetchTasks()
        })
        priorityFilter.addEventListener("change", () => {
            fetchTasks()
        })
        statusFilter.addEventListener("change", () => {
            fetchTasks()
        })
        dateFilter.addEventListener("change", () => {
            fetchTasks()
        })
        fetchTasks()
    </script>
</x-app-layout>