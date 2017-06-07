<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

class FondoRotatorioRepository extends EntityRepository
{
    public function qb_todas(){
        
	    $qb = $this->createQueryBuilder('fr')
                    ->select('fr')
                    ->orderBy('fr.id', 'DESC');
            return $qb;
    }

    public function qb_buscar($texto){
        
	    $qb = $this->createQueryBuilder('fr')	    
                    ->select('fr')
                    ->where("fr.id > 1")
                    ->orderBy('fr.id', 'DESC');
                    if(!is_null($texto)){
                        $qb->andWhere($qb->expr()->orX()->add("fr.expediente LIKE :expediente")
                                ->add("fr.nota LIKE :nota")
                                )
                        ->setParameter("expediente", '%' . $texto . '%')
                        ->setParameter("nota", '%' . $texto . '%');
                    }  
                    
                    ;
            
            return $qb;
            
    }
}
