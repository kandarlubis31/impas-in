<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard IMPAS-IN — kelola bantuan, tawaran, dan hutang budi.">
    <title>IMPAS-IN | Dashboard</title>
    @include('partials.head-assets')
</head>
<body class="font-sans bg-slate-50 text-slate-700 min-h-screen">

    {{-- ============ NAVBAR ============ --}}
    <header class="sticky top-0 z-50 backdrop-blur-md bg-white/85 border-b border-slate-100">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16">
            <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                <span class="w-9 h-9 rounded-xl bg-primary-600 text-white flex items-center justify-center font-black text-sm shadow-soft group-hover:scale-105 transition"><x-icon name="handshake" class="w-5 h-5"/></span>
                <span class="text-lg font-extrabold tracking-tight text-slate-800">IMPAS<span class="text-primary-600">-IN</span></span>
            </a>
            <div class="flex items-center gap-3">
                <div class="hidden sm:block text-right mr-1">
                    <p class="text-sm font-bold text-slate-800 leading-tight">{{ $user->name }}</p>
                    <p class="text-[11px] text-primary-600 font-bold"><x-icon name="star" class="w-3.5 h-3.5 inline-block align-[-2px] text-amber-500"/> {{ $user->reputation_score }} reputasi</p>
                </div>
                <span class="w-9 h-9 rounded-full bg-primary-100 border-2 border-primary-200 flex items-center justify-center text-sm font-black text-primary-700">{{ mb_substr($user->name, 0, 1) }}</span>
                <form action="{{ url('/logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-slate-500 hover:text-red-600 border border-slate-200 hover:border-red-300 px-4 py-2 rounded-xl transition">
                        Keluar
                    </button>
                </form>
            </div>
        </nav>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 pb-24 dash-bg">

        {{-- Flash banners --}}
        @if(session('success'))
            <div class="mt-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold rounded-2xl p-4 flex items-start gap-2.5" data-aos="fade-down">
                <x-icon name="circle-check" class="w-5 h-5 mt-0.5 shrink-0"/>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mt-6 bg-red-50 border border-red-200 text-red-600 text-sm font-semibold rounded-2xl p-4 flex items-start gap-2.5" data-aos="fade-down">
                <x-icon name="alert-triangle" class="w-5 h-5 mt-0.5 shrink-0"/>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- ============ HERO / STATS ============ --}}
        <div class="mt-8" data-aos="fade-up">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Halo, {{ $user->name }} <x-icon name="hand" class="w-7 h-7 inline-block align-[-5px] text-primary-600"/></h1>
            <p class="mt-1 text-sm text-slate-500">Siap saling bantu hari ini? Semua kebaikanmu tercatat &amp; terbalas.</p>
        </div>

        <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Saldo --}}
            <div class="bg-slate-900 rounded-3xl p-5 text-white relative overflow-hidden" data-aos="zoom-in">
                <div class="absolute -top-8 -right-8 w-28 h-28 rounded-full bg-primary-500/20 blur-2xl"></div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Saldo Hutang Budi</p>
                <p class="mt-2 text-3xl font-extrabold tracking-tight {{ $user->balance < 0 ? 'text-red-300' : 'text-emerald-300' }}">{{ $user->balance }}</p>
                <p class="mt-1 text-[11px] text-slate-400">poin siap balas budi</p>
            </div>
            {{-- Reputasi --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-soft p-5" data-aos="zoom-in" data-aos-delay="60">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Reputasi</p>
                <p class="mt-2 text-3xl font-extrabold text-amber-500 tracking-tight">{{ $user->reputation_score }} <x-icon name="star" class="w-6 h-6 inline-block align-[-3px] text-amber-500"/></p>
                <p class="mt-1 text-[11px] text-slate-400">makin bantu, makin dipercaya</p>
            </div>
            {{-- Feed tersedia --}}
            <div class="bg-primary-50 border border-primary-100 rounded-3xl p-5" data-aos="zoom-in" data-aos-delay="120">
                <p class="text-[11px] font-bold text-primary-700/70 uppercase tracking-widest">Butuh Bantuan</p>
                <p class="mt-2 text-3xl font-extrabold text-primary-700 tracking-tight">{{ $tasks->total() }}</p>
                <p class="mt-1 text-[11px] text-primary-700/60">kasus terbuka untukmu</p>
            </div>
            {{-- Tugas saya --}}
            <div class="bg-amber-50 border border-amber-100 rounded-3xl p-5" data-aos="zoom-in" data-aos-delay="180">
                <p class="text-[11px] font-bold text-amber-700/70 uppercase tracking-widest">Kasus Saya</p>
                <p class="mt-2 text-3xl font-extrabold text-amber-600 tracking-tight">{{ $myTasks->total() }}</p>
                <p class="mt-1 text-[11px] text-amber-700/60">total tugas dibuat</p>
            </div>
        </div>

        <div class="mt-10 grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            {{-- ============ KOLOM KIRI ============ --}}
            <div class="space-y-6">
                {{-- Minta Bantuan --}}
                <div class="bg-white rounded-3xl border border-slate-100 shadow-soft p-6" data-aos="fade-up">
                    <h2 class="text-lg font-extrabold text-slate-800 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center text-primary-600"><x-icon name="hand-helping" class="w-4 h-4"/></span>
                        Minta Bantuan
                    </h2>
                    <form action="{{ url('/tasks') }}" method="POST" class="mt-5 space-y-4" novalidate>
                        @csrf
                        <div>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Butuh bantuan apa?" aria-label="Judul bantuan"
                                   class="w-full px-4 py-3 rounded-2xl border {{ $errors->has('title') ? 'border-red-300 bg-red-50/50' : 'border-slate-200' }} text-sm outline-none focus:ring-2 focus:ring-primary-500 transition placeholder:text-slate-400" required>
                            @error('title')<p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <textarea name="description" rows="3" placeholder="Detail bantuannya..." aria-label="Detail bantuan"
                                      class="w-full px-4 py-3 rounded-2xl border {{ $errors->has('description') ? 'border-red-300 bg-red-50/50' : 'border-slate-200' }} text-sm outline-none focus:ring-2 focus:ring-primary-500 transition placeholder:text-slate-400" required>{{ old('description') }}</textarea>
                            @error('description')<p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <input type="number" name="effort_level" value="{{ old('effort_level') }}" placeholder="Level 1-5" min="1" max="5" aria-label="Level kesulitan (1-5)"
                                       class="w-full px-4 py-3 rounded-2xl border {{ $errors->has('effort_level') ? 'border-red-300 bg-red-50/50' : 'border-slate-200' }} text-sm outline-none focus:ring-2 focus:ring-primary-500 transition placeholder:text-slate-400" required>
                                @error('effort_level')<p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <input type="number" name="base_reward" value="{{ old('base_reward') }}" placeholder="Reward poin" min="0" aria-label="Reward poin"
                                       class="w-full px-4 py-3 rounded-2xl border {{ $errors->has('base_reward') ? 'border-red-300 bg-red-50/50' : 'border-slate-200' }} text-sm outline-none focus:ring-2 focus:ring-primary-500 transition placeholder:text-slate-400" required>
                                @error('base_reward')<p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 font-bold py-3.5 rounded-2xl shadow-soft transition hover:scale-[1.01] active:scale-[0.99]">
                            Publish Tugas
                        </button>
                    </form>
                </div>

                {{-- Riwayat Mutasi --}}
                <div class="bg-white rounded-3xl border border-slate-100 shadow-soft p-6" data-aos="fade-up" data-aos-delay="80">
                    <h2 class="text-lg font-extrabold text-slate-800 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600"><x-icon name="scroll-text" class="w-4 h-4"/></span>
                        Riwayat Mutasi
                    </h2>
                    <div class="mt-5 space-y-3 max-h-72 overflow-y-auto pr-1">
                        @forelse($transactions ?? [] as $tx)
                            <div class="flex justify-between items-center bg-slate-50 rounded-2xl p-3 border border-slate-100">
                                <div class="text-xs min-w-0">
                                    @if($tx->from_user == $user->id)
                                        <p class="font-bold text-red-500 truncate">Kirim ke {{ $tx->receiver->name }}</p>
                                    @else
                                        <p class="font-bold text-emerald-600 truncate">Terima dari {{ $tx->sender->name }}</p>
                                    @endif
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $tx->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="shrink-0 font-black text-sm {{ $tx->from_user == $user->id ? 'text-red-500' : 'text-emerald-600' }}">
                                    {{ $tx->from_user == $user->id ? '-' : '+' }}{{ $tx->points }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="mb-2"><span class="inline-flex w-12 h-12 rounded-full bg-primary-50 border border-primary-100 items-center justify-center"><x-icon name="sprout-fill" class="w-6 h-6 text-primary-500"/></span></p>
                                <p class="text-xs text-slate-400 italic">Belum ada mutasi poin.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ============ KOLOM KANAN ============ --}}
            <div class="lg:col-span-2 space-y-10">
                {{-- Feed Bantuan --}}
                <div data-aos="fade-up">
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2 mb-5">
                        <span class="w-9 h-9 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center text-primary-600"><x-icon name="flame" class="w-5 h-5"/></span>
                        Feed Bantuan
                        <span class="text-xs font-bold text-slate-400 bg-slate-100 rounded-full px-2.5 py-1">{{ $tasks->total() }}</span>
                    </h2>
                    @if($errors->has('condition'))
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-xs font-semibold rounded-2xl p-3.5 flex items-start gap-2">
                            <x-icon name="alert-triangle" class="w-4 h-4 mt-0.5 shrink-0"/>
                            <span>{{ $errors->first('condition') }}</span>
                        </div>
                    @endif
                    <div class="grid grid-cols-1 gap-4">
                        @forelse($tasks as $task)
                            <div class="bg-white rounded-3xl border border-slate-100 shadow-soft p-5 hover:shadow-lift hover:-translate-y-0.5 transition duration-300">
                                <div class="flex flex-col sm:flex-row justify-between gap-4 sm:items-start">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="font-extrabold text-slate-800">{{ $task->title }}</h3>
                                            <span class="text-[10px] font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-md">Lv.{{ $task->effort_level }}</span>
                                        </div>
                                        <p class="text-slate-500 text-sm mt-1">{{ $task->description }}</p>
                                        <div class="mt-3 flex items-center gap-2 flex-wrap text-[11px] font-bold">
                                            <span class="bg-primary-50 text-primary-700 px-2.5 py-1 rounded-lg">Reward {{ $task->base_reward }} poin</span>
                                            <span class="bg-slate-100 text-slate-500 px-2.5 py-1 rounded-lg">Oleh: {{ $task->creator->name }}</span>
                                        </div>
                                    </div>
                                    <form action="{{ url('/tasks/'.$task->id.'/offer') }}" method="POST" class="shrink-0 sm:w-52">
                                        @csrf
                                        <div class="flex gap-2">
                                            <input type="text" name="condition" placeholder="Syarat..." aria-label="Syarat bantuan"
                                                   class="flex-1 min-w-0 px-3 py-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-primary-500 transition placeholder:text-slate-400" required>
                                        </div>
                                        <div class="flex gap-2 mt-2">
                                            <input type="number" name="extra_fee" placeholder="Fee (Rp)" min="0" step="1" value="{{ old('extra_fee', '') }}" aria-label="Extra fee (opsional)"
                                                   class="flex-1 min-w-0 px-3 py-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-primary-500 transition placeholder:text-slate-400">
                                            <button class="shrink-0 text-white bg-primary-600 hover:bg-primary-700 px-4 py-2.5 rounded-xl font-bold text-xs transition shadow-soft">Bantu</button>
                                        </div>
                                            </form>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white border-2 border-dashed border-slate-200 rounded-3xl p-10 text-center">
                                <p class="mb-3"><span class="inline-flex w-14 h-14 rounded-full bg-primary-50 border border-primary-100 items-center justify-center"><x-icon name="sprout-fill" class="w-7 h-7 text-primary-500"/></span></p>
                                <p class="font-bold text-slate-600">Lagi gak ada yang butuh bantuan nih.</p>
                                <p class="mt-1 text-sm text-slate-400">Buka kasusmu sendiri atau tunggu teman minta bantuan.</p>
                            </div>
                        @endforelse
                    </div>
                    @if($tasks->hasPages())
                        <div class="mt-6">{{ $tasks->links() }}</div>
                    @endif
                </div>

                {{-- Tugas Saya & Tawaran --}}
                <div data-aos="fade-up" data-aos-delay="80">
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2 mb-5">
                        <span class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600"><x-icon name="clipboard-list" class="w-5 h-5"/></span>
                        Tugas Saya &amp; Tawaran
                    </h2>
                    <div class="space-y-4">
                        @forelse($myTasks as $myTask)
                            <div class="bg-white rounded-3xl border border-slate-100 shadow-soft p-5">
                                <div class="flex justify-between items-center gap-3">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-extrabold text-slate-800">{{ $myTask->title }}</span>
                                            <span class="text-[10px] font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded-md">Reward {{ $myTask->base_reward }} · Lv.{{ $myTask->effort_level }}</span>
                                        </div>
                                    </div>
                                    <span class="shrink-0 text-[10px] font-black uppercase px-3 py-1.5 rounded-full
                                        {{ $myTask->status == 'completed' ? 'bg-emerald-500 text-white' : ($myTask->status == 'accepted' ? 'bg-amber-400 text-white' : ($myTask->status == 'cancelled' ? 'bg-red-500 text-white' : 'bg-primary-100 text-primary-700')) }}">
                                        {{ $myTask->status }}
                                    </span>
                                </div>

                                @if($myTask->status == 'open' || $myTask->status == 'negotiation')
                                    <div class="mt-4 space-y-2">
                                        @forelse($myTask->offers as $offer)
                                            <div class="flex justify-between items-center bg-slate-50 p-3 rounded-2xl border border-slate-100">
                                                <div class="text-xs min-w-0">
                                                    <b class="text-slate-700">{{ $offer->helper->name }}</b>: <span class="text-slate-500">"{{ $offer->conditions[0] ?? '-' }}"</span>
                                                    @if(($offer->extra_fee ?? 0) > 0)
                                                        <span class="text-[10px] font-black text-amber-600 bg-amber-50 px-2 py-0.5 rounded ml-1">+Rp{{ number_format($offer->extra_fee, 0, ',', '.') }}</span>
                                                    @endif
                                                </div>
                                                <form action="{{ url('/offers/'.$offer->id.'/accept') }}" method="POST" class="shrink-0">
                                                    @csrf
                                                    <button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-1.5 rounded-xl font-bold text-[10px] uppercase transition">Terima</button>
                                                </form>
                                            </div>
                                        @empty
                                            <p class="text-xs text-slate-400 italic text-center py-2">Belum ada tawaran masuk.</p>
                                        @endforelse
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-slate-100">
                                        <form action="{{ url('/tasks/'.$myTask->id.'/cancel') }}" method="POST" onsubmit="return confirm('Yakin mau cancel tugas ini?')">
                                            @csrf
                                            <button class="w-full bg-red-50 hover:bg-red-100 text-red-500 px-4 py-2.5 rounded-2xl font-bold text-[10px] uppercase transition inline-flex items-center justify-center gap-1.5"><x-icon name="x" class="w-3.5 h-3.5"/>Cancel Tugas</button>
                                        </form>
                                    </div>
                                @elseif($myTask->status == 'cancelled')
                                    <p class="mt-4 text-center text-red-500 font-bold text-xs italic uppercase tracking-widest bg-red-50 rounded-2xl py-3"><x-icon name="circle-x" class="w-4 h-4 inline-block align-[-2px] mr-1"/>Tugas ini dicancel</p>
                                @elseif($myTask->status == 'accepted')
                                    <div class="mt-4 bg-primary-50 border border-primary-100 rounded-2xl p-4 flex flex-col sm:flex-row justify-between gap-3 sm:items-center">
                                        <p class="text-xs text-primary-800 font-medium">Sedang dikerjakan oleh <b>{{ $myTask->acceptedOffer?->helper?->name ?? 'Helper' }}</b></p>
                                        <form action="{{ url('/tasks/'.$myTask->id.'/complete') }}" method="POST" class="shrink-0">
                                            @csrf
                                            <button class="w-full sm:w-auto text-white bg-amber-500 hover:bg-amber-600 px-5 py-2.5 rounded-2xl font-black text-xs uppercase shadow-soft transition">Konfirmasi Selesai</button>
                                        </form>
                                    </div>
                                @elseif($myTask->status == 'completed')
                                    <p class="mt-4 text-center text-emerald-600 font-bold text-xs italic uppercase tracking-widest bg-emerald-50 rounded-2xl py-3"><x-icon name="circle-check" class="w-4 h-4 inline-block align-[-2px] mr-1"/>Beres &amp; saldo terbayar</p>
                                @endif
                            </div>
                        @empty
                            <div class="bg-white border-2 border-dashed border-slate-200 rounded-3xl p-10 text-center">
                                <p class="mb-3"><span class="inline-flex w-14 h-14 rounded-full bg-amber-50 border border-amber-100 items-center justify-center"><x-icon name="clipboard-fill" class="w-7 h-7 text-amber-600"/></span></p>
                                <p class="font-bold text-slate-600">Lu belum bikin tugas bantuan.</p>
                                <p class="mt-1 text-sm text-slate-400">Mulai dari form "Minta Bantuan" di samping.</p>
                            </div>
                        @endforelse
                    </div>
                    @if($myTasks->hasPages())
                        <div class="mt-6">{{ $myTasks->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</body>
</html>
