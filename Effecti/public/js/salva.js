document.addEventListener("DOMContentLoaded", function () {
    Salva();
});


function Salva() {
    var enviando = "Enviando...";
    var form = jQuery("#cadastro");

    // Adiciona o evento de submit ao formulário
    form.on("submit", function (e) {
        e.preventDefault(); // Previne o envio padrão do formulário

        // Cria um objeto FormData com os dados do formulário
        var formData = new FormData(this);

        // Envia os dados via AJAX com jQuery
        jQuery.ajax({
            url: salvarUrl, // Defina a URL da API
            method: "POST", // Ou 'GET', dependendo do método desejado
            data: formData,
            contentType: false, // Para enviar arquivos
            processData: false, // Impede o processamento dos dados
            success: function (response) {
                // Função executada em caso de sucesso no envio
                console.log("Dados enviados com sucesso!");
                jQuery("#resposta").text(response.message); // Exibe a mensagem de resposta
                jQuery("#cadastro")[0].reset(); // Reseta o formulário
                setTimeout(function () {
                    jQuery("#resposta").text("");
                }, 5000); // Limpa a resposta após 5 segundos
            },
            error: function (xhr, status, error) {
                // Função executada em caso de erro
                console.error("Erro ao enviar os dados:", error);
                jQuery("#resposta").text("Ocorreu um erro ao enviar os dados.");
            }
        });
    });
}