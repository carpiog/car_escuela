import { Dropdown } from "bootstrap";
import { Toast } from "../funciones";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const datatable = new DataTable('#tablaConductas', {
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
       { data: 'alu_catalogo' },
       { data: 'gra_nombre' },
       { data: 'ran_nombre' },
       { data: 'alumno_nombre' },
       { 
           data: 'total_sanciones',
           render: data => data || '0'
       },
       { 
           data: 'total_demeritos',
           render: data => data || '0'
       }
   ]
});

async function buscar() {
   try {
       const respuesta = await fetch("/car_escuela/API/conducta/buscar");
       const data = await respuesta.json();

       datatable.clear();
       if (data.codigo === 1 && Array.isArray(data.datos)) {
           datatable.rows.add(data.datos).draw();
       }
   } catch (error) {
       Toast.fire({
           icon: 'error',
           title: 'Error al cargar los datos'
       });
   }
}

// Agregar el evento para el botón de generar PDF
document.getElementById('btnImprimir').addEventListener('click', () => {
    window.open('/car_escuela/conducta/pdf', '_blank');
});

// Cargar datos al iniciar
window.addEventListener('load', buscar);