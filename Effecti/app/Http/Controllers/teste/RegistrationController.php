<?php

namespace App\Http\Controllers\teste;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PDF;

class RegistrationController extends Controller
{
    private $NomeArquivo;




    public function index()
    {
        return view('cadastro.home');
    }





    public function Cadastrar()
    {
        return view('cadastro.cadastro');
    }




    /**
     * Verifica se um endereço de e-mail já está cadastrado e se é válido.
     *
     * Esta função faz a busca do e-mail no banco de dados, e caso ele já
     * esteja registrado, retorna uma resposta informando que o e-mail foi encontrado.
     * Caso o e-mail não seja encontrado, ele valida o formato do e-mail.
     * Se o e-mail for válido, retorna uma resposta informando que o e-mail não foi encontrado.
     * Se o e-mail for inválido, retorna uma resposta informando que o e-mail é inválido.
     *
     * @param Request $request O objeto da requisição HTTP que contém o e-mail e, opcionalmente, um id.
     * @return \Illuminate\Http\JsonResponse Retorna uma resposta JSON com as informações sobre a validação do e-mail.
     */
    public function verificaEmail(Request $request)
    {
        // Obtém o valor do e-mail da requisição.
        $email = $request->input('email');

        // Realiza uma consulta na tabela 'Registration' para verificar se o e-mail já existe.
        // Se um 'id' for fornecido na requisição, ele será excluído da verificação (não verifica o próprio id).
        $reg = Registration::where('email', $email)
            ->when($request->has('id'), function ($query) use ($request) {
                // Exclui o registro com o ID fornecido da verificação
                return $query->where('id', '!=', $request->input('id'));
            })
            ->first();

        // Verifica se o registro foi encontrado.
        if ($reg) {
            // Se o e-mail foi encontrado no banco, retorna a informação e marca 'sucesso' como 0 (falha na verificação).
            return response()->json(['informacao' => 'Email encontrado', 'sucesso' => 0]);
        } else {
            // Se o e-mail não foi encontrado, verifica se o formato do e-mail é válido.
            if ($this->validarEmail($email)) {
                // Se o e-mail for válido mas não foi encontrado, retorna que o e-mail não foi encontrado com sucesso 1 (sucesso na verificação).
                return response()->json(['informacao' => 'Email não encontrado', 'sucesso' => 1]);
            } else {
                // Se o e-mail for inválido, retorna a mensagem que o e-mail é inválido e marca 'sucesso' como 0 (falha na verificação).
                return response()->json(['informacao' => 'Email inválido', 'sucesso' => 0]);
            }
        }
    }






    /**
     * Valida o formato de um endereço de e-mail.
     *
     * Esta função utiliza a função nativa filter_var do PHP para validar
     * se o e-mail fornecido está em um formato correto e aceitável.
     *
     * @param string $email O endereço de e-mail a ser validado.
     * @return bool Retorna true se o e-mail for válido, ou false se for inválido.
     */
    private function validarEmail($email)
    {
        // Utiliza o filtro FILTER_VALIDATE_EMAIL para validar o formato do e-mail.
        // Se o e-mail for válido, retorna true. Caso contrário, retorna false.
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }







    /**
     * Valida um número de CPF recebido na requisição.
     *
     * Esta função realiza a validação de um CPF, verificando se ele já está registrado
     * e se o formato do CPF é válido. O CPF é validado conforme as regras oficiais,
     * incluindo verificação de dígitos e estrutura. 
     *
     * @param Request $request O objeto da requisição HTTP que contém o CPF e, opcionalmente, um id.
     * @return \Illuminate\Http\JsonResponse Retorna uma resposta JSON indicando se o CPF é válido ou não.
     */
    public function validaCPF(Request $request)
    {
        // Obtém o valor do CPF da requisição.
        $cpf = $request->input('cpf');

        // Obtém o valor do ID da requisição, se fornecido, ou define como null.
        $id = $request->input('id', null);

        // Verifica se o CPF já existe, considerando um ID opcional para exclusão da verificação.
        if (!$this->validaCPFunico($cpf, $id)) {
            // Se o CPF já existir, retorna uma mensagem de erro.
            return response()->json(['informacao' => 'CPF já existe!']);
        }

        // Remove todos os caracteres não numéricos do CPF.
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Verifica se o CPF possui exatamente 11 dígitos.
        if (strlen($cpf) != 11) {
            // Se não tiver 11 dígitos, retorna uma mensagem de erro.
            return response()->json(['informacao' => 'CPF inválido']);
        }

        // Verifica se todos os dígitos do CPF são iguais (e.g., 11111111111).
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            // Se todos os dígitos forem iguais, retorna uma mensagem de erro.
            return response()->json(['informacao' => 'CPF inválido']);
        }

        // Valida os dígitos verificadores do CPF.
        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            // Calcula o valor dos dígitos verificadores com base nos primeiros $t dígitos do CPF.
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            // Verifica se o dígito calculado corresponde ao dígito do CPF.
            if ($cpf[$c] != $d) {
                // Se o dígito não corresponder, retorna uma mensagem de erro.
                return response()->json(['informacao' => 'CPF inválido']);
            }
        }

        // Se todas as validações passarem, retorna que o CPF é válido.
        return response()->json(['informacao' => 'CPF válido']);
    }








    /**
     * Verifica se um CPF é único no banco de dados, considerando um ID opcional para exclusão da verificação.
     *
     * Esta função consulta a base de dados para verificar se já existe um registro com o CPF fornecido. 
     * Se um ID é fornecido, a verificação exclui o registro com esse ID para permitir atualizações 
     * do próprio registro sem gerar um conflito. Caso contrário, verifica se o CPF já existe em qualquer 
     * outro registro.
     *
     * @param string $cpf O CPF a ser verificado.
     * @param int|null $id O ID do registro que deve ser excluído da verificação, se aplicável.
     * @return bool Retorna verdadeiro se o CPF é único (não encontrado) ou falso se já existe.
     */
    private function validaCPFunico($cpf, $id = null)
    {
        if ($id) {
            // Se um ID é fornecido, verifica se existe um registro com o mesmo CPF, mas com ID diferente.
            $registro = Registration::where('cpf', $cpf)
                ->where('id', '!=', $id)
                ->first();
        } else {
            // Se nenhum ID é fornecido, verifica se existe qualquer registro com o mesmo CPF.
            $registro = Registration::where('cpf', $cpf)->first();
        }

        // Retorna verdadeiro se nenhum registro com o CPF foi encontrado (único), falso caso contrário.
        return !$registro;
    }







    /**
     * Salva um novo registro no banco de dados com os dados fornecidos na requisição.
     *
     * Esta função cria uma nova instância do modelo `Registration`, preenche seus atributos com os dados
     * recebidos da requisição e salva o registro no banco de dados. Em caso de sucesso, retorna uma resposta
     * JSON indicando que o registro foi salvo com sucesso. Se ocorrer um erro de validação ou um erro geral,
     * a função captura e retorna a mensagem de erro apropriada.
     *
     * @param \Illuminate\Http\Request $request A requisição contendo os dados do registro a ser salvo.
     * @return \Illuminate\Http\JsonResponse Resposta JSON indicando o sucesso ou falha da operação.
     */
    public function Salva(Request $request)
    {
        try {
            // Cria uma nova instância do modelo Registration
            $reg = new Registration();

            // Preenche os atributos do modelo com os dados da requisição
            $reg->nome = $request->nome;
            $reg->cpf = $request->cpf;
            $reg->data_nascimento = $request->nascimento;
            $reg->email = $request->email;
            $reg->telefone = $request->telefone;
            $reg->cep = $request->cep;
            $reg->estado = $request->estado;
            $reg->bairro = $request->bairro;
            $reg->cidade = $request->cidade;
            $reg->endereco = $request->endereco;
            $reg->status = '1'; // Define o status do registro como ativo

            // Salva o registro no banco de dados
            $reg->save();

            // Retorna uma resposta JSON indicando sucesso e incluindo os dados do registro
            return response()->json([
                'success' => true,
                'message' => 'Registro salvo com sucesso!',
                'data' => $reg
            ], 201); // Código de status HTTP 201 Created

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Captura exceções de validação e retorna uma resposta JSON com erros de validação
            return response()->json([
                'success' => false,
                'message' => 'Erro na validação dos dados.',
                'errors' => $e->errors()
            ], 422); // Código de status HTTP 422 Unprocessable Entity

        } catch (\Exception $e) {
            // Captura exceções gerais e retorna uma resposta JSON com a mensagem de erro
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao salvar o registro.',
                'error' => $e->getMessage()
            ], 500); // Código de status HTTP 500 Internal Server Error
        }
    }








    /**
     * Atualiza um registro existente no banco de dados com os dados fornecidos na requisição.
     *
     * Esta função busca um registro pelo seu ID, atualiza seus atributos com os dados recebidos
     * da requisição e salva as alterações no banco de dados. Em caso de sucesso, retorna uma resposta
     * JSON indicando que o registro foi atualizado com sucesso. Se o registro não for encontrado, retorna
     * uma resposta JSON com uma mensagem de erro e um código de status 404.
     *
     * @param \Illuminate\Http\Request $request A requisição contendo o ID do registro e os dados para atualização.
     * @return \Illuminate\Http\JsonResponse Resposta JSON indicando o sucesso ou falha da atualização.
     */
    public function update(Request $request)
    {
        // Obtém o ID do registro a partir da requisição
        $id = $request->id;

        // Busca o registro no banco de dados pelo ID fornecido
        $registro = Registration::find($id);

        // Verifica se o registro foi encontrado
        if ($registro) {
            // Atualiza os atributos do registro com os dados da requisição
            $registro->nome = $request->nome;
            $registro->cpf = $request->cpf;
            $registro->data_nascimento = $request->nascimento;
            $registro->email = $request->email;
            $registro->telefone = $request->telefone;
            $registro->cep = $request->cep;
            $registro->estado = $request->estado;
            $registro->bairro = $request->bairro;
            $registro->cidade = $request->cidade;
            $registro->endereco = $request->endereco;

            // Salva as alterações no banco de dados
            $registro->save();

            // Retorna uma resposta JSON indicando sucesso e uma mensagem de sucesso
            return response()->json(['success' => true, 'message' => 'Registro atualizado com sucesso!']);
        }

        // Se o registro não for encontrado, retorna uma resposta JSON com uma mensagem de erro e código de status 404
        return response()->json(['success' => false, 'message' => 'Registro não encontrado!'], 404);
    }








    /**
     * Recupera uma lista de registros ativos e retorna uma "view" com esses registros.
     *
     * Esta função consulta o banco de dados para obter os registros com status '1' (ativos), limita a
     * quantidade de registros retornados a 20 e, em seguida, passa esses registros para uma "view" específica
     * para serem exibidos. A "view" é renderizada com os dados dos registros para que possam ser apresentados
     * ao usuário.
     *
     * @return \Illuminate\View\View A view contendo a lista de registros ativos.
     */
    public function lista()
    {
        // Consulta o banco de dados para obter registros com status '1' (ativos), limitando a 20 registros
        $registros = Registration::where('status', '1')->take(20)->get();

        // Retorna a 'view' 'cadastro.lista' com a lista de registros ativos
        return view('cadastro.lista', ['registros' => $registros]);
    }









    /**
     * Recupera um registro pelo ID e retorna uma view para edição.
     *
     * Esta função busca um registro no banco de dados usando o ID fornecido. Se o registro for encontrado,
     * a função retorna uma view para editar o registro. Se o registro não for encontrado, a função retorna
     * uma mensagem indicando que o registro não foi localizado.
     *
     * @param int $id O ID do registro a ser recuperado.
     * @return \Illuminate\View\View|string Retorna a view de edição com o registro encontrado ou uma mensagem de erro se o registro não for encontrado.
     */
    public function Editar($id)
    {
        // Recupera o registro com o ID fornecido
        $registro = Registration::find($id);

        // Verifica se o registro foi encontrado
        if (!$registro) {
            // Retorna uma mensagem de erro se o registro não for encontrado
            return "registro não localizado";
        }

        // Retorna a view de edição com os dados do registro
        return view('cadastro.editar', ['registro' => $registro]);
    }










    /**
     * Marca um registro como deletado (inativo) com base no ID fornecido.
     *
     * Esta função busca um registro no banco de dados usando o ID fornecido. Se o registro for encontrado,
     * a função marca o registro como deletado definindo seu status como 0 e salva as alterações no banco de dados.
     * Após isso, o usuário é redirecionado para a lista de registros. Se o registro não for encontrado,
     * uma mensagem de erro é retornada.
     *
     * @param int $id O ID do registro a ser marcado como deletado.
     * @return \Illuminate\Http\RedirectResponse|string Redireciona para a lista de registros ou retorna uma mensagem de erro se o registro não for encontrado.
     */
    public function Deletar($id)
    {
        // Recupera o registro com o ID fornecido
        $registro = Registration::find($id);

        // Verifica se o registro foi encontrado
        if (!$registro) {
            // Retorna uma mensagem de erro se o registro não for encontrado
            return "registro não localizado";
        } else {
            // Marca o registro como deletado definindo seu status como 0
            $registro->status = 0;
            // Salva as alterações no banco de dados
            $registro->save();
            // Redireciona o usuário para a rota que exibe a lista de registros
            return redirect()->route('cadastro.lista');
        }
    }





















    /**
     * Realiza uma pesquisa de registros e gera arquivos CSV, XLS e PDF com base nos resultados da pesquisa.
     * Também armazena um identificador único de visitante na sessão e retorna informações sobre os arquivos gerados.
     *
     * @param \Illuminate\Http\Request $request A solicitação contendo o termo de pesquisa e a chave opcional para o nome do arquivo.
     * @return \Illuminate\Http\JsonResponse A resposta JSON contendo informações sobre os arquivos gerados e os resultados da pesquisa.
     */
    public function pesquisar(Request $request)
    {
        // Obtém o termo de pesquisa a partir do parâmetro 'pesquisando' da solicitação
        $pesquisa = $request->input('pesquisando');

        // Define o nome do arquivo PDF a ser gerado. Se a chave 'key' for fornecida, usa uma parte dela para o nome do arquivo.
        // Caso contrário, usa o endereço IP remoto do visitante como parte do nome do arquivo.
        $filename = $request->input('key')
            ? substr($request->input('key'), 6, 16) . ".pdf"
            : $_SERVER['REMOTE_ADDR'] . ".pdf";

        // Gera um UUID único para identificar a sessão do visitante e armazena na sessão.
        $visitorId = (string) \Str::uuid();
        session()->put('visitor_id', $visitorId);

        // Realiza a pesquisa na tabela 'Registration' usando o termo de pesquisa em diferentes campos (nome, CPF, email, etc.).
        // Inclui a condição adicional para trazer apenas registros com status = 1. 
        $resultado = Registration::where('status', '1')
            ->where(function ($query) use ($pesquisa) {
                $query->where('nome', 'LIKE', "%{$pesquisa}%")
                    ->orWhere('cpf', 'LIKE', "%{$pesquisa}%")
                    ->orWhere('email', 'LIKE', "%{$pesquisa}%")
                    ->orWhere('endereco', 'LIKE', "%{$pesquisa}%")
                    ->orWhere('id', 'LIKE', "%{$pesquisa}%");
            })->get();

        // Remove a extensão do nome do arquivo para uso posterior (como prefixo).
        $NomeArquivo = explode(".", $filename);
        $this->NomeArquivo = $NomeArquivo[0];

        // Gera os arquivos CSV e XLS com base nos resultados da pesquisa.
        // Chama métodos auxiliares para criar os arquivos CSV e XLS.
        $arquivoCSV = $this->gerarCSV($resultado);
        $xls = $this->gerarXLS($resultado);

        // Gera o PDF com os resultados da pesquisa e salva no diretório apropriado.
        // Chama o método auxiliar para criar o PDF.
        $pdfPath = $this->gerarPDF($resultado);

        // Retorna uma resposta JSON contendo:
        // - O nome do arquivo sem a extensão.
        // - Um indicador de sucesso da operação.
        // - Os resultados da pesquisa.
        // - URLs para acessar os arquivos CSV, PDF e XLS gerados.
        return response()->json([
            'primeiroFileName' => $this->NomeArquivo,  // Nome do arquivo sem a extensão
            'success' => true,  // Indica que a operação foi bem-sucedida
            'pesquisa' => $resultado,  // Resultados da pesquisa
            'arquivocsv' => asset($arquivoCSV),  // URL do arquivo CSV gerado
            'filename' => asset('storage/pdf/') . "/" . $filename,  // URL do arquivo PDF gerado
            'arquivoxls' => asset('') . "" . $xls  // URL do arquivo XLS gerado
        ]);
    }







    /**
     * Gera um arquivo PDF com base nos resultados da pesquisa e o salva no diretório apropriado.
     *
     * @param \Illuminate\Database\Eloquent\Collection $resultado Os resultados da pesquisa.
     * @param string $filename O nome do arquivo PDF a ser gerado.
     * @return string O caminho completo para o arquivo PDF gerado.
     */
    private function gerarPDF($resultado)
    {
        // Cria um PDF com os resultados da pesquisa usando uma view específica
        $pdf = PDF::loadView('pdf.pesquisa', ['resultados' => $resultado]);

        // Define o caminho para salvar o arquivo PDF
        $path = storage_path('app/public/pdf/' . $this->NomeArquivo . ".pdf");
        $directory = dirname($path);

        // Cria o diretório se ele não existir
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Salva o arquivo PDF no caminho especificado
        $pdf->save($path);

        // Retorna o caminho completo do arquivo PDF gerado
        return $path;
    }









    /**
     * Gera um arquivo CSV com os dados fornecidos e salva no diretório especificado.
     *
     * @param \Illuminate\Support\Collection $dados Os dados a serem incluídos no arquivo CSV.
     * @param string $fileName O nome do arquivo CSV a ser gerado, sem a extensão.
     * @return string O caminho relativo para o arquivo CSV gerado.
     */
    private function gerarCSV($dados)
    {
        // Inicializa um array para armazenar os dados do CSV.
        $data = [];
        $linha = 0;
        // Itera sobre os dados fornecidos e organiza-os em um array associativo.
        foreach ($dados as $x) {
            $data[$linha] = [
                "id" => $x['id'],
                "nome" => $x['nome'],
                "cpf" => $x['cpf'],
                "data_nascimento" => $x['data_nascimento'],
                "email" => $x['email'],
                "telefone" => $x['telefone'],
                "cep" => $x['cep'],
                "estado" => $x['estado'],
                "bairro" => $x['bairro'],
                "cidade" => $x['cidade'],
                "endereco" => $x['endereco']
            ];
            $linha++;
        }

        // Define o caminho completo para salvar o arquivo CSV.
        // O diretório 'csv' está localizado dentro do diretório 'public' de armazenamento.
        $arquivo = storage_path('app/public/csv/' . $this->NomeArquivo . ".csv");

        // Abre o arquivo CSV para escrita. Se o arquivo não existir, ele será criado.
        $fp = fopen($arquivo, 'w');

        // Escreve o cabeçalho do CSV no arquivo.
        fputcsv($fp, ['id', 'Nome', 'Cpf', 'Data de Nascimento', 'E-mail', 'Telefone', 'Cep', 'Estado', 'Bairro', 'Cidade', 'Endereco', 'Status']);

        // Itera sobre os dados e escreve cada linha no arquivo CSV.
        foreach ($data as $linha) {
            fputcsv($fp, $linha);
        }

        // Fecha o arquivo CSV após a escrita.
        fclose($fp);

        // Retorna o caminho relativo para o arquivo CSV gerado, que pode ser usado para acessá-lo posteriormente.
        return 'storage/csv/' . $this->NomeArquivo . '.csv';
    }












    /**
     * Gera um arquivo XLSX com os dados fornecidos e salva no diretório especificado.
     *
     * @param \Illuminate\Support\Collection $dados Os dados a serem incluídos no arquivo XLSX.
     * @param string $fileName O nome do arquivo XLSX a ser gerado, sem a extensão.
     * @return string O caminho relativo para o arquivo XLSX gerado.
     */
    private function gerarXLS($dados,)
    {
        // Cria uma nova instância da classe Spreadsheet, que será usada para gerar o arquivo XLSX.
        $spreadsheet = new Spreadsheet();

        // Obtém a folha ativa da planilha onde os dados serão escritos.
        $sheet = $spreadsheet->getActiveSheet();

        // Define os cabeçalhos das colunas na primeira linha da planilha.
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nome');
        $sheet->setCellValue('C1', 'CPF');
        $sheet->setCellValue('D1', 'Data de Nascimento');
        $sheet->setCellValue('E1', 'Email');
        $sheet->setCellValue('F1', 'Telefone');
        $sheet->setCellValue('G1', 'CEP');
        $sheet->setCellValue('H1', 'Estado');
        $sheet->setCellValue('I1', 'Bairro');
        $sheet->setCellValue('J1', 'Cidade');
        $sheet->setCellValue('K1', 'Endereço');

        // Define a linha inicial para começar a escrever os dados.
        $row = 2;

        // Itera sobre os dados fornecidos e preenche as células da planilha.
        foreach ($dados as $x) {
            $sheet->setCellValue('A' . $row, $x->id);
            $sheet->setCellValue('B' . $row, $x->nome);
            $sheet->setCellValue('C' . $row, $x->cpf);
            $sheet->setCellValue('D' . $row, $x->data_nascimento);
            $sheet->setCellValue('E' . $row, $x->email);
            $sheet->setCellValue('F' . $row, $x->telefone);
            $sheet->setCellValue('G' . $row, $x->cep);
            $sheet->setCellValue('H' . $row, $x->estado);
            $sheet->setCellValue('I' . $row, $x->bairro);
            $sheet->setCellValue('J' . $row, $x->cidade);
            $sheet->setCellValue('K' . $row, $x->endereco);
            $row++;
        }

        // Cria uma nova instância do escritor XLSX para salvar o arquivo.
        $writer = new Xlsx($spreadsheet);

        // Define o caminho do diretório onde o arquivo XLSX será salvo.
        $path = storage_path('app/public/xlsx/');

        // Verifica se o diretório existe; se não, cria-o com permissões adequadas.
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // Define o caminho completo para o arquivo XLSX a ser salvo e salva o arquivo.
        $filePath = $path . $this->NomeArquivo . '.xlsx';
        $writer->save($filePath);

        // Retorna o caminho relativo para o arquivo XLSX gerado.
        return 'storage/xlsx/' . $this->NomeArquivo . '.xlsx';
    }
}
