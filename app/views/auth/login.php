<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Creaciones Justean</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body {
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a, #020617);
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            background: #f8fafc;
            padding: 30px;
            border-radius: 16px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .icon-box {
            background: #4f46e5;
            color: white;
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 22px;
        }

        .login-card input {
            border-radius: 8px;
        }

        .btn-login {
            border-radius: 8px;
            background: #e5e7eb;
        }

        .small-text {
            font-size: 0.8rem;
            color: #6b7280;
        }
    </style>
</head>
<body>

<div class="login-card">

    <div class="text-center mb-3">
        <div class="icon-box">
            <i class="fa-solid fa-shirt"></i>
        </div>
        <h4 class="mt-2">Creaciones Justean</h4>
        <p class="text-muted mb-3">Sistema de inventario</p>
    </div>

    <form action="<?php echo URLROOT; ?>auth/login" method="POST">

        <?php if(isset($data['error'])) : ?>
            <div class="alert alert-danger">
                <?php echo $data['error']; ?>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label>Usuario</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                <input type="text" name="login" class="form-control" placeholder="Usuario" required>
            </div>
        </div>

        <div class="mb-3">
            <label>Contraseña</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <div>
                <input type="checkbox"> Recordar sesión
            </div>
            <a href="#" class="small">¿Olvidaste tu contraseña?</a>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-login">
                <i class="fa-solid fa-right-to-bracket me-2"></i>Ingresar
            </button>
        </div>

        <p class="text-center mt-3 small-text">
            Acceso restringido — solo personal autorizado
        </p>

    </form>
</div>

</body>
</html>