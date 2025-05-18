<div 
    x-data="{
        isSubmitting: false,
        animateButton() {
            this.isSubmitting = true;
            setTimeout(() => this.isSubmitting = false, 2000);
        }
    }"
    @group-created.window="animateButton()"
    class="p-6 max-w-5xl mx-auto bg-white dark:bg-gray-900 rounded-2xl shadow-xl transition-all duration-300"
>
    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
        ساخت گروه جدید
    </h2>

    <form wire:submit.prevent="createGroup" class="space-y-6">
        <!-- Group Name -->
        <div x-data="{ focused: false }">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 transition-all duration-200" 
                   :class="{ 'text-blue-600 dark:text-blue-400': focused }">
                نام گروه <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="name"
                @focus="focused = true"
                @blur="focused = false"
                class="mt-1 block w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-800/50 focus:border-blue-500 transition-all duration-300 placeholder-gray-400 dark:placeholder-gray-500"
                placeholder="مثلاً: تیم توسعه‌دهندگان"
            >
            @error('name')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400 animate-pulse">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div x-data="{ focused: false }">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 transition-all duration-200"
                   :class="{ 'text-blue-600 dark:text-blue-400': focused }">
                توضیحات (اختیاری)
            </label>
            <textarea 
                wire:model="description" 
                rows="3"
                @focus="focused = true"
                @blur="focused = false"
                class="mt-1 block w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-800/50 focus:border-blue-500 transition-all duration-300 placeholder-gray-400 dark:placeholder-gray-500"
                placeholder="هدف گروه را شرح دهید..."
            ></textarea>
            @error('description')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400 animate-pulse">{{ $message }}</p>
            @enderror
        </div>

        <!-- User Search -->
        <div x-data="{ isSearchOpen: false }">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                افزودن اعضا
            </label>
            <div class="relative mt-1">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="searchQuery"
                    @focus="isSearchOpen = true"
                    @blur="setTimeout(() => isSearchOpen = false, 200)"
                    class="block w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-800/50 focus:border-blue-500 transition-all duration-300 placeholder-gray-400 dark:placeholder-gray-500"
                    placeholder="جستجوی نام یا ایمیل..."
                >
                @if(count($searchResults) > 0 && isSearchOpen)
                    <ul class="absolute z-20 mt-1 w-full bg-white dark:bg-gray-800 rounded-xl shadow-lg border-2 border-gray-200 dark:border-gray-700 max-h-60 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700 transition-all duration-300 origin-top">
                        @foreach($searchResults as $user)
                            <li 
                                wire:key="search-{{ $user->id }}"
                                class="flex items-center p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors duration-150"
                                wire:click="addUser({{ $user->id }})"
                            >
                                <div class="w-9 h-9 flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-500 rounded-full text-white font-medium">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="mr-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Selected Users -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                اعضای انتخاب شده <span class="text-xs text-gray-500">({{ count($selectedUsers) }})</span>
            </label>
            <div class="space-y-2" x-data="{ removedUserId: null }">
                @foreach($selectedUsers as $user)
                    <div 
                        wire:key="selected-{{ $user->id }}"
                        x-show="removedUserId !== {{ $user->id }}"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700"
                    >
                        <div class="flex items-center">
                            <div class="w-9 h-9 flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-500 rounded-full text-white font-medium">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <span class="mr-3 text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</span>
                        </div>
                        <button 
                            type="button" 
                            wire:click="removeUser({{ $user->id }})"
                            @click="removedUserId = {{ $user->id }}"
                            class="p-1.5 rounded-full hover:bg-red-100 dark:hover:bg-red-900/50 text-red-500 dark:text-red-400 transition-colors duration-200"
                            title="حذف کاربر"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endforeach
                @if(count($selectedUsers) === 0)
                    <div class="text-center py-4 text-gray-400 dark:text-gray-500 text-sm">
                        کاربری انتخاب نشده است
                    </div>
                @endif
            </div>
            @error('selectedUsers')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400 animate-pulse">{{ $message }}</p>
            @enderror
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end gap-3 pt-4">
            <button 
                type="button" 
                class="px-5 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all duration-300 font-medium"
                wire:click="$dispatch('close-modal', 'new-group')"
            >
                انصراف
            </button>
            <button 
                type="submit" 
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium shadow-md hover:shadow-lg transition-all duration-300 relative overflow-hidden"
                :disabled="isSubmitting"
            >
                <span x-show="!isSubmitting" class="block">ساخت گروه</span>
                <span x-show="isSubmitting" class="block flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    در حال ایجاد...
                </span>
                <span class="absolute inset-0 bg-white/10" x-show="isSubmitting" x-transition></span>
            </button>
        </div>
    </form>
</div>