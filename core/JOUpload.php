<?php

/**
 * Joroot Framework(PHP)
 * 
 * Responsavel para redimensionar imagens ou simplismente mover arquivos 
 *  
 * @author      Jurandi C. Oliveira (jurandi@jurandioliveira.com.br)
 * @link        https://github.com/jurandicostaoliveira/joroot 
 * @since       2011
 * @version     1.5.0
 * @license     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOUpload
{

    private $dir = 'lib/images/';

    /**
     * Retorna um novo nome aleatorio para o arquivo 
     * 
     * @param array $file
     * @return String 
     * @throws Exception
     */
    public function getRandomName($file = null)
    {
        try {
            if (is_array($file)) {
                return md5(uniqid(rand(), true)) . '.' . $this->getFileExtension($file);
            } else {
                throw new Exception('Informe o arquivo para obter um nome aleat&oacute;rio.');
            }
        } catch (Exception $e) {
            JOBootstrap::error($e->getMessage());
        }
    }

    /**
     * Retorna a extensao do arquivo
     * 
     * @param array $file
     * @return string
     * @throws Exception
     */
    public function getFileExtension($file = null)
    {
        try {
            if (is_array($file)) {
                $expFile = explode('.', $file['name']);
                $extension = end($expFile);
                return strtolower($extension);
            } else {
                throw new Exception('Informe o arquivo para obter sua extens&atilde;o.');
            }
        } catch (Exception $e) {
            JOBootstrap::error($e->getMessage());
        }
    }

    /**
     * Prepara o caminho para armazenar o arquivo  Ex: 'lib/images/'
     * Por padrao 'lib/images/'
     * 
     * @param String $dir 
     */
    public function setDir($dir = null)
    {
        if ($dir !== null) {
            $this->dir = $dir;
        }

        if (substr($this->dir, -1) != '/') {
            $this->dir .= '/';
        }

        if (!file_exists($this->dir)) {
            mkdir($this->dir);
            chmod($this->dir, 0777);
        }
    }

    /**
     * Redimensiona e envia a imagem para o diretorio de destino
     * Detalhe .: só funciona com .JPG, .JPEG, .GIF ou .PNG
     * 
     * @param array $file = $_FILES['arquivo']
     * @param int $width = A largura da nova imagem desejada. 
     * @param string $newName = O nome da nova imagem(personalizar se quiser)
     * @throws Exception
     */
    public function moveResizeImage($file = null, $width = 100, $newName = false)
    {
        try {
            if (is_array($file)) {
                $name = ($newName) ? $newName : $file['name'];
                //Verifica a extensao da imagem para cria-la
                switch ($file['type']) {
                    case 'image/gif': $image = imagecreatefromgif($file['tmp_name']);
                        break;
                    case 'image/png': $image = imagecreatefrompng($file['tmp_name']);
                        break;
                    default: $image = imagecreatefromjpeg($file['tmp_name']);
                        break;
                }
                //Obtendo a largura e altura da imagem real
                $x = imagesx($image);
                $y = imagesy($image);
                //Obtendo a altura propocional a largura
                $height = ($width * $y) / $x;
                //Criando uma nova imagem em branco com as novas dimensoes
                $newImage = imagecreatetruecolor($width, $height);
                //copiamos a imagem antiga para a nova 
                imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $x, $y);

                //Enviando para o diretório de destino
                switch ($file['type']) {
                    case 'image/gif': imagegif($newImage, $this->dir . $name);
                        break;
                    case 'image/png': imagepng($newImage, $this->dir . $name);
                        break;
                    default: imagejpeg($newImage, $this->dir . $name);
                        break;
                }
                chmod($this->dir . $name, 0777);

                //Depois de enviada destroi as imagens
                imagedestroy($image);
                imagedestroy($newImage);
            } else {
                throw new Exception('Informe a imagem para o redimensionamento.');
            }
        } catch (Exception $e) {
            JOBootstrap::error($e->getMessage());
        }
    }

    /**
     * Rotina para mover arquivo 
     *
     * @param array $file
     * @param string $newName
     * @throws Exception
     */
    public function moveFile($file = null, $newName = false)
    {
        try {
            if (is_array($file)) {
                $name = ($newName) ? $newName : $file['name'];
                if ((int) $file['error'] === 0) {
                    move_uploaded_file($file['tmp_name'], $this->dir . $name);
                    chmod($this->dir . $name, 0777);
                } else {
                    throw new Exception('Falha ao tentar enviar o arquivo.');
                }
            } else {
                throw new Exception('Informe o arquivo para ser enviado.');
            }
        } catch (Exception $e) {
            JOBootstrap::error($e->getMessage());
        }
    }

    /**
     * Rotina para remover arquivo
     * 
     * @param string $filename
     */
    public function removeFile($filename = null)
    {
        try {
            if (($filename !== null) && (file_exists($filename))) {
                unlink($filename);
            } else {
                throw new Exception("O arquivo {$filename}, n&atilde;o foi encontrado.");
            }
        } catch (Exception $e) {
            JOBootstrap::error($e->getMessage());
        }
    }

}
