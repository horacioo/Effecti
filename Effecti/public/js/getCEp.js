jQuery(document).ready(function(){
    jQuery('#cep').change(function(){
        // Pega o valor do campo de CEP
        var valor = jQuery("#cep").val(); 
        // Chama a função buscaCEP passando o valor do CEP do campo
        buscaCEP(valor);
    });
});



function buscaCEP(cep) {
    // URL da API com o CEP passado como parâmetro
    var apiURL = 'http://cep.republicavirtual.com.br/web_cep.php?cep='+cep+'&formato=json';  
    apiURL = 'https://viacep.com.br/ws/'+cep+'/json/'
    // Fazendo a requisição AJAX com jQuery
    jQuery.ajax({
        url: apiURL,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            // Exibe os dados retornados pela API no console
            console.log(data);
            jQuery("#endereco").val(data.logradouro);
            jQuery("#bairro").val( data.bairro );
            jQuery("#cidade").val(data.localidade);
            jQuery("#estado").val( data.uf );
        },
        error: function(xhr, status, error) {
            // Tratamento de erro
            console.error('Erro:', error);
            jQuery("#resposta").text("Erro ao buscar o CEP.");
        }
    });
}

// Exemplo de uso

