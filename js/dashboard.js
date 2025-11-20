fetch('dashboard_data.php')
    .then(r => r.json())
    .then(data => {
        const ctx = document.getElementById('overviewChart').getContent('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: { 
                labels: ['Livros', 'Clientes'],
                datasets:[{
                    label: 'Contagem',
                    data: [data.Livros, data.Clientes]
                }]
            },
            options: {
                responsinve: true,
                plugins: { legend: { position: bottom } }
            }
        });
    })
    .catch(err => console.error(err));

const toggleBtn = document.querySelector(".sidebar-toggle");
const sidebar = document.querySelector(".sidebar");

toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("active");
});