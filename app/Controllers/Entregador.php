<?php

namespace App\Controllers;

use MongoDB;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\Binary;
use MongoDB\Client;
use MongoDB\Driver\Exception\Exception as MongoDBException;
use CodeIgniter\Controller;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\AntiXSS;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Entregador extends BaseController
{ 
    public function entregador()
    {   
      $criar = getenv('data_checkeb');
      $host = getenv('host_db');
      $ambiente = getenv('ambiente');
      $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
      $collection = $client->$ambiente->entregador;
      $pass = session()->get('senha');
      $login = session()->get('user_e');
      $check = $collection->findOne(['user_e' => $login, 'senha' => $pass, 'status' => 'ativo']);
      if ($check) {

        return view('entregador/painel', ['token' => $check->token]);
      }
      else{
        return view('errors/html/error_404');
      }
    
    }

    public function list()
    {   
            $criar = getenv('data_checkeb');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->entregador;
            $pass = session()->get('senha');
            $login = session()->get('user_e');
            $check = $collection->findOne(['user_e' => $login, 'senha' => $pass, 'status' => 'ativo']);
            if ($check) {
              $criar = getenv('data_base');
              $host = getenv('host_db');
              $ambiente = getenv('ambiente');
              $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
              $collection = $client->$ambiente->produto;


                // Verificar se os campos necessários foram enviados
                if (isset($_FILES['foto_entrega']) && isset($_POST['nome_recebedor']) && isset($_POST['documento_recebedor']) && isset($_POST['obs'])) {
                  // Obter os dados enviados
                  $foto_entrega = $_FILES['foto_entrega'];
                  $nome_recebedor = esc($_POST['nome_recebedor']);
                  $documento_recebedor = esc($_POST['documento_recebedor']);
                  $obs = esc($_POST['obs']);
                  $id = esc($_POST['deliveryId']);
              
                  // Inserir os dados no MongoDB
                  $documento = array(
                    'foto_entrega' => base64_encode(file_get_contents($foto_entrega['tmp_name'])),
                    'documento_recebedor' => $documento_recebedor,
                    'nome_recebedor' => $nome_recebedor,
                    'obs_entrega' => $obs,
                    'status' => 'entregue',
                    'andamento' => 'off',
                    'entrega_data' => date('Y-m-d H:i:s')
                );
                
                $collection->updateOne(['rastreio' => $id], ['$set' => $documento]);
                
                  // Retornar uma resposta de sucesso
                echo "concluido";
                  die();
                }
              if (isset($_POST['choice'])){
                if($_POST['choice']=="14"){
                  $criar = getenv('data_entregador');
                  $host = getenv('host_db');
                  $ambiente = getenv('ambiente');
                  $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                  $collection = $client->$ambiente->produto;
                  $cursor = $collection->find(['entregador_id' => $check->token, 'status' => "A caminho de coleta"]);
                  $html = "";
                  $contagem = array();

                  foreach ($cursor as $pedido) {
                      $cliente = $pedido['cliente_id']; // substitua 'cliente' pelo campo correto que armazena o nome do cliente
                      $clienteString = strval($cliente); // converte o valor para uma string
                      
                      if (isset($contagem[$clienteString])) {
                          $contagem[$clienteString]++;
                      } else {
                          $contagem[$clienteString] = 1;
                      }
                  }
                  $collection = $client->$ambiente->cliente;
                  
                  foreach ($contagem as $cliente => $quantidade) {
                  $l = $collection->findOne(['_id' => new MongoDB\BSON\ObjectID($cliente)]);
                    if(isset($l->nome)){
                      $nome = $l->nome;
                    }else{
                      $nome = $l->empresa;
                    }
                       $html .= '<div class="tenrue" data-id="'. $l->endereco .'"></div>
                <ul class="list-group">
                <li class="list-group-item black-text"><strong>Cliente: </strong> '. $nome .'</li>
                <li class="list-group-item black-text"><strong>Endereço de busca: </strong>'. $l->endereco .', '. $l->numero .'</li>
                <li class="list-group-item black-text"><strong>'. $l->cep .'</strong> - '. $l->bairro .'</li>
                <li class="list-group-item black-text"><strong>Quantidade de objetos: '. $quantidade.'  </strong></li>
                <button id="confirmcoleta" type="button" class="btn btn-primary"  onclick="coleta(\''. $cliente . '\', \'' . $quantidade . '\')" >Confirmar coleta</button></ul>
                ';
                  }
                  echo $html;
                  
                  $collection = $client->$ambiente->produto;
                  $cursor = $collection->find(['entregador_id' => $check->token, 'status' => "Coletado, a caminho da central"]);
                  $contagem = array();
                  $html = "";

                  foreach ($cursor as $pedido) {
                      $cliente = $pedido['cliente_id']; 
                      $clienteString = strval($cliente); // converte o valor para uma string
                      
                      if (isset($contagem[$clienteString])) {
                          $contagem[$clienteString]++;
                      } else {
                          $contagem[$clienteString] = 1;
                      }
                  }
                  $collection = $client->$ambiente->cliente;
                  
                  foreach ($contagem as $cliente => $quantidade) {
                  $l = $collection->findOne(['_id' => new MongoDB\BSON\ObjectID($cliente)]);
                    if(isset($l->nome)){
                      $nome = $l->nome;
                    }else{
                      $nome = $l->empresa;
                    }
                       $html .= '
                     <li class="delivery-item" style="display: flex; align-items: center; flex-wrap: wrap; border-bottom: 1px solid #000; padding-bottom: 10px;">
                       <p style="margin-right: 10px;"> Entregar na central, de: '. $nome . '</p>
                       <div style="margin-right: 10px;">
                           Setor: <strong>'. $l->setor .'  |  </strong>'. $l->endereco .' | Qnt: '. $quantidade .' | 
                           <strong>Cep:</strong> '. $l->cep .'
                       </div>
                           <i class="fas fa-info-circle"></i>
                       </button>
                   </li>
                ';
                  }
                  echo $html;
                  


                 
                   die();
                }
                $rastreio =  $_POST['id'];
                $criar = getenv('data_base');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->produto;
                $product = $collection->findOne(['rastreio' => $rastreio]);
                $collection = $client->$ambiente->cliente;
                $cliente = $collection->findOne(['_id' => new MongoDB\BSON\ObjectID($product->cliente_id)]);
                $responsavel =  $cliente->responsavel;
                $busca = $cliente->endereco;
                $numero = $cliente->numero;
                $check ='';
                if(isset( $cliente->empresa)){
                  $empresa= $cliente->empresa;
                  $chekc = "Empresa: ";
                }else{
                  $empresa= $cliente->nome;
                  $chekc = "Cliente: ";
                }
                $collection = $client->$ambiente->produto;
                $product = $collection->findOne(['rastreio' => $rastreio]);
                $tentativas = "";
                if (isset($product->ocorrencia_entrega1)){
                  $tentativas .= '<li class="list-group-item black-text">'. $product->ocorrencia_entrega1 .'</li>';
                  
                  if (isset($product->ocorrencia_entrega2)){
                  $tentativas .= '<li class="list-group-item black-text">'. $product->ocorrencia_entrega2 .'</li>';
                    
                    if (isset($product->ocorrencia_entrega3)){
                  $tentativas .= '<li class="list-group-item black-text">'. $product->ocorrencia_entrega3 .'</li>';

                    }}

                }else{
                  $tentativas .= '<li class="list-group-item black-text"><strong>Sem tentativas</strong></li>';
                }

                $html = '<div class="tenrue" data-id="'. $product->rastreio .'"></div>
                <ul class="list-group">
                <li class="list-group-item black-text"><strong>Responsavel: </strong> '. esc($responsavel) .'</li>
                <li class="list-group-item black-text"><strong>Endereço de busca: </strong>'. $busca .', '. esc($numero) .'</li>
                <li class="list-group-item black-text"><strong>'. esc($chekc) .'</strong>'. esc($empresa) .'</li>
                '. $tentativas .'
                </ul>
                ';
                echo $html;
                
                die();
              }
                $id = $check->token;
                $criar = getenv('data_entregador');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->produto;
                $products = $collection->find(['entregador_id_entrega' => $id, 'status' => 'A caminho de entrega']);
                $html = "";
                $collection = $client->$ambiente->cliente;
                foreach($products as $p ){
                $dono = $collection->findOne(['_id' => $p->cliente_id]);
                if(isset($dono->nome)){
                  $res = $dono->nome;
                }else{
                  $res = $dono->empresa;
                }
                   if($p->status == 'A caminho de entrega'){                
                    $html .= '<li class="delivery-item" style="display: flex; align-items: center; flex-wrap: wrap; border-bottom: 1px solid #000; padding-bottom: 10px;">
                    <p style="margin-right: 10px;">'. $p->rastreio . '</p>
                    <div style="margin-right: 10px;">
                        nome: <strong>'. esc($res) .'  |  </strong>'. esc($p->endereco) .'
                        <strong>Cep:</strong> '. $p->cep .'
                    </div>
                    <button  class="btn" style="margin-left: 10px;" data-toggle="modal" data-target="#exampleModal" data-id="'. $p->rastreio .'" onclick="preload(\''. $p->rastreio .'\')">
                        <i class="fas fa-check"></i>
                    </button>
                </li>';
    }else{
                  //   $html .= '<li class="delivery-item">
                  //   <strong>Entrega:</strong>'.$p->destinatario.'  cabe em moto: '. $p->cabe_moto .'
                  //   <button class="btn" data-toggle="modal" data-target="#exampleModal" data-id="'. $p->rastreio .'" onclick="loadmodal(\''. $p->rastreio .'\')">
                  //     <i class="fas fa-info-circle"></i>
                  //   </button>
                  // </li>
                  // ';
                }
                }
                if($html != ""){
                }else{
                  $html = '			<li class="delivery-item">
                  Sem entregas
                  <button class="btn" >
                  <i class="fas fa-info-circle"></i>
                  </button>
                </li>';
                }
                echo $html;
                die();
            }
        die();
    }

    public function ocorrencia()
    {            
      function generateUniqueFileName($originalFileName, $collection)
      {
          $fileName = $originalFileName;
          $counter = 1;

          while ($collection->countDocuments(['filename' => $fileName]) > 0) {
              $fileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '_' . $counter++ . '.' . pathinfo($originalFileName, PATHINFO_EXTENSION);
          }

          return $fileName;
      }

      $criar = getenv('data_checkeb');
      $host = getenv('host_db');
      $ambiente = getenv('ambiente');
      $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
      $collection = $client->$ambiente->entregador;
      $pass = session()->get('senha');
      $login = session()->get('user_e');
      $check = $collection->findOne(['user_e' => $login, 'senha' => $pass, 'status' => 'ativo']);
      if ($check) {
        $criar = getenv('data_entregador');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->produto;
      $rastreio = esc($_POST['dataId']);
      $obs = esc($_POST['observation']);
      $tipo = esc($_POST['tipo']);
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
          // Obtenha o caminho temporário do arquivo
          $tempFilePath = $_FILES['file']['tmp_name'];
        
          // Obtenha o nome original do arquivo
          $originalFileName = $_FILES['file']['name'];
        
          // Verifique se já existe um arquivo com o mesmo nome
          $fileName = generateUniqueFileName($originalFileName, $collection);
        
          // Leia os bytes da imagem
          $image = file_get_contents($tempFilePath);
        
          // Verifique se a leitura do arquivo foi bem-sucedida
          if ($image !== false) {
              try {
                  // Crie um objeto Binary com os bytes da imagem
                  $binaryImage = new Binary($image, Binary::TYPE_GENERIC);
              } catch (MongoDBException $e) {
                  // Trate as exceções do MongoDB
                  echo "contate um administrador";
              }
          }
      }
      $result = $collection->findOne(['rastreio' => $rastreio]);   
      if ($tipo == 'Ausente') {
      $tipo = str_replace("_", " ", $tipo);
      if ($result->status == 'A caminho de entrega'){
        if (!isset($result->ocorrencia_entrega1)) {
          $tipo = $tipo . ' ' . date('Y-m-d H:i:s');
          $collection->updateOne(['rastreio' => $rastreio], ['$set' => ['ocorrencia_entrega1' => $tipo, 'oeft1' => $binaryImage]]);
          echo 'okay';
          die();
      } elseif (!isset($result->ocorrencia_entrega2)) {
          $tipo = $tipo . ' ' . date('Y-m-d H:i:s');
          $collection->updateOne(['rastreio' => $rastreio], ['$set' => ['ocorrencia_entrega2' => $tipo,  'oeft2' => $binaryImage]]);
          echo 'okay';
          die();
      } elseif (!isset($result->ocorrencia_entrega3)) {
          $tipo = $tipo . ' ' . date('Y-m-d H:i:s');
          $collection->updateOne(['rastreio' => $rastreio], ['$set' => ['ocorrencia_entrega3' => $tipo,  'oeft3' => $binaryImage]]);
          echo 'okay';
          die();
      } else { 
        echo 'Retorne para a central';
        die();
      }
      }}elseif ($value == "Não_existe" || $value == "Endereco_insuficiente" || $value == "Recusado" || $value == "Mudou_se" || $value == "Extravio") {
        $tipo = $tipo . ' ' . date('Y-m-d H:i:s');
        $collection->updateOne(['rastreio' => $rastreio], ['$set' => ['ocorrencia_entrega1' => $tipo, 'ocorrencia_entrega2' => $tipo, 'ocorrencia_entrega3' => $tipo,  'cancelft' => $binaryImage]]);
        echo 'okay';
        die();
      }
      }
    }

    public function baseset(){
      $criar = getenv('data_checkeb');
      $host = getenv('host_db');
      $ambiente = getenv('ambiente');
      $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
      $collection = $client->$ambiente->entregador;
      $pass = session()->get('senha');
      $login = session()->get('user_e');
      $check = $collection->findOne(['user_e' => $login, 'senha' => $pass, 'status' => 'ativo']);
      if ($check) {
        $criar = getenv('data_entregador');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->produto;  
        $clid = esc($_POST['clienteId']);
        $value = $collection->findOne(['entregador_id' => $check->token, 'cliente_id' => new MongoDB\BSON\ObjectID($clid)]);

        $filter = [
          'entregador_id' => $check->token,
          'cliente_id' => new MongoDB\BSON\ObjectID($clid),
          'status' => 'A caminho de coleta'
      ];
      
      $update = [
          '$set' => ['status' => 'Coletado, a caminho da central', 'data_coleta' => date('Y-m-d H:i:s')]
        ];
      
      $result = $collection->updateMany($filter, $update);
        echo "concluido";
        
      }  
      
    }
    
    
}
