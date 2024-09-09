<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 14px;
        color: #333;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f4f4f4;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #eaeaea;
    }
</style>
<table>
    <tbody>
        @foreach ($resultados as $resultado)
            <tr>
                <td>{{ $resultado->nome }}</td>
                <td>{{ $resultado->email }}</td>
                <td>{{ $resultado->cpf }}</td>
                <td>{{ $resultado->cep }}</td>
                <td>{{ $resultado->telefone }}</td>
                <td>{{ $resultado->data_nascimento }}</td>
                <td>{{ $resultado->endereco }}, {{ $resultado->bairro }}, {{ $resultado->cidade }},
                    {{ $resultado->estado }}, </td>
            </tr>
        @endforeach
    </tbody>
</table>
