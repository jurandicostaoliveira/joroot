<?php

/**
 * Joroot Framework(PHP)
 * 
 * JOUpload redimensiona imagens ou simplismente move arquivos 
 *  
 * @autor       Jurandi Costa Oliveira (jurandi@jurandioliveira.com.br)
 * @link        http://www.jurandioliveira.com.br/joroot 
 * @desde       2011
 * @versao      1.2.0
 * @licenca     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOUpload {

    private $joDir = 'lib/images/';

    /**
     * Retorna o erro caso houver um.
     * @param String $error
     * @return Page - Pagina imprimindo a mensagem de erro  
     */
    protected static function joError($error = null) {
        if (!SHOW_MSG_ERROR)
            $error = 'N&atilde;o entre em p&acirc;nico, pode ser apenas um erro de rota, verifique a URL digitada!';
        die(include($GLOBALS['JOCOREPATH'] . 'JOError.php'));
    }

    /**
     * Retorna um novo nome aleatorio para o arquivo 
     * @param array $file
     * @return String 
     * @throws Exception
     */
    public function joRandomName($file = null) {
        try {
            if (is_array($file)) {
                return md5(uniqid(rand(), true)) . '.' . self::joFileType($file);
            } else {
                throw new Exception('Informe os dados do arquivo para processamento do novo nome Ex .: $_FILES[\'arquivo\']');
            }
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

    /**
     * Retorna a extensao do arquivo
     * @param array $file
     * @return string
     * @throws Exception
     */
    public function joFileType($file = null) {
        try {
            if (is_array($file)) {
                $extension_exp = explode('.', $file['name']);
                $extension = end($extension_exp);
                $extension = strtolower($extension);
                return $extension;
            } else {
                throw new Exception('Informe os dados do arquivo para recuperar o tipo Ex .: $_FILES[\'arquivo\']');
            }
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

    /**
     * Seta o caminho para armazenar o arquivo  Ex: 'lib/images/subpasta/'
     * Por padrao 'lib/images/'
     * @param String $dir 
     */
    public function joSetDir($dir = null) {

        if ($dir != null)
            $this->joDir = $dir;

        if (substr($this->joDir, -1) <> '/')
            $this->joDir = $this->joDir . '/';

        if (!file_exists($this->joDir)) {
            mkdir($this->joDir);
            chmod($this->joDir, 0777);
        }
    }

    /**
     * Redimensiona e envia a imagem para a pasta de destino
     * Detalhe .: só funciona com .JPG, JPEG, .GIF ou .PNG
     * @param Array $file = $_FILES['arquivo']
     * @param Int $width = A largura da nova imagem desejada. 
     * @param String $filename = O nome da nova imagem(personalizar se quiser)
     */
    public function joResizeImage($file = null, $width = 100, $filename = false) {
        try {
            if (is_array($file)) {
                $name = ($filename) ? $filename : $file['name'];
                //Verifica a extensao da imagem para cria-la
                switch ($file['type']) {
                    case 'image/gif': $img = imagecreatefromgif($file['tmp_name']);
                        break;
                    case 'image/png': $img = imagecreatefrompng($file['tmp_name']);
                        break;
                    default: $img = imagecreatefromjpeg($file['tmp_name']);
                        break;
                }
                //Obtendo a largura e altura da imagem real
                $x = imagesx($img);
                $y = imagesy($img);
                //Obtendo a altura propocional a largura
                $height = ($width * $y) / $x;
                //Criando uma nova imagem em branco com as novas dimensoes
                $new_img = imagecreatetruecolor($width, $height);
                //copiamos a imagem antiga para a nova 
                imagecopyresampled($new_img, $img, 0, 0, 0, 0, $width, $height, $x, $y);

                //Enviando para o diretório de destino
                switch ($file['type']) {
                    case 'image/gif': imagegif($new_img, $this->joDir . $name);
                        break;
                    case 'image/png': imagepng($new_img, $this->joDir . $name);
                        break;
                    default: imagejpeg($new_img, $this->joDir . $name);
                        break;
                }
                chmod($this->joDir . $name, 0777);

                //Depois de enviada destroi as imagens
                imagedestroy($img);
                imagedestroy($new_img);
            } else {
                throw new Exception('Informe os dados da imagem para o redimensionamento Ex .: $_FILES[\'arquivo\']');
            }
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

    /**
     * Move o arquivo para a pasta de destino
     * @param Array $file
     * @param String $filename 
     */
    public function joMoveUpload($file = null, $filename = false) {
        try {
            if (is_array($file)) {
                $name = ($filename) ? $filename : $file['name'];
                if ($file['error'] == 0) {
                    if (move_uploaded_file($file['tmp_name'], $this->joDir . $name))
                        chmod($this->joDir . $name, 0777);
                    else
                        throw new Exception('Erro ao tentar enviar o arquivo');
                } else {
                    throw new Exception('Falha na tentativa de upload');
                }
            } else {
                throw new Exception('Informe os dados do arquivo para o envio Ex .: $_FILES[\'arquivo\']');
            }
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

    /**
     * Para remover arquivos em diretorios que tenha permissao 777
     * @param String $filename
     */
    public function joRemoveFile($filename = null) {
        if (($filename != null) && (file_exists($filename)))
            unlink($filename);
        else
            self::joError("O arquivo {$filename} n&atilde;o foi encontrado");
    }

}