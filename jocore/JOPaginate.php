<?php

/**
 * Joroot Framework(PHP)
 * 
 * JOPaginate gera paginacao de resultados 
 *  
 * @autor       Jurandi Costa Oliveira (jurandi@jurandioliveira.com.br)
 * @link        http://www.jurandioliveira.com.br/joroot 
 * @desde       2011
 * @versao      1.2.0
 * @licenca     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOPaginate {

    private $total = 0;
    private $limitPage = 0;
    private $limitLinks = 2;
    private $numParam = 1;
    private $btnFirst = '&laquo;&nbsp;Primeira&nbsp;';
    private $btnPrev = '&laquo;&nbsp;Anterior&nbsp;';
    private $btnNext = '&nbsp;Pr&oacute;ximo&nbsp;&raquo;';
    private $btnLast = '&nbsp;&Uacute;ltima&nbsp;&raquo;';

    /**
     * O total geral de resultados Ex: 140
     * @param int $value
     */
    public function joSetTotal($value = 0) {
        $this->total = (int) $value;
    }

    /**
     * A quantidade limite de resultados por pagina Ex: 20
     * @param int $value
     */
    public function joSetLimitPage($value = 0) {
        $this->limitPage = (int) $value;
    }

    /**
     * A quantidade de links que aparecera disponivel
     * @param int $value
     */
    public function joSetLimitLinks($value = 2) {
        $this->limitLinks = (int) $value;
    }

    /**
     * O parametro que deve ser ultilizados para receber o numero da pagina pela URL Ex: 2, ele ira capturar o valor do $JOURL[PARAM2]
     * @param int $value
     */
    public function joSetParamPosition($value = 1) {
        $this->numParam = (int) $value;
    }

    /**
     * Configuracoes para personalizar os botoes
     * @param string $first => Botao para primeira pagina
     * @param string $last => Botao para ultima pagina
     * @param string $prev => Botao para pagina anterior
     * @param string $next => Botao para proxima pagina
     */
    public function joSetButtons($first = null, $last = null, $prev = null, $next = null) {
        $this->btnFirst = $first;
        $this->btnLast = $last;
        $this->btnPrev = $prev;
        $this->btnNext = $next;
    }

    private function joGetParameters() {
        global $JOURL;
        $parameters = null;
        for ($i = 1; $i < $this->numParam; $i++)
            $parameters .= $JOURL['PARAM' . $i] . '/';

        return $parameters;
    }

    /**
     * Modelo 1 da paginacao de resultados
     */
    private function joPaginateOne() {

        global $JOURL;

        $url = ROOT . $JOURL['CONTROLLER'] . '/' . $JOURL['ACTION'] . '/';
        $lastParam = (int) $JOURL['PARAM' . $this->numParam];
        $convLastParam = ($lastParam > 0) ? $lastParam : 1;
        $parameters = self::joGetParameters();
        $pages = ceil($this->total / $this->limitPage);

        $string = null;
        //primeira pagina
        $string .= "<a href=\"{$url}{$parameters}1\" id=\"joBtnFirst\">{$this->btnFirst}</a>";
        for ($i = $convLastParam - $this->limitLinks; $i <= $convLastParam - 1; $i++) {
            if ($i <= 0)
                continue;
            else
                $string .= "<a href=\"{$url}{$parameters}{$i}\" class=\"joBtnNav\">{$i}</a>";
        }

        //link ativo
        $string .= "<a href=\"javascript:void(0)\" id=\"joBtnActive\">{$convLastParam}</a>";

        for ($i = $convLastParam + 1; $i <= $convLastParam + $this->limitLinks; $i++) {
            if ($i > $pages)
                continue;
            else
                $string .= "<a href=\"{$url}{$parameters}{$i}\" class=\"joBtnNav\">{$i}</a>";
        }

        //ultima pagina
        $string .= "<a href=\"{$url}{$parameters}{$pages}\" id=\"joBtnLast\">{$this->btnLast}</a>";
        return $string;
    }

    /**
     * Modelo 2 da paginacao de resultados
     */
    private function joPaginateTwo() {
        global $JOURL;

        $url = ROOT . $JOURL['CONTROLLER'] . '/' . $JOURL['ACTION'] . '/';
        $lastParam = (int) $JOURL['PARAM' . $this->numParam];
        $convLastParam = ($lastParam > 0) ? $lastParam : 1;
        $parameters = self::joGetParameters();
        $pages = ceil($this->total / $this->limitPage);

        //primeira pagina
        $firstLink = "<a href=\"{$url}{$parameters}1\" id=\"joBtnFirst\">{$this->btnFirst}</a>";

        //pagina anterior
        $prevPg = $convLastParam - 1;
        $prevLink = null;
        if ($convLastParam > 1)
            $prevLink = "<a href=\"{$url}{$parameters}{$prevPg}\" id=\"joBtnPrev\">{$this->btnPrev}</a>";

        //proxima pagina     
        $nextPg = $convLastParam + 1;
        $nextLink = null;
        if ($pages > $convLastParam)
            $nextLink = "<a href=\"{$url}{$parameters}{$nextPg}\" id=\"joBtnNext\">{$this->btnNext}</a>";

        //ultima pagina    
        $lastLink = "<a href=\"{$url}{$parameters}{$pages}\" id=\"joBtnLast\">{$this->btnLast}</a>";

        $string = "{$firstLink}&nbsp;{$prevLink}&nbsp;{$nextLink}&nbsp;{$lastLink}";
        return $string;
    }

    /**
     * Retorna os links da paginacao de resultados
     */
    public function joGetPaginate($type = 1) {
        $type = (int) $type;
        switch ($type) {
            case 2:
                return self::joPaginateTwo();
                break;
            default :
                return self::joPaginateOne();
                break;
        }
    }

    /**
     * Exibe os links da paginacao de resultados
     */
    public function joShowPaginate($type = 1) {
        $type = (int) $type;
        switch ($type) {
            case 2:
                echo self::joPaginateTwo();
                break;
            default :
                echo self::joPaginateOne();
                break;
        }
    }

}