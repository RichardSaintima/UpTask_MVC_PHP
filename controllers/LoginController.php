<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login( Router $router ) {
        
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] ==='POST') {
            $usuario = new Usuario($_POST);

            $alertas = $usuario->validarLogin();

            if(empty($alertas)) {
                // Verificar que el usuario existe
                $usuario = Usuario::where('email', $usuario->email);

                if(!$usuario  || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El Usuario No existe O no esta Confirmado');
                } else {
                    // El Usuario existe
                    if(password_verify($_POST['password'], $usuario->password)) {
                        // Iniciar la session
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        
                        // Redirrecionar
                        header('Location: /dashboard');
                    }else {
                        Usuario::setAlerta('error', 'Contraseña Incorrecto');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();
        // Render a la Vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas,
        ]);
    }

    public static function logout() {
        session_start();

        $_SESSION = [];
        header('Location: /');
        debuguear($_SESSION);
    }

    public static function crear( Router $router) {

        $usuario = new Usuario;
        $alertas = [];
    
        if($_SERVER['REQUEST_METHOD'] ==='POST') {
           $usuario->sincronizar($_POST);
           $alertas = $usuario->validarNuevaCuenta();
           
            if(empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);
                if($existeUsuario) {
                    Usuario::setAlerta('error', 'El Usuario ya esta  registrado');
                    $alertas = Usuario::getAlertas();
                }else {
                    // Hashear el Password
                    $usuario->hashPassword();

                    // Eliminar Password2
                    unset($usuario->password2);

                    // Generar el Token
                    $usuario->crearToken();

                    // Crear un nuevo usuario
                    $resultado = $usuario->guardar();

                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }
        
        // Render a la Vista
        $router->render('auth/crear', [
            'titulo' => 'Crea tu Cuenta en UpTask',
            'usuario' => $usuario, 
            'alertas' => $alertas,
        ]);
    }

    public static function olvide( Router $router) {
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] ==='POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)) {
                // buscar el usuario
                $usuario = Usuario::where('email', $usuario->email);

                if($usuario && $usuario->confirmado === "1") {
                    // Generar un nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    // Actualiza el usuario
                    $usuario->guardar();

                    // Enviar el Email
                    $email = new Email( $usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Imprime el alerta
                    Usuario::setAlerta('exito', 'Hemos enviado las Instrucciones a tu E-mail');
                    $alertas = Usuario::getAlertas();

                }else {
                    // Encuentro al Usuario
                    Usuario::setAlerta('error', 'El Usuario no existe o No esta Comfirmado');
                    $alertas = Usuario::getAlertas();
                }
            }
        }

        // Render a la Vista
        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi Contraseña',
            'alertas' => $alertas,
        ]);
    }

    public static function reestablecer( Router $router) {
        $token =s($_GET['token']);
        $mostrar = true;
        // Identifica el Usuario
        $usuario = Usuario::where('token', $token);

        if(!$token) header('Location: /');
                
        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no Válido');
            $mostrar = false;
        }

        if($_SERVER['REQUEST_METHOD'] ==='POST') {

            // Añadir el Nuevo Password
            $usuario->sincronizar($_POST);
            
            $alertas = $usuario->validarPassword();

            if(empty($alertas)) {
                // Hashear el password Nuevo
                $usuario->hashPassword();
                // Eliminar Password2
                unset($usuario->password2);

                // Eliminar el token
                $usuario->token = null;

                // Guardar el usuario en la BD
                $resultado = $usuario->guardar();

                // Redirecccionar
                if($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        // Render a la Vista
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablece tu Contraseña',
            'alertas' => $alertas,
            'mostrar' => $mostrar,
        ]);     
    }

    
    public static function mensaje( Router $router) {
        
        // Render a la Vista
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente',
        ]);   
    }

    public static function confirmar( Router $router) {
        $token =s($_GET['token']);
        if(!$token) header('Location: /');

        // Encuentrar al usuario Con este token
        $usuario = Usuario::where('token', $token);

       if(empty($usuario)) {
        // Nose encuentro Un usuario con este token
        Usuario::setAlerta('error', 'Token No Valido');
       }else {
        // Comfirmar cuenta
        $usuario->confirmado = 1;
        $usuario->token = null;
        // Eliminar Password2
        unset($usuario->password2);
        
        // Guardar el Usuario en la BD
        $usuario->guardar();

        Usuario::setAlerta('exito', 'Cuenta Comprobada correctamente');
       }

       $alertas = Usuario::getAlertas();

        // Render a la Vista
        $router->render('auth/confirmar', [
            'titulo' => 'Cuenta Confirmada',
            'alertas' => $alertas,
        ]);   
    }


}