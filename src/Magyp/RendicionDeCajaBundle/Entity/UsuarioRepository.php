<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UsuarioRepository extends EntityRepository
{
	
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
//
//		$qb = $this->createQueryBuilder('p')
//				->select('p')
//				->where('p.borrado = 0');
//
//		return $qb->getQuery()->getResult();
//	}
//        
        public function buscar($texto){

		$qb = $this->createQueryBuilder('u')
				->select('u')
				->where("u.nombre LIKE :texto")
                                ->orWhere("u.apellido LIKE :texto")
                                ->orWhere("u.email LIKE :texto")
                                ->orWhere("u.username LIKE :texto")
                                ->setParameter("texto", '%' . $texto . '%')
				;
				
		return $qb->getQuery()->getResult();
		;
	}
        public function qb_Buscar($texto){

		$qb = $this->createQueryBuilder('u')
				->select('u')
				->join("u.area", "area")
				->where("u.nombre LIKE :texto")
                                ->orWhere("u.apellido LIKE :texto")
                                ->orWhere("u.email LIKE :texto")
                                ->orWhere("u.username LIKE :texto")
                                ->setParameter("texto", '%' . $texto . '%')
				;
				
		return $qb;
	}
        public function qb_todos(){

		$qb = $this->createQueryBuilder('u')
				->select('u','area')
				->join("u.area", "area")
				;
				
		return $qb;
	}
        
        public function qb_Usuarios($texto = null){

                if(!is_null($texto)) $qb = $this->qb_Buscar ($texto);
		else $qb = $this->qb_todos();
                
                return $qb;
	}
    
    public function qb_todas_ordenadas(){

		$qb = $this->createQueryBuilder('u')
				->select('u')
                ->orderBy("u.username")
				;
				
		return $qb;
	}
    
    public function qb_responsables_disponibles(){

		$qb = $this->createQueryBuilder('u')
				->select('u')
                /*->LeftJoin("MagypRendicionDeCajaBundle:Area", "a", 'WITH', 'a.responsable = u.id')
                ->LeftJoin("MagypRendicionDeCajaBundle:Area", "b", 'WITH', 'b.subresponsable = u.id')
                ->where("a.responsable is null")
                ->andwhere("b.subresponsable is null")
                ->orwhere("a.nombre like '%tesoreria%'")
                ->orwhere("b.nombre like '%tesoreria%'")*/
                ->orderBy("u.username")
				;
        //echo ($qb->getQuery()->getSql());
        //die("");
		return $qb;
	}
    
    public function qb_subresponsables_disponibles(){

		$qb = $this->createQueryBuilder('u')
				->select('u')
                /*->LeftJoin("MagypRendicionDeCajaBundle:Area", "a", 'WITH', 'a.subresponsable = u.id')
                ->LeftJoin("MagypRendicionDeCajaBundle:Area", "b", 'WITH', 'b.responsable = u.id')
                ->where("a.subresponsable is null")
                ->andwhere("b.responsable is null")
                ->orwhere("a.nombre like '%tesoreria%'")
                ->orwhere("b.nombre like '%tesoreria%'")*/
                ->orderBy("u.username")
				;
		return $qb;
	}

    public function qb_responsables_disponibles_preset($resp){

		$qb = $this->createQueryBuilder('u')
				->select('u')
                /*->LeftJoin("MagypRendicionDeCajaBundle:Area", "a", 'WITH', 'a.responsable = u.id')
                ->LeftJoin("MagypRendicionDeCajaBundle:Area", "b", 'WITH', 'b.subresponsable = u.id')
                ->where("a.responsable is null")
                ->andwhere("b.subresponsable is null")
                ->orwhere("u.id = :resp")
                ->orwhere("a.nombre like '%tesoreria%'")
                ->orwhere("b.nombre like '%tesoreria%'")
                ->setParameter("resp", $resp*/
                ->orderBy("u.username")
				;
        //echo ($qb->getQuery()->getSql());
        //die("");
		return $qb->getQuery()->getResult();
	}
    
        public function qb_subresponsables_disponibles_preset($subresp){

		$qb = $this->createQueryBuilder('u')
				->select('u')
                /*->LeftJoin("MagypRendicionDeCajaBundle:Area", "a", 'WITH', 'a.responsable = u.id')
                ->LeftJoin("MagypRendicionDeCajaBundle:Area", "b", 'WITH', 'b.subresponsable = u.id')
                ->where("a.responsable is null")
                ->andwhere("b.subresponsable is null")
                ->orwhere("u.id = :subresp")
                ->orwhere("a.nombre like '%tesoreria%'")
                ->orwhere("b.nombre like '%tesoreria%'")
                ->setParameter("subresp", $subresp*/
                ->orderBy("u.username")
				;
        //echo ($qb->getQuery()->getSql());
        //die("");
		return $qb->getQuery()->getResult();
	}
}
