<?php

/**
 * Joroot Framework(PHP)
 * 
 * JOLog facilitadora na geracao de arquivos de logs
 *  
 * @autor       Jurandi Costa Oliveira (jurandi@jurandioliveira.com.br)
 * @link        http://www.jurandioliveira.com.br/joroot 
 * @desde       2011
 * @versao      1.2.0
 * @licenca     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOLog {

    private $nameDir = 'log/';

    /**
     * $joNameLog = recebe o nome do arquivo exemplo: teste.txt
     * @var String 
     */
    public $joNameLog = 'default_log.txt';
    private $fp, $text, $logArray = array();

    /**
     * Verifica a existencia da nome no arquivo. 
     */
    public function joSetNameLog($name = null) {
        if ($name != null)
            $this->joNameLog = $name;

        if (!file_exists($this->nameDir))
            mkdir($this->nameDir, 0777);
    }

    /**
     * Cria, e escreve no arquivo
     * @param String $msgLog = mensagem de personalizacao : email@email.com.br
     */
    public function joWriteLog($msgLog = null) {

        $this->fp = fopen($this->nameDir . $this->joNameLog, "a");
        $this->text = "[" . date('d/m/Y H:i:s') . "] - " . $msgLog . "\n";
        fwrite($this->fp, $this->text);

        if (!file_exists($this->nameDir . '.htaccess')) {
            $this->fp = fopen($this->nameDir . '.htaccess', "a");
            fwrite($this->fp, 'deny from all');
        }

        fclose($this->fp);
        chmod($this->nameDir . $this->joNameLog, 0777);
    }

    /**
     * Ler o arquivo
     * @return array
     * @throws Exception 
     */
    public function joReadLog() {
        try {
            if ($this->joNameLog) {
                if (file_exists($this->nameDir . $this->joNameLog))
                    $this->logArray = file($this->nameDir . $this->joNameLog);
                else
                    throw new Exception('Nenhum log, foi encontrado com esse nome .: ' . $this->joNameLog);
            } else {
                throw new Exception('Informe o nome do log que deseja visualizar');
            }
        } catch (Exception $e) {
            $this->logArray[0] = $e->getMessage();
        }
        return $this->logArray;
    }

    /**
     * Limpa o arquivo
     * @return array
     * @throws Exception 
     */
    public function joCleanLog() {
        try {
            if ($this->joNameLog) {
                if (file_exists($this->nameDir . $this->joNameLog)) {
                    $this->fp = fopen($this->nameDir . $this->joNameLog, "w+");
                    fclose($this->fp);
                    $this->logArray[] = 'O arquivo "' . $this->joNameLog . '" esta vazio!';
                } else {
                    throw new Exception('Nenhum log, foi encontrado com esse nome .: ' . $this->joNameLog);
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