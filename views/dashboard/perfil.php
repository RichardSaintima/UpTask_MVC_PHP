<?php include_once __DIR__ .'/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/cambiar-password" class="enlace">Cambiar Password</a>

    <form action="/perfil" method="POst" class="formulario">
        <div class="campo">
            <label for="nombre">Nombre:</label>
            <input type="text" 
            value="<?php echo $usuario->nombre; ?>" 
            name="nombre" placeholder="Tu Nombre">
        </div>

        <div class="campo">
            <label for="email">E-mail:</label>
            <input type="email" 
            value="<?php echo $usuario->email; ?>" 
            name="email" placeholder="Tu E-mail">
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>
</div>

<?php include_once __DIR__ .'/footer-dashboard.php'; ?>