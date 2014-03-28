<?php

/**
 * Joroot Framework(PHP)
 * 
 * JOSystem projeta a entrada de dados pela URL
 * Faz fluir o mecanismo do projeto, de acordo com o que vem da URL
 * Ex da URL : http://www.seudominio.com.br/controle/acao/parametros  
 *  
 * @autor       Jurandi Costa Oliveira (jurandi@jurandioliveira.com.br)
 * @link        http://www.jurandioliveira.com.br/joroot 
 * @desde       2011
 * @versao      1.2.0
 * @licenca     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOSystem {

    private $url, $getUrl, $setUrl;

    /**
     * Retorna uma mensagem de erro amigavel, se caso houver um.
     * @param String $error
     * @return HTML 
     */
    protected static function joError($error = null) {
        if (!SHOW_MSG_ERROR)
            $error = 'N&atilde;o entre em p&acirc;nico, pode ser apenas um erro de rota, verifique a URL digitada!';
        die(include($GLOBALS['JOCOREPATH'] . 'JOError.php'));
    }

    /**
     * Consulta os atributos
     * ::Experimento::
     */
    /* public function joRootUrl() {
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
      } */

    /**
     * Captura os dados passados pela URL.
     */
    public function joSetUrl() {
        $this->getUrl = isset($_GET['url']) ? $_GET['url'] : ROUTE_DEFAULT;
        $this->url = (explode('/', $this->getUrl));
    }

    /**
     * Trata o controller em execucao.
     */
    protected function joGetController() {
        try {
            $strRpl = str_replace('-', '_', $this->url[0]);
            if (file_exists(CONTROLLERS . ucfirst($strRpl) . 'Controller.php'))
                return $strRpl;
            else
                throw new Exception('N&atilde;o existe o arquivo ' . ucfirst($strRpl) . 'Controller.php em ' . CONTROLLERS);
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

    /**
     * Trata a action em execucao.
     */
    protected function joGetAction() {
        return isset($this->url[1]) ? str_replace('-', '_', $this->url[1]) : 'index';
    }

    /**
     * Monta e retorna um array com os valores que foram passados pela URL, em formato de variaveis globais
     */
    public function joGetUrl() {
        $this->setUrl['CONTROLLER'] = self::joGetController();
        $this->setUrl['ACTION'] = self::joGetAction();
        $maxParam = intval(MAX_PARAM) + 1;
        //Montagem dos parametros
        for ($i = 1; $i < $maxParam; ++$i) {
            if (isset($this->url[$i + 1]))
                $this->setUrl['PARAM' . $i] = $this->url[$i + 1];
            else
                $this->setUrl['PARAM' . $i] = 0;
        }
        return $this->setUrl;
    }

    /**
     * Verifica se ha existencia da action.
     * @param Object $controller
     * @param String $action
     * @return Object 
     */
    public function joAuthAction($controller, $action) {
        try {
            $action = (empty($action)) ? 'index' : $action;
            if (class_exists($controller)) {
                if (method_exists($controller, $action))
                    return $action;
                else
                    throw new Exception('O m&eacute;todo ' . $action . '(), n&atilde;o foi definido na classe ' . $controller . ' do arquivo ' . CONTROLLERS . $controller . '.php');
            } else {
                throw new Exception('A classe ' . $controller . ' n&atilde;o foi definida no arquivo ' . CONTROLLERS . $controller . '.php');
            }
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

}

