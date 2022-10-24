<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>@yield('title')</title>

  <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css')}}">

<body>
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
  
  <main class="content">
      @yield('message')
    <div class="container">
      @yield('content')
    </div>
  </main>

  <footer class="footer">
    <small class="copyright">Atte,inc</small>
  </footer>
</body>
</html>