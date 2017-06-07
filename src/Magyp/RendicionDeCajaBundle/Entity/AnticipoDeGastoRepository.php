<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

class AnticipoDeGastoRepository extends EntityRepository
{
    public function qb_todas(){
        
	    $qb = $this->createQueryBuilder('antdg')
                    ->select('antdg');
                   
            return $qb;
        
    }
    public function qb_buscar($texto){
        
	    $qb = $this->createQueryBuilder('antdg')
                    ->select('antdg')
                    ->where("antdg.id > 1");
                    if(!is_null($texto)){
                        $qb->andWhere($qb->expr()->orX()->add("antdg.expediente LIKE :expediente")
                                ->add("antdg.nota LIKE :nota")
                                )
                        ->setParameter("expediente", '%' . $texto . '%')
                        ->setParameter("nota", '%' . $texto . '%');

                    }                     
                    ;
            
            return $qb;
    }    
}
