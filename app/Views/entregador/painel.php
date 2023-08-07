<!DOCTYPE html>
<html>
<head>
  <title> Entregador - Real Time Express Solutions </title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>  
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
    /* Estilos CSS opcionais para as tabs */
    body {
		font-family: 'Open Sans', sans-serif;
		margin: 0; /* Adicionado para remover margens padrão do body */
		background-color: #222;
		color: #fff;
	  }
  
	  .header {
		background-color: #1a1a1a;
		text-align: center;
		padding: 20px;
		font-size: 24px;
		font-weight: bold;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
	  }
  
	  .exit-button {
		position: fixed;
		top: 10px;
		right: 10px;
		background-color: transparent;
		border: none;
		color: #fff;
		font-size: 24px;
		cursor: pointer;
	  }
  
	  .exit-button i {
		margin-right: 5px;
	  }
  
	  .tab {
		opacity: 0;
		visibility: hidden;
		position: absolute;
		left: 0;
		top: 80px; /* Espaço para o header */
		width: 100%;
		height: calc(100% - 80px); /* Altura da tela menos o espaço do header */
		transition: opacity 0.3s ease-in-out;
	  }
  
	  .tab.active {
		opacity: 1;
		visibility: visible;
	  }
  
	  .footer {
		background-color: #1a1a1a;
		text-align: center;
		position: fixed;
		bottom: 0;
		width: 100%;
		padding: 10px;
		display: flex;
		justify-content: space-around;
		align-items: center;
	  }
  
	  .footer i {
		color: #888;
		font-size: 24px;
		margin-right: 5px;
		cursor: pointer;
	  }
  
	  .footer span {
		font-size: 14px;
		opacity: 0;
		transition: opacity 0.3s ease-in-out;
	  }
  
	  .footer .active span {
		opacity: 1;
	  }
  
	  /* Adicionado para dar espaço abaixo do conteúdo */
	  .content {
		margin-bottom: 50px;
	  }
  
	  /* Estilos CSS para a lista de entregas */
	  .delivery-list {
		list-style: none;
		padding: 0;
		margin: 0;
	  }
	  .jotaro{
		list-style: none;
		padding: 0;
		margin: 0;
	  }
  
	  .delivery-item {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 10px;
		border-bottom: 1px solid #555;
	  }
  
	  .delivery-item:last-child {
		border-bottom: none;
	  }
  
	  .delivery-item .btn {
		background-color: transparent;
		border: none;
		color: #fff;
		font-size: 18px;
		cursor: pointer;
		transition: color 0.3s ease-in-out;
	  }
  
	  .delivery-item .btn i {
		margin-right: 5px;
	  }
  
	  .delivery-item .btn:hover {
		color: #aaa;
	  }
  
	  /* Estilos CSS para o modal */
	  .modal-header {
		background-color: #333;
		color: #fff;
	  }
  
	  .modal-title {
		color: #fff;
	  }
  
	  .modal-content {
		background-color: #1a1a1a;
		color: #fff;
	  }
  
	  .modal-footer {
		background-color: #333;
	  }
  
	  /* Estilos CSS para o seletor de opções */
	  .options-list {
		margin-top: 20px;
	  }
  
	  .options-list .form-group {
		margin-bottom: 10px;
	  }
  
	  .options-list label {
		display: block;
		font-weight: bold;
	  }
  
	  /* Estilos CSS para os calendários */
	  .date-picker-container {
		display: flex;
		justify-content: space-between;
	  }
  
	  .date-picker-container .form-group {
		width: 48%;
	  }
  
	  /* Estilos CSS para os botões de seleção de tema */
	  .theme-buttons {
		margin-top: 20px;
	  }
  
	  .theme-buttons .btn {
		margin-right: 10px;
	  } 

	  .black-text {
      color: black;
    }
    @media (max-width: 600px)
{
  body
   {
    
    font-size: 100%;
   }
}
  </style>
  <script>
    var manualOption = document.getElementById("manual");

	function openTab(tabName, element) {
		var i, tabs;
		tabs = document.getElementsByClassName("tab");
		for (i = 0; i < tabs.length; i++) {
		  tabs[i].classList.remove("active");
		}
		document.getElementById(tabName).classList.add("active");
  
		var tabElements = document.getElementsByClassName("tab-link");
		for (i = 0; i < tabElements.length; i++) {
		  tabElements[i].classList.remove("active");
		}
		element.classList.add("active");
  
		var tabTitle = element.querySelector("span").textContent;
		document.querySelector(".batman").textContent = tabTitle;
  
		if (tabName === "completedTab") {
		  initializeFlatpickr();
		  showManualOptionFields();
		} else {
		  hideManualOptionFields();
		}
	  }

    function exitApp() {
      // Lógica para sair do aplicativo
	  window.location.href = "<?= base_url('clean') ?>";
	}

    function initializeFlatpickr() {
      flatpickr("#datePickerStart", {
        enableTime: false,
        dateFormat: "d/m/Y",
        locale: "pt"
      });

      flatpickr("#datePickerEnd", {
        enableTime: false,
        dateFormat: "d/m/Y",
        locale: "pt"
      });
    }
  </script>
</head>
<body>
  <div class="header">
	<div class="batman">
    Painel
	</div>
    <button class="exit-button" onclick="exitApp()">
      <i  class="fas fa-door-open"> </i>Sair
    </button>
  </div>
<div class="mestre" data-id = "<?php echo $token; ?>"><div>
  <div class="content">
    <div class="tab active" id="deliveriesTab">
		<ul class="delivery-list">
			<li class="delivery-item">
			  <button class="btn" data-toggle="modal" data-target="#exampleModal">
				<i class="fas fa-info-circle"></i>
			  </button>
			</li>
		  </ul>
	</div>
    <div class="tab" id="centralTab">

	<ul class="jotaro">
	<li class="delivery-item">
  <button class="btn" data-toggle="modal" data-target="#confirmmodal" data-id="">
    <i class="fas fa-info-circle"></i>
  </button>
</li>
<div class="tenrue" data-id=""></div>
<ul class="list-group">
</ul>

		  </ul>

	</div>
    <div class="tab" id="completedTab">
      <h2>Entregas Feitas</h2>
      <div class="options-list">
        <div class="form-group">
          <label for="frequency">Selecione o inicio e fim:</label>
        </div>
      </div>
      <div id="manualFields" style="display: none;">
        <input type="text" id="datePickerStart" placeholder="Data de início" class="form-control">
        <input type="text" id="datePickerEnd" placeholder="Data de fim" class="form-control">
      </div>
	  <button class ="btn btn-primary" onclick="construirURL()"> Confirmar </button>
    </div>
<script>
 function construirURL() {
    var startDate = document.getElementById("datePickerStart").value;
    var endDate = document.getElementById("datePickerEnd").value;
    
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
    
    var id = document.querySelector('.mestre').getAttribute('data-id');
    var choice = "3"; // Substitua pelo valor desejado
    
    var url = "<?= base_url() ?>central/dados/" + id + "/" + startYear + "/" + startMonth + "/" + startDay + "/" + endYear + "/" + endMonth + "/" + endDay + "/" + choice;
	window.location.href = url;
    
  }

  function hideManualOptionFields() {
    document.getElementById("manualFields").style.display = "none";
  }

  function showManualOptionFields() {
    document.getElementById("manualFields").style.display = "block";
  }
</script>
    <div class="tab" id="settingsTab">Adição App mobile</div>
  </div>
  

  <div class="footer">
    <div class="tab-link active" onclick="openTab('deliveriesTab', this)">
      <i class="fas fa-truck"></i>
      <span>Entregas</span>
    </div>
    <div class="tab-link" onclick="openTab('centralTab', this)">
      <i class="fas fa-server"></i>
      <span>Coletas</span>
    </div>
    <div class="tab-link" onclick="openTab('completedTab', this)">
      <i class="fas fa-check-circle"></i>
      <span>Feitas</span>
    </div>
    <div class="tab-link" onclick="openTab('settingsTab', this)">
      <i class="fas fa-cog"></i>
      <span>Configurações</span>
    </div>
  </div>
    <!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detalhes da entrega</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body content-list">
        <p>Informações detalhadas sobre a entrega...</p>
      </div>
      <div class="modal-footer">
         <div class="mr-auto">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmmodal">Confirmar</button>
        </div>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ausenteModal" >Ocorrências</button>
      </div>
    </div>
  </div>
</div>
   <!-- Modal -->
<div class="modal fade" id="confirmmodal" tabindex="-1" role="dialog" aria-labelledby="confirmmodalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmmodalLabel">Confirmação da entrega</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-bodye">
		<div class = 'deliveryform'>
        <form id="deliveryForm" >
          <div class="form-group">
            <label for="foto_entrega">Foto de entrega:</label>
            <input type="file"accept="image/*" capture="camera"  class="form-control-file" id="foto_entrega" required>
          </div>
          <div class="form-group">
            <label for="documento_recebedor">Documento de quem recebeu:</label>
            <input type="text" class="form-control" id="documento_recebedor" required>
          </div>
          <div class="form-group">
            <label for="nome_recebedor">Nome de quem recebeu:</label>
            <input type="text" class="form-control" id="nome_recebedor" required>
          </div>
		  <div class="form-group">
            <label for="obs">Observação:</label>
            <textarea class="form-control" id="obs"></textarea>
          </div>
        </form>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="confirmButton">Confirmar</button>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>


<script>
	function coleta(clienteId, qnt){
    Swal.fire({
  title: "Confirmação",
  text: "Todos os " + qnt + " produtos foram coletados?",
  icon: "question",
  showCancelButton: true,
  confirmButtonText: "Sim",
  cancelButtonText: "Não",
}).then((result) => {
  if (result.isConfirmed) {
    $.ajax({
  url: "<?= base_url('entregador/base') ?>",
  type: 'POST',
  data: {
    clienteId: clienteId,
  },
  success: function(response) {
  },
  error: function(xhr, status, error) {
    console.log(xhr.responseText);
  }
});

  } else {
    return; // Retorna caso a opção "Não" seja selecionada
}})
  }
	
var luuk;
// Adiciona um ouvinte de evento para o clique no botão
$(function() {
  $(document).on('click', '#confirml', function() {
    var dataId = $(this).data('id');
    $('#deliveryForm').data('id', dataId);
  });
});

function preload(id){
  luuk = id;
  loadmodal(luuk);
}



    $("#confirmButton").click(function() {
      // Obter os dados do formulário
      var fotoEntrega = $("#foto_entrega")[0].files[0];
      var documentoRecebedor = $("#documento_recebedor").val();
      var nomeRecebedor = $("#nome_recebedor").val();
	  var obs = $('#obs').val();
      var deliveryId = luuk;

      // Criar um objeto FormData e adicionar os dados
      var formData = new FormData();
      formData.append("foto_entrega", fotoEntrega);
      formData.append("documento_recebedor", documentoRecebedor);
      formData.append("nome_recebedor", nomeRecebedor);
      formData.append("deliveryId", deliveryId);
      formData.append("obs", obs);


      // Enviar os dados usando AJAX
      $.ajax({
        url: "<?= base_url('list') ?>",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {

   if(response == "concluido"){
	location.reload();
   }
        },
		error: function(jqXHR, textStatus, errorThrown) {
            console.error("Status:", jqXHR.status);
            console.error("Server Error:", jqXHR.responseText);
          }
      });
    });
  
</script>


<div class="modal fade" id="ausenteModal" tabindex="-1" role="dialog" aria-labelledby="ausenteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ausenteModalLabel">Ocorrências</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="ocorrencia">
        <div class="form-group">  
          <label for="status_entrega">Status da entrega:</label>
          <select id="status_entrega" class="form-control">
            <option value="Ausente">Ausente</option>
            <option value="Não_existe">Não existe o número</option>
            <option value="Endereco_insuficiente">Endereço insuficiente</option>
            <option value="Recusado">Recusado</option>
            <option value="Mudou_se">Mudou-se</option>
            <option value="Extravio">Extravio</option>
          </select>
        </div>

          <div class="form-group">
            <label for="observationInput">Observação</label>
            <input type="text" class="form-control" id="observationInput" placeholder="Digite uma observação">
          </div>
          <div class="form-group">
            <label for="photoInput">Foto</label>
            <input type="file" accept="image/*" capture="camera" class="form-control-file" id="photoInput">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="ocorrencia()">Salvar</button>
      </div>
    </div>
  </div>
</div>
	  <script>
		    function ocorrencia() {
          Swal.fire({
  title: "Confirmação",
  text: "Deseja atribuir essa ocorrência?",
  icon: "success",
  showCancelButton: true,
  confirmButtonText: "Sim",
  cancelButtonText: "Não",
}).then((result) => {
  if (result.isConfirmed) {

    var fileInput = document.getElementById("photoInput");
					var file = fileInput.files[0];
									
					var formData = new FormData();
					formData.append("file", file);
									
					var divTenrue = document.querySelector('.tenrue');
					var dataId = divTenrue.getAttribute('data-id');
					var observation = document.getElementById("observationInput").value;
          var tipo = document.getElementById("status_entrega").value;
									
					formData.append("dataId", dataId);
					formData.append("observation", observation);
          formData.append("tipo", tipo);
          $.ajax({
        url: '<?= base_url('entregador/ocorrencia') ?>',
        method: 'POST',
		data: formData,
  		processData: false,
  		contentType: false,
        success: function(response) {
		  if (response == "okay"){
			location.reload();
      return;
		  }
      alert(response);
        },
		error: function(jqXHR, textStatus, errorThrown) {
            console.error("Status:", jqXHR.status);
            console.error("Server Error:", jqXHR.responseText);
          }
      });

  } else {
    return; // Retorna caso a opção "Não" seja selecionada
}})
          
			
    }
		function loadmodal(id) {
      $.ajax({
        url: '<?= base_url('list') ?>',
        method: 'POST',
		data:{choice: '1', id: id},
        success: function(response) {
          // Código para exibir o modal com o conteúdo retornado na resposta
		  $('.content-list').html(response);
		
        },
		error: function(jqXHR, textStatus, errorThrown) {
            console.error("Status:", jqXHR.status);
            console.error("Server Error:", jqXHR.responseText);
          }
      });
    }
		$(document).ready(function() {
			
		      setInterval(function() {
		        $.ajax({
		          url: '<?= base_url('list') ?>',
		          method: 'POST',
		          success: function(response) {
		            $('.delivery-list').html(response);
		          },
				  error: function(jqXHR, textStatus, errorThrown) {
            console.error("Status:", jqXHR.status);
            console.error("Server Error:", jqXHR.responseText);
          }
		        });
		      }, 2000); 
			  setInterval(function() {
		        $.ajax({
		          url: '<?= base_url('list') ?>',
		          method: 'POST',
				  data: {choice: "14"},
		          success: function(response) {
		            $('.jotaro').html(response);
		          },
				  error: function(jqXHR, textStatus, errorThrown) {
            console.error("Status:", jqXHR.status);
            console.error("Server Error:", jqXHR.responseText);
          }
		        });
		      }, 2000); 
		    });

	  </script>
</body>
</html>
