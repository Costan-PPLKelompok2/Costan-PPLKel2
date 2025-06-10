<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Corona Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('admin_assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin_assets/vendors/css/vendor.bundle.base.css')}}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{asset('admin_assets/vendors/jvectormap/jquery-jvectormap.css')}}">
    <link rel="stylesheet" href="{{asset('admin_assets/vendors/flag-icon-css/css/flag-icon.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin_assets/vendors/owl-carousel-2/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin_assets/vendors/owl-carousel-2/owl.theme.default.min.css')}}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{asset('admin_assets/css/style.css')}}">
    <!-- End layout styles -->
  </head>
  <body>
    <div class="container-scroller">
      <!-- Alerts -->
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="border-radius: 10px; margin: 20px;">
          <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 10px; margin: 20px;">
          <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 10px; margin: 20px;">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
          <a class="sidebar-brand brand-logo" href="#"><img src="{{asset('admin_assets/images/logo.svg')}}" alt="logo" /></a>
          <a class="sidebar-brand brand-logo-mini" href="#"><img src="{{asset('admin_assets/images/logo-mini.svg')}}" alt="logo" /></a>
        </div>
        <ul class="nav">
          <li class="nav-item profile">
            <div class="profile-desc">
              <div class="profile-pic">
                <div class="count-indicator">
                  <img class="img-xs rounded-circle " src="{{asset('admin_assets/images/faces/face15.jpg')}}" alt="">
                  <span class="count bg-success"></span>
                </div>
                <div class="profile-name">
                  <h5 class="mb-0 font-weight-normal">{{ $user->name }}</h5>
                  <span>Gold Member</span>
                </div>
              </div>
              <a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
              <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list" aria-labelledby="profile-dropdown">
                <a href="#" class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                      <i class="mdi mdi-settings text-primary"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1 text-small">Account settings</p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                      <i class="mdi mdi-onepassword  text-info"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1 text-small">Change Password</p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                      <i class="mdi mdi-calendar-today text-success"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1 text-small">To-do list</p>
                  </div>
                </a>
              </div>
            </div>
          </li>
          <li class="nav-item nav-category">
            <span class="nav-link">Navigation</span>
          </li>
          @include('admin.sidebar')
        </ul>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar p-0 fixed-top d-flex flex-row">
          <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
            <a class="navbar-brand brand-logo-mini" href="index.html"><img src="{{asset('admin_assets/images/logo-mini.svg')}}" alt="logo" /></a>
          </div>
          <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
              <span class="mdi mdi-menu"></span>
            </button>
            <ul class="navbar-nav navbar-nav-right">
              <li class="nav-item dropdown">
                <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
                  <div class="navbar-profile">
                    <img class="img-xs rounded-circle" src="{{asset('admin_assets/images/faces/face15.jpg')}}" alt="">
                    <p class="mb-0 d-none d-sm-block navbar-profile-name">{{$user->name}}</p>
                    <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
                  <h6 class="p-3 mb-0">Profile</h6>
                  <div class="dropdown-divider"></div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                      @csrf
                      <button type="submit" class="dropdown-item preview-item" style="background: none; border: none; padding: 0; width: 100%; text-align: left;">
                          <div class="preview-thumbnail">
                              <div class="preview-icon bg-dark rounded-circle">
                                  <i class="mdi mdi-logout text-danger"></i>
                              </div>
                          </div>
                          <div class="preview-item-content">
                              <p class="preview-subject mb-1">Log out</p>
                          </div>
                      </button>
                  </form>
                </div>
              </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
              <span class="mdi mdi-format-line-spacing"></span>
            </button>
          </div>
        </nav>
        <!-- partial -->
         @if($user->role == 'admin')
          <div class="main-panel">
            <div class="content-wrapper">
              <div class="row">
                {{-- Total Semua Kos --}}
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-9">
                          <div class="d-flex align-items-center align-self-start">
                            <h3 class="mb-0">{{ $totalAllKos }}</h3>
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="icon icon-box-success">
                            <span class="mdi mdi-home-city-outline icon-item"></span>
                          </div>
                        </div>
                      </div>
                      <h6 class="text-muted font-weight-normal">Total Semua Kos</h6>
                    </div>
                  </div>
                </div>

                {{-- Total Semua Views --}}
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-9">
                          <div class="d-flex align-items-center align-self-start">
                            <h3 class="mb-0">{{ $totalAllViews }}</h3>
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="icon icon-box-success">
                            <span class="mdi mdi-eye-outline icon-item"></span>
                          </div>
                        </div>
                      </div>
                      <h6 class="text-muted font-weight-normal">Total Semua Views</h6>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Chart per Pemilik --}}
              <div class="row mt-4">
                <div class="col-12">
                  <h4>Statistik Per Pemilik</h4>
                </div>

                @foreach ($pemilikStats as $index => $pemilik)
                  <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <h5 class="card-title">{{ $pemilik['nama'] }}</h5>
                        <canvas id="chartKos{{ $index }}"></canvas>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
         @elseif($user->role == 'pemilik')
          <div class="main-panel">
            <div class="content-wrapper">
              <div class="row">
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-9">
                          <div class="d-flex align-items-center align-self-start">
                            <h3 class="mb-0">{{$totalKos}}</h3>
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="icon icon-box-success ">
                            <span class="mdi mdi-home-city-outline icon-item"></span>
                          </div>
                        </div>
                      </div>
                      <h6 class="text-muted font-weight-normal">Jumlah Kos Anda</h6>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-9">
                          <div class="d-flex align-items-center align-self-start">
                            <h3 class="mb-0">{{$totalViews}}</h3>
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="icon icon-box-success">
                            <span class="mdi mdi-eye-outline icon-item"></span>
                          </div>
                        </div>
                      </div>
                      <h6 class="text-muted font-weight-normal">Jumlah Views</h6>
                    </div>
                  </div>
                </div>
              <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                      <div class="d-flex flex-row justify-content-between"></div>
                        <canvas id="kosViewChart"></canvas>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
            <footer class="footer">
              <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © bootstrapdash.com 2020</span>
                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank">Bootstrap admin templates</a> from Bootstrapdash.com</span>
              </div>
            </footer>
            <!-- partial -->
          </div>
          @endif
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{asset('admin_assets/vendors/js/vendor.bundle.base.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{asset('admin_assets/vendors/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('admin_assets/vendors/progressbar.js/progressbar.min.js')}}"></script>
    <script src="{{asset('admin_assets/vendors/jvectormap/jquery-jvectormap.min.js')}}"></script>
    <script src="{{asset('admin_assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
    <script src="{{asset('admin_assets/vendors/owl-carousel-2/owl.carousel.min.js')}}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{asset('admin_assets/js/off-canvas.js')}}"></script>
    <script src="{{asset('admin_assets/js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('admin_assets/js/misc.js')}}"></script>
    <script src="{{asset('admin_assets/js/settings.js')}}"></script>
    <script src="{{asset('admin_assets/js/todolist.js')}}"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{asset('admin_assets/js/dashboard.js')}}"></script>
    <!-- End custom js for this page -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const kosViewChartElem = document.getElementById('kosViewChart');
        if (kosViewChartElem) {
            const ctx = kosViewChartElem.getContext('2d');
            const kosChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($viewsStatistik->pluck('nama_kos')),
                    datasets: [{
                        label: 'Jumlah Views',
                        data: @json($viewsStatistik->pluck('views')),
                        backgroundColor: "#b0b435",
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }

        @foreach ($pemilikStats as $index => $pemilik)
          const kosNames{{ $index }} = @json(collect($pemilik['kosViews'])->pluck('nama_kos'));
          const kosViews{{ $index }} = @json(collect($pemilik['kosViews'])->pluck('views'));
        
          // Generate one random color for this owner
          const r{{ $index }} = Math.floor(Math.random() * 200) + 30;
          const g{{ $index }} = Math.floor(Math.random() * 200) + 30;
          const b{{ $index }} = Math.floor(Math.random() * 200) + 30;
          const ownerColor{{ $index }} = `rgba(${r{{ $index }}}, ${g{{ $index }}}, ${b{{ $index }}}, 0.7)`;
          // Make all bars the same color for this owner
          const barColors{{ $index }} = kosNames{{ $index }}.map(() => ownerColor{{ $index }});
        
          const ctxKos{{ $index }} = document.getElementById('chartKos{{ $index }}').getContext('2d');
          new Chart(ctxKos{{ $index }}, {
            type: 'bar',
            data: {
              labels: kosNames{{ $index }},
              datasets: [{
                label: 'Views Kos',
                data: kosViews{{ $index }},
                backgroundColor: barColors{{ $index }},
              }]
            },
            options: {
              responsive: true,
              plugins: {
                legend: { display: true },
                title: {
                  display: true,
                  text: 'Total Kos: {{ $pemilik["jumlahKos"] }}, Total Views: {{ $pemilik["jumlahViews"] }}'
                }
              },
              scales: {
                y: { beginAtZero: true }
              }
            }
          });
        @endforeach
    </script>
  </body>
</html>