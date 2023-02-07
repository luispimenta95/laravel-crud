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
        <div class="row">
        @if (isset($users))
            <div class="col-md-3">
                    <p>Selfie:</p>
                    <img src="/selfie/{{ $users->selfie }}" class="img-responsive" style="max-height: 100px; max-width: 100px;" alt="" srcset="">
                    <br>
        @endif
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <h3 class="text-center text-default"><b>Gerenciar Propietário</b> </h3>
                <div class="form-group">
                    <form action="/post" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="nome" class="form-control m-2" placeholder="Nome do usuário"  value="{{ isset($users->nome) ? $users->nome : ''  }}">
                        <input type="text" name="cpf" class="form-control m-2" placeholder="CPF/CNPJ"  value="{{ isset($users->cpf_cnpj) ? $users->cpf_cnpj : ''  }}">

                        <input type="hidden" name="id" value="{{ isset($users->id) ? $users->id : ''  }}" />

                        <label class="m-2">Selfie</label>
                        <input type="file" id="input-file-now-custom-3" class="form-control m-2" name="selfie">



                        <button type="submit" class="btn btn-danger mt-3 ">Submit</button>
                    </form>
                </div>
            </div>
        </div>



</body>

</html>