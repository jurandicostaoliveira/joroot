<?php

/**
 * Joroot Framework(PHP)
 * 
 * Responsavel pelo o compatilhamento de classes
 *  
 * @author      Jurandi C. Oliveira (jurandi@jurandioliveira.com.br)
 * @link        https://github.com/jurandicostaoliveira/joroot 
 * @since       2011
 * @version     1.5.0
 * @license     Gratuito para estudo, desenvolvimento e contribuicao
 */
abstract class JOController
{

    /**
     * Rotina que verifica se ha o prefixo especifico seguido de @ para encontrar o caminho.
     * 
     * @param string $filename
     * @return string
     */
    private static function getPath($filename)
    {
        $response = (object) array(
                    'className' => $filename,
                    'fileName' => __DIR__ . DIRECTORY_SEPARATOR . "{$filename}.php"
        );

        if (preg_match('/^APP@/', $filename)) {
            $response->className = str_replace('APP@', '', $filename);
            $response->fileName = "app/{$response->className}.php";
        } else if (preg_match('/^CONTROLLER@/', $filename)) {
            $response->className = str_replace('CONTROLLER@', '', $filename);
            $response->fileName = CONTROLLERS . "{$response->className}.php";
        } else if (preg_match('/^MODEL@/', $filename)) {
            $response->className = str_replace('MODEL@', '', $filename);
            $response->fileName = MODELS . "{$response->className}.php";
        }

        return $response;
    }

    /**
     * Rotina para testar a existencia da classe informada, que devera possui o mesmo nome do arquivo.
     * 
     * @param string $className
     * @param string $pathFile
     */
    private static function checkClass($className, $fileName)
    {
        if (!class_exists($className)) {
            JOBootstrap::error("A classe n&atilde;o foi encontrada no arquivo {$fileName}.");
        }
    }

    /**
     * Rotina para incluir classes predefinidas ou nao
     * 
     * JOView
     * JOSession
     * JOValidate
     * JOPaginate
     * JOUpload
     * JOLog
     * JOMail
     * JOHtml
     * JODownload
     * JORequest
     * 
     * OU
     * 
     * APP@caminho/do/arquivo a partir do diretorio app/
     * CONTROLLER@caminho/do/arquivo a partir do diretorio app/controllers/
     * MODEL@caminho/do/arquivo a partir do diretorio app/models/
     * 
     * @param array $options
     */
    public static function get($options)
    {
        if (is_array($options)) {
            foreach ($options as $key => $value) {
                $path = self::getPath($value);
                if (file_exists($path->fileName)) {
                    require_once $path->fileName;
                    self::checkClass($path->className, $path->fileName);
                } else {
                    JOBootstrap::error("O arquivo {$path->fileName} n&atilde;o foi encontrado.");
                }
            }
        }
    }

}
