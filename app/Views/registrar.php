  <style>
   .container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 90vh;
  position: relative;
  top: 35vh;
}
    
    .rounded-rectangle {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
      padding: 50px;
      width: 100%;
      max-width: 500px;
      margin: 0 auto;
    }
    
    .form-label {
      font-weight: 600;
    }
  </style>
  
  <div class="container">
    <form class="rounded-rectangle">
    <div class="mb-3">
  <label for="login" class="form-label">Email:</label>
  <input type="text" class="form-control" id="login" name="email" required>
</div>

      <div class="mb-3">
        <label for="senha" class="form-label">Senha:</label>
        <input type="password" class="form-control" id="senha" name="senha" required>
      </div>
      <div class="mb-3">
  <label for="tipo" class="form-label"></label><br>
  <input type="radio" id="cnpjRadio" name="tipo" value="cnpj" checked>
  <label for="cnpjRadio">CNPJ</label><br>
  <input type="radio" id="cpfRadio" name="tipo" value="cpf">
  <label for="cpfRadio">CPF</label>
</div>

<div class="mb-3" id="cnpjDiv">
  <label for="cnpj" class="form-label">CNPJ:</label>
  <input type="text" class="form-control" id="cnpj" name="cnpj" maxlength="14" required>
</div>

<div class="mb-3" id="empresaDiv">
  <label for="empresa" class="form-label">Empresa:</label>
  <input type="text" class="form-control" id="empresa" name="empresa" required>
</div>

<div class="mb-3" id="cpfDiv" style="display:none">
  <label for="cpf" class="form-label">CPF:</label>
  <input type="text" class="form-control" id="cpf" name="cpf" maxlength="11">
</div>

<div class="mb-3" id="nomeDiv" style="display:none">
  <label for="nome" class="form-label">Nome:</label>
  <input type="text" class="form-control" id="nome" name="nome">
</div>
      <div class="mb-3">
        <label for="responsavel" class="form-label">Responsável:</label>
        <input type="text" class="form-control" id="responsavel" name="responsavel" required>
      </div>
      <div class="mb-3">
        <label for="celular" class="form-label">Celular:</label>
        <input type="text" class="form-control" id="celular" name="celular" required>
      </div>
      <div class="mb-3">
        <label for="telefone" class="form-label">Telefone:</label>
        <input type="text" class="form-control" id="telefone" name="telefone">
      </div>
      <div class="mb-3">
        <label for="cep" class="form-label">CEP:</label>
        <input type="text" class="form-control" id="cep" name="cep" maxlength="8" required>
      </div>
      <div class="mb-3">
        <label for="endereco" class="form-label">Endereço:</label>
        <input type="text" class="form-control" id="endereco" name="endereco" required>
      </div>
      <div class="mb-3">
        <label for="numero" class="form-label">Número:</label>
        <input type="text" class="form-control" id="numero" name="numero" required>
      </div>
      <div class="mb-3">
        <label for="complemento" class="form-label">Complemento:</label>
        <input type="text" class="form-control" id="complemento" name="complemento" >
      </div>
      <div class="mb-3">
        <label for="bairro" class="form-label">Bairro:</label>
        <input type="text" class="form-control" id="bairro" name="bairro" required>
      </div>
<button type="submit" class="btn btn-primary">Cadastrar</button>
</form>

  </div>
  <script>
  $(document).ready(function() {
    $('form').submit(function(event) {
      if (confirm("Todos os dados estão corretos?")) {

      } else {
        return;
      }

      $('#loading-overlay').show();
      event.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        url: '<?= base_url('cadastrar') ?>',
        type: 'POST',
        data: formData,
        success: function(response) {
          if (response == "cadastrado com sucesso!"){
            alert('Cadastrado com sucesso, confirme no seu email');
            window.location.href = "<?= base_url('')?>";
          }
          console.log(response);
        $('#loading-overlay').hide();
        },
        error: function(xhr, status, error) {
          console.log(xhr.responseText);
          alert("houve um erro, tente novamente mais tarde ou entre em contato conosco");
        $('#loading-overlay').hide();

        }
      });
    });
    var cnpjRadio = document.getElementById('cnpjRadio');
  var cpfRadio = document.getElementById('cpfRadio');
  var cnpjDiv = document.getElementById('cnpjDiv');
  var empresaDiv = document.getElementById('empresaDiv');
  var cpfDiv = document.getElementById('cpfDiv');
  var nomeDiv = document.getElementById('nomeDiv');
  
  cnpjRadio.addEventListener('click', function() {
    cnpjDiv.style.display = 'block';
    empresaDiv.style.display = 'block';
    cpfDiv.style.display = 'none';
    nomeDiv.style.display = 'none';
    document.getElementById('cnpj').setAttribute('required', true);
    document.getElementById('cpf').removeAttribute('required');
    document.getElementById('empresa').setAttribute('required', true);
    document.getElementById('nome').removeAttribute('required');
  });
  
  cpfRadio.addEventListener('click', function() {
    cpfDiv.style.display = 'block';
    nomeDiv.style.display = 'block';
    cnpjDiv.style.display = 'none';
    empresaDiv.style.display = 'none';
    document.getElementById('cpf').setAttribute('required', true);
    document.getElementById('cnpj').removeAttribute('required');
    document.getElementById('nome').setAttribute('required', true);
    document.getElementById('empresa').removeAttribute('required');
  });
  });
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

</script>
