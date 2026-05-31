@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container">
    <div class="form-container">
        <h2><i class="fas fa-id-card"></i> Mi Perfil</h2>
        <p><strong>Nombre:</strong> {{ auth()->user()->name }}</p>
        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
        <p><strong>Rol:</strong> {{ auth()->user()->rol }}</p>
        @if(auth()->user()->dui)
            <p><strong>DUI:</strong> {{ auth()->user()->dui }}</p>
        @endif
        @if(auth()->user()->telefono)
            <p><strong>Teléfono:</strong> {{ auth()->user()->telefono }}</p>
        @endif
    </div>
</div>
@endsection