<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-10">
        
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">{{__('New Task')}}</h1>
            <x-link :href="route('task.index')" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                &larr; Retour à la liste
            </x-link>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-8 border border-gray-200">
            <form action="{{route('task.store')}}" method="post" ><div class="mb-5">
                @csrf
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Title') }}</label>
                    <input name="title" type="text" placeholder="{{ __('Example: Repairing the server...') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" value="{{ old('title') }}">
                </div>
                @error('title')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description')}}</label>
                    <textarea name="description" rows="4" placeholder="{{ __('Task details...') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">{{ old('description') }}</textarea>
                </div>
                @error('description')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Deadline') }}</label>
                        <input name="expiry_date" type="date" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none" value="{{ old('expiry_date') }}">
                    @error('expiry_date')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                    </div>


                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Priority')}}</label>
                        <select name="priority" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none bg-white" value="{{ old('priority') }}">
                            <option value="low">{{ __('Low') }}</option>
                            <option value="medium" selected>{{ __('Medium') }}</option>
                            <option value="high">{{ __('High') }}</option>
                        </select>
                    @error('priority')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none bg-white" value="{{ old('status') }}">
                            <option value="todo" selected>{{ __('Todo') }}</option>
                            <option value="in_progress">{{ __('In progress') }}</option>
                            <option value="done">{{ __('done') }}</option>
                        </select>
                    @error('status')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                    </div>

                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <x-link :href="route('task.index')" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition">
                        {{ __('Cancel') }}
                    </x-link>
                    <button  type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-md transition">
                        {{ __('Save')}}
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>