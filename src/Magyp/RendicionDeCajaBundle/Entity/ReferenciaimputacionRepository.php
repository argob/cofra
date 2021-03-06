<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
  *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReferenciaimputacionRepository extends EntityRepository
{
//
//	public function like($dato) {
//
//		$qb = $this->createQueryBuilder('p')
//				->select('p')
//				->where('p.descripcion LIKE :dato')
//				->setParameter("dato", '%' . $dato . '%')
//				->setMaxResults(100);
//		;
//
//		return $qb->getQuery()->getResult();
//	}
//	
//	public function buscarProveedores() {
//	    $qb= $this->qb_buscarProveedores();
//	    return $qb->getQuery()->getResult();
//	}
//	
//	public function qb_buscarProveedores() {	    
//		$qb = $this->createQueryBuilder('p')
//				->select('p')
//				->where('p.borrado = 0')
//				->orderBy('p.descripcion');
//		return $qb;
//	}
//        
	public function buscarReferencias($palabrasclaves){
			
		//$palabras = explode(' ',$palabrasclaves);
		$palabras = str_replace(' ', '%', $palabrasclaves);
		$qb = $this->createQueryBuilder('ref')
				->select('ref','imp.descripcion','imp.id')
				->join("ref.imputacion", "imp")
				->where('ref.descripcion LIKE :palabras')
				->setParameter("palabras", '%' . $palabras . '%')				
				//->groupBy("imp.id")				
			//	->groupBy("imp.id")				
				->orderBy("imp.codigo")				
				;
				
		return $qb->getQuery()->getResult();
		;
	}
//	public function seleccionar($lista){
//	     
//		$qb = $this->createQueryBuilder('pro')
//				->select('pro')                                
//				->where("pro.cuit IN (:lista)")
//			
//				->setParameter("lista", $lista);
//				;
//				
//				
//		return $qb->getQuery()->getResult();
//		;
//	}
//
//	public function qbBuscar($buscar){
//		$qb= $this->createQueryBuilder('p')
//				    ->select('p')
//				    ->where('p.borrado = 0');
//				    if($buscar != ''){
//                                        $qb->andWhere($qb->expr()->orX()->add("p.descripcion LIKE :descripcion")
//                                                ->add("p.cuit LIKE :cuit")
//                                                )
//                                        ->setParameter("descripcion", '%' . $buscar . '%')
//                                        ->setParameter("cuit", '%' . $buscar . '%');
//				    }
//				$qb->orderBy('p.descripcion');
//				    
//		return $qb->getQuery();
//	}
//	public function qbProveedor($id){
//	    //$buscar = 'Otros';
//		$qb= $this->createQueryBuilder('p')
//				    ->select('p')
//				    ->where('p.id = :id')
//				    ->setParameter("id",$id);				    
//		return $qb->getQuery();
//	}
//	
	
}
