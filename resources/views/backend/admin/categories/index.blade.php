@extends('layouts.app')

@section('content')

    <div class="container mt-4">
        <h3>Data Kategori</h3>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
            + Tambah Kategori
        </button>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th width="60">No</th>
                <th>Nama</th>
                <th width="160">Aksi</th>
            </tr>
            </thead>

            <tbody id="categoryTable">
            @foreach($categories as $key => $category)
                <tr id="row-{{ $category->id }}">
                    <td>{{ $key+1 }}</td>
                    <td class="nama">{{ $category->name }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm editBtn"
                                data-id="{{ $category->id }}"
                                data-name="{{ $category->name }}">
                            Edit
                        </button>

                        <button class="btn btn-danger btn-sm deleteBtn"
                                data-id="{{ $category->id }}">
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    {{-- ================= MODAL CREATE ================= --}}
    <div class="modal fade" id="createModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="createForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="text" name="name" class="form-control" placeholder="Nama kategori" required>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ================= MODAL EDIT ================= --}}
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

@endsection



@section('scripts')
    <script>

        // ================= CREATE =================
        $(document).on('submit','#createForm',function(e){
            e.preventDefault();

            $.post("{{ route('categories.store') }}", $(this).serialize(), function(){
                location.reload(); // reload supaya nomor urut rapi
            });
        });


        // ================= SHOW EDIT =================
        $(document).on('click','.editBtn',function(){

            let id = $(this).data('id');

            $.get("/admin/kategori/edit/"+id,function(res){
                $('#edit_id').val(res.id);
                $('#edit_name').val(res.name);
                $('#editModal').modal('show');
            });

        });


        // ================= UPDATE =================
        $(document).on('submit','#editForm',function(e){
            e.preventDefault();

            let id = $('#edit_id').val();

            $.post("/admin/kategori/update/"+id,{
                name: $('#edit_name').val()
            },function(){

                $('#editModal').modal('hide');
                location.reload();
            });

        });


        // ================= DELETE =================
        $(document).on('click','.deleteBtn',function(){

            let id = $(this).data('id');

            if(confirm('Yakin hapus kategori?')){

                $.ajax({
                    url: "/admin/kategori/delete/"+id,
                    type: "DELETE",
                    success:function(){
                        $("#row-"+id).remove();
                    },
                    error:function(xhr){
                        console.log(xhr.responseText);
                        alert('Delete gagal!');
                    }
                });

            }

        });

    </script>
@endsection
