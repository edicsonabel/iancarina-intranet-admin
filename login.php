<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Title -->
    <title>Admin</title>
    <!-- Bootstrap css -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />

    <!-- Bootstrap font icons css -->
    <link rel="stylesheet" href="assets/fonts/bootstrap/bootstrap-icons.css" />

    <!-- Main css -->
    <link rel="stylesheet" href="assets/css/main.min.css" />

    <!-- Login css -->
    <link rel="stylesheet" href="assets/css/login.css" />
</head>

<body class="login-container">
    <!-- Login box start -->
    <div class="container">
        <form action="#" id="formLogin">
            <div class="login-box rounded-2 p-5">
                <div class="login-form">
                    <a href="#" class="login-logo mb-3 justify-content-center">
                        <img src="assets/images/logo_rojo.svg" />
                    </a>
                    <h5 class="my-3">Inicio de Sesion</h5>
                    <div class="mb-3">
                        <label class="form-label">Usuario</label>
                        <input type="text" id="usuario_login" name="usuario_login" class="form-control" placeholder="Ingrese su usario" autocomplete="off" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Clave</label>
                        <input type="password" id="clave_login" name="clave_login" class="form-control" placeholder="Coloque su clave" autocomplete="off" />
                    </div>

                    <div class="d-grid py-3">
                        <button type="btnLogin" id="btnLogin" class="btn btn-lg btn-primary">
                            Ingresar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Login box end -->
</body>

</html>
<?php include_once('footer.php'); ?>
<script>
    const formLogin = document.querySelector("#formLogin");
    const btnLogin = document.getElementById('btnLogin');
    const usuario = document.getElementById('usuario_login');
    const clave = document.getElementById('clave_login');

    btnLogin.addEventListener('click', function(event) {
        event.preventDefault();

        if (usuario.value == "" || clave.value == "") {
            Swal.fire('Error', 'Completa todos los campos...', 'error');
            return false;
        }

        const form = new FormData(formLogin);
        form.append("function", "login");

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'Validar_Usuario.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = xhr.responseText;
                console.log(response);
                try {
                    var res = JSON.parse(response);
                    if (res.status === 200) {
                        Swal.fire('Éxito', res.message, 'success');
                        sessionStorage.setItem("user", JSON.stringify(res));
                        window.location.href = "index.php";
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire('Error', 'Ocurrió un error al procesar la respuesta', 'error');
                }
            } else {
                Swal.fire('Error', 'Ocurrió un error al procesar la solicitud', 'error');
            }
        };
        xhr.send(form);
    });
</script>