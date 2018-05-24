<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Ecommerce\EcommerceBundle\Entity\Categories;
use Ecommerce\EcommerceBundle\Form\RechercheType;
use Ecommerce\EcommerceBundle\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProduitsController extends Controller
{

    /**
     * @param Request $request
     * @param Categories|null $categorie
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function produitsAction(Request $request, Categories $categorie = null)
    {


        $session = $request->getSession();
        if( $session->has('panier'))
            $panier = $session->get('panier');
        else
            $panier = false;


        $em = $this->getDoctrine()->getManager();

        if($categorie != null ){
            $findproduits = $em->getRepository("EcommerceBundle:Produits")->byCategorie($categorie);
        }
        else{

            $findproduits = $em->getRepository('EcommerceBundle:Produits')->findBy(array('disponible' => 1));
        }


        $produits  = $this->get('knp_paginator')->paginate(
            $findproduits, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            3
        );


        return $this->render('@Ecommerce/Default/index.html.twig', array(
            "produits" => $produits,
            'panier' => $panier,
        ));
    }

    public function presentationAction(Request $request, $id){

        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository("EcommerceBundle:Produits")->find($id);


        $session = $request->getSession();
        if( $session->has('panier'))
            $panier = $session->get('panier');
        else
            $panier = false;

        return $this->render('@Ecommerce/Default/produit.html.twig', array(
            "produit" => $produit,
            'panier' => $panier
        ));
    }
/*
    public function categorieAction($categorie){

        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository("EcommerceBundle:Produits")->byCategorie($categorie);


        $categorie = $em->getRepository("EcommerceBundle:Categories")->find($categorie);
        if(!$categorie) throw new NotFoundHttpException("Cette catégorie n'existe pas");
        return $this->render('@Ecommerce/Default/index.html.twig', array(
            "produits" => $produits
        ));

    }
*/
    public function rechercheAction(){

        $form = $this->createForm(RechercheType::class);

        return $this->render('recherche.html.twig', array(
            "form" => $form->createView()
        ));
    }

    public function rechercheTraitementAction(Request $request){

        $form = $this->createForm(RechercheType::class);
        $produits = null;
        $form->handleRequest($request);
        if($request->getMethod() == 'POST' && $form->isSubmitted()){

            $em = $this->getDoctrine()->getManager();

            dump($form);
            $produits = $em->getRepository('EcommerceBundle:Produits')->recherche($form->get('recherche')->getData());
        }



        return $this->render('@Ecommerce/Default/index.html.twig', array(
            'produits' => $produits
        ));
    }
}
