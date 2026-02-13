@extends('layouts.app')

@section('content')

    <h3>Data Product</h3>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
        + Tambah Product
    </button>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>No</th>
            <th>Kategori</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Stock</th>
            <th width="150">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $key => $product)
            <tr id="row-{{ $product->id }}">
                <td>{{ $key+1 }}</td>
                <td>{{ $product->category->name }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->price) }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <button class="btn btn-warning btn-sm editBtn" data-id="{{ $product->id }}">Edit</button>
                    <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $product->id }}">Delete</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


    {{-- ================= CREATE MODAL ================= --}}
    <div class="modal fade" id="createModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="createForm">
                    <div class="modal-header">
                        <h5>Tambah Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <select name="category_id" class="form-control mb-2" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>

                        <input type="text" name="name" class="form-control mb-2" placeholder="Nama" required>
                        <input type="number" name="price" class="form-control mb-2" placeholder="Harga" required>
                        <input type="number" name="stock" class="form-control" placeholder="Stock" required>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ================= EDIT MODAL ================= --}}
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm">
                    <div class="modal-header">
                        <h5>Edit Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="edit_id">

                        <select id="edit_category" class="form-control mb-2">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>

                        <input type="text" id="edit_name" class="form-control mb-2">
                        <input type="number" id="edit_price" class="form-control mb-2">
                        <input type="number" id="edit_stock" class="form-control">
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

        /* ================= FUNCTION ROW ================= */
        function makeRow(response, number){
            return `
<tr id="row-${response.id}">
<td>${number}</td>
<td>${response.category_name}</td>
<td>${response.name}</td>
<td>${response.price_formatted}</td>
<td>${response.stock}</td>
<td>
<button class="btn btn-warning btn-sm editBtn" data-id="${response.id}">Edit</button>
<button class="btn btn-danger btn-sm deleteBtn" data-id="${response.id}">Delete</button>
</td>
</tr>`;
        }


        /* ================= CREATE ================= */
        $(document).on('submit','#createForm',function(e){
            e.preventDefault();

            $.post("{{ route('products.store') }}", $(this).serialize(), function(res){

                let no = $('table tbody tr').length + 1;
                $('table tbody').append(makeRow(res, no));

                $('#createModal').modal('hide');
                $('#createForm')[0].reset();
            });
        });


        /* ================= SHOW EDIT ================= */
        $(document).on('click','.editBtn',function(){

            let id = $(this).data('id');

            $.get("/admin/produk/edit/"+id,function(data){
                $('#edit_id').val(data.id);
                $('#edit_name').val(data.name);
                $('#edit_price').val(data.price);
                $('#edit_stock').val(data.stock);
                $('#edit_category').val(data.category_id);
                $('#editModal').modal('show');
            });

        });


        /* ================= UPDATE ================= */
        $(document).on('submit','#editForm',function(e){
            e.preventDefault();

            let id = $('#edit_id').val();

            $.post("/admin/produk/update/"+id,{
                category_id: $('#edit_category').val(),
                name: $('#edit_name').val(),
                price: $('#edit_price').val(),
                stock: $('#edit_stock').val()
            },function(res){

                let number = $("#row-"+id+" td:first").text();
                $("#row-"+id).replaceWith(makeRow(res, number));

                $('#editModal').modal('hide');
            });
        });


        /* ================= DELETE ================= */
        $(document).on('click','.deleteBtn',function(){

            if(confirm('Hapus product?')){
                let id = $(this).data('id');

                $.ajax({
                    url:"/admin/produk/delete/"+id,
                    type:"DELETE",
                    success:function(){
                        $("#row-"+id).remove();
                    }
                });
            }

        });

    </script>
@endsection
