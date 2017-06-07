<?php

namespace Framework\GeneralBundle\Entity;

class funcionalidadescuit
{
    
    /**
     * get funcionalidadescuit
     *
     * @param string $stringCuit
     * @return Boolean
     */
    
    public static function validaCuit($stringCuit){
        $suma = 0;
        if(is_null($stringCuit))return false;
        if(strlen($stringCuit) != 11)return false;
        $multiplicadores = array (5,4,3,2,7,6,5,4,3,2,1);
        for($i=0;$i<11;$i++){
                $suma += $stringCuit[$i]*$multiplicadores[$i];
        }
        return $suma%11==0;
    }
}
