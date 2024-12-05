import { Dropdown } from "bootstrap";
import { Toast } from "../funciones";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const formulario = document.getElementById('formSancion');
const btnGuardar = document.getElementById('btnGuardar');
const btnModificar = document.getElementById('btnModificar');
const btnCancelar = document.getElementById('btnCancelar');
const selectFalta = document.getElementById('san_falta_id');
const fechaSancion = document.getElementById('san_fecha_sancion');
const startDate = document.getElementById('startDate');
const endDate = document.getElementById('endDate');
const btnAplicarFiltro = document.getElementById('btnAplicarFiltro');
const selectGrados = document.getElementById('filtroGrado');
const selectAlumnos = document.getElementById('san_catalogo');

fechaSancion.max = new Date().toISOString().split('T')[0];

const datatable = new DataTable('#tablaSancion', {
    data: null,
    language: lenguaje,
    pageLength: 15,
    lengthMenu: [3, 9, 11, 25, 100],
    order: [],
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
                        data-san_falta_id="${row.categoria_falta}"
                        data-san_instructor_ordena="${row.gra_orden}"
                        data-san_horas_arresto="${row.san_horas_arresto || ''}"
                        data-san_demeritos="${row.san_demeritos || ''}"
                        data-san_observaciones="${row.san_observaciones || ''}">
                        <i class='bi bi-pencil-square' title='MODIFICAR'></i>
                    </button>
                    <button class='btn btn-danger btn-sm eliminar' data-id="${row.san_id}">
                        <i class='bi bi-trash' title='ELIMINAR'></i>
                    </button>
                </div>`
        }
    ]
});

const actualizarSancion = () => {
    const selectedOption = selectFalta.options[selectFalta.selectedIndex];
    formulario.san_horas_arresto.value = selectedOption?.dataset?.horas || '';
    formulario.san_demeritos.value = selectedOption?.dataset?.demeritos || '';
};

const buscar = async () => {
    try {
        let url = "/car_escuela/API/sancion/buscar";
        if (startDate.value && endDate.value) {
            const params = new URLSearchParams({
                startDate: startDate.value,
                endDate: endDate.value
            });
            url += `?${params.toString()}`;
        }

        const respuesta = await fetch(url);
        const data = await respuesta.json();

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

const guardar = async (e) => {
    e.preventDefault();
    try {
        const formData = new FormData(formulario);
        const response = await fetch("/car_escuela/API/sancion/guardar", {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        Toast.fire({
            icon: data.codigo === 1 ? 'success' : 'error',
            title: data.mensaje
        });

        if (data.codigo === 1) {
            formulario.reset();
            buscar();
        }
    } catch (error) {
        console.error('Error:', error);
        Toast.fire({
            icon: 'error',
            title: 'Error al guardar la sanción'
        });
    }
};

const traerDatos = (e) => {
    const botonModificar = e.target.closest('.modificar');
    if (!botonModificar) return;

    const fila = datatable.row(botonModificar.closest('tr')).data();
    
    formulario.san_id.value = fila.san_id;
    formulario.san_catalogo.value = fila.san_catalogo;
    formulario.san_fecha_sancion.value = fila.san_fecha_sancion;
    formulario.san_falta_id.value = fila.san_falta_id;            // Corregido
    formulario.san_instructor_ordena.value = fila.san_instructor_ordena;  // Corregido
    formulario.san_horas_arresto.value = fila.san_horas_arresto;
    formulario.san_demeritos.value = fila.san_demeritos;
    formulario.san_observaciones.value = fila.san_observaciones || '';

    selectFalta.dispatchEvent(new Event('change'));

    // UI updates...
    document.querySelector('#tablaSancion').closest('.row').style.display = 'none';
    btnGuardar.parentElement.style.display = 'none';
    btnGuardar.disabled = true;
    btnModificar.parentElement.style.display = '';
    btnModificar.disabled = false;
    btnCancelar.parentElement.style.display = '';
    btnCancelar.disabled = false;
};

const cancelar = () => {
    formulario.reset();
    btnGuardar.parentElement.style.display = '';
    btnGuardar.disabled = false;
    btnModificar.parentElement.style.display = 'none';
    btnModificar.disabled = true;
    btnCancelar.parentElement.style.display = 'none';
    btnCancelar.disabled = true;
    document.querySelector('#tablaSancion').closest('.row').style.display = '';
    
    if (document.querySelector('.alert')) {
        document.querySelector('.alert').remove();
    }
};

const modificar = async (e) => {
    e.preventDefault();
    
    try {
        const formData = new FormData(formulario);
        console.log("Datos a enviar:", Object.fromEntries(formData));
        
        const response = await fetch("/car_escuela/API/sancion/modificar", {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        console.log("Respuesta del servidor:", data);

        Toast.fire({
            icon: data.codigo === 1 ? 'success' : 'error',
            title: data.mensaje
        });

        if (data.codigo === 1) {
            await buscar();
            cancelar();
        }
    } catch (error) {
        console.error("Error completo:", error);
        Toast.fire({
            icon: 'error',
            title: 'Error al modificar la sanción'
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

const buscarPorGrados = async (gradoId = '') => {
    try {
        const url = `/car_escuela/API/alumno/buscar${gradoId ? `?grado=${gradoId}` : ''}`;
        const respuesta = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'fetch'
            }
        });

        if (!respuesta.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const { codigo, datos } = await respuesta.json();

        if (codigo === 1 && Array.isArray(datos)) {
            selectAlumnos.disabled = false;
            selectAlumnos.innerHTML = '<option value="">Seleccione un alumno</option>';
            
            datos.forEach(alumno => {
                const option = document.createElement('option');
                option.value = alumno.alu_id;
                option.textContent = `${alumno.rango_nombre} - ${alumno.nombres_completos}`;
                selectAlumnos.appendChild(option);
            });
        } else {
            selectAlumnos.disabled = true;
            selectAlumnos.innerHTML = '<option value="">No hay alumnos disponibles</option>';
        }
    } catch (error) {
        console.error('Error al buscar alumnos:', error);
        Toast.fire({
            icon: 'error',
            title: 'Error al cargar los datos'
        });
        selectAlumnos.disabled = true;
        selectAlumnos.innerHTML = '<option value="">Error al cargar alumnos</option>';
    }
};

// Event Listeners
btnAplicarFiltro.addEventListener('click', () => {
    if (!startDate.value || !endDate.value) {
        Toast.fire({
            icon: 'warning',
            title: 'Por favor seleccione ambas fechas'
        });
        return;
    }
    
    if (startDate.value > endDate.value) {
        Toast.fire({
            icon: 'warning',
            title: 'La fecha inicial no puede ser mayor que la fecha final'
        });
        return;
    }
    
    buscar();
});

formulario.addEventListener('submit', (e) => {
    e.preventDefault();
    if (formulario.san_id.value) {
        modificar(e);
    } else {
        guardar(e);
    }
});

btnModificar.addEventListener('click', modificar);
btnCancelar.addEventListener('click', cancelar);
selectFalta.addEventListener('change', actualizarSancion);
selectGrados.addEventListener('change', function() {
    buscarPorGrados(this.value);
});

document.querySelector('#tablaSancion').addEventListener('click', (e) => {
    if (e.target.closest('.modificar')) {
        traerDatos(e);
    } else if (e.target.closest('.eliminar')) {
        const id = e.target.closest('.eliminar').dataset.id;
        eliminar(id);
    }
});

window.addEventListener('load', () => {
    startDate.value = '';
    endDate.value = '';
    buscar();
    buscarPorGrados();
});