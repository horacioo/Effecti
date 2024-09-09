<?php

namespace App\Http\Controllers\teste;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PharIo\Manifest\Email;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class RegistrationController extends Controller
{





    /**************************************************************************************/
    /**aqui  é a  home do sistema**/
    public function index()
    {
        session(['user_session_id' => (string) \Str::uuid()]);
        return view('cadastro.home');
    }
    /**************************************************************************************/




    /**************************************************************************************/
    /**aqui  é a  tela do cadastro**/
    public function Cadastrar()
    {
        return view('cadastro.cadastro');
    }
    /**************************************************************************************/









    /**************************************************************************************/
    public function verificaEmail(Request $request)
    {
        // Obtenha o email da requisição
        $email = $request->input('email');

        // Verifique se um ID foi passado na requisição
        if ($request->has('id')) {
            // Se o ID foi passado, busque o registro pelo email e pelo ID
            $reg = Registration::where('email', $email)
                ->when($request->has('id'), function ($query) use ($request) {
                    return $query->where('id', '!=', $request->input('id'));
                })
                ->first();
        } else {
            // Se o ID não foi passado, busque apenas pelo email
            $reg = Registration::where('email', $email)->first();
        }

        // Retorne a resposta JSON com a informação
        if ($reg) {
            return response()->json(['informacao' => 'Email encontrado']);
        } else {
            return response()->json(['informacao' => 'Email não encontrado']);
        }
    }
    /**************************************************************************************/





    /**************************************************************************************/
    public function validaCPF(Request $request)
    {
        $cpf = $request->input('cpf');
        $id = $request->input('id', null);

        // Verifica se o CPF é único, excluindo o próprio registro (se o ID estiver presente)
        if (!$this->validaCPFunico($cpf, $id)) {
            return response()->json(['informacao' => 'CPF já existe!']);
        }

        // Remove qualquer máscara como pontos, traços ou espaços
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Verifica se o CPF tem exatamente 11 dígitos
        if (strlen($cpf) != 11) {
            return response()->json(['informacao' => 'CPF inválido']);
        }

        // Verifica se todos os números são iguais, o que invalida o CPF (ex: 111.111.111-11)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return response()->json(['informacao' => 'CPF inválido']);
        }

        // Cálculo para o primeiro dígito verificador
        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return response()->json(['informacao' => 'CPF inválido']);
            }
        }

        return response()->json(['informacao' => 'CPF válido']);
    }

    /**************************************************************************************/



    /**************************************************************************************/
    /**************************************************************************************/
    private function validaCPFunico($cpf, $id = null)
    {
        // Se o $id for fornecido, busca registros com o mesmo CPF, mas exclui o registro com o ID fornecido
        if ($id) {
            $registro = Registration::where('cpf', $cpf)->where('id', '!=', $id)->first();
        } else {
            // Caso contrário, busca se o CPF já existe no banco de dados
            $registro = Registration::where('cpf', $cpf)->first();
        }

        // Retorna true se o CPF não for encontrado (ou seja, é único)
        if (!$registro) {
            return true;
        }

        // Retorna false se o CPF for encontrado (ou seja, não é único)
        return false;
    }
    /**************************************************************************************/




    /**************************************************************************************/
    public function Salva(Request $request)
    {

        try {

            // Criação e preenchimento do modelo Registration
            $reg = new Registration();
            $reg->nome             = $request->nome;
            $reg->cpf              = $request->cpf;
            $reg->data_nascimento  = $request->nascimento;
            $reg->email            = $request->email;
            $reg->telefone         = $request->telefone;
            $reg->cep              = $request->cep;
            $reg->estado           = $request->estado;
            $reg->bairro           = $request->bairro;
            $reg->cidade           = $request->cidade;
            $reg->endereco         = $request->endereco;
            $reg->status           = '1'; // Ou qualquer outro valor padrão que você queira usar

            // Salvando o registro no banco de dados
            $reg->save();

            // Retornando uma resposta JSON com os dados salvos
            return response()->json([
                'success' => true,
                'message' => 'Registro salvo com sucesso!',
                'data'    => $reg
            ], 201); // Código de status HTTP 201 para criação bem-sucedida

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tratamento de erros de validação
            return response()->json([
                'success' => false,
                'message' => 'Erro na validação dos dados.',
                'errors'  => $e->errors()
            ], 422); // Código de status HTTP 422 para erro de validação

        } catch (\Exception $e) {
            // Tratamento de outros tipos de erros
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao salvar o registro.',
                'error'   => $e->getMessage()
            ], 500); // Código de status HTTP 500 para erro interno do servidor
        }
    }
    /**************************************************************************************/













    /**************************************************************************************/
    /**************************************************************************************/
    /**************************************************************************************/
    /**************************************************************************************/
    /**************************************************************************************/

    public function update(Request $request)
    {
        // Apenas para depuração: exibe todos os dados recebidos
        //return json_encode($request->id);

        $id = $request->id;
        $registro = Registration::find($id);

        if ($registro) {
            $registro->nome             = $request->nome;
            $registro->cpf              = $request->cpf;
            $registro->data_nascimento  = $request->nascimento;
            $registro->email            = $request->email;
            $registro->telefone         = $request->telefone;
            $registro->cep              = $request->cep;
            $registro->estado           = $request->estado;
            $registro->bairro           = $request->bairro;
            $registro->cidade           = $request->cidade;
            $registro->endereco         = $request->endereco;

            $registro->save();

            return response()->json(['success' => true, 'message' => 'Registro atualizado com sucesso!']);
        }

        return response()->json(['success' => false, 'message' => 'Registro não encontrado!'], 404);
    }
    /**************************************************************************************/
    /**************************************************************************************/
    /**************************************************************************************/
    /**************************************************************************************/
    /**************************************************************************************/










    /**************************************************************************************/
    public function lista()
    {
        $reg = new Registration();
        $registros = $reg::where('status', '1')->take(500)->get();
        return view('cadastro.lista', ['registros' => $registros]);
    }
    /**************************************************************************************/



    /**************************************************************************************/
    public function Editar($id)
    {
        $reg = new Registration();
        $registro = Registration::find($id);
        if (!$registro) {
            return "registro não localizado";
        }

        return view('cadastro.editar', ['registro' => $registro]);
    }
    /**************************************************************************************/


    /**************************************************************************************/
    public function Deletar($id)
    {
        $reg = new Registration();
        $registro = Registration::find($id);
        if (!$registro) {
            return "registro não localizado";
        } else {

            $registro->status = 0;
            $registro->save();

            return redirect()->route('cadastro.lista');
        }
    }
    /**************************************************************************************/




    /**************************************************************************************/

    public function pesquisar(Request $request)
    {
        $pesquisa = $request->input('pesquisando');


        // Gerar um identificador único para o visitante não logado
        $visitorId =  $uuid = (string) \Str::uuid();
        if (!$visitorId) {
            $visitorId = uniqid('visitor_', true);
            session()->put('visitor_id', $visitorId);
        }


        // Consulta aos resultados
        $resultado = Registration::where(function ($query) use ($pesquisa) {
            $query->where('nome', 'LIKE', "%{$pesquisa}%")
                ->orWhere('cpf', 'LIKE', "%{$pesquisa}%")
                ->orWhere('email', 'LIKE', "%{$pesquisa}%")
                ->orWhere('endereco', 'LIKE', "%{$pesquisa}%")
                ->orWhere('id', 'LIKE', "%{$pesquisa}%");
        })->get();

        // Obtém o nome do usuário da sessão ou qualquer outra informação
        $user = Auth::user();
        $username = $user ? $user->name : $visitorId;
        $filename = "pesquisa_{$username}_" . date('Ymd') . '_' . $_SERVER['REMOTE_ADDR'] . '.pdf';

        // Gera o PDF a partir da visualização
        $pdf = Pdf::loadView('pdf.pesquisa', ['resultados' => $resultado]);

        // Caminho onde o PDF será salvo
        $path = storage_path('app/public/pdf/' . $filename);

        // Verifica se o diretório existe, se não, cria
        $directory = dirname($path);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Salva o PDF
        $pdf->save($path);

        // Retorna a resposta JSON
        return response()->json(['success' => true, 'pesquisa' => $resultado, 'filename' => asset('storage/pdf/') . "/" . $filename]);
    }
    /**************************************************************************************/
}
