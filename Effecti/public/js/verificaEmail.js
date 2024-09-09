// Adiciona um ouvinte de eventos para mudanças no campo de e-mail
jQuery('#email').change(function() {
    // Obtém o valor do campo de e-mail
    var valor = jQuery("#email").val(); 
    // Chama a função PesquisaEmail passando o valor do e-mail do campo
    PesquisaEmail(valor);
});

// Função para pesquisar informações relacionadas ao e-mail
function PesquisaEmail(email) {
    // Define a URL da API para verificar o e-mail
    var apiURL = UrlEmail;  // URL da API para verificação do e-mail
    
    var data = { 'email': email }; // Dados a serem enviados na requisição

    // Adiciona o idUsuario aos dados se estiver definido
    if (typeof idUsuario !== 'undefined') { 
        data.id = idUsuario;  
    }

    // Realiza a requisição AJAX com jQuery
    jQuery.ajax({
        url: apiURL, // URL da API para onde a requisição será enviada
        method: 'GET', // Método HTTP para a requisição; pode ser 'POST' se necessário
        dataType: 'json', // Tipo de dados esperado na resposta
        data: data, // Dados a serem enviados na requisição; para GET, eles são passados na URL
        success: function(data) {
            // Função executada em caso de sucesso na requisição
            console.log(data.informacao); // Exibe os dados retornados pela API no console para depuração
            
            // Verifica se a informação retornada é "Email encontrado"
            if (data.informacao === "Email encontrado") { 
                // Se o e-mail for encontrado, limpa o campo de e-mail
                jQuery("#email").val('');
                // Adiciona uma classe para exibir um alerta com a mensagem de erro
                jQuery(".alerta").addClass('posicao').text("Email já existe, tente outro");
                // Remove a classe de alerta após 2 segundos
                setTimeout(function() {
                    jQuery(".alerta").removeClass('posicao');
                }, 2000); 
            } else {
                // Se o e-mail não for encontrado, exibe uma mensagem no console para depuração
                console.log('00');
            }
        },
        error: function(xhr, status, error) {
            // Função executada em caso de erro na requisição
            console.log(error); // Exibe o erro no console para depuração
            jQuery("#resposta").text("Erro ao buscar o e-mail."); // Exibe uma mensagem de erro para o usuário
        }
    });
}