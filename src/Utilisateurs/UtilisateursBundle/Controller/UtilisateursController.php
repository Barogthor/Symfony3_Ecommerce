<?php

namespace Utilisateurs\UtilisateursBundle\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UtilisateursController extends Controller
{

    public function facturesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $facture = $em->getRepository('EcommerceBundle:Commandes')->byFacture($this->getUser());
dump($facture);
        return $this->render("@Utilisateurs/facture.html.twig", array(
            'factures' => $facture
        ));
    }

    public function facturesPDFAction(Request $request, $id){

        $em = $this->getDoctrine()->getManager();
        $facture = $em->getRepository('EcommerceBundle:Commandes')->findOneBy(array(
            'utilisateur' => $this->getUser(),
            'valider' => 1,
            'id' => $id
        ));

        if(!$facture){
            $request->getSession()->getFlashBag()->add('error','Une erreur est survenue.');
            return $this->redirect($this->generateUrl('factures'));
        }


        return $this->container->get('setNewFacture')->facture($facture);
    }
}
