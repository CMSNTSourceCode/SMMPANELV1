@php use App\Helpers\Helper; @endphp
@extends('layouts.app')
@section('title', $pageTitle)
@section('content')
  <div class="card custom-card">
    <div class="card-header">
      <h3 class="card-title">{{ __t('Điều khoản sử dụng dịch vụ') }}</h3>
    </div>
    <div class="card-body">
      {!! Helper::getNotice('page_privacy_policy') !!}
    </div>
  </div>
@endsection
