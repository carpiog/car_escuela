import { Dropdown } from "bootstrap";
import { Toast } from "../funciones";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

document.addEventListener('DOMContentLoaded', function () {
    let dataTable = new DataTable('#tablaDemerito', {
        language: lenguaje,
        pageLength: 25,
        order: [],
        columns: [
            { data: null, render: (data, type, row, meta) => meta.row + 1 },
            { data: 'alu_catalogo' },
            { data: 'gra_nombre' },
            { data: 'ran_nombre' },
            { data: 'alumno_nombre' },
            { data: 'demeritos_acumulados' },
            {
                data: 'conducta',
                render: function (data, type, row) {
                    if (type === 'display') {
                        let badgeClass = '';
                        switch (data) {
                            case 'EXCELENTE':
                                badgeClass = 'badge bg-success';
                                break;
                            case 'MUY BUENA':
                                badgeClass = 'badge bg-info';
                                break;
                            case 'BUENA':
                                badgeClass = 'badge bg-primary';
                                break;
                            case 'REGULAR':
                                badgeClass = 'badge bg-warning text-dark';
                                break;
                            case 'DEFICIENTE':
                                badgeClass = 'badge bg-danger';
                                break;
                            case 'MALA':
                                badgeClass = 'badge bg-danger';
                                break;
                            default:
                                badgeClass = 'badge bg-secondary';
                        }
                        return `<span class="${badgeClass}">${data}</span>`;
                    }
                    return data;
                }
            }
        ],
        rowCallback: function (row, data) {
            if (data.conducta === 'MALA' || data.conducta === 'DEFICIENTE') {
                $(row).addClass('table-danger');
            }
        }
    });

    const aplicarFiltros = () => {
        const filtroGrado = document.getElementById('filtroGrado').value;
        let filtroConducta = document.getElementById('filtroConducta').value;

        // Filtro de grado (columna 2)
        dataTable.column(2).search(filtroGrado ? filtroGrado : '', true, false);

        // Filtro de conducta (columna 7)
        if (filtroConducta === 'RIESGO') {
            dataTable.column(7).search('DEFICIENTE|MALA', true, false);
        } else if (filtroConducta) {
            filtroConducta = filtroConducta.toUpperCase();
            dataTable.column(7).search(filtroConducta, true, false);
        } else {
            dataTable.column(7).search('');
        }

        dataTable.draw();
    };

    const buscar = async () => {
        try {
            const respuesta = await fetch("/car_escuela/API/demerito/buscar");
            const data = await respuesta.json();

            if (data.codigo === 1 && data.datos) {
                dataTable.clear().rows.add(data.datos).draw();
            }
        } catch (error) {
            console.error('Error:', error);
            Toast.fire({
                icon: 'error',
                title: 'Error al cargar los datos'
            });
        }
    };

    document.getElementById('filtroGrado').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroConducta').addEventListener('change', aplicarFiltros);

    buscar();
});