@extends('errors.layout')

@section('error_code', '419')
@section('error_title', 'Page Expired')
@section('error_description', 'Your session has expired or the security token is no longer valid. This usually happens after a long period of inactivity. Please go back and try again.')

@section('error_icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10 text-[#4F772D] dark:text-[#90A955]">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
</svg>
@endsection
