<?php
/**
 * Created by PhpStorm.
 * User: florian
 * Date: 15/05/2018
 * Time: 13:50
 */

namespace Ecommerce\EcommerceBundle\Controller;


use Ecommerce\EcommerceBundle\Form\UtilisateursAdressesType;
use Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses;
use Ecommerce\EcommerceBundle\Entity\Commandes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Utilisateurs\UtilisateursBundle\Entity\Utilisateurs;

class PanierController extends Controller
{

    public function menuAction(Request $request){
        $session = $request->getSession();
        if(!$session->has('panier'))
            $article = 0;
        else
            $article = count($session->get('panier'));

        return $this->render('panier.html.twig',array(
           'article' => $article
        ));
    }

    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        if( !$session->has('panier')) $session->set('panier',array());
        $panier = $session->get('panier');

        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('EcommerceBundle:Produits')->findIn(array_keys($panier));
        //dump($panier);
        if(count($produits) < count($panier))
            $panier = array();
        $session->set('panier',$panier);

        return $this->render('@Ecommerce/Default/panier.html.twig', array(
            'produits' => $produits,
            'panier' => $panier
        ));
    }

    public function livraisonAction(Request $request)
    {
        $utilisateur = $this->container->get('security.token_storage')->getToken()->getUser();
        $entity = new UtilisateursAdresses();
        $form = $this->createForm(UtilisateursAdressesType::class,$entity);

        $form->handleRequest($request);
        if($request->getMethod() == "POST"){
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $entity->setUtilisateur($utilisateur);
                $em->persist($entity);
                $em->flush();
                return $this->redirect($this->generateUrl('livraison'));
            }
        }

        return $this->render('@Ecommerce/Default/livraison.html.twig', array(
            'utilisateur' => $utilisateur,
            'form' => $form->createView()
        ));
    }


    public function supprimerAdresseAction(Request $request, $id){

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->find($id);

        /*
        echo $this->container->get('security.token_storage')->getToken()->getUser()->getId().":".$entity->getUtilisateur()->getId();
        var_dump($this->container->get('security.token_storage')->getToken()->getUser()->getId() != $entity->getUtilisateur()->getId());
        var_dump($this->container->get('security.token_storage')->getToken()->getUser() != $entity->getUtilisateur());
        var_dump(!$entity);
        die();*/


        if( $this->container->get('security.token_storage')->getToken()->getUser() != $entity->getUtilisateur() || !$entity)
            return $this->redirect($this->generateUrl('livraison'));


        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('livraison'));
    }

    private function setLivraisonOnSession(Request $request){
        $session = $request->getSession();

        if( !$session->has('adresse')) $session->set('adresse',array());
        $adresse = $session->get('adresse');

        if($request->request->get('livraison') != null && $request->request->get('facturation') != null){
            $adresse['livraison'] = $request->request->get('livraison');
            $adresse['facturation'] = $request->request->get('facturation');
        }
        else{
            return $this->redirect($this->generateUrl('validation'));
        }

        $session->set('adresse',$adresse);
        return $this->redirect($this->generateUrl('validation'));
    }

    public function validationAction(Request $request)
    {
        if( $request->getMethod() == "POST")
            $this->setLivraisonOnSession($request);




        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        /*
        $adresse = $session->get('adresse');
        $produits = $em->getRepository('EcommerceBundle:Produits')->findIn(array_keys($session->get('panier')));
        $livraison = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->find($adresse['livraison']);
        $facturation = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->find($adresse['facturation']);
*/

        $prepareCommande = $this->forward('EcommerceBundle:Commandes:prepareCommande');
        $commande = $em->getRepository('EcommerceBundle:Commandes')->find($prepareCommande->getContent());

dump($commande);


        return $this->render('@Ecommerce/Default/validation.html.twig', array(/*
            'produits' => $produits,
            'livraison' => $livraison,
            'facturation' => $facturation,
            'panier' => $session->get('panier')*/
            'commande' => $commande
        ));
    }

    public function ajouterAction(Request $request, $id){

        $session = $request->getSession();
        if( !$session->has('panier')) $session->set('panier',array());

        $panier = $session->get('panier');
        if( array_key_exists($id,$panier) ){
            if($request->query->get('qte') != null){
                $panier[$id] = ( $request->query->get('qte') );
                $session->getFlashBag()->add('success','Quantité modifié avec succès');
            }

        }
        else{
            if($request->query->get('qte') != null)
                $panier[$id] = ( $request->query->get('qte') );
            else
                $panier[$id] = 1;
            $session->getFlashBag()->add('success','Article ajouté avec succès');
        }
        $session->set('panier', $panier);



        return $this->redirect($this->generateUrl('panier'));
    }

    public function supprimerAction(Request $request, $id){

        $session = $request->getSession();
        $panier = $session->get('panier');

        if(array_key_exists($id,$panier))
            unset($panier[$id]);
        $session->set('panier', $panier);

        $session->getFlashBag()->add('success','Article supprimé avec succès');

        return $this->redirect($this->generateUrl('panier'));
    }
}

