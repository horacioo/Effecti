@extends('cadastro.layout.layout')

@section('header')
    <script>
        var pesquisaURL = "{{ route('pesquisa') }}"; /****Essa rota é a rota da api que será acessada quando eu for pesquisar****/
        var urlEditarRegistro = "{{ route('home') }}/cadastros/editar/" 

        console.log(urlEditarRegistro);
    </script>
    
@endsection

@section('content')
    <form action="" id="Pesquisa">
        <label>Pesquisar: <input type="text" id="pesquisa" name="pesquisando" /></label>
    </form>
    <table id="resultados">
        <tbody></tbody>
    </table>



    <div id="resposta"></div>
@endsection


@section('footer')
    <footer>
        <section id="exportar">
            Escolha um formato de arquivo e exporte os dados 
            <div id='pdfExport'><a class='icon1' id="linkPdf" download=""></a></div>
            <div id='exportCSV'><a class='icon2' id="linkCSV" download=""></a></div>
            <div id='exportXLS'><a class='icon3' id="linkXLS" download=""></a></div>
        </section>
    </footer>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src={{ asset('js/pesquisa.min.js') }}></script>
@endsection
