<div class="container">
    <div class="row">
      <div class="col-md-4 col-md-offset-4 center-div">
        <form id="senhaForm">
          <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" class="form-control" id="senha" placeholder="Digite a senha">
          </div>
          <div class="form-group">
            <label for="repetirSenha">Repetir Senha:</label>
            <input type="password" class="form-control" id="repetirSenha" placeholder="Digite novamente a senha">
          </div>
          <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
      </div>
    </div>
  </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  $('#senhaForm').submit(function(event) {
    event.preventDefault(); // Evita o envio do formulário

    var senha = $('#senha').val();
    var repetirSenha = $('#repetirSenha').val();

    // Verifica se as senhas são iguais
    if (senha !== repetirSenha) {
      alert('As senhas não coincidem.');
      return;
    }
    var urlAtual = window.location.href;
    // Envia a solicitação AJAX
    $.ajax({
      url: urlAtual,
      method: 'POST',
      data: { senha: senha },
      success: function(response) {
        alert('Senha alterada com sucesso!');
        window.location.href = "<?= base_url('')?>";
      },
      error: function() {
        alert('Ocorreu um erro ao enviar a solicitação.');
      }
    });
  });
});
</script>