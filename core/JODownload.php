<?php

/**
 * Joroot Framework(PHP)
 * 
 * Responsavel para forcar o download do arquivo
 *   
 * @author      Jurandi C. Oliveira (jurandi@jurandioliveira.com.br)
 * @link        https://github.com/jurandicostaoliveira/joroot 
 * @since       2011
 * @version     1.5.0
 * @license     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JODownload
{

    /**
     * Executa o download 
     * 
     * @param string $filename = Ex:'diretorio/imagem.jpg'
     * @throws Exception
     */
    public function getFile($filename = null)
    {
        try {
            if (file_exists($filename)) {
                set_time_limit(0); //Define o tempo maximo de execucao
                header('Content-Description: File Transfer');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Content-Type: application/octet-stream');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . filesize($filename));
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Expires: 0');
                readfile($filename);
            } else {
                throw new Exception("O arquivo {$filename} n&atilde;o existe.");
            }
        } catch (Exception $e) {
            JOBootstrap::error($e->getMessage());
        }
    }

}
