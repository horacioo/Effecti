@extends('cadastro.layout.layout')

@section('header')
   
    <script>
        var salvarUrl = "{{ route('salvarCadastro') }}";
        var UrlEmail = "{{ route('VerificaEmail') }}";
        var UrlVerificaCpf = "{{ route('VerificaCpf') }}";
    </script>
@endsection

@section('content')
    <table>
        <thead>
            <td>nome</td>
            <td>cpf</td>
            <td>email</td>
            <td>cep</td>
            <td>telefone</td>
            <td> - </td>
        </thead>
        <tbody>
            @foreach ($registros as $registro)
                <tr>
                    <td>{{ $registro->nome }} </td>
                    <td>{{ $registro->cpf }} </td>
                    <td>{{ $registro->email }} </td>
                    <td>{{ $registro->cep }} </td>
                    <td>{{ $registro->telefone }} </td>
                    <td> <a class='buttom' href="{{route('cadastro.lista.editar',[$registro->id])}}">Editar</a> </td>
                </tr>
            @endforeach
        </tbody>
    </table>
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
