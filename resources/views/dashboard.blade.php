@extends('layout.app')

@section('content')
  
<!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <div class="col-lg-12 mb-4 order-0">
                  <div class="card mb-4">
                    <div class="d-flex align-items-end row">
                      <div class="col-sm-8">
                        <div class="card-body">
                          <h5 class="card-title text-primary">Selamat datang di SIGMA</h5>
                          <p class="mb-4">
                            Sistem Informasi Tugas Akhir Mahasiswa<span class="fw-bold"> (SIGMA)</span> 
                            adalah aplikasi berbasis web yang dirancang untuk memudahkan pengelolaan tugas akhir mahasiswa.
                          </p>
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

                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title text-primary">Langkah-Langkah Pengajuan Judul Proposal Tugas Akhir</h5>
                      <p><strong>1. Konsultasi :</strong> Konsultasi dengan Calon Dosen Pembimbing. Sebelum mengajukan judul secara resmi melalui sistem, sangat disarankan untuk berkonsultasi terlebih dahulu dengan dosen yang Anda harapkan menjadi pembimbing. Diskusikan rencana judul, latar belakang, permasalahan, metode penelitian dan ide-ide Anda untuk mendapatkan masukan, arahan, serta memastikan topik tersebut sesuai dengan bidang keahlian dosen.
                        <a href="{{ asset('storage/Form_Pengajuan_Judul.doc') }}" class="btn btn-sm btn-outline-primary" download>Template Pengajuan Judul</a>
                      </p>
                      
                      <p><strong>2. Pilih Satu Dosen:</strong> Disarankan untuk memilih satu dosen pembimbing saja untuk mempermudah proses validasi. Apabila Anda memilih lebih dari satu dosen pembimbing, pastikan Anda siap karena judul yang Anda ajukan harus divalidasi dan disetujui oleh semua dosen yang Anda pilih.</p>
                        <p><strong>3. Kirim Usulan :</strong> Setelah yakin dengan judul dan calon pembimbing, lakukan pengajuan melalui sistem. Perhatikan dengan saksama:</p>
                        <ul>
                        <li><strong>Proses Ini Bersifat Final:</strong> Setelah Anda menekan tombol "Kirim Usulan", Anda tidak dapat lagi mengubah (mengedit) atau membatalkan (menghapus) usulan tersebut.</li>
                        <li><strong>Periksa Kembali:</strong> Pastikan semua informasi, termasuk penulisan judul dan pemilihan dosen, sudah benar sebelum mengirim.</li>
                        </ul>
                      <p><strong>4. Hubungi Dosen :</strong> Setelah mengirim, segera hubungi dosen pembimbing Anda untuk meminta persetujuan.</p>
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
            <!-- / Content -->
@endsection