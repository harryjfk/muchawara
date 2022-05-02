<?php use App\Components\Theme; ?>
@extends('admin.layouts.admin')
@section('content')
@parent

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">Estadísticas</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        
        <h1 style="color : white">Últimas conexiones</h1>
        <label style="color : white">
            Hoy: {{count($statics[0])}}
        </label>
        <br />
        <label style="color : white">
            Ayer: {{count($statics[1])}}
        </label>
        <br />
        <label style="color : white">
            Hace 2 días: {{count($statics[2])}}
        </label>
        <br />
        <label style="color : white">
            Hace 3 días: {{count($statics[3])}}
        </label>
        <br />
        <label style="color : white">
            Hace 4 días: {{count($statics[4])}}
        </label>
        <br />
        <label style="color : white">
            Hace 5 días: {{count($statics[5])}}
        </label>
        <br />
        <label style="color : white">
            Hace 6 días: {{count($statics[6])}}
        </label>
        <br />
        
    </section>
</div>


@endsection
@section('scripts')
{{Theme::render('admin_usermanagement_bottom_scripts')}}
@endsection