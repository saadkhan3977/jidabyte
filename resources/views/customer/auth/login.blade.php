<!DOCTYPE html>
<html lang="en">

<head>
  <title>{{env('APP_NAME')}} || Login Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('/toastr/toastr.min.css')}}">
<link href="{{asset('backend/css/sb-admin-2.min.css')}}" rel="stylesheet">

</head>

<body class="bg-gradient-primary">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9 mt-5">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                  </div>
                  <div class="logmod__container">
                    <ul class="nav nav-tabs" id="myTab">
                        <li data-tabtar="nav-item"><a href="#login" class="nav-link active" data-bs-toggle="tab">Login</a></li>
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane active" id="login"><br>
                        <div class="logmod__heading">
                          <span class="logmod__heading-subtitle">Log In To Your Account</span>
                        </div><br>
                        <div class="logmod__form">
                          <form method="POST" action="{{ route('customer-login') }}">
                            @csrf
                            <div class="sminputs">
                              <div class="input full">
                                <input class="string optional  form-control" maxlength="255" id="user-email"  value="{{old('email') }}" placeholder="Username/Email" name="email" type="email" size="50" />
                              </div>
                            </div><br>
                            <div class="sminputs">
                              <div class="input full">
                                <input class="string optional form-control" maxlength="255" id="user-pw" placeholder="Password" name="password" type="password" size="50" />
                              </div>
                            </div>
                            <div class="rememberme-div">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">Remember Me</label>
                              </div>
                            </div>
                            <div class="simform__actions">
                              <button class="btn btn-primary">Log In</button>
                            </div>
                          </form>
                        </div>
                        <div class="text-center">
                          @if (Route::has('password.request'))
                            <a class="btn btn-link small" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                          @endif
                        </div>
                      </div>
                     
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

<!-- Toastr -->
<script src="{{asset('/toastr/toastr.min.js')}}"></script>
<script>
@if(session('success'))
  toastr.success("{{session('success')}}");
@endif
@if(session('error'))
  toastr.error("{{session('error')}}")
@endif
@if($errors->any())
    @foreach ($errors->all() as $error)
    toastr.error("{{$error}}")
    @endforeach
@endif
</script>
</html>
