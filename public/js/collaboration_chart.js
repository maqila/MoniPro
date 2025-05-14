// public/js/collaboration_chart.js

document.addEventListener("DOMContentLoaded", function () {
    const collaborationChartCtx = document.getElementById("collaborationChart");

    if (collaborationChartCtx) {
        const months = JSON.parse(collaborationChartCtx.dataset.months);
        const counts = JSON.parse(collaborationChartCtx.dataset.counts);

        // Initialize the chart
        new Chart(collaborationChartCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Collaborations',
                    data: counts,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Monthly Collaborations'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Collaborations'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month, Year'
                        }
                    }
                }
            }
        });
    }
});
