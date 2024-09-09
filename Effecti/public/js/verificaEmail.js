
    jQuery('#email').change(function(){
        // Pega o valor do campo de CEP
        var valor = jQuery("#email").val(); 
        // Chama a função buscaCEP passando o valor do CEP do campo
        PesquisaEmail(valor);
    });


    function PesquisaEmail(email) {
        // URL da API com o CEP passado como parâmetro
        var apiURL = UrlEmail;  
        
        var data =  { 'email': email };

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
                if(data.informacao === "Email encontrado")
                    { 
                        jQuery("#email").val('');
                        jQuery(".alerta").addClass('posicao').text("email já existe, tente outro");
                        setTimeout( function(){jQuery(".alerta").removeClass('posicao');},2000 ); 
                    }
                       else 
                    {console.log('00');}
            },
            error: function(xhr, status, error) {
                // Tratamento de erro
                console.log(error);
                jQuery("#resposta").text("Erro ao buscar o e-mail.");
            }
        });
    }
    