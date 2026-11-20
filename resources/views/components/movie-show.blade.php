@extends('templates.index')

@push('style')
  <link rel="stylesheet" type="text/css" href="{{ asset('template/list-css/table-style.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('template/list-css/basictable.css') }}" />
@endpush

@push('scripts')
<script type="text/javascript" src="{{ asset('template/list-js/jquery.basictable.min.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#table').basictable();
    $('#table-breakpoint').basictable({
      breakpoint: 768
    });
  });
</script>
@endpush

@section('content')

<div class="container">
  <br />
    <div class="agileits-single-top">
      <ol class="breadcrumb">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('movies') }}">Movies</a></li>
        <li class="active">{{ $film->title }}</li>
      </ol>
    </div>
    <div class="single-page-agile-info">
      <div class="show-top-grids-w3lagile">
        <div class="col-sm-8 single-left">
          <div class="song">
            <div class="single-right-grids">

              <div class="col-md-4 single-right-grid-left">
                <a href="{{ route('movies.show', $film->id) }}"><img src="/{{ $film->poster }}" alt="" /></a>
              </div>
              <div class="col-md-8 single-right-grid-right">
                <div class="song-info">
                  <h2>{{ $film->title }}</h2> 
                  <p class="author"><a href="#" class="author">{{ $film->year }}</a></p>
                  <p class="views">{{ $film->sinopsis }}</p>
                </div>
                <div id="myTabContent" class="tab-content">
                  <div role="tabpanel" class="tab-pane fade in active" id="home" aria-labelledby="home-tab">
                    <div class="agile-news-table">
                      <table id="table-breakpoint">
                        <thead>
                          <tr>
                            <th>Cast</th>
                            <th>Peran</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($perans as $peran)
                          <tr>
                            <td class="w3-list-info">{{ $peran->cast_id }}</td>
                            <td class="w3-list-info">{{ $peran->actor }}</td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="clearfix"> </div>
            </div>
          </div>

          <div class="song-grid-right">
            <div class="share">
              <h5>Share this</h5>
              <div class="single-agile-shar-buttons">
                <ul>
                  <li>
                    <div class="fb-like" data-href="https://www.facebook.com/w3layouts" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="clearfix"> </div>

          {{-- BAGIAN KRITIK & KOMENTAR FILM --}}
          <div class="all-comments">
            <div class="all-comments-info">
              <a href="#">Kritik &amp; Komentar ({{ $comments->count() }})</a>

              @if (session('success'))
                <div class="alert alert-success" style="margin-top: 10px;">
                  {{ session('success') }}
                </div>
              @endif

              @if ($errors->any())
                <div class="alert alert-danger" style="margin-top: 10px;">
                  @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                  @endforeach
                </div>
              @endif

              @auth
                @if (Auth::user()->role_id == 1)
                  <div class="agile-info-wthree-box">
                    <form action="{{ route('kritik.store', $film->id) }}" method="POST">
                      @csrf
                      <div style="margin-bottom: 12px;">
                        <label for="rating" style="display: block; margin-bottom: 5px; font-weight: bold;">Rating Film (1 - 5):</label>
                        <select name="rating" id="rating" required style="width: 100%; padding: 8px; border: 1px solid #ccc;">
                          <option value="">-- Pilih Rating --</option>
                          <option value="5">5 - Sangat Bagus</option>
                          <option value="4">4 - Bagus</option>
                          <option value="3">3 - Cukup</option>
                          <option value="2">2 - Kurang</option>
                          <option value="1">1 - Buruk</option>
                        </select>
                      </div>
                      <textarea name="comment" placeholder="Tulis kritik atau ulasan kamu tentang film ini..." required></textarea>
                      <input type="submit" value="KIRIM KRITIK">
                      <div class="clearfix"> </div>
                    </form>
                  </div>
                @else
                  <div class="alert alert-warning" style="margin-top: 15px;">
                    Anda login sebagai <strong>Administrator</strong>. Admin tidak diperbolehkan memberikan kritik pada film.
                  </div>
                @endif
              @endauth

              @guest
                <div class="alert alert-info" style="margin-top: 15px;">
                  Silakan <a href="#" data-toggle="modal" data-target="#myModal" style="font-weight: bold; text-decoration: underline;">Login</a> sebagai User untuk memberikan kritik pada film ini.
                </div>
              @endguest
            </div>

            <div class="media-grids">
              @forelse ($comments as $comment)
                <div class="media">
                  <h5>
                    {{ $comment->user->name ?? 'User #' . $comment->user_id }}
                    <span style="color: #ff8d1b; margin-left: 8px;">
                      ★ {{ $comment->rating ?? '-' }}/5
                    </span>
                  </h5>
                  <div class="media-left">
                    <a href="#">
                      <img src="{{ asset('storage/images/user.jpg') }}" title="User" alt=" " />
                    </a>
                  </div>
                  <div class="media-body">
                    <p>{{ $comment->comment }}</p>
                  </div>
                </div>
              @empty
                <p style="margin-top: 15px; color: #777;">Belum ada kritik untuk film ini. Jadilah yang pertama berkomentar!</p>
              @endforelse
            </div>
          </div>
          {{-- END BAGIAN KRITIK --}}

        </div>

        <div class="col-md-4 single-right">
          <h3>Similar Film By Genre</h3>
          @foreach ($filmByGenre as $similarFilm)
            <div class="single-grid-right">
              <div class="single-right-grids">
                <div class="col-md-4 single-right-grid-left">
                  <a href="{{ route('movies.show', $similarFilm->id) }}"><img src="/{{ $similarFilm->poster }}" alt="" /></a>
                </div>
                <div class="col-md-8 single-right-grid-right">
                  <a href="{{ route('movies.show', $similarFilm->id) }}" class="title">{{ $similarFilm->title }}</a>
                  <p class="author"><a href="#" class="author">{{ $similarFilm->year }}</a></p>
                  <p class="views" style="text-align: justify; margin-right: 10px">{{ $similarFilm->sinopsis }}</p>
                </div>
                <div class="clearfix"> </div>
              </div>
            </div>
          @endforeach
        </div>
        <div class="clearfix"> </div>
      </div>

      <div class="w3_agile_banner_bottom_grid">
        <div id="owl-demo" class="owl-carousel owl-theme">
          @foreach ($filmByRelease as $filmRelease)
            <div class="item">
              <div class="w3l-movie-gride-agile w3l-movie-gride-agile1">
                <a href="{{ route('movies.show', $filmRelease->id) }}" class="hvr-shutter-out-horizontal"><img src="/{{ $filmRelease->poster }}" title="{{ $filmRelease->title }}" class="img-responsive" alt=" " />
                  <div class="w3l-action-icon"><i class="fa fa-play-circle" aria-hidden="true"></i></div>
                </a>
                <div class="mid-1 agileits_w3layouts_mid_1_home">
                  <div class="w3l-movie-text">
                    <h6><a href="{{ route('movies.show', $filmRelease->id) }}">{{ $filmRelease->title }}</a></h6>             
                  </div>
                  <div class="mid-2 agile_mid_2_home">
                    <p>{{ $filmRelease->year }}</p>
                    <div class="block-stars">
                      <ul class="w3l-ratings">
                        <li><a href="#"><i class="fa fa-star" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-star" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-star" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-star" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-star-half-o" aria-hidden="true"></i></a></li>
                      </ul>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                </div>
                @if ($filmRelease->year == now()->year)
                  <div class="ribben">
                    <p>NEW</p>
                  </div>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="js/simplePlayer.js"></script>
<script>
  $("document").ready(function() {
    $("#video").simplePlayer();
  });
</script>
@endpush