@extends('templates.index')

@section('content')
<div class="container" style="padding: 40px 15px;">
  <div class="agileits-single-top">
    <ol class="breadcrumb">
      <li><a href="{{ route('home') }}">Home</a></li>
      <li class="active">Profil Saya</li>
    </ol>
  </div>

  <div class="row">
    {{-- Kartu Ringkasan Profil --}}
    <div class="col-md-4">
      <div class="panel panel-default" style="border-top: 3px solid #ff8d1b;">
        <div class="panel-heading">
          <h4 class="panel-title"><strong>Informasi Akun</strong></h4>
        </div>
        <div class="panel-body">
          <p><strong>Nama:</strong> {{ $user->name }}</p>
          <p><strong>Email:</strong> {{ $user->email }}</p>
          <p><strong>Role:</strong> <span class="label label-warning">{{ strtoupper($user->role->name ?? 'User') }}</span></p>
          <hr>
          <p><strong>Umur:</strong> {{ $user->profile->umur ?? '-' }} tahun</p>
          <p><strong>Alamat:</strong> {{ $user->profile->alamat ?? '-' }}</p>
          <p><strong>Bio:</strong> {{ $user->profile->bio ?? '-' }}</p>
        </div>
      </div>
    </div>

    {{-- Form Edit Profil --}}
    <div class="col-md-8">
      <div class="panel panel-default" style="border-top: 3px solid #ff8d1b;">
        <div class="panel-heading">
          <h4 class="panel-title"><strong>Edit Profil Saya</strong></h4>
        </div>
        <div class="panel-body">
          @if (session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif

          @if ($errors->any())
            <div class="alert alert-danger">
              @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
              @endforeach
            </div>
          @endif

          <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
              <label for="name">Nama Lengkap</label>
              <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
              <label for="umur">Umur</label>
              <input type="number" name="umur" id="umur" class="form-control" value="{{ old('umur', $user->profile->umur ?? '') }}" required>
            </div>

            <div class="form-group">
              <label for="alamat">Alamat</label>
              <textarea name="alamat" id="alamat" rows="2" class="form-control" required>{{ old('alamat', $user->profile->alamat ?? '') }}</textarea>
            </div>

            <div class="form-group">
              <label for="bio">Bio</label>
              <textarea name="bio" id="bio" rows="3" class="form-control">{{ old('bio', $user->profile->bio ?? '') }}</textarea>
            </div>

            <hr>
            <p class="text-muted"><small>Kosongkan bagian password di bawah ini jika tidak ingin mengganti password.</small></p>

            <div class="form-group">
              <label for="password">Password Baru (Opsional)</label>
              <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 8 karakter">
            </div>

            <div class="form-group">
              <label for="password_confirmation">Konfirmasi Password Baru</label>
              <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password baru">
            </div>

            <button type="submit" class="btn" style="background-color: #ff8d1b; color: #fff; font-weight: bold;">
              Simpan Perubahan
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection