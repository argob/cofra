<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

class AreaRepository extends EntityRepository
{
    public function qb_destino(){
	
 
        $areas = $this->qb_areas();
	return $areas;
	    ;
    }
    /**
     * Obtener el area de Administracion Financiera, mediante este metodo. Siempre!!!
     * 
     * @return Area
     */
    public function getAF() {	    
		$qb = $this->createQueryBuilder('a')
				->select('a')				
				->where("a.id = 1000")	// setear con el id del area de administracion financiera.
			;
		return $qb->getQuery()->getSingleResult();
		
	}
   
    public function qb_AreasConExpedientes($estado, $idarea = null, $baja = 0) {	    
    
		$qb = $this->createQueryBuilder('a')
				->select('a','count(ren)')
				->innerJoin('a.rendiciones', 'ren') //, 'ren.estado = 2'
				->where("a.id <> 1000")	
				->andWhere("ren.estado = :estado")
				->setParameter("estado", $estado)
				->groupBy("a.id")
                ->orderBy("a.nombre", "ASC")
			;
            if ($baja != 2){
                $qb->andWhere("a.borrado = :baja")
                ->setParameter("baja", $baja);
            }
                return $qb;
    }                
    
    public function getAreasConExpedientes($estado, $idarea = null, $baja = 0) {	
                $qb=$this->qb_AreasConExpedientes($estado);
		return $qb->getQuery()->getResult();
		
	}
    public function getAreasConExpedientesEnviados($idarea = null, $baja = 0) {	 
                $qb=$this->qb_AreasConExpedientes(Estado::ENVIADO, $idarea, $baja);
                return $qb->getQuery()->getResult();
		
	}
    public function getAreasConExpedientesAceptados($idarea = null, $baja = 0) {	    
		$qb=$this->qb_AreasConExpedientes(Estado::ACEPTADO, $idarea, $baja);
		return $qb->getQuery()->getResult();
	}
    public function qb_AreasConExpedientesEnviados($idarea = null, $baja = 0) {	 
                $qb=$this->qb_AreasConExpedientes(Estado::ENVIADO, $idarea, $baja);
                return $qb;
		
	}
    public function qb_AreasConExpedientesAceptados($idarea = null, $baja = 0) {	    
		$qb=$this->qb_AreasConExpedientes(Estado::ACEPTADO, $idarea, $baja);
		return $qb;
	}
        
    public function qb_AreasConExpedientesAtesoreria($idarea = null, $baja = 0) {	 
                $qb=$this->qb_AreasConExpedientes(Estado::ATESORERIA, $idarea, $baja);
                return $qb;
		
	}
        
    public function qb_AreasConExpedientesArchivada($idarea = null, $baja = 2) {	 
                $qb=$this->qb_AreasConExpedientes(Estado::ARCHIVADA, $idarea, $baja);
                return $qb;
		
	}
        
    public function qb_AreasConExpedientesAapagar($idarea = null, $baja = 2) {	 
        $qb=$this->qb_AreasConExpedientes(Estado::APAGAR, $idarea, $baja);
        return $qb;
    }
    
    public function qb_AreasConExpedientesApagado($idarea = null, $baja = 0) {	 
        $qb=$this->qb_AreasConExpedientes(Estado::PAGADO, $idarea, $baja);
        return $qb;
    }
    
                
    public function buscar($texto) {	    
		$qb = $this->createQueryBuilder('a')
				->select('a')				
				->where("a.nombre LIKE :texto")
                                ->setParameter("texto", '%' . $texto . '%')
                                ->where('a.borrado = 0')
			;
		return $qb->getQuery()->getResult();
		
	}
    
    public function buscar_cerradas($texto) {	    
		$qb = $this->createQueryBuilder('a')
				->select('a')				
				->where("a.nombre LIKE :texto")
                                ->setParameter("texto", '%' . $texto . '%')
                                ->where('a.borrado = 1')
			;
		return $qb->getQuery()->getResult();
		
	}       
    
    public function qb_areas($texto = null){	
		$qb = $this->createQueryBuilder('a')
				->select('a','resp','subresp','prog','act')				
				//->select('a','prog','act')				
				//->select('a','ug')				
                                ->leftJoin("a.responsable", "resp")
                                ->leftJoin("a.subresponsable", "subresp")
                                ->join("a.programa", "prog")
                                ->join("a.actividad", "act")
                                ->join("a.ff", "ff")
                                ->join("a.ug", "ug")
                                ->where('a.borrado = 0');
                                if(!is_null($texto)){
                                $qb->andWhere("a.nombre LIKE :texto")
                                ->setParameter("texto", '%' . $texto . '%');
                                }
                                $qb->orderBy("a.nombre", "ASC")
			;
		return $qb;
		
	}       

    public function qb_AreasDisponibles($idarea = null) {	    
    
		$qb = $this->createQueryBuilder('a')
            ->select('a')
            ->LeftJoin("MagypRendicionDeCajaBundle:Usuario", "u", 'WITH', 'u.id = a.responsable')
            ->where("a.responsable is null")
            ->andWhere('a.borrado = 0')
            //->AndWhere("a.responsable.area.id = areactual")
            //->setParameter("areactual", $idarea)
            ->orderBy("a.nombre", "ASC");
        return $qb;
    }      
    
    public function qb_areas_cerradas($texto = null){	    
		$qb = $this->createQueryBuilder('a')
				->select('a','resp','subresp','prog','act')				
				//->select('a','prog','act')				
				//->select('a','ug')				
                                ->leftJoin("a.responsable", "resp")
                                ->leftJoin("a.subresponsable", "subresp")
                                ->join("a.programa", "prog")
                                ->join("a.actividad", "act")
                                ->join("a.ff", "ff")
                                ->join("a.ug", "ug")
                                ->where('a.borrado = 1');
                                if(!is_null($texto)){
                                $qb->andWhere("a.nombre LIKE :texto")
                                ->setParameter("texto", '%' . $texto . '%');
                                }
                                $qb->orderBy("a.nombre", "ASC")
			;
		return $qb;
		
	}      
        
    public function qb_areas_tesoreria(){
        $qb = $this->createQueryBuilder('a')
                        ->select('a')                
                        ->where("a.borrado = 0")				
                        ;
        $qb->andWhere("a.nombre not LIKE :texto")
                      ->setParameter("texto", '%' . 'cerrada' . '%');
        $qb->andWhere("a.nombre LIKE :texto2")
                      ->setParameter("texto2", '%' . 'tesoreria' . '%');
        return $qb->getQuery()->getResult();
    }
}
