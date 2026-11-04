@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="page-head"><div><h1>Profil Saya</h1><p>Informasi akun pengguna SIMDA.</p></div></div>

<div class="card profile-card">
    <div class="profile-cover"></div>
    <div class="profile-who">
        <span class="avatar">{{ strtoupper(mb_substr($user->name, 0, 1)) }}</span>
        <div style="padding-bottom:6px"><h2>{{ $user->name }}</h2><p>Administrator Tata Usaha</p></div>
    </div>
    <div class="info-grid">
        <div class="info-item"><span class="ico"><x-icon name="user" :size="20" /></span><div><small>Nama Lengkap</small><b>{{ $user->name }}</b></div></div>
        <div class="info-item"><span class="ico"><x-icon name="mail" :size="20" /></span><div><small>Email</small><b>{{ $user->email }}</b></div></div>
        <div class="info-item"><span class="ico"><x-icon name="shield" :size="20" /></span><div><small>Peran</small><b>Administrator</b></div></div>
        <div class="info-item"><span class="ico"><x-icon name="home" :size="20" /></span><div><small>Sistem</small><b>SIMDA SMK</b></div></div>
    </div>
</div>
@endsection
