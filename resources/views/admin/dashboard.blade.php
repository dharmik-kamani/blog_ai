@extends('layouts.admin')

@section('header', 'Overview')

@section('content')
<div class="space-y-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass p-6 border-b-4 border-zinc-800 fade-in" style="animation-delay: 0.1s">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-zinc-100 text-zinc-900 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <span class="text-xs font-black text-zinc-800 bg-zinc-100 px-2 py-1 rounded-lg uppercase tracking-tighter">+12%</span>
            </div>
            <div class="text-slate-500 text-sm font-bold uppercase tracking-wider">Total Blogs</div>
            <div class="text-3xl font-black text-slate-800 mt-1">{{ $stats['total_blogs'] }}</div>
        </div>

        <div class="glass p-6 border-b-4 border-zinc-600 fade-in" style="animation-delay: 0.2s">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-zinc-100 text-zinc-700 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span class="text-xs font-black text-zinc-700 bg-zinc-100 px-2 py-1 rounded-lg uppercase tracking-tighter">+5%</span>
            </div>
            <div class="text-slate-500 text-sm font-bold uppercase tracking-wider">Total Readers</div>
            <div class="text-3xl font-black text-slate-800 mt-1">{{ $stats['total_users'] }}</div>
        </div>

        <div class="glass p-6 border-b-4 border-zinc-400 fade-in" style="animation-delay: 0.3s">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-zinc-100 text-zinc-500 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <span class="text-xs font-black text-zinc-600 bg-zinc-100 px-2 py-1 rounded-lg uppercase tracking-tighter">+24%</span>
            </div>
            <div class="text-slate-500 text-sm font-bold uppercase tracking-wider">Total Likes</div>
            <div class="text-3xl font-black text-slate-800 mt-1">{{ $stats['total_likes'] }}</div>
        </div>

        <div class="glass p-6 border-b-4 border-zinc-200 fade-in" style="animation-delay: 0.4s">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-zinc-100 text-zinc-400 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-bookmark"></i>
                </div>
                <span class="text-xs font-black text-zinc-500 bg-zinc-100 px-2 py-1 rounded-lg uppercase tracking-tighter">+8%</span>
            </div>
            <div class="text-slate-500 text-sm font-bold uppercase tracking-wider">Saved Posts</div>
            <div class="text-3xl font-black text-slate-800 mt-1">{{ $stats['total_saves'] ?? 0 }}</div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Activity Chart -->
        <div class="lg:col-span-2 glass p-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-slate-800">Publishing Trends</h3>
                <select class="bg-slate-50 border-none rounded-xl text-xs font-bold px-4 py-2 focus:ring-2 focus:ring-zinc-900">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                </select>
            </div>
            <div class="h-[300px] w-full">
                <canvas id="blogChart"></canvas>
            </div>
        </div>

        <!-- Quick Info -->
        <div class="space-y-6">
            <div class="glass p-8 bg-zinc-900 text-white border-none shadow-zinc-200 shadow-xl overflow-hidden relative">
                <div class="relative z-10">
                    <h3 class="text-xl font-black mb-2">Grow Your Voice</h3>
                    <p class="text-zinc-300 text-sm mb-6 leading-relaxed">Publish new insights and connect with your audience today.</p>
                    <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center bg-white text-zinc-900 px-6 py-3 rounded-xl font-black text-sm transition hover:scale-105 active:scale-95">
                        <i class="fa-solid fa-pen-nib mr-2"></i> Write New Post
                    </a>
                </div>
                <i class="fa-solid fa-quote-right absolute -right-4 -bottom-4 text-8xl text-white/10 rotate-12"></i>
            </div>

            <div class="glass p-8">
                <h3 class="text-lg font-black text-slate-800 mb-4">Account Status</h3>
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-sm font-bold text-slate-700">System Live</span>
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">v1.0.4</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-zinc-900 rounded-full"></div>
                        <span class="text-sm font-bold text-slate-700">Auto-Backup</span>
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('blogChart').getContext('2d');
    const data = @json($graphData);
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(24, 24, 27, 0.2)');
    gradient.addColorStop(1, 'rgba(24, 24, 27, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.date),
            datasets: [{
                label: 'Blogs Published',
                data: data.map(d => d.count),
                borderColor: '#18181b',
                backgroundColor: gradient,
                fill: true,
                tension: 0.45,
                borderWidth: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#18181b',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    cornerRadius: 12,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9', drawBorder: false },
                    ticks: { color: '#64748b', font: { weight: '600' } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { weight: '600' } }
                }
            }
        }
    });
</script>
@endpush
