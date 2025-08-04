{{-- filepath: resources/views/user.blade.php --}}
@extends('layout.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
      <h5 class="mb-0">Data User</h5>
      <button class="btn btn-primary" id="addBtn">
        <i class="bx bx-plus"></i> Tambah User
      </button>
    </div>
    <div class="card-body">
      <div class="table-responsive text-nowrap">
        <table id="userTable" class="table table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Role</th>
              <th>Aksi</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="userForm">
      @csrf
      <input type="hidden" name="id" id="user_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label  for="defaultSelect" class="form-label">Jenis User</label>
            <select class="form-select" name="role" id="role" required>
              <option value="">-- Pilih Jenis User --</option>
              @foreach(\Spatie\Permission\Models\Role::all() as $role)
                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" name="name" id="name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" required>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Pilih Prodi</label>
                <select class="form-select" name="prodi" id="prodi" required>
                  
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">NIP</label>
                <input type="text" class="form-control" name="nip" id="nip" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">No. HP</label>
                <input type="text" class="form-control" name="no_hp" id="no_hp" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Alamat</label>
                <input type="text" class="form-control" name="alamat" id="alamat" required>
              </div>
            </div>
          </div>
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="../../assets/vendor/libs/select2/select2.css " />
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="../../assets/vendor/libs/select2/select2.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>


<script>
  $(function () {
    function loadProdiSelect(selectedId = null) {
        $.getJSON('/get-prodi', function(res) {
            const $prodi = $('#prodi');
            $prodi.empty().append('<option value="">-- Pilih Prodi --</option>');
            // Grouping by fakultas (optgroup)
            let groups = {};
            res.options.forEach(function(item) {
                if (!groups[item.optgroup]) groups[item.optgroup] = [];
                groups[item.optgroup].push(item);
            });
            for (const fakultas in groups) {
                const $group = $('<optgroup>', { label: fakultas });
                groups[fakultas].forEach(function(prodi) {
                    $group.append($('<option>', {
                        value: prodi.value,
                        text: prodi.text,
                        selected: selectedId == prodi.value
                    }));
                });
                $prodi.append($group);
            }
            // Inisialisasi select2 ulang
            $prodi.select2({
                dropdownParent: $('#userModal'),
                width: '100%'
            });
        });
    }

    // Saat modal tambah user
    $('#addBtn').on('click', function () {
        $('#userForm')[0].reset();
        $('#user_id').val('');
        loadProdiSelect();
        $('.modal-title').text('Tambah User');
        $('#userModal').modal('show');
    });

    // Saat modal edit user
    $(document).on('click', '.editBtn', function () {
        var id = $(this).data('id');
        $.get('/user/' + id, function (data) {
            $('#user_id').val(data.id);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#role').val(data.roles[0] ?? '');
            $('#nip').val(data.nip);
            $('#no_hp').val(data.no_hp);
            $('#alamat').val(data.alamat);
            loadProdiSelect(data.prodi); // set selected prodi
            $('.modal-title').text('Edit User');
            $('#userModal').modal('show');
        });
    });

    // Inisialisasi select2 saat modal dibuka (jika perlu)
    $('#userModal').on('shown.bs.modal', function () {
        $('#prodi').select2({
            dropdownParent: $('#userModal'),
            width: '100%'
        });
    });

    var table = $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("user.ajax") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'roles', name: 'roles', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // submit form (add/update)
    $('#userForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#user_id').val();
        var url = id ? '/user/update/' + id : '/user/store';
        var method = 'POST';
        var $form = $(this);

       $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    $('#userModal').modal('hide');
                    table.ajax.reload();
                    alert(res.message);
                }
            },
            error: function (xhr) {
                alert('Terjadi kesalahan!');
                console.log(xhr.responseText);
            }
        });
    });


    // delete
    $(document).on('click', '.deleteBtn', function () {
        if (!confirm('Yakin ingin menghapus user ini?')) return;
        var id = $(this).data('id');
        $.ajax({
            url: '/user/' + id,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (res) {
                if (res.success) {
                    table.ajax.reload();
                    alert(res.message);
                }
            },
            error: function (xhr) {
                alert('Terjadi kesalahan!');
                console.log(xhr.responseText);
            }
        });
    });
});
</script>
@endpush