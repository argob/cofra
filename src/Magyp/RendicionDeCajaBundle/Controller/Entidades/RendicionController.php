<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Rendicion;
use Magyp\RendicionDeCajaBundle\Form\RendicionType;

/**
 * Rendicion controller.
 *
 * @Route("/rendicion")
 */
class RendicionController extends Controller
{
    /**
     * Lists all Rendicion entities.
     *
     * @Route("/", name="rendicion")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Rendicion entity.
     *
     * @Route("/{id}/show", name="rendicion_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rendicion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Rendicion entity.
     *
     * @Route("/new", name="rendicion_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Rendicion();
        $form   = $this->createForm(new RendicionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Rendicion entity.
     *
     * @Route("/create", name="rendicion_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Rendicion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Rendicion();
        $form = $this->createForm(new RendicionType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('rendicion_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Rendicion entity.
     *
     * @Route("/{id}/edit", name="rendicion_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rendicion entity.');
        }

        $editForm = $this->createForm(new RendicionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Rendicion entity.
     *
     * @Route("/{id}/update", name="rendicion_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Rendicion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rendicion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new RendicionType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('rendicion_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Rendicion entity.
     *
     * @Route("/{id}/delete", name="rendicion_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Rendicion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('rendicion'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
	
	/**     
     * @Route("/datosdeprueba/{desde}/{hasta}", name="rendicion_datosdeprueba")
     * 
     */
    public function datosdepruebaAction($desde,$hasta)
    {	
		$rendiciones = array();
		
		$em = $this->getDoctrine()->getManager();
		for($i=$desde;$i<$hasta;$i++){
			$rendicion = new Rendicion();
			$rendicion->setFecha(new \DateTime());
			$responsable = $em->getRepository("MagypRendicionDeCajaBundle:Usuario")->findOneByUsername('asd');
			
			$rendicion->setResponsable($responsable);
			$subresponsable = $em->getRepository("MagypRendicionDeCajaBundle:Usuario")->findOneByUsername('fer1');
			$rendicion->setSubresponsable($subresponsable);		
			$rendicion->setTotalRendicion(rand(100, 1000));
			$aux=str_pad(5000+$i,7, "0", STR_PAD_LEFT);
			$rendicion->setExpediente("S01:{$aux}/2012");
			
			$rendiciones[] = $rendicion;
			
			
		}
		
		//var_dump($rendicion);
		for($i=$desde,$n=0;$i<$hasta;$i++,$n++){
				echo $rendiciones[$n]->getExpediente () . "<br>";
		
				$em->persist($rendiciones[$n]);
		}		
		//$em->persist($rendicion);
		$em->flush();
		
		return new \Symfony\Component\HttpFoundation\Response();
	}
}
