// Mensagem para indicar que o script foi carregado. Remover em produção.
console.log("carregado");

// Quando o documento estiver totalmente carregado
jQuery(document).ready(function () {
    // Adiciona um ouvinte de eventos para o campo de pesquisa
    jQuery("#pesquisa").on("input", function () {
        var valor = jQuery(this).val(); // Obtém o valor do campo de pesquisa
        Pesquisar(valor); // Chama a função de pesquisa com o valor atual
    });
});

// Função para realizar a pesquisa
function Pesquisar(valor) {

    var chave = window.localStorage.getItem('key');
    if (typeof chave === 'undefined' || chave === null) {
        // Se a variável não existir ou for null, redireciona a página
        alert("chave indosponível,  volte para a home e reinicie o processo de pesquisa "+chave);
    }


    jQuery.ajax({
        url: pesquisaURL, // URL da API para pesquisa
        method: "GET", // Use 'POST' se você estiver enviando dados sensíveis ou grandes volumes de dados
        dataType: "json", // Tipo de dados esperados na resposta
        data: { pesquisando: valor, key: chave }, // Parâmetro da pesquisa adicionado à URL como parâmetros de consulta
        success: function (response) {
            // Limpa o conteúdo da tabela antes de adicionar novos dados
            jQuery("#resultados tbody").empty(); // Alternativa mais eficiente para remover todos os filhos

            jQuery('#linkPdf').attr('href', response.filename);
            jQuery('#linkCSV').attr('href', response.arquivocsv);
            jQuery('#linkXLS').attr('href', response.arquivoxls);

            if (response.success) {
                // Verifica se a pesquisa foi bem-sucedida
                var pesquisa = response.pesquisa; // Obtém os resultados da pesquisa
                var i = 0;

                // Itera sobre o array de resultados e adiciona linhas à tabela
                pesquisa.forEach(function (item) {
                    jQuery("#resultados tbody").append(
                        "<tr>" +
                            "<td>" +
                            item.nome +
                            "</td>" +
                            "<td>" +
                            item.email +
                            "</td>" +
                            "<td>" +
                            item.cpf +
                            "</td>" +
                            "<td>" +
                            item.cep +
                            "</td>" +
                            "<td>" +
                            item.telefone +
                            "</td>" +
                            "<td>" +
                            item.data_nascimento +
                            "</td>" +
                            "<td>" +
                            item.endereco +
                            ", " +
                            item.bairro +
                            ", " +
                            item.cidade +
                            ", " +
                            item.estado +
                            "</td>"+
                            "</tr>"
                    );
                    i++;
                });
            }
        },
        error: function (xhr, status, error) {
            // Registra erros na requisição
            console.error("Erro na requisição:", status, error); // Melhorar o tratamento de erros conforme necessário
        },
    });
}
