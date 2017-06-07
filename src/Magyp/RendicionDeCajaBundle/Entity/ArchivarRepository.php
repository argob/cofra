<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

class ArchivarRepository extends EntityRepository
{
    
    public function buscar($texto) {	    
		$qb = $this->createQueryBuilder('arch')
				->select('arch')		
				->join("arch.rendicion", 'ren')
				->join("arch.liquidacion", 'liq')
				->where("ren.expediente LIKE :texto")
				->setParameter("texto", '%' . $texto . '%')
			;
		return $qb->getQuery()->getResult();
		
	}       
    public function qb_archivar($texto = null){	    
		$qb = $this->createQueryBuilder('arch')
				->select('arch')		
				->leftJoin("arch.rendicion", 'ren')
				->leftJoin("arch.liquidacion", 'liq');
				if(!is_null($texto)){
				$qb->where("ren.expediente LIKE :texto")
				->setParameter("texto", '%' . $texto . '%');
				$qb->orWhere("arch.caja LIKE :caja")
				->setParameter("caja", '%' . $texto . '%');
				}
				$qb->orderBy("arch.id", "DESC")
			;
		return $qb;
		
	}       

}
