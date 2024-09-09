jQuery("document").ready(function (e) {
    jQuery("#EdicaoDados").submit(function (e) {
        e.preventDefault();
        SalvaEdicao();
    });
});



 
function SalvaEdicao() {
    var form = jQuery("#EdicaoDados")[0]; // Seleciona o formulário
    var formData = new FormData(form); // Cria o FormData a partir do formulário

    formData.append('_method', 'PUT'); // Adiciona o campo _method para usar PUT no Laravel

    jQuery.ajax({
        url: UrlSalvaEdicao, // URL do controlador
        method: "POST", // Usamos POST com o campo _method definido como PUT
        dataType: "json",
        data: formData,
        processData: false, // Impede o processamento dos dados
        contentType: false, // Impede o cabeçalho content-type automático
        success: function (response) {
            console.log("Dados enviados com sucesso!", response);
            jQuery('#resposta').text(response.message);
            setTimeout(function(){jQuery('#resposta').text('');  }, 2000); 
        },
        error: function (xhr, status, error) {
            console.error("Erro ao enviar os dados:", error);
        }
    });
}