@extends('cadastro.layout.layout')

@section('header')
    
    <script>
        var salvarUrl = "{{ route('salvarCadastro') }}";
        var UrlEmail = "{{ route('VerificaEmail') }}";
        var UrlVerificaCpf = "{{ route('VerificaCpf') }}";
    </script>
@endsection

@section('content')
    <form action="" id="cadastro">
        <label>Nome: <input required type="text" name="nome" /></label>
        <label>Cpf: <input  required type="text" name="cpf" id="cpf" /></label>
        <label>Data De Nascimento: <input  required type="date" name="nascimento" /></label>
        <label>E-mail: <input  required  type="email" id="email" name="email" />
            <div class='alerta'></div>
        </label>
        <label>Telefone: <input required  type="tel" name="telefone" /></label>
        <label>Cep: <input required  type="text" id='cep' name="cep" /></label>
        <label>endereço: <input required  type="text" id="endereco" name="endereco" /></label>
        <label>bairro: <input required  type="text" id="bairro" name="bairro" /></label>
        <label>cidade: <input  required type="text" id="cidade" name="cidade" /></label>
        <label>estado: <input  required type="text" id="estado" name="estado" /></label>
        <input class='buttom' type="submit" value="Cadastrar">
    </form>
    <div id="resposta"></div>
@endsection


@section('footer')
    <footer>apenas um teste</footer>
    <script src={{ asset('js/salva.min.js') }}></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src={{ asset('js/getCEp.min.js') }}></script>
    <script src={{ asset('js/verificaEmail.js') }}></script>
    <script src={{ asset('js/verificaCpf.min.js') }}></script>
@endsection
