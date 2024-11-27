import { Dropdown } from "bootstrap";
import { Toast } from "../funciones";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// Elementos del DOM
const formulario = document.getElementById('formCumplimiento');
const btnGuardar = document.getElementById('btnGuardar');
const btnModificar = document.getElementById('btnModificar');
const btnCancelar = document.getElementById('btnCancelar');
const selectSancion = document.getElementById('cum_sancion_id');
const inputHorasCumplidas = document.getElementById('cum_horas_cumplidas');
const inputHorasPendientes = document.getElementById('cum_horas_pendientes');
const fechaCumplimiento = document.getElementById('cum_fecha');

// Configurar fecha máxima como la actual
fechaCumplimiento.max = new Date().toISOString().split('T')[0];

// Inicialización del DataTable
const datatable = new DataTable('#tablaCumplimiento', {
    data: null,
    language: lenguaje,
    pageLength: 15,
    lengthMenu: [3, 9, 11, 25, 100],
    order: [[2, 'desc']], // Ordenar por fecha descendente
    columns: [
        {
            title: 'No.',
            data: null,
            width: '2%',
            render: (data, type, row, meta) => meta.row + 1
        },
        {
            title: 'Alumno',
            data: 'alumno_nombre'
        },
        {
            title: 'Fecha',
            data: 'cum_fecha',
            render: data => new Date(data).toLocaleDateString('es-GT')
        },
        {
            title: 'Estado',
            data: 'cum_estado',
            render: data => {
                const estados = {
                    'P': 'Pendiente',
                    'C': 'Cumplió',
                    'T': 'Traslado',
                    'F': 'Faltó'
                };
                return estados[data] || data;
            }
        },
        {
            title: 'Horas Cumplidas',
            data: 'cum_horas_cumplidas'
        },
        {
            title: 'Horas Pendientes',
            data: 'cum_horas_pendientes'
        },
        {
            title: 'Instructor Supervisa',
            data: null,
            render: row => `${row.grado_arma_supervisa} - ${row.instructor_supervisa_nombre}`
        },
        {
            title: 'Acciones',
            data: null,
            orderable: false,
            searchable: false,
            width: '15%',
            render: (data, type, row) => `
                <div class="btn-group btn-group-sm" role="group">
                    <button class='btn btn-warning btn-sm modificar' data-id="${row.cum_id}">
                        <i class='bi bi-pencil-square'></i>
                    </button>
                    <button class='btn btn-danger btn-sm eliminar' data-id="${row.cum_id}">
                        <i class='bi bi-trash'></i>
                    </button>
                </div>
            `
        }
    ]
});

// Función para actualizar horas pendientes
const actualizarHorasPendientes = () => {
    const selectedOption = selectSancion.options[selectSancion.selectedIndex];
    const horasArresto = selectedOption.dataset.horas ? parseInt(selectedOption.dataset.horas) : 0;
    const horasCumplidas = parseInt(inputHorasCumplidas.value) || 0;
    
    inputHorasPendientes.value = Math.max(0, horasArresto - horasCumplidas);
};

// Función para guardar
const guardar = async (e) => {
    e.preventDefault();

    try {
        const formData = new FormData(formulario);
        const response = await fetch("/car_escuela/API/cumplimiento/guardar", {
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
            title: 'Error al guardar el registro'
        });
    }
};

const buscar = async () => {
    try {
        const response = await fetch("/car_escuela/API/cumplimiento/buscar");
        const data = await response.json();

        datatable.clear();
        if (data.datos && Array.isArray(data.datos)) {
            datatable.rows.add(data.datos).draw();
        }
    } catch (error) {
        console.error('Error:', error);
        Toast.fire({
            icon: 'error',
            title: 'Error al cargar los datos'
        });
    }
};

const traerDatos = async (id) => {
    try {
        const response = await fetch(`/car_escuela/API/cumplimiento/buscar?id=${id}`);
        const data = await response.json();

        if (data.codigo === 1 && data.datos) {
            const registro = data.datos;

            // Llenar formulario
            formulario.cum_id.value = registro.cum_id;
            formulario.cum_sancion_id.value = registro.cum_sancion_id;
            formulario.cum_fecha.value = registro.cum_fecha;
            formulario.cum_estado.value = registro.cum_estado;
            formulario.cum_horas_cumplidas.value = registro.cum_horas_cumplidas;
            formulario.cum_horas_pendientes.value = registro.cum_horas_pendientes;
            formulario.cum_fin_semana_inicio.value = registro.cum_fin_semana_inicio || '';
            formulario.cum_fin_semana_siguiente.value = registro.cum_fin_semana_siguiente || '';
            formulario.cum_instructor_supervisa.value = registro.cum_instructor_supervisa;
            formulario.cum_observaciones.value = registro.cum_observaciones || '';

            // Cambiar visibilidad de elementos
            document.querySelector('#tablaCumplimiento').closest('.row').style.display = 'none';
            btnGuardar.parentElement.style.display = 'none';
            btnGuardar.disabled = true;
            btnModificar.parentElement.style.display = '';
            btnModificar.disabled = false;
            btnCancelar.parentElement.style.display = '';
            btnCancelar.disabled = false;
        }
    } catch (error) {
        console.error('Error:', error);
        Toast.fire({
            icon: 'error',
            title: 'Error al cargar el registro'
        });
    }
};

const cancelar = () => {
    formulario.reset();
    btnGuardar.parentElement.style.display = '';
    btnGuardar.disabled = false;
    btnModificar.parentElement.style.display = 'none';
    btnModificar.disabled = true;
    btnCancelar.parentElement.style.display = 'none';
    btnCancelar.disabled = true;
    document.querySelector('#tablaCumplimiento').closest('.row').style.display = '';
};

const modificar = async () => {
    try {
        const formData = new FormData(formulario);
        const response = await fetch("/car_escuela/API/cumplimiento/modificar", {
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
        }
    } catch (error) {
        console.error('Error:', error);
        Toast.fire({
            icon: 'error',
            title: 'Error al modificar el registro'
        });
    }
};

const eliminar = async (id) => {
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
        try {
            const formData = new FormData();
            formData.append('cum_id', id);

            const response = await fetch("/car_escuela/API/cumplimiento/eliminar", {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            Toast.fire({
                icon: data.codigo === 1 ? 'success' : 'error',
                title: data.mensaje
            });

            if (data.codigo === 1) {
                await buscar();
            }
        } catch (error) {
            console.error('Error:', error);
            Toast.fire({
                icon: 'error',
                title: 'Error al eliminar el registro'
            });
        }
    }
};

// Event Listeners
formulario.addEventListener('submit', guardar);
btnModificar.addEventListener('click', modificar);
btnCancelar.addEventListener('click', cancelar);
selectSancion.addEventListener('change', actualizarHorasPendientes);
inputHorasCumplidas.addEventListener('input', actualizarHorasPendientes);

document.querySelector('#tablaCumplimiento').addEventListener('click', (e) => {
    if (e.target.closest('.modificar')) {
        const id = e.target.closest('.modificar').dataset.id;
        traerDatos(id);
    } else if (e.target.closest('.eliminar')) {
        const id = e.target.closest('.eliminar').dataset.id;
        eliminar(id);
    }
});

// Iniciar búsqueda al cargar
buscar();