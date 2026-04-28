@extends('errors.layout')

@section('error_code', '404')
@section('error_title', 'Page Not Found')
@section('error_description', 'The page you\'re looking for seems to have wandered off. It may have been moved, renamed, or it never existed. Double-check the URL or use the search to find what you need.')

@section('error_icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10 text-[#4F772D] dark:text-[#90A955]">
    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
</svg>
@endsection
