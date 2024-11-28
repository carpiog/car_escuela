import { Dropdown } from "bootstrap";
import { Toast } from "../funciones";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

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
            title: 'Sexo',             
            data: 'alu_sexo'         
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
                    <button class='btn btn-info btn-sm ver-sanciones' 
                        data-alu_id="${row.alu_id}"
                        title='VER SANCIONES'>
                        <i class='bi bi-file-earmark-pdf'></i>
                    </button>                 
                `;             
            }         
        }     
    ] 
});

const buscar = async (gradoId = '') => {
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
        console.log('Datos recibidos:', datos); // Para debuggear

        datatable.clear();
        
        if (codigo === 1 && Array.isArray(datos)) {
            datatable.rows.add(datos).draw();
        } else {
            console.error('No se recibieron datos válidos');
        }

    } catch (error) {
        console.error('Error al buscar alumnos:', error);
        Toast.fire({
            icon: 'error',
            title: 'Error al cargar los datos'
        });
    }
};

// Event listener para el filtro
document.getElementById('filtroGrado').addEventListener('change', function(e) {
    const gradoId = this.value;
    console.log('Filtrando por grado:', gradoId); // Para debugging
    buscar(gradoId);
});
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

    const gradoId = document.querySelector('#filtroGrado').value;
    const url = `/car_escuela/reporte/sancionesPDF?id=${id}${gradoId ? `&grado=${gradoId}` : ''}`;
    window.open(url, '_blank');
};

// Event listeners
document.querySelector('#tablaAlumno').addEventListener('click', (e) => {
    if (e.target.closest('.ver-sanciones')) {
        verSanciones(e);
    }
});

// Cargar datos iniciales
buscar();