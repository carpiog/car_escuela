import { Toast } from "../funciones";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { Modal } from 'bootstrap';

// Inicializar modal
const modalHoras = new Modal('#modalHoras');
const datatable = new DataTable('#tablaArresto', {
    language: lenguaje,
    pageLength: 15,
    order: [],
    columns: [
        { data: 'alu_catalogo' },
        { data: 'gra_nombre' },
        { data: 'ran_nombre' },
        { data: 'alumno_nombre' },
        { 
            data: 'total_horas_arresto',
            render: data => Math.round(data)
        },
        { 
            data: 'horas_cumplidas', 
            render: data => Math.round(data)
        },
        {
            data: 'horas_pendientes',
            render: data => Math.round(data)
        },
        {
            data: 'estado_sancion',
            render: (data) => data === 'P' ? 'PENDIENTE' : data
        },
       {
            data: null,
            render: row => `
               <button class="btn btn-success btn-sm registrar-horas" 
                       data-id="${row.alu_id}"
                       data-horas="${row.total_horas_arresto}"
                       ${row.estado_sancion === 'C' ? 'disabled' : ''}>
                   <i class="bi bi-clock-fill"></i> Registrar Horas
               </button>`
        }
    ]
});

async function cargarDatos() {
    try {
        const response = await fetch('/car_escuela/API/arresto/listar');
        const data = await response.json();

        if (data.codigo === 1) {
            datatable.clear().rows.add(data.datos).draw();
        } else {
            Toast.fire({ icon: 'error', title: data.mensaje });
        }
    } catch (error) {
        console.error(error);
        Toast.fire({ icon: 'error', title: 'Error al cargar datos' });
    }
}

async function guardarHoras() {
    const horasCumplidas = document.getElementById('horas_cumplidas').value;
    const alumnoId = document.getElementById('alumno_id').value;
    const totalHoras = document.getElementById('total_horas').value;

    if (!horasCumplidas || horasCumplidas <= 0) {
        Toast.fire({ icon: 'error', title: 'Ingrese un número válido de horas' });
        return;
    }

    if (parseFloat(horasCumplidas) > parseFloat(totalHoras)) {
        Toast.fire({ icon: 'error', title: 'Las horas cumplidas no pueden ser mayores al total' });
        return;
    }

    try {
        const formData = new FormData();
        formData.append('alu_id', alumnoId);
        formData.append('horas_cumplidas', horasCumplidas);

        const response = await fetch('/car_escuela/API/arresto/actualizar', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        Toast.fire({
            icon: data.codigo === 1 ? 'success' : 'error',
            title: data.mensaje
        });

        if (data.codigo === 1) {
            modalHoras.hide();
            await cargarDatos();
        }
    } catch (error) {
        console.error(error);
        Toast.fire({ icon: 'error', title: 'Error al actualizar' });
    }
}

// Al abrir el modal
document.querySelector('#tablaArresto').addEventListener('click', e => {
    const btn = e.target.closest('.registrar-horas');
    if (btn) {
        const row = datatable.row(btn.closest('tr')).data();
        document.getElementById('alumno_id').value = btn.dataset.id;
        document.getElementById('total_horas').value = Math.round(row.horas_pendientes);
        document.getElementById('horas_cumplidas').value = '';
        document.getElementById('horas_cumplidas').max = Math.round(row.horas_pendientes);
        modalHoras.show();
    }
 });

document.getElementById('btnGuardarHoras').addEventListener('click', guardarHoras);

// Agregar el evento para el botón de generar nómina
document.getElementById('btnGenerarNomina').addEventListener('click', () => {
    window.open('/car_escuela/arresto/nomina-pdf', '_blank');
});

// Cargar datos al iniciar
cargarDatos();