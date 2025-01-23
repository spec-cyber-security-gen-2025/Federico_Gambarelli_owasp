<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    @vite(['resources/css/app.css'])
    {{$style ?? ''}}
</head>
<body>
    <x-layouts._nav/>
    @if(session('errors'))
    <div class="alert alert-danger" role="alert">{{session('errors')}}</div>
    @endif
    @if(session('message'))
    <div class="alert alert-success" role="alert">{{session('message')}}</div>
    @endif
    <main>
        {{$slot}}
    </main>
    <x-layouts._footer/>
    @vite(['resources/js/app.js'])
    {{$scripts ?? ''}}
</body>
</html>