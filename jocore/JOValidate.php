<?php

/**
 * Joroot Framework(PHP)
 * 
 * JOValidate maximiza validacoes de valores
 *  
 * @autor       Jurandi Costa Oliveira (jurandi@jurandioliveira.com.br)
 * @link        http://www.jurandioliveira.com.br/joroot 
 * @desde       2011
 * @versao      1.2.0
 * @licenca     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOValidate {

    protected $values = array();

    public function joSetFieldValue($id = false, $value = null, $type = 'VOID') {
        if ($id)
            $this->values[] = array('id' => $id, 'value' => $value, 'type' => $type);
    }

    public function joValidateField() {

        $validated = array('status' => true, 'id' => null, 'format' => null);

        $regex['ALNUM'] = '/^[[:alnum:]]*$/';
        $regex['ALPHA'] = '/^[[:alpha:]]*$/';
        $regex['CEP'] = '/^[0-9]{5}\-[0-9]{3}$/';
        $regex['CNPJ'] = '/^[0-9]{2}\.[0-9]{3}\.[0-9]{3}\/[0-9]{4}\-[0-9]{2}$/';
        $regex['CPF'] = '/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}\-[a-zA-Z0-9]{2}$/';
        $regex['DATE'] = '/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/';
        $regex['DDD'] = '/^[0-9]{2,3}$/';
        $regex['EMAIL'] = '/^[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/';
        $regex['NUMBER'] = '/^[[:digit:]]*$/';
        $regex['PHONE'] = '/^[0-9]{4,5}\-[0-9]{4}$/';
        $regex['RG'] = '/^[0-9]{2}\.[0-9]{3}\.[0-9]{3}\-[a-zA-Z0-9]{1}$/';
        //
        $format['ALNUM'] = 'Letras e n&uacute;meros';
        $format['ALPHA'] = 'Letras';
        $format['CEP'] = '00000-000';
        $format['CNPJ'] = '00.000.000/0000-00';
        $format['CPF'] = '000.000.000-00';
        $format['DATE'] = '00/00/0000';
        $format['DDD'] = '00 ou 000';
        $format['EMAIL'] = 'exemplo@nomedominio.servico ou exemplo@nomedominio.servico.pais';
        $format['NUMBER'] = 'N&uacute;meros';
        $format['PHONE'] = '0000-0000 ou 00000-0000';
        $format['RG'] = '00.000.000-0';
        $format['VOID'] = 'N&atilde;o aceita campo vazio';

        $size = count($this->values);
        for ($i = 0; $i < $size; $i++) {
            $upper = strtoupper($this->values[$i]['type']);
            $type = (isset($regex[$upper])) ? $upper : 'VOID';

            if ($type == 'VOID') {
                if (empty($this->values[$i]['value'])) {
                    $validated = array('status' => false, 'id' => $this->values[$i]['id'], 'format' => $format[$type]);
                    break;
                }
            } else {
                if (!preg_match($regex[$type], $this->values[$i]['value'])) {
                    $validated = array('status' => false, 'id' => $this->values[$i]['id'], 'format' => $format[$type]);
                    break;
                }
            }
        }
        return $validated;
    }

}