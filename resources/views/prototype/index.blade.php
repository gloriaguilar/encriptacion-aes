<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/fontawesome.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>      
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="  crossorigin="anonymous"></script>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    </head>
    <body class="bg-light">
        <div class="container">   
          <div class="row">
            @if (session('message'))
              <div class="alert alert-danger">
                    {{ session('message') }}
              </div>
            @endif
            <div class="col-lg-12 mt-4">
              <h4 class="mb-3">{{ __('Generar llave nueva') }}</h4>
              <div class="alert alert-primary" role="alert">
               Llave generada:<br> <span class="font-weight-bold" id="new-generated-key"></span>
            </div>
              <form class="needs-validation generate-key" method="POST" action="{{ route('generateKey') }}">
                @csrf
                <div class="mb-3">
                  <label>Metodo de Encriptación</label>
                  <div class="input-group">
                    <select name="method" id="method" class="form-control">
                        <option value="sha512">OpenSSL Default Value (1024 bits)</option>
                    </select>
                  </div>
                </div>
                   
                <hr class="mb-12">
                <div class="row">
                    <div class="col-lg-12">
                        <button class="btn btn-primary btn-lg btn-block"  type="submit">{{ __('Generar llaves') }}</button>
                    </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <hr>
        <div class="container">   
          <div class="row">
            <div class="col-lg-12">
              <h4 class="mb-3">{{ __('Cadena a encriptar') }}</h4>
              <div class="alert alert-success" role="alert">
                Cadena encriptada: <br>
                <span id="string-encrypted"></span>
              </div>
              <form class="needs-validation encrypt-form" method="POST" action="{{ route('encrypt') }}">
                @csrf
                <div class="mb-3">
                  <label>Llave ingrese la llave previamente generada</label>
                  <div class="input-group">
                    <input type="text" class="form-control" id="key" placeholder="Llave de encriptación generada" name="key" required>
                  </div>
                </div>
                <div class="mb-3">
                  <label>Cadena a encriptar</label>
                  <div class="input-group">
                    <input type="text" class="form-control" id="encrypt-string" placeholder="Ingrese lo que desea encriptar" name="encrypt_string" required>
                  </div>
                </div>
                   
                <hr class="mb-12">
                <div class="row">
                    <div class="col-lg-12">
                        <button class="btn btn-success btn-lg btn-block" type="submit">{{ __('Encriptar cadena') }}</button>
                    </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <hr>
        <div class="container">   
          <div class="row">
            <div class="col-lg-12">
              <h4 class="mb-3">{{ __(' Mostrar Decrypt') }}</h4>
              <div class="alert alert-warning" role="alert">
            Cadena desencriptada <br>    <span class="font-weight-bold" id="string-decrypted"></span>
            </div>
              <form class="needs-validation decrypt-form" method="POST" action="{{ route('decrypt') }}">
                @csrf
                <div class="mb-3">
                  <label >Llave</label>
                  <div class="input-group">
                    <input type="text" class="form-control" id="key" placeholder="Llave de encriptación" name="key" required>
                  </div>
                </div>
                <div class="mb-3">
                  <label>Cadena</label>
                  <div class="input-group">
                    <input type="text" class="form-control" id="decrypt_string" placeholder="Cadena a desencriptar" name="decrypt_string" required>
                  </div>
                </div>
                <hr class="mb-12">
                <div class="row">
                    <div class="col-lg-12 mb-4">
                        <button class="btn btn-warning btn-lg btn-block" type="submit">{{ __('Desencriptar') }}</button>
                    </div>
                </div>
              </form>
            </div>
          </div>
        </div>
    </body>
    
  <script type="text/javascript">

  $().ready(function(){

    $(".generate-key").on("submit", function(e){
      e.preventDefault();
      e.stopPropagation();

      $.ajax({
        type: 'POST',
        url: '{{route('generateKey')}}',
        data: $(this).serialize(),
        
        success: function(response){
          if(response.error){
            alert("Ocurrio un error al generar la llave");
          }
          $('#new-generated-key').text(response.data);
        },
        error: function(err){
          console.error(err);
        }
      });
    });
    
    $(".encrypt-form").on("submit", function(e){
      e.preventDefault();
      e.stopPropagation();
      $.ajax({
        type: 'POST',
        url: '{{route('encrypt')}}',
        data: $(this).serialize(),
        success: function(response){
          if(response.error){
            alert("Ocurrio un error al encriptar la llave");
          }
          $('#string-encrypted').text(response.data);
        },
        error: function(err){
          console.error(err);
        }
      });
    });

    $(".decrypt-form").on("submit", function(e){
      e.preventDefault();
      e.stopPropagation();
      $.ajax({
        type: 'POST',
        url: '{{route('decrypt')}}',
        data: $(this).serialize(),
        success: function(response){
          if(response.error){
            alert("Ocurrio un error al desencriptar la llave");
          }
          $('#string-decrypted').text(response.data);
        },
        error: function(err){
          console.error(err);
        }
      });
    });
  });
  </script>
</html>
