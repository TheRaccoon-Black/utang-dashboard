<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Utang DBH — Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {} }
        };
    </script>
    @stack('head')
</head>
<body class="bg-slate-100 antialiased">
<div class="mx-auto flex min-h-screen w-full max-w-[1600px] flex-col gap-3 p-3 lg:gap-4 lg:p-4">
    @yield('content')
</div>
@stack('scripts')
</body>
</html>
