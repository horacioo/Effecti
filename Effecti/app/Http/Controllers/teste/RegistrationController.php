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






    public function verificaEmail(Request $request)
    {
        $email = $request->input('email');
        $reg = Registration::where('email', $email)
            ->when($request->has('id'), function ($query) use ($request) {
                return $query->where('id', '!=', $request->input('id'));
            })
            ->first();

        if ($reg) {
            return response()->json(['informacao' => 'Email encontrado']);
        } else {
            return response()->json(['informacao' => 'Email não encontrado']);
        }
    }





    public function validaCPF(Request $request)
    {
        $cpf = $request->input('cpf');
        $id = $request->input('id', null);

        if (!$this->validaCPFunico($cpf, $id)) {
            return response()->json(['informacao' => 'CPF já existe!']);
        }

        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) != 11) {
            return response()->json(['informacao' => 'CPF inválido']);
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return response()->json(['informacao' => 'CPF inválido']);
        }

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





    private function validaCPFunico($cpf, $id = null)
    {
        if ($id) {
            $registro = Registration::where('cpf', $cpf)->where('id', '!=', $id)->first();
        } else {
            $registro = Registration::where('cpf', $cpf)->first();
        }

        return !$registro;
    }








    public function Salva(Request $request)
    {
        try {
            $reg = new Registration();
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
            $reg->status = '1';

            $reg->save();

            return response()->json([
                'success' => true,
                'message' => 'Registro salvo com sucesso!',
                'data' => $reg
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro na validação dos dados.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao salvar o registro.',
                'error' => $e->getMessage()
            ], 500);
        }
    }








    public function update(Request $request)
    {
        $id = $request->id;
        $registro = Registration::find($id);

        if ($registro) {
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

            $registro->save();

            return response()->json(['success' => true, 'message' => 'Registro atualizado com sucesso!']);
        }

        return response()->json(['success' => false, 'message' => 'Registro não encontrado!'], 404);
    }








    public function lista()
    {
        $registros = Registration::where('status', '1')->take(20)->get();
        return view('cadastro.lista', ['registros' => $registros]);
    }





    public function Editar($id)
    {
        $registro = Registration::find($id);
        if (!$registro) {
            return "registro não localizado";
        }
        return view('cadastro.editar', ['registro' => $registro]);
    }










    public function Deletar($id)
    {
        $registro = Registration::find($id);
        if (!$registro) {
            return "registro não localizado";
        } else {
            $registro->status = 0;
            $registro->save();
            return redirect()->route('cadastro.lista');
        }
    }






    public function pesquisar(Request $request)
    {
        $pesquisa = $request->input('pesquisando');
        $filename = $request->input('key') ? substr($request->input('key'), 6, 16) . ".pdf" : $_SERVER['REMOTE_ADDR'] . ".pdf";
        $visitorId = (string) \Str::uuid();
        session()->put('visitor_id', $visitorId);

        $resultado = Registration::where(function ($query) use ($pesquisa) {
            $query->where('nome', 'LIKE', "%{$pesquisa}%")
                ->orWhere('cpf', 'LIKE', "%{$pesquisa}%")
                ->orWhere('email', 'LIKE', "%{$pesquisa}%")
                ->orWhere('endereco', 'LIKE', "%{$pesquisa}%")
                ->orWhere('id', 'LIKE', "%{$pesquisa}%");
        })->get();


        $NomeArquivo = explode(".",$filename);
        $this->NomeArquivo = $NomeArquivo[0];

        $arquivoCSV = $this->gerarCSV($resultado, $filename);
        $xls        = $this->gerarXLS($resultado,"novo");

        $pdf = PDF::loadView('pdf.pesquisa', ['resultados' => $resultado]);

        $path = storage_path('app/public/pdf/' . $filename);
        $directory = dirname($path);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $pdf->save($path);

        return response()->json([
            'primeiroFileName' => $this->NomeArquivo,
            'success' => true,
            'pesquisa' => $resultado,
            'arquivocsv' => asset($arquivoCSV),
            'filename' => asset('storage/pdf/') . "/" . $filename,
            'arquivoxls' => asset('') . "" . $xls
        ]);
    }










    private function gerarCSV($dados, $fileName)
    {
        $data = [];
        $linha = 0;

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

        $arquivo = storage_path('app/public/csv/' . $this->NomeArquivo.".csv");

        $fp = fopen($arquivo, 'w');

        fputcsv($fp, ['id', 'Nome', 'Cpf', 'Data de Nascimento', 'E-mail', 'Telefone', 'Cep', 'Estado', 'Bairro', 'Cidade', 'Endereco', 'Status']);
    
        foreach ($data as $linha) {
            fputcsv($fp, $linha);
        }

        fclose($fp);

        return 'storage/csv/' . $this->NomeArquivo;
    }

    
    












    private function gerarXLS($dados, $fileName)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
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
    
        $row = 2;
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
    
        $writer = new Xlsx($spreadsheet);
    
        // Caminho do diretório onde o arquivo será salvo
        $path = storage_path('app/public/xlsx/');
    
        // Verifica se o diretório existe, se não existir, cria-o
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    
        // Salva o arquivo XLSX
        $filePath = $path . $this->NomeArquivo . '.xlsx';
        $writer->save($filePath);
    
        return 'storage/xlsx/' . $this->NomeArquivo . '.xlsx';
    }

}