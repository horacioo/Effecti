@extends('cadastro.layout.layout')

@section('header')
    <script>
        var pesquisaURL = "{{ route('pesquisa') }}";
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
        <div id='pdfExport'><a id="linkPdf" download="">exportar pdf</a></div>
    </footer>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src={{ asset('js/pesquisa.min.js') }}></script>
@endsection
