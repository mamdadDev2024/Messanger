<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#fdfdfc" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="#0a0a0a" media="(prefers-color-scheme: dark)">
<meta name="description" content="{{ $description ?? 'پیام‌رسان ایمن و مدرن' }}">

<title>{{ $title ?? 'مسنجر' }} | Messenger</title>

<!-- Preconnect & Preload -->
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>


@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
  @vite([
    'resources/css/app.css', 
    'resources/js/app.js',
  ])
@endif

@livewireStyles

<!-- Inline Critical CSS -->
<style>
  [x-cloak] { display: none !important; }
  :root { color-scheme: light dark; }
  ::-webkit-scrollbar { width: 6px; }
  ::-webkit-scrollbar-thumb { background-color: rgba(0,0,0,0.2); border-radius: 3px; }
</style>