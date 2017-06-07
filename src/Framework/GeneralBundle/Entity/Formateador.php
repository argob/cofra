<?php

namespace Framework\GeneralBundle\Entity;

class formateador
{


    /**
     * get String
     *
     * @param integer $numero
     * @return String
     */
    
    public function formateameNumero($numero){
        return number_format($numero, 2, ',', '.');
    }
    
    
    /**
     * get String
     *
     * @param date $fecha
     * @return String
     */
    
    public function formateameFecha($fecha){
        $meses= array( '1' => 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE' );
        $dia= $fecha->format("j");
        $mes=  $fecha->format("m");
        $año=  $fecha->format("Y");
        return $dia." DE ".$meses[(int)$mes]." DEL ".$año;
    }

}
