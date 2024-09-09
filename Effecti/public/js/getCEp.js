// Quando o documento estiver totalmente carregado
jQuery(document).ready(function() {
    // Adiciona um ouvinte de eventos para mudanças no campo de CEP
    jQuery('#cep').change(function() {
        // Pega o valor do campo de CEP
        var valor = jQuery("#cep").val(); 
        // Chama a função buscaCEP passando o valor do CEP do campo
        buscaCEP(valor);
    });
});

// Função para buscar informações do endereço com base no CEP
function buscaCEP(cep) {
    // URL da API para buscar dados do CEP
    // Comentado: var apiURL = 'http://cep.republicavirtual.com.br/web_cep.php?cep='+cep+'&formato=json';  
    var apiURL = 'https://viacep.com.br/ws/'+cep+'/json/'; // URL atual da API para consulta do CEP

    // Fazendo a requisição AJAX com jQuery
    jQuery.ajax({
        url: apiURL, // URL para a requisição
        method: 'GET', // Método HTTP para a requisição
        dataType: 'json', // Tipo de dados esperado na resposta
        success: function(data) {
            // Função executada em caso de sucesso na requisição
            console.log(data); // Exibe os dados retornados pela API no console para depuração
            
            // Preenche os campos do formulário com os dados retornados
            jQuery("#endereco").val(data.logradouro); // Endereço
            jQuery("#bairro").val(data.bairro); // Bairro
            jQuery("#cidade").val(data.localidade); // Cidade
            jQuery("#estado").val(data.uf); // Estado
        },
        error: function(xhr, status, error) {
            // Função executada em caso de erro na requisição
            console.error('Erro:', error); // Exibe o erro no console para depuração
            jQuery("#resposta").text("Erro ao buscar o CEP."); // Exibe uma mensagem de erro para o usuário
        }
    });
}
