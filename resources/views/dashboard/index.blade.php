<x-app-layout>
    <div class="min-h-screen flex flex-col">


        <main class="flex-1 p-6 max-w-7xl mx-auto w-full">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                <div class="bg-white p-4 rounded-xl shadow border-l-4 border-indigo-500">
                    <div class="text-gray-500 text-sm">Total Tâches</div>
                    <div class="text-2xl font-bold" id="statTotal">0</div>
                </div>
                <div class="bg-white p-4 rounded-xl shadow border-l-4 border-gray-400">
                    <div class="text-gray-500 text-sm">À faire</div>
                    <div class="text-2xl font-bold" id="statTodo">0</div>
                </div>
                <div class="bg-white p-4 rounded-xl shadow border-l-4 border-blue-400">
                    <div class="text-gray-500 text-sm">En cours</div>
                    <div class="text-2xl font-bold" id="statInProgress">0</div>
                </div>
                <div class="bg-white p-4 rounded-xl shadow border-l-4 border-green-500">
                    <div class="text-gray-500 text-sm">Terminées</div>
                    <div class="text-2xl font-bold" id="statDone">0</div>
                </div>
                <div class="bg-white p-4 rounded-xl shadow border-l-4 border-red-500 flex flex-col justify-between">
                    <div>
                        <div class="text-red-500 text-sm font-bold">⚠ En Retard: <span id="statOverdue">0</span></div>
                    </div>
                    <div class="mt-2">
                        <div class="text-xs text-gray-500 text-right mb-1">Achèvement</div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div id="progressBar" class="bg-green-600 h-2.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Mes Tâches (Kanban)</h2>

                <div class="flex items-center space-x-2 bg-white p-2 rounded-lg shadow-sm">
                    <button onclick="changePage(-1)" class="p-1 hover:bg-gray-100 rounded disabled:opacity-50" id="btnPrev">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span class="text-sm font-medium">Page <span id="currentPageDisplay">1</span></span>
                    <button onclick="changePage(1)" class="p-1 hover:bg-gray-100 rounded disabled:opacity-50" id="btnNext">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-200 p-4 rounded-lg h-fit min-h-[300px]">
                    <h3 class="font-bold text-gray-700 mb-4 flex items-center">
                        <span class="w-3 h-3 bg-gray-400 rounded-full mr-2"></span> À FAIRE
                    </h3>
                    <div class="space-y-3">
                        @foreach ($tasks as $task)
                        @if($task->status == 'todo')
                        <div class="bg-white p-3 rounded shadow hover:shadow-md transition mb-3 border-l-4 border-indigo-500">
                            <div class="flex justify-between items-start mb-2">
                            @if($task->priority == 'high')
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-red-100">{{ $task->priority }}</span>
                            @elseif($task->priority == 'medium')
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-yellow-100">{{ $task->priority }}</span>
                            @else
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-green-100">{{ $task->priority }}</span>
                            @endif
                                <span class="text-xs text-gray-400">{{ $task->created_at->format('Y-m-d') }}</span>
                            </div>

                            <h4 class="font-bold text-gray-800 mb-1">{{ $task->title }}</h4>
                            <p class="text-sm text-gray-600 mb-3 truncate">{{ $task->description }}</p>

                            <div class="flex justify-between items-center border-t pt-2 mt-2">
                                <div class="flex gap-2">
                                    <a href="{{ route('task.edit', $task->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-2 rounded hover:bg-indigo-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </a>

                                    <form action="{{ route('task.destroy', $task->id) }}" onsubmit="return confirm('Are you sure?')" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded hover:bg-red-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg h-fit min-h-[300px]">
                    <h3 class="font-bold text-blue-700 mb-4 flex items-center">
                        <span class="w-3 h-3 bg-blue-400 rounded-full mr-2"></span> EN COURS
                    </h3>
                    <div class="space-y-3">
                        @foreach ($tasks as $task)
                        @if($task->status == 'in_progress')
                        <div class="bg-white p-3 rounded shadow hover:shadow-md transition mb-3 border-l-4 border-blue-500">
                            <div class="flex justify-between items-start mb-2">
                            @if($task->priority == 'high')
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-red-100">{{ $task->priority }}</span>
                            @elseif($task->priority == 'medium')
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-yellow-100">{{ $task->priority }}</span>
                            @else
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-green-100">{{ $task->priority }}</span>
                            @endif
                                <span class="text-xs text-gray-400">{{ $task->created_at->format('Y-m-d') }}</span>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-1">{{ $task->title }}</h4>
                            <p class="text-sm text-gray-600 mb-3 truncate">{{ $task->description }}</p>

                            <div class="flex justify-between items-center border-t pt-2 mt-2">
                                <div class="flex gap-2">
                                    <a href="{{ route('task.edit', $task->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-2 rounded hover:bg-indigo-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('task.destroy', $task->id) }}" onsubmit="return confirm('Are you sure?')" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 bg-red-50 p-2 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>

                <div class="bg-green-50 p-4 rounded-lg h-fit min-h-[300px]">
                    <h3 class="font-bold text-green-700 mb-4 flex items-center">
                        <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span> TERMINÉ
                    </h3>
                    <div class="space-y-3">
                        @foreach ($tasks as $task)
                        @if($task->status == 'done')
                        <div class="bg-white p-3 rounded shadow hover:shadow-md transition mb-3 border-l-4 border-green-500 opacity-75">
                            <div class="flex justify-between items-start mb-2">
                            @if($task->priority == 'high')
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-red-100">{{ $task->priority }}</span>
                            @elseif($task->priority == 'medium')
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-yellow-100">{{ $task->priority }}</span>
                            @else
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-green-100">{{ $task->priority }}</span>
                            @endif
                                <span class="text-xs text-gray-400">{{ $task->created_at->format('Y-m-d') }}</span>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-1 line-through">{{ $task->title }}</h4>
                            <p class="text-sm text-gray-600 mb-3 truncate">{{ $task->description }}</p>

                            <div class="flex justify-between items-center border-t pt-2 mt-2">
                                <div class="flex gap-2">
                                    <a href="{{ route('task.edit', $task->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-2 rounded hover:bg-indigo-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('task.destroy', $task->id) }}" onsubmit="return confirm('Are you sure?')" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 bg-red-50 p-2 rounded"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>

            </div>
        </main>
    </div>

    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full flex items-center justify-center z-50">
        <div class="bg-white p-5 rounded-lg shadow-xl w-96 transform transition-all scale-100">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-trash text-red-600"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Supprimer la tâche ?</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Êtes-vous sûr ? Cette action est irréversible (Hard Delete).</p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Oui, supprimer
                    </button>
                    <button onclick="closeModal()" class="mt-3 px-4 py-2 bg-white text-gray-700 text-base font-medium rounded-md w-full shadow-sm border border-gray-300 hover:bg-gray-50">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>