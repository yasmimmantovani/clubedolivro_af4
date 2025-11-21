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

// Gráficos
document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById("overviewChart").getContext("2d");

    new Chart(ctx, {
        type: "bar",
        data: {
            labels: generos.length > 0 ? generos : ["Sem dados"],
            datasets: [{
                label: "Empréstimos por gênero",
                data: qtdGenero.length > 0 ? qtdGenero : [0]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: `Total de empréstimos: ${qtdEmprestimos}`
                }
            }
        }
    });
});

// Sidebar
const toggleBtn = document.querySelector(".sidebar-toggle");
const sidebar = document.querySelector(".sidebar");

toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("active");
});

//Modal
function confirmarExclusao(id) {
    const modal = document.getElementById("confirmModal");
    const btnConfirm = document.getElementById("confirmDelete");

    // Atualiza o link do botão do modal
    btnConfirm.href = `${window.location.pathname}?del=${id}`;

    // Mostra modal
    modal.style.display = "flex";

    // Bloqueia o link original
    return false;
}

// Botão cancelar
document.getElementById("cancelBtn").addEventListener("click", () => {
    document.getElementById("confirmModal").style.display = "none";
});

// Fechar clicando fora
document.getElementById("confirmModal").addEventListener("click", (e) => {
    if (e.target.id === "confirmModal") {
        document.getElementById("confirmModal").style.display = "none";
    }
});