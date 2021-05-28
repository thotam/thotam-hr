@extends('layouts.layout-2')
@section('styles')
    <link rel="stylesheet" href="{{ mix('/vendor/libs/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ mix('/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}">
@endsection

@section('scripts')
    <!-- Dependencies -->
    <script src="{{ mix('/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ mix('/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
@endsection


@section('content')
    <h4 class="font-weight-bold py-3 mb-4">{{ $title }}</h4>

    @if (isset($msg))
        <div class="card mb-4">
            <h4 class="text-center text-danger font-weight-bolder pt-3 mb-3">
                <i class="fas mr-1 fa-exclamation-circle"></i> {{ $msg }}
            </h4>
        </div>
    @endif

    <div class="card overflow-hidden p-3">

        @livewire('thotam-hr::update-info-livewire')

    </div>

@endsection
