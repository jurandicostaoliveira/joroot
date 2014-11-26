<?php

/**
 * Joroot Framework(PHP)
 * 
 * Responsavel para validar tipos e valores
 *  
 * @author      Jurandi C. Oliveira (jurandi@jurandioliveira.com.br)
 * @link        https://github.com/jurandicostaoliveira/joroot 
 * @since       2011
 * @version     1.5.0
 * @license     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOValidate
{

    private $values = array();

    /**
     * Informar os campos para validar
     * 
     * @param string $value
     * @param string $type
     * @param string $message
     */
    public function setFieldValue($value = null, $type = 'VOID', $message = '')
    {
        $this->values[] = array('value' => $value, 'type' => $type, 'message' => $message, 'length' => 0);
    }

    /**
     * Informar os campos para validar
     * 
     * @param string $value
     * @param string $type
     * @param int $length
     * @param string $message
     */
    public function setFieldLength($value = null, $type = 'MIN', $length = 1, $message = '')
    {
        $this->values[] = array('value' => $value, 'type' => $type, 'length' => $length, 'message' => $message);
    }

    /**
     * Informar os campos para comprarar e validar
     * 
     * @param string $value
     * @param string $valueCompare
     * @param string $message
     */
    public function setFieldsCompare($value = null, $valueCompare = null, $message = '')
    {
        $this->values[] = array('value' => $value, 'valueCompare' => $valueCompare, 'type' => 'COMPARE', 'message' => $message);
    }

    /**
     * Formato de REGEX
     * 
     * @return array
     */
    private function getRegex()
    {
        return array(
            'ALNUM' => '/[[:alnum:]]/',
            'ALPHA' => '/[[:alpha:]]/',
            'CEP' => '/^[0-9]{5}\-[0-9]{3}$/',
            'CNPJ' => '/^[0-9]{2}\.[0-9]{3}\.[0-9]{3}\/[0-9]{4}\-[0-9]{2}$/',
            'CPF' => '/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}\-[a-zA-Z0-9]{2}$/',
            'DATE' => '/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/',
            'DDD' => '/^[0-9]{2,3}$/',
            'EMAIL' => '/^[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/',
            'NUMBER' => '/^[0-9]+$/',
            'PHONE' => '/^[0-9]{4,5}\-[0-9]{4}$/',
            'RG' => '/^[0-9]{2}\.[0-9]{3}\.[0-9]{3}\-[a-zA-Z0-9]{1}$/',
            'PRICE' => '/^[0-9\.\,]+$/',
            'MIN' => 1,
            'MAX' => 100,
            'COMPARE' => null
        );
    }

    /**
     * Executa a validacao
     * 
     * @return array
     */
    public function get()
    {
        $regex = $this->getRegex();
        $size = count($this->values);
        $message = '';
        $error = false;
        for ($i = 0; $i < $size; $i++) {
            $upper = strtoupper($this->values[$i]['type']);
            $type = (isset($regex[$upper])) ? $upper : 'VOID';
            switch ($type) {
                case 'VOID':
                    if (empty($this->values[$i]['value'])) {
                        $error = true;
                        $message .= "{$this->values[$i]['message']} ";
                    }
                    break;
                case 'MIN':
                    if (strlen($this->values[$i]['value']) < (int) $this->values[$i]['length']) {
                        $error = true;
                        $message .= "{$this->values[$i]['message']} ";
                    }
                    break;
                case 'MAX':
                    if (strlen($this->values[$i]['value']) > (int) $this->values[$i]['length']) {
                        $error = true;
                        $message .= "{$this->values[$i]['message']} ";
                    }
                    break;
                case 'COMPARE':
                    if ($this->values[$i]['value'] !== $this->values[$i]['valueCompare']) {
                        $error = true;
                        $message .= "{$this->values[$i]['message']} ";
                    }
                    break;
                default :
                    if (!preg_match($regex[$type], $this->values[$i]['value'])) {
                        $error = true;
                        $message .= "{$this->values[$i]['message']} ";
                    }
                    break;
            }
        }
        return array('error' => $error, 'message' => $message);
    }

}
