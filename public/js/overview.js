document.addEventListener('DOMContentLoaded', function () {
    initOverviewModals();
    initCategoryChart();
});

function initOverviewModals() {
    window.openModal = function (id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('active');
        }
    };

    window.closeModal = function (id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('active');
        }
    };

    window.printModal = function (contentId, title) {
        const content = document.getElementById(contentId);
        if (!content) return;

        const printWindow = window.open('', '_blank');
        if (!printWindow) return;

        printWindow.document.write(`
            <html>
                <head>
                    <title>${title}</title>
                    <style>
                        body { font-family: 'DM Sans', sans-serif; padding: 24px; color: #0f172a; }
                        h1 { font-size: 20px; margin-bottom: 16px; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: left; font-size: 13px; }
                        th { text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-size: 11px; }
                    </style>
                </head>
                <body>
                    <h1>${title}</h1>
                    ${content.innerHTML}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };

    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.remove('active');
            }
        });
    });
}

function initCategoryChart() {
    const canvas = document.getElementById('categoryChart');
    if (!canvas || typeof Chart === 'undefined') return;

    const dataEl = document.getElementById('categoryChartData');
    if (!dataEl) return;

    let chartData = null;
    try {
        chartData = JSON.parse(dataEl.textContent || '{}');
    } catch (error) {
        chartData = null;
    }
    if (!chartData || !Array.isArray(chartData.labels)) return;

    const ctx = canvas.getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Number of Products',
                data: chartData.counts || [],
                backgroundColor: 'rgba(30, 58, 95, 0.8)',
                borderColor: 'rgba(30, 58, 95, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
}
