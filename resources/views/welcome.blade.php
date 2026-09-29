<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="IMPAS-IN — platform barter bantuan berbasis poin. Minta bantuan, bantu sesama, dan balas budi. Transparan, amanah, dan saling menguatkan.">
    <title>IMPAS-IN | Bantu Dibantu, Hutang Budi Lunas!</title>
    @include('partials.head-assets')
</head>
<body class="bg-white font-sans text-slate-700 antialiased">

    {{-- ===================== NAVBAR ===================== --}}
    <header class="sticky top-0 z-50 backdrop-blur-md bg-white/80 border-b border-slate-100">
        <nav class="max-w-6xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16">
            <a href="/" class="flex items-center gap-2 group">
                <span class="w-9 h-9 rounded-xl bg-primary-600 text-white flex items-center justify-center font-black text-sm shadow-soft group-hover:scale-105 transition"><x-icon name="handshake" class="w-5 h-5"/></span>
                <span class="text-lg font-extrabold tracking-tight text-slate-800">IMPAS<span class="text-primary-600">-IN</span></span>
            </a>
            <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-500">
                <a href="#cara-kerja" class="hover:text-primary-600 transition">Cara Kerja</a>
                <a href="#kasus" class="hover:text-primary-600 transition">Kasus Aktif</a>
                <a href="#transparansi" class="hover:text-primary-600 transition">Transparansi</a>
                <a href="#tentang" class="hover:text-primary-600 transition">Tentang</a>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url('/login') }}" class="text-sm font-bold text-slate-600 hover:text-primary-700 transition px-3 py-2">Masuk</a>
                <a href="{{ url('/register') }}" class="text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 px-4 py-2.5 rounded-xl shadow-soft transition hover:scale-[1.03]">Daftar Gratis</a>
            </div>
        </nav>
    </header>

    {{-- ===================== HERO ===================== --}}
    <section class="hero-grid-bg relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-16 pb-24 grid lg:grid-cols-2 gap-14 items-center">
            <div data-aos="fade-right">
                <span class="inline-flex items-center gap-2 text-xs font-bold text-primary-700 bg-primary-50 border border-primary-100 rounded-full px-3 py-1.5 mb-6">
                    <span class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
                    Komunitas saling bantu · 100% transparan
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-[3.4rem] font-extrabold tracking-tight text-slate-900 leading-[1.1]">
                    Satu kebaikan kecil untuk <span class="text-primary-600">membalas budi</span> & meringankan beban sesama.
                </h1>
                <p class="mt-5 text-lg text-slate-500 leading-relaxed max-w-lg">
                    IMPAS-IN adalah platform barter bantuan berbasis poin. Minta bantuan saat butuh, bantu orang lain saat mampu — setiap kebaikan tercatat, setiap budi terbalas.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="{{ url('/register') }}" class="text-center text-white bg-primary-600 hover:bg-primary-700 font-bold px-8 py-4 rounded-2xl shadow-lift transition hover:-translate-y-0.5">
                        Mulai Bantu Sekarang <x-icon name="arrow-right" class="w-4 h-4 inline-block align-[-2px]"/>
                    </a>
                    <a href="#kasus" class="text-center font-bold text-slate-700 bg-white border-2 border-slate-200 hover:border-primary-400 hover:text-primary-700 px-8 py-4 rounded-2xl transition">
                        Lihat Kasus Aktif
                    </a>
                </div>
                <div class="mt-10 flex items-center gap-6 text-sm">
                    <div class="flex -space-x-3">
                        <span class="w-10 h-10 rounded-full bg-primary-200 border-2 border-white flex items-center justify-center text-primary-700"><x-icon name="user" class="w-5 h-5"/></span>
                        <span class="w-10 h-10 rounded-full bg-emerald-200 border-2 border-white flex items-center justify-center text-emerald-700"><x-icon name="hand-helping" class="w-5 h-5"/></span>
                        <span class="w-10 h-10 rounded-full bg-teal-200 border-2 border-white flex items-center justify-center text-teal-700"><x-icon name="dumbbell" class="w-5 h-5"/></span>
                        @if($stats['members'] > 3)
                            <span class="w-10 h-10 rounded-full bg-primary-600 border-2 border-white text-white flex items-center justify-center text-xs font-bold">+{{ number_format($stats['members'] - 3) }}</span>
                        @endif
                    </div>
                    <p class="text-slate-500"><span class="font-extrabold text-slate-800">{{ $stats['members'] }}</span> orang sudah saling terhubung</p>
                </div>
            </div>

            {{-- Mockup kartu kasus (visual) --}}
            <div class="relative" data-aos="fade-left" data-aos-delay="150">
                <div class="float-slow absolute -top-6 -left-6 w-40 h-40 rounded-3xl bg-primary-100/70 blur-2xl"></div>
                <div class="float-slower absolute -bottom-8 -right-4 w-48 h-48 rounded-3xl bg-emerald-100/70 blur-2xl"></div>
                <div class="relative bg-white rounded-3xl border border-slate-100 shadow-lift p-6 max-w-md mx-auto">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Kasus Terbaru</span>
                        <span class="text-[10px] font-bold text-primary-600 bg-primary-50 rounded-full px-2.5 py-1 inline-flex items-center gap-1"><x-icon name="flame" class="w-3 h-3"/>Butuh bantuan</span>
                    </div>
                    @forelse($activeCases->take(1) as $case)
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                            <h3 class="font-bold text-slate-800">{{ $case->title }}</h3>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $case->description }}</p>
                            <div class="mt-3 flex items-center gap-2 text-[10px] font-bold">
                                <span class="bg-primary-100 text-primary-700 px-2 py-1 rounded-md">Reward {{ $case->base_reward }} poin</span>
                                <span class="bg-amber-100 text-amber-700 px-2 py-1 rounded-md">Lv.{{ $case->effort_level }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                            <h3 class="font-bold text-slate-800">Kasus bantuan pertamamu menanti</h3>
                            <p class="text-xs text-slate-500 mt-1">Belum ada kasus aktif di komunitas. Jadilah yang pertama membuka kasus bantuan!</p>
                            <div class="mt-3 inline-flex items-center gap-2 text-[10px] font-bold">
                                <span class="bg-primary-100 text-primary-700 px-2 py-1 rounded-md">Reward sesuai kamu</span>
                                <span class="bg-amber-100 text-amber-700 px-2 py-1 rounded-md">Level 1–5</span>
                            </div>
                        </div>
                    @endforelse
                    <div class="mt-4 bg-white border border-slate-100 rounded-2xl p-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-600"><x-icon name="handshake" class="w-4 h-4"/></span>
                            <div class="text-[10px]">
                                <p class="font-bold text-slate-700">Transparan & tercatat</p>
                                <p class="text-slate-400">setiap kebaikan ada jejaknya</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-black text-primary-600 bg-primary-50 rounded-full px-2.5 py-1 inline-flex items-center gap-1"><x-icon name="sparkles" class="w-3 h-3"/>Amanah</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Marquee trust strip --}}
        <div class="border-t border-slate-100 bg-white/60 backdrop-blur-sm py-4 overflow-hidden">
            <div class="marquee-track flex gap-12 whitespace-nowrap w-max text-sm font-semibold text-slate-400">
                @php $phrases = ['Saling bantu tanpa pamrih', 'Poin bukti kebaikanmu', 'Transparan & amanah', 'Hutang budi lunas terbayar', 'Komunitas saling menguatkan', 'Setiap bantuan tercatat', 'Balas budi dengan kebaikan', 'Bangun reputasi lewat aksi nyata']; @endphp
                @for($i = 0; $i < 2; $i++)
                    @foreach($phrases as $ph)
                        <span class="flex items-center gap-12"><x-icon name="handshake" class="w-4 h-4 text-primary-400"/> {{ $ph }}</span>
                    @endforeach
                @endfor
            </div>
        </div>
    </section>

    {{-- ===================== BENTO STATISTIK ===================== --}}
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-2xl mx-auto mb-14" data-aos="fade-up">
                <span class="text-xs font-black text-primary-600 uppercase tracking-widest">Transparansi</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Bukti nyata, bukan janji</h2>
                <p class="mt-4 text-slate-500">Setiap angka di bawah ini diambil langsung dari data komunitas IMPAS-IN. Semua tercatat, semua terlihat.</p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5">
                {{-- Kartu besar: member --}}
                <div class="col-span-2 lg:col-span-2 lg:row-span-2 bg-slate-900 rounded-3xl p-8 text-white relative overflow-hidden" data-aos="zoom-in">
                    <div class="absolute -top-10 -right-10 w-44 h-44 rounded-full bg-primary-500/20 blur-2xl"></div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Komunitas</span>
                    <p class="mt-3 text-6xl font-extrabold tracking-tight">{{ $stats['members'] }}</p>
                    <p class="mt-2 font-semibold text-slate-200">Anggota terdaftar</p>
                    <div class="mt-6 pt-6 border-t border-white/10 flex flex-wrap gap-2 text-[11px] font-bold">
                        <span class="bg-white/10 rounded-full px-3 py-1.5">{{ $stats['tasks_total'] }} kasus dibuat</span>
                        <span class="bg-white/10 rounded-full px-3 py-1.5">{{ $stats['offers_count'] }} bantuan ditawarkan</span>
                    </div>
                </div>

                {{-- Poin beredar --}}
                <div class="bg-primary-50 border border-primary-100 rounded-3xl p-6" data-aos="zoom-in" data-aos-delay="100">
                    <x-icon name="wallet" class="w-6 h-6 text-primary-600"/>
                    <p class="mt-3 text-3xl font-extrabold text-slate-900">{{ number_format($stats['points_total']) }}</p>
                    <p class="mt-1 text-xs font-semibold text-slate-500">Poin hutang budi beredar</p>
                </div>

                {{-- Kasus aktif --}}
                <div class="bg-emerald-50 border border-emerald-100 rounded-3xl p-6" data-aos="zoom-in" data-aos-delay="150">
                    <x-icon name="flame" class="w-6 h-6 text-emerald-600"/>
                    <p class="mt-3 text-3xl font-extrabold text-slate-900">{{ $stats['tasks_active'] }}</p>
                    <p class="mt-1 text-xs font-semibold text-slate-500">Kasus butuh bantuan sekarang</p>
                </div>

                {{-- Bantuan tuntas --}}
                <div class="bg-teal-50 border border-teal-100 rounded-3xl p-6" data-aos="zoom-in" data-aos-delay="100">
                    <x-icon name="badge-check" class="w-6 h-6 text-teal-600"/>
                    <p class="mt-3 text-3xl font-extrabold text-slate-900">{{ $stats['tasks_completed'] }}</p>
                    <p class="mt-1 text-xs font-semibold text-slate-500">Bantuan tuntas terbayar</p>
                </div>

                {{-- Transaksi tercatat --}}
                <div class="bg-amber-50 border border-amber-100 rounded-3xl p-6" data-aos="zoom-in" data-aos-delay="150">
                    <x-icon name="scroll-text" class="w-6 h-6 text-amber-600"/>
                    <p class="mt-3 text-3xl font-extrabold text-slate-900">{{ $stats['transactions'] }}</p>
                    <p class="mt-1 text-xs font-semibold text-slate-500">Transaksi kebaikan tercatat</p>
                </div>

                {{-- CTA bento --}}
                <div class="col-span-2 bg-linear-to-br from-primary-600 to-primary-800 rounded-3xl p-6 flex items-center justify-between gap-4" data-aos="zoom-in" data-aos-delay="200">
                    <div>
                        <p class="font-extrabold text-white text-lg">Jadilah bagian dari kebaikan</p>
                        <p class="text-primary-100 text-xs mt-1">Gratis daftar, langsung bisa bantu.</p>
                    </div>
                    <a href="{{ url('/register') }}" class="shrink-0 bg-white text-primary-700 font-bold text-sm px-5 py-3 rounded-2xl shadow hover:scale-105 transition">Daftar</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== KASUS AKTIF ===================== --}}
    <section id="kasus" class="py-20 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10" data-aos="fade-up">
                <div>
                    <span class="text-xs font-black text-primary-600 uppercase tracking-widest">Kasus Aktif</span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Mereka butuh bantuanmu</h2>
                    <p class="mt-3 text-slate-500 max-w-lg">Pilih kasus, tawarkan bantuanmu, dan raih poin plus reputasi. Semua transparan.</p>
                </div>
                <a href="{{ url('/register') }}" class="shrink-0 text-primary-700 border-2 border-primary-200 hover:border-primary-500 hover:bg-primary-50 font-bold text-sm px-6 py-3 rounded-2xl transition">+ Buka Kasus Baru</a>
            </div>

            @if($activeCases->isEmpty())
                <div class="bg-white border-2 border-dashed border-slate-200 rounded-3xl p-12 text-center" data-aos="fade-up">
                    <span class="mx-auto inline-flex w-20 h-20 rounded-full bg-primary-50 border border-primary-100 items-center justify-center"><x-icon name="sprout-fill" class="w-10 h-10 text-primary-500"/></span>
                    <h3 class="mt-4 text-xl font-extrabold text-slate-800">Belum ada kasus aktif</h3>
                    <p class="mt-2 text-slate-500 max-w-md mx-auto">Komunitas baru tumbuh. Buka kasus pertamamu dan jadilah pelopor budaya saling bantu di sini.</p>
                    <a href="{{ url('/register') }}" class="inline-block mt-6 text-white bg-primary-600 hover:bg-primary-700 font-bold px-7 py-3.5 rounded-2xl shadow-soft transition hover:-translate-y-0.5">Buka Kasus Pertama <x-icon name="arrow-right" class="w-4 h-4 inline-block align-[-2px]"/></a>
                </div>
            @else
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($activeCases as $case)
                        <div class="bg-white rounded-3xl border border-slate-100 p-6 hover:shadow-lift hover:-translate-y-1 transition duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center text-sm">{{ mb_substr($case->creator->name, 0, 1) }}</span>
                                    <div class="text-xs">
                                        <p class="font-bold text-slate-700">{{ $case->creator->name }}</p>
                                        <p class="text-slate-400">{{ $case->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold {{ $case->status == 'negotiation' ? 'bg-amber-100 text-amber-700' : 'bg-primary-100 text-primary-700' }} rounded-full px-2.5 py-1 uppercase">{{ $case->status == 'negotiation' ? 'Ada tawaran' : 'Terbuka' }}</span>
                            </div>
                            <h3 class="mt-4 font-extrabold text-slate-800 text-lg leading-snug">{{ $case->title }}</h3>
                            <p class="mt-1.5 text-sm text-slate-500 line-clamp-3">{{ $case->description }}</p>
                            <div class="mt-4 flex gap-2 text-[10px] font-bold flex-wrap">
                                <span class="bg-primary-50 text-primary-700 px-2.5 py-1 rounded-lg">Reward {{ $case->base_reward }} poin</span>
                                <span class="bg-amber-50 text-amber-700 px-2.5 py-1 rounded-lg">Lv.{{ $case->effort_level }}</span>
                            </div>
                            {{-- Progress bar: 1 tawaran = 33% (target 3) --}}
                            <div class="mt-5">
                                <div class="flex justify-between text-[10px] font-bold text-slate-400 mb-1.5">
                                    <span>Respon bantuan</span>
                                    <span class="text-primary-600">{{ min($case->offers_count, 3) }}/3 tawaran</span>
                                </div>
                                <div class="relative h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="progress-shine absolute inset-y-0 left-0 bg-linear-to-r from-primary-500 to-primary-400 rounded-full transition-all duration-700" style="width: {{ min(($case->offers_count / 3) * 100, 100) }}%"></div>
                                </div>
                            </div>
                            <a href="{{ url('/register') }}" class="mt-5 block text-center text-white bg-primary-600 hover:bg-primary-700 font-bold text-sm py-3 rounded-2xl transition hover:scale-[1.02]">Saya Bisa Bantu</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ===================== LOG KEBAIKAN ===================== --}}
    <section id="transparansi" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 grid lg:grid-cols-2 gap-14 items-start">
            <div data-aos="fade-right">
                <span class="text-xs font-black text-primary-600 uppercase tracking-widest">Log Kebaikan</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Transparan seperti commit history</h2>
                <p class="mt-4 text-slate-500 leading-relaxed">Setiap bantuan yang tuntas tercatat otomatis: siapa yang dibantu, siapa yang membantu, dan berapa poin budi yang terbalas. Tidak ada kebaikan yang hilang tanpa jejak.</p>
                <div class="mt-8 grid grid-cols-3 gap-4">
                    <div class="bg-primary-50 rounded-2xl p-4 text-center">
                        <p class="text-2xl font-extrabold text-primary-700">{{ $stats['transactions'] }}</p>
                        <p class="text-[11px] font-semibold text-slate-500 mt-1">Bantuan tuntas</p>
                    </div>
                    <div class="bg-emerald-50 rounded-2xl p-4 text-center">
                        <p class="text-2xl font-extrabold text-emerald-700">{{ $stats['tasks_completed'] }}</p>
                        <p class="text-[11px] font-semibold text-slate-500 mt-1">Hutang lunas</p>
                    </div>
                    <div class="bg-amber-50 rounded-2xl p-4 text-center">
                        <p class="text-2xl font-extrabold text-amber-700">{{ $stats['points_total'] }}</p>
                        <p class="text-[11px] font-semibold text-slate-500 mt-1">Poin beredar</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 rounded-3xl p-6 sm:p-8" data-aos="fade-left">
                <div class="flex items-center gap-2 mb-6">
                    <span class="w-3 h-3 rounded-full bg-red-400"></span>
                    <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                    <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                    <span class="ml-3 text-xs font-bold text-slate-400">log-kebaikan.log</span>
                </div>
                <div class="space-y-4 font-mono text-xs">
                    @forelse($goodDeeds as $deed)
                        <div class="flex items-start gap-3 text-slate-300">
                            <x-icon name="check" class="w-4 h-4 text-primary-400 mt-0.5 shrink-0"/>
                            <p><span class="text-emerald-300 font-bold">{{ $deed->receiver->name }}</span> dibantu oleh <span class="text-primary-300 font-bold">{{ $deed->sender->name }}</span> · <span class="text-amber-300 font-bold">{{ $deed->points }} poin</span> · <span class="text-slate-500">{{ $deed->created_at->diffForHumans() }}</span></p>
                        </div>
                    @empty
                        <div class="text-slate-400 text-center py-6">
                            <p class="mb-3"><span class="inline-flex w-14 h-14 rounded-full bg-white/10 items-center justify-center"><x-icon name="sprout-fill" class="w-7 h-7 text-primary-400"/></span></p>
                            <p class="font-bold text-slate-300">Log kebaikan masih kosong</p>
                            <p class="mt-1 text-slate-500 font-sans">Kebaikan pertamamu akan tercatat di sini.</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-6 pt-5 border-t border-white/10 flex items-center justify-between">
                    <span class="text-[11px] text-slate-500 inline-flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>live · data real-time</span>
                    <a href="{{ url('/register') }}" class="text-[11px] font-bold text-primary-400 hover:text-primary-300 transition">Mulai menulis log <x-icon name="arrow-right" class="w-3 h-3 inline-block align-[-1px]"/></a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CARA KERJA ===================== --}}
    <section id="cara-kerja" class="py-20 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-2xl mx-auto mb-14" data-aos="fade-up">
                <span class="text-xs font-black text-primary-600 uppercase tracking-widest">Cara Kerja</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Gampang, tiga langkah</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @php
                    $steps = [
                        ['1', 'user-plus', 'Buka kasus', 'Butuh bantuan apa pun? Tulis kasusmu, tentukan reward poin dan seberapa berat (level 1–5).'],
                        ['2', 'handshake', 'Tawarkan bantuan', 'Lihat kasus orang lain, tawarkan bantuanmu dengan syarat & fee opsional yang kamu tentukan.'],
                        ['3', 'star', 'Selesai & terbalas', 'Konfirmasi selesai, poin berpindah, dan reputasimu naik sesuai level bantuan yang kamu beri.'],
                    ];
                @endphp
                @foreach($steps as $i => [$num, $icon, $title, $desc])
                    <div class="relative bg-white rounded-3xl border border-slate-100 p-7 hover:shadow-lift transition duration-300" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                        <span class="absolute top-6 right-6 text-5xl font-black text-slate-100 select-none">{{ $num }}</span>
                        <span class="inline-flex w-14 h-14 rounded-2xl bg-primary-50 border border-primary-100 items-center justify-center text-primary-600">{{ <x-icon name="{{ $icon }}" class="w-7 h-7"/> }}</span>
                        <h3 class="mt-5 text-lg font-extrabold text-slate-800">{{ $title }}</h3>
                        <p class="mt-2 text-sm text-slate-500 leading-relaxed">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== CTA FINAL ===================== --}}
    <section class="py-24 bg-linear-to-br from-primary-700 to-primary-900 relative overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-primary-500/20 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-emerald-400/20 blur-3xl"></div>
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 text-center" data-aos="zoom-in">
            <span class="mx-auto inline-flex w-16 h-16 rounded-full bg-white/10 items-center justify-center"><x-icon name="heart-fill" class="w-8 h-8 text-primary-300"/></span>
            <h2 class="mt-5 text-3xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight">Kebaikan kecil hari ini,<br>kenangan besar esok hari.</h2>
            <p class="mt-5 text-primary-100 text-lg">Gratis daftar. Tanpa biaya. Hanya niat tulus untuk saling bantu.</p>
            <div class="mt-9 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/register') }}" class="text-primary-800 bg-white hover:bg-primary-50 font-bold px-9 py-4 rounded-2xl shadow-lift transition hover:-translate-y-0.5">Daftar Sekarang</a>
                <a href="{{ url('/login') }}" class="text-white border-2 border-white/40 hover:border-white hover:bg-white/10 font-bold px-9 py-4 rounded-2xl transition">Masuk Akun</a>
            </div>
        </div>
    </section>

    {{-- ===================== FOOTER ===================== --}}
    <footer id="tentang" class="bg-slate-950 text-slate-400 pt-16 pb-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid md:grid-cols-4 gap-10 pb-12 border-b border-white/10">
                <div class="md:col-span-2">
                    <a href="/" class="flex items-center gap-2">
                        <span class="w-9 h-9 rounded-xl bg-primary-600 text-white flex items-center justify-center font-black text-sm"><x-icon name="handshake" class="w-5 h-5"/></span>
                        <span class="text-lg font-extrabold tracking-tight text-white">IMPAS<span class="text-primary-500">-IN</span></span>
                    </a>
                    <p class="mt-4 text-sm leading-relaxed max-w-sm">Platform barter bantuan berbasis poin. Minta bantuan, bantu sesama, dan balas budi — semua transparan dan tercatat.</p>
                </div>
                <div>
                    <p class="text-xs font-black text-white uppercase tracking-widest mb-4">Jelajahi</p>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#kasus" class="hover:text-primary-400 transition">Kasus Aktif</a></li>
                        <li><a href="#cara-kerja" class="hover:text-primary-400 transition">Cara Kerja</a></li>
                        <li><a href="#transparansi" class="hover:text-primary-400 transition">Transparansi</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-xs font-black text-white uppercase tracking-widest mb-4">Mulai</p>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ url('/register') }}" class="hover:text-primary-400 transition">Daftar Gratis</a></li>
                        <li><a href="{{ url('/login') }}" class="hover:text-primary-400 transition">Masuk</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition">Panduan Komunitas</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 flex flex-col sm:flex-row justify-between gap-3 text-xs">
                <p>© {{ date('Y') }} IMPAS-IN · Komunitas saling bantu & balas budi</p>
                <p class="text-slate-600">Dibuat dengan <x-icon name="heart" class="w-3.5 h-3.5 inline-block align-[-2px] text-primary-500"/> untuk sesama</p>
            </div>
        </div>
    </footer>

</body>
</html>
