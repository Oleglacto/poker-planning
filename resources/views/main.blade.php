<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- User-token Token -->
    <meta name="user-token" content="{{ auth()->user()->api_token }}">
    <!-- Styles -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>
<body>
    <section class="content" id="app">
        <el-container style="height: 100vh; border: 1px solid #eee">
            <my-aside-menu></my-aside-menu>
            <el-container direction="vertical">
                <my-header></my-header>
                <el-main>
                    <router-view></router-view>
                </el-main>
            </el-container>
        </el-container>
    </section>
</body>
<script src="/assets/js/app.js"></script>
</html>
