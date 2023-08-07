<?php

namespace App\Controllers;

use MongoDB;
use Smalot\PdfParser\Parser;
use chillerlan\QRCode\QRCode;
use Mpdf\Mpdf;
use Mpdf\Image\ImageProcessor;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cliente extends BaseController
{
    public function pedidos()
    {
        $criar = getenv('data_check');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->cliente;
        $cnpj = session()->get('cnpj');
        $pass = session()->get('pass');
        $token = session()->get('token');
        $cpf = session()->get('cpf');
        $check = $collection->findOne([
            '$or' => [
                ['cnpj' => $cnpj, 'senha' => $pass, 'status' => 'active', 'token' => $token],
                ['cpf' => $cpf, 'senha' => $pass, 'status' => 'active', 'token' => $token]
            ]
        ]);
        if ($check) {
            $id = $check->_id;
            $inserir = getenv('data_cliente');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $inserir . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->produto;
            $today = date('d/m/Y');

            $pedidos = $collection->find([
                'cliente_id' => $id,
                '$or' => [
                    ['status' => ['$nin' => ['entregue', 'cancelado']]],
                    ['$and' => [
                        ['status' => 'entregue'],
                        ['datea1' => $today]
                    ]]
                ]
            ]);
            
            


            function recuperarMensagens($sender, $receiver)
            {
                $filter = [
                  '$or' => [
                    ['sender' => $sender, 'receiver' => $receiver],
                    ['sender' => $receiver, 'receiver' => $sender],

                  ]
                ];
                $criar = getenv('data_sac');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->sac;
                $options = [
                    'sort' => ['dateo' => 1]
                  ];

                $messages = $collection->find($filter, $options);

                return $messages;
            }

            $cnpj = session()->get('cnpj');
            $cpf = session()->get('cpf');
            if(isset($cpf)) {
                $doc = $cpf;
            } else {
                $doc = $cnpj;
            }
            $mensagens = recuperarMensagens('sac', $doc);
            $criar = getenv('data_check');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->cliente;
            $cnpj = session()->get('cnpj');
            $pass = session()->get('pass');
            $token = session()->get('token');
            $cpf = session()->get('cpf');
            $check = $collection->findOne([
                '$or' => [
                    ['cnpj' => $cnpj, 'senha' => $pass, 'status' => 'active', 'token' => $token],
                    ['cpf' => $cpf, 'senha' => $pass, 'status' => 'active', 'token' => $token]
                ]
            ]);
            if ($check) {
            $state = "logado";
            if (isset($check->cpf)){
                $login = $check->cpf;
            }else{
                $login = $check->cnpj;
            }
            if (isset($check->contrato)){
                $contrato = $check->contrato;
            }else{
                $contrato = "";
            }
            return view('templates/header.php', ['cliente' => $login , 'pedidos' => $pedidos]) . view('painel', ['chat' => $mensagens, 'contrato' => $contrato]); 
            }
        } else {
            header("Location: " . base_url());
            die();
        }
    }

    public function post()
    {
        $criar = getenv('data_check');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->cliente;
        $cnpj = session()->get('cnpj');
        $pass = session()->get('pass');
        $token = session()->get('token');
        $cpf = session()->get('cpf');
        $check = $collection->findOne([
            '$or' => [
                ['cnpj' => $cnpj, 'senha' => $pass, 'status' => 'active', 'token' => $token],
                ['cpf' => $cpf, 'senha' => $pass, 'status' => 'active', 'token' => $token]
            ]
        ]);
        if ($check) {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Se tiver o post de destinatario, entra nesse if
                if (isset($_POST['destinatario'])) {
                    $destinatario =  $_POST['destinatario'];
                    $endereco =  $_POST['endereco'];
                    $numero = esc($_POST['numero']);
                    $complemento =  $_POST['complemento'];
                    $cep =  $_POST['cep'];
                    $bairro =  $_POST['bairro'];
                    $telefone =  $_POST['telefone'];
                    $observacao = esc($_POST['observacao']);
                    $cabeMoto = esc($_POST['cabeMoto']);
                    if(empty($observacao)){
                        $observacao = "Sem observação";
                    }
                    if(empty($complemento)){
                        $complemento = "Sem complemento";
                    }
                    $emaildest = esc($_POST['emaildest']);
                    if($_POST['declal']){
                        $declared = esc($_POST['declal']);
                    }else{
                        $declared = "Não";
                    }
                    // Inserção
                    $id = $check->_id;
                    $inserir = getenv('data_base');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new MongoDB\Client('mongodb://' . $inserir . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->setor;
                    $findsetor = $collection->findOne(['cep' => intval($cep)]);
                    if (empty($findsetor)){
                        $setorEntrega = "Expansão";
                    }else{
                        $setorEntrega = $findsetor->setor;
                    }

                    $inserir = getenv('data_cliente');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new MongoDB\Client('mongodb://' . $inserir . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->produto;

                    function gerarCodigoRastreio($length = 10)
                    {
                        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $codigo = '';
                        $maxIndex = strlen($characters) - 1;

                        for ($i = 0; $i < $length; $i++) {
                            $codigo .= $characters[rand(0, $maxIndex)];
                        }
                        $horaAtual = date('H:i'); // Obtém a hora atual no formato HH:MM
                    if ($horaAtual >= '00:01' && $horaAtual <= '11:59') {
                        $codigo = 'RT' . $codigo . 'DD';
                    } elseif ($horaAtual >= '12:00' && $horaAtual <= '18:00') {
                        $codigo = 'RT' . $codigo . 'TT';
                    } else {
                        $codigo = 'RT' . $codigo . 'NN';
                    }


                        return $codigo;
                    }

                    // Gerar um código de rastreamento único
                    $rastreio = gerarCodigoRastreio();

                    // Verificar se o código de rastreamento já existe no collection
                    while ($collection->count(['rastreio' => $rastreio]) > 0) {
                        // Se o código de rastreamento já existe, gerar um novo
                        $rastreio = gerarCodigoRastreio();
                    }
                    $dataHoraAtual = date('Y-m-d H:i:s');

           
                    $email = \Config\Services::email();
                $email->setTo($emaildest);
                $email->setFrom('cadastro@rtes.com.br');
                $email->setSubject('Envio de produto em seu nome');
                $email->setMessage("<!DOCTYPE html><html><head><meta charset='utf-8'><meta http-equiv='x-ua-compatible' content='ie=edge'><title>Envio a caminho</title><meta name='viewport' content='width=device-width, initial-scale=1'><style type='text/css'>/*** Google webfonts. Recommended to include the .woff version for cross-client compatibility.*/@media screen {@font-face {font-family: 'Source Sans Pro';font-style: normal;font-weight: 400;src: local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format('woff');}@font-face {font-family: 'Source Sans Pro';font-style: normal;font-weight: 700;src: local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format('woff');}}/*** Avoid browser level font resizing.* 1. Windows Mobile* 2. iOS / OSX*/body,table,td,a {-ms-text-size-adjust: 100%; /* 1 */-webkit-text-size-adjust: 100%; /* 2 */}/*** Remove extra space added to tables and cells in Outlook.*/table,td {mso-table-rspace: 0pt;mso-table-lspace: 0pt;}/*** Better fluid images in Internet Explorer.*/img {-ms-interpolation-mode: bicubic;}/*** Remove blue links for iOS devices.*/a[x-apple-data-detectors] {font-family: inherit !important;font-size: inherit !important;font-weight: inherit !important;line-height: inherit !important;color: inherit !important;text-decoration: none !important;}/*** Fix centering issues in Android 4.4.*/div[style*='margin: 16px 0;'] {margin: 0 !important;}body {width: 100% !important;height: 100% !important;padding: 0 !important;margin: 0 !important;}/*** Collapse table borders to avoid space between cells.*/table {border-collapse: collapse !important;}a {color: #1a82e2;}img {height: auto;line-height: 100%;text-decoration: none;border: 0;outline: none;}</style></head><body style='background-color: #e9ecef;'><!-- start preheader --><div class='preheader' style='display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;'>Envio em seu endereço </div><!-- end preheader --><!-- start body --><table border='0' cellpadding='0' cellspacing='0' width='100%'><!-- start logo --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><tr><td align='center' valign='top' style='padding: 36px 24px;'><a href='' target='_blank' style='display: inline-block;'><!-- <img src='#' alt='Logo' border='0' width='48' style='display: block; width: 48px; max-width: 48px; min-width: 48px;'> --></a></td></tr></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end logo --><!-- start hero --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><tr><td align='left' bgcolor='#ffffff' style='padding: 36px 24px 0; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;'><h1 style='margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px;'><center>Solicitação de envio aberta</h1></td></tr></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end hero --><!-- start copy block --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;'><p style='margin: 0;'>Entre no site e coloque seu codigo de rastreio para verificar sobre o produto a caminho: <a href='https://Deliveryexpresssolutions.com.br'>Real time express solutions</a>.</p><p><b>Rastreio: </b> $rastreio </p></td></tr><!-- end copy --><!-- start button --><tr><td align='left' bgcolor='#ffffff'><table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td align='center' bgcolor='#ffffff' style='padding: 12px;'><table border='0' cellpadding='0' cellspacing='0'><tr><td align='center' bgcolor='#1a82e2' style='border-radius: 6px;'><a href='https://Deliveryexpresssolutions.com.br/rastreio' target='_blank' style='display: inline-block; padding: 16px 36px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px;'>rastreio</a></td></tr></table></td></tr></table></td></tr><!-- end button --><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;'><p style='margin: 0;'>Não responda esse email, email automatico</p><p style='margin: 0;'><a href='https://Deliveryexpresssolutions.com.br' target='_blank'>https://Deliveryexpresssolutions.com.br</a></p></td></tr><!-- end copy --><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf'><p style='margin: 0;'>atenciosamente,<br> Real time express solutions.</p></td></tr><!-- end copy --></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end copy block --><!-- start footer --><tr><td align='center' bgcolor='#e9ecef' style='padding: 24px;'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'></td></tr><!-- end unsubscribe --></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end footer --></table><!-- end body --></body></html>");
                if ($email->send()) {

                } else {
                    echo 'Falha ao enviar o e-mail.' ;
                    die();
                }
                    // Inserir os dados no collection
                    $collectionProdutos = $collection->insertOne([
                        'time' => $dataHoraAtual,
                        'rastreio' => $rastreio,
                        'destinatario' => $destinatario,
                        'endereco' => $endereco,
                        'numero' => $numero,
                        'complemento' => $complemento,
                        'cep' => $cep,
                        'bairro' => $bairro,
                        'telefone' => $telefone,
                        'observacao' => $observacao,
                        'cabe_moto' => $cabeMoto,
                        'andamento' => 'on',
                        'entregador_entrega' => null,
                        'documento_recebedor' => null,
                        'status' => 'Aguardando confirmação',
                        'setor' => $check->setor,
                        'setor_entrega' => $setorEntrega,
                        'busca' => $check->endereco . "," . $check->numero,
                        'emaildest' => $emaildest,
                        'declared' => $declared,
                        'status_rastreio' => '<li><b>Solicitado</b>: ' . date('H:i:s d/m/Y') . '</li>',

                        
                        'cliente_id' => $id // Supondo que o ID do cliente seja armazenado em _id
                    ]);
                    
                    echo "cadastrado";
                    die();

                }

                // Certifique-se de que o arquivo foi enviado com sucesso.
                if (!isset($_FILES['arquivo']) || $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
                    http_response_code(400);
                    echo json_encode(['erro' => 'Erro ao enviar o arquivo.']);
                    exit();
                }

                // Crie um objeto de análise do PDF.
                $parser = new Parser();

                // Analise o arquivo PDF e extraia o texto.
                $fieldName = key($_FILES);
                $pdf = $parser->parseFile($_FILES[$fieldName]['tmp_name']);
                $texto = $pdf->getText();

                // Use expressões regulares para extrair as informações desejadas.
                $padraoDestinatario = '/.*Destinatario: (.*)/i';
                $padraoEndereco = '/Endereço: (.*)/i';
                $padraoNumero = '/Numero:(.*)/i';
                $padraoComplemento = '/Complemento: (.*)/i';
                $padraoCEP = '/CEP: (.*)/i';
                $padraoBairro = '/Bairro: (.*)/i';
                $padraoTelefone = '/Telefone: (.*)/i';
                $padraoObservacao = '/Obs: (.*)/i';

                preg_match($padraoDestinatario, $texto, $matchesDestinatario);
                preg_match($padraoEndereco, $texto, $matchesEndereco);
                preg_match($padraoNumero, $texto, $matchesNumero);
                preg_match($padraoComplemento, $texto, $matchesComplemento);
                preg_match($padraoCEP, $texto, $matchesCEP);
                preg_match($padraoBairro, $texto, $matchesBairro);
                preg_match($padraoTelefone, $texto, $matchesTelefone);
                preg_match($padraoObservacao, $texto, $matchesObservacao);

                $destinatario = isset($matchesDestinatario[1]) ? $matchesDestinatario[1] : '';
                $endereco = isset($matchesEndereco[1]) ? $matchesEndereco[1] : '';
                if (!isset($matchesNumero[1])) {
                    if (!isset($matchesEndereco[1])) {
                        $matchesNumero[1] = '';
                    } else {
                        preg_match_all('/\d+/', $matchesEndereco[1], $numeros);

                        $endereco = preg_replace("/\d+/", "", $matchesEndereco[1]);
                        $matchesNumero[1] = "";

                        foreach ($numeros[0] as $numero) {
                            $matchesNumero[1] .= $numero;
                        }
                    }
                }
                $numero = $matchesNumero[1];

                $complemento = isset($matchesComplemento[1]) ? $matchesComplemento[1] : '';
                if(!isset($matchesCEP[1])) {
                    $cep = '';
                } else {
                    $numeros = preg_replace("/[^0-9]/", "", $matchesCEP[1]);
                    $numeros = substr($numeros, 0, 9);
                    $cep = $numeros;
                }
                $bairro = isset($matchesBairro[1]) ? $matchesBairro[1] : '';
                $telefone = isset($matchesTelefone[1]) ? $matchesTelefone[1] : '';
                $observacao = isset($matchesObservacao[1]) ? $matchesObservacao[1] : '';
                //adjuste
                $destinatario = ltrim($destinatario);
                $endereco = ltrim($endereco);
                $numero = ltrim($numero);
                $cep = ltrim($cep);
                $bairro = ltrim($bairro);
                $telefone = ltrim($telefone);
                $observacao = ltrim($observacao);
                $complemento = ltrim($complemento);
                $resultado = [
                    'destinatario' => $destinatario,
                    'endereco' => $endereco,
                    'numero' => $numero,
                    'complemento' => $complemento,
                    'cep' => $cep,
                    'bairro' => $bairro,
                    'telefone' => $telefone,
                    'observacao' => $observacao,

                ];

                echo json_encode($resultado);

            }
        } else {
            die();
        }

    }

    public function activate($variavel)
    {
        $criar = getenv('data_cliente');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->cliente;
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
        $updateResult = $collection->updateMany(
            ['token' => $variavel],
            [
                '$set' => [
                    'status' => 'active',
                    'token' => $token
                ]
            ]
        );
        return view('errors/html/error_404');
    }

    public function forgot($variavel)
    {
        $criar = getenv('data_check');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->cliente;
        $existingCustomer = $collection->findOne(['$and' => [['token' => $variavel], ['alterar' => 'sim'] ]]);
        if($existingCustomer) {
        } else {
            return view('errors/html/error_404');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return view('templates/header') . view('reset');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pass = hash('sha3-256', $_POST['senha']);
            $criar = getenv('data_cliente');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->cliente;
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
            $updateResult = $collection->updateMany(
                ['token' => $variavel],
                [
                    '$set' => [
                        'senha' => $pass,
                        'token' => $token,
                        'alterar' => 'não'
                    ]
                ]
            );
            return view('errors/html/error_404');
        }
    }

    public function pedf($variavel)
    {
        $criar = getenv('data_check');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->cliente;
        $cnpj = session()->get('cnpj');
        $pass = session()->get('pass');
        $token = session()->get('token');
        $cpf = session()->get('cpf');
        $check = $collection->findOne([
            '$or' => [
                ['cnpj' => $cnpj, 'senha' => $pass, 'status' => 'active', 'token' => $token],
                ['cpf' => $cpf, 'senha' => $pass, 'status' => 'active', 'token' => $token]
            ]
        ]);
        if ($check) {
            $criar = getenv('data_cliente');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->produto;
            $check = $collection->findOne([
                '$or' => [
                    ['rastreio' => $variavel]
                ]
            ]);
            if ($check) {
                $collection = $client->$ambiente->cliente;

                $cliente = $collection->findOne([
                    '$or' => [
                        ['cnpj' => $cnpj],
                        ['cpf' => $cpf]

                    ]
                    ]);
                $data['qr'] = (new QRCode())->render($variavel);

                $mpdf = new Mpdf();

                $mpdf->WriteHTML('
                <div style="border: 1px dotted black; width: 70%; margin: 0 auto; padding: 10px; line-height: 1;">
                    <h3>Real Time Express Solutions</h3>
                    <hr>
                    <div style="text-align:center; position: relative;">
                        <img src="' . $data['qr'] . '" style="width:30%; height:30%;">
                        <p>
                        <small style="font-style: italic; position: absolute; bottom: -20px; left: 50%; transform: translateX(-50%);">Para rastreio acesse www.Deliveryexpresssolutions.com.br</small>
                        <hr style="margin-top:3px;">
                    </div>
                    <h4>Remetente:</h4>
                    <p>' . $cliente->nome . '</p>        
                    <h4>Rastreio:</h4>
                    <p>' . $check->rastreio . '</p>        
                    <h4>Rua do Remetente:</h4>
                    <p>' . $cliente->endereco . '</p>
                    <hr>        
                    <h4>Destinatário:</h4>
                    <p>' . $check->destinatario . '</p>        
                    <h4>Endereço:</h4>
                    <p>' . $check->endereco . '</p>        
                    <h4>Bairro:</h4>
                    <p>' . $check->bairro  . '</p>        
                    <h4>Complemento:</h4>
                    <p>' . $check->complemento . '</p>        
                    <h4>CEP:</h4>
                    <p>' . $check->cep . '</p>
                    <h4>Observação:</h4>
                    <p>' . $check->observacao . '</p>   
                    <small style="font-style: italic; position: absolute; bottom: -20px; left: 50%; transform: translateX(-50%);">'.$check->time.'</small>
                </div>
                <style>
                    .imagem-overlay {
                        position: absolute;
                        top: 30px;
                        right: 100px;
                        width: 120px;
                        height: 170px;
                        background-image: url("https://Deliveryexpresssolutions.com.br/assets/img/63769_Delivery_110523_cl.png");
                        background-repeat: no-repeat;
                        background-position: center;
                        background-size: contain;
                        opacity: 0.5;
                        z-index: 9999;
                    }
                </style>
                <div class="imagem-overlay"></div>
            ');
            
            $mpdf->Output();
            

            } else {
                echo "<script> alert('erro, contate um administrador')</script>";
                header('location: ' . base_url(''));
                die();
            }
        } else {
            header('location: ' . base_url(''));
            die();
        }
    }

    public function clean()
    {
        session()->destroy();

        return view('errors/html/error_404');
    }
    public function sac()
    {
        $criar = getenv('data_check');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->cliente;
        $cnpj = session()->get('cnpj');
        $pass = session()->get('pass');
        $token = session()->get('token');
        $cpf = session()->get('cpf');
        $check = $collection->findOne([
            '$or' => [
                ['cnpj' => $cnpj, 'senha' => $pass, 'status' => 'active', 'token' => $token],
                ['cpf' => $cpf, 'senha' => $pass, 'status' => 'active', 'token' => $token]
            ]
        ]);
        if ($check) {
            function enviarMensagem($sender, $receiver, $message, $check)
            {
                global $messagesCollection;

                $data = [
                  'sender' => $sender,
                  'receiver' => $receiver,
                  'message' => $message,
                  'dateo' => new MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d H:i:s')) * 1000),
                  'date' => date('d/m/Y H:i'),
                  'read' => true,
                  'readb' => false,
                  'cliente' => $sender,
                  'token' => hash('sha3-256', $sender),
                  'nome' => $check->nome
                ];
                $criar = getenv('data_sac');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->sac;

                $collection->insertOne($data);
            }

            function recuperarMensagens($sender, $receiver)
            {
                global $collection;

                $filter = [
                    '$or' => [
                        ['sender' => $sender, 'receiver' => $receiver, 'read' => false], // Filtra mensagens não lidas
                        ['sender' => $receiver, 'receiver' => $sender, 'read' => false] // Filtra mensagens não lidas
                    ]
                ];

                $options = [
                    'sort' => ['dateo' => -1]
                  ];
                $criar = getenv('data_sac');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->sac; // Inicialize $messagesCollection com a coleção 'sac'

                $messages = $collection->find($filter, $options);

                return $messages;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                if ($_POST['choice'] == "envio") {
                    // Obtenha a mensagem enviada via AJAX
                    $mensagem = esc($_POST['mensagem']);

                    // Processar a mensagem, se necessário

                    // Construir a nova mensagem formatada como HTML
                    $novaMensagem = '
                <div class="message-right">
                  <div class="message-balloon">
                    <div class="message-text">' . $mensagem . '</div>
                    <div class="message-time">' . date('d/m/Y H:i') . '</div>
                  </div>
                </div>
                ';

                    // Retorne a nova mensagem para ser adicionada ao chat
                    $cnpj = session()->get('cnpj');
                    $cpf = session()->get('cpf');
                    if(isset($cpf)) {
                        $doc = $cpf;
                    } else {
                        $doc = $cnpj;
                    }
                    $envio = enviarMensagem($doc, 'sac', $mensagem, $check);
                    $criar = getenv('data_sac');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->listsac; // Inicialize $messagesCollection com a coleção 'sac'

                    $collection->updateOne(
                        [
                            'cliente' => $doc,
                        ],
                        ['$set' => ['last' =>  $mensagem, 'dateo' => new MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d H:i:s')) * 1000), 'date' =>  date('d/m/Y H:i'), 'nome' =>$check->nome, 'view' => false, 'token' => hash('sha3-256', $doc)]],
                        ['upsert' => true]
                    );
                    echo $novaMensagem;
                    die();
                } elseif($_POST['choice'] == "receber") {
                    $cnpj = session()->get('cnpj');
                    $cpf = session()->get('cpf');
                    if(isset($cpf)) {
                        $doc = $cpf;
                    } else {
                        $doc = $cnpj;
                    }
                    $mensagensNaoLidas = recuperarMensagens($doc, 'sac');

                    // Construa o HTML das mensagens não lidas
                    $htmlMensagens = '';
                    foreach ($mensagensNaoLidas as $mensagem) {
                        // Construa o HTML da mensagem, usando as propriedades desejadas (sender, message, date)
                        $htmlMensagens .= '
                    <div class="message-left">
                        <div class="message-balloon">
                            <div class="message-text">' . $mensagem->message . '</div>
                            <div class="message-time">' . $mensagem->date . '</div>
                        </div>
                    </div>
                    ';

                        $criar = getenv('data_sac');
                        $host = getenv('host_db');
                        $ambiente = getenv('ambiente');
                        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                        $collection = $client->$ambiente->sac; // Inicialize $messagesCollection com a coleção 'sac'

                        // Marque a mensagem como lida atualizando a propriedade "read" para true
                        $collection->updateOne(
                            ['_id' => $mensagem->_id],
                            ['$set' => ['read' => true]]
                        );




                    }

                    // Retorne o HTML das mensagens não lidas como resposta
                    echo $htmlMensagens;
                    die();


                }

            }


        }
    }

    public function doc($mes, $fmes, $choice)
    {
        $criar = getenv('data_check');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->cliente;
        $cnpj = session()->get('cnpj');
        $pass = session()->get('pass');
        $token = session()->get('token');
        $cpf = session()->get('cpf');

        $check = $collection->findOne([
            '$or' => [
                ['cnpj' => $cnpj, 'senha' => $pass, 'status' => 'active', 'token' => $token],
                ['cpf' => $cpf, 'senha' => $pass, 'status' => 'active', 'token' => $token]
            ]
        ]);
        if ($check) {
            $criar = getenv('data_cliente');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->produto;
            if(isset($check->cpf)){
                $doc = $check->cpf;
            }else{
                $doc = $check->cnpj;
            }
        
            if($choice == "pdf"){

                 // Obtém os parâmetros de data do período
                $dataInicial = $mes;
                $dataFinal = $fmes;
                        
                // Consulta os dados no MongoDB com base no período
                $result = $collection->find([
                    'time' => [
                        '$gte' => $dataInicial,
                        '$lte' => $dataFinal
                    ],
                    'cliente_id' => $check->_id

                ]);
                
                
            
                // Instancia o mPDF
                $mpdf = new \Mpdf\Mpdf();
            
                // Inicia o conteúdo do PDF
                $html = '';
                $tester = 0;
            
                // Percorre os resultados do MongoDB
                foreach ($result as $documento) {
                    $tester = $tester + 1;
                    if (isset($documento->rastreio)) {
                        $html .= '<p><b>Rastreio:</b> ' . $documento->rastreio . '</p>';
                    }
                    if (isset($documento->destinatario)) {
                        $html .= '<p><b>Destinatário:</b> ' . $documento->destinatario . '</p>';
                    }
                    if (isset($documento->endereco)) {
                        $html .= '<p><b>Endereço:</b> ' . $documento->endereco . '</p>';
                    }
                    if (isset($documento->numero)) {
                        $html .= '<p><b>Número:</b> ' . $documento->numero . '</p>';
                    }
                    if (isset($documento->complemento)) {
                        $html .= '<p><b>Complemento:</b> ' . $documento->complemento . '</p>';
                    }
                    if (isset($documento->cep)) {
                        $html .= '<p><b>CEP:</b> ' . $documento->cep . '</p>';
                    }
                    if (isset($documento->bairro)) {
                        $html .= '<p><b>Bairro:</b> ' . $documento->bairro . '</p>';
                    }
                    if (isset($documento->telefone)) {
                        $html .= '<p><b>Telefone:</b> ' . $documento->telefone . '</p>';
                    }
                    if (isset($documento->observacao)) {
                        $html .= '<p><b>Observação:</b> ' . $documento->observacao . '</p>';
                    }
                    if (isset($documento->cabe_moto)) {
                        $html .= '<p><b>Cabe Moto:</b> ' . $documento->cabe_moto . '</p>';
                    }
                    if (isset($documento->status)) {
                        $html .= '<p><b>Status:</b> ' . $documento->status . '</p>';
                    }
                    if (isset($documento->setor)) {
                        $html .= '<p><b>Setor:</b> ' . $documento->setor . '</p>';
                    }
                    if (isset($documento->busca)) {
                        $html .= '<p><b>Busca:</b> ' . $documento->busca . '</p>';
                    }
                    if (isset($documento->entregador)) {
                        if (isset($documento->entregador_entrega)) {
                        $html .= '<p><b>Entregadores:</b> ' . $documento->entregador . ', '. $documento->entregador_entrega .'</p>';}
                        }else{
                        $html .= '<p><b>Entregador:</b> ' . $documento->entregador . '</p>';}
                    }
                    $html .= '<hr>'; // Adiciona a linha separadora entre os documentos
                
            
                // Adiciona o conteúdo HTML ao documento PDF
                $mpdf->WriteHTML("<hr><b>Total de entregas: </b>" . $tester . "<hr>");
                $mpdf->WriteHTML($html);
            
                // Gera o arquivo PDF
                $mpdf->Output('relatorio.pdf', 'D');
                die();


            }elseif($choice == "excel"){
                $criar = getenv('data_cliente');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->produto;
                if(isset($check->cpf)){
                    $doc = $check->cpf;
                }else{
                    $doc = $check->cnpj;
                }
    
                // Obtém os parâmetros de data do período
            $dataInicial = $mes;
            $dataFinal = $fmes;
                        
            // Consulta os dados no MongoDB com base no período
            $result = $collection->find([
                'time' => [
                    '$gte' => $dataInicial,
                    '$lte' => $dataFinal
                ],
                'cliente_id' => $check->_id
            ]);
            
            // Instancia a planilha do Excel
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Define o cabeçalho da planilha
            $sheet->setCellValue('A1', 'Rastreio');
            $sheet->setCellValue('B1', 'Destinatário');
            $sheet->setCellValue('C1', 'Endereço');
            $sheet->setCellValue('D1', 'Número');
            $sheet->setCellValue('E1', 'Complemento');
            $sheet->setCellValue('F1', 'CEP');
            $sheet->setCellValue('G1', 'Bairro');
            $sheet->setCellValue('H1', 'Telefone');
            $sheet->setCellValue('I1', 'Observação');
            $sheet->setCellValue('J1', 'Cabe Moto');
            $sheet->setCellValue('K1', 'Status');
            $sheet->setCellValue('L1', 'Setor');
            $sheet->setCellValue('M1', 'Busca');
            $sheet->setCellValue('N1', 'Entregador');
            
            // Percorre os resultados do MongoDB
            $row = 2; // Inicia na segunda linha da planilha
            $tester = 0; // Contador de entregas
            foreach ($result as $documento) {
                $tester = $tester + 1;
                $sheet->setCellValue('A'.$row, isset($documento->rastreio) ? $documento->rastreio : '');
                $sheet->setCellValue('B'.$row, isset($documento->destinatario) ? $documento->destinatario : '');
                $sheet->setCellValue('C'.$row, isset($documento->endereco) ? $documento->endereco : '');
                $sheet->setCellValue('D'.$row, isset($documento->numero) ? $documento->numero : '');
                $sheet->setCellValue('E'.$row, isset($documento->complemento) ? $documento->complemento : '');
                $sheet->setCellValue('F'.$row, isset($documento->cep) ? $documento->cep : '');
                $sheet->setCellValue('G'.$row, isset($documento->bairro) ? $documento->bairro : '');
                $sheet->setCellValue('H'.$row, isset($documento->telefone) ? $documento->telefone : '');
                $sheet->setCellValue('I'.$row, isset($documento->observacao) ? $documento->observacao : '');
                $sheet->setCellValue('J'.$row, isset($documento->cabe_moto) ? $documento->cabe_moto : '');
                $sheet->setCellValue('K'.$row, isset($documento->status) ? $documento->status : '');
                $sheet->setCellValue('L'.$row, isset($documento->setor) ? $documento->setor : '');
                $sheet->setCellValue('M'.$row, isset($documento->busca) ? $documento->busca : '');
                $entregador = isset($documento->entregador) ? $documento->entregador : '';
                $entregadorEntrega = isset($documento->entregador_entrega) ? ' e '.$documento->entregador_entrega : '';
                
                $sheet->setCellValue('N'.$row, $entregador.$entregadorEntrega);
                
                $row++;
            }
            
            // Define o total de entregas
            $sheet->setCellValue('A'.$row, 'Total de entregas:');
            $sheet->setCellValue('B'.$row, $tester);
            
            // Cria um objeto Writer para salvar a planilha em um arquivo Excel
            $writer = new Xlsx($spreadsheet);
            
            // Define o nome do arquivo Excel
            $filename = 'relatorio.xlsx';
            
            // Cabeçalhos para forçar o download do arquivo
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            
            // Salva a planilha em um arquivo Excel
            $writer->save('php://output');
                die();
            }else{
            return view('errors/html/error_404');
            }


        }
        return view('errors/html/error_404');
    }

    public function delete()
    {   
        $criar = getenv('data_check');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->cliente;
        $cnpj = session()->get('cnpj');
        $pass = session()->get('pass');
        $token = session()->get('token');
        $cpf = session()->get('cpf');
        $check = $collection->findOne([
            '$or' => [
                ['cnpj' => $cnpj, 'senha' => $pass, 'status' => 'active', 'token' => $token],
                ['cpf' => $cpf, 'senha' => $pass, 'status' => 'active', 'token' => $token]
            ]
        ]);
        if ($check) {
            $id = esc($_POST['id']);
            $criar = getenv('data_base');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->produto;
            $conditions = [
                '$or' => [
                    ['cliente_id' => new MongoDB\BSON\ObjectID($check->_id)],
                ],
                '_id' => new MongoDB\BSON\ObjectID($id), 'status' => 'Aguardando confirmação'
            ];
            $verificar = $collection->findOne($conditions);
            $updateData = ['$set' => ['status' => 'cancelado', 'andamento' => 'off', 'status_rastreio' => $verificar->status_rastreio . '<li><b> Cancelado</b>: '. date('H:i:s d/m/Y') . '</li>']];
        
            $produto = $collection->updateOne($conditions, $updateData);
            echo "cancelado";
            die();
        
        }
        
    }
    public function config()
    {
        $criar = getenv('data_check');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->cliente;
        $cnpj = session()->get('cnpj');
        $pass = session()->get('pass');
        $token = session()->get('token');
        $cpf = session()->get('cpf');
        $check = $collection->findOne([
            '$or' => [
                ['cnpj' => $cnpj, 'senha' => $pass, 'status' => 'active', 'token' => $token],
                ['cpf' => $cpf, 'senha' => $pass, 'status' => 'active', 'token' => $token]
            ]
        ]);
        if ($check) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $criar = getenv('data_cliente');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->cliente;
                
                   // Atualizar os dados no MongoDB
                   $email = esc($_POST["email"]);
                   $nome = isset($_POST["nome"]) ? $_POST["nome"] : null;
                   $empresa = isset($_POST["empresa"]) ? $_POST["empresa"] : null;
                   $endereco = esc($_POST["endereco"]);
                   $numero = esc($_POST["numero"]);
                   $complemento = esc($_POST["complemento"]);
                   $cep = esc($_POST["cep"]);
                   $bairro = esc($_POST["bairro"]);
                   $telefone = esc($_POST["telefone"]);
                   $celular = esc($_POST["celular"]);
                   $responsavel = esc($_POST["responsavel"]);
                $updateData = [];
                if (!empty($email)) {
                    $updateData["email"] = $email;
                }
                if (!empty($nome)) {
                    $updateData["nome"] = $nome;
                } elseif (!empty($empresa)) {
                    $updateData["empresa"] = $empresa;
                }
                $updateData["endereco"] = $endereco;
                $updateData["numero"] = $numero;
                $updateData["complemento"] = $complemento;
                $updateData["cep"] = $cep;
                $updateData["bairro"] = $bairro;
                $updateData["telefone"] = $telefone;
                $updateData["celular"] = $celular;
                $updateData["responsavel"] = $responsavel;
            
                // Encontrar o documento a ser atualizado
                $filter = ['_id' => $check->_id];
            
                // Atualizar o documento no MongoDB
                $updateResult = $collection->updateOne($filter, ['$set' => $updateData]);
            
                // Verificar se a atualização foi bem-sucedida
                if ($updateResult->getModifiedCount() > 0) {
                    // Atualização bem-sucedida
                    $response = array(
                        "success" => true,
                        "message" => "Dados atualizados com sucesso!"
                    );
                } else {
                    // Nenhum documento foi modificado, provavelmente não foi encontrado
                    $response = array(
                        "success" => false,
                        "message" => "Não foi possível atualizar os dados."
                    );
                }
            
                // Enviar a resposta como JSON
                header('Content-Type: application/json');
                echo json_encode($response);
                die();}

            if (isset($check->cpf)){
                $login = $check->cpf;
            }else{
                $login = $check->cnpj;
            }
    
        return view('templates/header', ['cliente' => $login]) . view('config', ['config' => $check]);
        }
        return view('errors/html/error_404');
    }

    public function finalizados()
    {
        $criar = getenv('data_check');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->cliente;
        $cnpj = session()->get('cnpj');
        $pass = session()->get('pass');
        $token = session()->get('token');
        $cpf = session()->get('cpf');
        $check = $collection->findOne([
            '$or' => [
                ['cnpj' => $cnpj, 'senha' => $pass, 'status' => 'active', 'token' => $token],
                ['cpf' => $cpf, 'senha' => $pass, 'status' => 'active', 'token' => $token]
            ]
        ]);
        if ($check) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $criar = getenv('data_cliente');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->produto;
                $query = ['cliente_id' => $check->_id, '$or' => [['status' => 'cancelado'], ['status' => 'entregue']]];
                $result = $collection->find($query);

                $html = '<table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Horario de solicitação</th>
                                    <th>Rastreio</th>
                                    <th>Destinatário</th>
                                    <th>Endereço</th>
                                    <th>Número</th>
                                    <th>Complemento</th>
                                    <th>CEP</th>
                                    <th>Bairro</th>
                                    <th>Telefone</th>
                                    <th>Observação</th>
                                    <th>Cabe Moto</th>
                                    <th>Entregador Entrega</th>
                                    <th>Documento Recebedor</th>
                                    <th>Status</th>
                                    <th>Setor</th>
                                    <th>Busca</th>
                                    <th>Entregador</th>
                                    <th>termino</th>
                                </tr>
                            </thead>
                            <tbody>';

                // Preenchimento da tabela com os dados encontrados
                foreach ($result as $doc) {
                    $html .= '<tr>';
                    $html .= '<td>' . (isset($doc->time) ? $doc->time : '') . '</td>';
                    $html .= '<td>' . (isset($doc->rastreio) ? $doc->rastreio : '') . '</td>';
                    $html .= '<td>' . (isset($doc->destinatario) ? $doc->destinatario : '') . '</td>';
                    $html .= '<td>' . (isset($doc->endereco) ? $doc->endereco : '') . '</td>';
                    $html .= '<td>' . (isset($doc->numero) ? $doc->numero : '') . '</td>';
                    $html .= '<td>' . (isset($doc->complemento) ? $doc->complemento : '') . '</td>';
                    $html .= '<td>' . (isset($doc->cep) ? $doc->cep : '') . '</td>';
                    $html .= '<td>' . (isset($doc->bairro) ? $doc->bairro : '') . '</td>';
                    $html .= '<td>' . (isset($doc->telefone) ? $doc->telefone : '') . '</td>';
                    $html .= '<td>' . (isset($doc->observacao) ? $doc->observacao : '') . '</td>';
                    $html .= '<td>' . (isset($doc->cabe_moto) ? $doc->cabe_moto : '') . '</td>';
                    $html .= '<td>' . (isset($doc->entregador_entrega) ? $doc->entregador_entrega : '') . '</td>';
                    $html .= '<td>' . (isset($doc->documento_recebedor) ? $doc->documento_recebedor : '') . '</td>';
                    $html .= '<td>' . (isset($doc->status) ? $doc->status : '') . '</td>';
                    $html .= '<td>' . (isset($doc->setor) ? $doc->setor : '') . '</td>';
                    $html .= '<td>' . (isset($doc->busca) ? $doc->busca : '') . '</td>';
                    $html .= '<td>' . (isset($doc->entregador) ? $doc->entregador : '') . '</td>';
                    $html .= '<td>' . (isset($doc->datea1) ? $doc->datea1 : '') . '</td>';
                    $html .= '</tr>';
                }

                $html .= '</tbody></table>';
                echo $html;
                die();
            }
        }
        
    }

    public function massa()
    {
        $criar = getenv('data_check');
        $host = getenv('host_db');
        $ambiente = getenv('ambiente');
        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        $collection = $client->$ambiente->cliente;
        $cnpj = session()->get('cnpj');
        $pass = session()->get('pass');
        $token = session()->get('token');
        $cpf = session()->get('cpf');
        $check = $collection->findOne([
            '$or' => [
                ['cnpj' => $cnpj, 'senha' => $pass, 'status' => 'active', 'token' => $token],
                ['cpf' => $cpf, 'senha' => $pass, 'status' => 'active', 'token' => $token]
            ]
        ]);
        if ($check) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verifica se o arquivo foi enviado corretamente
            if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
                // Move o arquivo temporário para o local desejado
                $caminhoArquivo = $_FILES['arquivo']['tmp_name'];
            
                // Verifica a extensão do arquivo
                $extensao = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);
                function gerarCodigoRastreio($length = 10)
                {
                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $codigo = '';
                    $maxIndex = strlen($characters) - 1;

                    for ($i = 0; $i < $length; $i++) {
                        $codigo .= $characters[rand(0, $maxIndex)];
                    }
                    $horaAtual = date('H:i'); // Obtém a hora atual no formato HH:MM
                    if ($horaAtual >= '00:01' && $horaAtual <= '11:59') {
                        $codigo = 'RT' . $codigo . 'DD';
                    } elseif ($horaAtual >= '12:00' && $horaAtual <= '18:00') {
                        $codigo = 'RT' . $codigo . 'TT';
                    } else {
                        $codigo = 'RT' . $codigo . 'NN';
                    }

                    return $codigo;
                }
                if ($extensao === 'xlsx') {
            
        
                // Carrega a planilha
                $planilha = IOFactory::load($caminhoArquivo);
                $aba = $planilha->getActiveSheet();
        
                // Obtém os dados a partir da segunda linha
                $dados = [];
                $linhaInicial = 2;
                $ultimaLinha = $aba->getHighestRow();
                $ultimaColuna = $aba->getHighestColumn();
                $colunas = range('A', $ultimaColuna);
        
                for ($linha = $linhaInicial; $linha <= $ultimaLinha; $linha++) {
                    $linhaDados = [];
                    foreach ($colunas as $coluna) {
                        $celula = $coluna . $linha;
                        $valorCelula = $aba->getCell($celula)->getValue();
                        $linhaDados[] = $valorCelula;
                    }
                    $dados[] = $linhaDados;
                }
        
                // Adicione os dados no MongoDB
                $criar = getenv('data_cliente');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->produto;
                $id = $check->_id;
                $linha = 1;

                foreach ($dados as $linhaDados) {
                    $linha = $linha + 1;
                    $rastreio = gerarCodigoRastreio();

                    // Verificar se o código de rastreamento já existe no collection
                    while ($collection->count(['rastreio' => $rastreio]) > 0) {
                        // Se o código de rastreamento já existe, gerar um novo
                        $rastreio = gerarCodigoRastreio();
                    }
                  
                    // Verifica se todos os campos são nulos
                    if (array_filter($linhaDados)) {
                        // Converte o telefone para inteiro
                        $telefone = ($linhaDados[6] !== null) ? (int) $linhaDados[6] : null;
                        if($linhaDados[8] == "sim"){
                            $moto = "sim";
                        }elseif($linhaDados[8] == "não"){
                            $moto = "não";
                        }else{
                            $moto = "não";
                        }
                        if (strlen($linhaDados[4]) > 8) {
                            echo "cep incorreto na linha ". $linha;
                            die();
                        } elseif ( preg_match('/[a-zA-Z]/', $linhaDados[4])){
                            echo "cep incorreto na linha ". $linha;
                        }
                        $id = $check->_id;
                    $inserir = getenv('data_base');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new MongoDB\Client('mongodb://' . $inserir . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->setor;
                    $findsetor = $collection->findOne(['cep' => intval($linhaDados[4])]);
                    if (empty($findsetor)){
                        $setorEntrega = "Expansão";
                    }else{
                        $setorEntrega = $findsetor->setor;
                    }

                        $documento = [
                            'destinatario' => esc($linhaDados[0]),
                            'endereco' => esc($linhaDados[1]),
                            'numero' => esc($linhaDados[2]),
                            'complemento' => esc($linhaDados[3]),
                            'cep' => esc($linhaDados[4]),
                            'bairro' => esc($linhaDados[5]),
                            'telefone' => esc(strval($telefone)),
                            'observacao' => esc($linhaDados[7]),
                            'cabe_moto' => $moto,
                            'time' => date('Y-m-d H:i:s'),
                            'rastreio' => $rastreio,
                            'andamento' => 'on',
                            'entregador_entrega' => null,
                            'documento_recebedor' => null,
                            'status' => 'Aguardando confirmação',
                            'setor' => $check->setor,
                            'busca' => $check->endereco . "," . $check->numero,
                            'cliente_id' => $id, // Supondo que o ID do cliente seja armazenado em _id
                            'busca' => $check->endereco . "," . $check->numero,
                            'setor_entrega'=>$setorEntrega,
                            'status_rastreio' => '<li><b>Solicitado</b>: ' . date('H:i:s d/m/Y') . '</li>',
                        ];
                        $criar = getenv('data_cliente');
                        $host = getenv('host_db');
                        $ambiente = getenv('ambiente');
                        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                        $collection = $client->$ambiente->produto;
        
                        $collection->insertOne($documento);
                    }
                }
        
                echo "Dados adicionados com sucesso!";
                die();
            }else if($extensao === 'txt'){
                $criar = getenv('data_cliente');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->produto;
                $id = $check->_id;
                $linhas = file($caminhoArquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

                // Adicione os dados no MongoDB
                $criar = getenv('data_cliente');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->produto;
                $id = $check->_id;
    
                foreach ($linhas as $linha) {
                    // Divide a linha em campos usando um delimitador (por exemplo, vírgula)
                    $campos = explode(',', $linha);
    
                    // Verifica se todos os campos são preenchidos
                    if (count($campos) === 9 && array_filter($campos)) {
                        $rastreio = gerarCodigoRastreio();
    
                        // Verificar se o código de rastreamento já existe no collection
                        while ($collection->count(['rastreio' => $rastreio]) > 0) {
                            // Se o código de rastreamento já existe, gerar um novo
                            $rastreio = gerarCodigoRastreio();
                        }
                           
                        $telefone = ($campos[6] !== '') ? (int) $campos[6] : null;
                        if ($campos[8] == "sim") {
                            $moto = "sim";
                        } elseif ($campos[8] == "não") {
                            $moto = "não";
                        } else {
                            $moto = "não";
                        }
                        $id = $check->_id;
                    $inserir = getenv('data_base');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new MongoDB\Client('mongodb://' . $inserir . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->setor;
                    $findsetor = $collection->findOne(['cep' => intval(trim($campos[4]))]);
                    if (empty($findsetor)){
                        $setorEntrega = "Expansão";
                    }else{
                        $setorEntrega = $findsetor->setor;
                    }   
                        $documento = [
                            'destinatario' => esc(trim($campos[0])),
                            'endereco' => esc(trim($campos[1])),
                            'numero' => esc(trim($campos[2])),
                            'complemento' => esc(trim($campos[3])),
                            'cep' => esc(strval(trim($campos[4]))),
                            'bairro' => esc(trim($campos[5])),
                            'telefone' => esc(strval($telefone)),
                            'observacao' => esc(trim($campos[7])),
                            'cabe_moto' => $moto,
                            'time' => date('Y-m-d H:i:s'),
                            'rastreio' => $rastreio,
                            'andamento' => 'on',
                            'entregador_entrega' => null,
                            'documento_recebedor' => null,
                            'status' => 'Aguardando confirmação',
                            'setor' => $check->setor,
                            'busca' => $check->endereco . "," . $check->numero,
                            'cliente_id' => $id ,// Supondo que o ID do cliente seja armazenado em _id
                            'busca' => $check->endereco . "," . $check->numero,
                            'setor_entrega' => $setorEntrega,

                        ];
                        $criar = getenv('data_cliente');
                        $host = getenv('host_db');
                        $ambiente = getenv('ambiente');
                        $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                        $collection = $client->$ambiente->produto;
                        $collection->insertOne($documento);
                    }
                }
    
                echo "Dados adicionados com sucesso!";
                die();
      
                    }
                }
            }
        }
    }
}

