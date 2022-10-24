@extends('layouts.default')

@section('message')

<p class="work-message">{{$auth->name}}さんお疲れ様です！</p>
<div class="message_result">
  <p class="message">{{session('message')}}</p>
  <p class="result">{{session('result')}}</p>
</div>

@endsection

@section('content')
  <div class="main_stamp">
    <form action="/start" method="post" class="work_start-stamp">
    @csrf
      <button class="stamp-btn" type="submit"z>勤務開始</button>
    </form>
    <form action="/end" method="post" class="work_end-stamp">
    @csrf
      <button class="stamp-btn" type="submit">勤務終了</button>
    </form>

    <form action="/break/start" method="post" class="break_start-stamp">
    @csrf
      <button class="stamp-btn" type="submit">休憩開始</button>
    </form>
    <form action="/break/end" method="post" class="break_end-stamp">
    @csrf
    <button class="stamp-btn" type="submit">休憩終了</button>
    </form>  
  </div>
@endsection
