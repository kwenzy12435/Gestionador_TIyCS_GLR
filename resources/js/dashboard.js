import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
  const ticketsCanvas = document.getElementById('ticketsByDayChart');
  const devicesCanvas = document.getElementById('devicesStatusChart');

  // Si no estamos en el dashboard, no hacemos nada
  if (!ticketsCanvas && !devicesCanvas) return;

  // Gráfica de líneas: tickets por día (ejemplo)
  if (ticketsCanvas) {
    new Chart(ticketsCanvas, {
      type: 'line',
      data: {
        labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
        datasets: [{
          label: 'Tickets',
          data: [3, 4, 2, 6, 5, 1, 2],
          tension: 0.3,
          borderWidth: 2,
          pointRadius: 3
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          x: {
            ticks: { color: '#aaa' },
            grid: { color: 'rgba(255,255,255,0.05)' }
          },
          y: {
            ticks: { color: '#aaa', stepSize: 1, beginAtZero: true },
            grid: { color: 'rgba(255,255,255,0.05)' }
          }
        }
      }
    });
  }

  // Gráfica de doughnut: estado de dispositivos (ejemplo)
  if (devicesCanvas) {
    new Chart(devicesCanvas, {
      type: 'doughnut',
      data: {
        labels: ['Activos', 'En reparación', 'Dados de baja'],
        datasets: [{
          data: [28, 5, 9],
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              color: '#ddd',
              boxWidth: 14
            }
          }
        },
        cutout: '60%'
      }
    });
  }
});