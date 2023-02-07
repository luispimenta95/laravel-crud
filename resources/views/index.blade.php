<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laravel CRUD With Multiple Image Upload</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <!-- Font-awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>

<body>

  <div class="container" style="margin-top: 50px;">

    <a href="/create" class="btn btn-outline-success">Novo usuário </a>
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>CPF/CNPJ</th>
          <th>Editar</th>
          <th>Excluir</th>
        </tr>
      </thead>
      <tbody>


        @foreach ($users as $user)
        <tr>
          <th scope="row">{{ $user->id }}</th>
          <td>{{ $user->nome }}</td>
          <td>{{ $user->cpf_cnpj }}</td>          <td>
            <form action="/edit/" method="post">
              <input type="hidden" name="id" value="{{ $user->id }}" />
              <button class="btn btn-outline-primary" type=" submit">Editar</button>
              @csrf

            </form>
          </td>
          <td>
            <form action="/delete/" method="post">
              @csrf
              <input type="hidden" name="id" value="{{ $user->id }}" />
              <button type="submit" class="btn btn-xs btn-danger btn-flat show-alert-delete-box btn-sm" data-toggle="tooltip" title='Delete'>Delete</button>
            </form>
          </td>

        </tr>
        @endforeach

      </tbody>
    </table>
  </div>




</body>

</html>