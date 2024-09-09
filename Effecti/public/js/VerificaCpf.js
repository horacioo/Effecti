// Adiciona um ouvinte de eventos para mudanças no campo de CPF
jQuery('#cpf').change(function() {
    // Obtém o valor do campo de CPF
    var valor = jQuery("#cpf").val(); 
    // Chama a função PesquisaCpf passando o valor do CPF do campo
    PesquisaCpf(valor);
});

// Função para pesquisar informações relacionadas ao CPF
function PesquisaCpf(cpf) {
    // Define a URL da API para verificar o CPF
    var apiURL = UrlVerificaCpf;  // URL da API para verificação de CPF
    var data = { 'cpf': cpf }; // Dados a serem enviados na requisição

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
            
            // Verifica se a informação retornada é verdadeira
            if (data.informacao == true) {
                // Se verdadeiro, nada é feito; pode-se adicionar lógica aqui se necessário
            } else {
                // Se falso, exibe um alerta com a informação retornada pela API
                alert(data.informacao);
            }
        },
        error: function(xhr, status, error) {
            // Função executada em caso de erro na requisição
            console.log(error); // Exibe o erro no console para depuração
            jQuery("#resposta").text("Erro ao buscar o CPF."); // Exibe uma mensagem de erro para o usuário
        } 
    });
}