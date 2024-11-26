import { Dropdown } from "bootstrap";
import { Toast } from "../funciones";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// Obtener tipo de falta de la URL si existe
const params = new URLSearchParams(window.location.search);
const tipoFalta = params.get('tipo');

// Inicializar DataTable
const datatable = new DataTable('#tablaFalta', {
    language: lenguaje,
    pageLength: 25,
    columns: [
        {
            title: 'No.',
            data: 'fal_id',  // Usar fal_id en lugar del índice de fila
            render: (data) => data // Mostrar directamente el ID
        },
        {
            title: 'Tipo',
            data: 'tipo_nombre',
            render: (data) => {
                const clases = {
                    'LEVE': 'badge bg-info',
                    'GRAVE': 'badge bg-warning',
                    'GRAVISIMA': 'badge bg-danger'
                };
                return data ? `<span class="${clases[data]}">${data}</span>` : '';
            }
        },
        {
            title: 'Categoría',
            data: 'categoria_nombre'
        },
        {
            title: 'Descripción',
            data: 'fal_descripcion'
        },
        {
            title: 'Sanción',
            render: (data, type, row) => {
                if (row.fal_horas_arresto) {
                    return `${row.fal_horas_arresto} horas de arresto`;
                } else if (row.fal_demeritos) {
                    return `${row.fal_demeritos} deméritos`;
                } else {
                    return 'BAJA';
                }
            }
        }
    ],
    order: [[0, 'asc']] // Ordenar por la primera columna (ID) de forma ascendente
});
// Función para buscar
const buscar = async () => {
    try {
        const url = "/car_escuela/API/falta/buscar" + (tipoFalta ? `?tipo=${tipoFalta}` : '');
        const config = {
            method: 'GET',
            headers: {
                'X-Requested-With': 'fetch'
            }
        };

        const respuesta = await fetch(url, config);
        
        if (!respuesta.ok) {
            throw new Error(`Error HTTP: ${respuesta.status}`);
        }
        
        const data = await respuesta.json();
        
        if (data.codigo === 1 && Array.isArray(data.datos)) {
            datatable.clear().rows.add(data.datos).draw();
        } else {
            Toast.fire({
                icon: 'error',
                title: data.mensaje || 'No se encontraron datos'
            });
        }

    } catch (error) {
        console.error('Error en buscar:', error);
        Toast.fire({
            icon: 'error',
            title: 'Error al cargar los datos'
        });
    }
};

// Cargar datos iniciales
buscar();