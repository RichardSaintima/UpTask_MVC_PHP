<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_actual;
    public $password_nueva;
    public $token;
    public $confirmado;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nueva = $args['password_nueva'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    // Validar el Login del Usuario
    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El E-mail es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'E-mail no Valido';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'La Contraseña es no puede ir vacio';
        } 
        if(strlen($this->password) <6 ) {
            self::$alertas['error'][] = 'La Contraseña debe tener al menos 6 caracteres';
        } 
        return self::$alertas;  
    }

    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El E-mail es Obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'La Contraseña es Obligatorio';
        }
        if(strlen($this->password) <6 ) {
            self::$alertas['error'][] = 'La Contraseña debe tener al menos 6 caracteres';
        }
        if($this->password !== $this->password2 ) {
            self::$alertas['error'][] = 'Las Contraseñas Son Diferentes';
        }
        return self::$alertas;
    }


    // Valida un email
    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El E-mail es Obligatorio';
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'E-mail no Valido';
        }
        return self::$alertas;
    }

    // Validar Password
    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'La Contraseña es Obligatorio';
        }
        if(strlen($this->password) <6 ) {
            self::$alertas['error'][] = 'La Contraseña debe tener al menos 6 caracteres';
        }
        if($this->password !== $this->password2 ) {
            self::$alertas['error'][] = 'Las Contraseñas Son Diferentes';
        }
        return self::$alertas;
    }

    public function validar_perfil() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es Obligatorio';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El E-mail es Obligatorio';
        }
        return self::$alertas;
    }

    public function nuevo_password() : array {
        if(!$this->password_actual) {
            self::$alertas['error'][] = 'El Password Actual No Puede ir Vacio';
        }
        if(!$this->password_nueva) {
            self::$alertas['error'][] = 'El Password Nuevo No Puede ir Vacio';
        }
        if(strlen($this->password_nueva) < 6) {
            self::$alertas['error'][] = 'El Password Debe contener al menos 6 Caractares';
        }
        return self::$alertas;
    }
    
    public function comprobar_password() : bool {
        return password_verify($this->password_actual, $this->password);
    }

    // Hashea el password
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    
    // Generar un Token
    public function crearToken() : void {
        $this->token = (uniqid());
    }
}