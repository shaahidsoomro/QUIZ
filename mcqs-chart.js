# Create a JS file that will be used with Chart.js for rendering admin analytics charts

chartjs_script_code = """
/**
 * Chart.js script for MCQS Analytics
 * Author: Shahid Hussain Soomro
 */

document.addEventListener('DOMContentLoaded', function () {
  const ctx = document.getElementById('mcqAnalyticsChart');
  if (ctx) {
    const labels = JSON.parse(ctx.dataset.labels);
    const data = JSON.parse(ctx.dataset.values);

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'MCQ Attempt Frequency',
          data: data,
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            ticks: { stepSize: 1 }
          }
        }
      }
    });
  }
});
"""

# Save this JS file
with open("/mnt/data/assets/js/mcqs-chart.js", "w", encoding="utf-8") as f:
    f.write(chartjs_script_code)

"✅ Chart.js script file 'assets/js/mcqs-chart.js' created for rendering visual analytics."
