   <div class="container">
    <div class="row">
      <div class="col-md-4 col-md-offset-4 center-div">
        <form>
          <div class="form-group">
            <label for="cnpj_cpf">Insira seu CNPJ/CPF:</label>
            <input type="text" class="form-control" id="cnpj_cpf" placeholder="Insira seu CNPJ/CPF">
          </div>
          <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
      </div>
    </div>
  </div>

  <script>
$(document).ready(function() {
  $('form').submit(function(event) {
    $('#loading-overlay').show();
    event.preventDefault(); // Evita o envio do formulário

    // Obtém o valor do input
    var cnpj_cpf = $('#cnpj_cpf').val();

    // Envia a solicitação AJAX
    $.ajax({
      url: '<?= base_url('esqueci') ?>',
      method: 'POST',
      data: { cnpj_cpf: cnpj_cpf },
      success: function(response) {
        // Atualiza o conteúdo da div com a resposta
        $('#cnpj_cpf').val(response);
        $('label[for="cnpj_cpf"]').text(response);
        $('#loading-overlay').hide();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        // Imprime o erro no console
        console.error("Status:", jqXHR.status);
        console.error("Erro do servidor:", jqXHR.responseText);
        $('#loading-overlay').hide();
        alert('Ocorreu um erro durante a solicitação ao servidor.');
      }
    });
  });
});

</script>
