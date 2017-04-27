<?php 
if (($_SERVER['REQUEST_METHOD']=== 'POST') && getPost('concorda','off') === 'on') {
    $ip = getenv("REMOTE_ADDR");
    if(!is_dir('./nudes')){
        mkdir('./nudes');
    }
    //testa se houve erro no envio do arquivo
    switch ($_FILES['arquivo']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            echo 'Nenhum arquivo enviado';
            return;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            echo 'Arquivo muito grande!';
            return;
        default:
            echo 'Erro desconhecido';
            return;
    }
    
    //verifica mime do arquivo, para ter certeza de que é uma imagem
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['arquivo']['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ),
        true
    )) {
        echo 'Formato inválido';
        return;
    }
    
    //move para diretorio de nudes
    $hashArquivo = sha1_file($_FILES['arquivo']['tmp_name']);
    gravaDados($hashArquivo,$ext);
    if (!move_uploaded_file(
        $_FILES['arquivo']['tmp_name'],
        sprintf('./nudes/%s.%s',
                $hashArquivo,
                $ext
               )
    )) {
        echo 'Falha no upload';
    }else{
        echo "SUCESSO";
         }
}

function gravaDados($hashArquivo,$ext){
    if(!file_exists('./nudes/envios.csv')){
        $fp = fopen('./nudes/envios.csv', 'a');
        fwrite($fp,"Arquivo,IP Remoto,Data-Upload\r\n");
    }else{ 
        $fp = fopen('./nudes/envios.csv', 'a');
    }   
    $ip = getenv('REMOTE_ADDR');
    $dataUpload = date("Y-m-d H:i:s");
    fwrite($fp,"$hashArquivo.$ext,$ip,$dataUpload\r\n");
    fclose($fp);
    if(!file_exists('./contador.txt')){
        $fcont = fopen('./contador.txt', 'w+');
        fwrite($fcont,'0');
        fclose($fcont);
    }

    $fcont = fopen('./contador.txt', 'r+');
    $cont = fgets($fcont,16);
   
    $contador = empty($cont)? 1 : (intval($cont) +1);

    rewind($fcont);
    fwrite($fcont, $contador);
    fclose($fcont);
}

function getPost($key, $default) {
    if (isset($_POST[$key]))
        return $_POST[$key];
    return $default;
}
?>