<div class="contenedor olvide">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupere tu Contraseña De Uptask</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form action="/olvide" method="POST" class="formulario" novalidate>
            <div class="campo">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Tu E-mail">
            </div>
            <input type="submit" class="boton" value="Recuperar">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes Cuenta? Iniciar Sesión</a>
            <a href="/crear">¿Aun no tienes una Cuenta? Obtener una</a>
        </div>

    </div> <!--contenedor-sm -->
</div>