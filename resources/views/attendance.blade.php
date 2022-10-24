@extends('layouts.default')

@section('content')
<div class="date-container">
  <a class="arrow" href="{!! '/attendance/' . ($num - 1) !!}">&lt;</a>
    {{ $date }}
  <a class="arrow" href="{!! '/attendance/' . ($num + 1) !!}">&gt;</a>
</div>

<div class="attendance_data">
  <table>
    <thead>
      <tr>
        <th>名前</th>
        <th>勤務開始</th>
        <th>勤務終了</th>
        <th>休憩時間</th>
        <th>勤務時間</th>
      </tr>
    </thead>

    <tbody>
    @foreach($pageData as $attendance)
      <tr>
        <td>{{ $attendance['name']}}</td>
        <td>{{ $attendance['start_time']}}</td>
        <td>{{ $attendance['end_time']}}</td>
        <td>{{ $attendance['total_break']}}</td>
        <td>{{ $attendance['total_work']}}</td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>

  {{ $pageData->links('vendor.pagination.tailwind2') }}

@endsection