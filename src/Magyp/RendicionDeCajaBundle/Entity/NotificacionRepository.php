<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Magyp\RendicionDeCajaBundle\Entity\Estado;

/**
 * NotificacionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NotificacionRepository extends EntityRepository {

    // trae las notificaciones para el usuario, y las q son para todos.
    public function paraUsuario($usuario) {

        $qb = $this->createQueryBuilder('nov')
                ->select('nov')
                ->where("nov.destino = :destino") // las q son para mi
                ->setParameter("destino", $usuario->getArea()->getId())
                ->orWhere("nov.destino = -1")  // o las q son para todos	
        ;

        return $qb->getQuery()->getResult();
        ;
    }

    public function qb_destino() {
        $areas = $this->getManager("MagypRendicionDeCajaBundle:Area")->findAll();
        $areas[] = new Area("nombre", "-1");
        return $areas;
        ;
    }

    public function misnotificaciones($usuario) {
        $qb = $this->createQueryBuilder('nov')
                ->select('nov')
                ->where("nov.destino = :destino") // las q son para mi
                ->setParameter("destino", $usuario->getArea()->getId())
                //->orWhere("nov.destino = -1")		// o las q son para todos	
                ->orderBy("nov.fecha", "DESC")
        ;
        //  echo 'id es '. $usuario->getArea()->getId();
        return $qb->getQuery()->getResult();
        ;
    }

    public function notificacionesAF() {
        $qb = $this->createQueryBuilder('nov')
                ->select('nov')
                ->where("nov.destino = 1000") // el id de AF			
        ;
        return $qb->getQuery()->getResult();
        ;
    }

    public function notificacionesSalientes($usuario) {
        return $this->qbNotificacionesSalientes($usuario)->getResult();
    }

    public function notificacionesEntrantes($usuario) {
        return $this->qbNotificacionesEntrantes($usuario)->getResult();
    }

    public function qbNotificacionesSalientes($usuario, $texto = null) {
        $qb = $this->createQueryBuilder('noti')
                ->select('noti', 'd', 'u')
                ->join("noti.destino", "d")
                ->join("noti.usuario", "u")
                ->join("noti.areaDeOrigen", "o")
                ->where("noti.usuario = :usuario")
                ->setParameter("usuario", $usuario->getId())
                ->andWhere("noti.version = 2");
        if (!is_null($texto)) {
            $qb->andWhere($qb->expr()->orX()->add("noti.contenido LIKE :texto")
                            ->add("noti.asunto LIKE :asunto")
                    )
                    ->setParameter("texto", '%' . $texto . '%')
                    ->setParameter("asunto", '%' . $texto . '%');
        }
        $qb->orderBy("noti.id", "DESC")
        ;
        return $qb;
        ;
    }

    public function qbNotificacionesEntrantes($usuario, $texto = null) {
        $qb = $this->createQueryBuilder('noti')
                ->select('noti', 'd', 'u')
                ->join("noti.destino", "d")
                ->join("noti.usuario", "u")
                ->join("noti.areaDeOrigen", "o")
//                ->join("noti.rendicion", "r")
                ->where("noti.destino = :area")
                ->setParameter("area", $usuario->getArea()->getId())
                ->andWhere("noti.version = 2");
        if (!is_null($texto)) {
//                            $qb->andWhere("noti.contenido LIKE :texto")
//                            ->setParameter("texto", '%' . $texto . '%');
//                             $qb->orWhere("noti.asunto LIKE :asunto")
//                            ->setParameter("asunto", '%' . $texto . '%');
            $qb->andWhere($qb->expr()->orX()->add("noti.contenido LIKE :texto")
                            ->add("noti.asunto LIKE :asunto")
                    )
                    ->setParameter("texto", '%' . $texto . '%')
                    ->setParameter("asunto", '%' . $texto . '%');
        }
        $qb->andWhere($qb->expr()->in("noti.estadorendicion", array(ESTADO::NUEVO, ESTADO::ACEPTADO, ESTADO::ACORREGIR, ESTADO::APAGAR, ESTADO::PAGADO)));
        $qb->orderBy("noti.id", "DESC")


        ;
        return $qb;
        ;
    }

    public function buscarPorDestino($idarea, $orden) {
        $qb = $this->createQueryBuilder('nov')
                ->select('nov');
        if ($idarea != Notificacion::TODOS) {
            $qb->where("nov.destino = :area")
                    ->setParameter("area", $idarea);
        }
        $qb->orderBy("nov.fecha", $orden)
        ;
        return $qb->getQuery()->getResult();
        ;
    }

    public function TodasPorOrdenDescendente() {
        $qb = $this->createQueryBuilder('nov')
                ->select('nov');
        $qb->orderBy("nov.fecha", "DESC")
        ;
        return $qb->getQuery()->getResult();
        ;
    }

    public function qbNotificacionesSalientesAF($texto = null) {
        $qb = $this->createQueryBuilder('noti')
                ->select('noti', 'd', 'u')
                ->join("noti.destino", "d")
                ->join("noti.usuario", "u")
                ->join("noti.areaDeOrigen", "o")
                ->where("noti.areaDeOrigen = 1000")
                ->andWhere("noti.version = 2");
        //->setParameter("usuario", $usuario->getId());
        if (!is_null($texto)) {
            $qb->andWhere($qb->expr()->orX()->add("noti.contenido LIKE :texto")
                            ->add("noti.asunto LIKE :asunto")
                    )
                    ->setParameter("texto", '%' . $texto . '%')
                    ->setParameter("asunto", '%' . $texto . '%');
        }
        $qb->orderBy("noti.id", "DESC")
        ;
        return $qb;
        ;
    }

    public function qbNotificacionesEntrantesAF($texto = null) {
        $qb = $this->createQueryBuilder('noti')
                ->select('noti', 'd', 'u')
                ->join("noti.destino", "d")
                ->join("noti.usuario", "u")
                ->join("noti.areaDeOrigen", "o")
                ->where("noti.destino = :area")
                ->setParameter("area", 1000)
                ->andWhere("noti.version = 2");
        if (!is_null($texto)) {
            $qb->andWhere($qb->expr()->orX()->add("noti.contenido LIKE :texto")
                            ->add("noti.asunto LIKE :asunto")
                    )
                    ->setParameter("texto", '%' . $texto . '%')
                    ->setParameter("asunto", '%' . $texto . '%');
        }

        $qb->orderBy("noti.id", "DESC")


        ;
        return $qb;
        ;
    }

    public function qbTodas() {
        $qb = $this->createQueryBuilder('noti')
                ->select('noti', 'd', 'u')
                ->join("noti.destino", "d")
                ->join("noti.usuario", "u")
                ->join("noti.areaDeOrigen", "o")
                ->where("noti.version = 2");
        $qb->orderBy("noti.id", "DESC")
        ;
        return $qb;
        ;
    }

    public function qbBuscarEnTodas($texto) {
        $qb = $this->createQueryBuilder('noti')
                ->select('noti', 'd', 'u')
                ->join("noti.destino", "d")
                ->join("noti.usuario", "u")
                ->join("noti.areaDeOrigen", "o")
                ->where("noti.version = 2");
        if (!is_null($texto)) {
            $qb->andWhere($qb->expr()->orX()->add("noti.contenido LIKE :texto")
                            ->add("noti.asunto LIKE :asunto")
                    )
                    ->setParameter("texto", '%' . $texto . '%')
                    ->setParameter("asunto", '%' . $texto . '%');
        }
        $qb->orderBy("noti.id", "DESC")
        ;
        return $qb;
        ;
    }

    public function qbBuscarEnTodasPersonalizado($texto) {


        $opciones = $this->obtenerOpciones($texto);
        //var_dump($opciones);
        $qb = $this->createQueryBuilder('noti')
                ->select('noti', 'd', 'u')
                ->join("noti.destino", "d")
                ->join("noti.usuario", "u")
                ->join("noti.areaDeOrigen", "o")
                ->where("noti.version = 2");
        if (!empty($opciones['texto'])) {
            $expr = $qb->expr()->orX()->add("noti.contenido LIKE :texto")
                    ->add("noti.asunto LIKE :asunto");
            $qb->where($expr)
                    ->setParameter("texto", '%' . $opciones['texto'] . '%')
                    ->setParameter("asunto", '%' . $opciones['texto'] . '%');
        }
        if (!empty($opciones['usuario'])) {
            if (is_numeric($opciones['usuario'])) {
                $qb->andWhere("u.id = :usuario")
                        ->setParameter("usuario", $opciones['usuario']);
            } else {
                $qb->andWhere("u.username = :usuario")
                        ->setParameter("usuario", $opciones['usuario']);
            }
        }
        if (!empty($opciones['destino'])) {
            if (is_numeric($opciones['destino'])) {
                $qb->andWhere("d.id = :destino")
                        ->setParameter("destino", $opciones['destino']);
            } else {
                $qb->andWhere("d.nombre LIKE :destino")
                        ->setParameter("destino", '%' . $opciones['destino'] . '%');
            }
        }
        if (!empty($opciones['origen'])) {
            if (is_numeric($opciones['origen'])) {
                $qb->andWhere("o.id = :origen")
                        ->setParameter("destino", $opciones['origen']);
            } else {
                $qb->andWhere("o.nombre LIKE :origen")
                        ->setParameter("origen", '%' . $opciones['origen'] . '%');
            }
        }
        if (!empty($opciones['fecha'])) {
            $qb->andWhere("noti.fecha LIKE :fecha")
                    ->setParameter("fecha", '%' . $opciones['fecha'] . '%');
        }
        if (!empty($opciones['fechainicial'])) {
            $qb->andWhere("noti.fecha >= :fechainicial")
                    ->setParameter("fechainicial", $opciones['fechainicial']);
        }
        if (!empty($opciones['fechafinal'])) {
            $qb->andWhere("noti.fecha <= :fechafinal")
                    ->setParameter("fechafinal", $opciones['fechafinal'] . " 23:59:59");
        }
        $qb->orderBy("noti.id", "DESC")
        ;
        return $qb;
        ;
    }

    public function obtenerOpciones($buscado) {
        // if(!strpos($buscado,','))return array('texto'=> $buscado);

        $texto = explode(',', $buscado);
        $opciones = array();
        $opciones['texto'] = $texto[0];
        $array = explode(' ', $texto[1]);

        foreach ($array as $opcion) {
            $aux = explode(':', $opcion);
            switch ($aux[0]) {
                case "usuario":
                case "u":
                    $opciones['usuario'] = $aux[1];
                    break;
                case "destino":
                case "d":
                    $opciones['destino'] = $aux[1];
                    break;
                case "origen":
                case "o":
                    $opciones['origen'] = $aux[1];
                    break;
                case "fecha":
                case "f":
                    $opciones['fecha'] = $aux[1];
                    break;
                case "fechainicial":
                case "fi":
                    $opciones['fechainicial'] = $aux[1];
                    break;
                case "fechafinal":
                case "ff":
                    $opciones['fechafinal'] = $aux[1];
                    break;
                default:
                    break;
            }
        }
        return $opciones;
    }

    public function qbNotificacionesEntrantesTesoreria($usuario, $texto = null) {
        $qb = $this->createQueryBuilder('noti')
                ->select('noti', 'd', 'u')
                ->join("noti.destino", "d")
                ->join("noti.usuario", "u")
                ->join("noti.areaDeOrigen", "o");
//                ->join("noti.rendicion", "r");
        //->where("noti.destino = :area")
        //->setParameter("area", $usuario->getArea()->getId());
        if (!is_null($texto)) {
//                            $qb->andWhere("noti.contenido LIKE :texto")
//                            ->setParameter("texto", '%' . $texto . '%');
//                             $qb->orWhere("noti.asunto LIKE :asunto")
//                            ->setParameter("asunto", '%' . $texto . '%');
            $qb->andWhere($qb->expr()->orX()->add("noti.contenido LIKE :texto")
                            ->add("noti.asunto LIKE :asunto")
                    )
                    ->setParameter("texto", '%' . $texto . '%')
                    ->setParameter("asunto", '%' . $texto . '%');
        }


//        $qb->where($qb->expr()->orX()->add("noti.asunto LIKE '%PAGAR%'")
//                        ->add("noti.asunto LIKE '%PAGADO%'")
//                        ->add("noti.asunto LIKE '%TESORERIA%'")
//                        ->add("noti.asunto LIKE '%ARCHIVADA%'")
//        )
        $qb->where($qb->expr()->in("noti.estadorendicion", array(ESTADO::ACEPTADO, ESTADO::ATESORERIA, ESTADO::APAGAR, ESTADO::PAGADO)));
        $qb->andWhere($qb->expr()->orX()->add("noti.version = 2"));

        $qb->orderBy("noti.id", "DESC")

        ;
        return $qb;
        ;
    }

}
