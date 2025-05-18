<div class="p-6">
    <h2 class="text-lg font-medium mb-4">Create New Group</h2>

    <form wire:submit="createGroup">
        <div class="space-y-4">
            <!-- Group Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Group Name</label>
                <input type="text" 
                       wire:model="name"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                <textarea wire:model="description"
                          rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- User Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Add Members</label>
                <div class="relative mt-1">
                    <input type="text"
                           wire:model.live="searchQuery"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="Search users...">
                    
                    @if(count($searchResults) > 0)
                        <div class="absolute z-10 mt-1 w-full bg-white rounded-md shadow-lg">
                            @foreach($searchResults as $user)
                                <div wire:key="search-{{ $user->id }}"
                                     class="p-2 hover:bg-gray-100 cursor-pointer"
                                     wire:click="addUser({{ $user->id }})">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <span class="ml-2">{{ $user->name }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Selected Users -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Selected Members</label>
                <div class="space-y-2">
                    @foreach($selectedUsers as $user)
                        <div wire:key="selected-{{ $user->id }}"
                             class="flex items-center justify-between p-2 bg-gray-50 rounded-md">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="ml-2">{{ $user->name }}</span>
                            </div>
                            <button type="button"
                                    wire:click="removeUser({{ $user->id }})"
                                    class="text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
                @error('selectedUsers')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button"
                        class="btn btn-ghost"
                        wire:click="$dispatch('close-modal', 'new-group')">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    Create Group
                </button>
            </div>
        </div>
    </form>
</div>
