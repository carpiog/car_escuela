import { Dropdown } from "bootstrap";
import { Toast, validarFormulario } from "../funciones";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import Swal from "sweetalert2";

// Elementos del DOM
const formulario = document.getElementById('formInstructor');
const tabla = document.getElementById('tablaInstructor');
const btnGuardar = document.getElementById('btnGuardar');
const btnModificar = document.getElementById('btnModificar');
const btnCancelar = document.getElementById('btnCancelar');

// Inicialización del DataTable
const datatable = new DataTable('#tablaInstructor', {
    language: lenguaje,
    pageLength: 15,
    lengthMenu: [3, 9, 11, 25, 100],
    columns: [
        {
            title: 'No.',
            data: null,
            width: '2%',
            render: (data, type, row, meta) => meta.row + 1
        },
        {
            title: 'Catálogo',
            data: 'ins_catalogo'
        },
        {
            title: 'Grado y Arma',
            data: 'grado_arma'
        },
        {
            title: 'Nombres Completos',
            data: 'nombres_apellidos'
        },
        {
            title: 'Dependencia',
            data: 'puesto_dependencia'
        },
        {
            title: 'Acciones',
            data: 'ins_id',
            searchable: false,
            orderable: false,
            render: (data, type, row) => `
                <button class='btn btn-warning btn-sm modificar' data-id="${data}" 
                    data-catalogo="${row.ins_catalogo}">
                    <i class='bi bi-pencil-square' title='MODIFICAR'></i>
                </button>
                <button class='btn btn-danger btn-sm eliminar ms-2' data-id="${data}">
                    <i class='bi bi-trash' title='ELIMINAR'></i>
                </button>
            `
        }
    ]
});

// Configuración inicial de botones
const configurarBotones = (modo = 'inicial') => {
    if (modo === 'inicial') {
        btnModificar.parentElement.style.display = 'none';
        btnModificar.disabled = true;
        btnCancelar.parentElement.style.display = 'none';
        btnCancelar.disabled = true;
        btnGuardar.parentElement.style.display = '';
        btnGuardar.disabled = false;
    } else if (modo === 'edicion') {
        btnModificar.parentElement.style.display = '';
        btnModificar.disabled = false;
        btnCancelar.parentElement.style.display = '';
        btnCancelar.disabled = false;
        btnGuardar.parentElement.style.display = 'none';
        btnGuardar.disabled = true;
    }
}

// Inicialización
configurarBotones('inicial');

// Función para guardar
const guardar = async (e) => {
    e.preventDefault();

    if (!validarFormulario(formulario, ['ins_id'])) {
        Toast.fire({
            icon: 'info',
            title: 'Debe ingresar un catálogo'
        });
        return;
    }

    try {
        const body = new FormData(formulario);
        const respuesta = await fetch("/car_escuela/API/instructor/guardar", {
            method: 'POST',
            body
        });
        const data = await respuesta.json();

        Toast.fire({
            icon: data.codigo === 1 ? 'success' : 'error',
            title: data.mensaje
        });

        if (data.codigo === 1) {
            formulario.reset();
            buscar();
        }
    } catch (error) {
        console.error(error);
        Toast.fire({
            icon: 'error',
            title: 'Error al guardar'
        });
    }
}

// Función para modificar
const modificar = async (e) => {
    e.preventDefault();

    if (!validarFormulario(formulario)) {
        Toast.fire({
            icon: 'info',
            title: 'Complete todos los campos'
        });
        return;
    }

    try {
        const body = new FormData(formulario);
        const respuesta = await fetch("/car_escuela/API/instructor/modificar", {
            method: 'POST',
            body
        });
        const data = await respuesta.json();

        Toast.fire({
            icon: data.codigo === 1 ? 'success' : 'error',
            title: data.mensaje
        });

        if (data.codigo === 1) {
            formulario.reset();
            buscar();
            configurarBotones('inicial');
        }
    } catch (error) {
        console.error(error);
        Toast.fire({
            icon: 'error',
            title: 'Error al modificar'
        });
    }
}

const eliminar = async (e) => {
    try {
        const id = e.currentTarget.dataset.id;
        
        if (!id) {
            Toast.fire({
                icon: 'error',
                title: 'Error: ID no encontrado'
            });
            return;
        }

        const result = await Swal.fire({
            title: '¿Está seguro?',
            text: "Esta acción no se puede revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('ins_id', id);

            const respuesta = await fetch("/car_escuela/API/instructor/eliminar", {
                method: 'POST',
                body: formData
            });

            if (!respuesta.ok) {
                throw new Error(`HTTP error! status: ${respuesta.status}`);
            }

            const data = await respuesta.json();
            
            Toast.fire({
                icon: data.codigo === 1 ? 'success' : 'error',
                title: data.mensaje || 'Error al procesar la solicitud'
            });

            if (data.codigo === 1) {
                await buscar();
            }
        }
    } catch (error) {
        console.error('Error al eliminar:', error);
        Toast.fire({
            icon: 'error',
            title: 'Error al eliminar el instructor'
        });
    }
}

// Función para traer datos al formulario
const traerDatos = (e) => {
    const elemento = e.currentTarget.dataset;
    formulario.ins_id.value = elemento.id;
    formulario.ins_catalogo.value = elemento.catalogo;
    configurarBotones('edicion');
}

// Función para cancelar
const cancelar = () => {
    formulario.reset();
    configurarBotones('inicial');
}

// Función para buscar/actualizar tabla
const buscar = async () => {
    try {
        const respuesta = await fetch("/car_escuela/API/instructor/buscar");
        const data = await respuesta.json();
        
        datatable.clear();
        if (data.codigo === 1 && data.datos) {
            datatable.rows.add(data.datos).draw();
        }
    } catch (error) {
        console.error(error);
        Toast.fire({
            icon: 'error',
            title: 'Error al buscar datos'
        });
    }
}

// Event Listeners
formulario.addEventListener('submit', guardar);
btnModificar.addEventListener('click', modificar);
btnCancelar.addEventListener('click', cancelar);
datatable.on('click', '.modificar', traerDatos);
datatable.on('click', '.eliminar', eliminar);

// Cargar datos iniciales
buscar();