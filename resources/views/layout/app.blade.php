<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Dashboard - Analytics | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/assets//vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->
    @stack('styles')

    <!-- Helpers -->
    <script src="{{ asset('assets/assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/assets/js/config.js') }}"></script>
    

  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        
        <!-- Menu -->
        @include('layout.sidebar')
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">

            <!-- Navbar -->
          @include('layout.header')
            <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            @yield('content')
            <!-- / Content -->
            
            <!-- Footer -->
            @include('layout.footer')
            <!-- / Footer -->
            

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{ asset('assets/assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{ asset('assets/assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{ asset('assets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

    <script src="{{ asset('assets/assets/vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/assets/js/main.js')}}"></script>
    <script src="{{ asset('assets/assets/js/ui-popover.js')}}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/assets/js/dashboards-analytics.js')}}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      window.jenisPenelitianData = {};
      window.getJenisPenelitian = function(selectId, callback) {
        fetch('/get-jenis-penelitian')
          .then(res => res.json())
          .then(data => {
            const select = document.getElementById(selectId);
            if (!select) return;
            select.innerHTML = '<option value="">--Pilih Jenis Tugas Akhir--</option>';
            data.forEach(item => {
              select.innerHTML += `<option value="${item.id}" data-nama="${item.nama}" data-ket="${item.ket ?? ''}">${item.nama}</option>`;
              window.jenisPenelitianData[item.id] = item;
            });
            if (typeof callback === 'function') callback(select);
          });
      };

      window.initSelectPopover = function(selectId) {
        const select = document.getElementById(selectId);
        if (!select) return;
        let popoverInstance = null;
        select.addEventListener('change', function() {
          if (popoverInstance) {
            popoverInstance.dispose();
            popoverInstance = null;
          }
          const selected = this.options[this.selectedIndex];
          const nama = selected.getAttribute('data-nama');
          const ket = selected.getAttribute('data-ket');
          if (this.value && nama) {
            popoverInstance = new bootstrap.Popover(this, {
              title: nama,
              content: ket || 'Tidak ada deskripsi.',
              placement: 'right',
              trigger: 'manual',
              html: true
            });
            popoverInstance.show();
          }
        });
        select.addEventListener('blur', function() {
          if (popoverInstance) {
            popoverInstance.hide();
          }
        });
      };

      window.getBidangPeminatan = function(selectId, groupId, callback) {
        fetch('/get-bidang-peminatan')
          .then(res => res.json())
          .then(data => {
            const bidangGroup = document.getElementById(groupId);
            const select = document.getElementById(selectId);
            if (!select || !bidangGroup) return;
            if (data.length === 0) {
              bidangGroup.style.display = 'none';
            } else {
              bidangGroup.style.display = '';
              select.innerHTML = '<option value="">--Pilih Bidang Peminatan--</option>';
              data.forEach(item => {
                select.innerHTML += `<option value="${item.id}" data-nama="${item.nama}" data-ket="${item.ket ?? ''}">${item.nama}</option>`;
              });
              if (typeof callback === 'function') callback(select);
            }
          });
      };

      window.initBidangPopover = function(selectId) {
        const select = document.getElementById(selectId);
        if (!select) return;
        let bidangPopoverInstance = null;
        select.addEventListener('change', function() {
          if (bidangPopoverInstance) {
            bidangPopoverInstance.dispose();
            bidangPopoverInstance = null;
          }
          const selected = this.options[this.selectedIndex];
          const nama = selected.getAttribute('data-nama');
          const ket = selected.getAttribute('data-ket');
          if (this.value && nama) {
            bidangPopoverInstance = new bootstrap.Popover(this, {
              title: nama,
              content: ket || 'Tidak ada deskripsi.',
              placement: 'right',
              trigger: 'manual',
              html: true
            });
            bidangPopoverInstance.show();
          }
        });
        select.addEventListener('blur', function() {
          if (bidangPopoverInstance) {
            bidangPopoverInstance.hide();
          }
        });
      };

      $(document).on('click', '.role-switcher-item', function(e) {
        e.preventDefault();
        var role = $(this).data('role');
        $.post("{{ route('set-active-role') }}", {
          role: role,
          _token: "{{ csrf_token() }}"
        }, function() {
          location.reload();
        });
      });
      </script>
    @stack('scripts')
    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    
  </body>
</html>
