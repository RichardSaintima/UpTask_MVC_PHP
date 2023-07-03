<?php include_once __DIR__ .'/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/perfil" class="enlace">Volver al Perfil</a>

    <form action="/cambiar-password" method="POst" class="formulario">
        <div class="campo">
            <label for="password_actual">Contraseña Actual:</label>
            <input type="password"  
            name="password_actual" placeholder="Ingresa Contraseña Actual">
        </div>

        <div class="campo">
            <label for="password_nueva">Contraseña Nueva:</label>
            <input type="password" 
            name="password_nueva" placeholder="Ingresa Contraseña Nueva">
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>
</div>

<?php include_once __DIR__ .'/footer-dashboard.php'; ?>