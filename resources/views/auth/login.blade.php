<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Masuk ke akun IMPAS-IN — lanjutkan saling bantu & balas budi.">
    <title>Masuk | IMPAS-IN</title>
    @include('partials.head-assets')
</head>
<body class="font-sans bg-slate-50 min-h-screen flex">

    {{-- ============ PANEL BRANDING (kiri) ============ --}}
    <div class="hidden lg:flex w-1/2 brand-bg bg-linear-to-br from-primary-700 via-primary-800 to-primary-950 text-white relative overflow-hidden">
        <div class="relative z-10 w-full max-w-md mx-auto flex flex-col justify-between py-16">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <span class="w-10 h-10 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center font-black text-lg backdrop-blur-sm"><x-icon name="handshake" class="w-5 h-5"/></span>
                <span class="text-xl font-extrabold tracking-tight">IMPAS<span class="text-primary-300">-IN</span></span>
            </a>

            <div>
                <h2 class="text-4xl font-extrabold leading-tight tracking-tight">Satu kebaikan kecil,<br>hutang budi <span class="text-primary-300">lunas terbayar.</span></h2>
                <p class="mt-4 text-primary-100/90 text-sm leading-relaxed">Lanjutkan perjalananmu saling bantu. Setiap kebaikanmu tercatat, setiap budi terbalas.</p>
            </div>

            <div class="space-y-3">
                <div class="float-slow flex items-center gap-3 bg-white/10 backdrop-blur-sm border border-white/15 rounded-2xl p-4">
                    <x-icon name="heart" class="w-6 h-6 text-primary-300 shrink-0"/>
                    <div class="text-xs">
                        <p class="font-bold">Transparan &amp; amanah</p>
                        <p class="text-primary-200/80">semua kebaikan punya jejak</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm border border-white/15 rounded-2xl p-4">
                    <x-icon name="star" class="w-6 h-6 text-amber-300 shrink-0"/>
                    <div class="text-xs">
                        <p class="font-bold">Reputasi naik</p>
                        <p class="text-primary-200/80">semakin sering bantu, semakin dipercaya</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ PANEL FORM (kanan) ============ --}}
    <div class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-md" data-aos="fade-up">
            {{-- Logo mobile --}}
            <a href="{{ url('/') }}" class="lg:hidden flex items-center justify-center gap-2 mb-8">
                <span class="w-10 h-10 rounded-xl bg-primary-600 text-white flex items-center justify-center font-black text-lg shadow-soft"><x-icon name="handshake" class="w-5 h-5"/></span>
                <span class="text-xl font-extrabold tracking-tight text-slate-800">IMPAS<span class="text-primary-600">-IN</span></span>
            </a>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-lift p-8 sm:p-10">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Selamat datang kembali <x-icon name="hand" class="w-6 h-6 inline-block align-[-4px] text-primary-600"/></h1>
                <p class="mt-1.5 text-sm text-slate-500">Masuk untuk lanjut saling bantu &amp; balas budi.</p>

                {{-- Flash error (server error) --}}
                @if(session('error'))
                    <div class="mt-5 bg-red-50 border border-red-200 text-red-600 text-sm font-semibold rounded-2xl p-4 flex items-start gap-2.5">
                        <x-icon name="alert-triangle" class="w-5 h-5 mt-0.5 shrink-0"/>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Validation error (email/password salah) --}}
                @if($errors->any())
                    <div class="mt-5 bg-red-50 border border-red-200 text-red-600 text-sm font-semibold rounded-2xl p-4 flex items-start gap-2.5">
                        <x-icon name="alert-triangle" class="w-5 h-5 mt-0.5 shrink-0"/>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ url('/login') }}" method="POST" class="mt-7 space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email"
                               class="w-full px-4 py-3.5 rounded-2xl border {{ $errors->has('email') ? 'border-red-300 bg-red-50/50' : 'border-slate-200' }} text-sm outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-400 transition placeholder:text-slate-400" required autofocus>
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">Password</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password"
                               class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-400 transition placeholder:text-slate-400" required>
                    </div>
                    <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 font-bold py-3.5 rounded-2xl shadow-soft transition hover:scale-[1.01] active:scale-[0.99]">
                        Masuk
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-slate-100 text-center text-sm text-slate-500">
                    Belum punya akun?
                    <a href="{{ url('/register') }}" class="font-bold text-primary-600 hover:text-primary-700 transition">Daftar gratis</a>
                </div>
            </div>

            <p class="mt-6 text-center text-xs text-slate-400">
                <a href="{{ url('/') }}" class="hover:text-primary-600 transition"><x-icon name="arrow-left" class="w-3.5 h-3.5 inline-block align-[-1px] mr-1"/> Kembali ke beranda</a>
            </p>
        </div>
    </div>

</body>
</html>
