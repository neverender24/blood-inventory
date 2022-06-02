
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Blood Inventory</title>
  <!-- plugins:css -->

  <link rel="stylesheet" href="{{ mix('css/app.css') }}" media="screen">

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
              <div class="row mb-3">
                  <div class="col-md-12 text-center">
                      @if (count($errors) > 0)
                      <div class="alert alert-danger" style="margin-bottom: unset; padding: .3rem;">
                          @foreach($errors->all() as $message)
                          <small class="text-danger"><strong>
                                  <p style="margin-bottom: unset;">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor"
                                          class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16"
                                          role="img" aria-label="Warning:">
                                          <path
                                              d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                      </svg>
                                      {{ $message }}</p>
                              </strong></small>
                          @endforeach
                      </div>
                      @endif
                  </div>
              </div>
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
</body>

</html>