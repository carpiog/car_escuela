import { Dropdown } from "bootstrap";
import Chart from "chart.js/auto";

document.addEventListener('DOMContentLoaded', function () {
    const canvasTipos = document.getElementById('chartTipos');
    const ctxTipos = canvasTipos.getContext('2d');
    const canvasGrados = document.getElementById('chartGrados');
    const ctxGrados = canvasGrados.getContext('2d');
    const canvasFaltas = document.getElementById('chartFaltas');
    const ctxFaltas = canvasFaltas.getContext('2d');

    const btnActualizar = document.getElementById('actualizar');

    // Paleta de colores personalizada
    const COLOR_PALETTE = [
        'rgba(54, 162, 235, 0.7)',   // Azul
        'rgba(255, 99, 132, 0.7)',   // Rojo
        'rgba(75, 192, 192, 0.7)',   // Verde azulado
        'rgba(255, 206, 86, 0.7)',   // Amarillo
        'rgba(153, 102, 255, 0.7)',  // Púrpura
        'rgba(255, 159, 64, 0.7)'    // Naranja
    ];

    // Inicializamos los gráficos con estilos mejorados
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        }
    };

    const chartTipos = new Chart(ctxTipos, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Total de Sanciones',
                data: [],
                backgroundColor: COLOR_PALETTE
            }]
        },
        options: chartOptions
    });

    const chartGrados = new Chart(ctxGrados, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Total de Sanciones',
                data: [],
                backgroundColor: COLOR_PALETTE
            }]
        },
        options: chartOptions
    });

    const chartFaltas = new Chart(ctxFaltas, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Total de Faltas',
                data: [],
                backgroundColor: COLOR_PALETTE
            }]
        },
        options: chartOptions
    });

    const getEstadisticas = async () => {
        try {
            // Mostrar spinner o deshabilitar botón
            btnActualizar.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cargando...';
            btnActualizar.disabled = true;

            // Peticiones de datos
            const endpoints = [
                { url: '/car_escuela/API/estadisticas/tipos', name: 'tipos' },
                { url: '/car_escuela/API/estadisticas/grados', name: 'grados' },
                { url: '/car_escuela/API/estadisticas/faltas', name: 'faltas' }
            ];

            const responses = await Promise.all(
                endpoints.map(endpoint => fetch(endpoint.url))
            );

            const datasetsData = await Promise.all(
                responses.map(async (response, index) => {
                    if (!response.ok) {
                        throw new Error(`Error en ${endpoints[index].name}: ${response.statusText}`);
                    }
                    const data = await response.json();
                    console.log(`Datos recibidos para ${endpoints[index].name}:`, data); // Ver datos en consola
                    return data;
                })
            );

            // Actualizar gráficos con validación
            if (Array.isArray(datasetsData[0])) {
                chartTipos.data.labels = datasetsData[0].map(r => r.tipo_falta || 'Desconocido');
                chartTipos.data.datasets[0].data = datasetsData[0].map(r => r.total_sanciones || 0);
            }

            if (Array.isArray(datasetsData[1])) {
                chartGrados.data.labels = datasetsData[1].map(r => r.grado || 'Desconocido');
                chartGrados.data.datasets[0].data = datasetsData[1].map(r => r.total_sanciones || 0);
            }

            if (Array.isArray(datasetsData[2])) {
                chartFaltas.data.labels = datasetsData[2].map(r => r.descripcion || 'Sin Descripción');
                chartFaltas.data.datasets[0].data = datasetsData[2].map(r => r.total || 0);
            }

            // Actualizar gráficos
            [chartTipos, chartGrados, chartFaltas].forEach(chart => chart.update());

            // Restaurar botón
            btnActualizar.innerHTML = 'Actualizar';
            btnActualizar.disabled = false;

        } catch (error) {
            console.error('Error en la carga de datos:', error);
            btnActualizar.innerHTML = 'Reintentar';
            btnActualizar.disabled = false;
            alert('No se pudieron cargar las estadísticas. Intente de nuevo.');
        }
    };

    // Llamamos a la función al hacer clic en el botón
    btnActualizar.addEventListener('click', getEstadisticas);

    // Llamamos a la función cuando se carga la página
    getEstadisticas();
});
