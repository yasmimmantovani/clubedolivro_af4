let cepValido = false;

document.getElementById("cep").addEventListener("blur", function() {
    const cep = this.value.replace(/\D/g, "");

    if (cep.length === 8) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(res => res.json())
            .then(data => {
                if (!data.erro) {
                    cepValido = true;
                    document.getElementById("logradouro").value = data.logradouro;
                    document.getElementById("bairro").value = data.bairro;
                    document.getElementById("cidade").value = data.localidade;
                    document.getElementById("estado").value = data.uf;
                } else {
                    cepValido = false;
                    mostrarModalCep("CEP não encontrado!");
                }
            })
            .catch(() => {
                cepValido = false;
                mostrarModalCep("Erro ao consultar o CEP.");
            });
    } else {
        cepValido = false;
        mostrarModalCep("CEP inválido. Deve conter 8 números.");
    }
});

function mostrarModalCep(mensagem) {
    document.getElementById("cepMessage").innerText = mensagem;
    document.getElementById("cepModal").style.display = "flex";
}

document.getElementById("closeCepModal").addEventListener("click", () => {
    document.getElementById("cepModal").style.display = "none";
});

// BLOQUEIA O ENVIO DO FORMULÁRIO
document.querySelector("form").addEventListener("submit", function(e) {
    if (!cepValido) {
        e.preventDefault();
        mostrarModalCep("Informe um CEP válido antes de prosseguir.");
    }
});
