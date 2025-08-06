@extends('layout.app')

@section('content')
  
<!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <div class="col-lg-12 mb-4 order-0">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                      <div class="col-sm-8">
                        <div class="card-body">
                          <h5 class="card-title text-primary">Selamat datang di SIGMA</h5>
                          <p class="mb-4">
                            Sistem Informasi Tugas Akhir Mahasiswa<span class="fw-bold"> (SIGMA)</span> 
                            adalah aplikasi berbasis web yang dirancang untuk memudahkan pengelolaan tugas akhir mahasiswa.
                          <a href="{{route('tugas-akhir.index')}}" class="btn btn-sm btn-outline-primary">Lihat Tugas Akhir</a>
                        </div>
                      </div>
                      <div class="col-sm-4 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                          <img
                            src="{{ asset('assets/assets/img/illustrations/man-with-laptop-light.png')}}"
                            height="140"
                            alt="View Badge User"
                            data-app-dark-img="illustrations/man-with-laptop-dark.png"
                            data-app-light-img="illustrations/man-with-laptop-light.png"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
            <!-- / Content -->
@endsection