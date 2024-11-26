import { Dropdown } from "bootstrap";
import Chart from "chart.js/auto";

document.addEventListener('DOMContentLoaded', function() {
    const canvasTipos = document.getElementById('chartTipos');
    const ctxTipos = canvasTipos.getContext('2d');
    const canvasGrados = document.getElementById('chartGrados');
    const ctxGrados = canvasGrados.getContext('2d');
    const canvasTendencias = document.getElementById('chartTendencias');
    const ctxTendencias = canvasTendencias.getContext('2d');
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

    const chartTendencias = new Chart(ctxTendencias, { 
        type: 'line', 
        data: { 
            labels: [], 
            datasets: [{ 
                label: 'Total de Sanciones', 
                data: [], 
                borderColor: 'rgba(75,192,192,1)', 
                backgroundColor: 'rgba(75,192,192,0.2)', 
                borderWidth: 2,
                fill: true
            }] 
        },
        options: {
            ...chartOptions,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
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
            // Mostrar un loader o indicador de carga
            btnActualizar.innerHTML = 'Cargando...';
            btnActualizar.disabled = true;

            // Peticiones de datos
            const endpoints = [
                { url: '/car_escuela/API/estadisticas/tipos', name: 'tipos' },
                { url: '/car_escuela/API/estadisticas/grados', name: 'grados' },
                { url: '/car_escuela/API/estadisticas/tendencias', name: 'tendencias' },
                { url: '/car_escuela/API/estadisticas/faltas', name: 'faltas' }
            ];

            const responses = await Promise.all(
                endpoints.map(endpoint => fetch(endpoint.url))
            );

            // Verificar respuestas
            responses.forEach((response, index) => {
                if (!response.ok) {
                    throw new Error(`Error al obtener ${endpoints[index].name}`);
                }
            });

            // Parsear datos
            const datasetsData = await Promise.all(
                responses.map(response => response.json())
            );

            // Limpiar gráficos
            [chartTipos, chartGrados, chartTendencias, chartFaltas].forEach(chart => {
                chart.data.labels = [];
                chart.data.datasets[0].data = [];
            });

            // Actualizar gráficos
            chartTipos.data.labels = datasetsData[0].map(r => r.tipo);
            chartTipos.data.datasets[0].data = datasetsData[0].map(r => r.total_sanciones);

            chartGrados.data.labels = datasetsData[1].map(r => r.grado);
            chartGrados.data.datasets[0].data = datasetsData[1].map(r => r.total_sanciones);

            chartTendencias.data.labels = datasetsData[2].map(r => r.nombre_mes);
            chartTendencias.data.datasets[0].data = datasetsData[2].map(r => r.total_sanciones);

            chartFaltas.data.labels = datasetsData[3].map(r => r.descripcion);
            chartFaltas.data.datasets[0].data = datasetsData[3].map(r => r.total);

            // Actualizar gráficos
            [chartTipos, chartGrados, chartTendencias, chartFaltas].forEach(chart => chart.update());

            // Restaurar botón
            btnActualizar.innerHTML = 'Actualizar';
            btnActualizar.disabled = false;

        } catch (error) {
            console.error('Error en la carga de datos: ', error);
            // Manejar error en UI
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
