@extends('templates.index')

@section('content')
<div class="container" style="padding: 35px 15px;">
  <div class="agileits-single-top">
    <ol class="breadcrumb">
      <li><a href="{{ route('home') }}">Home</a></li>
      <li class="active">Dashboard Administrator</li>
    </ol>
  </div>

  <h2 style="margin-bottom: 20px; font-weight: bold;">Dashboard Administrator</h2>

  @if (session('success'))
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      {{ session('success') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif

  {{-- Menu Navigasi Tab Admin --}}
  <ul class="nav nav-tabs" style="margin-bottom: 25px;">
    <li class="{{ $tab == 'films' ? 'active' : '' }}">
      <a href="{{ route('admin.dashboard', ['tab' => 'films']) }}"><strong>Data Film ({{ $films->count() }})</strong></a>
    </li>
    <li class="{{ $tab == 'genres' ? 'active' : '' }}">
      <a href="{{ route('admin.dashboard', ['tab' => 'genres']) }}"><strong>Data Genre ({{ $genres->count() }})</strong></a>
    </li>
    <li class="{{ $tab == 'casts' ? 'active' : '' }}">
      <a href="{{ route('admin.dashboard', ['tab' => 'casts']) }}"><strong>Data Cast ({{ $casts->count() }})</strong></a>
    </li>
    <li class="{{ $tab == 'perans' ? 'active' : '' }}">
      <a href="{{ route('admin.dashboard', ['tab' => 'perans']) }}"><strong>Data Peran ({{ $perans->count() }})</strong></a>
    </li>
    <li class="{{ $tab == 'users' ? 'active' : '' }}">
      <a href="{{ route('admin.dashboard', ['tab' => 'users']) }}"><strong>List Data User ({{ $users->count() }})</strong></a>
    </li>
  </ul>

  {{-- ==================== TAB 1: CRUD FILM ==================== --}}
  @if ($tab == 'films')
    <div class="panel panel-default" style="border-top: 3px solid #ff8d1b;">
      <div class="panel-heading clearfix">
        <h4 class="panel-title pull-left" style="padding-top: 6px;"><strong>Manajemen Data Film</strong></h4>
        <button class="btn btn-sm pull-right" style="background: #ff8d1b; color: #fff; font-weight: bold;" data-toggle="modal" data-target="#modalTambahFilm">
          + Tambah Film
        </button>
      </div>
      <div class="panel-body table-responsive">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th width="50">No</th>
              <th width="80">Poster</th>
              <th>Judul Film</th>
              <th>Genre</th>
              <th width="80">Tahun</th>
              <th>Sinopsis</th>
              <th width="140">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($films as $index => $film)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td><img src="/{{ $film->poster }}" alt="{{ $film->title }}" style="width: 60px; height: 80px; object-fit: cover;"></td>
                <td><strong>{{ $film->title }}</strong></td>
                <td>{{ $film->genre->name ?? '-' }}</td>
                <td>{{ $film->year }}</td>
                <td>{{ \Illuminate\Support\Str::limit($film->sinopsis, 80) }}</td>
                <td>
                  <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modalEditFilm{{ $film->id }}">Edit</button>
                  <form action="{{ route('admin.films.destroy', $film->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus film ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                  </form>
                </td>
              </tr>

              {{-- Modal Edit Film --}}
              <div class="modal fade" id="modalEditFilm{{ $film->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <form action="{{ route('admin.films.update', $film->id) }}" method="POST" enctype="multipart/form-data">
                      @csrf
                      @method('PUT')
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Film: {{ $film->title }}</h4>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <label>Judul Film</label>
                          <input type="text" name="title" class="form-control" value="{{ $film->title }}" required>
                        </div>
                        <div class="form-group">
                          <label>Genre</label>
                          <select name="genre_id" class="form-control" required>
                            @foreach ($genres as $g)
                              <option value="{{ $g->id }}" {{ $film->genre_id == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="form-group">
                          <label>Tahun Rilis</label>
                          <input type="number" name="year" class="form-control" value="{{ $film->year }}" required>
                        </div>
                        <div class="form-group">
                          <label>Sinopsis</label>
                          <textarea name="sinopsis" rows="3" class="form-control" required>{{ $film->sinopsis }}</textarea>
                        </div>
                        <div class="form-group">
                          <label>Ganti Poster (Kosongkan jika tidak diubah)</label>
                          <input type="file" name="poster" class="form-control" accept="image/*">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn" style="background: #ff8d1b; color: #fff;">Simpan Perubahan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- Modal Tambah Film --}}
    <div class="modal fade" id="modalTambahFilm" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.films.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Tambah Film Baru</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Judul Film</label>
                <input type="text" name="title" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Genre</label>
                <select name="genre_id" class="form-control" required>
                  <option value="">-- Pilih Genre --</option>
                  @foreach ($genres as $g)
                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Tahun Rilis</label>
                <input type="number" name="year" class="form-control" value="{{ date('Y') }}" required>
              </div>
              <div class="form-group">
                <label>Sinopsis</label>
                <textarea name="sinopsis" rows="3" class="form-control" required></textarea>
              </div>
              <div class="form-group">
                <label>Poster Film</label>
                <input type="file" name="poster" class="form-control" accept="image/*" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn" style="background: #ff8d1b; color: #fff;">Simpan Film</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

  {{-- ==================== TAB 2: CRUD GENRE ==================== --}}
  @if ($tab == 'genres')
    <div class="panel panel-default" style="border-top: 3px solid #ff8d1b;">
      <div class="panel-heading clearfix">
        <h4 class="panel-title pull-left" style="padding-top: 6px;"><strong>Manajemen Data Genre</strong></h4>
        <button class="btn btn-sm pull-right" style="background: #ff8d1b; color: #fff; font-weight: bold;" data-toggle="modal" data-target="#modalTambahGenre">
          + Tambah Genre
        </button>
      </div>
      <div class="panel-body table-responsive">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th width="60">No</th>
              <th>Nama Genre</th>
              <th width="150">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($genres as $index => $genre)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $genre->name }}</td>
                <td>
                  <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modalEditGenre{{ $genre->id }}">Edit</button>
                  <form action="{{ route('admin.genres.destroy', $genre->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus genre ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                  </form>
                </td>
              </tr>

              {{-- Modal Edit Genre --}}
              <div class="modal fade" id="modalEditGenre{{ $genre->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <form action="{{ route('admin.genres.update', $genre->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Genre</h4>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <label>Nama Genre</label>
                          <input type="text" name="name" class="form-control" value="{{ $genre->name }}" maxlength="45" required>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn" style="background: #ff8d1b; color: #fff;">Simpan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- Modal Tambah Genre --}}
    <div class="modal fade" id="modalTambahGenre" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.genres.store') }}" method="POST">
            @csrf
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Tambah Genre Baru</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Nama Genre</label>
                <input type="text" name="name" class="form-control" maxlength="45" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn" style="background: #ff8d1b; color: #fff;">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

  {{-- ==================== TAB 3: CRUD CAST ==================== --}}
  @if ($tab == 'casts')
    <div class="panel panel-default" style="border-top: 3px solid #ff8d1b;">
      <div class="panel-heading clearfix">
        <h4 class="panel-title pull-left" style="padding-top: 6px;"><strong>Manajemen Data Cast (Aktor/Aktris)</strong></h4>
        <button class="btn btn-sm pull-right" style="background: #ff8d1b; color: #fff; font-weight: bold;" data-toggle="modal" data-target="#modalTambahCast">
          + Tambah Cast
        </button>
      </div>
      <div class="panel-body table-responsive">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th width="60">No</th>
              <th>Nama Cast</th>
              <th width="80">Umur</th>
              <th>Bio</th>
              <th width="150">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($casts as $index => $cast)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $cast->name }}</strong></td>
                <td>{{ $cast->age }} th</td>
                <td>{{ \Illuminate\Support\Str::limit($cast->bio, 90) }}</td>
                <td>
                  <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modalEditCast{{ $cast->id }}">Edit</button>
                  <form action="{{ route('admin.casts.destroy', $cast->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus cast ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                  </form>
                </td>
              </tr>

              {{-- Modal Edit Cast --}}
              <div class="modal fade" id="modalEditCast{{ $cast->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <form action="{{ route('admin.casts.update', $cast->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Cast</h4>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <label>Nama Cast</label>
                          <input type="text" name="name" class="form-control" value="{{ $cast->name }}" maxlength="45" required>
                        </div>
                        <div class="form-group">
                          <label>Umur</label>
                          <input type="number" name="age" class="form-control" value="{{ $cast->age }}" required>
                        </div>
                        <div class="form-group">
                          <label>Bio</label>
                          <textarea name="bio" rows="3" class="form-control" required>{{ $cast->bio }}</textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn" style="background: #ff8d1b; color: #fff;">Simpan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- Modal Tambah Cast --}}
    <div class="modal fade" id="modalTambahCast" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.casts.store') }}" method="POST">
            @csrf
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Tambah Cast Baru</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Nama Cast</label>
                <input type="text" name="name" class="form-control" maxlength="45" required>
              </div>
              <div class="form-group">
                <label>Umur</label>
                <input type="number" name="age" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Bio</label>
                <textarea name="bio" rows="3" class="form-control" required></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn" style="background: #ff8d1b; color: #fff;">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

  {{-- ==================== TAB 4: CRUD PERAN ==================== --}}
  @if ($tab == 'perans')
    <div class="panel panel-default" style="border-top: 3px solid #ff8d1b;">
      <div class="panel-heading clearfix">
        <h4 class="panel-title pull-left" style="padding-top: 6px;"><strong>Manajemen Data Peran</strong></h4>
        <button class="btn btn-sm pull-right" style="background: #ff8d1b; color: #fff; font-weight: bold;" data-toggle="modal" data-target="#modalTambahPeran">
          + Tambah Peran
        </button>
      </div>
      <div class="panel-body table-responsive">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th width="60">No</th>
              <th>Judul Film</th>
              <th>Nama Cast</th>
              <th>Peran / Karakter</th>
              <th width="150">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($perans as $index => $peran)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $peran->film->title ?? 'Film #' . $peran->film_id }}</td>
                <td>{{ $peran->cast->name ?? 'Cast #' . $peran->cast_id }}</td>
                <td><strong>{{ $peran->actor }}</strong></td>
                <td>
                  <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modalEditPeran{{ $peran->id }}">Edit</button>
                  <form action="{{ route('admin.perans.destroy', $peran->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus peran ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                  </form>
                </td>
              </tr>

              {{-- Modal Edit Peran --}}
              <div class="modal fade" id="modalEditPeran{{ $peran->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <form action="{{ route('admin.perans.update', $peran->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Peran</h4>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <label>Pilih Film</label>
                          <select name="film_id" class="form-control" required>
                            @foreach ($films as $f)
                              <option value="{{ $f->id }}" {{ $peran->film_id == $f->id ? 'selected' : '' }}>{{ $f->title }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="form-group">
                          <label>Pilih Cast</label>
                          <select name="cast_id" class="form-control" required>
                            @foreach ($casts as $c)
                              <option value="{{ $c->id }}" {{ $peran->cast_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="form-group">
                          <label>Nama Peran / Karakter</label>
                          <input type="text" name="actor" class="form-control" value="{{ $peran->actor }}" required>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn" style="background: #ff8d1b; color: #fff;">Simpan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- Modal Tambah Peran --}}
    <div class="modal fade" id="modalTambahPeran" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.perans.store') }}" method="POST">
            @csrf
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Tambah Peran Baru</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Pilih Film</label>
                <select name="film_id" class="form-control" required>
                  <option value="">-- Pilih Film --</option>
                  @foreach ($films as $f)
                    <option value="{{ $f->id }}">{{ $f->title }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Pilih Cast</label>
                <select name="cast_id" class="form-control" required>
                  <option value="">-- Pilih Cast --</option>
                  @foreach ($casts as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Nama Peran / Karakter</label>
                <input type="text" name="actor" class="form-control" placeholder="Contoh: Pemeran Utama / Tony Stark" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn" style="background: #ff8d1b; color: #fff;">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

  {{-- ==================== TAB 5: LIST DATA USER ==================== --}}
  @if ($tab == 'users')
    <div class="panel panel-default" style="border-top: 3px solid #ff8d1b;">
      <div class="panel-heading">
        <h4 class="panel-title"><strong>List Data User Terdaftar</strong></h4>
      </div>
      <div class="panel-body table-responsive">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th width="60">No</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Role</th>
              <th width="80">Umur</th>
              <th>Alamat</th>
              <th>Bio</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($users as $index => $u)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $u->name }}</strong></td>
                <td>{{ $u->email }}</td>
                <td>
                  @if ($u->role_id == 2)
                    <span class="label label-danger">Admin</span>
                  @else
                    <span class="label label-info">User</span>
                  @endif
                </td>
                <td>{{ $u->profile->umur ?? '-' }}</td>
                <td>{{ $u->profile->alamat ?? '-' }}</td>
                <td>{{ $u->profile->bio ?? '-' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif
</div>
@endsection