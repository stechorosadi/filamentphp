@extends('errors.layout')

@section('error_code', '503')
@section('error_title'){{ __('ui.error_503_title') }}@endsection
@section('error_description'){{ __('ui.error_503_desc') }}@endsection

@section('error_icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10 text-[var(--accent)]">
    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l5.654-4.654m5.65-4.65 2.497-3.032c.317-.384.74-.626 1.208-.766m0 0a3 3 0 1 1-4.242 4.241M3 3l18 18"/>
</svg>
@endsection
