// Adiciona um ouvinte de eventos para quando o conteúdo do DOM estiver totalmente carregado
document.addEventListener("DOMContentLoaded", function () {
    Salva(); // Chama a função Salva após o DOM estar carregado
});
 

// Função para lidar com o envio do formulário
function Salva() {
    var enviando = "Enviando..."; // Mensagem de status para ser usada durante o envio
    var form = jQuery("#cadastro"); // Seleciona o formulário com o ID 'cadastro'

    // Adiciona um ouvinte de eventos para o evento de submit do formulário
    form.on("submit", function (e) {
        e.preventDefault(); // Previne o comportamento padrão de envio do formulário, que recarregaria a página

        // Cria um objeto FormData com os dados do formulário
        var formData = new FormData(this);

        // Envia os dados do formulário via AJAX usando jQuery
        jQuery.ajax({
            url: salvarUrl, // URL da API onde os dados serão enviados
            method: "POST", // Método HTTP para enviar os dados; ajuste conforme necessário (por exemplo, 'GET')
            data: formData, // Dados do formulário a serem enviados
            contentType: false, // Impede que jQuery defina o cabeçalho 'Content-Type'; necessário para envio de arquivos
            processData: false, // Impede que jQuery processe os dados; necessário para envio de FormData
            success: function (response) {
                // Função executada se a requisição for bem-sucedida
                console.log("Dados enviados com sucesso!"); // Log para depuração
                jQuery("#resposta").text(response.message); // Exibe a mensagem de resposta recebida da API
                jQuery("#cadastro")[0].reset(); // Reseta o formulário após o envio
                // Limpa a mensagem de resposta após 5 segundos
                setTimeout(function () {
                    jQuery("#resposta").text("");
                }, 5000); // Tempo em milissegundos (5000 ms = 5 segundos)
            },
            error: function (xhr, status, error) {
                // Função executada se ocorrer um erro durante a requisição
                console.error("Erro ao enviar os dados:", error); // Log do erro para depuração
                jQuery("#resposta").text("Ocorreu um erro ao enviar os dados."); // Exibe uma mensagem de erro para o usuário
            }
        });
    });
}