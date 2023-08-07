
<style>
    .divpainel {
        width: 100%;
        height: 100%;
        margin: 0 auto;
        background-color: white;
        box-shadow: 0px 0px 10px #888888;
        overflow: auto; 
            }

            .message-balloon {
  display: inline-block;
  background-color: #f2f2f2;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 10px;
}

.message-right {
  text-align: right;
}

.message-left {
  text-align: left;
}

.message-time {
  font-size: 12px;
  color: #777;
}
.chat{
  overflow-y: auto;
    height: 74vh;
}

.btn-detalhes{
  float: right;
}
.modal-body {
  overflow-y: auto;
  overflow-x: auto;

}
</style>

<!-- Cria a div -->
<div class="container divpainel">
    <!-- Modal de post -->
    <button type="button" class="btn btn-primary" data-toggle="modal"  style="margin-top: 3px;"  data-target="#meu-modal">Postar</button>
    <!-- postagem em massa -->
    <button type="button" class="btn btn-primary" data-toggle="modal"  style="margin-top: 3px;" data-target="#massainfo">Postagem em massa</button>
    <!-- Botão que abre o modal de chat -->
    <a href="https://wa.me/551132979914" class="btn btn-primary">Contato</a>
    <!-- pedidos finalizados -->
    <button type="button" class="btn btn-primary" data-toggle="modal" style="margin-top: 3px;"  data-target="#modalFinalizados" onclick="finalizados()">Finalizados</button>
    <!-- contrato -->
    <?php if(isset($contrato)): ?>
    <a href="<?= $contrato ?>" class="btn btn-primary" style="margin-top: 3px;" >Contrato</a>
    <?php endif ?>
    <!-- requisição de dados -->
    <button type="button" class="btn btn-primary btn-detalhes" data-toggle="modal"  style="margin-top: 3px;" data-target="#detalhesModal">Relatorios</button>
<!-- modal info massa -->
<div id="massainfo" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Conteúdo do primeiro modal -->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Envio em massa</h4>
        </div>
        <div class="modal-body">  
        <form id="uploadForm" enctype="multipart/form-data">
        <input type="file" name="arquivo" accept=".xlsx, .txt">
        <input type="submit" value="Enviar">
    </form>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Abre o modal
        function openModal() {
            $('#dadosmassa').css('display', 'block');
        }

        // Fecha o modal
        function closeModal() {
            $('#dadosmassa').css('display', 'none');
        }

        // Evento para fechar o modal ao clicar no botão de fechar (X)
        $('.close').click(function() {
            closeModal();
        });

        // Evento para fechar o modal ao clicar fora da área do modal
        $(window).click(function(event) {
            if (event.target == document.getElementById('dadosmassa')) {
                closeModal();
            }
        });

        // Função para popular a tabela no modal
        function populateTable(data) {
            var tableBody = $('#dados-table tbody');
            tableBody.empty();

            $.each(data, function(index, row) {
                var newRow = $('<tr>');
                $.each(row, function(_, value) {
                    newRow.append($('<td>').text(value));
                });
                tableBody.append(newRow);
            });

            openModal();
        }

        // Envia o formulário e processa a resposta
        $('#uploadForm').submit(function(event) {
          if(confirm('Todos os dados estão corretos?')){}else{return;}
          $('#loading-overlay').show(); 
            event.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: '<?= site_url('upload/process') ?>',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                  $('#loading-overlay').hide(); 
                    alert(response);
                    location.reload();
                  
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  $('#loading-overlay').hide(); 
            console.error("Status:", jqXHR.status);
            console.error("Server Error:", jqXHR.responseText);
          }
            });
        });
    </script>

          <!-- Botão que abre o segundo modal -->
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#massa" style="float:right;">Como enviar?</button>

        </div>
      </div>

    </div>
  </div>
<!-- modal em massa -->
<div id="massa" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Conteúdo do segundo modal -->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Tutorial: Envio em massa com TXT ou XLSX</h4>
        </div>
        <div class="modal-body">
  <img src="<?= base_url('assets/img/massa.JPG') ?>" style="width: 100%;" alt="">
        <p>Para enviar um arquivo XLSX corretamente, siga as instruções abaixo:</p>
  
  <table>
    <tr>
      <th>Linha 1</th>
      <th>Linhas a diante</th>
    </tr>
    <tr>
      <td><strong>Destinatário:</strong></td>
      <td>Coloque o nome do destinatário aqui</td>
    </tr>
    <tr>
      <td><strong>Endereço:</strong></td>
      <td>Coloque o endereço aqui</td>
    </tr>
    <tr>
      <td><strong>Número:</strong></td>
      <td>Coloque o número do endereço aqui</td>
    </tr>
    <tr>
      <td><strong>Complemento:</strong></td>
      <td>Coloque o complemento do endereço aqui (opcional)</td>
    </tr>
    <tr>
      <td><strong>CEP:</strong></td>
      <td>Coloque o CEP do endereço aqui</td>
    </tr>
    <tr>
      <td><strong>Bairro:</strong></td>
      <td>Coloque o bairro aqui</td>
    </tr>
    <tr>
      <td><strong>Telefone:</strong></td>
      <td>Coloque o telefone do destinatário aqui</td>
    </tr>
    <tr>
      <td><strong>Observação:</strong></td>
      <td>Coloque alguma observação adicional aqui (opcional)</td>
    </tr>
    <tr>
      <td><strong>Cabe_moto:</strong></td>
      <td>coloque apenas sim ou não)</td>
    </tr>
  </table>
  
  <p>Lembre-se de preencher cada célula corretamente, respeitando a ordem indicada acima.</p>
<p> O envio por TXT deve ser conforme orientado na imagem. </p>  
  <p>Após preencher todas as informações necessárias no arquivo XLSX ou TXT, você estará pronto para enviá-lo.</p>
        </div>
      </div>

    </div>
</div>
<!-- Modal de Chat -->
<div id="chatModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Conteúdo do modal -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Chat</h4>
      </div>
      <div class="modal-body chat">
        <!-- Conteúdo do chat -->
        <div class="chat-container">
          <div class="chat-messages">
            <?php foreach ($chat as $mensagem): ?>
            <div class="message-<?php if($mensagem->sender == "sac") {
                echo "left";
            } else {
                echo "right";
            } ?>">
              <div class="message-balloon">
                <div class="message-text"><?= esc($mensagem->message) ?></div>
                <div class="message-time"><?= esc($mensagem->date) ?></div>
              </div>
            </div>
            <?php endforeach ?>
            <!-- Mais mensagens aqui -->
          
          </div>
        </div>
      </div>
      <div class = "modal-footer">
      <div class="chat-input">
            <input type="text" class="form-control" placeholder="Digite sua mensagem...">
            <button class="btn btn-primary" id="enviarMensagem">Enviar</button>
          </div>
          </div>
    </div>
  </div>
</div>
 <!-- Modal "Finalizados" -->
 <div class="modal fade" id="modalFinalizados" role="dialog">
    <div class="modal-dialog">
      <!-- Conteúdo do modal -->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Finalizados</h4>
        </div>
        <div class="modal-body hay" id="hay">
          <!-- O conteúdo da solicitação Ajax será exibido aqui -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Relatorio -->
  <div class="modal fade" id="detalhesModal" tabindex="-1" role="dialog" aria-labelledby="detalhesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="detalhesModalLabel">Selecionar Datas</h4>
        </div>
        <div class="modal-body">
          <label for="dataInicial">Data Inicial:</label>
          <input type="date" id="dataInicial">

          <label for="dataFinal">Data Final:</label>
          <input type="date" id="dataFinal">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnExcel">Excel</button>
          <button type="button" class="btn btn-primary" id="btnPDF">PDF</button>
        </div>
      </div>
    </div>
  </div>

<script>
function finalizados() {
        $.ajax({
          url: '<?= base_url('pedidos/finalizados') ?>',
          type: 'POST',
          dataType: 'html',
          success: function(response) {
            // Insira o resultado da solicitação Ajax no corpo do modal
            $('.hay').html(response);
          },
          error: function() {
            alert('Erro ao carregar os dados.');
          }
        });
      };
      var dataInicial = null;
      var dataFinal = null;

      // Captura as datas selecionadas dos calendários
      $("#dataInicial").on("change", function() {
        dataInicial = $(this).val();
      });

      $("#dataFinal").on("change", function() {
        dataFinal = $(this).val();
      });

      // Envio dos dados para o PHP
      $("#btnExcel").on("click", function() {
        enviarRelatorio("excel");
      });

      $("#btnPDF").on("click", function() {
        enviarRelatorio("pdf");
      });

      function enviarRelatorio(formato) {
        // Verifica se as datas foram selecionadas
        if (dataInicial && dataFinal) {
          var url = "<?= base_url('painel/dados/') ?>" + dataInicial + "/" + dataFinal + "/" + formato;
          window.location.href = url;
        } else {
          alert("Por favor, selecione as datas.");
        }
      }
       
     function atualizarChat() {
        var dados = {choice : 'receber'};
    $.ajax({
      url: '<?= base_url('sac')?>',
      type: 'POST',
      data: dados,
      success: function(response) {
        // Atualize o chat com as novas mensagens
        if (response == ""){
      }else
      {$('.chat-messages').append(response);
        }},
      error: function(xhr, status, error) {
        // Trate o erro, se necessário
        console.log(error);
        console.log(xhr.responseText);
      }
    });
  }

  // Atualize o chat imediatamente quando o modal for aberto
  $('#chatModal').on('shown.bs.modal', function() {
    atualizarChat();
  });

  // Atualize o chat a cada 5 segundos
  setInterval(function() {
    atualizarChat();
  }, 2000);

  $('#enviarMensagem').click(function() {
    // Obtenha o valor da mensagem digitada
    var mensagem = $('.chat-input input').val();

    // Certifique-se de que a mensagem não está vazia
    if (mensagem !== '') {
      // Crie um objeto de dados para enviar via AJAX
      var dados = {
        choice : 'envio',
        mensagem: mensagem
      };
      // Envie a mensagem para o arquivo PHP usando AJAX
      $.ajax({
        url: '<?= base_url('sac')?>',
        type: 'POST',
        data: dados,
        success: function(response) {
          // Limpe o campo de entrada de texto
          $('.chat-input input').val('');

          // Atualize o chat com a nova mensagem
          $('.chat-messages').append(response);
        },
        error: function(xhr, status, error) {
          // Trate o erro, se necessário
          console.log(error);
          console.log(xhr.responseText);

        }
      });
    }
  });
</script>

    <!-- Cria a tabela de dados -->
    <table class="table">
    <thead>
        <tr>
            <th>Destinatário</th>
            <th>Endereço</th>
            <th>Número</th>
            <th>Complemento</th>
            <th>CEP</th>
            <th>Bairro</th>
            <th>Telefone</th>
            <th>Observação</th>
            <th>Rastreio</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?= esc($pedido->destinatario) ?></td>
                <td><?= esc($pedido->endereco) ?></td>
                <td><?= esc($pedido->numero) ?></td>
                <td><?= esc($pedido->complemento) ?></td>
                <td><?= esc($pedido->cep) ?></td>
                <td><?= esc($pedido->bairro) ?></td>
                <td><?= esc($pedido->telefone) ?></td>
                <td><?= esc($pedido->observacao) ?></td>
                <td><?= esc($pedido->rastreio) ?></td>
                <td><?= esc($pedido->status) ?></td>
                <td><a type="button" class="btn btn-primary" href="<?= base_url('pedidos/pdf/' . $pedido->rastreio)?>">PDF</a></td>
                <?php if($pedido->status == "Aguardando confirmação"): ?>
                <td><a type ="button" class="btn btn-danger" onclick="deletePedido('<?= $pedido->_id ?>')">Cancelar</a></td>
                <?php else: ?>
                <td></td>
                <?php endif ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    <!-- Cria o modal -->
    <div id="meu-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Conteúdo do modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Postagem</h4>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" id="form">
                        <label for="arquivo">Insira automaticamente por PDF:</label>
                        <input type="file" name="arquivo" id="arquivo"  accept=".pdf">
                    </form>
                    <form id="post">
                    <div class="mb-3">
                        <label for="cabeMoto" class="form-label">Cabe em uma moto?</label><br>
                        <input type="radio" id="cabeMotoSim" name="cabeMoto" value="sim" required>
                        <label for="cabeMotoSim">Sim</label><br>
                        <input type="radio" id="cabeMotoNao" name="cabeMoto" value="nao" required>
                        <label for="cabeMotoNao">Não</label>
                    </div>
                    <div class="mb-3">
                        <label for="destinatario" class="form-label">Destinatario:</label>
                        <input type="text" class="form-control" id="destinatario" name="destinatario" required>
                    </div>
                    <div class="mb-3">
                        <label for="emaildest" class="form-label">Email destinatario:</label>
                        <input type="text" class="form-control" id="emaildest" name="emaildest" required>
                    </div>
                    <div class="mb-3">
                        <label for="cep" class="form-label">CEP:</label>
                        <input type="text" class="form-control" id="cep" name="cep" maxlength="8" required>
                    </div>
                    <div class="mb-3">
                        <label for="endereco" class="form-label">Endereco:</label>
                        <input type="text" class="form-control" id="endereco" name="endereco" required>
                    </div>
                        <div class="mb-3">
                        <label for="numero" class="form-label">Numero:</label>
                        <input type="text" class="form-control" id="numero" name="numero" required>
                    </div>
                        <div class="mb-3">
                        <label for="complemento" class="form-label">Complemento:</label>
                        <input type="text" class="form-control" id="complemento" name="complemento">
                    </div>
                        <div class="mb-3">
                        <label for="bairro" class="form-label">Bairro:</label>
                        <input type="text" class="form-control" id="bairro" name="bairro" required>
                    </div>
                        <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone:</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" required>
                    </div>
                        <div class="mb-3">
                        <label for="observacao" class="form-label">Observação:</label>
                        <input type="text" class="form-control" id="observacao" name="observacao">
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="checkbox2" onchange="toggleInput(this)">
                      <label class="form-check-label" for="checkbox2">
                        Valor declarado?
                      </label>
                      <div id="declared" class="mt-2">
                        <input type="number" id="declal" name="declal" class="form-control" placeholder="Valor em R$" style="display: none;">
                      </div>
                    </div>

<script>
  function toggleInput(checkbox) {
    var input = document.getElementById("declared").querySelector("input");
    if (checkbox.checked) {
      input.style.display = "block";
    } else {
      input.style.display = "none";
      input.value = "";
    }
  }
</script>

                    </div>
                    <div class="modal-footer">
                    <button type="submit" class="btn btn-default" >Confirmar</button>
                    </div>
                    </form>
                    </div>
                    </div>
                    </div>

                    </div>
<script>
 function deletePedido(id){
  if(confirm('Quer deletar essa solicitação?')){}else{
    return;
  }
  $.ajax({
    url: '<?= base_url('post/delete') ?>',
    type: 'POST',
    data: {id:id},
    success: function(response){
      location.reload();
    },
    error: function(xhr, status, error) {
      console.log("Erro ao deletar: " + xhr.responseText);
    }
  })
}


    function preencherEndereco(cep) {
  $.ajax({
    url: 'https://viacep.com.br/ws/' + cep + '/json/',
    type: 'GET',
    dataType: 'json',
    success: function(response) {
      if (!response.erro) {
        $('#endereco').val(response.logradouro);
        $('#bairro').val(response.bairro);
      }
    },
    error: function(xhr, status, error) {
      console.log(xhr.responseText);
    }
  });
}

$('#cep').on('blur', function() {
  var cep = $(this).val().replace(/\D/g, '');
  if (cep.length === 8) {
    preencherEndereco(cep);
  }
});
    $(function() {
        $(document).ajaxStart(function() {
            // Exibe o overlay de loading quando a requisição AJAX começa
        }).ajaxStop(function() {
            // Oculta o overlay de loading quando a requisição AJAX termina
        });

        $('#arquivo').change(function() {
            var formData = new FormData();
            formData.append('arquivo', $('input[type="file"]')[0].files[0]);
            $.ajax({
                url: '<?= base_url('post') ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    $('#destinatario').val(response.destinatario);
                    $('#endereco').val(response.endereco);
                    $('#numero').val(response.numero);
                    $('#complemento').val(response.complemento);
                    $('#cep').val(response.cep);
                    $('#bairro').val(response.bairro);
                    $('#telefone').val(response.telefone);
                    $('#observacao').val(response.observacao);
                },
                error: function(xhr, status, error) {
                    console.log("Erro ao enviar o arquivo: " + xhr.responseText);
                }
            });
        });
    });
    $('#post').submit(function(event) {
        event.preventDefault(); // Evita o envio normal do formulário

        // Dados do formulário
        var formData = $(this).serialize();

        // Envia a requisição Ajax
        $.ajax({
            type: 'POST',
            url: '<?= base_url('post') ?>',
            data: formData,
            success: function(response) {
                // Lida com a resposta da página
            console.log(response);
            if (response == "cadastrado"){
              location.reload();
            }
            // Faça algo com a resposta aqui
            },
            error: function(xhr, status, error) {
            // Lida com erros de requisição
            console.log(xhr.responseText);
            }
            });
            });

</script>