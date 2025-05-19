<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Dark Mode Class Strategy (before Vue loads) -->
    <script>
        if (
            localStorage.getItem('color-theme') === 'dark' ||
            (!localStorage.getItem('color-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Vite + Inertia -->
    @vite(['resources/js/app.js'])
    @inertiaHead
    @routes
</head>

<body class="bg-white dark:bg-gray-900 text-black dark:text-white">
    @inertia
</body>

</html>
