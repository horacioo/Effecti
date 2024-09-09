<?php

namespace App\Http\Controllers\teste;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;



class PDFController extends Controller
{
    public function gerarPDF()
    {
        // Dados que você deseja passar para a view
        $data = [
            'title' => 'Exemplo de PDF'
        ];

        // Gera o PDF usando a view 'pdf'
        $pdf = PDF::loadView('pdf.inf', $data);

        // Retorna o PDF para ser baixado
        return $pdf->download('arquivo.pdf');

        // Ou para exibir no navegador
        // return $pdf->stream();
    }
}
