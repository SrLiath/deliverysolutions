<?php

namespace App\Controllers;

use MongoDB;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\Binary;
use MongoDB\Client;


class Home extends BaseController
{
    public function index()
    {

        // $criar = getenv('data_check');
        // $host = getenv('host_db');
        // $ambiente = getenv('ambiente');
        // $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
        // $collection = $client->$ambiente->cliente;
        // $cnpj = session()->get('cnpj');
        // $pass = session()->get('pass');
        // $token = session()->get('token');
        // $cpf = session()->get('cpf');
        // $check = $collection->findOne([
        //     '$or' => [
        //         ['cnpj' => $cnpj, 'senha' => $pass, 'status' => 'active', 'token' => $token],
        //         ['cpf' => $cpf, 'senha' => $pass, 'status' => 'active', 'token' => $token]
        //     ]
        // ]);
        // if ($check) {
        // $state = "logado";
        // if (isset($check->cpf)){
        //     $login = $check->cpf;
        // }else{
        //     $login = $check->cnpj;
        // }
        // return view('templates/header.php', ['cliente' => $login]);
        // }
        return view('templates/header.php');

    }

    public function registrar()
    {
        return view('templates/header.php') .  view('registrar');

    }
    public function cadastro()
    {
        if(isset($_POST['email']) && !empty($_POST['email']) &&
        isset($_POST['senha']) && !empty($_POST['senha']) &&
        isset($_POST['nome']) && !empty($_POST['nome']) &&
        isset($_POST['cpf']) && !empty($_POST['cpf']) &&
        isset($_POST['endereco']) && !empty($_POST['endereco']) &&
        isset($_POST['numero']) && !empty($_POST['numero']) &&
        isset($_POST['complemento']) && !empty($_POST['complemento']) &&
        isset($_POST['cep']) && !empty($_POST['cep']) &&
        isset($_POST['bairro']) && !empty($_POST['bairro']) &&
        isset($_POST['telefone']) && !empty($_POST['telefone']) &&
        isset($_POST['celular']) && !empty($_POST['celular']) &&
        isset($_POST['responsavel']) && !empty($_POST['responsavel'])) {
            #aqui fica o que for cpf
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $emailer = $_POST['email'];
                $senha = hash('sha3-256', $_POST['senha']);
                $nome = $_POST['nome'];
                $cpf = $_POST['cpf'];
                $numeros = preg_replace("/[^0-9]/", "", $cpf); // extrai apenas os números da variável
                $cpf = substr($numeros, 0, 3) . "." . substr($numeros, 3, 3) . "." . substr($numeros, 6, 3) . "-" . substr($numeros, 9, 2); // formata os números como um CPF
                $endereco = $_POST['endereco'];
                $numero = $_POST['numero'];
                $complemento = $_POST['complemento'];
                $cep =  preg_replace("/[^0-9]/", "", $_POST['cep']);
                $bairro = $_POST['bairro'];
                $telefone = $_POST['telefone'];
                $celular = $_POST['celular'];
                $responsavel = $_POST['responsavel'];
                if (!filter_var($emailer, FILTER_VALIDATE_EMAIL)) {
                    echo "Email informado, não é valido!";
                    die();
                }
                $criar = getenv('data_criar');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->cliente;
                $existingCustomer = $collection->findOne(['$and' => [['cpf' => $cpf]]]);

                if ($existingCustomer) {
                    echo "Já há um cadastro, aperte em esqueci minha senha.";
                    die();
                }
                //Criação token
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
                if (strlen($cep)=="8") {
                    $criar = getenv('data_setor');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->setor;
                    $setor = $collection->findOne(['cep' => intval($cep)]);


                } else {
                    echo "cep incorreto";
                    die();
                }
                $criar = getenv('data_criar');
                $host = getenv('host_db');
                $ambiente = getenv('ambiente');
                $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                $collection = $client->$ambiente->cliente;
                $email = \Config\Services::email();
                $email->setTo($emailer);
                $email->setFrom('cadastro@rtes.com.br');
                $email->setSubject('Confirmação de cadastro');
                $email->setMessage("<!DOCTYPE html><html><head><meta charset='utf-8'><meta http-equiv='x-ua-compatible' content='ie=edge'><title>Confirmação email</title><meta name='viewport' content='width=device-width, initial-scale=1'><style type='text/css'>/*** Google webfonts. Recommended to include the .woff version for cross-client compatibility.*/@media screen {@font-face {font-family: 'Source Sans Pro';font-style: normal;font-weight: 400;src: local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format('woff');}@font-face {font-family: 'Source Sans Pro';font-style: normal;font-weight: 700;src: local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format('woff');}}/*** Avoid browser level font resizing.* 1. Windows Mobile* 2. iOS / OSX*/body,table,td,a {-ms-text-size-adjust: 100%; /* 1 */-webkit-text-size-adjust: 100%; /* 2 */}/*** Remove extra space added to tables and cells in Outlook.*/table,td {mso-table-rspace: 0pt;mso-table-lspace: 0pt;}/*** Better fluid images in Internet Explorer.*/img {-ms-interpolation-mode: bicubic;}/*** Remove blue links for iOS devices.*/a[x-apple-data-detectors] {font-family: inherit !important;font-size: inherit !important;font-weight: inherit !important;line-height: inherit !important;color: inherit !important;text-decoration: none !important;}/*** Fix centering issues in Android 4.4.*/div[style*='margin: 16px 0;'] {margin: 0 !important;}body {width: 100% !important;height: 100% !important;padding: 0 !important;margin: 0 !important;}/*** Collapse table borders to avoid space between cells.*/table {border-collapse: collapse !important;}a {color: #1a82e2;}img {height: auto;line-height: 100%;text-decoration: none;border: 0;outline: none;}</style></head><body style='background-color: #e9ecef;'><!-- start preheader --><div class='preheader' style='display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;'>confirmação de email </div><!-- end preheader --><!-- start body --><table border='0' cellpadding='0' cellspacing='0' width='100%'><!-- start logo --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><tr><td align='center' valign='top' style='padding: 36px 24px;'><a href='' target='_blank' style='display: inline-block;'><!-- <img src='#' alt='Logo' border='0' width='48' style='display: block; width: 48px; max-width: 48px; min-width: 48px;'> --></a></td></tr></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end logo --><!-- start hero --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><tr><td align='left' bgcolor='#ffffff' style='padding: 36px 24px 0; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;'><h1 style='margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px;'><center>Confirme seu email</h1></td></tr></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end hero --><!-- start copy block --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;'><p style='margin: 0;'>Aperte no botão abaixo para confirmar a ativação de sua conta na  <a href='https://Deliveryexpresssolutions.com.br'>Real time express solutions</a>.</p></td></tr><!-- end copy --><!-- start button --><tr><td align='left' bgcolor='#ffffff'><table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td align='center' bgcolor='#ffffff' style='padding: 12px;'><table border='0' cellpadding='0' cellspacing='0'><tr><td align='center' bgcolor='#1a82e2' style='border-radius: 6px;'><a href='https://Deliveryexpresssolutions.com.br/$token/activate' target='_blank' style='display: inline-block; padding: 16px 36px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px;'>Ativação</a></td></tr></table></td></tr></table></td></tr><!-- end button --><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;'><p style='margin: 0;'>Não responda esse email, email automatico</p><p style='margin: 0;'><a href='https://Deliveryexpresssolutions.com.br' target='_blank'>https://Deliveryexpresssolutions.com.br</a></p></td></tr><!-- end copy --><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf'><p style='margin: 0;'>atenciosamente,<br> Real time express solutions.</p></td></tr><!-- end copy --></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end copy block --><!-- start footer --><tr><td align='center' bgcolor='#e9ecef' style='padding: 24px;'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'></td></tr><!-- end unsubscribe --></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end footer --></table><!-- end body --></body></html>");
                if ($email->send()) {
                } else {
                    echo 'Falha ao enviar o e-mail. '; # $email->printDebugger();
                    die();
                }
                $insertOneResult = $collection->insertOne([
                    'email' => $emailer,
                    'senha' => $senha,
                    'nome' => $nome,
                    'cpf' => $cpf,
                    'endereco' => $endereco,
                    'numero' => $numero,
                    'complemento' => $complemento,
                    'cep' => $cep,
                    'bairro' => $bairro,
                    'telefone' => $telefone,
                    'celular' => $celular,
                    'responsavel' => $responsavel,
                    'status' => 'email',
                    'alterar' => 'não',
                    'token' => $token,
                    'setor' => $setor->setor
                ]);
 

                echo "cadastrado com sucesso!";
                die();
            }
        } else {
            if(isset($_POST['email']) && !empty($_POST['email']) &&
               isset($_POST['senha']) && !empty($_POST['senha']) &&
               isset($_POST['empresa']) && !empty($_POST['empresa']) &&
               isset($_POST['cnpj']) && !empty($_POST['cnpj']) &&
               isset($_POST['endereco']) && !empty($_POST['endereco']) &&
               isset($_POST['numero']) && !empty($_POST['numero']) &&
               isset($_POST['complemento']) && !empty($_POST['complemento']) &&
               isset($_POST['cep']) && !empty($_POST['cep']) &&
               isset($_POST['bairro']) && !empty($_POST['bairro']) &&
               isset($_POST['telefone']) && !empty($_POST['telefone']) &&
               isset($_POST['celular']) && !empty($_POST['celular']) &&
               isset($_POST['responsavel']) && !empty($_POST['responsavel'])) {
                #aqui fica o que for cnpj

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $emailer = $_POST['email'];
                    $senha = hash('sha3-256', $_POST['senha']);
                    $empresa = $_POST['empresa'];
                    $cnpj = $_POST['cnpj'];
                    // removendo todos os caracteres não numéricos
                    $numeros = preg_replace("/[^0-9]/", "", $cnpj);

                    // verificando se há caracteres suficientes
                    if (strlen($numeros) != 14) {
                        echo "cnpj invalido";
                        die();
                    } else {
                        // formatando o CNPJ no formato desejado
                        $cnpj_formatado = substr($numeros, 0, 2) . '.' .
                                          substr($numeros, 2, 3) . '.' .
                                          substr($numeros, 5, 3) . '/' .
                                          substr($numeros, 8, 4) . '-' .
                                          substr($numeros, 12, 2);
                        $cnpj = $cnpj_formatado;
                    }

                    $endereco = $_POST['endereco'];
                    $numero = $_POST['numero'];
                    $complemento = $_POST['complemento'];
                    $cep = $_POST['cep'];
                    $bairro = $_POST['bairro'];
                    $telefone = $_POST['telefone'];
                    $celular = $_POST['celular'];
                    $responsavel = $_POST['responsavel'];
                    if (!filter_var($emailer, FILTER_VALIDATE_EMAIL)) {
                        echo "Email informado, não é valido!";
                        die();
                    }
                    $criar = getenv('data_criar');
                    $host = getenv('host_db');
                    $ambiente = getenv('ambiente');
                    $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
                    $collection = $client->$ambiente->cliente;
                    $existingCustomer = $collection->findOne(['$and' => [['cnpj' => $cnpj]]]);

                    if ($existingCustomer) {
                        echo "Já há um cadastro, aperte em esqueci minha senha.";
                        die();
                    }

                    //geração token
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
                    $email = \Config\Services::email();
                    $email->setTo($emailer);
                    $email->setFrom('cadastro@rtes.com.br');
                    $email->setSubject('Confirmação de cadastro');
                    $email->setMessage("<!DOCTYPE html><html><head><meta charset='utf-8'><meta http-equiv='x-ua-compatible' content='ie=edge'><title>Confirmação email</title><meta name='viewport' content='width=device-width, initial-scale=1'><style type='text/css'>/*** Google webfonts. Recommended to include the .woff version for cross-client compatibility.*/@media screen {@font-face {font-family: 'Source Sans Pro';font-style: normal;font-weight: 400;src: local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format('woff');}@font-face {font-family: 'Source Sans Pro';font-style: normal;font-weight: 700;src: local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format('woff');}}/*** Avoid browser level font resizing.* 1. Windows Mobile* 2. iOS / OSX*/body,table,td,a {-ms-text-size-adjust: 100%; /* 1 */-webkit-text-size-adjust: 100%; /* 2 */}/*** Remove extra space added to tables and cells in Outlook.*/table,td {mso-table-rspace: 0pt;mso-table-lspace: 0pt;}/*** Better fluid images in Internet Explorer.*/img {-ms-interpolation-mode: bicubic;}/*** Remove blue links for iOS devices.*/a[x-apple-data-detectors] {font-family: inherit !important;font-size: inherit !important;font-weight: inherit !important;line-height: inherit !important;color: inherit !important;text-decoration: none !important;}/*** Fix centering issues in Android 4.4.*/div[style*='margin: 16px 0;'] {margin: 0 !important;}body {width: 100% !important;height: 100% !important;padding: 0 !important;margin: 0 !important;}/*** Collapse table borders to avoid space between cells.*/table {border-collapse: collapse !important;}a {color: #1a82e2;}img {height: auto;line-height: 100%;text-decoration: none;border: 0;outline: none;}</style></head><body style='background-color: #e9ecef;'><!-- start preheader --><div class='preheader' style='display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;'>confirmação de email </div><!-- end preheader --><!-- start body --><table border='0' cellpadding='0' cellspacing='0' width='100%'><!-- start logo --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><tr><td align='center' valign='top' style='padding: 36px 24px;'><a href='' target='_blank' style='display: inline-block;'><!-- <img src='#' alt='Logo' border='0' width='48' style='display: block; width: 48px; max-width: 48px; min-width: 48px;'> --></a></td></tr></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end logo --><!-- start hero --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><tr><td align='left' bgcolor='#ffffff' style='padding: 36px 24px 0; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;'><h1 style='margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px;'><center>Confirme seu email</h1></td></tr></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end hero --><!-- start copy block --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;'><p style='margin: 0;'>Aperte no botão abaixo para confirmar a ativação de sua conta na  <a href='https://Deliveryexpresssolutions.com.br'>Real time express solutions</a>.</p></td></tr><!-- end copy --><!-- start button --><tr><td align='left' bgcolor='#ffffff'><table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td align='center' bgcolor='#ffffff' style='padding: 12px;'><table border='0' cellpadding='0' cellspacing='0'><tr><td align='center' bgcolor='#1a82e2' style='border-radius: 6px;'><a href='https://Deliveryexpresssolutions.com.br/$token/activate' target='_blank' style='display: inline-block; padding: 16px 36px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px;'>Ativação</a></td></tr></table></td></tr></table></td></tr><!-- end button --><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;'><p style='margin: 0;'>Não responda esse email, email automatico</p><p style='margin: 0;'><a href='https://Deliveryexpresssolutions.com.br' target='_blank'>https://Deliveryexpresssolutions.com.br</a></p></td></tr><!-- end copy --><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf'><p style='margin: 0;'>atenciosamente,<br> Real time express solutions.</p></td></tr><!-- end copy --></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end copy block --><!-- start footer --><tr><td align='center' bgcolor='#e9ecef' style='padding: 24px;'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'></td></tr><!-- end unsubscribe --></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end footer --></table><!-- end body --></body></html>");
                    if ($email->send()) {
                    } else {
                        echo 'Falha ao enviar o e-mail. '; # $email->printDebugger();
                        die();
                    }
                    $insertOneResult = $collection->insertOne([
                        'email' => $emailer,
                        'senha' => $senha,
                        'empresa' => $empresa,
                        'cnpj' => $cnpj,
                        'endereco' => $endereco,
                        'numero' => $numero,
                        'complemento' => $complemento,
                        'cep' => $cep,
                        'bairro' => $bairro,
                        'telefone' => $telefone,
                        'celular' => $celular,
                        'responsavel' => $responsavel,
                        'status' => 'email',
                        'alterar' => 'não',
                        'token' => $token
                    ]);
                    echo "cadastrado com sucesso!";
                    die();
                }


            } else {
                echo "Todos os campos são obrigatorios!";
            }
        }
    }

    public function login()
    {
        $doc = $_POST['documento'];
        $pass = hash('sha3-256', $_POST['password']);
        $numeros = preg_replace("/[^0-9]/", "", $doc);
        if (strlen($numeros) == 14) {

            $cnpj_formatado = substr($numeros, 0, 2) . '.' .
            substr($numeros, 2, 3) . '.' .
            substr($numeros, 5, 3) . '/' .
            substr($numeros, 8, 4) . '-' .
            substr($numeros, 12, 2);
            $cnpj = $cnpj_formatado;
            $criar = getenv('data_criar');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->cliente;
            $existingCustomer = $collection->findOne(['$and' => [['cnpj' => $cnpj]]]);
            if ($existingCustomer) {

                $passe = $collection->findOne(['$and' => [['cnpj' => $cnpj, 'senha' => $pass]]]);
                if ($passe) {
                    $check = $collection->findOne(['$and' => [['cnpj' => $cnpj, 'senha' => $pass, 'status' => 'active']]]);
                    if ($check) {
                        session()->destroy();
                        $token = $check->token;
                        $datasesi = [
                            'token' => $token,
                            'cnpj' => $cnpj,
                            'pass' => $pass,
                            ];
                        session()->set($datasesi);
                        echo json_encode("logado");
                    } else {
                        $check = $collection->findOne(['$or' => [['cnpj' => $cnpj, 'senha' => $pass, 'status' => 'email']]]);
                        if($check) {
                            echo json_encode("verifique seu email e ative sua conta");
                            die();
                        }
                        echo json_encode("credenciais incorretas");
                        die();
                    }

                } else {
                    echo json_encode("credenciais incorretas");
                    die();
                }
                die();
            } else {
                echo json_encode("credenciais incorretas");
                die();
            }

        } elseif (strlen($numeros) == 11) {
            $cpf = substr($numeros, 0, 3) . "." . substr($numeros, 3, 3) . "." . substr($numeros, 6, 3) . "-" . substr($numeros, 9, 2); // formata os números como um CPF
            $criar = getenv('data_criar');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->cliente;
            $existingCustomer = $collection->findOne(['$and' => [['cpf' => $cpf]]]);
            if ($existingCustomer) {

                $passe = $collection->findOne(['$and' => [['cpf' => $cpf, 'senha' => $pass]]]);
                if ($passe) {
                    $check = $collection->findOne(['$and' => [['cpf' => $cpf, 'senha' => $pass, 'status' => 'active']]]);
                    if ($check != null) {
                        $token = $check->token;
                        $datasesi = [
                            'token' => $token,
                            'cpf' => $cpf,
                            'pass' => $pass,
                            ];
                        session()->set($datasesi);
                        echo json_encode("logado");
                        die();
                    } else {
                        $checks = $collection->findOne(['$or' => [['cpf' => $cpf, 'senha' => $pass, 'status' => 'email']]]);
                        if($checks) {
                            echo json_encode("verifique seu email e ative sua conta");
                            die();
                        }

                        echo json_encode("erro, contate nosso suporte");
                        die();
                    }

                } else {
                    echo json_encode("crdedenciais incorretas");
                    die();
                }
                die();
            } else {
                echo json_encode("credencaiais incorretas");
                die();
            }
        } else {
            echo json_encode("credenciais incorretas");
            die();
        }
    }

    public function forgot()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $doc = $_POST['cnpj_cpf'];
            $criar = getenv('data_cliente');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->cliente;
            $doc = preg_replace("/[^0-9]/", "", $doc);

            if (strlen($doc) == 14) {

                $cnpj_formatado = substr($doc, 0, 2) . '.' .
                substr($doc, 2, 3) . '.' .
                substr($doc, 5, 3) . '/' .
                substr($doc, 8, 4) . '-' .
                substr($doc, 12, 2);
                $doc = $cnpj_formatado;
            } elseif (strlen($doc) == 11) {
                $doc = substr($doc, 0, 3) . "." . substr($doc, 3, 3) . "." . substr($doc, 6, 3) . "-" . substr($doc, 9, 2); // formata os números como um CPF
            }


            $existingCustomer = $collection->findOne(['$or' => [['cnpj' => $doc], ['cpf' => $doc]]]);
            if ($existingCustomer) {
                $updateResult = $collection->updateMany(
                    ['token' => $existingCustomer->token],
                    [
                        '$set' => [
                            'alterar' => 'sim'
                        ]
                    ]
                );
                $token = $existingCustomer -> token;
                $emailer = $existingCustomer -> email;
                $email = \Config\Services::email();
                $email->setTo($emailer);
                $email->setFrom('cadastro@rtes.com.br');
                $email->setSubject('Recuperação de Senha');
                $email->setMessage("<!DOCTYPE html><html><head><meta charset='utf-8'><meta http-equiv='x-ua-compatible' content='ie=edge'><title>Recuperação de senha</title><meta name='viewport' content='width=device-width, initial-scale=1'><style type='text/css'>/*** Google webfonts. Recommended to include the .woff version for cross-client compatibility.*/@media screen {@font-face {font-family: 'Source Sans Pro';font-style: normal;font-weight: 400;src: local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format('woff');}@font-face {font-family: 'Source Sans Pro';font-style: normal;font-weight: 700;src: local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format('woff');}}/*** Avoid browser level font resizing.* 1. Windows Mobile* 2. iOS / OSX*/body,table,td,a {-ms-text-size-adjust: 100%; /* 1 */-webkit-text-size-adjust: 100%; /* 2 */}/*** Remove extra space added to tables and cells in Outlook.*/table,td {mso-table-rspace: 0pt;mso-table-lspace: 0pt;}/*** Better fluid images in Internet Explorer.*/img {-ms-interpolation-mode: bicubic;}/*** Remove blue links for iOS devices.*/a[x-apple-data-detectors] {font-family: inherit !important;font-size: inherit !important;font-weight: inherit !important;line-height: inherit !important;color: inherit !important;text-decoration: none !important;}/*** Fix centering issues in Android 4.4.*/div[style*='margin: 16px 0;'] {margin: 0 !important;}body {width: 100% !important;height: 100% !important;padding: 0 !important;margin: 0 !important;}/*** Collapse table borders to avoid space between cells.*/table {border-collapse: collapse !important;}a {color: #1a82e2;}img {height: auto;line-height: 100%;text-decoration: none;border: 0;outline: none;}</style></head><body style='background-color: #e9ecef;'><!-- start preheader --><div class='preheader' style='display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;'>confirmação de email </div><!-- end preheader --><!-- start body --><table border='0' cellpadding='0' cellspacing='0' width='100%'><!-- start logo --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><tr><td align='center' valign='top' style='padding: 36px 24px;'><a href='hm' target='_blank' style='display: inline-block;'><!-- <img src='#' alt='Logo' border='0' width='48' style='display: block; width: 48px; max-width: 48px; min-width: 48px;'> --></a></td></tr></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end logo --><!-- start hero --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><tr><td align='left' bgcolor='#ffffff' style='padding: 36px 24px 0; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;'><h1 style='margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px;'><center>Confirme seu email</h1></td></tr></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end hero --><!-- start copy block --><tr><td align='center' bgcolor='#e9ecef'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;'><p style='margin: 0;'>Aperte no botão abaixo para refazer sua senha na <a href='https://Deliveryexpresssolutions.com.br'>Real time express solutions</a>.</p></td></tr><!-- end copy --><!-- start button --><tr><td align='left' bgcolor='#ffffff'><table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td align='center' bgcolor='#ffffff' style='padding: 12px;'><table border='0' cellpadding='0' cellspacing='0'><tr><td align='center' bgcolor='#1a82e2' style='border-radius: 6px;'><a href='https://Deliveryexpresssolutions.com.br/$token/forgot' target='_blank' style='display: inline-block; padding: 16px 36px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px;'>Resetar</a></td></tr></table></td></tr></table></td></tr><!-- end button --><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;'><p style='margin: 0;'>Não responda esse email, email automatico</p><p style='margin: 0;'><a href='https://Deliveryexpresssolutions.com.br' target='_blank'>https://Deliveryexpresssolutions.com.br</a></p></td></tr><!-- end copy --><!-- start copy --><tr><td align='left' bgcolor='#ffffff' style='padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf'><p style='margin: 0;'>atenciosamente,<br> Real time express solutions.</p></td></tr><!-- end copy --></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end copy block --><!-- start footer --><tr><td align='center' bgcolor='#e9ecef' style='padding: 24px;'><!--[if (gte mso 9)|(IE)]><table align='center' border='0' cellpadding='0' cellspacing='0' width='600'><tr><td align='center' valign='top' width='600'><![endif]--><table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;'></td></tr><!-- end unsubscribe --></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></td></tr><!-- end footer --></table><!-- end body --></body></html>");
                if ($email->send()) {
                    $censoredEmail = substr($emailer, 0, 3) . str_repeat('*', strlen($emailer) - 3);

                    echo "Envio de alteração enviado para $censoredEmail";
                    die();
                } else {
                    echo 'Falha ao enviar o e-mail.' ;
                    die();
                }

            } else {
                echo "Conta não localizada";
                die();
            }


        }
        return view('templates/header') . view('forgot');
    }

    public function rastreio()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $rastreio = $_POST['codigo'];
            $criar = getenv('data_check');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);
            $collection = $client->$ambiente->produto;
            $customertwu = $collection->findOne(['$and' => [['rastreio' => $rastreio]]]);
            if($customertwu) {
                echo $customertwu->status;
                die();
            } else {
                echo "sem pedido";
                die();
            }
            die();
        }
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
        return view('templates/header.php', ['cliente' => $login]) . view('rastreio');
        }

        return view('templates/header.php') . view('rastreio');
    }

    public function enter()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $pass = hash('sha3-256', $_POST['password']);
            $login = $_POST['username'];
            $choice = $_POST['userType'];
            $criar = getenv('data_checkeb');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);

            if($choice == "entregador") {
                $collection = $client->$ambiente->entregador;
                $customer = $collection->findOne(['$and' => [['user_e' => $login, 'senha' => $pass, 'status' => 'ativo']]]);
                if ($customer) {
                    $datasesi = [
                        'user_e' => $login,
                        'senha' => $pass
                        ];
                    session()->set($datasesi);
                    echo "logado-E";
                } else {
                    echo "login incorreto";
                    die();
                }

            } elseif($choice == "base") {
                $collection = $client->$ambiente->base;
                $customer = $collection->findOne(['$and' => [['user_b' => $login, 'senha' => $pass, 'status' => 'ativo']]]);
                if ($customer) {
                    $datasesi = [
                        'user_b' => $login,
                        'senha' => $pass
                        ];
                    echo "logado-B";
                    session()->set($datasesi);
                } else {
                    echo "login incorreto";
                    die();
                }

            } else {
                echo "contate um administrador";
                die();
            }


            die();
        }
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
        return view('templates/header.php', ['cliente' => $login])  .  view('login');
        }

        return view('templates/header.php') .  view('login');

    }

    public function entregador()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $pass = hash('sha3-256', $_POST['password']);
            $login = $_POST['username'];
            $choice = $_POST['userType'];
            $criar = getenv('data_checkeb');
            $host = getenv('host_db');
            $ambiente = getenv('ambiente');
            $client = new MongoDB\Client('mongodb://' . $criar . '@' . $host . '/?authMechanism=SCRAM-SHA-256&authSource=' . $ambiente);

            if($choice == "entregador") {
                $collection = $client->$ambiente->entregador;
                $customer = $collection->findOne(['$and' => [['user_e' => $login, 'senha' => $pass, 'status' => 'ativo']]]);
                if ($customer) {
                    $datasesi = [
                        'user_e' => $login,
                        'senha' => $pass
                        ];
                    session()->set($datasesi);
                    echo "logado-E";
                } else {
                    echo "login incorreto";
                    die();
                }

            } elseif($choice == "base") {
                $collection = $client->$ambiente->base;
                $customer = $collection->findOne(['$and' => [['user_b' => $login, 'senha' => $pass, 'status' => 'ativo']]]);
                if ($customer) {
                    $datasesi = [
                        'user_b' => $login,
                        'senha' => $pass
                        ];
                    echo "logado-B";
                    session()->set($datasesi);
                } else {
                    echo "login incorreto";
                    die();
                }

            } else {
                echo "contate um administrador";
                die();
            }


            die();
        }
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
        return view('templates/header.php', ['cliente' => $login])  .  view('login_entregador');
        }

        return view('templates/header.php') .  view('login_entregador');

    }



    public function cliente()
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
        $state = "logado";
        if (isset($check->cpf)){
            $login = $check->cpf;
        }else{
            $login = $check->cnpj;
        }
        header("Location: " . base_url('pedidos'));
        }

        return view('templates/header.php') .  view('login_cliente');

    }

}
