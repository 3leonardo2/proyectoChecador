<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        .form-pills .form-control {
            border-radius: 50px;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 " style="background-color: #e6e8e4;">
    <div class="container-fluid px-0 flex-grow-1 d-flex align-items-center ">
        <div class="card shadow w-100">
            <div class="card-body py-5">
                <h2 class="text-center mb-4">Iniciar sesión</h2>

                <form class="form-pills mx-auto" style="max-width: 400px;">
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" class="form-control" placeholder="example@gmail.com">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-control" placeholder="••••••••">
                    </div>

                    <button type="submit" class="btn btn-custom w-100 rounded-pill py-2">
                        Iniciar Sesión
                    </button>

                    <div class="text-center mt-3">
                        <a  class="btn btn-secondary btn-lg "style="font-size: 1.1rem;" href="/edit_prac" >Ir a bitácora de practicantes</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
