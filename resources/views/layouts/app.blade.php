<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Tiket Stadion')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .stadium-field {
            background: linear-gradient(180deg, #15803d 0%, #166534 100%);
            border: 2px dashed rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen font-sans antialiased">
    <!-- Navbar -->
    <nav class="bg-slate-900 border-b border-slate-800 px-6 py-4 flex justify-between items-center sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <span class="text-2xl font-black tracking-wider text-emerald-400">STADION<span class="text-white">TICKET</span></span>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('customer.stadium') }}" 
               class="px-4 py-2 rounded-lg font-medium text-sm transition {{ request()->routeIs('customer.stadium') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/50' : 'text-slate-400 hover:bg-slate-800' }}">
                Mode Customer (Bioskop)
            </a>
            <a href="{{ route('admin.stadium') }}" 
               class="px-4 py-2 rounded-lg font-medium text-sm transition {{ request()->routeIs('admin.stadium') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : 'text-slate-400 hover:bg-slate-800' }}">
                Mode Admin (Drag & Drop)
            </a>
        </div>
    </nav>

    <!-- Konten Utama -->
    <main class="p-6">
        @if(session('success'))
            <div class="max-w-6xl mx-auto mb-6 bg-emerald-500/10 border border-emerald-500/50 text-emerald-300 px-5 py-3.5 rounded-xl shadow">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="max-w-6xl mx-auto mb-6 bg-red-500/10 border border-red-500/50 text-red-300 px-5 py-3.5 rounded-xl shadow">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>