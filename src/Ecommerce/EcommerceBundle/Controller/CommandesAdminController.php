<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Ecommerce\EcommerceBundle\Entity\Commandes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Commande controller.
 *
 */
class CommandesAdminController extends Controller
{
    /**
     * Lists all commande entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $commandes = $em->getRepository('EcommerceBundle:Commandes')->findAll();

        return $this->render('@Ecommerce/Admin/Pages/commandes/index.html.twig', array(
            'commandes' => $commandes,
        ));

    }

    /**
     * Finds and displays a commande entity.
     *
     */
    public function showAction(Commandes $commande)
    {

        return $this->container->get('setNewFacture')->facture($commande);
    }

    /**
     * Creates a new commande entity.
     *
     */
    /*
    public function newAction(Request $request)
    {
        $commande = new Commandes();
        $form = $this->createForm('Ecommerce\EcommerceBundle\Form\CommandesType', $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

            return $this->redirectToRoute('admin_commandes_show', array('id' => $commande->getId()));
        }

        return $this->render('@Ecommerce/Admin/Pages/commandes/new.html.twig', array(
            'commande' => $commande,
            'form' => $form->createView(),
        ));
    }
*/


    /**
     * Displays a form to edit an existing commande entity.
     *
     */
    /*
    public function editAction(Request $request, Commandes $commande)
    {
        $deleteForm = $this->createDeleteForm($commande);
        $editForm = $this->createForm('Ecommerce\EcommerceBundle\Form\CommandesType', $commande);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_commandes_edit', array('id' => $commande->getId()));
        }

        return $this->render('@Ecommerce/Admin/Pages/commandes/edit.html.twig', array(
            'commande' => $commande,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
*/
    /**
     * Deletes a commande entity.
     *
     */
    /*
    public function deleteAction(Request $request, Commandes $commande)
    {
        $form = $this->createDeleteForm($commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commande);
            $em->flush();
        }

        return $this->redirectToRoute('admin_commandes_index');
    }
*/
    /**
     * Creates a form to delete a commande entity.
     *
     * @param Commandes $commande The commande entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    /*
    private function createDeleteForm(Commandes $commande)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_commandes_delete', array('id' => $commande->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }*/
}
