<!DOCTYPE html>
<html lang="en">
<head>
  <title> Painel Real time </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet" >
  <script src="https://Deliveryexpresssolutions.com.br/assets/js/jsQR.js"></script>
    
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap");:root{--header-height: 3rem;--nav-width: 68px;--first-color: #4723D9;--first-color-light: #AFA5D9;--white-color: #F7F6FB;--body-font: 'Nunito', sans-serif;--normal-font-size: 1rem;--z-fixed: 100}*,::before,::after{box-sizing: border-box}body{position: relative;margin: var(--header-height) 0 0 0;padding: 0 1rem;font-family: var(--body-font);font-size: var(--normal-font-size);transition: .5s}a{text-decoration: none}.header{width: 100%;height: var(--header-height);position: fixed;top: 0;left: 0;display: flex;align-items: center;justify-content: space-between;padding: 0 1rem;background-color: var(--white-color);z-index: var(--z-fixed);transition: .5s}.header_toggle{color: var(--first-color);font-size: 1.5rem;cursor: pointer}.header_img{width: 35px;height: 35px;display: flex;justify-content: center;border-radius: 50%;overflow: hidden}.header_img img{width: 40px}.l-navbar{position: fixed;top: 0;left: -30%;width: var(--nav-width);height: 100vh;background-color: var(--first-color);padding: .5rem 1rem 0 0;transition: .5s;z-index: var(--z-fixed)}.nav{height: 100%;display: flex;flex-direction: column;justify-content: space-between;overflow: hidden}.nav_logo, .nav_link{display: grid;grid-template-columns: max-content max-content;align-items: center;column-gap: 1rem;padding: .5rem 0 .5rem 1.5rem}.nav_logo{margin-bottom: 2rem}.nav_logo-icon{font-size: 1.25rem;color: var(--white-color)}.nav_logo-name{color: var(--white-color);font-weight: 700}.nav_link{position: relative;color: var(--first-color-light);margin-bottom: 1.5rem;transition: .3s}.nav_link:hover{color: var(--white-color)}.nav_icon{font-size: 1.25rem}.show{left: 0}.body-pd{padding-left: calc(var(--nav-width) + 1rem)}.active{color: var(--white-color)}.active::before{content: '';position: absolute;left: 0;width: 2px;height: 32px;background-color: var(--white-color)}.height-100{height:100vh}@media screen and (min-width: 768px){body{margin: calc(var(--header-height) + 1rem) 0 0 0;padding-left: calc(var(--nav-width) + 2rem)}.header{height: calc(var(--header-height) + 1rem);padding: 0 2rem 0 calc(var(--nav-width) + 2rem)}.header_img{width: 40px;height: 40px}.header_img img{width: 45px}.l-navbar{left: 0;padding: 1rem 1rem 0 0}.show{width: calc(var(--nav-width) + 156px)}.body-pd{padding-left: calc(var(--nav-width) + 188px)}}
        #nav-bar a {
    color: #F2FFFC;
    text-decoration: none;
}

.tab-content {
  height:100%;
}
        .icon-large {
            font-size: 24px;
        }
    </style>
</head>
<body id="body-pd">
    <header class="header" id="header">
        <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
        <!-- caso algum dia coloque foto <div class="header_img"> <img src="" alt=""> </div> -->
    </header>
    <div class="l-navbar" id="nav-bar" style="  text-decoration: none;">
        <nav class="nav">
            <div> <a href="#" class="nav_logo"> <i class='bx bx-layer nav_logo-icon'></i> <span class="nav_logo-name">Real Time Painel</span> </a>
                <div class="nav_list" > 
                    <a class="nav_link active" data-tab="tab1"> <i class='bx bx-grid-alt nav_icon no-decoration'></i> <span class="nav_name">Em andamento</span> </a> 
                    <a class="nav_link" data-tab="tab2"> <i class='bx bx-user nav_icon no-decoration'></i> <span class="nav_name">Entregadores</span> </a> 
                    <a class="nav_link" data-tab="tab3"> <i class='bx bx-message-square-detail nav_icon no-decoration'></i> <span class="nav_name">Mensagens</span> </a> 
                    <a class="nav_link" data-tab="tab4"> <i class='bx bx-bookmark nav_icon no-decoration'></i> <span class="nav_name">Base</span> </a> 
                    <a class="nav_link" data-tab="tab5"> <i class='bx bx-folder nav_icon no-decoration'></i> <span class="nav_name">Atribuir</span> </a> 
                    <a class="nav_link" data-tab="tab7"> <i class="bx bx-group nav_icon no-decoration"></i> <span class="nav_name">Usuários</span> </a>
                    <a class="nav_link" data-tab="tab6"> <i class='bx bx-bar-chart-alt-2 nav_icon no-decoration'></i> <span class="nav_name">Documentos</span> </a> 
                </div>
            </div> 
            <a href="<?= base_url('clean') ?>" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span class="nav_name">Sair</span> </a>
        </nav>
    </div>
    <!--Container Main start-->
    <div class="height-100 bg-light" style = "top: 3vh; position: relative;overflow: auto;";>
        <div id="tab1" class="tab-content">
<!-- painel de entregas em andamento -->
        <!DOCTYPE html>
          <html lang="en">

          <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
          </head>

          <body>   
          <div class="container mt-5">
        <h4>Em andamento</h4>
            <div class="table-responsive">
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
            <th>Cabe Moto</th>
            <th>Status</th>
            <th>Detalhes</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($produto as $item) : ?>
            <tr>
              <td><?= esc($item['destinatario']) ?></td>
              <td><?= esc($item['endereco']) ?></td>
              <td><?= esc($item['numero']) ?></td>
              <td><?= esc($item['complemento']) ?></td>
              <td><?= esc($item['cep']) ?></td>
              <td><?= esc($item['bairro']) ?></td>
              <td><?= esc($item['telefone']) ?></td>
              <td><?= esc($item['cabe_moto']) ?></td>
              <td><?= esc($item['status']) ?></td>
              <td>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailsModal<?= esc($item['_id']) ?>">
                  Detalhes
                </button>
              </td>
            </tr>

            <!-- Modal -->
            <div class="modal fade" id="detailsModal<?= esc($item['_id']) ?>" tabindex="-1" aria-labelledby="detailsModalLabel<?= esc($item['_id']) ?>" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel<?= esc($item['_id']) ?>">Detalhes do Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p><strong>Tempo:</strong> <?= esc($item['time']) ?></p>
                    <p><strong>Rastreio:</strong> <?= esc($item['rastreio']) ?></p>
                    <p><strong>Destinatário:</strong> <?= esc($item['destinatario']) ?></p>
                    <p><strong>Endereço:</strong> <?= esc($item['endereco']) ?></p>
                    <p><strong>Número:</strong> <?= esc($item['numero']) ?></p>
                    <p><strong>Complemento:</strong> <?= esc($item['complemento']) ?></p>
                    <p><strong>CEP:</strong> <?= esc($item['cep']) ?></p>
                    <p><strong>Bairro:</strong> <?= esc($item['bairro']) ?></p>
                    <p><strong>Telefone:</strong> <?= esc($item['telefone']) ?></p>
                    <p><strong>Observação:</strong> <?= esc($item['observacao']) ?></p>
                    <p><strong>Cabe Moto:</strong> <?= esc($item['cabe_moto']) ?></p>
                    <p><strong>Status:</strong> <?= esc($item['status']) ?></p>
                    <p><strong>Cliente ID:</strong> <?= esc($item['cliente_id']) ?></p>
                    <p><strong>Andamento:</strong> <?= esc($item['andamento']) ?></p>
                    <p><strong>Entregador:</strong> <?= !empty($item['Entregador']) ? $item['Entregador'] : 'não atribuído' ?></p>

                  </div>
                  <button type="button" class="btn btn-primary btn-details" data-cliente-id="<?= esc($item['cliente_id']) ?>">
                    Detalhes Cliente
                  </button>

                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </tbody>
      </table>
  <!-- Client Details Modal -->
  <div class="modal" id="clientDetailsModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detalhes do Cliente</h5>
          <span class="modal-close">&times;</span>
        </div>
        <div class="modal-body">
          <p><strong>Email:</strong> <span id="modalEmail"></span></p>
          <p><strong>Nome:</strong> <span id="modalNome"></span></p>
          <p><strong>CPF:</strong> <span id="modalCPF"></span></p>
          <p><strong>Endereço:</strong> <span id="modalEndereco"></span></p>
          <p><strong>Número:</strong> <span id="modalNumero"></span></p>
          <p><strong>Complemento:</strong> <span id="modalComplemento"></span></p>
          <p><strong>CEP:</strong> <span id="modalCEP"></span></p>
          <p><strong>Bairro:</strong> <span id="modalBairro"></span></p>
          <p><strong>Telefone:</strong> <span id="modalTelefone"></span></p>
          <p><strong>Celular:</strong> <span id="modalCelular"></span></p>
          <p><strong>Responsável:</strong> <span id="modalResponsavel"></span></p>
          <p><strong>Status:</strong> <span id="modalStatus"></span></p>
        </div>
      </div>
    </div>
  </div>

  <script>
    function showModal() {
      var modal = document.getElementById("clientDetailsModal");
      modal.style.display = "block";
    }

    // Função para fechar o modal de detalhes do cliente
    function closeModal() {
      var modal = document.getElementById("clientDetailsModal");
      modal.style.display = "none";
    }

    // Adiciona um listener para fechar o modal ao clicar no botão de fechar
    var closeBtn = document.getElementsByClassName("modal-close")[0];
    closeBtn.addEventListener("click", closeModal);

    // Adiciona um listener para exibir o modal ao clicar no botão Detalhes
    var btnDetails = document.getElementsByClassName("btn-details");
    for (var i = 0; i < btnDetails.length; i++) {
      btnDetails[i].addEventListener("click", showModal);
    }

      $('.btn-details').click(function() {
        var clienteId = $(this).data('cliente-id');
        $.ajax({
          url: '<?= base_url('central/painel') ?>',
          method: 'POST',
          data: {
            choice: "10",
            clienteId: clienteId
          },
          success: function(data) {
            $('#modalEmail').text(data.email);
            $('#modalNome').text(data.nome);
            $('#modalCPF').text(data.cpf);
            $('#modalEndereco').text(data.endereco);
            $('#modalNumero').text(data.numero);
            $('#modalComplemento').text(data.complemento);
            $('#modalCEP').text(data.cep);
            $('#modalBairro').text(data.bairro);
            $('#modalTelefone').text(data.telefone);
            $('#modalCelular').text(data.celular);
            $('#modalResponsavel').text(data.responsavel);
            $('#modalStatus').text(data.status);

            // Show the client details modal
            $('#clientDetailsModal').modal('show');
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.error("Status:", jqXHR.status);
            console.error("Server Error:", jqXHR.responseText);
          }
        });
      });
  </script>


        </div>
        </body>
        </div>
        </div>
        <div id="tab2" class="tab-content" style="display: none;">
        <?php if ($role == "agente" ||$role == "coordenador" ||$role == "coodernadorSupervisor" ||$role == "coordenador" ||$role == "supervisor" ||$role  == "agente" ||$role  == "financeiro" ||$role == "sac") { ?>
<!-- painel para verificação da entregadores -->  
        <!DOCTYPE html>
          <html lang="en">

          <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
          </head>

          <body>

            <div class="container mt-5">
              <h1>Tabela de Entregadores</h1>

              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForme">
                Adicionar usuario
              </button>

              <table class="table mt-3">
            <thead>
              <tr>
                <th scope="col">apelido</th>
                <th scope="col">nome</th>
                <th scope="col">Telefone</th>
                <th scope="col">status</th>
                <th scope="col">Dados</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
          <?php foreach($entregaores as $user): ?>
              <tr>
                  <td><?= isset($user->apelido) ? $user->apelido : 0 ?></td>
                  <td><?= isset($user->nome) ? $user->nome : 0 ?></td>
                  <td><?= isset($user->telefone) ? $user->telefone : 0 ?></td>
                  <td><?= isset($user->status) ? $user->status : 0 ?></td>
                  
                  <td>
                      <button class="btn btn-primary" onclick="openModale('<?= esc($user->_id) ?>', '4')">Ver Dados</button>
                  </td>
                  <td>
                      <button type="button" class="btn btn-primary btn-edit-e" data-id='<?= esc($user->_id) ?>' data-choice='1'>Editar</button>
                  </td>
              </tr>
          <?php endforeach; ?>
          
            </tbody>
          </table>

          <div class="modal fade" id="editmodale" tabindex="-1" aria-labelledby="editmodaleLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editmodaleLabel">Editar dados Entregador</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <form id="editForm-e">
            <input type="hidden" id="editId-e" name="id">
            <div class="mb-3">
              <label for="editcpfcnpj" class="form-label">Documento</label>
              <input type="text" class="form-control" id="editcpfcnpj-e" name="cpfcnpj">
            </div>
            <div class="mb-3">
              <label for="editNome" class="form-label">Acesso</label>
              <input type="text" class="form-control" id="editNome-e" name="nome">
            </div>
            <div class="mb-3">
              <label for="editPassword" class="form-label">Nova senha</label>
              <input type="password" class="form-control" id="editPassword-e" name="password" placeholder="Digite sua senha">
            </div>
            <div class="mb-3">
              <label for="editnome" class="form-label">Nome</label>
              <input type="text" class="form-control" id="editnome-e" name="nomee">
            </div>
            <div class="mb-3">
              <label for="editapelido" class="form-label">Apelido</label>
              <input type="text" class="form-control" id="editapelido-e" name="apelido">
            </div>
            <div class="mb-3">
              <label for="editEndereco" class="form-label">Endereço</label>
              <input type="text" class="form-control" id="editEndereco-e" name="endereco">
            </div>
            <div class="mb-3">
              <label for="editNumero" class="form-label">Número</label>
              <input type="text" class="form-control" id="editNumero-e" name="numero">
            </div>
            <div class="mb-3">
              <label for="editComplemento" class="form-label">Complemento</label>
              <input type="text" class="form-control" id="editComplemento-e" name="complemento">
            </div>
            <div class="mb-3">
              <label for="editCep" class="form-label">CEP</label>
              <input type="text" class="form-control" id="editCep-e" name="cep">
            </div>
            <div class="mb-3">
              <label for="editTelefone" class="form-label">Telefone</label>
              <input type="text" class="form-control" id="editTelefone-e" name="telefone">
            </div>
            <div class="mb-3">
              <label for="editCpfcnpj" class="form-label">CPF/CNPJ</label>
              <input type="text" class="form-control" id="editCpfcnpj-e" name="cpfcnpj">
            </div>
            <div class="mb-3">
              <label for="editStatus" class="form-label">Status</label>
              <select class="form-control" id="editStatus-e" name="status">
                <option value="ativo">Ativo</option>
                <option value="desativado">Desativado</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="button" class="btn btn-primary" onclick="saveentre()">Salvar</button>
        </div>
      </div>
    </div>
  </div>
  <script>
  function saveentre() {
  // Obtém os valores dos campos do formulário
  var id = $("#editId-e").val();
  var cpfcnpj = $("#editcpfcnpj-e").val();
  var nome = $("#editNome-e").val();
  var password = $("#editPassword-e").val();
  var nomee = $("#editnome-e").val();
  var apelido = $("#editapelido-e").val();

  var endereco = $("#editEndereco-e").val();
  var numero = $("#editNumero-e").val();
  var complemento = $("#editComplemento-e").val();
  var cep = $("#editCep-e").val();
  var telefone = $("#editTelefone-e").val();
  var status = $("#editStatus-e").val();

  // Crie um objeto com os dados do usuário
  var userData = {
    id: id,
    cpfcnpj: cpfcnpj,
    nome: nome,
    password: password,
    nomee: nomee,
    endereco: endereco,
    numero: numero,
    complemento: complemento,
    cep: cep,
    telefone: telefone,
    status: status,
    apelido : apelido
  };

  // Envie os dados para o arquivo PHP usando Ajax
  $.ajax({
    url: "<?= base_url('central/painel/edite') ?>",
    type: "POST",
    dataType: "json",
    data: userData,
    success: function(response) {
      // Processar a resposta do servidor, se necessário
      alert(response);
      window.location.reload();
    },
    error: function(jqXHR, textStatus, errorThrown) {
            console.error("Status:", jqXHR.status);
            console.error("Server Error:", jqXHR.responseText);
          }
  });
}
$(document).on('click', '.btn-edit-e', function() {
            // Obtenha o ID do usuário e a opção do botão
            var userId = $(this).data('id');
            var choice = $(this).data('choice');

            // Chame a função edit(userId, choice)
            edite(userId, choice);
          });
          function edite(userId, choice){
            
            $.ajax({
          url: "<?= base_url('central/painel/edite') ?>",
          type: "GET",
          dataType: "json",
          data: { id: userId},
          success: function(data) {

            // Preencha os campos do modal com os dados retornados
            $("#editNome-e").val(data.user_e);
            $("#editnome-e").val(data.nome);
            $("#editcpfcnpj-e").val(data.cpfcnpj);
            $("#editEndereco-e").val(data.endereco);
            $("#editNumero-e").val(data.numero);
            $("#editComplemento-e").val(data.complemento);
            $("#editCep-e").val(data.cep);
            $("#editTelefone-e").val(data.telefone);
            $("#editCpfcnpj-e").val(data.cpfcnpj);
            $("#editValidadeCnh-e").val(data.validadeCnh);
            $("#editRole-e").val(data.role);
            $("#editFilename-e").val(data.filename);
            $("#editStatus-e").val(data.status);
            $("#editId-e").val(data.token);
            $("#editapelido-e").val(data.apelido);


            
            $('#editmodale').modal('show');
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.error("Status:", jqXHR.status);
            console.error("Server Error:", jqXHR.responseText);
          }
        });
          }
</script>

          <!-- Modal -->
          <div class="modal" id="userDataModal_e">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title">Dados do Usuário</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                          <ul id="userDataList_e">
                              <!-- Os dados do usuário serão inseridos aqui -->
                          </ul>
                      </div>
                      <div class="modal-footer">
                      <button type="button" class="btn btn-danger" id="desativarBtn" style = "left: 1px;">Desativar</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                      </div>
                  </div>
              </div>
          </div>

  
          <!-- JavaScript -->
          <script>
           
          function openModale(userId, choice) {
              // Fazer uma requisição AJAX para obter os dados do usuário com base no ID
              // Substitua a URL pela sua rota de backend ou API
              $.ajax({
                  url: '<?= base_url('central/painel') ?>',
                  type: 'POST',
                  data: { id: userId, choice: choice },
                  dataType: 'json',
                  success: function(userData) {
                      if (userData === "desativado") {
                          alert("Desativado com sucesso");
                          return;
                      } else if (userData === "erro desativado") {
                          alert("Erro, contate um administrador");
                          return;
                      }

                      // Preencher a lista no modal com os dados do usuário
                      var userDataList = document.getElementById('userDataList_e');
                      var string = userData._id.toString();
                      userDataList.innerHTML = `
                          <li>Login: ${userData.user_e}</li>
                          <li>Nome: ${userData.nome}</li>
                          <li>Endereço: ${userData.endereco}</li>
                          <li>Número: ${userData.numero}</li>
                          <li>Complemento: ${userData.complemento}</li>
                          <li>CEP: ${userData.cep}</li>
                          <li>Telefone: ${userData.telefone}</li>
                          <li>CPF/CNPJ: ${userData.cpfcnpj}</li>
                          <li>Validade CNH: ${userData.validadeCnh}</li>
                          <li>Permissões: ${userData.role}</li>
                          <li>Status: ${userData.status}</li>
                          <li><a href="<?= base_url('central/painel/cnh')?>/${userData.token}/2"><button class="btn btn-secondary btn-show-cnh" data-id="${userData._id}" >Mostrar Foto CNH</button></a></li>
                          `;

                      var btnUser = document.getElementById('desativarBtn');
                      btnUser.setAttribute('onclick', `openModale('${userId}', '5')`);

                      // Abrir o modal
                      var userDataModal = new bootstrap.Modal(document.getElementById('userDataModal_e'));
                      userDataModal.show();
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                      console.error("Status:", jqXHR.status);
                      console.error("Erro do servidor:", jqXHR.responseText);
                  }
              });
          }

          </script>

            </div>

            <!-- Modal -->
            <div class="modal fade" id="modalForme" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalFormLabel">Adicionar usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="usuarioform_e">
                    <div class="mb-3">
                        <label for="apelido" class="form-label">Apelido</label>
                        <input type="text" class="form-control" id="apelido_e" name="apelido_e">
                      </div>
                      <div class="mb-3">
                        <label for="login" class="form-label">Login</label>
                        <input type="text" class="form-control" id="login_e" name="login_e" required>
                      </div>
                      <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha_e" name="senha_e" required>
                      </div>
                      <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome_e" name="nome_e" required>
                      </div>
                      <div class="mb-3">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input type="text" class="form-control" id="endereco_e" name="endereco_e" required>
                      </div>
                      <div class="mb-3">
                        <label for="numero" class="form-label">Número</label>
                        <input type="text" class="form-control" id="numero_e" name="numero_e" required>
                      </div>
                      <div class="mb-3">
                        <label for="complemento" class="form-label">Complemento</label>
                        <input type="text" class="form-control" id="complemento_e" name="complemento_e" required>
                      </div>
                      <div class="mb-3">
                        <label for="cep" class="form-label">CEP</label>
                        <input type="text" class="form-control" id="cep_e" name="cep_e" required>
                      </div>
                      <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telefone_e" name="telefone_e" required>
                      </div>
                      <div class="mb-3">
                        <label for="cpfcnpj" class="form-label">CPF/CNPJ</label>
                        <input type="text" class="form-control" id="cpfcnpj_e" name="cpfcnpj_e" required>
                      </div>
                      <div class="mb-3">
                        <label for="fotoCnh" class="form-label">Foto CNH</label>
                        <input type="file" class="form-control" id="fotoCnh_e" name="fotoCnh_e" required>
                      </div>
                      <div class="mb-3">
                        <label for="validadeCnh" class="form-label">Validade CNH</label>
                        <input type="date" class="form-control" id="validadeCnh_e" name="validadeCnh_e" required>
                      </div>
                      <button type="submit" class="btn btn-primary">Adicionar</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
           $(document).ready(function () {
            $('#usuarioform_e').submit(function (event) {
              event.preventDefault();

              // Cria um objeto FormData
              var formData = new FormData(this);

              // Adiciona o arquivo ao objeto FormData
              var fileInput = $('#fotoCnh_e')[0];
              var file = fileInput.files[0];
              formData.append('fotoCnh_e', file);
              formData.append('choice', '6');

              // Enviar o formulário via AJAX
              $.ajax({
                url: '<?= base_url('central/painel') ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                  console.log(response);
                  window.location.reload();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                  // Imprime o erro no console
                  console.error("Status:", jqXHR.status);
                  console.error("Erro do servidor:", jqXHR.responseText);
                }
              });
            });
          });

            </script>

          </body>

          </html>
          <?php } else {
              echo "sem autorização";
          }?>
                  </div>
<!-- mensagens                   -->
    <div id="tab3" class="tab-content" style="display: none;">
      <h4>Mensagens</h4>
      <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <style>
      .chat-container {
        height: 400px;
        overflow-y: scroll;
      }

      .message {
        margin-bottom: 10px;
      }

      .message .sender {
        font-weight: bold;
      }

      .message .text {
        margin-top: 5px;
      }

      .input-container {
        margin-top: 20px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h1>Lista de Mensagens</h1>
      <div id="messageList">
      <?php foreach ($chat as $chat): ?>
    <div class="list-group mt-4">
      <div class="list-group-item" data-id="<?= esc($chat->cliente) ?>" onclick="openLink('<?= esc($chat->token) ?>')">
        <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1"><?= esc($chat->nome) ?></h5>
          <small><?= esc($chat->date) ?></small>
        </div>
        <p class="mb-1"><?= esc($chat->last) ?></p>
      </div>
    </div>
  <?php endforeach ?>

  <script>
  function openLink(dataId) {
    var url = 'https://Deliveryexpresssolutions.com.br/central/painel/sac/' + dataId;
    window.open(url, '_blank');
  }
  </script>

      </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
  <script>

    // Função para enviar solicitação AJAX e atualizar as mensagens
    function updateMessages() {
      var dados = {choice : 'receberChat'};
      $.ajax({
        url: '<?= base_url('central/painel/sac') ?>',
        type: 'POST',
        dataType: 'html',
        data: dados,
        success: function(response) {
          // Anexa a resposta ao elemento #messageList
          if(response == ''){
            console.log(response);
          }else{
          $('#messageList').html(response);
        }},
        error: function(jqXHR, textStatus, errorThrown) {
          console.error("Status:", jqXHR.status);
          console.error("Erro do servidor:", jqXHR.responseText);
        }
      });
    }

    // Atualiza as mensagens a cada 5 segundos
    setInterval(updateMessages, 5000);

  </script>
  </body>
  </html>

  </div>
  </div>
  
<!-- usuarios da base  -->
  <div id="tab4" class="tab-content" style="display: none;">

                    <?php if ($role == "coordenador" || $role == "coordenadorSupervisor") { ?>
  <!-- painel para verificação da base -->  
            <!DOCTYPE html>
            <html lang="en">

          <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
          </head>

      <body>

        <div class="container mt-5">
          <h1>Tabela de usuarios Base</h1>

          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm">
            Adicionar usuario
          </button>
          <style>
        /* Estilos para a tela de loading */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalrotas">Setores</button>
  <!-- Modal -->
  <div class="modal fade" id="modalrotas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Setores</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulário para envio de arquivo -->
                <form id="formSetores" action="<?= base_url('central/painel/setor/set_json') ?>" method="post" enctype="multipart/form-data">
                    <input type="file" name="xlsx_file" id="xlsx_file" />
                    <input type="submit" class="btn btn-primary" value="Enviar Setores" />
                </form>
                <button id="btnAtualizarSetores" class="btn btn-secondary btnAtualizarSetores">Atualizar Setores</button>
                <a href="https://Deliveryexpresssolutions.com.br/central/setores/1" class="btn btn-primary">Listar</a>
            </div>
        </div>
    </div>
  </div>

  <!-- Tela de loading -->
  <div class="loading-overlay" id="loadingOverlay" style="display:none;">
      <div class="loading-spinner"></div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
  <script>
    $(document).ready(function() {
        // Função para exibir a tela de loading
        function showLoading() {
            $('#loadingOverlay').show();
        }

        // Função para ocultar a tela de loading
        function hideLoading() {
            $('#loadingOverlay').hide();
        }

        // Requisição AJAX quando o botão "Enviar Setores" é clicado
        $('#formSetores').submit(function(event) {
            event.preventDefault(); // Evita o envio do formulário

            var fileInput = $('#xlsx_file')[0];
            var file = fileInput.files[0];
            var reader = new FileReader();

            reader.onload = function(e) {
                var data = new Uint8Array(e.target.result);
                var workbook = XLSX.read(data, { type: 'array' });
                var sheetName = workbook.SheetNames[0];
                var jsonData = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName]);

                $.ajax({
                    url: '<?= base_url('central/painel/setor/set') ?>',
                    type: 'POST',
                    data: JSON.stringify(jsonData),
                    beforeSend: function() {
                        showLoading(); // Exibe a tela de loading antes de enviar a requisição
                    },
                    success: function(response) {
                      alert('inserido arquivo com sucesso!!!');
                        // Lógica para manipular a resposta do servidor
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        // Trate o erro, se necessário
                        console.log(error);
                        console.log(xhr.responseText);
                    },
                    complete: function() {
                        hideLoading(); // Oculta a tela de loading após a resposta do servidor
                    },
                    contentType: 'application/json',
                    processData: false
                });
            };

            reader.readAsArrayBuffer(file);
        });
                    // Requisição AJAX quando o botão "Atualizar Setores" é clicado
                    $('#btnAtualizarSetores').click(function() {
                $.ajax({
                    url: '<?= base_url('central/painel/setor/set') ?>',
                    data:{ choice:'1' },
                    type: 'POST',
                    beforeSend: function() {
                      
                        showLoading(); // Exibe a tela de loading antes de enviar a requisição
                    },
                    success: function(response) {
                      alert( 'atualizado com sucesso!!!');
                        // Lógica para manipular a resposta do servidor
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        // Trate o erro, se necessário
                        console.log(error);
                        console.log(xhr.responseText);
                    },
                    complete: function() {
                        hideLoading(); // Oculta a tela de loading após a resposta do servidor
                    }
                });
            });
    });



    </script>
          <table class="table mt-3">
        <thead>
          <tr>
            <th scope="col">login</th>
            <th scope="col">nome</th>
            <th scope="col">Permissões</th>
            <th scope="col">Status</th>
            <th scope="col">Dados</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
      <?php foreach($base_u as $user): ?>
          <tr>
              <td><?= isset($user->user_b) ? $user->user_b : 0 ?></td>
              <td><?= isset($user->nome) ? $user->nome : 0 ?></td>
              <td><?= isset($user->role) ? $user->role : 0 ?></td>
              <td><?= isset($user->status) ? $user->status : 0 ?></td>
              <td>
                  <button class="btn btn-primary" onclick="openModal('<?= esc($user->_id) ?>', 0
                  )">Ver Dados</button>
              </td>
              <td>
                      <button type="button" class="btn btn-primary btn-edit" data-id='<?= esc($user->_id) ?>' data-choice='1'>Editar</button>
                  </td>

          </tr>
      <?php endforeach; ?>
      
        </tbody>
      </table>
      <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editmodalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editmodalLabel">Editar Usuário</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <form id="editForm">
            <input type="hidden" id="editId" name="id">
            <div class="mb-3">
              <label for="editcpfcnpj" class="form-label">Documento</label>
              <input type="text" class="form-control" id="editcpfcnpj" name="cpfcnpj">
            </div>
            <div class="mb-3">
              <label for="editNome" class="form-label">Acesso</label>
              <input type="text" class="form-control" id="editNome" name="nome">
            </div>
            <div class="mb-3">
              <label for="editPassword" class="form-label">Nova senha</label>
              <input type="password" class="form-control" id="editPassword" name="password" placeholder="Digite sua senha">
            </div>
            <div class="mb-3">
              <label for="editnome" class="form-label">Nome</label>
              <input type="text" class="form-control" id="editnome" name="nomee">
            </div>
            <div class="mb-3">
              <label for="editEndereco" class="form-label">Endereço</label>
              <input type="text" class="form-control" id="editEndereco" name="endereco">
            </div>
            <div class="mb-3">
              <label for="editNumero" class="form-label">Número</label>
              <input type="text" class="form-control" id="editNumero" name="numero">
            </div>
            <div class="mb-3">
              <label for="editComplemento" class="form-label">Complemento</label>
              <input type="text" class="form-control" id="editComplemento" name="complemento">
            </div>
            <div class="mb-3">
              <label for="editCep" class="form-label">CEP</label>
              <input type="text" class="form-control" id="editCep" name="cep">
            </div>
            <div class="mb-3">
              <label for="editTelefone" class="form-label">Telefone</label>
              <input type="text" class="form-control" id="editTelefone" name="telefone">
            </div>
            <div class="mb-3">
              <label for="editCpfcnpj" class="form-label">CPF/CNPJ</label>
              <input type="text" class="form-control" id="editCpfcnpj" name="cpfcnpj">
            </div>
            <div class="mb-3">
            <label for="editRole" class="form-label">Status</label>
              <select class="form-control" id="editRole" name="role">
                      <option value="coordenador">Coordenador</option>
                      <option value="coordenadorSupervisor">Coordenador Supervisor</option>
                      <option value="agente">Agente</option>
                      <option value="supervisor">Supervisor</option>
                      <option value="financeiro">Financeiro</option>
                      <option value="sac">SAC</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="editStatus" class="form-label">Status</label>
              <select class="form-control" id="editStatus" name="status">
                <option value="ativo">Ativo</option>
                <option value="desativado">Desativado</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="button" class="btn btn-primary" onclick="saveUser()">Salvar</button>
        </div>
      </div>
    </div>
  </div>
      <!-- Modal -->
      <div class="modal" id="userDataModal">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">Dados do Usuário</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                      <ul id="userDataList">
                          <!-- Os dados do usuário serão inseridos aqui -->
                      </ul>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                  </div>
              </div>
          </div>
      </div>
      <!-- Modal para exibir a foto CNH -->
      <div class="modal fade" id="cnhModal" tabindex="-1" role="dialog" aria-labelledby="cnhModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                  <div class="modal-header">
   
                  </div>
                  <div class="modal-body">
                      <img id="cnhImage" src="" alt="Foto da CNH">
                  </div>
              </div>
          </div>
      </div>
      
      <!-- JavaScript -->
      <script>
        function saveUser() {
  // Obtém os valores dos campos do formulário
  var id = $("#editId").val();
  var cpfcnpj = $("#editcpfcnpj").val();
  var nome = $("#editNome").val();
  var password = $("#editPassword").val();
  var nomee = $("#editnome").val();
  var endereco = $("#editEndereco").val();
  var numero = $("#editNumero").val();
  var complemento = $("#editComplemento").val();
  var cep = $("#editCep").val();
  var telefone = $("#editTelefone").val();
  var status = $("#editStatus").val();
  var role = $("#editRole").val();

  // Crie um objeto com os dados do usuário
  var userData = {
    id: id,
    cpfcnpj: cpfcnpj,
    nome: nome,
    password: password,
    nomee: nomee,
    endereco: endereco,
    numero: numero,
    complemento: complemento,
    cep: cep,
    telefone: telefone,
    status: status,
    role: role
  };

  // Envie os dados para o arquivo PHP usando Ajax
  $.ajax({
    url: "<?= base_url('central/painel/edit') ?>",
    type: "POST",
    dataType: "json",
    data: userData,
    success: function(response) {
      // Processar a resposta do servidor, se necessário
      alert(response);
      window.location.reload();
    },
    error: function(jqXHR, textStatus, errorThrown) {
            console.error("Status:", jqXHR.status);
            console.error("Server Error:", jqXHR.responseText);
          }
  });
}

                  $(document).on('click', '.btn-edit', function() {
            // Obtenha o ID do usuário e a opção do botão
            var userId = $(this).data('id');
            var choice = $(this).data('choice');

            // Chame a função edit(userId, choice)
            edit(userId, choice);
          });
          function edit(userId, choice){
            
            $.ajax({
          url: "<?= base_url('central/painel/edit') ?>",
          type: "GET",
          dataType: "json",
          data: { id: userId},
          success: function(data) {
            // Preencha os campos do modal com os dados retornados
            $("#editNome").val(data.user_b);
            $("#editnome").val(data.nome);
            $("#editcpfcnpj").val(data.cpfcnpj);
            $("#editEndereco").val(data.endereco);
            $("#editNumero").val(data.numero);
            $("#editComplemento").val(data.complemento);
            $("#editCep").val(data.cep);
            $("#editTelefone").val(data.telefone);
            $("#editCpfcnpj").val(data.cpfcnpj);
            $("#editValidadeCnh").val(data.validadeCnh);
            $("#editRole").val(data.role);
            $("#editFilename").val(data.filename);
            $("#editStatus").val(data.status);
            $("#editId").val(data.token);

            
            $('#editmodal').modal('show');
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.error("Status:", jqXHR.status);
            console.error("Server Error:", jqXHR.responseText);
          }
        });
          }
      function openModal(userId, choice) {
          // Fazer uma requisição AJAX para obter os dados do usuário com base no ID
          // Substitua a URL pela sua rota de backend ou API
          $.ajax({
              url: '<?= base_url('central/painel') ?>',
              type: 'POST',
              data: { id: userId, choice: choice },
              dataType: 'json',
              success: function(userData) {
                  if (userData === "desativado") {
                      alert("Desativado com sucesso");
                      return;
                  } else if (userData === "erro desativado") {
                      alert("Erro, contate um administrador");
                      return;
                  }
                
                  // Preencher a lista no modal com os dados do usuário
                  var userDataList = document.getElementById('userDataList');
                  var string = userData._id.toString();
                  userDataList.innerHTML = `
                      <li>Login: ${userData.user_b}</li>
                      <li>Nome: ${userData.nome}</li>
                      <li>Endereço: ${userData.endereco}</li>
                      <li>Número: ${userData.numero}</li>
                      <li>Complemento: ${userData.complemento}</li>
                      <li>CEP: ${userData.cep}</li>
                      <li>Telefone: ${userData.telefone}</li>
                      <li>CPF/CNPJ: ${userData.cpfcnpj}</li>
                      <li>Validade CNH: ${userData.validadeCnh}</li>
                      <li>Permissões: ${userData.role}</li>
                      <li>Status: ${userData.status}</li>
                      <li><a href="<?= base_url('central/painel/cnh')?>/${userData.token}/1"><button class="btn btn-secondary btn-show-cnh" data-id="${userData._id}" >Mostrar Foto CNH</button></a></li>
                  `;
                
                  var btnUser = document.getElementById('desativarBtn');
                  btnUser.setAttribute('onclick', `openModal('${userId}', '2')`);
                
                  // Abrir o modal
                  var userDataModal = new bootstrap.Modal(document.getElementById('userDataModal'));
                  userDataModal.show();
              },
              error: function(jqXHR, textStatus, errorThrown) {
                  console.error("Status:", jqXHR.status);
                  console.error("Erro do servidor:", jqXHR.responseText);
              }
          });
      }

      </script>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalFormLabel">Adicionar usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="usuarioform">
                  <div class="mb-3">
                    <label for="login" class="form-label">Login</label>
                    <input type="text" class="form-control" id="login" name="login" required>
                  </div>
                  <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                  </div>
                  <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                  </div>
                  <div class="mb-3">
                    <label for="endereco" class="form-label">Endereço</label>
                    <input type="text" class="form-control" id="endereco" name="endereco" required>
                  </div>
                  <div class="mb-3">
                    <label for="numero" class="form-label">Número</label>
                    <input type="text" class="form-control" id="numero" name="numero" required>
                  </div>
                  <div class="mb-3">
                    <label for="complemento" class="form-label">Complemento</label>
                    <input type="text" class="form-control" id="complemento" name="complemento" required>
                  </div>
                  <div class="mb-3">
                    <label for="cep" class="form-label">CEP</label>
                    <input type="text" class="form-control" id="cep" name="cep" required>
                  </div>
                  <div class="mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" required>
                  </div>
                  <div class="mb-3">
                    <label for="cpfcnpj" class="form-label">CPF/CNPJ</label>
                    <input type="text" class="form-control" id="cpfcnpj" name="cpfcnpj" required>
                  </div>
                  <div class="mb-3">
                    <label for="permissoes" class="form-label">Permissões</label>
                    <select class="form-select" id="permissoes" name="permissoes" required>
                      <option value="coordenador">Coordenador</option>
                      <option value="coordenadorSupervisor">Coordenador Supervisor</option>
                      <option value="agente">Agente</option>
                      <option value="supervisor">Supervisor</option>
                      <option value="financeiro">Financeiro</option>
                      <option value="sac">SAC</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary">Adicionar</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
       $(document).ready(function () {
        $('#usuarioform').submit(function (event) {
          event.preventDefault();
        
          // Cria um objeto FormData
          var formData = new FormData(this);
        
          // Adiciona o arquivo ao objeto FormData
          var fileInput = $('#fotoCnh')[0];
          var file = fileInput.files[0];
          formData.append('fotoCnh', file);
          formData.append('choice', "6");

        
          // Enviar o formulário via AJAX
          $.ajax({
            url: '<?= base_url('central/painel') ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
              console.log(response);
              if (response == "okay"){
                window.location.reload();
              }
            },
            error: function (jqXHR, textStatus, errorThrown) {
              // Imprime o erro no console
              console.error("Status:", jqXHR.status);
              console.error("Erro do servidor:", jqXHR.responseText);
            }
          });
        });
      });

        </script>

      </body>

      </html>
      <?php } else {
          echo "sem autorização";
      }?>









              </div>
<!-- Atribuir entregador -->
              <div id="tab5" class="tab-content" style="display: none;">
              <!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
  </style>
</head>

<body>
  <div class="container mt-5 berserk" style="height: 100%;">
   <!-- aqui fica os produtos -->
   <hr><h3><center>Sem Entregas</center></h3><hr>
  </div>
        <!-- Modal Entregador -->
  <div class="modal fade" id="modalEntregador" tabindex="-1" aria-labelledby="modalEntregadorLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEntregadorLabel">Detalhes</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="container">
            <ul class="list-group list-ajg">
 <!-- aqui fica o que voltar do ajax -->
            </ul>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="modalItem1" tabindex="-1" aria-labelledby="modalItem1Label" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalItem1Label">Atribuir entregador</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>
    <!-- Modal -->
    <div class="modal fade" id="modalItem0" tabindex="-1" aria-labelledby="modalItem1Label" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalItem1Label">Atribuir entregador</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

</head>
<body>
  <div class="modal fade" id="modalConfirmarEntrega" tabindex="-1" aria-labelledby="modalConfirmarEntregaLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalConfirmarEntregaLabel">Confirmar Entrega</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form class="dform">
            <div class="form-group">
            <div class="container">
    <div class="form-group">
      <label for="rastreio">Insira o código de rastreio ou leia o QR code:</label>
      <div class="input-group">
        <input type="text" class="form-control" id="rastreio" placeholder="Insira o código de rastreio">
        <input type="file" accept="image/*" capture="environment" id="qrInput" style="display: none">
        <label for="qrInput" class="btn btn-primary">Ler QR Code</label>
      </div>
    </div>
  </div>

  <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <div class="form-check form-check-inline">
                    <input type="radio" name="T" class = 'T' value="T1" class="form-check-input">
                    <label class="form-check-label">T1</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" name="T" class = 'T' value="T2" class="form-check-input">
                    <label class="form-check-label">T2</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" name="T"  class = 'T'value="T3" class="form-check-input">
                    <label class="form-check-label">T3</label>
                </div>
            </div>
            <div class="col">
                <input type="number" class="form-control" id="peso" placeholder="Peso">
            </div>
        </div>
    </div>

            </div>
        </div>
        <div class="modal-footer">
        <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#resultModal" style="float: right">Enviar</button>
        </div>
      </div>
    </div>
  </div>
  </form>

  <script>
        function confirmscri(id){
      if(confirm('O entregador entregou e não está conseguindo confirmar?')){

      }else{
        return;
      }
      $.ajax({
          url: '<?= base_url('central/painel/confirmentregador') ?>',
          type: 'post',
          data: { id: id, choice: 'confirmen' },
          success: function(response) {

            if(response == 'okay'){}
            else{
          alert(response);}        

          },})
    }

    function confirmscr(id){
      if(confirm('O entregador coletou e não está conseguindo confirmar?')){

      }else{
        return;
      }
      $.ajax({
          url: '<?= base_url('central/painel/confirmentregador') ?>',
          type: 'post',
          data: { id: id, choice: 'confirm' },
          success: function(response) {

            if(response == 'okay'){}
            else{
          alert(response);}        

          },})
    }
         $('.dform').submit(function(event) {
        event.preventDefault();

        var codigoRastreio = $('#rastreio').val();
        var T = $('.T').val();
        var peso = $('#peso').val();
        if (codigoRastreio === '' || T === '' || peso === '') {
    alert('Por favor, preencha todos os campos.');
    return false;
  }

        $.ajax({
          url: '<?= base_url('central/painel/confirmentregador') ?>',
          type: 'post',
          data: { codigo: codigoRastreio, T: T, peso: peso },
          success: function(response) {
            console.log(response);
            if(response == ''){}
            else{
          alert(response);}        
          $('input[name="T"]').prop('checked', false);;
            $('#peso').val("");
            $('#rastreio').val("");  
          },

          error: function(jqXHR, textStatus, errorThrown) {
            console.error("Status:", jqXHR.status);
            console.error("Erro do servidor:", jqXHR.responseText);
            alert('Ocorreu um erro durante a solicitação ao servidor.');

          }
        });
      });

      $('#qrInput').change(function() {
        var file = this.files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
          var imageData = e.target.result;
          var image = new Image();
          
          image.onload = function() {
            var canvas = document.createElement('canvas');
            canvas.width = image.width;
            canvas.height = image.height;
            var ctx = canvas.getContext('2d');
            ctx.drawImage(image, 0, 0, image.width, image.height);
            var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            var code = jsQR(imageData.data, imageData.width, imageData.height);
            
            if (code) {
              $('#rastreio').val(code.data);
            } else {
              alert('Não foi possível ler o QR code');
            }
          };
          
          image.src = imageData;
        };
        
        reader.readAsDataURL(file);
      });
function update() {
      $.ajax({
        url: '<?= base_url('central/painel/list') ?>',
        type: 'POST',
        dataType: 'html',
        success: function(response) {
          // Anexa a resposta ao elemento #messageList
          if(response == ''){
            console.log(response);
          }else{
          $('.berserk').html(response);
        }},
        error: function(xhr, status, error) {
      console.log("Erro ao deletar: " + xhr.responseText);
    }
      });
    }

    // Atualiza as mensagens a cada 5 segundos
    setInterval(update, 2000);

        // Selecionando o botão pelo seu seletor de classe

// Adicionando um ouvinte de evento de clique no botão
function overlord(idProduto) {
  // Obtendo os atributos de dados do botão
  const ajgElement = $('.list-ajg');

  // Fazendo a requisição AJAX
  $.ajax({
    url: '<?= base_url('central/painel') ?>',
    type: 'POST',
    data: {idProduto : idProduto, choice:'13'},
    success: function(response) {
      // Manipular a resposta recebida do servidor
      ajgElement.html(response);
      
      // Faça algo com a resposta aqui, como atualizar uma parte da página ou exibir em uma janela modal
    },
    error: function(xhr, status, error) {
      // Lidar com erros de requisição, se houver
      console.log(error);
    }
  });
}


        function exec(id, apelido, rastreio) {
          var resposta = confirm("Deseja atribuir para "+ apelido+"?");
          if (resposta) {
      $.ajax({
        url: "<?= base_url('central/painel') ?>",
        type: 'POST',
        data: { id: id, choice: '12', rastreio: rastreio},
        success: function(response) {
          alert('Atribuido com sucesso!!!');
          console.log(response);
        },
        error: function(xhr, status, error) {
          // Trate o erro, se necessário
          console.log(error);
          console.log(xhr.responseText);

        }
      });}

      
    }
    // Função para carregar o conteúdo do modal usando AJAX
    function loadModalContent(url, modalId, id) {
      $.ajax({
        url: url,
        type: 'POST',
        data: { id: id, choice: '11' },
        success: function(response) {
          $(modalId + ' .modal-body').html(response);
        },
        error: function(xhr, status, error) {
          // Trate o erro, se necessário
          console.log(error);
          console.log(xhr.responseText);

        }
      });
    }

    // Captura o evento de abertura do modal e carrega o conteúdo
    $(document).on('click', '[data-bs-target="#modalItem1"]', function() {
      var url = $(this).data('bs-url');
      var targetModal = $(this).data('bs-target');
      var id = $(this).data('bs-id');
      loadModalContent(url, targetModal, id);
    });
    $(document).on('click', '[data-bs-target="#modalItem0"]', function() {
      var url = $(this).data('bs-url');
      var targetModal = $(this).data('bs-target');
      var id = $(this).data('bs-id');
      loadModalContent(url, targetModal, id);
    });
  </script>
</body>

</html>

              </div>
              <div id="tab6" class="tab-content" style="display: none;">
                  <!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
  <script src="https://npmcdn.com/flatpickr/dist/l10n/pt.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet" >

</head>
<body>
<div class="container mt-5" style="height: 100%;">

<h4>Documentos</h4>


  <!-- Botão "Clientes" -->
  <button type="button" class="btn btn-primary" onclick="openClientesModal()" style="width:100%">
    Clientes
  </button>

  <!-- Botão "Entregadores" -->
  <button type="button" class="btn btn-primary" onclick="openEntregadoresModal()" style="width:100%"> 
    Entregadores
  </button>

<!-- Modal "Clientes" -->
<div class="modal fade" id="modalClientes" tabindex="-1" aria-labelledby="modalClientesLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalClientesLabel">Lista de Clientes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="input-group mb-3">
          <input type="text" class="form-control" id="searchInputClientes" placeholder="Pesquisar cliente" aria-describedby="searchBtnClientes">
          <button class="btn btn-outline-secondary" type="button" id="searchBtnClientes">Pesquisar</button>
        </div>
        <ul class="list-group oyasumipunpun" id="clientesList">
          <li class="list-group-item" onclick="openPeriodoModal('cliente1')">Cliente 1</li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var searchInputClientes = document.getElementById('searchInputClientes');
    var clientesList = document.getElementById('clientesList').getElementsByTagName('li');

    document.getElementById('searchBtnClientes').addEventListener('click', filterClientesList);
    searchInputClientes.addEventListener('input', filterClientesList);

    function filterClientesList() {
      var searchValue = searchInputClientes.value.toLowerCase();
      
      Array.from(clientesList).forEach(function (cliente) {
        var text = cliente.innerText.toLowerCase();
        if (text.indexOf(searchValue) > -1) {
          cliente.style.display = '';
        } else {
          cliente.style.display = 'none';
        }
      });
    }
  });
</script>


  <div class="modal fade" id="modalEntregadores" tabindex="-1" aria-labelledby="modalEntregadoresLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEntregadoresLabel">Lista de Entregadores</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="input-group mb-3">
          <input type="text" class="form-control" id="searchInput" placeholder="Pesquisar entregador" aria-describedby="searchBtn">
          <button class="btn btn-outline-secondary" type="button" id="searchBtn">Pesquisar</button>
        </div>
        <ul class="list-group slamdunk" id="entregadoresList">
          <li class="list-group-item" onclick="openPeriodoModal('entregador1')">Entregador 1</li>

        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('searchInput');
    var entregadoresList = document.getElementById('entregadoresList').getElementsByTagName('li');

    document.getElementById('searchBtn').addEventListener('click', filterList);
    searchInput.addEventListener('input', filterList);

    function filterList() {
      var searchValue = searchInput.value.toLowerCase();
      
      Array.from(entregadoresList).forEach(function (entregador) {
        var text = entregador.innerText.toLowerCase();
        if (text.indexOf(searchValue) > -1) {
          entregador.style.display = '';
        } else {
          entregador.style.display = 'none';
        }
      });
    }
  });
</script>

  <!-- Modal "Período" -->
 <!-- Adicione os arquivos CSS e JavaScript do Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Modal "Período" -->
<div class="modal fade" id="modalPeriodo" tabindex="-1" aria-labelledby="modalPeriodoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPeriodoLabel">Selecione o Período</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="startDate">Início:</label>
          <input type="text" id="startDate" class="form-control dex">
        </div>
        <div class="mb-3">
          <label for="endDate">Fim:</label>
          <input type="text" id="endDate" class="form-control dex">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="openImportModal()">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal "Importar" -->
<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalImportLabel">Deseja importar como?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <button class="btn btn-primary" onclick="importAsPDF()">PDF</button>
        <button class="btn btn-primary" onclick="importAsExcel()">Excel</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<script>
  
  document.addEventListener('DOMContentLoaded', function () {
    var datepickers = document.querySelectorAll('.dex');
    datepickers.forEach(function (datepicker) {
      flatpickr(datepicker, {
        dateFormat: 'd/m/Y',
      });
    });
  });

  function openPeriodoModal(id) {
    $('#modalPeriodo').modal('show');
    $('#modalPeriodo').data('id', id);
  }

  function openImportModal() {
    $('#modalPeriodo').modal('hide');
    $('#modalImport').modal('show');
  }

  function importAsPDF() {
    var id = $('#modalPeriodo').data('id');
    id = id.replace("/", "__");
    var startDate = document.getElementById('startDate').value;
  var endDate = document.getElementById('endDate').value;
  if (selectedOption === 'clientes') {
    var choice = "2";
  } else if (selectedOption === 'entregadores') {
    var choice = "1";
  }
  
  // Separar dia, mês e ano da data de início
  var startDateParts = startDate.split('/');
  var startDay = startDateParts[0];
  var startMonth = startDateParts[1];
  var startYear = startDateParts[2];
  
  // Separar dia, mês e ano da data de fim
  var endDateParts = endDate.split('/');
  var endDay = endDateParts[0];
  var endMonth = endDateParts[1];
  var endYear = endDateParts[2];
  var url = "<?= base_url() ?>central/dados/" + id + "/" + startDay + "/" + startMonth + "/" + startYear + "/" + endYear + "/" + endMonth + "/" + endDay + "/" + choice;

// Redirecionar para a URL para baixar o arquivo
window.location.href = url;

    $('#modalImport').modal('hide');
  }

  function importAsExcel() {
    var id = $('#modalPeriodo').data('id');
    id = id.replace("/", "__");
    var startDate = document.getElementById('startDate').value;
  var endDate = document.getElementById('endDate').value;
  if (selectedOption === 'clientes') {
    var choice = "5";
  } else if (selectedOption === 'entregadores') {
    var choice = "4";
  }
  
  // Separar dia, mês e ano da data de início
  var startDateParts = startDate.split('/');
  var startDay = startDateParts[0];
  var startMonth = startDateParts[1];
  var startYear = startDateParts[2];
  
  // Separar dia, mês e ano da data de fim
  var endDateParts = endDate.split('/');
  var endDay = endDateParts[0];
  var endMonth = endDateParts[1];
  var endYear = endDateParts[2];
  var url = "<?= base_url() ?>central/dados/" + id + "/" + startYear + "/" + startMonth + "/" + startYear + "/" + endYear + "/" + endMonth + "/" + endDay + "/" + choice;

// Redirecionar para a URL para baixar o arquivo
window.location.href = url;

    $('#modalImport').modal('hide');
  }

    var selectedOption = '';
    function openClientesModal() {$.ajax({
        url: '<?= base_url('central/painel/documentos') ?>',
        method: 'POST',
        data: {choice: 'clientes'},
        success: function (response) {
          $('.oyasumipunpun').html(response);
          selectedOption = 'clientes';
        },
        error: function (error) {
          console.error('Erro ao enviar dados:', error);
        }
      });

      $('#modalClientes').modal('show');
    }

    function openEntregadoresModal() {
      $.ajax({
        url: '<?= base_url('central/painel/documentos') ?>',
        method: 'POST',
        data: {choice: 'entregador'},
        success: function (response) {
          $('.slamdunk').html(response);
          selectedOption = 'entregadores';
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Status:", jqXHR.status);
            console.error("Server Error:", jqXHR.responseText);
          }
      });
      $('#modalEntregadores').modal('show');
    }

    function openPeriodoModal(id) {
      $('#modalPeriodo').modal('show');
      $('#modalPeriodo').data('id', id);
    }

   </script>
</body>
</html>

        </div>
    </div>
    <div id="tab7" class="tab-content" style="display: none;">
  <div class="container mt-5" style="height: 100%;">
  <h1>Tabela de Clientes</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Nome / Empresa</th>
                    <th>CPF / CNPJ</th>
                    <th>Status</th>
                    <th>Ações</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cli) : ?>
                    <tr>
                        <td><?php echo isset($cli->nome) ? esc($cli->nome) : esc($cli->empresa); ?></td>
                        <td><?php echo isset($cli->cpf) ? esc($cli->cpf) : esc($cli->cnpj); ?></td>

                        <td><?php  if ($cli->status == "active"){echo "ativo";}else{echo $cli->status;}  ?></td>
                        <td>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detalhescli" onclick="loadData('<?php echo esc($cli->_id); ?>')">Detalhes</button>
                        </td>
                        <td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#contrato"  data-id="<?= esc($cli->_id) ?>"<?php if (isset($cli->contrato)): ?>data-link="<?= esc($cli->contrato) ?>"> Contrato<?php else: ?> Atribuir<?php endif ?></button></td>
                          <td><button type="button" class="btn btn-danger" data-id="<?= esc($cli->_id) ?>" onclick="deleteuser('<?= esc($cli->_id) ?>', '<?= isset($cli->nome) ? esc($cli->nome) : esc($cli->empresa) ?>')">Deletar</button></td>

                          
                        </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="modal fade" id="contrato" tabindex="-1" aria-labelledby="contratoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="contratoLabel">Contrato</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label for="link-contrato">Link do Contrato:</label>
        <input type="text" id="link-contrato" class="form-control">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="confirmar-contrato">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function deleteuser(id, nomezi) {
    if(confirm('Deletar usuário ' + nomezi + '?')){
  $.ajax({
    url: '<?= base_url("central/painel/user/delete") ?>',
    type: 'POST',
    data: { id: id },
    success: function(response) {
      if (response == "desativado"){
        location.reload();
      }
      console.log(response);
    },
    error: function(xhr, status, error) {
      console.log("Erro ao deletar: " + xhr.responseText);
    }
  });}else{
    return;
  }
}
$(document).ready(function() {
  var dataId;

  $('#contrato').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget);
    dataId = button.data('id');
    link = button.data('link');
    if (link) {
      $('#link-contrato').val(link);
    }
  });

  $('#confirmar-contrato').click(function() {
    var linkContrato = $('#link-contrato').val();
    $.ajax({
      url: '<?= base_url('central/painel/contrato') ?>', // Substitua pelo caminho para o arquivo PHP que irá tratar a solicitação
      method: 'POST',
      data: { link: linkContrato, dataId: dataId },
      success: function(response) {
        // Trate a resposta da solicitação aqui
        alert(response);
        
      },
      error: function(jqXHR, textStatus, errorThrown) {
            console.error("Status:", jqXHR.status);
            console.error("Server Error:", jqXHR.responseText);
          }
    });
  });
});
</script>
    
  </div>    
    </div>
    <div class="modal fade" id="detalhescli" tabindex="-1" aria-labelledby="detalhescliModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalhescliModalLabel">Detalhes do Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modalContent"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
function loadData(clientId) {
    $.ajax({
        url: '<?= base_url('central/painel/cli') ?>',
        method: 'POST',
        data: { id: clientId },
        success: function(response) {
            $('#modalContent').html(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Status:", jqXHR.status);
            console.error("Server Error:", jqXHR.responseText);
          }
    });
}

    </script>
    <!--Container Main end-->
    <script>
    document.addEventListener("DOMContentLoaded", function(event) {

        const showNavbar = (toggleId, navId, bodyId, headerId) => {
            const toggle = document.getElementById(toggleId),
                nav = document.getElementById(navId),
                bodypd = document.getElementById(bodyId),
                headerpd = document.getElementById(headerId)

            // Verificar se todas as variáveis existem
            if (toggle && nav && bodypd && headerpd) {
                toggle.addEventListener('click', () => {
                    // Mostrar/ocultar a barra de navegação
                    nav.classList.toggle('show');
                    // Alterar o ícone do botão de menu
                    toggle.classList.toggle('bx-x');
                    // Adicionar/remover padding ao conteúdo do corpo
                    bodypd.classList.toggle('body-pd');
                    // Adicionar/remover padding ao cabeçalho
                    headerpd.classList.toggle('body-pd');
                });
            }
        }

        showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header');

        /*===== LINK ACTIVE =====*/
        const linkColor = document.querySelectorAll('.nav_link');

        function colorLink() {
            if (linkColor) {
                linkColor.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                const tabId = this.getAttribute('data-tab');
                const tabContent = document.querySelectorAll('.tab-content');
                
                tabContent.forEach(tab => {
                    if (tab.getAttribute('id') === tabId) {
                        tab.style.display = 'block';
                    } else {
                        tab.style.display = 'none';
                    }
                });
            }
        }
        linkColor.forEach(l => l.addEventListener('click', colorLink));
    });
    </script>
</body>
</html>
