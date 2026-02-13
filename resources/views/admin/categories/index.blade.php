<!DOCTYPE html>
<html>
<head>
    <title>Data Kategori</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h3>Data Kategori</h3>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
        + Tambah Kategori
    </button>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th width="150">Aksi</th>
        </tr>
        </thead>
        <tbody id="categoryTable">
        @foreach($categories as $key => $category)
            <tr id="row-{{ $category->id }}">
                <td>{{ $key+1 }}</td>
                <td>{{ $category->name }}</td>
                <td>
                    <button class="btn btn-warning btn-sm editBtn" data-id="{{ $category->id }}">Edit</button>
                    <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $category->id }}">Delete</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{-- CREATE MODAL --}}
<div class="modal fade" id="createModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createForm">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control" placeholder="Nama Kategori" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id">
                    <input type="text" id="edit_name" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // CREATE
    $('#createForm').submit(function(e){
        e.preventDefault();

        $.post("{{ route('categories.store') }}", $(this).serialize(), function(){
            location.reload();
        });
    });

    // EDIT SHOW
    $('.editBtn').click(function(){
        let id = $(this).data('id');

        $.get("/admin/categories/edit/"+id, function(data){
            $('#edit_id').val(data.id);
            $('#edit_name').val(data.name);
            $('#editModal').modal('show');
        });
    });

    // UPDATE
    $('#editForm').submit(function(e){
        e.preventDefault();
        let id = $('#edit_id').val();

        $.post("/admin/categories/update/"+id, {
            name: $('#edit_name').val()
        }, function(){
            location.reload();
        });
    });

    // DELETE
    $('.deleteBtn').click(function(){
        if(confirm('Yakin hapus?')){
            let id = $(this).data('id');

            $.ajax({
                url: "/admin/categories/delete/"+id,
                type: "DELETE",
                success: function(){
                    $("#row-"+id).remove();
                }
            });
        }
    });

</script>

</body>
</html>
