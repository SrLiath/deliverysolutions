<!DOCTYPE html>
<html>
<head>
  <title>Chat Real Time</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
    }

    .chat-container {
      position: relative;
      height: 100%;
    }

    .message-container {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .message {
      margin: 10px;
    }

    .message-left {
      align-self: flex-start;
    }

    .message-right {
      align-self: flex-end;
    }

    .balloon-left {
      background-color: #f1f0f0;
      color: #000;
      border-radius: 10px;
      padding: 10px;
      max-width: 70%;
    }

    .balloon-right {
      background-color: #007bff;
      color: #fff;
      border-radius: 10px;
      padding: 10px;
      max-width: 70%;
      float: right;
    }

    .name {
      font-weight: bold;
      margin-bottom: 5px;
    }

    .header {
      position: absolute;
      top: 10px;
      left: 10px;
    }

    .input-container {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      margin: 10px;
    }
    .chat{
        overflow-y: auto;
        height: 90vh;
    }
  </style>
</head>
<body>
<div class="chat">

<div class="chat-messages">
      <!-- Adicione mais mensagens conforme necessário -->
    </div>

    <div class="input-container">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Digite sua mensagem">
        <div class="input-group-append">
          <button class="btn btn-primary" type="button">Enviar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
       $(document).ready(function() {
        <?php foreach($chat as $chat): ?> 
        $('.chat').append(`
        <div class="message-container">
            <div class="message message-<?php if ($chat->receiver == "sac") {
                echo "left";
            } else {
                echo "right";
            }?>">
                <div class="name"><?= esc($chat->nome) ?></div>
                <div class="balloon-<?php if ($chat->receiver == "sac") {
                    echo "left";
                } else {
                    echo "right";
                }?>"><?= esc($chat->message) ?></div>
            </div>
        </div>
        `);
        <?php endforeach ?>
    });
    
    $('button.btn-primary').click(function() {
    var mensagem = $('input.form-control').val(); // Obter o valor do campo de entrada
    var urlAtual = window.location.href;

      // Enviar dados via AJAX
      $.ajax({
        url: urlAtual, // Substitua pela sua URL de destino
        method: 'POST', // Ou use 'GET', dependendo da sua necessidade
        data: { mensagem: mensagem, choice: 'enviar'}, // Os dados que você deseja enviar
        success: function(response) {
          // Executar ações após o sucesso do envio
          $('.chat').append(response);
        },
        error: function(xhr, status, error) {
          // Trate o erro, se necessário
          console.log(error);
          console.log(xhr.responseText);

        }
      });
    });

  function atualizarChat() {
    var urlAtual = window.location.href;
    var dados = {choice : 'receber'};
    $.ajax({
      url: urlAtual,
      type: 'POST',
      data: dados,
      success: function(response) {
        // Atualize o chat com as novas mensagens
        if (response == "") {
          // Nada a fazer se não houver resposta
        } else {
          $('.chat').append(response);
        }
      },
      error: function(xhr, status, error) {
        // Trate o erro, se necessário
        console.log(error);
        console.log(xhr.responseText);
      }
    });
  }

  // Atualiza o chat a cada 5 segundos
  setInterval(atualizarChat, 5000);
</script>
</body>
</html>
