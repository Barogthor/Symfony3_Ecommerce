<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Ecommerce\EcommerceBundle\Entity\Tva;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tva controller.
 *
 */
class TvaAdminController extends Controller
{
    /**
     * Lists all tva entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tvas = $em->getRepository('EcommerceBundle:Tva')->findAll();

        return $this->render('@Ecommerce/Admin/Pages/tva/index.html.twig', array(
            'tvas' => $tvas,
        ));
    }

    /**
     * Creates a new tva entity.
     *
     */
    public function newAction(Request $request)
    {
        $tva = new Tva();
        $form = $this->createForm('Ecommerce\EcommerceBundle\Form\TvaType', $tva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tva);
            $em->flush();

            return $this->redirectToRoute('admin_tva_show', array('id' => $tva->getId()));
        }

        return $this->render('@Ecommerce/Admin/Pages/tva/new.html.twig', array(
            'tva' => $tva,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a tva entity.
     *
     */
    public function showAction(Tva $tva)
    {
        $deleteForm = $this->createDeleteForm($tva);

        return $this->render('@Ecommerce/Admin/Pages/tva/show.html.twig', array(
            'tva' => $tva,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing tva entity.
     *
     */
    public function editAction(Request $request, Tva $tva)
    {
        $deleteForm = $this->createDeleteForm($tva);
        $editForm = $this->createForm('Ecommerce\EcommerceBundle\Form\TvaType', $tva);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_tva_edit', array('id' => $tva->getId()));
        }

        return $this->render('@Ecommerce/Admin/Pages/tva/edit.html.twig', array(
            'tva' => $tva,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a tva entity.
     *
     */
    public function deleteAction(Request $request, Tva $tva)
    {
        $form = $this->createDeleteForm($tva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tva);
            $em->flush();
        }

        return $this->redirectToRoute('admin_tva_index');
    }

    /**
     * Creates a form to delete a tva entity.
     *
     * @param Tva $tva The tva entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Tva $tva)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_tva_delete', array('id' => $tva->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
