/**
 * CF Page or Post Duplicator - Dashboard Chart
 * Gráfico de clones por dia usando Chart.js
 */

document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById("cloneChart");
    
    // Verifica se o Chart.js está carregado e se o canvas existe
    if (typeof Chart !== "undefined" && ctx) {
        
        // Configuração do gradiente
        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, 'rgba(0, 115, 170, 0.2)');
        gradient.addColorStop(1, 'rgba(0, 115, 170, 0.02)');
        
        // Criar o gráfico
        new Chart(ctx, {
            type: "line",
            data: {
                labels: cfCloneData.labels,
                datasets: [{
                    label: "Clones per day",
                    data: cfCloneData.data,
                    fill: true,
                    tension: 0.4,
                    borderColor: '#0073aa',
                    backgroundColor: gradient,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#0073aa',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: '#00a0d2',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 13,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: '600'
                        },
                        bodyFont: {
                            size: 13
                        },
                        cornerRadius: 6,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y + (context.parsed.y === 1 ? ' clone' : ' clones');
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 12
                            },
                            color: '#666'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#666',
                            maxRotation: 45,
                            minRotation: 0
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                }
            }
        });
    } else {
        // Fallback se o Chart.js não estiver disponível
        if (!ctx) {
            console.warn('CF Clone Dashboard: Canvas element #cloneChart not found');
        }
        if (typeof Chart === "undefined") {
            console.warn('CF Clone Dashboard: Chart.js library not loaded');
        }
    }
});