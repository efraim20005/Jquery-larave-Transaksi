<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h4>Login AJAX</h4>
                </div>
                <div class="card-body">

                    <div id="error" class="alert alert-danger d-none"></div>

                    <form id="loginForm">
                        @csrf

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button class="btn btn-primary w-100" id="btnLogin">
                            Login
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    $('#loginForm').submit(function(e){
        e.preventDefault();

        $('#btnLogin').text('Loading...');

        $.ajax({
            url: "{{ route('login.process') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(res){

                if(res.success){
                    window.location.href = res.redirect;
                }else{
                    $('#error').removeClass('d-none').text(res.message);
                    $('#btnLogin').text('Login');
                }

            },
            error: function(){
                $('#error').removeClass('d-none').text('Terjadi error server');
                $('#btnLogin').text('Login');
            }
        });
    });
</script>

</body>
</html>
