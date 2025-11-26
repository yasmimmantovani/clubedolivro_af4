document.getElementById('telefone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, ""); // Remove tudo que não for número

    if (value.length > 11) {
        value = value.substring(0, 11);
    }

    if (value.length > 6) {
        e.target.value = value.replace(/^(\d{2})(\d{5})(\d{0,4})/, "($1) $2-$3");
    } else if (value.length > 2) {
        e.target.value = value.replace(/^(\d{2})(\d{0,5})/, "($1) $2");
    } else {
        e.target.value = value.replace(/^(\d*)/, "($1");
    }
});

document.querySelector("form").addEventListener("submit", function() {
    const phone = document.getElementById("telefone");
    phone.value = phone.value.replace(/\D/g, ""); // Envia só números ao PHP
});

document.getElementById('cpf').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, "");

    if (value.length > 11) {
        value = value.substring(0, 11);
    }

    if (value.length > 9) {
        e.target.value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{0,2})/, "$1.$2.$3-$4");
    } else if (value.length > 6) {
        e.target.value = value.replace(/^(\d{3})(\d{3})(\d{0,3})/, "$1.$2.$3");
    } else if (value.length > 3) {
        e.target.value = value.replace(/^(\d{3})(\d{0,3})/, "$1.$2");
    } else {
        e.target.value = value;
    }
});

document.querySelector("form").addEventListener("submit", function() {
    const cpf = document.getElementById("cpf");
    cpf.value = cpf.value.replace(/\D/g, "");
});

const emailInput = document.getElementById("email");
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

emailInput.addEventListener("input", function () {
    if (!emailRegex.test(this.value.trim())) {
        this.style.border = "2px solid #e74c3c"; // vermelho
    } else {
        this.style.border = "2px solid #2ecc71"; // verde
    }
});

document.querySelector("form").addEventListener("submit", function (e) {
    const emailValue = emailInput.value.trim();

    if (!emailRegex.test(emailValue)) {
        e.preventDefault();
        mostrarModal("Por favor, insira um e-mail válido antes de continuar.");
        emailInput.focus();
    }
});

function mostrarModal(mensagem, tipo = "error") {
    // remove modal anterior se existir
    const existente = document.getElementById("modal");
    if (existente) existente.remove();

    const modal = document.createElement("div");
    modal.className = "modal-bg";
    modal.id = "modal";

    modal.innerHTML = `
        <div class="modal ${tipo}">
            <p>${mensagem}</p>
            <button onclick="document.getElementById('modal').remove();">OK</button>
        </div>
    `;

    document.body.appendChild(modal);
}
