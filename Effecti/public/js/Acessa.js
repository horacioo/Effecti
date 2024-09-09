function Salva(salvarUrl, metodoHttp = "POST", formId = null) {
    var form = formId ? document.getElementById(formId) : null; // Tenta obter o formulário, se o formId for passado

    // Verifica se o formulário foi passado e se existe
    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault(); // Previne o envio padrão do formulário

            // Cria um objeto FormData com os dados do formulário
            var formData = new FormData(form);

            // Exibe mensagem de "Enviando..." enquanto a requisição está em andamento
            document.querySelector("#resposta").textContent = "Enviando...";

            // Envia os dados via AJAX com fetch API
            fetch(salvarUrl, {
                method: metodoHttp, // O método HTTP é passado como parâmetro
                body: metodoHttp === "GET" || metodoHttp === "DELETE" ? null : formData, // Apenas POST e PUT enviam body
                headers: {
                    'Accept': 'application/json', // Garantindo que esperamos uma resposta JSON
                },
            }) 
                .then(function (response) {
                    if (response.ok) {
                        return response.json(); // Parse da resposta JSON
                    } else {
                        throw new Error("Erro ao enviar os dados. Código: " + response.status);
                    }
                })
                .then(function (response) {
                    // Função executada em caso de sucesso no envio
                    console.log("Dados enviados com sucesso!");
                    document.querySelector("#resposta").textContent = response.message;

                    // Limpa o formulário, se ele existir
                    form.reset();

                    // Limpa a mensagem de resposta após 5 segundos
                    setTimeout(function () {
                        document.querySelector("#resposta").textContent = "";
                    }, 5000);
                })
                .catch(function (error) {
                    // Função executada em caso de erro
                    alert("Ocorreu um erro ao enviar os dados.");
                    console.log(error); // Exibe detalhes do erro

                    // Mostra o erro na div #resposta
                    document.querySelector("#resposta").textContent = error.message;
                });
        });
    } else {
        // Caso não exista formulário ou não seja passado, faz a requisição sem FormData
        fetch(salvarUrl, {
            method: metodoHttp,
            headers: {
                'Accept': 'application/json', // Inclua outros headers conforme necessário
            },
        }) 
            .then(function (response) {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error("Erro ao enviar os dados. Código: " + response.status);
                }
            })
            .then(function (response) {
                console.log("Dados enviados com sucesso!");
                document.querySelector("#resposta").textContent = response.message;

                setTimeout(function () {
                    document.querySelector("#resposta").textContent = "";
                }, 5000);
            })
            .catch(function (error) {
                alert("Ocorreu um erro ao enviar os dados.");
                document.querySelector("#resposta").textContent = error.message;
            });
    }
}
