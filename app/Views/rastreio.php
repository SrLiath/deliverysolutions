<!DOCTYPE html>
<html>
<head>
  <script src="https://Deliveryexpresssolutions.com.br/assets/js/jsQR.js"></script>
  <style>
    .container {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
    }

    .rounded-div {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-4 col-md-offset-4 center-div">
        <div class="rounded-div">
          <form>
            <div class="form-group">
              <label for="rastreio">Insira o código de rastreio ou leia o QR code:</label>
              <input type="text" class="form-control" id="rastreio" placeholder="Insira o código de rastreio">
              <input type="file" accept="image/*" capture="environment" id="qrInput" style="display: none">
              <label for="qrInput" class="btn btn-primary">Ler QR Code</label>
            </div>
            <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#resultModal">Enviar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="resultModalLabel">Resposta do Rastreio</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('form').submit(function(event) {
        event.preventDefault();

        var codigoRastreio = $('#rastreio').val();
        $('#loading-overlay').show();

        $.ajax({
          url: '<?= base_url('rastreio') ?>',
          type: 'post',
          data: { codigo: codigoRastreio },
          success: function(response) {
            $('#resultModal .modal-body').html(response);
            $('#resultModal').modal('show');
            $('#loading-overlay').hide();
            
          },

          error: function(jqXHR, textStatus, errorThrown) {
            console.error("Status:", jqXHR.status);
            console.error("Erro do servidor:", jqXHR.responseText);
            alert('Ocorreu um erro durante a solicitação ao servidor.');
            $('#loading-overlay').hide();

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
    });
  </script>
</body>
</html>
