@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="mb-4">Transaksi Kasir</h3>

        <div class="row">
            <div class="col-md-6">
                <h5>Daftar Produk</h5>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>{{ number_format($p->price) }}</td>
                            <td>{{ $p->stock }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary addCart"
                                        data-id="{{ $p->id }}"
                                        data-name="{{ $p->name }}"
                                        data-price="{{ $p->price }}">
                                    Tambah
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <h5>Keranjang</h5>
                <table class="table table-bordered" id="cartTable">
                    <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>

                    <tbody></tbody>
                </table>

                <h4>Total: Rp <span id="total">0</span></h4>

                <button class="btn btn-success w-100 mt-3" id="checkout">
                    Checkout
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="product_id">
                    <input type="hidden" id="product_price">

                    <div class="mb-3">
                        <label>Nama Produk</label>
                        <input type="text" id="product_name" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Qty</label>
                        <input type="number" id="product_qty" class="form-control" min="1" value="1">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" class="btn btn-primary"
                            id="saveCart">
                        Tambah
                    </button>
                </div>

            </div>
        </div>
    </div>





@endsection

@section('scripts')
    <script>
        $(function() {

            let cart = [];

            function renderCart() {
                let tbody = $("#cartTable tbody");
                tbody.empty();

                let total = 0;

                cart.forEach((item, index) => {
                    let subtotal = item.price * item.qty;
                    total += subtotal;

                    tbody.append(`
                <tr>
                    <td>${item.name}</td>
                    <td>
                        <input type="number" min="1" value="${item.qty}"
                            class="form-control qty"
                            data-index="${index}">
                    </td>
                    <td>${item.price}</td>
                    <td>${subtotal}</td>
                    <td>
                        <button class="btn btn-danger btn-sm remove"
                            data-index="${index}">
                            X
                        </button>
                    </td>
                </tr>
            `);
                });

                $("#total").text(total);
            }

            $(document).on("click", ".addCart", function() {

                $("#product_id").val($(this).data("id"));
                $("#product_name").val($(this).data("name"));
                $("#product_price").val($(this).data("price"));
                $("#product_qty").val(1);

                let modal = new bootstrap.Modal(document.getElementById('modalTambah'));
                modal.show();
            });

            $("#saveCart").click(function() {

                let id = $("#product_id").val();
                let name = $("#product_name").val();
                let price = parseInt($("#product_price").val());
                let qty = parseInt($("#product_qty").val());

                let exist = cart.find(item => item.id == id);

                if (exist) {
                    exist.qty += qty;
                } else {
                    cart.push({ id, name, price, qty });
                }

                renderCart();

                bootstrap.Modal.getInstance(
                    document.getElementById('modalTambah')
                ).hide();
            });

            $(document).on("change", ".qty", function() {
                let index = $(this).data("index");
                cart[index].qty = parseInt($(this).val());
                renderCart();
            });

            $(document).on("click", ".remove", function() {
                let index = $(this).data("index");
                cart.splice(index, 1);
                renderCart();
            });

            $("#checkout").click(function() {

                if (cart.length === 0) {
                    alert("Keranjang masih kosong!");
                    return;
                }

                $.ajax({
                    url: "{{ route('transactions.store') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        products: cart,
                        total: $("#total").text()
                    },
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Transaksi berhasil disimpan!'
                        });


                        cart = [];
                        renderCart();

                        $("#total").text(0);
                    }
                    ,
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan!'
                        });

                    }
                });
            });

        });
    </script>
@endsection

