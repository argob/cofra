<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

class ReintegroDeGastoRepository extends EntityRepository
{
    public function qb_todas(){
        
	    $qb = $this->createQueryBuilder('rdg')
                    ->select('rdg');
                   
            return $qb;
        
    }
    
    public function qb_buscar($texto){
        
	    $qb = $this->createQueryBuilder('rdg')	    
                    ->select('rdg')
                    ->where("rdg.id > 1");
                    if(!is_null($texto)){
                        $qb->andWhere($qb->expr()->orX()->add("rdg.expediente LIKE :expediente")
                                ->add("rdg.nota LIKE :nota")
                                )
                        ->setParameter("expediente", '%' . $texto . '%')
                        ->setParameter("nota", '%' . $texto . '%');

                    }                     
                    ;
            
            return $qb;
            
    }    
}
