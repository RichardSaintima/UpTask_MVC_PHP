<div class="contenedor reestablecer">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nuevo Contraseña</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <?php if($mostrar) { ?>

        <form method="POST" class="formulario">
            <div class="campo">
                <label for="password">Nuevo Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Tu Nuevo Contraseña">
            </div>

            <div class="campo">
                <label for="password2">Repite Contraseña</label>
                <input type="password" id="password2" name="password2" placeholder="Repite Nuevo Contraseña">
            </div>

            <input type="submit" class="boton" value="Guardar Contraseña">
        </form>

        <?php } ?>

        <div class="acciones">
            <a href="/crear">¿Aun no tienes una Cuenta? Obtener una</a>
            <a href="/olvide">¿Olvidaste tu contraseña?</a>
        </div>

    </div> <!--contenedor-sm -->
</div>