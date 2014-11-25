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

    protected $values = array();

    public function joSetFieldValue($value = null, $type = 'VOID', $msg = '')
    {
        $this->values[] = array('value' => $value, 'type' => $type, 'msg' => $msg, 'length' => 0);
    }

    public function joSetFieldLength($value = null, $type = 'MIN', $length = 1, $msg = '')
    {
        $this->values[] = array('value' => $value, 'type' => $type, 'length' => $length, 'msg' => $msg);
    }

    public function joSetFieldsCompare($value = null, $valueCompare = null, $msg = '')
    {
        $this->values[] = array('value' => $value, 'valueCompare' => $valueCompare, 'type' => 'COMPARE', 'msg' => $msg);
    }

    public function joValidateFields()
    {

        $regex['ALNUM'] = '/[[:alnum:]]/';
        $regex['ALPHA'] = '/[[:alpha:]]/';
        $regex['CEP'] = '/^[0-9]{5}\-[0-9]{3}$/';
        $regex['CNPJ'] = '/^[0-9]{2}\.[0-9]{3}\.[0-9]{3}\/[0-9]{4}\-[0-9]{2}$/';
        $regex['CPF'] = '/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}\-[a-zA-Z0-9]{2}$/';
        $regex['DATE'] = '/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/';
        $regex['DDD'] = '/^[0-9]{2,3}$/';
        $regex['EMAIL'] = '/^[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/';
        $regex['NUMBER'] = '/^[0-9]+$/';
        $regex['PHONE'] = '/^[0-9]{4,5}\-[0-9]{4}$/';
        $regex['RG'] = '/^[0-9]{2}\.[0-9]{3}\.[0-9]{3}\-[a-zA-Z0-9]{1}$/';
        $regex['PRICE'] = '/^[0-9\.\,]+$/';
        $regex['MIN'] = 1;
        $regex['MAX'] = 100;
        $regex['COMPARE'] = null;

        $size = count($this->values);
        $msg = '';
        $error = false;
        for ($i = 0; $i < $size; $i++) {
            $upper = strtoupper($this->values[$i]['type']);
            $type = (isset($regex[$upper])) ? $upper : 'VOID';
            switch ($type) {
                case 'VOID':
                    if (empty($this->values[$i]['value'])) {
                        $error = true;
                        $msg .= "{$this->values[$i]['msg']} ";
                    }
                    break;
                case 'MIN':
                    if (strlen($this->values[$i]['value']) < (int) $this->values[$i]['length']) {
                        $error = true;
                        $msg .= "{$this->values[$i]['msg']} ";
                    }
                    break;
                case 'MAX':
                    if (strlen($this->values[$i]['value']) > (int) $this->values[$i]['length']) {
                        $error = true;
                        $msg .= "{$this->values[$i]['msg']} ";
                    }
                    break;
                case 'COMPARE':
                    if ($this->values[$i]['value'] !== $this->values[$i]['valueCompare']) {
                        $error = true;
                        $msg .= "{$this->values[$i]['msg']} ";
                    }
                    break;
                default :
                    if (!preg_match($regex[$type], $this->values[$i]['value'])) {
                        $error = true;
                        $msg .= "{$this->values[$i]['msg']} ";
                    }
                    break;
            }
        }
        return array('error' => $error, 'msg' => $msg);
    }

}
