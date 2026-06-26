<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Meta -->
        <meta name="description" content="UnionOS — A private space for couples to grow together.">
        <meta name="theme-color" content="#0f766e">
        <meta name="robots" content="noindex, nofollow">
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Open Graph -->
        <meta property="og:title" content="{{ config('app.name') }}">
        <meta property="og:description" content="A private space for couples to grow together.">
        <meta property="og:type" content="website">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
