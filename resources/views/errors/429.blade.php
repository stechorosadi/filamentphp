@extends('errors.layout')

@section('error_code', '429')
@section('error_title', 'Too Many Requests')
@section('error_description', 'You\'ve made too many requests in a short period of time. Please slow down and wait a moment before trying again. This limit helps us keep the service running smoothly for everyone.')

@section('error_icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10 text-[#4F772D] dark:text-[#90A955]">
    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z"/>
</svg>
@endsection
