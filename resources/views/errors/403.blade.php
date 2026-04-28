@extends('errors.layout')

@section('error_code', '403')
@section('error_title', 'Access Forbidden')
@section('error_description', 'You don\'t have permission to access this page. If you believe this is a mistake, please contact the administrator or log in with an account that has the required permissions.')

@section('error_icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10 text-[#4F772D] dark:text-[#90A955]">
    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
</svg>
@endsection
