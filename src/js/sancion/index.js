import { Dropdown } from "bootstrap";
import { Toast } from "../funciones";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// Elementos del DOM
const formulario = document.getElementById('formSancion');
const btnGuardar = document.getElementById('btnGuardar');
const btnModificar = document.getElementById('btnModificar');
const btnCancelar = document.getElementById('btnCancelar');
const selectFalta = document.getElementById('san_falta_id');
const fechaSancion = document.getElementById('san_fecha_sancion');

// Configurar fecha máxima como la actual
fechaSancion.max = new Date().toISOString().split('T')[0];

// Inicialización del DataTable
const datatable = new DataTable('#tablaSancion', {
    data: null,
    language: lenguaje,
    pageLength: 15,
    lengthMenu: [3, 9, 11, 25, 100],
    order: [], // Ordenar por fecha de sanción descendente
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
            data: 'gra_nombre'
        },
        {
            title: 'Rango',
            data: 'ran_nombre'
        },
        {
            title: 'Alumno',
            data: 'alumno_nombre'
        },
        {
            title: 'Fecha Sanción',
            data: 'san_fecha_sancion',
            render: data => new Date(data).toLocaleDateString('es-GT')
        },
        {
            title: 'Falta',
            data: null,
            render: row => `${row.tipo_falta} - ${row.fal_descripcion}`
        },
        {
            title: 'Horas Arresto',
            data: 'san_horas_arresto',
            render: data => data || '-'
        },
        {
            title: 'Deméritos',
            data: 'san_demeritos',
            render: data => data || '-'
        },
        { 
            title: 'Ordeno', 
            data: null,
            render: row => `${row.grado_arma} - ${row.nombres_apellidos}`
        },
        {
            title: 'Acciones',
            data: null,
            orderable: false,
            searchable: false,
            width: '15%',
            render: (data, type, row) => `
            <div class="btn-group btn-group-sm" role="group">
                <button class='btn btn-warning btn-sm modificar' 
                    data-id="${row.san_id}"
                    data-san_catalogo="${row.san_catalogo}"
                    data-san_fecha_sancion="${row.san_fecha_sancion}"
                    data-san_falta_id="${row.san_falta_id}"
                    data-san_instructor_ordena="${row.san_instructor_ordena}"
                    data-san_horas_arresto="${row.san_horas_arresto || ''}"
                    data-san_demeritos="${row.san_demeritos || ''}"
                    data-san_observaciones="${row.san_observaciones || ''}">
                    <i class='bi bi-pencil-square' title='MODIFICAR'></i>
                </button>
                <button class='btn btn-danger btn-sm eliminar' data-id="${row.san_id}">
                    <i class='bi bi-trash' title='ELIMINAR'></i>
                </button>
            </div>
        `
        }
    ]
});

// Función para actualizar campos de sanción según la falta seleccionada
const actualizarSancion = () => {
    const selectedOption = selectFalta.options[selectFalta.selectedIndex];

    if (!selectFalta.value) {
        formulario.san_horas_arresto.value = '';
        formulario.san_demeritos.value = '';
        return;
    }

    const horas = selectedOption.dataset.horas;
    const demeritos = selectedOption.dataset.demeritos;

    formulario.san_horas_arresto.value = horas || '';
    formulario.san_demeritos.value = demeritos || '';

    console.log('Valores actualizados:', { horas, demeritos });
};

// Función para guardar sanción
const guardar = async (e) => {
    e.preventDefault();

    try {
        const formData = new FormData(formulario);
        const response = await fetch("/car_escuela/API/sancion/guardar", {
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
            title: 'Error al guardar la sanción'
        });
    }
};

const buscar = async () => {
    try {
        const url = "/car_escuela/API/sancion/buscar";
        const respuesta = await fetch(url);
        const data = await respuesta.json();

        console.log('Respuesta completa:', data);
        console.log('Datos recibidos:', data.datos);

        datatable.clear();
        if (data.datos && Array.isArray(data.datos)) {
            console.log('Número de registros:', data.datos.length);
            datatable.rows.add(data.datos).draw();
        } else {
            console.log('No hay datos o no es un array');
        }
    } catch (error) {
        console.error('Error completo:', error);
        Toast.fire({
            icon: 'error',
            title: 'Error al cargar los datos'
        });
    }
};

const traerDatos = (e) => {
    const botonModificar = e.target.closest('.modificar');
    if (!botonModificar) return;

    const dataset = botonModificar.dataset;
    console.log('Datos del dataset:', dataset); // Para debug

    // Asignar valores a los campos del formulario
    formulario.san_id.value = dataset.id;
    formulario.san_catalogo.value = dataset.san_catalogo;
    formulario.san_fecha_sancion.value = dataset.san_fecha_sancion;
    formulario.san_falta_id.value = dataset.san_falta_id;
    formulario.san_instructor_ordena.value = dataset.san_instructor_ordena;
    formulario.san_horas_arresto.value = dataset.san_horas_arresto || '';
    formulario.san_demeritos.value = dataset.san_demeritos || '';
    formulario.san_observaciones.value = dataset.san_observaciones || '';

    // Cambiar visibilidad de elementos
    document.querySelector('#tablaSancion').closest('.row').style.display = 'none';
    btnGuardar.parentElement.style.display = 'none';
    btnGuardar.disabled = true;
    btnModificar.parentElement.style.display = '';
    btnModificar.disabled = false;
    btnCancelar.parentElement.style.display = '';
    btnCancelar.disabled = false;
};

// Función para cancelar modificación
const cancelar = () => {
    // Resetear el formulario
    formulario.reset();
    
    // Restaurar visibilidad de botones
    btnGuardar.parentElement.style.display = '';
    btnGuardar.disabled = false;
    btnModificar.parentElement.style.display = 'none';
    btnModificar.disabled = true;
    btnCancelar.parentElement.style.display = 'none';
    btnCancelar.disabled = true;
    
    // Restaurar visibilidad de la tabla
    document.querySelector('#tablaSancion').closest('.row').style.display = '';
    
    // Limpiar cualquier mensaje de error o estado previo si existe
    if (document.querySelector('.alert')) {
        document.querySelector('.alert').remove();
    }
};

// Función para modificar sanción
const modificar = async (e) => {
    e.preventDefault();

    try {
        const formData = new FormData(formulario);
        const response = await fetch("/car_escuela/API/sancion/modificar", {
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
            title: 'Error al modificar la sanción'
        });
    }
};

// Función para eliminar sanción
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
            formData.append('san_id', id);

            const response = await fetch("/car_escuela/API/sancion/eliminar", {
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
                title: 'Error al eliminar la sanción'
            });
        }
    }
};

// Event Listeners
formulario.addEventListener('submit', guardar);
btnModificar.addEventListener('click', modificar);
btnCancelar.addEventListener('click', cancelar);
selectFalta.addEventListener('change', actualizarSancion);

document.querySelector('#tablaSancion').addEventListener('click', (e) => {
    if (e.target.closest('.modificar')) {
        traerDatos(e); // Pasar el evento e a la función
    } else if (e.target.closest('.eliminar')) {
        const id = e.target.closest('.eliminar').dataset.id;
        eliminar(id);
    }
});
// Iniciar búsqueda al cargar
buscar();