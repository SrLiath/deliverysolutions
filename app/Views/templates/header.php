<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Time Express Solutions</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <style>
    body {
      background: url('<?= base_url('') ?>assets/img/back.jpg') no-repeat center center fixed;
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
      padding: 50px;
      overflow-x: hidden;
    }

    #login-dp {
      min-width: 250px;
      padding: 14px 14px 0;
      overflow: hidden;
      background-color: rgba(255, 255, 255, .8);
    }

    #login-dp .help-block {
      font-size: 12px
    }

    #login-dp .bottom {
      background-color: rgba(255, 255, 255, .8);
      border-top: 1px solid #ddd;
      clear: both;
      padding: 14px;
    }

    #login-dp .social-buttons {
      margin: 12px 0
    }

    #login-dp .social-buttons a {
      width: 49%;
    }

    #login-dp .form-group {
      margin-bottom: 10px;
    }

    .btn-fb {
      color: #fff;
      background-color: #3b5998;
    }

    .btn-fb:hover {
      color: #fff;
      background-color: #496ebc
    }

    .btn-tw {
      color: #fff;
      background-color: #55acee;
    }

    .btn-tw:hover {
      color: #fff;
      background-color: #59b5fa;
    }

    @media(max-width:768px) {
      #login-dp {
        background-color: inherit;
        color: #fff;
      }

      #login-dp .bottom {
        background-color: inherit;
        border-top: 0 none;
      }
    }
    #loading-overlay {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(0, 0, 0, 0.5);
          z-index: 9999;
          display: flex;
          justify-content: center;
          align-items: center;
        }

        #loading-spinner {
          width: 50px;
          height: 50px;
          border: 4px solid #f3f3f3;
          border-top: 4px solid #3498db;
          border-radius: 50%;
          animation: spin 2s linear infinite;
        }

        @keyframes spin {
          0% {
            transform: rotate(0deg);
          }
          100% {
            transform: rotate(360deg);
          }
        }
  </style>
</head>
<body>
<div id="loading-overlay" style="display: none;">
  <div id="loading-spinner"></div>
</div>
<script>  $('#loading-overlay').show(); </script>
  <nav class="navbar navbar-default navbar-inverse" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Real Time Express Solutions</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?= base_url('') ?>">Real Time Express Solutions</a>
      </div>
  
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="https://rtes.com.br">Instituncional</a></li>
          <li><a href="<?= base_url('rastreio') ?>">Rastreio</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Faça login<span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="<?= base_url('cliente')?>">Login clientes</a></li>
              <li class="divider"></li>
              <li><a href="<?= base_url('entregadorl')?>">Login entregadores</a></li>
              <li class="divider"></li>
              <li><a href="<?= base_url('enter')?>">Login colaboradores</a></li>
            </ul>
          </li>
        </ul>
        <?php if (isset($cliente)){ ?>
          <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown"><b><?= $cliente ?></b> <span class="caret"></span></a>
        <ul id="login-dp" class="dropdown-menu">
          <li>
          <a href="<?= base_url('dados') ?>" class="btn btn-secondary btn-block">Dados</a>
          <a href="<?= base_url('pedidos') ?>" class="btn btn-secondary btn-block" style="margin-bottom: 10px;">Pedidos</a>
             <div class="row">
                <div class="bottom text-center">
                  <a href="<?= base_url('clean')?>"><b>Sair</b></a>
                </div>
             </div>
          </li>
        </ul>
        <?php }else{?>
        <ul class="nav navbar-nav navbar-right">
          <li><p class="navbar-text"> <a href="<?= base_url('cadastrar')?>">Não possui uma conta?</a></p></li>
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown"><b>Login</b> <span class="caret"></span></a>
        <ul id="login-dp" class="dropdown-menu">
          <li>
             <div class="row">
                <div class="col-md-12">
                   <form class="form" role="form" method="post" action="login" accept-charset="UTF-8" id="login-nav">
                      <div class="form-group">
                         <label class="sr-only" for="documento">Documento</label>
                         <input type="input" class="form-control" id="documento" placeholder="CPF/CNPJ" required>
                      </div>
                      <div class="form-group">
                         <label class="sr-only" for="inputpass">Senha</label>
                         <input type="password" class="form-control" id="inputpass" placeholder="Password" required>
                                               <div class="help-block text-right"><a href="<?= base_url('esqueci') ?>">Esqueceu sua senha?</a></div>
                      </div>
                      <div class="form-group">
                         <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                      </div>
                   </form>
                </div>
                <div class="bottom text-center">
                  Sem cadastro ? <a href="<?= base_url('cadastrar')?>"><b>Cadastrar</b></a>
                </div>
             </div>
          </li>
        </ul>
        <?php } ?>
          </li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
<script>
$(document).ready(function() {
  $('#loading-overlay').hide();
  $('#login-nav').submit(function(event) {
    // Impedir que o formulário seja enviado normalmente
    event.preventDefault();

    // Obter os dados do formulário
    var documento = $('#documento').val();
    var password = $('#inputpass').val();

    // Enviar uma solicitação AJAX
    $.ajax({
      url: '<?= base_url('login'); ?>',
      type: 'POST',
      cache: false,
      data: {
        documento: documento,
        password: password
      },
      dataType: 'json',
      success: function(response) {
        // Manipular a resposta do servidor
        if (response == "logado") {
          window.location.href = "<?= base_url('pedidos') ?>";
        } else {
          alert(response);
        }
      },
      error: function(xhr, status, error) {
        // Manipular erros
        alert("contate um administrador do sistema");
        console.log(xhr);
      }
    });
  });
});


</script>
