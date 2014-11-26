<?php

/**
 * Joroot Framework(PHP)
 * 
 * Responsavel para gerar arquivos de logs
 *   
 * @author      Jurandi C. Oliveira (jurandi@jurandioliveira.com.br)
 * @link        https://github.com/jurandicostaoliveira/joroot 
 * @since       2011
 * @version     1.5.0
 * @license     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOLog
{

    private $dir = 'log/',
            $name = 'default_log.txt',
            $fp,
            $text,
            $logArray = array();

    /**
     * Definir o nome do arquivo de log 
     */
    public function setName($name = null)
    {
        if ($name != null) {
            $this->name = $name;
        }

        if (!file_exists($this->dir)) {
            mkdir($this->dir, 0777);
        }
    }

    /**
     * Cria, e escreve no arquivo
     * @param String $message = mensagem de personalizacao : email@email.com.br
     */
    public function write($message = null)
    {
        $this->fp = fopen($this->dir . $this->name, "a");
        $this->text = "[" . date('d/m/Y H:i:s') . "] - " . $message . "\n";
        fwrite($this->fp, $this->text);

        if (!file_exists($this->dir . '.htaccess')) {
            $this->fp = fopen($this->dir . '.htaccess', "a");
            fwrite($this->fp, 'deny from all');
        }

        fclose($this->fp);
        chmod($this->dir . $this->name, 0777);
    }

    /**
     * Ler o arquivo
     * 
     * @return array
     * @throws Exception 
     */
    public function read()
    {
        try {
            if ($this->name) {
                if (file_exists($this->dir . $this->name)) {
                    $this->logArray = file($this->dir . $this->name);
                } else {
                    throw new Exception('Nenhum log, foi encontrado com esse nome .: ' . $this->name);
                }
            } else {
                throw new Exception('Informe o nome do log que deseja visualizar');
            }
        } catch (Exception $e) {
            $this->logArray[0] = $e->getMessage();
        }
        return $this->logArray;
    }

    /**
     * Limpar o arquivo
     * 
     * @return array
     * @throws Exception 
     */
    public function clean()
    {
        try {
            if ($this->name) {
                if (file_exists($this->dir . $this->name)) {
                    $this->fp = fopen($this->dir . $this->name, "w+");
                    fclose($this->fp);
                    $this->logArray[] = 'O arquivo "' . $this->name . '" esta vazio!';
                } else {
                    throw new Exception('Nenhum log, foi encontrado com esse nome .: ' . $this->name);
                }
            } else {
                throw new Exception('Informe o nome do log, que deseja limpar');
            }
        } catch (Exception $e) {
            $this->logArray[0] = $e->getMessage();
        }
        return $this->logArray;
    }

}
