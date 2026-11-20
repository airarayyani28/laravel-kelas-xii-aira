<div class="modal video-modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Sign In & Sign Up
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>            
      </div>
      <section>
        <div class="modal-body">
          <div class="w3_login_module">
            <div class="module form-module">
              <div class="toggle"><i class="fa fa-times fa-pencil"></i>
                <div class="tooltip">Daftar / Login</div>
              </div>
              
              {{-- Form Login --}}
              <div class="form">
                <h3>Login to your account</h3>
                @if ($errors->any())
                  <div class="alert alert-danger" style="font-size: 13px; padding: 8px;">
                    @foreach ($errors->all() as $error)
                      <div>{{ $error }}</div>
                    @endforeach
                  </div>
                @endif
                <form action="{{ route('login') }}" method="POST">
                  @csrf
                  <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
                  <input type="password" name="password" placeholder="Password" required>
                  <input type="submit" value="Login">
                </form>
              </div>

              {{-- Form Register --}}
              <div class="form">
                <h3>Create an account</h3>
                <form action="{{ route('register') }}" method="POST">
                  @csrf
                  <input type="text" name="name" placeholder="Nama Lengkap" required>
                  <input type="email" name="email" placeholder="Email Address" required>
                  <input type="password" name="password" placeholder="Password (min. 8 karakter)" required>
                  <input type="number" name="umur" placeholder="Umur" required style="width: 100%; margin-bottom: 15px; padding: 10px; border: 1px solid #d9d9d9;">
                  <input type="text" name="alamat" placeholder="Alamat" required>
                  <input type="text" name="bio" placeholder="Bio Singkat (Opsional)">
                  <input type="submit" value="Register">
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>
<script>
  $('.toggle').click(function(){
    $(this).children('i').toggleClass('fa-pencil');
    $('.form').animate({
      height: "toggle",
      'padding-top': 'toggle',
      'padding-bottom': 'toggle',
      opacity: "toggle"
    }, "slow");
  });
</script>