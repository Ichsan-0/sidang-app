@extends('layout.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Role & Permission</h4>
        <button class="btn btn-primary" id="btnAddRole">
            <i class="bx bx-plus"></i> Tambah Role
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tableRole" class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Role</th>
                            <th>Jumlah Permission</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Role (Create/Update) -->
<div class="modal fade" id="roleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleModalLabel">Tambah Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formRole">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="role_id">
                    
                    <div class="mb-3">
                        <label for="role_name" class="form-label">Nama Role</label>
                        <input type="text" name="name" id="role_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Give Permission -->
<div class="modal fade" id="givePermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="givePermissionModalLabel">Kelola Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formGivePermission">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="role_id_perm" id="role_id_perm">
                    
                    <div class="alert alert-info">
                        <i class="bx bx-info-circle"></i> 
                        Role: <strong id="role_name_display"></strong>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Permissions</label>
                        
                        <!-- Search Box -->
                        <div class="mb-3">
                            <input type="text" id="searchPermission" class="form-control" placeholder="Cari permission...">
                        </div>

                        <!-- Select All -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                            <label class="form-check-label fw-bold" for="selectAll">
                                Pilih Semua
                            </label>
                        </div>

                        <div class="row" id="permissionList">
                            @foreach($permissions as $group => $perms)
                            <div class="col-md-6 mb-3 permission-group">
                                <h6 class="text-uppercase text-primary border-bottom pb-2">
                                    <i class="bx bx-folder"></i> {{ ucfirst($group) }}
                                </h6>
                                @foreach($perms as $permission)
                                <div class="form-check permission-item">
                                    <input class="form-check-input permission-checkbox" 
                                           type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $permission->name }}" 
                                           id="perm_give_{{ $permission->id }}">
                                    <label class="form-check-label" for="perm_give_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check"></i> Simpan Permission
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
    .permission-item { padding-left: 20px; margin-bottom: 8px; }
    .permission-group { border: 1px solid #e0e0e0; border-radius: 8px; padding: 15px; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }});

$(function () {
    const table = $('#tableRole').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/role-permission/ajax',
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name' },
            { data: 'permissions_count' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    // Tambah Role
    $('#btnAddRole').click(function() {
        $('#roleModalLabel').text('Tambah Role');
        $('#role_id').val('');
        $('#formRole')[0].reset();
        $('#roleModal').modal('show');
    });

    // Submit Form Create/Update Role (hanya nama role, tanpa permission)
    $('#formRole').on('submit', function(e) {
        e.preventDefault();
        const id = $('#role_id').val();
        const url = '/role-permission' + (id ? '/' + id : '');
        const method = id ? 'PUT' : 'POST';

        $.ajax({
            url, method,
            data: $(this).serialize(),
            success: function(res) {
                if(res.success) {
                    $('#roleModal').modal('hide');
                    table.ajax.reload();
                    alert(id ? 'Role diperbarui' : 'Role ditambahkan');
                }
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    alert(Object.values(xhr.responseJSON.errors).flat().join('\n'));
                } else {
                    alert('Gagal menyimpan data');
                }
            }
        });
    });

    // Edit Role (hanya nama)
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).data('id');
        $('#roleModalLabel').text('Edit Role');
        
        $.get('/role-permission/' + id, function(res) {
            $('#role_id').val(res.id);
            $('#role_name').val(res.name);
            $('#roleModal').modal('show');
        });
    });

    // Give Permission - Buka Modal
    $(document).on('click', '.btnGivePermission', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        $('#role_id_perm').val(id);
        $('#role_name_display').text(name);
        $('.permission-checkbox').prop('checked', false);
        
        // Load permission yang sudah dimiliki role ini
        $.get('/role-permission/' + id, function(res) {
            res.permissions.forEach(function(perm) {
                $('input[value="'+perm.name+'"]').prop('checked', true);
            });
        });
        
        $('#givePermissionModal').modal('show');
    });

    // Submit Give Permission
    $('#formGivePermission').on('submit', function(e) {
        e.preventDefault();
        const id = $('#role_id_perm').val();
        
        $.ajax({
            url: '/role-permission/' + id + '/give-permission',
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if(res.success) {
                    $('#givePermissionModal').modal('hide');
                    table.ajax.reload();
                    alert(res.message);
                }
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    alert(Object.values(xhr.responseJSON.errors).flat().join('\n'));
                } else {
                    alert('Gagal menyimpan permission');
                }
            }
        });
    });

    // Delete Role
    $(document).on('click', '.btnDelete', function() {
        if (!confirm('Hapus role ini?')) return;
        const id = $(this).data('id');
        
        $.ajax({
            url: '/role-permission/' + id,
            method: 'DELETE',
            success: function(res) {
                if(res.success) {
                    table.ajax.reload();
                    alert('Role dihapus');
                }
            },
            error: function() { alert('Gagal menghapus'); }
        });
    });

    // Select All Checkbox
    $('#selectAll').on('change', function() {
        $('.permission-checkbox').prop('checked', $(this).is(':checked'));
    });

    // Search Permission
    $('#searchPermission').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('.permission-item').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
</script>
@endpush
