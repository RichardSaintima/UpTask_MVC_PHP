(function() {
    obtenerTareas();
    let tareas =[];
    let filtradas =[];
    // Boton para mostrar el model de Agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', function() {
        mostrarFormulario();
    } );

    // Flitros de busqueda
    const flitros = document.querySelectorAll('#filtros input[type="radio"');
    flitros.forEach( radio => {
        radio.addEventListener('input', filtrasTareas);
    });

    function filtrasTareas(e) {
        const filtro = e.target.value;

        if(filtro !== '') {
            filtradas = tareas.filter(tarea => tarea.estado === filtro);
        } else {
            filtradas = [];
        }
        mostrarTareas();
    }

    async function obtenerTareas() {
        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?id=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            tareas  = resultado.tareas
            mostrarTareas();

        } catch (error) {
            console.log(error);
        }
    }

    function mostrarTareas() {
        limpiarTareas();
        totalPendientes();
        totalcompletas();

        const arrayTareas = filtradas.length ? filtradas : tareas;

        if(arrayTareas.length === 0) {
            const contenedorTareas = document.querySelector('#listado-tareas');

            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent =  'No Hay Tareas';
            textoNoTareas.classList.add('no-tareas');

            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        const estados = {
            0: 'Pendiente',
            1: 'Completa'
        }

        arrayTareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');

            const nombreTarea = document.createElement('P');
            nombreTarea.textContent  = tarea.nombre;
            nombreTarea.ondblclick = function() {
                mostrarFormulario(true, {...tarea});
            }

            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            // BOTTONES
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado]
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function() {
                cambiarEstadoTarea({...tarea});
            }

            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea'); 
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.ondblclick = function() {
                confirmarEliminarTarea({...tarea});
            }

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listadoTarea = document.querySelector('#listado-tareas');
            listadoTarea.appendChild(contenedorTarea);
        });
    }
    
    function totalPendientes() {
        const totalPendientes = tareas.filter(tarea => tarea.estado === '0');
        const pendientesRadio = document.querySelector('#pendientes');

        if(totalPendientes.length === 0) {
            pendientesRadio.disabled = true;
        } else {
            pendientesRadio.disabled = false;
        }
    }

    function totalcompletas() {
        const totalcompletas = tareas.filter(tarea => tarea.estado === '1');
        const completasRadio = document.querySelector('#completadas');

        if(totalcompletas.length === 0) {
            completasRadio.disabled = true;
        } else {
            completasRadio.disabled = false;
        }
    }

    function mostrarFormulario(editar = false, tarea = {}) {
        console.log(editar);
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = 
        `
            <form class="formulario nueva-tarea">
                <legend>${ editar ? 'Editar Tarea' : 'Añade un Nueva Tarea'}</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input type="text" name="tarea" placeholder="${tarea.nombre ? 'Editar el Nombre de la Tarea' : 'Añadir una nueva Tarea al Proyecto'}" 
                    id="tarea" value="${tarea.nombre ? tarea.nombre : ''}">
                </div>
                <div class="opciones">
                    <input 
                    type="submit" 
                    class="submit-nueva-tarea" 
                    value="${ tarea.nombre ? 'Guardar Cambios' : 'Añadir Tarea'} "> 
                    <button type="button" class="cerrar-modal"> Cancelar</button>
                </div>
            </form>
        
        `;

        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        modal.addEventListener('click', function(e) {
            e.preventDefault();

            if(e.target.classList.contains('cerrar-modal')) {
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 500);
        
            }

            if(e.target.classList.contains('submit-nueva-tarea')) {
                const nombreTarea = document.querySelector('#tarea').value.trim();

                if(nombreTarea === '') {
                    // Mostrar una alerta de error
                    mostrarAlerta('El Nombre de la Tarea es Obligatorio', 'error', 
                    document.querySelector('.formulario legend'));
                    return;
                }
                if(editar) {
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                }else {
                    agregarTarea(nombreTarea);
                }
            }
        });
        document.querySelector('.dashboard').appendChild(modal);
    }


    function mostrarAlerta( mensaje,  tipo, referiencia) {
        // Previne muchas alertas
        const previaAlerta = document.querySelector('.alertas');
        if(previaAlerta) {
            previaAlerta.remove();
        }


        const alerta = document.createElement('DIV');
        alerta.classList.add('alertas', tipo);
        alerta.textContent = mensaje;

        // referiencia.appendChild(alerta);
        // referiencia.insertBefore(alerta);
        // referiencia.nextElementSibling(alerta);

        // Inserta la Alerta antes del legend
        // referiencia.parentElement.insertBefore(alerta, referiencia);

        // Inserta la Alerta despues del legend
        referiencia.parentElement.insertBefore(alerta, referiencia.nextElementSibling);

        setTimeout(() => {
            alerta.remove();
        }, 5000);
    }

    // Consultar el Servidor para añidir una nueva tarea al proyecto actual
    async function agregarTarea(tarea) {
        // Comtruir la peticion
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = 'http://localhost:3000/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();

            mostrarAlerta(resultado.mensaje, resultado.tipo, 
            document.querySelector('.formulario legend'));

            if(resultado.tipo === 'exito') {
                const modal =document.querySelector('.modal');

                setTimeout(() => {
                    modal.remove();
                    // window.location.reload();
                }, 1500);

                // Agragar el objetivo de tarea al global
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                }

                tareas = [...tareas, tareaObj];
                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }
    }

    function cambiarEstadoTarea( tarea) {
        const nuevaEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevaEstado;
        actualizarTarea(tarea);
    }

    async function actualizarTarea(tarea) {
        const {estado, id, nombre, proyectoId} = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        // for(let valor of datos.values()) {
        //     console.log(valor);
        // }

        try {
            const url = 'http://localhost:3000/api/tarea/actualizar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            // console.log(respuesta);
            
            if(resultado.respuesta.tipo === 'exito') {
                Swal.fire(
                    resultado.respuesta.mensaje, resultado.respuesta.mensaje, 'success'
                );

                const modal = document.querySelector('.modal');
                    if(modal) {
                        modal.remove();
                    }
                    

                tareas =tareas.map( tareaMemoria => {
                    if(tareaMemoria.id === id) {
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }
                    return tareaMemoria;
                });
                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }
    }

    function confirmarEliminarTarea(tarea) {
        Swal.fire({
            title: '¿Eliminar Tarea?',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.isConfirmed) {
              eliminarTarea(tarea);
            }
        })
    }

    async function eliminarTarea(tarea) {
        const {estado, id, nombre} = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());


        try {
            const url = 'http://localhost:3000/api/tarea/eliminar';
            const respuesta = await  fetch(url, {
                method: 'POST',
                body: datos
            });
            // console.log(respuesta);

            const resultado = await respuesta.json();
            if(resultado.resultado) {
                // mostrarAlerta(resultado.mensaje, resultado.tipo,
                //     document.querySelector('.contenedor-nueva-tarea')
                // );

                Swal.fire('Eliminado', resultado.mensaje, 'success');

                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);
                mostrarTareas();
            }
        } catch (error) {
            console.log(error);
        }
    }

    function obtenerProyecto() {
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
    }

    function limpiarTareas() {
        const listadoTareas = document.querySelector('#listado-tareas');

        while(listadoTareas.firstChild) {
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
        
    }
})();