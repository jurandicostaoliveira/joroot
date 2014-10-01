<?php

/**
 * Joroot Framework(PHP)
 * 
 * JODownload forca o download do arquivo  
 *  
 * @autor       Jurandi Costa Oliveira (jurandi@jurandioliveira.com.br)
 * @link        http://www.jurandioliveira.com.br/joroot 
 * @desde       2011
 * @versao      1.2.0
 * @licenca     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JODownload
{

    private $joPath = null;

    /**
     * Retorna o erro caso houver um.
     * @param String $error
     * @return Page - Pagina imprimindo a mensagem de erro  
     */
    protected static function joError($error = null)
    {
        if (!SHOW_MSG_ERROR) {
            $error = 'N&atilde;o entre em p&acirc;nico, pode ser apenas um erro de rota, verifique a URL digitada!';
        }
        die(require_once($GLOBALS['JOCOREPATH'] . 'JOError.php'));
    }

    /**
     * Indica o caminho caminho da pasta onde se encontra o arquivo 
     * @param String $path = Ex: 'lib/images/'
     */
    public function joSetPath($path = false)
    {
        if ($path) {
            $this->joPath = $path;
            if (substr($this->joPath, -1) <> '/') {
                $this->joPath = $this->joPath . '/';
            }
        }
    }

    /**
     * Executa o download do arquivo
     * @param String $file = Ex:'imagem.jpg'
     * @throws Exception
     */
    public function joSetDownload($file = false)
    {
        try {
            if ($file) {
                if (file_exists($this->joPath . $file)) {
                    //Define o tempo máximo de execução
                    set_time_limit(0);
                    header('Content-Description: File Transfer');
                    header('Content-Disposition: attachment; filename="' . $file . '"');
                    header('Content-Type: application/octet-stream');
                    header('Content-Transfer-Encoding: binary');
                    header('Content-Length: ' . filesize($this->joPath . $file));
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Expires: 0');
                    readfile($this->joPath . $file);
                } else {
                    throw new Exception('O arquivo n&atilde;o existe .: ' . $this->joPath . $file);
                }
            } else {
                throw new Exception('Informe o nome do arquivo');
            }
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

}
