<!DOCTYPE html>
<html 
  lang="fa" 
  dir="rtl"
  class="h-full scroll-smooth"
  x-data="{
    isMobileMenuOpen: false,
    darkMode: $persist(window.matchMedia('(prefers-color-scheme: dark)').matches).as('darkMode'),
    toggleDarkMode() { this.darkMode = !this.darkMode }
  }"
  :class="{ 'dark': darkMode }"
  x-init="$watch('darkMode', v => document.documentElement.classList.toggle('dark', v))"
>
<head>
  @include('components.app.head', ['title' => $title ?? ''])
</head>

<body 
  class="h-full flex flex-col bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300"
>
  @include('components.app.header')

  <div class="flex-1 w-full flex overflow-hidden">
    <main 
      class="h-full w-full max-w-screen-sm lg:max-w-4xl mx-auto px-4 md:px-6 py-4 flex flex-col transition-all"
    >
      <div class="flex-1 flex flex-col lg:flex-row gap-6 lg:gap-8">
        {{ $slot }}
      </div>
    </main>
  </div>

  @if (Route::has('login'))
    <div class="h-14 lg:h-[5.5rem]"></div>
  @endif

  <x-toaster-hub />

  @livewireScripts
  @stack('scripts')

  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.$screen = (breakpoint) => window.matchMedia(`(min-width: ${breakpoint})`).matches;
    });
  </script>
</body>
</html>
