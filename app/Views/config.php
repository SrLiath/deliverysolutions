<div class="container" style="background-color: white;">
        <h1>Alterar Dados</h1>
        <form id="form-dados">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" class="form-control" id="email" name="email" value="<?= esc($config->email) ?>">
            </div>
            <?php if(isset(esc($config->nome))): ?>
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= esc($config->nome) ?>">
            </div>
            <?php else: ?>
                <div class="form-group">
                <label for="empresa">Empresa:</label>
                <input type="text" class="form-control" id="empresa" name="empresa" value="<?= esc($config->empresa) ?>">
            </div>
            <?php endif ?>
            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <input type="text" class="form-control" id="endereco" name="endereco" value="<?= esc($config->endereco) ?>">
            </div>
            <div class="form-group">
                <label for="numero">Número:</label>
                <input type="text" class="form-control" id="numero" name="numero" value="<?= esc($config->numero) ?>">
            </div>
            <div class="form-group">
                <label for="complemento">Complemento:</label>
                <input type="text" class="form-control" id="complemento" name="complemento" value="<?= esc($config->complemento) ?>">
            </div>
            <div class="form-group">
                <label for="cep">CEP:</label>
                <input type="text" class="form-control" id="cep" name="cep" value="<?= esc($config->cep) ?>">
            </div>
            <div class="form-group">
                <label for="bairro">Bairro:</label>
                <input type="text" class="form-control" id="bairro" name="bairro" value="<?= esc($config->bairro) ?>">
            </div>
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" class="form-control" id="telefone" name="telefone" value="<?= esc($config->telefone) ?>">
            </div>
            <div class="form-group">
                <label for="celular">Celular:</label>
                <input type="text" class="form-control" id="celular" name="celular" value="<?= esc($config->celular) ?>">
            </div>
            <div class="form-group">
                <label for="responsavel">Responsável:</label>
                <input type="text" class="form-control" id="responsavel" name="responsavel" value="<?= esc($config->responsavel) ?>">
            </div>
            <button type="button" class="btn btn-primary" id="btn-alterar">Alterar Dados</button>
        </form>
    </div>

    <script>
        esc($(document).ready)(function() {
            esc($('#btn-alterar').)click(function() {
                var data = esc($('#form-dados').)serialize();
                esc($.ajax({
                    url): '<?= base_url('dados') ?>',
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        alert("Dados atualizados com sucesso");
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                    console.log("Erro ao enviar o arquivo: " + xhr.responseText);
                }
                });
            });
        });
    </script>
