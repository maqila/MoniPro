// public/js/customer_type_chart.js

document.addEventListener("DOMContentLoaded", function () {
    const customerTypeChartCtx = document.getElementById("customerTypeChart");

    if (customerTypeChartCtx) {
        const types = JSON.parse(customerTypeChartCtx.dataset.types);
        const counts = JSON.parse(customerTypeChartCtx.dataset.counts);

        // Initialize the pie chart
        new Chart(customerTypeChartCtx, {
            type: 'pie',
            data: {
                labels: types,
                datasets: [{
                    label: 'Customer Types',
                    data: counts,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
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
                        text: 'Distribution of Customer Types'
                    }
                }
            }
        });
    }
});
