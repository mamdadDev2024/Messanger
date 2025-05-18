<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

<meta name="theme-color" content="#fdfdfc" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="#0a0a0a" media="(prefers-color-scheme: dark)">

<meta name="description" content="{{ $description ?? 'پیام‌رسان ایمن، سریع و حرفه‌ای' }}">

<title>{{ $title ?? 'مسنجر' }} | Messenger</title>

<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700&display=swap" rel="stylesheet">

@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
  @vite([
    'resources/css/app.css', 
    'resources/js/app.js',
  ])
@endif

@livewireStyles

<style>
  [x-cloak] { display: none !important; }
  :root {
    color-scheme: light dark;
    font-family: 'Vazirmatn', ui-sans-serif, system-ui;
    scroll-behavior: smooth;
  }
  ::-webkit-scrollbar {
    width: 6px;
    height: 6px;
  }
  ::-webkit-scrollbar-thumb {
    background-color: rgba(100, 100, 100, 0.3);
    border-radius: 3px;
  }

  body {
    transition: background-color 0.3s ease, color 0.3s ease;
  }
</style>
