<div class="contenedor crear">
    <?php
        include_once __DIR__ . '/../templates/nombre-sitio.php'; 
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crear tu Cuenta en UpTask</p>
        <?php
        include_once __DIR__ . '/../templates/alertas.php'; 
        ?>

        <form action="/crear" method="POST" class="formulario">
            <div class="campo">
                <label for="nombre">Nombres</label>
                <input type="text" id="nombre" name="nombre" placeholder="Tu Nombre Completo" value="<?php echo $usuario->nombre; ?>">
            </div>

            <div class="campo">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Tu E-mail"  value="<?php echo $usuario->email; ?>" >
            </div>

            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Tu Password">
            </div>

            <div class="campo">
                <label for="password2">Repetir Password</label>
                <input type="password" id="password2" name="password2" placeholder="Repitir Tu Password">
            </div>

            <input type="submit" class="boton" value="Crear Cuenta">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes Cuenta? Iniciar Sesión</a>
            <a href="/olvide">¿Olvidaste tu contraseña?</a>
        </div>

    </div> <!--contenedor-sm -->
</div>