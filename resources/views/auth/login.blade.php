
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Blood Inventory</title>
  <!-- plugins:css -->

  <link rel="stylesheet" href="{{ mix('css/app.css') }}">

  <style>
    .auth.auth-bg-1 {
      background: radial-gradient(ellipse at center, #754306 1%, #1c2b5a 150%) !important;
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper auth-page">
      <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
            <div class="auto-form-wrapper">
              <div class="row text-center">
                <div class="col-md-offset-4">
                  <img src="images/logo.png" class="img-responsive w-50" alt="">
                </div>

                <h3>Blood Distribution Management System</h3>
              </div>
              <hr>
              <form method="POST" action="{{ route('login') }}" >
                @csrf 
                <div class="form-group">
                  <label class="label">Username</label>
                  <div class="input-group">
                    <input type="email" class="form-control" placeholder="Username / Email" name="email" autofocus required autocomplete>
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="mdi mdi-check-circle-outline"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="label">Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" placeholder="*********" name="password" required autocomplete>
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="mdi mdi-check-circle-outline"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <button class="btn btn-primary submit-btn btn-block" type="submit">Login</button>
                </div>
              </form>
            </div>
            {{-- <p class="footer-text text-center auth-footer">Copyright © 2018 ITCDD. All rights reserved.</p> --}}
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <script src="{{ mix('js/app.js') }}"></script>
  <script src="{{ asset('js/off-canvas.js') }}"></script>
  <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('js/misc.js') }}"></script>
</body>

</html>