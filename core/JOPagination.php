<?php

/**
 * Joroot Framework(PHP)
 * 
 * Responsavel para gerar paginacao de resultados 
 *   
 * @author      Jurandi C. Oliveira (jurandi@jurandioliveira.com.br)
 * @link        https://github.com/jurandicostaoliveira/joroot 
 * @since       2011
 * @version     1.5.0
 * @license     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOPagination
{

    private $total = 0,
            $limitPerPage = 0,
            $limitLinks = 2,
            $paramPosition = 1,
            $btnFirst = '&laquo;&nbsp;Primeira&nbsp;',
            $btnPrev = '&laquo;&nbsp;Anterior&nbsp;',
            $btnNext = '&nbsp;Pr&oacute;ximo&nbsp;&raquo;',
            $btnLast = '&nbsp;&Uacute;ltima&nbsp;&raquo;',
            $wrapTag = array('open' => null, 'close' => null);

    /**
     * O total geral de resultados Ex: 1000
     * 
     * @param int $total
     */
    public function setTotal($total = 0)
    {
        $this->total = (int) $total;
    }

    /**
     * A quantidade limite de resultados por pagina Ex: 20
     * 
     * @param int $limitPerPage
     */
    public function setLimitPerPage($limitPerPage = 0)
    {
        $this->limitPerPage = (int) $limitPerPage;
    }

    /**
     * A quantidade de links que aparecera disponivel
     * 
     * @param int $limitLinks
     */
    public function setLimitLinks($limitLinks = 2)
    {
        $this->limitLinks = (int) $limitLinks;
    }

    /**
     * O parametro que deve ser ultilizados para receber o numero da pagina pela URL Ex: 2, ele ira capturar o valor do $JOURL[PARAM2]
     * 
     * @param int $paramPosition
     */
    public function setParamPosition($paramPosition = 1)
    {
        $this->paramPosition = (int) $paramPosition;
    }

    /**
     * Envolve o link com a tag HTML que for configurada
     * 
     * @param string $open
     * @param string $close
     */
    public function setWrapTag($open = null, $close = null)
    {
        $this->wrapTag = array('open' => $open, 'close' => $close);
    }

    /**
     * Configuracoes para personalizar os botoes
     * 
     * @param string $first => Botao para primeira pagina
     * @param string $last => Botao para ultima pagina
     * @param string $prev => Botao para pagina anterior
     * @param string $next => Botao para proxima pagina
     */
    public function setButtons($first = null, $last = null, $prev = null, $next = null)
    {
        $this->btnFirst = $first;
        $this->btnLast = $last;
        $this->btnPrev = $prev;
        $this->btnNext = $next;
    }

    private function getParameters()
    {
        global $JOURL;
        $parameters = null;
        for ($i = 1; $i < $this->paramPosition; $i++) {
            $parameters .= $JOURL['PARAM' . $i] . '/';
        }
        return $parameters;
    }

    /**
     * Modelo 1 da paginacao de resultados
     */
    private function modelOne()
    {
        global $JOURL;

        $url = ROOT . $JOURL['CONTROLLER'] . '/' . $JOURL['ACTION'] . '/';
        $lastParam = (int) $JOURL['PARAM' . $this->paramPosition];
        $convLastParam = ($lastParam > 0) ? $lastParam : 1;
        $parameters = $this->getParameters();
        $pages = ceil($this->total / $this->limitPerPage);

        $string = null;
        //primeira pagina
        $string .= "{$this->wrapTag['open']}<a href=\"{$url}{$parameters}1\" id=\"jorootBtnFirst\">{$this->btnFirst}</a>{$this->wrapTag['close']}";
        for ($i = $convLastParam - $this->limitLinks; $i <= $convLastParam - 1; $i++) {
            if ($i <= 0) {
                continue;
            } else {
                $string .= "{$this->wrapTag['open']}<a href=\"{$url}{$parameters}{$i}\" class=\"jorootBtnNav\">{$i}</a>{$this->wrapTag['close']}";
            }
        }

        //link ativo
        $string .= "{$this->wrapTag['open']}<a href=\"javascript:void(0)\" id=\"joBtnActive\">{$convLastParam}</a>{$this->wrapTag['close']}";

        for ($i = $convLastParam + 1; $i <= $convLastParam + $this->limitLinks; $i++) {
            if ($i > $pages) {
                continue;
            } else {
                $string .= "{$this->wrapTag['open']}<a href=\"{$url}{$parameters}{$i}\" class=\"jorootBtnNav\">{$i}</a>{$this->wrapTag['close']}";
            }
        }

        //ultima pagina
        $string .= "{$this->wrapTag['open']}<a href=\"{$url}{$parameters}{$pages}\" id=\"jorootBtnLast\">{$this->btnLast}</a>{$this->wrapTag['close']}";
        return $string;
    }

    /**
     * Modelo 2 da paginacao de resultados
     */
    private function modelTwo()
    {
        global $JOURL;

        $url = ROOT . $JOURL['CONTROLLER'] . '/' . $JOURL['ACTION'] . '/';
        $lastParam = (int) $JOURL['PARAM' . $this->paramPosition];
        $convLastParam = ($lastParam > 0) ? $lastParam : 1;
        $parameters = $this->getParameters();
        $pages = ceil($this->total / $this->limitPerPage);

        //primeira pagina
        $firstLink = "{$this->wrapTag['open']}<a href=\"{$url}{$parameters}1\" id=\"jorootBtnFirst\">{$this->btnFirst}</a>{$this->wrapTag['close']}";

        //pagina anterior
        $prevPg = $convLastParam - 1;
        $prevLink = null;
        if ($convLastParam > 1) {
            $prevLink = "{$this->wrapTag['open']}<a href=\"{$url}{$parameters}{$prevPg}\" id=\"joBtnPrev\">{$this->btnPrev}</a>{$this->wrapTag['close']}";
        }

        //proxima pagina     
        $nextPg = $convLastParam + 1;
        $nextLink = null;
        if ($pages > $convLastParam) {
            $nextLink = "{$this->wrapTag['open']}<a href=\"{$url}{$parameters}{$nextPg}\" id=\"joBtnNext\">{$this->btnNext}</a>{$this->wrapTag['close']}";
        }

        //ultima pagina    
        $lastLink = "{$this->wrapTag['open']}<a href=\"{$url}{$parameters}{$pages}\" id=\"jorootBtnLast\">{$this->btnLast}</a>{$this->wrapTag['close']}";

        $string = "{$firstLink}&nbsp;{$prevLink}&nbsp;{$nextLink}&nbsp;{$lastLink}";
        return $string;
    }

    /**
     * Retornar os links da paginacao de resultados
     *
     * @param int $model
     * @return string
     */
    public function get($model = 1)
    {
        switch ((int) $model) {
            case 2:
                return $this->modelTwo();
                break;
            default :
                return $this->modelOne();
                break;
        }
    }

    /**
     * Exibir os links da paginacao de resultados
     * 
     * @param int $model
     */
    public function show($model = 1)
    {
        echo $this->get($model);
    }

}
