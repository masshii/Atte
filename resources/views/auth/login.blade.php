<style>
.header {
    height: 80px;
}

.header_inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 100%;
    padding: 0 40px;
}

.title {
    font-size: 32px;
}

.header_nav {
    font-size: 15px;
    font-weight: bold;
    padding:  50px;
}

.nav-first {
    color: #000;
    padding-right: 50px;
    text-decoration: none;
}

.nav-second {
    color: #000;
    padding-right: 50px;
    text-decoration: none;
}

.nav-third {
    color: #000;
    text-decoration: none;
}

.work-message {
    font-size: 20px;
    font-weight: bold;
    padding: 40px;
    text-align: center;
}

.container {
    width: 70%;
    height: 70%;
    margin: 0 auto;
}

.content {
    background: #f5f5f5;
    height: 600px;
}

.message {
    color: silver;
    font-size: 13px;
    text-align: center;
}

.footer {
    padding: 15px;
    text-align: center;
}
</style>
<header class="header">
    <div class="header_inner">
        <h1 class="title">Atte</h1>
        <nav class="header_nav">
            <a  class="nav-first" href="{{ route('index') }}" >ホーム</a>
            <a  class="nav-second" href="{{ route('attendance', 0 ) }}">日付一覧</a>
            <a  class="nav-third" href="/logout">ログアウト</a>
        </nav>
    </div>
</header>

<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />

                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />

                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="ml-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>

        <p class="message">アカウントをお持ちでない方はこちらから</p>
        <form method="POST" action="{{ route('register') }}" style="text-align: center;">
            @csrf
            <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
                会員登録
            </x-nav-link>
        </form>
        
    </x-auth-card>    
</x-guest-layout>

<footer class="footer">
    <small class="copyright">Atte,inc</small>
</footer>

