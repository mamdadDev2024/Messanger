<form wire:submit.prevent="register" class="max-w-md w-full mx-auto backdrop-blur-2xl bg-white dark:bg-gray-800 p-8 rounded-4xl shadow-xl border dark:border-gray-700">
  <div class="space-y-6">
    <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-gray-100 mb-6">ساخت حساب کاربری</h2>

    <div class="space-y-2">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        ایمیل
        <span class="text-red-500">*</span>
      </label>
      <input 
        type="email" 
        wire:model="email"
        placeholder="example@domain.com"
        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100 transition-all"
        aria-label="ایمیل"
      />
      @error('email')
        <div class="flex items-center gap-2 mt-1 text-red-600 dark:text-red-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
          <span class="text-sm">{{ $message }}</span>
        </div>
      @enderror
    </div>

    <div class="space-y-2">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        رمز عبور
        <span class="text-red-500">*</span>
      </label>
      <input 
        type="password" 
        wire:model="password"
        placeholder="••••••••"
        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100 transition-all"
        aria-label="رمز عبور"
      />
      <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">حداقل ۸ کاراکتر (حروف، اعداد و نمادها)</p>
      @error('password')
        <div class="flex items-center gap-2 mt-1 text-red-600 dark:text-red-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
          <span class="text-sm">{{ $message }}</span>
        </div>
      @enderror
    </div>

        <div class="space-y-2">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        تکرار رمز عبور
        <span class="text-red-500">*</span>
      </label>
      <input 
        type="password" 
        wire:model="password_confirmation"
        placeholder="••••••••"
        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100 transition-all"
        aria-label="رمز عبور"
      />
      <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">حداقل ۸ کاراکتر (حروف، اعداد و نمادها)</p>
      @error('password_confirmation')
        <div class="flex items-center gap-2 mt-1 text-red-600 dark:text-red-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
          <span class="text-sm">{{ $message }}</span>
        </div>
      @enderror
    </div>

    <button 
      type="submit" 
      class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all"
      wire:loading.class="opacity-50 cursor-not-allowed"
    >
      <span wire:loading.remove>ورود</span>
      <span wire:loading class="flex items-center justify-center flex-row gap-1">
        <svg class="animate-spin h-5 w-5 inline text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        در حال بررسی...
      </span>
    </button>
  </div>
</form>