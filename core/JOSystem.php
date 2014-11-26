<?php

/**
 * Joroot Framework(PHP)
 * 
 * Responsavel em projetar a entrada de dados pela URL
 * Faz fluir o mecanismo do projeto, de acordo com o que vem da URL
 * Ex da URL : http://www.seudominio.com.br/controle/acao/parametro1/parametro2 ...  
 *   
 * @author      Jurandi C. Oliveira (jurandi@jurandioliveira.com.br)
 * @link        https://github.com/jurandicostaoliveira/joroot 
 * @since       2011
 * @version     1.5.0
 * @license     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOSystem
{

    private $url;

    /**
     * Captura os dados passados pela URL.
     */
    private function prepareUrl()
    {
        $inputUrl = filter_input(INPUT_GET, 'url');
        $url = ($inputUrl) ? $inputUrl : ROUTE_DEFAULT;
        $this->url = explode('/', $url);
    }

    /**
     * Monta e retorna um array com os valores que foram passados pela URL, em formato de variaveis globais
     */
    public function getUrl()
    {
        $this->prepareUrl();
        $url['CONTROLLER'] = $this->getController();
        $url['ACTION'] = $this->getAction();
        $maxParam = intval(MAX_PARAM) + 1;
        //Montagem dos parametros
        for ($i = 1; $i < $maxParam; ++$i) {
            if (isset($this->url[$i + 1])) {
                $url['PARAM' . $i] = $this->url[$i + 1];
            } else {
                $url['PARAM' . $i] = 0;
            }
        }
        return $url;
    }

    /**
     * Trata o controller em execucao.
     */
    private function getController()
    {
        try {
            if (file_exists(CONTROLLERS . ucfirst($this->url[0]) . 'Controller.php')) {
                return $this->url[0];
            } else {
                throw new Exception('O arquivo ' . CONTROLLERS . ucfirst($this->url[0]) . 'Controller.php n&atilde;o foi encontrado.');
            }
        } catch (Exception $e) {
            JOBootstrap::error($e->getMessage());
        }
    }

    /**
     * Trata a action em execucao.
     */
    private function getAction()
    {
        return isset($this->url[1]) && (!empty($this->url[1])) ? $this->url[1] : 'index';
    }

    /**
     * Retorna a action no formato camelCase
     * 
     * @param string $action
     */
    public function getCamelCaseAction($action = 'index')
    {
        $actionCamelCase = preg_replace_callback('/[-_](.)/', function ($matches) {
            return strtoupper($matches[1]);
        }, $action);
        return $actionCamelCase;
    }

    /**
     * Autenticacao da existencia da action.
     * 
     * @param string $controller
     * @param string $action
     * @return string 
     */
    public function authAction($controller, $action)
    {
        try {
            if (empty($action)) {
                $action = 'index';
            }

            if (class_exists($controller)) {
                if (method_exists($controller, $action)) {
                    return $action;
                } else {
                    throw new Exception('O m&eacute;todo ' . $action . '(), n&atilde;o foi definido na classe ' . $controller . ' do arquivo ' . CONTROLLERS . $controller . '.php');
                }
            } else {
                throw new Exception('A classe ' . $controller . ' n&atilde;o foi definida no arquivo ' . CONTROLLERS . $controller . '.php');
            }
        } catch (Exception $e) {
            JOBootstrap::error($e->getMessage());
        }
    }

    /**
     * Consulta os atributos
     * ::Experimento::
     */
    /*
      public function joRootUrl() {
      $dom = new DOMDocument();
      $dom->loadHTMLFile("app/views/index.php");

      //Consultando os links
      $links = $dom->getElementsByTagName("a");
      foreach ($links as $link) {
      echo $link->getAttribute("href") . PHP_EOL;
      }

      //Consultando as imagens
      $imgs = $dom->getElementsByTagName("img");
      foreach ($imgs as $img) {
      echo $img->getAttribute("src") . PHP_EOL;
      }
      }
     */
}
