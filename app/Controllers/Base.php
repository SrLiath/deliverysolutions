<?php

namespace App\Controllers;

use MongoDB;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\Binary;
use MongoDB\Client;
use MongoDB\Driver\Exception\Exception as MongoDBException;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\AntiXSS;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;
use Mpdf\Tag\InlineTag;


class Base extends BaseController
{
    public function painel()
    {
        $user = session()->get('user_b');
        $pass = session()->get('senha');
        $criar = getenv('data_checkeb');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->base;
        $customer = $collection->findOne(['$and' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo']]]);
        if ($customer) {
            function generateUniqueFileName($originalFileName, $collection)
            {
                $fileName = $originalFileName;
                $counter = 1;

                while ($collection->countDocuments(['filename' => $fileName]) > 0) {
                    $fileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '_' . $counter++ . '.' . pathinfo($originalFileName, PATHINFO_EXTENSION);
                }

                return $fileName;
            }

            function createDocument($login, $senha, $nome, $endereco, $numero, $complemento, $cep, $telefone, $cpfcnpj, $permissoes, $token)
            {
                return [
                    'user_b' => $login,
                    'senha' => $senha,
                    'nome' => $nome,
                    'endereco' => $endereco,
                    'numero' => $numero,
                    'complemento' => $complemento,
                    'cep' => $cep,
                    'telefone' => $telefone,
                    'cpfcnpj' => $cpfcnpj,
                    'role' => $permissoes,
                    'status' => 'ativo',
                    'token' => $token
                ];
            }
            function createDocumente($login, $senha, $nome, $endereco, $numero, $complemento, $cep, $telefone, $cpfcnpj, $validadeCnh, $permissoes, $binaryImage, $fileName, $token, $apelido, $setor)
            {
                return [
                    'apelido' => $apelido,
                    'user_e' => $login,
                    'senha' => $senha,
                    'nome' => $nome,
                    'endereco' => $endereco,
                    'numero' => $numero,
                    'complemento' => $complemento,
                    'cep' => $cep,
                    'telefone' => $telefone,
                    'cpfcnpj' => $cpfcnpj,
                    'validadeCnh' => $validadeCnh,
                    'role' => $permissoes,
                    'fotoCnh' => $binaryImage,
                    'filename' => $fileName,
                    'status' => 'ativo',
                    'token' => $token,
                    'setor' => $setor
                ];
            }
            $criar = getenv('data_base');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);

            $role = isset($customer->role) ? $customer->role : "nada";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['choice'] == "4") {
                    if ($role == "agente" ||$role == "coordenador" ||$role == "coodernadorSupervisor" ||$role == "coordenador" ||$role == "supervisor" ||$role  == "agente" ||$role  == "financeiro" ||$role == "sac") {
                        $collection = $client->$ambiente->entregador;
                        $id = esc($_POST['id']);
                        $userData = $collection->findOne(['$and' => [['_id' =>  new MongoDB\BSON\ObjectID($id)]]]);
                        if ($userData) {
                            // Retornar os dados do usuário como uma resposta JSON
                            header('Content-Type: application/json');
                            echo json_encode($userData);
                        } else {
                            // Retornar uma resposta vazia ou uma mensagem de erro, conforme necessário
                            http_response_code(404);
                            echo 'Usuário não encontrado';
                        }
                        die();
                    }

                    die();
                } elseif ($_POST['choice'] == "5") {
                    $collection = $client->$ambiente->entregador;
                    $id = esc($_POST['id']);
                    $result = $collection->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectID($id)],
                        ['$set' => ['status' => 'desativado']]
                    );

                    if ($result->getModifiedCount() === 1) {
                        // O documento foi atualizado com sucesso
                        echo "desativado";
                        die();
                    } else {
                        // O documento não foi encontrado ou não foi modificado
                        echo "erro desativado";
                        die();
                    }
                    die();
                } elseif ($_POST['choice'] == "6") {
                    if (isset($_POST['login'])) {


                        // Dados do formulário
                        $login = esc($_POST['login']);
                        $senha = hash('sha3-256', $_POST['senha']);
                        $nome = esc($_POST['nome']);
                        $endereco = esc($_POST['endereco']);
                        $numero = esc($_POST['numero']);
                        $complemento = esc($_POST['complemento']);
                        $cep = esc($_POST['cep']);
                        $telefone = esc($_POST['telefone']);
                        $cpfcnpj = esc($_POST['cpfcnpj']);
                        $permissoes = esc($_POST['permissoes']);

                        // Crie uma instância do cliente MongoDB
                        $client = new Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);

                        // Seleciona o banco de dados
                        $database = $client->selectDatabase('Prod');

                        // Seleciona a coleção
                        $collection = $database->selectCollection('base');

                        // Verifique se o arquivo foi enviado corretamente
                        $token = null;
                        while ($token === null) {
                            $random = bin2hex(random_bytes(16));
                            $timestamp = time();
                            $newId = hash('sha256', $random . $timestamp);

                            // Verifica se o ID já existe no banco de dados
                            $idExists = $collection->findOne(['token' => $newId]);

                            if ($idExists) {
                                // Se o ID já existe, continue gerando novos IDs
                                continue;
                            } else {
                                // Se o ID não existe, defina o novo ID e saia do loop
                                $token = $newId;
                            }
                        }
                        // Insira o documento com os dados
                        $documento = createDocument($login, $senha, $nome, $endereco, $numero, $complemento, $cep, $telefone, $cpfcnpj, $permissoes, $token);

                        // Insere o documento na coleção
                        $result = $collection->insertOne($documento);

                        // Verifique o resultado da inserção e tome as medidas apropriadas
                        if ($result->getInsertedCount() > 0) {
                            // Inserção bem-sucedida
                        } else {
                            // Falha na inserção
                        }
                        echo "okay";
                    die();}


                    print_r($_POST);
                    $login = esc($_POST['login_e']);
                    $senha = hash('sha3-256', $_POST['senha_e']);
                    $nome = esc($_POST['nome_e']);
                    $endereco = esc($_POST['endereco_e']);
                    $numero = esc($_POST['numero_e']);
                    $complemento = esc($_POST['complemento_e']);
                    $cep = esc($_POST['cep_e']);
                    $telefone = esc($_POST['telefone_e']);
                    $cpfcnpj = esc($_POST['cpfcnpj_e']);
                    $validadeCnh = esc($_POST['validadeCnh_e']);
                    if(isset($_POST['apelido_e'])) {
                        $apelido = esc($_POST['apelido_e']);
                    } else {
                        $apelido = "-";
                    }

                    // Crie uma instância do cliente MongoDB
                    $client = new Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);

                    // Seleciona o banco de dados
                    $database = $client->selectDatabase('Prod');

                    // Seleciona a coleção
                    $collection = $database->selectCollection('entregador');

                    // Verifique se o arquivo foi enviado corretamente
                    if (isset($_FILES['fotoCnh_e']) && $_FILES['fotoCnh_e']['error'] === UPLOAD_ERR_OK) {
                        // Obtenha o caminho temporário do arquivo
                        $tempFilePath = $_FILES['fotoCnh_e']['tmp_name'];

                        // Obtenha o nome original do arquivo
                        $originalFileName = $_FILES['fotoCnh_e']['name'];

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
                    } else {
                        $binaryImage = "0";
                        $originalFileName = "0";
                    }
                    $token = null;
                    while ($token === null) {
                        $random = bin2hex(random_bytes(16));
                        $timestamp = time();
                        $newId = hash('sha256', $random . $timestamp);

                        // Verifica se o ID já existe no banco de dados
                        $idExists = $collection->findOne(['token' => $newId]);

                        if ($idExists) {
                            // Se o ID já existe, continue gerando novos IDs
                            continue;
                        } else {
                            // Se o ID não existe, defina o novo ID e saia do loop
                            $token = $newId;
                        }
                    }
                    $cep = intval($cep);
                    $criar = getenv('data_setor');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->setor;

                    $checkes = $collection->findOne(['$or' => [['cep' => $cep]]]);
                    if (isset($checkes->setor)) {
                        $setor = $checkes->setor;

                    } else {
                        $setor = "Expansão";

                    }
                    $criar = getenv('data_base');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->entregador;

                    // Insira o documento com os dados
                    $documento = createDocumente($login, $senha, $nome, $endereco, $numero, $complemento, $cep, $telefone, $cpfcnpj, $validadeCnh, 'entregador', $binaryImage, $fileName, $token, $apelido, $setor);

                    // Insere o documento na coleção
                    $result = $collection->insertOne($documento);

                    // Verifique o resultado da inserção e tome as medidas apropriadas
                    if ($result->getInsertedCount() > 0) {
                        // Inserção bem-sucedida
                        echo "sucesso";
                    } else {
                        // Falha na inserção
                        echo "erro";
                    }
                    die();

               
               
               
               
                } elseif($_POST['choice'] == "10") {
                    $clienteId = esc($_POST['clienteId']);
                    $collection = $client->$ambiente->cliente;
                    $resultado = $collection->findOne(['_id' => new MongoDB\BSON\ObjectID($clienteId)]);

                    // Verifique se o cliente foi encontrado
                    if ($resultado) {
                        // Retorne os detalhes do cliente como uma resposta JSON
                        header('Content-Type: application/json');
                        echo json_encode($resultado);
                    } else {
                        // Se o cliente não for encontrado, retorne uma resposta de erro
                        http_response_code(404);
                        echo json_encode(['mensagem' => 'Cliente não encontrado']);
                    }
                    die();

                } elseif($_POST['choice'] == "11") {
                    $rastreio = esc($_POST['id']);

                    $collection = $client->$ambiente->produto;

                    $resultado = $collection->findOne(['rastreio' => $rastreio]);
                    if ($resultado->status=="Sendo separado na central"){
                        if ($resultado->setor_entrega !== "Expansão"){
                        $setor = $resultado->setor_entrega;
                        $collection = $client->$ambiente->entregador;
                        $entregadores_setor = $collection->find(['setor' => $setor]);

                        }else{
                            $setor = "Expansão";
                            $collection = $client->$ambiente->entregador;
                            $entregadores_setor = $collection->find();
    
                        }
    
    
                        $html = '
                        <div class="container">                    
                        <ul class="list-group">';
                    foreach ($entregadores_setor as $entregador) {
                        $html .= '<li class="list-group-item" onclick="exec(\''. $entregador->token. '\',\'' . esc($entregador->apelido) .'\',\''. $rastreio . '\')">'.esc($entregador->apelido).'</li>';
                    }
                    $html .= '</ul>
                      </div>
                            ';
                    echo $html;

                        


                        die();
                    }
                    $idcli = $resultado -> cliente_id;
                    if($resultado->setor == 'Expansão'){

                        $collection = $client->$ambiente->cliente;
                        $cliente = $collection->findOne(['_id' => new MongoDB\BSON\ObjectID($idcli)]);
                        $setor = $cliente->setor;
    
                        $collection = $client->$ambiente->entregador;
                        $entregadores_setor = $collection->find();
    

                    }else{
                        $collection = $client->$ambiente->cliente;
                        $cliente = $collection->findOne(['_id' => new MongoDB\BSON\ObjectID($idcli)]);
                        $setor = $cliente->setor;
    
                        $collection = $client->$ambiente->entregador;
                        $entregadores_setor = $collection->find(['setor' => $setor]);
    
                    }
                  
                    $html = '
                        <div class="container">                    
                        <ul class="list-group">';
                    foreach ($entregadores_setor as $entregador) {
                        $html .= '<li class="list-group-item" onclick="exec(\''. $entregador->token. '\',\'' . esc($entregador->apelido) .'\',\''. $rastreio . '\')">'.esc($entregador->apelido).'</li>';
                    }
                    $html .= '</ul>
                      </div>
                            ';
                    echo $html;
                    die();
                } elseif($_POST['choice'] == "12") {
                    $entregadorid = esc($_POST['id']);
                    $collection = $client->$ambiente->entregador;
                    $entregador = $collection->findOne(['token' => $entregadorid]);
                    $rastreio = esc($_POST['rastreio']);
                    $collection = $client->$ambiente->produto;
                    $produto = $collection->findOne(['rastreio' => $rastreio]);
                    
                    if($produto->status == "Sendo separado na central"){
                        
                        $result = $collection->updateOne(
                            ['rastreio' => $rastreio],
                            ['$set' => ['entregador_entrega' => $entregador->apelido, 'entregador_id_entrega' => $entregador->token, 'status' => 'A caminho de entrega']]
                        );
                        $rev = $collection->findOne(['rastreio' => $rastreio]);
                        $idrev = $rev->cliente_id;
                        $collection = $client->$ambiente->cliente;
                        $cliented = $collection->findOne(['_id' => new MongoDB\BSON\ObjectID($idrev)]);
                        $emailer = $cliented->email;
                        $email = \Config\Services::email();
                        $email->setTo($emailer);
                        $email->setFrom('cadastro@rtes.com.br');
                        $email->setSubject('A caminho da entrega - Real time express solutions');
                        $email->setMessage("<!DOCTYPE html><html><head><meta charset='utf-8'><meta http-equiv='x-ua-compatible' content='ie=edge'><title>Saindo para entrega</title><meta name='viewport' content='width=device-width, initial-scale=1'><style type='text/css'>/*** Google webfonts. Recommended to include the .woff version for cross-client compatibility.*/@media screen {@font-face {font-family: 'Source Sans Pro';font-style: normal;font-weight: 400;src: local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format('woff');}@font-face {font-family: 'Source Sans Pro';font-style: normal;font-weight: 700;src: local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format('woff');}}/*** Avoid browser level font resizing.* 1. Windows Mobile* 2. iOS / OSX*/body,table,td,a {-ms-text-size-adjust: 100%; /* 1 */-webkit-text-size-adjust: 100%; /* 2 */}/*** Remove extra space added to tables and cells in Outlook.*/table,td {mso-table-rspace: 0pt;mso-table-lspace: 0pt;}/*** Better fluid images in Internet Explorer.*/img {-ms-interpolation-mode: bicubic;}/*** Remove blue links for iOS devices.*/a[x-apple-data-detectors] {font-family: inherit !important;font-size: inherit !important;font-weight: inherit !important;line-height: inherit !important;color: inherit !important;text-decoration: none !important;}/*** Fix centering issues in Android 4.4.*/div[style*='margin: 16px 0;'] {margin: 0 !important;}body {width: 100% !important;height: 100% !important;padding: 0 !important;margin: 0 !important;}/*** Collapse table borders to avoid space between cells.*/table {border-collapse: collapse !important;}a {color: #1a82e2;}img {height: auto;line-height: 100%;text-decoration: none;border: 0;outline: none;}</style></head><body style='background-color: #e9ecef;'><!-- start preheader --><div class='preheader' style='display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;'>Seu pedido foi para entrega </div><!-- end preheader --><!-- start body --><table border='0' cellpadding='0' cellspacing='0' width='100%'><!-- start logo --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><tr><td align='center' valign='top' style='padding: 36px 24px;'><a href='' target='_blank' style='display: inline-block;'><!-- <img src='#' alt='Logo' border='0' width='48' style='display: block; width: 48px; max-width: 48px; min-width: 48px;'> --></a></td></tr></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end logo --><!-- start hero --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><tr><td align='left' bgcolor='#ffffff' style='padding: 36px 24px 0; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;'><h1 style='margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px;'><center>Pedido saiu da central</h1></td></tr></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end hero --><!-- start copy block --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;'><p style='margin: 0;'>Seu pedido está indo em direção ao destino na <a href='https://Deliveryexpresssolutions.com.br'>Real time express solutions</a>.</p> caso tenha qualquer questão, entre em contato conosco pelo chat</td></tr><!-- end copy --><!-- start button --><tr><td align='left' bgcolor='#ffffff'><table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td align='center' bgcolor='#ffffff' style='padding: 12px;'><table border='0' cellpadding='0' cellspacing='0'><tr><td align='center' bgcolor='#1a82e2' style='border-radius: 6px;'><a href='https://Deliveryexpresssolutions.com.br/' target='_blank' style='display: inline-block; padding: 16px 36px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px;'>Acessar</a></td></tr></table></td></tr></table></td></tr><!-- end button --><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;'><p style='margin: 0;'>Não responda esse email, email automatico</p><p style='margin: 0;'><a href='https://Deliveryexpresssolutions.com.br' target='_blank'>https://Deliveryexpresssolutions.com.br</a></p></td></tr><!-- end copy --><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf'><p style='margin: 0;'>atenciosamente,<br> Real time express solutions.</p></td></tr><!-- end copy --></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end copy block --><!-- start footer --><tr><td align='center' bgcolor='#e9ecef' style='padding: 24px;'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'></td></tr><!-- end unsubscribe --></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end footer --></table><!-- end body --></body></html>");
                        if ($email->send()) {        
                            die();
                        } else {
                            echo "errado";
                            die();
                        }
        

                        die();

                    }
    
                    $result = $collection->updateOne(
                        ['rastreio' => $rastreio],
                        ['$set' => ['entregador' => $entregador->apelido, 'entregador_id' => $entregador->token, 'status' => 'A caminho de coleta']]
                    );
                    $rev = $collection->findOne(['rastreio' => $rastreio]);
                    $idrev = $rev->cliente_id;
                    $collection = $client->$ambiente->cliente;
                    $cliented = $collection->findOne(['_id' => new MongoDB\BSON\ObjectID($idrev)]);
                    $emailer = $cliented->email;
                    $email = \Config\Services::email();
                    $email->setTo($emailer);
                    $email->setFrom('cadastro@rtes.com.br');
                    $email->setSubject('Saindo para busca - Real time express solutions');
                    $email->setMessage("<!DOCTYPE html><html><head><meta charset='utf-8'><meta http-equiv='x-ua-compatible' content='ie=edge'><title>Saindo para busca</title><meta name='viewport' content='width=device-width, initial-scale=1'><style type='text/css'>/*** Google webfonts. Recommended to include the .woff version for cross-client compatibility.*/@media screen {@font-face {font-family: 'Source Sans Pro';font-style: normal;font-weight: 400;src: local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format('woff');}@font-face {font-family: 'Source Sans Pro';font-style: normal;font-weight: 700;src: local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format('woff');}}/*** Avoid browser level font resizing.* 1. Windows Mobile* 2. iOS / OSX*/body,table,td,a {-ms-text-size-adjust: 100%; /* 1 */-webkit-text-size-adjust: 100%; /* 2 */}/*** Remove extra space added to tables and cells in Outlook.*/table,td {mso-table-rspace: 0pt;mso-table-lspace: 0pt;}/*** Better fluid images in Internet Explorer.*/img {-ms-interpolation-mode: bicubic;}/*** Remove blue links for iOS devices.*/a[x-apple-data-detectors] {font-family: inherit !important;font-size: inherit !important;font-weight: inherit !important;line-height: inherit !important;color: inherit !important;text-decoration: none !important;}/*** Fix centering issues in Android 4.4.*/div[style*='margin: 16px 0;'] {margin: 0 !important;}body {width: 100% !important;height: 100% !important;padding: 0 !important;margin: 0 !important;}/*** Collapse table borders to avoid space between cells.*/table {border-collapse: collapse !important;}a {color: #1a82e2;}img {height: auto;line-height: 100%;text-decoration: none;border: 0;outline: none;}</style></head><body style='background-color: #e9ecef;'><!-- start preheader --><div class='preheader' style='display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;'>Seu pedido foi para entrega </div><!-- end preheader --><!-- start body --><table border='0' cellpadding='0' cellspacing='0' width='100%'><!-- start logo --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><tr><td align='center' valign='top' style='padding: 36px 24px;'><a href='' target='_blank' style='display: inline-block;'><!-- <img src='#' alt='Logo' border='0' width='48' style='display: block; width: 48px; max-width: 48px; min-width: 48px;'> --></a></td></tr></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end logo --><!-- start hero --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><tr><td align='left' bgcolor='#ffffff' style='padding: 36px 24px 0; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;'><h1 style='margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px;'><center>Pedido saiu da central</h1></td></tr></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end hero --><!-- start copy block --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;'><p style='margin: 0;'>Um entregador está indo a seu local para busca da entrega, verifique sobre em <a href='https://Deliveryexpresssolutions.com.br'>Real time express solutions</a>.</p> caso tenha qualquer questão, entre em contato conosco pelo chat</td></tr><!-- end copy --><!-- start button --><tr><td align='left' bgcolor='#ffffff'><table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td align='center' bgcolor='#ffffff' style='padding: 12px;'><table border='0' cellpadding='0' cellspacing='0'><tr><td align='center' bgcolor='#1a82e2' style='border-radius: 6px;'><a href='https://Deliveryexpresssolutions.com.br/' target='_blank' style='display: inline-block; padding: 16px 36px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px;'>Acessar</a></td></tr></table></td></tr></table></td></tr><!-- end button --><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;'><p style='margin: 0;'>Não responda esse email, email automatico</p><p style='margin: 0;'><a href='https://Deliveryexpresssolutions.com.br' target='_blank'>https://Deliveryexpresssolutions.com.br</a></p></td></tr><!-- end copy --><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf'><p style='margin: 0;'>atenciosamente,<br> Real time express solutions.</p></td></tr><!-- end copy --></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end copy block --><!-- start footer --><tr><td align='center' bgcolor='#e9ecef' style='padding: 24px;'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'></td></tr><!-- end unsubscribe --></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end footer --></table><!-- end body --></body></html>");
                    if ($email->send()) {        
                        die();
                    } else {
                        echo "errado";
                        die();
                    }

                    die();

                }elseif($_POST['choice'] == "13"){
                    $rastreio = esc($_POST['idProduto']);
                    $criar = getenv('data_base');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->produto; // Inicialize $messagesCollection com a coleção 'sac'
                    $produto = $collection->findOne(['rastreio' => $rastreio]);
                    $html = '
                    <li class="list-group-item">
                      <strong>tempo:</strong> '. $produto->time .'
                    </li>
                    <li class="list-group-item">
                      <strong>rastreio:</strong> '.$produto->rastreio .'
                    </li>
                    <li class="list-group-item">
                      <strong>destinatario:</strong> '. esc($produto->destinatario) .'
                    </li>
                    <li class="list-group-item">
                      <strong>endereco:</strong> '. esc($produto->endereco) .'
                    </li>
                    <li class="list-group-item">
                      <strong>numero:</strong> '. esc($produto->numero) .'
                    </li>
                    <li class="list-group-item">
                      <strong>complemento:</strong> '. esc($produto->complemento) .'
                    </li>
                    <li class="list-group-item">
                      <strong>cep:</strong> ' . esc($produto->cep) . '
                    </li>
                    <li class="list-group-item">
                      <strong>bairro:</strong> '. esc($produto->bairro) .'
                    </li>
                    <li class="list-group-item">
                      <strong>telefone:</strong> '. esc($produto->telefone) .'
                    </li>
                    <li class="list-group-item">
                      <strong>observacao:</strong> ' . esc($produto->observacao) . '
                    </li>
                    <li class="list-group-item">
                      <strong>cabe na moto:</strong> '. esc($produto->cabe_moto) .'
                    </li>
                    <li class="list-group-item">
                      <strong>status:</strong> '. esc($produto->status) .'
                    </li>
                    <li class="list-group-item">
                      <strong>andamento:</strong> '. esc($produto->andamento) .'
                    </li>

                    ';
                    echo $html;
                    die();
                }
            }
            if ($role == "coordenador" || $role == "coordenadorSupervisor") {
                $criar = getenv('data_base');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->base;

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($_POST['choice'])) {
                        if ($_POST['choice'] == "2") {
                            $id = esc($_POST['id']);
                            $result = $collection->updateOne(
                                ['_id' => new MongoDB\BSON\ObjectID($id)],
                                ['$set' => ['status' => 'desativado']]
                            );

                            if ($result->getModifiedCount() === 1) {
                                // O documento foi atualizado com sucesso
                                echo "desativado";
                                die();
                            } else {
                                // O documento não foi encontrado ou não foi modificado
                                echo "erro desativado";
                                die();
                            }
                            die();
                        } elseif ($_POST['choice'] == "0") {
                            $userId = esc($_POST['id']);
                            $userData = $collection->findOne(['_id' => new MongoDB\BSON\ObjectID($userId)]);

                            // Verificar se os dados do usuário foram encontrados
                            if ($userData) {
                                // Retornar os dados do usuário como uma resposta JSON
                                header('Content-Type: application/json');
                                echo json_encode($userData);
                            } else {
                                // Retornar uma resposta vazia ou uma mensagem de erro, conforme necessário
                                http_response_code(404);
                                echo 'Usuário não encontrado';
                            }
                            die();
                        }
                    }
                }

                $base_u = $collection->find();

            } else {
                $base_u = "nada";
            };
            $criar = getenv('data_base');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->base;
            $entregador = $collection->findOne(['$and' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo']]]);
            $regra = $entregador -> role;
            if ($regra == "agente" ||$regra == "coordenador" ||$regra == "coordenadorSupervisor" ||$regra == "coordenador" ||$regra == "supervisor" ||$regra == "agente" ||$regra == "financeiro" ||$regra == "sac") {
                $tocoringadoja = $client->$ambiente->entregador;
                $entregaores = $tocoringadoja -> find();

            }else{
                $entregaores = "nada";
            }
            $collection = $client->$ambiente->produto;
            $pedidos = $collection->find(['andamento' => 'on']);
            if ($regra == "coordenador" || $regra == "sac" || $regra == "coordenadorSupervisor") {

                $criar = getenv('data_sac');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->listsac;
                $options = [
                    'sort' => ['dateo' => -1]
                ];

                $messages = $collection->find([], $options);

            } else {
                $messages = "nada";
            }

            $criar = getenv('data_base');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->produto;
            $confirm = $collection->find(['$and' => [['status' => 'Aguardando confirmação', 'setor' => ['$ne' => 'Expansão']]]]);
            $confirm1 = $collection->find(['$and' => [['status' => 'Aguardando confirmação', 'setor' => 'Expansão']]]);
            $confirm2 = $collection->find(['$and' => [['status' => 'A caminho de coleta' ]]]);
            $confirm3 = $collection->find(['$or' => [['ausente' => '1' ],['ausente' => '2' ],['ausente' => '3' ]]]);
            $collection = $client->$ambiente->cliente;
            $clientes = $collection->find();


            return view('base/painel', ['role' => $role, 'base_u' => $base_u, 'entregaores' => $entregaores, 'produto' => $pedidos, 'chat' => $messages, 'confirm' => $confirm, 'confirm1' => $confirm1, 'confirm2' => $confirm2, 'confirm3' => $confirm, 'clientes' => $clientes]);
            die();




        } else {
            return view('errors/html/error_404');
            die();
        }
    }

    public function cnh($id, $choice)
    {
        $user = session()->get('user_b');
        $pass = session()->get('senha');
        $criar = getenv('data_checkeb');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->base;
        $customer = $collection->findOne(['$and' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo']]]);
        if ($customer) {
            $role = isset($customer->role) ? $customer->role : "nada";
            if ($choice == "1") {
                if ($role == "coordenador" || $role == "coordenadorSupervisor") {
                    $user = session()->get('user_b');
                    $pass = session()->get('senha');
                    $criar = getenv('data_base');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->base;
                    $userData = $collection->findOne(['token' => $id]);

                    // Verifique se os dados do usuário foram encontrados e se a foto existe
                    if ($userData && isset($userData['fotoCnh']) && $userData['fotoCnh'] !== "0") {
                        $fotoCnh = $userData['fotoCnh'];

                        // Defina o cabeçalho Content-Type para apropriado para o tipo de imagem
                        header('Content-Type: image/jpeg'); // Substitua pelo tipo de imagem correto

                        // Exiba a foto
                        echo $fotoCnh;
                    } else {
                        header('Content-Type: image/jpeg');
                        readfile('https://Deliveryexpresssolutions.com.br/assets/img/foto404.jpg'); // Substitua pelo caminho para a imagem padrão desejada
                    }

                } else {
                    header('Content-Type: image/jpeg');
                    readfile('https://Deliveryexpresssolutions.com.br/assets/img/foto404.jpg'); // Substitua pelo caminho para a imagem padrão desejada
                }
            } elseif ($choice == "2") {
                if ($role == "coordenador" || $role == "coordenadorSupervisor") {
                    $user = session()->get('user_b');
                    $pass = session()->get('senha');
                    $criar = getenv('data_base');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->entregador;
                    $userData = $collection->findOne(['token' => $id]);

                    // Verifique se os dados do usuário foram encontrados e se a foto existe
                    if ($userData && isset($userData['fotoCnh']) && $userData['fotoCnh'] !== "0") {
                        $fotoCnh = $userData['fotoCnh'];

                        // Defina o cabeçalho Content-Type para apropriado para o tipo de imagem
                        header('Content-Type: image/jpeg'); // Substitua pelo tipo de imagem correto

                        // Exiba a foto
                        echo $fotoCnh;
                    } else {
                        header('Content-Type: image/jpeg');
                        readfile('https://Deliveryexpresssolutions.com.br/assets/img/foto404.jpg'); // Substitua pelo caminho para a imagem padrão desejada
                    }

                } else {
                    header('Content-Type: image/jpeg');
                    readfile('https://Deliveryexpresssolutions.com.br/assets/img/foto404.jpg'); // Substitua pelo caminho para a imagem padrão desejada
                }
            }
        } else {
            return view('errors/html/error_404');
        }

    }

    public function chat()
    {
        $user = session()->get('user_b');
        $pass = session()->get('senha');
        $criar = getenv('data_checkeb');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->base;
        $customer = $collection->findOne(['$and' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo']]]);
        if ($customer) {
            $regra = $customer->role;
            if ($regra == "coordenador" || $regra == "sac" || $regra == "coordenadorSupervisor") {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if ($_POST['choice'] == 'receberChat') {
                        // aqui recebe a lista
                        $criar = getenv('data_sac');
                        $host = getenv('host_db');
                        $ambiente = getenv('ambiente');
                        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                        $options = [
                            'sort' => ['dateo' => -1]
                          ];

                        $collection = $client->$ambiente->listsac;
                        $messagesc = $collection->find([], $options);
                        $htmlMensagens = '';
                        foreach ($messagesc as $chat) {

                            // Construa o HTML da mensagem com as propriedades desejadas (sender, text, etc.)
                            $htmlMensagens .= '
                            <div class="list-group mt-4">
                            <a href="' .base_url('central/painel/sac/'). $chat->token .'" class="list-group-item" data-id="'. esc($chat->cliente) .'" target="_blank">
                              <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">'. esc($chat->nome) .'</h5>
                                <small>'. $chat->date .'</small>
                              </div>
                              <p class="mb-1">'. esc($chat->last) .'</p>
                            </a>
                          </div>
                          
              ';
                        }

                        // Retorne o HTML das mensagens como resposta
                        echo $htmlMensagens;
                        die();

                    } elseif($_POST['choice'] == 'receber') {
                        // aqui recebe os dados do chat com base no data-id enviado
                        echo"1";
                    }
                }
            }
        }
    }

    public function mchat($token)
    {
        $user = session()->get('user_b');
        $pass = session()->get('senha');
        $criar = getenv('data_checkeb');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->base;
        $customer = $collection->findOne(['$and' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo']]]);
        if ($customer) {
            $regra = $customer->role;
            if ($regra == "coordenador" || $regra == "sac" || $regra == "coordenadorSupervisor") {

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if($_POST['choice'] == "receber") {
                        $criar = getenv('data_sac');
                        $host = getenv('host_db');
                        $ambiente = getenv('ambiente');
                        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                        $options = [
                            'sort' => ['dateo' => -1]
                          ];

                        $collection = $client->$ambiente->sac;
                        $messagesc = $collection->find(['token' => $token, 'readb' => false], $options);
                        $htmlMensagens = '';
                        foreach ($messagesc as $chat) {
                            if ($chat->receiver == "sac") {
                                $position = "left";
                            } else {
                                $position = "right";
                            }
                            $htmlMensagens .= '
                <div class="message-container">
                <div class="message message-'. $position .'">
                  <div class="name">' . esc($chat->nome) . '</div>
                  <div class="balloon-'. $position .'">' . esc($chat->message) . '</div>
                </div>
                ';
                            $criar = getenv('data_sac');
                            $host = getenv('host_db');
                            $ambiente = getenv('ambiente');
                            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                            $collection = $client->$ambiente->sac; // Inicialize $messagesCollection com a coleção 'sac'

                            // Marque a mensagem como lida atualizando a propriedade "read" para true
                            $collection->updateOne(
                                ['_id' => $chat->_id],
                                ['$set' => ['readb' => true]]
                            );
                        }

                        echo $htmlMensagens;
                        die();
                    } elseif($_POST['choice']=="enviar") {
                        function enviarMensagem($sender, $receiver, $message, $checks)
                        {

                            $data = [
                              'sender' => $sender,
                              'receiver' => $receiver,
                              'message' => $message,
                              'dateo' => new MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d H:i:s')) * 1000),
                              'date' => date('d/m/Y H:i'),
                              'read' => false,
                              'readb' => true,
                              'cliente' => 'sac',
                              'token' => hash('sha3-256', $receiver),
                              'nome' => $checks
                            ];
                            $criar = getenv('data_sac');
                            $host = getenv('host_db');
                            $ambiente = getenv('ambiente');
                            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                            $collection = $client->$ambiente->sac;

                            $collection->insertOne($data);
                        }
                        $mensagem = esc($_POST['mensagem']);

                        // Processar a mensagem, se necessário
                        $htmlMensagens = '';
                        // Construir a nova mensagem formatada como HTML
                        $htmlMensagens .= '
                <div class="message-container">
                <div class="message message-right">
                  <div class="name"> sac </div>
                  <div class="balloon-right">' . esc($mensagem) . '</div>
                </div>
                ';
                        $criar = getenv('data_sac');
                        $host = getenv('host_db');
                        $ambiente = getenv('ambiente');
                        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                        $collection = $client->$ambiente->listsac;
                        $clients = $collection->findOne(['token' => $token]);
                        $doc = $clients->cliente;
                        $checks = $clients->nome;
                        $envio = enviarMensagem('sac', $doc, $mensagem, $checks);
                        $criar = getenv('data_sac');
                        $host = getenv('host_db');
                        $ambiente = getenv('ambiente');
                        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                        $collection = $client->$ambiente->listsac; // Inicialize $messagesCollection com a coleção 'sac'

                        $collection->updateOne(
                            [
                                'cliente' => $doc,
                            ],
                            ['$set' => ['last' =>  $mensagem, 'dateo' => new MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d H:i:s')) * 1000), 'date' =>  date('d/m/Y H:i'), 'nome' => 'sac', 'view' => false]],
                            ['upsert' => true]
                        );
                        echo $htmlMensagens;
                        die();

                        die();
                    }
                }
                if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                    $criar = getenv('data_sac');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $options = [
                        'sort' => ['dateo' => 1]
                      ];

                    $collection = $client->$ambiente->sac;
                    $messagesc = $collection->find(['token' => $token], $options);
                    return view('base/chat', ['chat' => $messagesc]);
                    die();
                }
            }
        }

    }

    public function setor()
    {
        $user = session()->get('user_b');
        $pass = session()->get('senha');
        $criar = getenv('data_checkeb');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->base;
        $customer = $collection->findOne(['$or' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo', 'role' => 'coordenador'],['user_b' => $user, 'senha' => $pass, 'status' => 'ativo', 'role' => 'coordenadorSupervisor']]]);
        if ($customer) {

            if(isset($_POST['choice'])) {


                $criar = getenv('data_cliente');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->cliente;



                // Percorrer coleção "cliente"
                $cursor_cliente = $collection->find();
                foreach ($cursor_cliente as $doc) {
                    $cep = intval($doc->cep);
                    $criar = getenv('data_setor');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->setor;
                    $setor = $collection->findOne(['$or' => [['cep' => $cep]]]);
                    if (isset($setor->setor)) {
                        $desks = $setor->setor;

                    } else {
                        $desks = "Expansão";

                    }
                    $criar = getenv('data_cliente');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->cliente;

                    $collection->updateOne(
                        [
                            '_id' => new MongoDB\BSON\ObjectID($doc->_id),
                        ],
                        ['$set' => ['setor' => $desks]],
                    );
                }

                $criar = getenv('data_base');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->entregador;

                // Percorrer coleção "entregador"
                $cursor_entregador = $collection->find();
                foreach ($cursor_entregador as $doc) {
                    $cep = intval($doc->cep);
                    $criar = getenv('data_setor');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->setor;
                    $setor = $collection->findOne(['$or' => [['cep' => $cep]]]);
                    if (isset($setor->setor)) {
                        $desks = $setor->setor;

                    } else {
                        $desks = "Expansão";

                    }
                    $criar = getenv('data_base');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->entregador;
                    $collection->updateOne(
                        [
                            '_id' => new MongoDB\BSON\ObjectID($doc->_id),
                        ],
                        ['$set' => ['setor' => $desks]],
                    );
                }

                echo "atualizado";
                die();
            }
            // Obtém o nome do arquivo
            $jsonString = file_get_contents('php://input');
            $jsonData = json_decode($jsonString, true);

            // Conecta-se ao MongoDB e realiza a inserção dos dados
            $criar = getenv('data_setor');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->setor;

            // Limpa a coleção existente (opcional)
            $collection->deleteMany([]);

            // Insere os dados no MongoDB
            $collection->insertMany($jsonData);

            echo 'Arquivo XLSX convertido com sucesso para uma coleção MongoDB.';



        }
        die();
    }
    
    public function list()
    {
        $user = session()->get('user_b');
        $pass = session()->get('senha');
        $criar = getenv('data_checkeb');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->base;
        $customer = $collection->findOne(['$and' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo']]]);
        if ($customer) {
            $criar = getenv('data_base');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->produto;
            $regra = $customer->role;
            $confirm = $collection->find(['$or' => [['status' => 'Aguardando confirmação', 'setor' => ['$ne' => 'Expansão']]]]);
            $confirm1 = $collection->find(['$and' => [['status' => 'Aguardando confirmação', 'setor' => 'Expansão']]]);
            $confirm2 = $collection->find(['$or' => [['status' => 'A caminho de coleta' ]]]);
            $confirm3 = $collection->find([
                '$or' => [
                    ['ocorrencia_entrega1' => ['$exists' => true]],
                    ['ocorrencia_entrega2' => ['$exists' => true]],
                    ['ocorrencia_entrega3' => ['$exists' => true]]
                ], 'status' => 'A caminho de entrega'
            ]);
            $confirm4 = $collection->find(['$or' => [['status' => 'Coletado, a caminho da central']]]);
            $confirm5 = $collection->find(['$or' => [['status' => 'Sendo separado na central' ]]]);
            $confirm6 = $collection->find([
                '$and' => [
                    ['status' => 'A caminho de entrega'],
                    [
                        '$or' => [
                            ['ocorrencia_entrega1' => ['$exists' => false]],
                            ['ocorrencia_entrega2' => ['$exists' => false]],
                            ['ocorrencia_entrega3' => ['$exists' => false]]
                        ]
                    ]
                ]
            ]);

            $c0 = 0;
            $c1 = 0;
            $c2 = 0;
            $c3 = 0;
            $c4 = 0;
            $c5 = 0;
            $c6 = 0;
            $html = '';

            if (!empty($confirm)) {
              foreach ($confirm as $produto) {
                if ($c0 == 0){
                    $html .= '<center><h1>Objetos a coletar</h1></center>';
                    $c0++;  }
                $html .= '<div class="container">
                    <div class="row">
                      <div class="col">
                        <div class="card">
                          <div class="card-header">
                            <h5 class="card-title">' . esc($produto->busca) . '</h5>
                          </div>
                          <div class="card-body" style="height: 8vh;">
                            <p>Sem entregador atribuído - Moto: ' . esc($produto->cabe_moto) . ' - ' . $produto->status . ' - Setor: '. $produto->setor .'</p>
                          </div>
                          <div class="card-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-success flex-grow-1 mb-2"
                                    data-id-produto="' . $produto->rastreio . '"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalItem1"
                                    data-bs-url="' . base_url('central/painel') . '"
                                    data-bs-id="' . $produto->rastreio . '">Atribuir Entregador</button>
                            <button type="button" class="btn btn-primary btn-detalhes-modal mb-2 ml-2"
                                    data-id-produto="' . $produto->rastreio . '"
                                    data-bs-toggle="modal"
                                    data-bs-url="' . base_url('central/painel') . '"
                                    data-bs-target="#modalEntregador" onclick="overlord(\'' . $produto->rastreio . '\')">Detalhes</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>';
              }
            }
            
            if (!empty($confirm1)) {
              foreach ($confirm1 as $produto) {
                if ($c1 == 0){
                    $html .= '<h1><center>Objetos a coletar</center></h1>';
                    $c1++;  }
                $html .= '<div class="container">
                <div class="row">
                  <div class="col">
                    <div class="card">
                      <div class="card-header">
                        <h5 class="card-title">' . esc($produto->busca) . '</h5>
                      </div>
                      <div class="card-body" style="height: 8vh;">
                        <p>Sem entregador atribuído - Moto: ' . esc($produto->cabe_moto) . ' - ' . $produto->status . '</p>
                      </div>
                      <div class="card-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-success flex-grow-1 mb-2"
                                data-id-produto="' . $produto->rastreio . '"
                                data-bs-toggle="modal"
                                data-bs-target="#modalItem0"
                                data-bs-url="' . base_url('central/painel') . '"
                                data-bs-id="' . $produto->rastreio . '">Atribuir Entregador</button>
                        <button type="button" class="btn btn-primary btn-detalhes-modal mb-2 ml-2"
                                data-id-produto="' . $produto->rastreio . '"
                                data-bs-toggle="modal"
                                data-bs-url="' . base_url('central/painel') . '"
                                data-bs-target="#modalEntregador" onclick="overlord(\'' . $produto->rastreio . '\')">Detalhes</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>';
              }
            }
            
            if (!empty($confirm2)) {
              foreach ($confirm2 as $produto) {
                if ($c2 == 0){
                  $html .= '<h1><center>A caminho de coleta</center></h1>';
                    $c2++;  }
                $html .= '<div class="container">
                <div class="row">
                  <div class="col">
                    <div class="card">
                      <div class="card-header">
                        <h5 class="card-title">' . esc($produto->busca) . '</h5>
                      </div>
                      <div class="card-body" style="height: 8vh;">
                        <p>Rastreio: ' . $produto->rastreio . ' -  Setor: ' . $produto->setor . ' - ' . esc($produto->endereco) . ' - Entregador: ' .esc($produto->entregador) . '</p>
                      </div>
                      <div class="card-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-success flex-grow-1 mb-2"
                                data-id-produto="' . $produto->rastreio . '"
                                data-bs-toggle="modal"
                                data-bs-target="#modalItem0"
                                data-bs-url="' . base_url('central/painel') . '"
                                data-bs-id="' . $produto->rastreio . '">Mudar Entregador</button>
                        <button type="button" class="btn btn-primary btn-detalhes-modal mb-2 ml-2"
                                data-id-produto="' . $produto->rastreio . '"
                                data-bs-toggle="modal"
                                data-bs-url="' . base_url('central/painel') . '"
                                data-bs-target="#modalEntregador" onclick="overlord(\'' . $produto->rastreio . '\')">Detalhes</button>
                        <button type="button" class="btn btn-primary mb-2 ml-2"
                              onclick=\'confirmscr("' . $produto->rastreio . '")\' >Confirmar entrega</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>';

              }
            }
            
            if (!empty($confirm3)) {
              foreach ($confirm3 as $produto) {
                if ($c3 == 0){
                  $html .= '<h1><center>Entregas com ocorrências</center></h1>';
                    $c3++;  }
                $html .= '<div class="container">
                <div class="row">
                  <div class="col">
                    <div class="card">
                      <div class="card-header">
                        <h5 class="card-title">' . esc($produto->busca) . '</h5>
                      </div>
                      <div class="card-body" style="height: auto;">
                        <p><b>' . esc($produto->entregador_entrega) . '</b> - <b>Ocorrencias: </b>';
                        if (isset($produto->ocorrencia_entrega1)) {
                            $html .= esc($produto->ocorrencia_entrega1);
                        }
                        
                        if (isset($produto->ocorrencia_entrega2)) {
                            $html .= ', ' . esc($produto->ocorrencia_entrega2);
                        }
                        
                        if (isset($produto->ocorrencia_entrega3)) {
                            $html .= ', ' . esc($produto->ocorrencia_entrega3);
                        }
                $html .= '   </p> 
                        <strong>' . $produto->rastreio . '</strong>                    
                      </div>
                      <div class="card-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-success flex-grow-1 mb-2"
                                data-id-produto="' . $produto->rastreio . '"
                                data-bs-toggle="modal"
                                data-bs-target="#modalItem0"
                                data-bs-url="' . base_url('central/painel') . '"
                                data-bs-id="' . $produto->rastreio . '">Mudar Entregador</button>
                        <button type="button" class="btn btn-primary btn-detalhes-modal mb-2 ml-2"
                                data-id-produto="' . $produto->rastreio . '"
                                data-bs-toggle="modal"
                                data-bs-url="' . base_url('central/painel') . '"
                                data-bs-target="#modalEntregador" onclick="overlord(\'' . $produto->rastreio . '\')">Detalhes</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>';
  
              }
            }

            if (!empty($confirm4)) {
                foreach ($confirm4 as $produto) {
                  if ($c4 == 0){
                    $html .= '<h1><center>A caminho da Central</center></h1>';
                      $c4++;  }
                  $html .= '<div class="container">
                  <div class="row">
                    <div class="col">
                      <div class="card">
                      <div class="card-header">
                      <h5 class="card-title">' . esc($produto->busca) . '</h5>
                    </div>
                    <div class="card-body" style="height: 8vh;">
                      <p>Rastreio: ' . $produto->rastreio . ' -  Setor: ' . $produto->setor_entrega . ' - ' . esc($produto->endereco) . ' - Entregador: ' .esc($produto->entregador) . '</p>
                    </div>
                        <div class="card-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-primary btn-confirmar-entrega flex-grow-1 mb-2"
                                data-bs-toggle="modal"
                                data-bs-target="#modalConfirmarEntrega"
                                data-id="' . $produto->rastreio . '">Confirmar entrega</button>
                          <button type="button" class="btn btn-primary btn-detalhes-modal mb-2 ml-2"
                                  data-id-produto="' . $produto->rastreio . '"
                                  data-bs-toggle="modal"
                                  data-bs-url="' . base_url('central/painel') . '"
                                  data-bs-target="#modalEntregador" onclick="overlord(\'' . $produto->rastreio . '\')">Detalhes</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>';
    
                }
              }

              if (!empty($confirm5)) {
                foreach ($confirm5 as $produto) {
                  if ($c5 == 0){
                    $html .= '<h1><center>Distribuição</center></h1>';
                      $c5++;  }
                  $html .= '<div class="container">
                  <div class="row">
                    <div class="col">
                      <div class="card">
                      <div class="card-header">
                      <h5 class="card-title">' . esc($produto->endereco) . '</h5>
                    </div>
                    <div class="card-body" style="height: 8vh;">
                      <p>Rastreio: ' . $produto->rastreio . ' -  Setor: ' . $produto->setor_entrega . ' - ' . esc($produto->endereco) . '</p>
                    </div>
                        <div class="card-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-success flex-grow-1 mb-2"
                        data-id-produto="' . $produto->rastreio . '"
                        data-bs-toggle="modal"
                        data-bs-target="#modalItem1"
                        data-bs-url="' . base_url('central/painel') . '"
                        data-bs-id="' . $produto->rastreio . '">Atribuir Entregador</button>
                          <button type="button" class="btn btn-primary btn-detalhes-modal mb-2 ml-2"
                                  data-id-produto="' . $produto->rastreio . '"
                                  data-bs-toggle="modal"
                                  data-bs-url="' . base_url('central/painel') . '"
                                  data-bs-target="#modalEntregador" onclick="overlord(\'' . $produto->rastreio . '\')">Detalhes</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>';
    
                }
              }
              if (!empty($confirm6)) {
                foreach ($confirm6 as $produto) {
                  if ($c6 == 0){
                    $html .= '<h1><center>A caminho de entrega</center></h1>';
                      $c6++;  }
                  $html .= '<div class="container">
                  <div class="row">
                    <div class="col">
                      <div class="card">
                      <div class="card-header">
                      <h5 class="card-title">' . esc($produto->endereco) . '</h5>
                    </div>
                    <div class="card-body" style="height: 8vh;">
                      <p>Rastreio: ' . $produto->rastreio . ' -  Setor: ' . $produto->setor_entrega . ' - ' . esc($produto->endereco) . ' - 
                      Entregador: ' . esc($produto->entregador_entrega) . '</p>
                    </div>
                        <div class="card-footer d-flex justify-content-between">
                          <button type="button" class="btn btn-primary btn-detalhes-modal  flex-grow-1 mb-2"
                                  data-id-produto="' . $produto->rastreio . '"
                                  data-bs-toggle="modal"
                                  data-bs-url="' . base_url('central/painel') . '"
                                  data-bs-target="#modalEntregador" onclick="overlord(\'' . $produto->rastreio . '\')">Detalhes</button>
                                  <button type="button" class="btn btn-success btn-confirmar-entrega mb-2 ml-2"
                                onclick= confirmscri("' . $produto->rastreio . '")>Confirmar entrega</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>';
    
                }
              }
            if ($html == ""){
                $html = "   <hr><h3><center>Sem Entregas</center></h3><hr>";
            }
            echo $html;
                
        }
    

    }
    public function confirm()
    {
        $user = session()->get('user_b');
        $pass = session()->get('senha');
        $criar = getenv('data_checkeb');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->base;
        $customer = $collection->findOne(['$and' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo']]]);
        if ($customer) {
            $criar = getenv('data_base');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->produto;
            if(isset($_POST['choice'])){
                if ($_POST['choice'] == 'confirm'){
                    $id = $_POST['id'];
                    $result = $collection->updateOne(
                        ['rastreio' => $id],
                        ['$set' => ['status' => 'Coletado, a caminho da central']]
                    );
                    echo 'okay';
                }elseif($_POST['choice']=='confirmen'){
                    $id = $_POST['id'];
                    $result = $collection->updateOne(
                        ['rastreio' => $id, 'status'=> 'A caminho de entrega'],
                        ['$set' => ['status' => 'entregue', 'andamento' => 'off',  'datea1' => date('d/m/Y')]]
                    );
                    echo 'okay';
                }
                die();

            }
            
            $codigo = esc($_POST['codigo']);
            $T = esc($_POST['T']);
            $peso = esc($_POST['peso']);

            
            if (empty($codigo)) {
                echo "Rastreio incorreto: código de rastreio não fornecido";
                die();
            }
            
            $checagem = $collection->findOne(['rastreio' => $codigo]);
            
            if (!$checagem) {
                echo "Rastreio incorreto: código de rastreio inválido";
                die();
            }
            
            if (isset($checagem->status) && $checagem->status == "A caminho de entrega") {
                $result = $collection->updateOne(
                    ['rastreio' => $codigo],
                    ['$set' => ['status' => 'entregue', 'datea1' => date('d/m/Y'), 'andamento' => 'off']]
                );
                echo "Entregue";
                die();
            }
            
            $result = $collection->updateOne(
                ['rastreio' => $codigo, 'status' => 'Coletado, a caminho da central'],
                ['$set' => ['status' => 'Sendo separado na central', 'T' => $T, 'peso' => $peso]]
            );
            
            if ($result->getModifiedCount() > 0) {
                echo "Status atualizado com sucesso";
            } else {
                echo "Erro ao atualizar o status";
            }
            
            die();
            
        
        }

   
    }
    public function doc()
    {
            $user = session()->get('user_b');
            $pass = session()->get('senha');
            $criar = getenv('data_checkeb');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->base;
            $customer = $collection->findOne(['$and' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo']]]);
            if ($customer) {
                $criar = getenv('data_base');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                if($_POST['choice'] == "clientes"){
                $collection = $client->$ambiente->cliente;
                $clients = $collection->find();
                $html = "";
                foreach ($clients as $c){
                    if(isset($c->cpf)) {
                        $doc = $c->cpf;
                    } else {
                        $doc = $c->cnpj;
                    }

                    if(isset($c->nome)) {
                        $reconhecimento = $c->nome;
                    } else {
                        $reconhecimento = $c->empresa;
                    }


                    $html .= '<li class="list-group-item" onclick="openPeriodoModal(\''. $doc .'\')">' . $doc .' | ' . $reconhecimento . ' </li>';

                }
                echo $html;
                die();
        }elseif($_POST['choice']=="entregador"){
                $collection = $client->$ambiente->entregador;
                $entregadores = $collection->find();
                $html = "";
                foreach ($entregadores as $e){
                    $html .= '
          <li class="list-group-item" onclick="openPeriodoModal(\''. $e->token . '\')">' . $e->cpfcnpj . ' | ' . $e->apelido . '</li>
                    
                    ';
                }
                echo $html;
                die();
                }
            }
    }
    public function pdf($id,$dia,$mes,$ano,$a,$m,$d,$choice)
    {
        if($choice == "1")
        {
            
                $user = session()->get('user_b');
                $pass = session()->get('senha');
                $criar = getenv('data_checkeb');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->base;
                $customer = $collection->findOne(['$and' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo']]]);
                if ($customer) {
                    $id = str_replace("__", "/", $id);
                    $start = $ano.'-'.$mes."-".$dia;
                    $end = $a.'-'.$m."-".$d;
                    $criar = getenv('data_base');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->produto;
                    
                    $filter = ['$or' => [[
                        'time' => [
                            '$gte' => $start,
                            '$lte' => $end
                        ],
                        'entregador_id' => $id],
                        [
                            'time' => [
                                '$gte' => $start,
                                '$lte' => $end
                            ],
                            'entregador_id_entrega' => $id]]];

                    $result = $collection->find($filter);

                    // Criar um novo objeto mPDF
                $mpdf = new \Mpdf\Mpdf();

                // Extrair os dados do objeto $result
                $html = "";
                $choice = 0;
                foreach($result as $data){
                    if (isset($data->entregador_entrega)) {
                        $html .= '
                            <h2>Entrega Base - Destinatario</h2>
                            <hr>
                            <p><strong>Rastreio:</strong> ' . (isset($data['rastreio']) ? $data['rastreio'] : '') . '</p>
                            <p><strong>Tempo de solicitação:</strong> ' . (isset($data['time']) ? $data['time'] : '') . '</p>
                            <p><strong>Destinatário:</strong> ' . (isset($data['destinatario']) ? $data['destinatario'] : '') . '</p>
                            <p><strong>Endereço:</strong> ' . (isset($data['endereco']) ? $data['endereco'] : '') . ' , ' . (isset($data['numero']) ? $data['numero'] : '') . '</p>
                            <p><strong>Complemento:</strong> ' . (isset($data['complemento']) ? $data['complemento'] : '') . '</p>
                            <p><strong>CEP:</strong> ' . (isset($data['cep']) ? $data['cep'] : '') . '</p>
                            <p><strong>Bairro:</strong> ' . (isset($data['bairro']) ? $data['bairro'] : '') . '</p>
                            <p><strong>Telefone:</strong> ' . (isset($data['telefone']) ? $data['telefone'] : '') . '</p>
                            <p><strong>Observação:</strong> ' . (isset($data['observacao']) ? $data['observacao'] : '') . '</p>
                            <p><strong>Cabe Moto:</strong> ' . (isset($data['cabe_moto']) ? $data['cabe_moto'] : '') . '</p>
                            <p><strong>Andamento:</strong> ' . (isset($data['andamento']) ? $data['andamento'] : '') . '</p>
                            <p><strong>Status:</strong> ' . (isset($data['status']) ? $data['status'] : '') . '</p>
                            <p><strong>Setor:</strong> ' . (isset($data['setor']) ? $data['setor'] : '') . '</p>
                            <p><strong>Endereço Cliente:</strong> ' . (isset($data['busca']) ? $data['busca'] : '') . '</p>
                            <p><strong>Entregador:</strong> ' . (isset($data['entregador']) ? $data['entregador'] : '') . '</p>
                            <p><strong>Documento Recebedor:</strong> ' . (isset($data['documento_recebedor']) ? $data['documento_recebedor'] : '') . '</p>
                            <hr>
                        ';
                    } else {
                        $html .= '
                            <h2>Entrega Cliente - Base</h2>
                            <hr>
                            <p><strong>Tempo de solicitação:</strong> ' . (isset($data['time']) ? $data['time'] : '') . '</p>
                            <p><strong>Rastreio:</strong> ' . (isset($data['rastreio']) ? $data['rastreio'] : '') . '</p>
                            <p><strong>Destinatário:</strong> ' . (isset($data['destinatario']) ? $data['destinatario'] : '') . '</p>
                            <p><strong>Endereço:</strong> ' . (isset($data['endereco']) ? $data['endereco'] : '') . ' , ' . (isset($data['numero']) ? $data['numero'] : '') . '</p>
                            <p><strong>Complemento:</strong> ' . (isset($data['complemento']) ? $data['complemento'] : '') . '</p>
                            <p><strong>CEP:</strong> ' . (isset($data['cep']) ? $data['cep'] : '') . '</p>
                            <p><strong>Bairro:</strong> ' . (isset($data['bairro']) ? $data['bairro'] : '') . '</p>
                            <p><strong>Telefone:</strong> ' . (isset($data['telefone']) ? $data['telefone'] : '') . '</p>
                            <p><strong>Observação:</strong> ' . (isset($data['observacao']) ? $data['observacao'] : '') . '</p>
                            <p><strong>Cabe Moto:</strong> ' . (isset($data['cabe_moto']) ? $data['cabe_moto'] : '') . '</p>
                            <p><strong>Status:</strong> ' . (isset($data['status']) ? $data['status'] : '') . '</p>
                            <p><strong>Setor:</strong> ' . (isset($data['setor']) ? $data['setor'] : '') . '</p>
                            <p><strong>Endereço cliente:</strong> ' . (isset($data['busca']) ? $data['busca'] : '') . '</p>
                            <p><strong>Entregador:</strong> ' . (isset($data['entregador_entrega']) ? $data['entregador_entrega'] : '') . '</p>
                            <hr>
                        ';
                    }
                $choice = $choice + 1;
                $mpdf->WriteHTML($html);
                }
                // Adicionar o conteúdo HTML ao mPDF
                $html = "Quantidades de entregas: " . $choice . "<hr>";
                $mpdf->WriteHTML($html);

                // Gerar o PDF
                $mpdf->Output('formulario.pdf', 'D');
                    

                }        
            
        }elseif($choice == "2")
        {
            
                $user = session()->get('user_b');
                $pass = session()->get('senha');
                $criar = getenv('data_checkeb');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->base;
                $customer = $collection->findOne(['$and' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo']]]);
                if ($customer) {
                    $id = str_replace("__", "/", $id);
                    $start = $ano.'-'.$mes."-".$dia;
                    $end = $a.'-'.$m."-".$d;
                    $criar = getenv('data_base');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->cliente;
                    
                    $cli = $collection->findOne(['$or' => [['cpf' => $id ], ['cnpj' => $id]]]);
                    $rz = $cli->_id;
                    $filter = ['$or' => [[
                        'time' => [
                            '$gte' => $start,
                            '$lte' => $end
                        ],
                        'cliente_id' =>new MongoDB\BSON\ObjectID($rz)]]];
                    $collection = $client->$ambiente->produto;
                    $result = $collection->find($filter);

                    // Criar um novo objeto mPDF
                $mpdf = new \Mpdf\Mpdf();
                                    
                // Extrair os dados do objeto $result
                $html = "";
                $choice = 0;
                foreach($result as $data){
                    $html = '
                    <h2>Entrega</h2>
                    <hr>
                    <p><strong>Time:</strong> ' . (isset($data['time']) ? $data['time'] : '') . '</p>
                    <p><strong>Rastreio:</strong> ' . (isset($data['rastreio']) ? $data['rastreio'] : '') . '</p>
                    <p><strong>Destinatário:</strong> ' . (isset($data['destinatario']) ? $data['destinatario'] : '') . '</p>
                    <p><strong>Endereço:</strong> ' . (isset($data['endereco']) ? $data['endereco'] : '') . '</p>
                    <p><strong>Número:</strong> ' . (isset($data['numero']) ? $data['numero'] : '') . '</p>
                    <p><strong>Complemento:</strong> ' . (isset($data['complemento']) ? $data['complemento'] : '') . '</p>
                    <p><strong>CEP:</strong> ' . (isset($data['cep']) ? $data['cep'] : '') . '</p>
                    <p><strong>Bairro:</strong> ' . (isset($data['bairro']) ? $data['bairro'] : '') . '</p>
                    <p><strong>Telefone:</strong> ' . (isset($data['telefone']) ? $data['telefone'] : '') . '</p>
                    <p><strong>Observação:</strong> ' . (isset($data['observacao']) ? $data['observacao'] : '') . '</p>
                    <p><strong>Cabe Moto:</strong> ' . (isset($data['cabe_moto']) ? $data['cabe_moto'] : '') . '</p>
                    <p><strong>Andamento:</strong> ' . (isset($data['andamento']) ? $data['andamento'] : '') . '</p>
                    <p><strong>Status:</strong> ' . (isset($data['status']) ? $data['status'] : '') . '</p>
                    <p><strong>Setor:</strong> ' . (isset($data['setor']) ? $data['setor'] : '') . '</p>
                    <p><strong>Busca:</strong> ' . (isset($data['busca']) ? $data['busca'] : '') . '</p>
                    <p><strong>Cliente ID:</strong> ' . (isset($data['cliente_id']) ? $data['cliente_id'] : '') . '</p>
                    <p><strong>Entregador:</strong> ' . (isset($data['entregador']) ? $data['entregador'] : '') . '</p>
                    <p><strong>Entregador Entrega:</strong> ' . (isset($data['entregador_entrega']) ? $data['entregador_entrega'] : '') . '</p>
                    <hr>
                ';
                
                // Adicionar o conteúdo HTML ao mPDF
                $choice = $choice + 1;
                $mpdf->WriteHTML($html);
                }
                // Adicionar o conteúdo HTML ao mPDF
                $html = "Solicitações de entrega: " . $choice . "<hr>";
                $mpdf->WriteHTML($html);
                
                // Gerar o PDF
                $mpdf->Output('formulario.pdf', 'D');
                }        
            
        }elseif($choice == "3"){

            $id = str_replace("__", "/", $id);
            $criar = getenv('data_checkeb');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->entregador;
            $pass = session()->get('senha');
            $login = session()->get('user_e');
            $check = $collection->findOne(['user_e' => $login, 'senha' => $pass, 'status' => 'ativo']);
            if ($check) {
            
                $id = str_replace("__", "/", $id);
                $start = $ano.'-'.$mes."-".$dia;
                $end = $a.'-'.$m."-".$d;
                $criar = getenv('data_base');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->produto;
                
                $filter = ['$or' => [[
                    'time' => [
                        '$gte' => $start,
                        '$lte' => $end
                    ],
                    '$or' => [
                        ['entregador_id' => $check->token], ['entregador_id_entrega' => $check->token]
                        ]
                        ]]];
                $collection = $client->$ambiente->produto;
                
                $result = $collection->find($filter);

                // Criar um novo objeto mPDF
            $mpdf = new \Mpdf\Mpdf();

            // Extrair os dados do objeto $result
            $html = "";
            $choice = 0;
            foreach($result as $data){
                if (isset($data->entregador_id_entrega) && $data->entregador_id_entrega == $id) {
                    $html = '
                        <h2>Entrega Base - Destinatario</h2>
                        <hr>
                        <p><strong>Rastreio:</strong> ' . (isset($data['rastreio']) ? $data['rastreio'] : '') . '</p>
                        <p><strong>Tempo de solicitação:</strong> ' . (isset($data['time']) ? $data['time'] : '') . '</p>
                        <p><strong>Destinatário:</strong> ' . (isset($data['destinatario']) ? $data['destinatario'] : '') . '</p>
                        <p><strong>Endereço:</strong> ' . (isset($data['endereco']) ? $data['endereco'] : '') . ' , ' . (isset($data['numero']) ? $data['numero'] : '') . '</p>
                        <p><strong>Complemento:</strong> ' . (isset($data['complemento']) ? $data['complemento'] : '') . '</p>
                        <p><strong>CEP:</strong> ' . (isset($data['cep']) ? $data['cep'] : '') . '</p>
                        <p><strong>Bairro:</strong> ' . (isset($data['bairro']) ? $data['bairro'] : '') . '</p>
                        <p><strong>Telefone:</strong> ' . (isset($data['telefone']) ? $data['telefone'] : '') . '</p>
                        <p><strong>Observação:</strong> ' . (isset($data['observacao']) ? $data['observacao'] : '') . '</p>
                        <p><strong>Cabe Moto:</strong> ' . (isset($data['cabe_moto']) ? $data['cabe_moto'] : '') . '</p>
                        <p><strong>Andamento:</strong> ' . (isset($data['andamento']) ? $data['andamento'] : '') . '</p>
                        <p><strong>Status:</strong> ' . (isset($data['status']) ? $data['status'] : '') . '</p>
                        <p><strong>Setor:</strong> ' . (isset($data['setor']) ? $data['setor'] : '') . '</p>
                        <p><strong>Endereço Cliente:</strong> ' . (isset($data['busca']) ? $data['busca'] : '') . '</p>
                        <hr>
                    ';
                } else {
                    $html = '
                        <h2>Entrega Cliente - Base</h2>
                        <hr>
                        <p><strong>Tempo de solicitação:</strong> ' . (isset($data['time']) ? $data['time'] : '') . '</p>
                        <p><strong>Rastreio:</strong> ' . (isset($data['rastreio']) ? $data['rastreio'] : '') . '</p>
                        <p><strong>Destinatário:</strong> ' . (isset($data['destinatario']) ? $data['destinatario'] : '') . '</p>
                        <p><strong>Endereço:</strong> ' . (isset($data['endereco']) ? $data['endereco'] : '') . ', ' . (isset($data['numero']) ? $data['numero'] : '') . '</p>
                        <p><strong>Complemento:</strong> ' . (isset($data['complemento']) ? $data['complemento'] : '') . '</p>
                        <p><strong>CEP:</strong> ' . (isset($data['cep']) ? $data['cep'] : '') . '</p>
                        <p><strong>Bairro:</strong> ' . (isset($data['bairro']) ? $data['bairro'] : '') . '</p>
                        <p><strong>Telefone:</strong> ' . (isset($data['telefone']) ? $data['telefone'] : '') . '</p>
                        <p><strong>Observação:</strong> ' . (isset($data['observacao']) ? $data['observacao'] : '') . '</p>
                        <p><strong>Cabe Moto:</strong> ' . (isset($data['cabe_moto']) ? $data['cabe_moto'] : '') . '</p>
                        <p><strong>Status:</strong> ' . (isset($data['status']) ? $data['status'] : '') . '</p>
                        <p><strong>Setor:</strong> ' . (isset($data['setor']) ? $data['setor'] : '') . '</p>
                        <p><strong>Endereço cliente:</strong> ' . (isset($data['busca']) ? $data['busca'] : '') . '</p>
                        <hr>
                    ';
                }
            $choice = $choice + 1;
            $mpdf->WriteHTML($html);
            }
            // Adicionar o conteúdo HTML ao mPDF
            $html = "Quantidades de entregas: " . $choice . "<hr>";
            $mpdf->WriteHTML($html);

            // Gerar o PDF
            $mpdf->Output('formulario.pdf', 'D');

            }      
        }elseif($choice == "4"){
            $user = session()->get('user_b');
        $pass = session()->get('senha');
        $criar = getenv('data_checkeb');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->base;
        $customer = $collection->findOne(['$and' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo']]]);

        if ($customer) {
            $id = str_replace("__", "/", $id);
            $start = $ano . '-' . $mes . '-' . $dia;
            $end = $a . '-' . $m . '-' . $d;
            $criar = getenv('data_base');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->produto;

            $filter = [
                '$or' => [
                    [
                        'time' => [
                            '$gte' => $start,
                            '$lte' => $end
                        ],
                        'entregador_id' => $id
                    ],
                    [
                        'time' => [
                            '$gte' => $start,
                            '$lte' => $end
                        ],
                        'entregador_id_entrega' => $id
                    ]
                ]
            ];

            $result = $collection->find($filter);

            // Criar uma nova planilha
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Definir o cabeçalho
            $header = ['Rastreio', 'Tempo de solicitação', 'Destinatário', 'Endereço', 'Complemento', 'CEP', 'Bairro', 'Telefone', 'Observação', 'Cabe Moto', 'Andamento', 'Status', 'Setor', 'Endereço Cliente', 'Entregador', 'Documento Recebedor'];
            $sheet->fromArray($header, null, 'A1');

            $row = 2;
            foreach ($result as $data) {
                $rowData = [
                    isset($data->entregador_entrega) ? 'Entrega Base - Destinatario' : 'Entrega Cliente - Base',
                    isset($data['rastreio']) ? $data['rastreio'] : '',
                    isset($data['time']) ? $data['time'] : '',
                    isset($data['destinatario']) ? $data['destinatario'] : '',
                    isset($data['endereco']) && isset($data['numero']) ? $data['endereco'] . ', ' . $data['numero'] : '',
                    isset($data['complemento']) ? $data['complemento'] : '',
                    isset($data['cep']) ? $data['cep'] : '',
                    isset($data['bairro']) ? $data['bairro'] : '',
                    isset($data['telefone']) ? $data['telefone'] : '',
                    isset($data['observacao']) ? $data['observacao'] : '',
                    isset($data['cabe_moto']) ? $data['cabe_moto'] : '',
                    isset($data['andamento']) ? $data['andamento'] : '',
                    isset($data['status']) ? $data['status'] : '',
                    isset($data['setor']) ? $data['setor'] : '',
                    isset($data['busca']) ? $data['busca'] : '',
                    isset($data->entregador_entrega) ? $data['entregador_entrega'] : (isset($data['entregador']) ? $data['entregador'] : ''),
                ];
                
                $sheet->fromArray($rowData, null, 'A' . $row);
                $row++;
            }

            // Definir a largura automática das colunas
            foreach (range('A', 'P') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Gerar o arquivo Excel
            $writer = new Xlsx($spreadsheet);
            $writer->save( WRITEPATH . 'uploads/formulario.xlsx');
            $filename = WRITEPATH . 'uploads/formulario.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);

        // Excluir o arquivo após o download
        unlink($filename);

            die();


                       }
                }elseif($choice == "5"){
                    $user = session()->get('user_b');
                    $pass = session()->get('senha');
                    $criar = getenv('data_checkeb');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->base;
                    $customer = $collection->findOne(['$and' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo']]]);

                    if ($customer) {
                        $id = str_replace("__", "/", $id);
                        $start = $ano . '-' . $mes . '-' . $dia;
                        $end = $a . '-' . $m . '-' . $d;
                        $criar = getenv('data_base');
                        $host = getenv('host_db');
                        $ambiente = getenv('ambiente');
                        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                        $collection = $client->$ambiente->cliente;

                        $cli = $collection->findOne(['$or' => [['cpf' => $id], ['cnpj' => $id]]]);
                        $rz = $cli->_id;
                        $filter = [
                            '$or' => [
                                [
                                    'time' => [
                                        '$gte' => $start,
                                        '$lte' => $end
                                    ],
                                    'cliente_id' => new MongoDB\BSON\ObjectID($rz)
                                ]
                            ]
                        ];
                        $collection = $client->$ambiente->produto;
                        $result = $collection->find($filter);

                        // Criar uma nova planilha
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();

                        // Definir o cabeçalho
                        $header = ['Time', 'Rastreio', 'Destinatário', 'Endereço', 'Número', 'Complemento', 'CEP', 'Bairro', 'Telefone', 'Observação', 'Cabe Moto', 'Andamento', 'Status', 'Setor', 'Busca', 'Cliente ID', 'Entregador', 'Entregador ID', 'Entregador Entrega', 'Entregador ID Entrega', 'Documento Recebedor'];
                        $sheet->fromArray($header, null, 'A1');

                        $row = 2;
                        foreach ($result as $data) {
                            $rowData = [
                                isset($data['time']) ? $data['time'] : '',
                                isset($data['rastreio']) ? $data['rastreio'] : '',
                                isset($data['destinatario']) ? $data['destinatario'] : '',
                                isset($data['endereco']) ? $data['endereco'] : '',
                                isset($data['numero']) ? $data['numero'] : '',
                                isset($data['complemento']) ? $data['complemento'] : '',
                                isset($data['cep']) ? $data['cep'] : '',
                                isset($data['bairro']) ? $data['bairro'] : '',
                                isset($data['telefone']) ? $data['telefone'] : '',
                                isset($data['observacao']) ? $data['observacao'] : '',
                                isset($data['cabe_moto']) ? $data['cabe_moto'] : '',
                                isset($data['andamento']) ? $data['andamento'] : '',
                                isset($data['status']) ? $data['status'] : '',
                                isset($data['setor']) ? $data['setor'] : '',
                                isset($data['busca']) ? $data['busca'] : '',
                                isset($data['cliente_id']) ? $data['cliente_id'] : '',
                                isset($data['entregador']) ? $data['entregador'] : '',
                                isset($data['entregador_id']) ? $data['entregador_id'] : '',
                                isset($data['entregador_entrega']) ? $data['entregador_entrega'] : '',
                            ];
                            
                            $sheet->fromArray($rowData, null, 'A' . $row);
                            $row++;
                        }

                        // Definir a largura automática das colunas
                        foreach (range('A', 'U') as $column) {
                            $sheet->getColumnDimension($column)->setAutoSize(true);
                        }

                        // Gerar o arquivo Excel
                        $writer = new Xlsx($spreadsheet);
                        $writer->save(WRITEPATH . 'uploads/formulario.xlsx');
                        $filename = WRITEPATH . 'uploads/formulario.xlsx';
                        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);

        // Excluir o arquivo após o download
        unlink($filename);
                    }
                }elseif($choice == 6){

                    $id = str_replace("__", "/", $id);
        $criar = getenv('data_checkeb');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->entregador;
        $pass = session()->get('senha');
        $login = session()->get('user_e');
        $check = $collection->findOne(['user_e' => $login, 'senha' => $pass, 'status' => 'ativo']);

        if ($check) {
            $id = str_replace("__", "/", $id);
            $start = $ano . '-' . $mes . '-' . $dia;
            $end = $a . '-' . $m . '-' . $d;
            $criar = getenv('data_base');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->produto;

            $result = $collection->find($filter);

            // Criar uma nova planilha
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Extrair os dados do objeto $result e preencher a planilha
            $row = 1;
            $choice = 0;
            foreach ($result as $data) {
                $rowData = [];
                if (isset($data->entregador_id_entrega) && $data->entregador_id_entrega == $id) {
                    $rowData = [
                        'Entrega Base - Destinatario',
                        '',
                        'Rastreio',
                        isset($data['rastreio']) ? $data['rastreio'] : '',
                        'Tempo de solicitação',
                        isset($data['time']) ? $data['time'] : '',
                        'Destinatário',
                        isset($data['destinatario']) ? $data['destinatario'] : '',
                        'Endereço',
                        isset($data['endereco']) && isset($data['numero']) ? $data['endereco'] . ', ' . $data['numero'] : '',
                        'Complemento',
                        isset($data['complemento']) ? $data['complemento'] : '',
                        'CEP',
                        isset($data['cep']) ? $data['cep'] : '',
                        'Bairro',
                        isset($data['bairro']) ? $data['bairro'] : '',
                        'Telefone',
                        isset($data['telefone']) ? $data['telefone'] : '',
                        'Observação',
                        isset($data['observacao']) ? $data['observacao'] : '',
                        'Cabe Moto',
                        isset($data['cabe_moto']) ? $data['cabe_moto'] : '',
                        'Andamento',
                        isset($data['andamento']) ? $data['andamento'] : '',
                        'Status',
                        isset($data['status']) ? $data['status'] : '',
                        'Setor',
                        isset($data['setor']) ? $data['setor'] : '',
                        'Endereço Cliente',
                        isset($data['busca']) ? $data['busca'] : '',
                        'Entregador',
                        isset($data['entregador']) ? $data['entregador'] : '',
                        'Documento Recebedor',
                        isset($data['documento_recebedor']) ? $data['documento_recebedor'] : '',
                    ];
                } else {
                    $rowData = [
                        'Entrega Cliente - Base',
                        '',
                        'Tempo de solicitação',
                        isset($data['time']) ? $data['time'] : '',
                        'Rastreio',
                        isset($data['rastreio']) ? $data['rastreio'] : '',
                        'Destinatário',
                        isset($data['destinatario']) ? $data['destinatario'] : '',
                        'Endereço',
                        isset($data['endereco']) && isset($data['numero']) ? $data['endereco'] . ', ' . $data['numero'] : '',
                        'Complemento',
                        isset($data['complemento']) ? $data['complemento'] : '',
                        'CEP',
                        isset($data['cep']) ? $data['cep'] : '',
                        'Bairro',
                        isset($data['bairro']) ? $data['bairro'] : '',
                        'Telefone',
                        isset($data['telefone']) ? $data['telefone'] : '',
                        'Observação',
                        isset($data['observacao']) ? $data['observacao'] : '',
                        'Cabe Moto',
                        isset($data['cabe_moto']) ? $data['cabe_moto'] : '',
                        'Status',
                        isset($data['status']) ? $data['status'] : '',
                        'Setor',
                        isset($data['setor']) ? $data['setor'] : '',
                        'Endereço cliente',
                        isset($data['busca']) ? $data['busca'] : '',
                        'Entregador',
                        isset($data['entregador_entrega']) ? $data['entregador_entrega'] : '',
                    ];
                }
                

                $sheet->fromArray($rowData, null, 'A' . $row);
                $row += 2;
                $choice++;
            }

            // Definir a largura automática das colunas
            foreach (range('A', 'U') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Gerar o arquivo Excel
            $writer = new Xlsx($spreadsheet);
            $writer->save(WRITEPATH . 'uploads/formulario.xlsx');
            $filename = WRITEPATH . 'uploads/formulario.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);

        // Excluir o arquivo após o download
        unlink($filename);
        }

                }


    }

    public function setores($page = 1)
    {
        $user = session()->get('user_b');
        $pass = session()->get('senha');
        $criar = getenv('data_checkeb');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->base;
        $customer = $collection->findOne(['$and' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo']]]);
        if ($customer) {
            $criar = getenv('data_setor');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->setor;
             // Obtém o valor do campo de pesquisa
            $search = $this->request->getGet('search');

            // Configuração da paginação
            $perPage = 200;
            $totalRows = $collection->count();
            $totalPages = ceil($totalRows / $perPage);
            $offset = ($page - 1) * $perPage;

            // Constrói a consulta de pesquisa
            $searchQuery = [];
            if (!empty($search)) {
                // Obtém um documento da coleção
                $document = $collection->findOne();

                // Verifica se há documento e converte para array
                if ($document instanceof MongoDB\Model\BSONDocument) {
                    $document = $document->getArrayCopy();
                }

                // Obtém os nomes dos campos da coleção
                $fieldNames = array_keys($document);

                // Constrói as expressões de pesquisa para cada campo
                $searchExpressions = [];
                foreach ($fieldNames as $fieldName) {
                    if (is_numeric($search)) {
                        $searchExpressions[] = [$fieldName => intval($search)];
                    } else {
                        $searchExpressions[] = [$fieldName => ['$regex' => $search, '$options' => 'i']];
                    }
                }

                // Combina as expressões de pesquisa com a operação lógica $or
                $searchQuery = ['$or' => $searchExpressions];
            }

            // Obtém os documentos da coleção com a paginação e pesquisa aplicadas
            $setores = $search ? $collection->find($searchQuery, ['limit' => $perPage, 'skip' => $offset]) : $collection->find([], ['limit' => $perPage, 'skip' => $offset]);

            // Carrega a visualização da tabela com os dados e a paginação
            $data['setores'] = $setores;
            $data['pagination'] = [
                'totalRows' => $totalRows,
                'perPage' => $perPage,
                'currentPage' => $page,
                'totalPages' => $totalPages
            ];

            // Passa o valor de pesquisa para a visualização
            $data['search'] = $search;
                    return view('base/setor', $data);
    
        }
        

    }
    public function edit()
    {
        $user = session()->get('user_b');
        $pass = session()->get('senha');
        $criar = getenv('data_checkeb');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->base;
        $customer = $collection->findOne(['$and' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo']]]);
        if ($customer) {
            $criar = getenv('data_base');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->base;
        
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Recupera os valores dos campos do formulário
                $id = esc($_POST["id"]);
                $cpfcnpj = esc($_POST["cpfcnpj"]);
                $nome = esc($_POST["nome"]);
                $password = esc($_POST["password"]);
                $nomee = esc($_POST["nomee"]);
                $endereco = esc($_POST["endereco"]);
                $numero = esc($_POST["numero"]);
                $complemento = esc($_POST["complemento"]);
                $cep = esc($_POST["cep"]);
                $telefone = esc($_POST["telefone"]);
                $status = esc($_POST["status"]);
                $role = esc($_POST["role"]);
                // Define os campos que devem ser atualizados
                $updateFields = [];
              
                if (!empty($cpfcnpj)) {
                  $updateFields["cpfcnpj"] = $cpfcnpj;
                }
              
                if (!empty($nome)) {
                  $updateFields["user_b"] = $nome;
                }
              
                if (!empty($password)) {
                  $updateFields["senha"] = hash('sha3-256', $password);
                }
              
                if (!empty($nomee)) {
                  $updateFields["nome"] = $nomee;
                }
              
                if (!empty($endereco)) {
                  $updateFields["endereco"] = $endereco;
                }
              
                if (!empty($numero)) {
                  $updateFields["numero"] = $numero;
                }
              
                if (!empty($complemento)) {
                  $updateFields["complemento"] = $complemento;
                }
              
                if (!empty($cep)) {
                  $updateFields["cep"] = $cep;
                }
              
                if (!empty($telefone)) {
                  $updateFields["telefone"] = $telefone;
                }
              
                if (!empty($status)) {
                  $updateFields["status"] = $status;
                }
                if (!empty($role)) {
                    $updateFields["role"] = $role;
                  }
              
                // Atualiza os dados no banco de dados
                $result = $collection->updateOne(
                  ["token" => $id], // Filtro para encontrar o documento específico a ser atualizado
                  ['$set' => $updateFields] // Campos a serem atualizados
                );
              
                // Verifica se a atualização foi realizada com sucesso
                if ($result->getModifiedCount() > 0) {
                  echo json_encode("Dados atualizados com sucesso!");
                } else {
                  $error = "Falha ao atualizar os dados.";
                  echo json_encode($error);
                }
              
                exit;
              }
              
              $id = $_GET['id'];
              // Se não for uma requisição POST, retorne os dados do documento
              $document = $collection->findOne( ['_id' => new MongoDB\BSON\ObjectID($id)]); // Você pode adicionar filtros aqui, se necessário
              
              // Retorne os dados como uma resposta JSON
              echo json_encode($document);
        }


    }
    public function edite()
    {
        $user = session()->get('user_b');
        $pass = session()->get('senha');
        $criar = getenv('data_checkeb');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->base;
        $customer = $collection->findOne(['$and' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo']]]);
        if ($customer) {
            $criar = getenv('data_base');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->entregador;
        
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Recupera os valores dos campos do formulário
                $id = esc($_POST["id"]);
                $cpfcnpj = esc($_POST["cpfcnpj"]);
                $nome = esc($_POST["nome"]);
                $password = esc($_POST["password"]);
                $nomee = esc($_POST["nomee"]);
                $endereco = esc($_POST["endereco"]);
                $numero = esc($_POST["numero"]);
                $complemento = esc($_POST["complemento"]);
                $cep = esc($_POST["cep"]);
                $telefone = esc($_POST["telefone"]);
                $status = esc($_POST["status"]);
                $apelido = esc($_POST["apelido"]);

                // Define os campos que devem ser atualizados
                $updateFields = [];
              
                if (!empty($cpfcnpj)) {
                  $updateFields["cpfcnpj"] = $cpfcnpj;
                }
              
                if (!empty($nome)) {
                  $updateFields["user_e"] = $nome;
                }
              
                if (!empty($password)) {
                  $updateFields["senha"] = hash('sha3-256', $password);
                }
              
                if (!empty($nomee)) {
                  $updateFields["nome"] = $nomee;
                }
              
                if (!empty($endereco)) {
                  $updateFields["endereco"] = $endereco;
                }
              
                if (!empty($numero)) {
                  $updateFields["numero"] = $numero;
                }
              
                if (!empty($complemento)) {
                  $updateFields["complemento"] = $complemento;
                }
              
                if (!empty($cep)) {
                  $updateFields["cep"] = $cep;
                }
              
                if (!empty($telefone)) {
                  $updateFields["telefone"] = $telefone;
                }
              
                if (!empty($status)) {
                  $updateFields["status"] = $status;
                }

                if (!empty($apelido)) {
                    $updateFields["apelido"] = $apelido;
                  }
                // Atualiza os dados no banco de dados
                $result = $collection->updateOne(
                  ["token" => $id], // Filtro para encontrar o documento específico a ser atualizado
                  ['$set' => $updateFields] // Campos a serem atualizados
                );
              
                // Verifica se a atualização foi realizada com sucesso
                if ($result->getModifiedCount() > 0) {
                  echo json_encode("Dados atualizados com sucesso!");
                } else {
                  $error = "Falha ao atualizar os dados.";
                  echo json_encode($error);
                }
              
                exit;
              }
              
              $id = $_GET['id'];
              // Se não for uma requisição POST, retorne os dados do documento
              $document = $collection->findOne( ['_id' => new MongoDB\BSON\ObjectID($id)]); // Você pode adicionar filtros aqui, se necessário
              
              // Retorne os dados como uma resposta JSON
              echo json_encode($document);
        }


    }
    public function clientes()
    {
        $user = session()->get('user_b');
        $pass = session()->get('senha');
        $criar = getenv('data_checkeb');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->base;
        $customer = $collection->findOne(['$or' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo', 'role' => 'coordenador'],['user_b' => $user, 'senha' => $pass, 'status' => 'ativo', 'role' => 'coordenadorSupervisor']]]);
        if ($customer) {
            $criar = getenv('data_base');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->cliente;
            $id = esc($_POST['id']);
            $cliente_u = $collection->findOne( ['_id' => new MongoDB\BSON\ObjectID($id)]); 
            $html = "";
            if(isset($cliente_u->nome)){
                $nome = $cliente_u->nome;
                $docs = $cliente_u->cpf;
            }else{
                $nome = $cliente_u->empresa;
                $docs = $cliente_u -> cnpj;
            }
     
                $html .= '
                <div class="container">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Email:</strong>' . $cliente_u->email . '</li>
                        <li class="list-group-item"><strong>Nome:</strong>' . $nome . '</li>
                        <li class="list-group-item"><strong>CPF/CNPJ:</strong>' . $docs . '</li>
                        <li class="list-group-item"><strong>Endereço:</strong>' . $cliente_u->endereco . '</li>
                        <li class="list-group-item"><strong>Número:</strong>' . $cliente_u->numero . '</li>
                        <li class="list-group-item"><strong>Complemento:</strong>' . $cliente_u->complemento . '</li>
                        <li class="list-group-item"><strong>CEP:</strong>' . $cliente_u->cep . '</li>
                        <li class="list-group-item"><strong>Bairro:</strong>' . $cliente_u->bairro . '</li>
                        <li class="list-group-item"><strong>Telefone:</strong>' . $cliente_u->telefone . '</li>
                        <li class="list-group-item"><strong>Celular:</strong>' . $cliente_u->celular . '</li>
                        <li class="list-group-item"><strong>Responsável:</strong>' . $cliente_u->responsavel . '</li>
                        <li class="list-group-item"><strong>Status:</strong>' . $cliente_u->status . '</li>
                        <li class="list-group-item"><strong>Setor:</strong>' . (isset($cliente_u->setor) ? $cliente_u->setor : 'Expansão') . '</li>
                        
                    </ul>
                </div>
                ';
            
        echo $html;
        die();            
    
        }
    }

    public function contrato()
    {
        $user = session()->get('user_b');
        $pass = session()->get('senha');
        $criar = getenv('data_checkeb');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->base;
        $customer = $collection->findOne(['$or' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo', 'role' => 'coordenador'],['user_b' => $user, 'senha' => $pass, 'status' => 'ativo', 'role' => 'coordenadorSupervisor']]]);
        if ($customer) {
        $id = esc($_POST['dataId']);
        $link = esc($_POST['link']);
        $criar = getenv('data_cliente');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->cliente;

        $result = $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectID($id)],
            ['$set' => ['contrato' => $link]]
        );
        echo "atribuido com sucesso";
        }
    }

    public function delete(){
        $user = session()->get('user_b');
        $pass = session()->get('senha');
        $criar = getenv('data_checkeb');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->base;
        $customer = $collection->findOne(['$or' => [['user_b' => $user, 'senha' => $pass, 'status' => 'ativo', 'role' => 'coordenador'],['user_b' => $user, 'senha' => $pass, 'status' => 'ativo', 'role' => 'coordenadorSupervisor']]]);
        if ($customer) {
        $id = esc($_POST['id']);
        $criar = getenv('data_cliente');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->cliente;

        $result = $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectID($id)],
            ['$set' => ['status' => 'desativado']]
        );
        echo "desativado";
        }
    }
}
