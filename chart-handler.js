// chart-handler.js – renders pie/bar charts for MCQ/Exam analytics in admin

document.addEventListener('DOMContentLoaded', function () {
  const canvas = document.getElementById('pakstudy-chart');
  if (!canvas) return;

  const ctx = canvas.getContext('2d');
  const chartType = canvas.dataset.type || 'bar'; // Accepts: bar, pie, line, etc.
  const chartLabel = canvas.dataset.label || 'Exam Analytics';
  const chartData = JSON.parse(canvas.dataset.chartData || '{}');

  new Chart(ctx, {
    type: chartType,
    data: {
      labels: chartData.labels,
      datasets: [{
        label: chartLabel,
        data: chartData.values,
        backgroundColor: [
          'rgba(54, 162, 235, 0.6)',
          'rgba(255, 99, 132, 0.6)',
          'rgba(255, 206, 86, 0.6)',
          'rgba(75, 192, 192, 0.6)',
          'rgba(153, 102, 255, 0.6)'
        ],
        borderColor: 'rgba(255, 255, 255, 0.8)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        title: {
          display: true,
          text: chartLabel
        },
        legend: {
          display: true,
          position: 'bottom'
        },
        tooltip: {
          enabled: true
        }
      },
      scales: chartType === 'bar' || chartType === 'line' ? {
        y: {
          beginAtZero: true
        }
      } : {}
    }
  });
});
