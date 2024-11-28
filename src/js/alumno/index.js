import { Dropdown } from "bootstrap";
import { Toast } from "../funciones";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// Elementos del DOM
const formulario = document.getElementById('formAlumno');
const btnGuardar = document.getElementById('btnGuardar');
const btnModificar = document.getElementById('btnModificar');
const btnCancelar = document.getElementById('btnCancelar');

// Configuración inicial de botones
btnModificar.parentElement.style.display = 'none';
btnModificar.disabled = true;
btnCancelar.parentElement.style.display = 'none';
btnCancelar.disabled = true;

// Inicialización del DataTable
const datatable = new DataTable('#tablaAlumno', {
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
            data: 'alu_catalogo'
        },
        {
            title: 'Grado',
            data: 'grado_nombre'
        },
        {
            title: 'Rango',
            data: 'rango_nombre'
        },
        {
            title: 'Nombres',
            data: 'nombres_completos',
            render: function (data, type, row) {
                if (type === 'display') {
                    return data.trim().replace(/\s+/g, ' ');
                }
                return data;
            }
        },
        {
            title: 'Acciones',
            data: 'alu_id',
            searchable: false,
            orderable: false,
            render: (data, type, row) => {
                return `
                    <button class='btn btn-warning btn-sm modificar' title='MODIFICAR' 
                        data-alu_id="${row.alu_id}"
                        data-alu_catalogo="${row.alu_catalogo}"
                        data-alu_grado_id="${row.alu_grado_id}"
                        data-alu_rango_id="${row.alu_rango_id}"
                        data-alu_primer_nombre="${row.alu_primer_nombre}"
                        data-alu_segundo_nombre="${row.alu_segundo_nombre || ''}"
                        data-alu_primer_apellido="${row.alu_primer_apellido}"
                        data-alu_segundo_apellido="${row.alu_segundo_apellido || ''}">
                        <i class='bi bi-pencil-square'></i>
                    </button>
                    <button class='btn btn-danger btn-sm eliminar' data-alu_id="${row.alu_id}">
                        <i class='bi bi-trash' title='ELIMINAR'></i>  
                    </button>
                    <button class='btn btn-info btn-sm ver-sanciones' data-alu_id="${row.alu_id}">
                        <i class='bi bi-file-earmark-pdf' title='VER SANCIONES'></i> 
                    </button>
                `;
            }
        }
    ]
});

// Función para guardar alumno
const guardar = async (e) => {
    e.preventDefault();

    try {
        const formData = new FormData(formulario);
        const response = await fetch("/car_escuela/API/alumno/guardar", {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.codigo === 1) {
            Toast.fire({
                icon: 'success',
                title: data.mensaje
            });
            formulario.reset();
            buscar();
        } else {
            Toast.fire({
                icon: 'error',
                title: data.mensaje
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Toast.fire({
            icon: 'error',
            title: 'Error al guardar el alumno'
        });
    }
};

// Función para buscar alumnos
const buscar = async () => {
    try {
        const url = "/car_escuela/API/alumno/buscar";
        const respuesta = await fetch(url, { method: 'GET' });
        const { codigo, datos } = await respuesta.json();

        datatable.clear();

        if (datos && Array.isArray(datos)) {
            datatable.rows.add(datos).draw();
        }
    } catch (error) {
        console.error('Error al buscar alumnos:', error);
        Toast.fire({
            icon: 'error',
            title: 'Error al cargar los datos'
        });
    }
};

// Función para traer datos al formulario (modificar)
const traerDatos = (e) => {
    const botonModificar = e.target.closest('.modificar');
    if (!botonModificar) return;

    const dataset = botonModificar.dataset;

    // Asignar valores a los campos del formulario
    formulario.alu_id.value = dataset.alu_id;
    formulario.alu_catalogo.value = dataset.alu_catalogo;
    formulario.alu_grado_id.value = dataset.alu_grado_id;
    formulario.alu_rango_id.value = dataset.alu_rango_id;
    formulario.alu_primer_nombre.value = dataset.alu_primer_nombre;
    formulario.alu_segundo_nombre.value = dataset.alu_segundo_nombre;
    formulario.alu_primer_apellido.value = dataset.alu_primer_apellido;
    formulario.alu_segundo_apellido.value = dataset.alu_segundo_apellido;

    // Cambiar visibilidad de elementos
    document.querySelector('#tablaAlumno').closest('.row').style.display = 'none';
    btnGuardar.parentElement.style.display = 'none';
    btnGuardar.disabled = true;
    btnModificar.parentElement.style.display = '';
    btnModificar.disabled = false;
    btnCancelar.parentElement.style.display = '';
    btnCancelar.disabled = false;
};

// Función para cancelar modificación
const cancelar = () => {
    document.querySelector('#tablaAlumno').closest('.row').style.display = '';
    formulario.reset();
    btnGuardar.parentElement.style.display = '';
    btnGuardar.disabled = false;
    btnModificar.parentElement.style.display = 'none';
    btnModificar.disabled = true;
    btnCancelar.parentElement.style.display = 'none';
    btnCancelar.disabled = true;
};

// Función para modificar alumno
const modificar = async (e) => {
    e.preventDefault();

    try {
        const formData = new FormData(formulario);

        // Validar campos requeridos
        const camposRequeridos = [
            'alu_primer_nombre',
            'alu_primer_apellido',
            'alu_catalogo',
            'alu_grado_id',
            'alu_rango_id'
        ];

        for (const campo of camposRequeridos) {
            if (!formData.get(campo)) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Todos los campos marcados son obligatorios'
                });
                return;
            }
        }

        const response = await fetch("/car_escuela/API/alumno/modificar", {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.codigo === 1) {
            Toast.fire({
                icon: 'success',
                title: data.mensaje
            });
            formulario.reset();
            buscar();
            cancelar();
        } else {
            Toast.fire({
                icon: 'error',
                title: data.mensaje
            });
            console.error(data.detalle);
        }
    } catch (error) {
        console.error(error);
        Toast.fire({
            icon: 'error',
            title: 'Error al modificar el alumno'
        });
    }
};

const eliminar = async (e) => {
    try {
        // Obtener el botón eliminar directamente
        const botonEliminar = e.target.closest('.eliminar');
        if (!botonEliminar) return;

        const id = botonEliminar.getAttribute('data-alu_id');

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
            formData.append('alu_id', id);

            const respuesta = await fetch("/car_escuela/API/alumno/eliminar", {
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
            title: 'Error al eliminar el alumno'
        });
    }
}

// Event Listeners
formulario.addEventListener('submit', guardar);
btnModificar.addEventListener('click', modificar);
btnCancelar.addEventListener('click', cancelar);



// Función para manejar el clic en ver sanciones
const verSanciones = async (e) => {
    const botonSanciones = e.target.closest('.ver-sanciones');
    if (!botonSanciones) return;

    const id = botonSanciones.getAttribute('data-alu_id');
    if (!id) {
        Toast.fire({
            icon: 'error',
            title: 'Error: ID no encontrado'
        });
        return;
    }


    // Asegúrate que esta URL sea exactamente igual a tu estructura
    const url = `/car_escuela/reporte/sancionesPDF?id=${id}`;
    console.log('URL:', url); // Para debugging
    window.open(url, '_blank');
};

// Agregar al event listener existente
document.querySelector('#tablaAlumno').addEventListener('click', (e) => {
    if (e.target.closest('.modificar')) {
        traerDatos(e);
    } else if (e.target.closest('.eliminar')) {
        eliminar(e);
    } else if (e.target.closest('.ver-sanciones')) {
        verSanciones(e);
    }
});

// Validación en tiempo real para el campo de catálogo
document.getElementById('alu_catalogo').addEventListener('input', function (e) {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
});

// Iniciar búsqueda al cargar
buscar();
