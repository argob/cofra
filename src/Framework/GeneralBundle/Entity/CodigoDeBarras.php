<?php

namespace Framework\GeneralBundle\Entity;

class codigodebarras
{
    
    private $codigodebarras;

    /**
     * get codigodebarras
     * @param integer $actividadId
     * @return String
     */
   
    public function getCodigoDeBarras( $reporteTipo, $id)
    {
        $nCantDigitos= strlen($id);
        $reporteTipo= str_pad ( $reporteTipo, 2 , "0", STR_PAD_LEFT);   
        $nCRC= crc32($reporteTipo.$nCantDigitos.$id);
        $nCRC= sprintf("%u", $nCRC);
        $nCRC= str_pad ( $nCRC, 10 , "0", STR_PAD_LEFT);
        $this->codigodebarras= $reporteTipo.$nCantDigitos.$id.$nCRC;
        return $this->codigodebarras;
    }
    
    public function esValidoCodigoDeBarras( $cCodigoDeBarras)
    {
        //$cCodigoDeBarras= str_pad ( $cCodigoDeBarras, 15 , "0", STR_PAD_LEFT);
        $reporteTipo= substr ($cCodigoDeBarras, 0, 2 );
        $reporteTipo= str_pad ( $reporteTipo, 2 , "0", STR_PAD_LEFT);
        $nCantDigitos= substr ($cCodigoDeBarras, 2, 1 );
        $id= substr ($cCodigoDeBarras, 3, $nCantDigitos );
        
        $nCRC= crc32($reporteTipo.$nCantDigitos.$id);
        $nCRC= sprintf("%u", $nCRC);
        $nCRC= str_pad ( $nCRC, 10 , "0", STR_PAD_LEFT);

        return ( $cCodigoDeBarras === $reporteTipo.$nCantDigitos.$id.$nCRC );

    }

}
