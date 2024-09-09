@extends('cadastro.layout.layout')

@section('header')
    <script>
        var pesquisaURL = "{{ route('pesquisa') }}";
    </script>
@endsection

@section('content')
    bem vindo!
@endsection


@section('footer')
    <footer>
        <div id='pdfExport'><a id="linkPdf" download="">exportar pdf</a></div>
    </footer>

    <script>
       
    </script>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

       
        <script src="{{ asset('js/identificador.min.js') }}"></script>
@endsection
