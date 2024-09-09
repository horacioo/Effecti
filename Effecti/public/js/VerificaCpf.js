
jQuery('#cpf').change(function(){
    // Pega o valor do campo de CEP
    var valor = jQuery("#cpf").val(); 
    // Chama a função buscaCEP passando o valor do CEP do campo
    PesquisaCpf(valor);
});


function PesquisaCpf(cpf) {
    // URL da API com o CEP passado como parâmetro
    var apiURL = UrlVerificaCpf;  
    var data = { 'cpf': cpf }
    if (typeof idUsuario !== 'undefined') { data.id = idUsuario;  }

    // Fazendo a requisição AJAX com jQuery
    jQuery.ajax({
        url: apiURL,
        method: 'GET', // Ou 'POST' dependendo do que você deseja
        dataType: 'json',
        data: data, // Para GET, os parâmetros são passados na URL. Para POST, isso está correto.
        success: function(data) {
            // Exibe os dados retornados pela API no console
            console.log(data.informacao);
            if(data.informacao == true)
                {}
                else 
                { alert(data.informacao); }
        },
        error: function(xhr, status, error) {
            // Tratamento de erro
            console.log(error);
            jQuery("#resposta").text("Erro ao buscar o cpf.");
        } 
    });
}
