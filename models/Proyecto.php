<?php

namespace Model;

class Proyecto extends ActiveRecord {
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id','proyecto','url', 'propietarioId'];


    public $id;
    public $url;
    public $proyecto;
    public $propietarioId;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->url = $args['url'] ?? '';
        $this->proyecto = $args['proyecto'] ?? '';
        $this->propietarioId = $args['propietarioId'] ?? '';

    }

    public function validarProyecto() {
        if(!$this->proyecto) {
            self::$alertas['error'][] = 'El Nombre del Proyecto Es Obligatoria';
        }

        return self::$alertas;
    }
}