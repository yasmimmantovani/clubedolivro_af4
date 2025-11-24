document.getElementById("cep").addEventListener("blur", function() {
    const cep = this.value.replace(/\D/g, "");

    if(cep.length === 8) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(res => res.json())
        .then(data => {
            if (!data.erro) {
                document.getElementById("logradouro").value = data.logradouro;
                document.getElementById("bairro").value = data.bairro;
                document.getElementById("cidade").value = data.localidade;
                document.getElementById("estado").value = data.uf;
            } else {
                mostrarModalCep("CEP não encontrado!");
            }
        })
        .catch(() => mostrarModalCep("Erro ao consultar o CEP."));
    }
})

function mostrarModalCep(mensagem) {
    document.getElementById("cepMessage").innerText = mensagem;
    document.getElementById("cepModal").style.display = "flex";
}

document.getElementById("closeCepModal").addEventListener("click", () => {
    document.getElementById("cepModal").style.display = "none";
})