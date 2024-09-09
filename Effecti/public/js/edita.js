// Aguarda o carregamento completo do documento antes de executar o código
jQuery(document).ready(function () {
    // Adiciona um ouvinte de eventos para o evento de submissão do formulário
    jQuery("#EdicaoDados").submit(function (e) {
        e.preventDefault(); // Evita o envio padrão do formulário
        SalvaEdicao(); // Chama a função para salvar a edição
    });
});

// Função para salvar a edição
function SalvaEdicao() {
    var form = jQuery("#EdicaoDados")[0]; // Seleciona o formulário DOM
    var formData = new FormData(form); // Cria um objeto FormData a partir do formulário

    formData.append('_method', 'PUT'); // Adiciona o campo '_method' ao FormData para usar o método PUT no Laravel

    jQuery.ajax({
        url: UrlSalvaEdicao, // URL para onde a requisição será enviada (controlador no Laravel)
        method: "POST", // Utiliza o método POST com o campo '_method' para simular um PUT
        dataType: "json", // Tipo de dados esperado na resposta
        data: formData, // Dados enviados com a requisição
        processData: false, // Impede que jQuery processe os dados (necessário para FormData)
        contentType: false, // Impede que jQuery defina automaticamente o cabeçalho 'Content-Type'
        success: function (response) {
            // Ação a ser tomada quando a requisição for bem-sucedida
            console.log("Dados enviados com sucesso!", response); // Log para depuração
            jQuery('#resposta').text(response.message); // Exibe a mensagem de sucesso na página
            // Limpa a mensagem de resposta após 2 segundos
            setTimeout(function() {
                jQuery('#resposta').text('');
            }, 2000); 
        },
        error: function (xhr, status, error) {
            // Ação a ser tomada em caso de erro na requisição
            console.error("Erro ao enviar os dados:", error); // Log do erro para depuração
        }
    });
}