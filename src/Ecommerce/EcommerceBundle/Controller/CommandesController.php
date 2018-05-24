<?php
/**
 * Created by PhpStorm.
 * User: florian
 * Date: 15/05/2018
 * Time: 13:50
 */

namespace Ecommerce\EcommerceBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Session;
use Ecommerce\EcommerceBundle\Entity\Commandes;
use Ecommerce\EcommerceBundle\Repository\CommandesRepository;



class CommandesController extends Controller
{
    private function facture(Request $request){

        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $adresse = $session->get('adresse');
        $panier = $session->get('panier');
        $commande = array();
        $totalHT = 0;
        $totalTVA = 0;

        $facturation = $em->getRepository("EcommerceBundle:UtilisateursAdresses")->find($adresse['facturation']);
        $livraison = $em->getRepository("EcommerceBundle:UtilisateursAdresses")->find($adresse['livraison']);
        $produits = $em->getRepository("EcommerceBundle:Produits")->findIn(array_keys($panier));

        foreach ($produits as $produit) {
            $prixHT = ($produit->getPrix() * $panier[$produit->getId()]);
            $prixTTC = $prixHT / $produit->getTva()->getMultiplicate();
            $totalHT += $prixHT;

            if( !isset($commande['tva']['%'.$produit->getTva()->getValeur()])) {
                $commande['tva']['%' . $produit->getTva()->getValeur()] = round($prixTTC - $prixHT, 2);

            }
            else {
                $commande['tva']['%' . $produit->getTva()->getValeur()] += round($prixTTC - $prixHT, 2);
            }
            $totalTVA += round($prixTTC-$prixHT,2);

            $commande['produit'][$produit->getId()] = array(
                'reference' => $produit->getNom(),
                'quantite' => $panier[$produit->getId()],
                'prixHT' => round($produit->getPrix(),2),
                'prixTTC' => round($produit->getPrix() / $produit->getTva()->getMultiplicate(),2)
            );


        }
        $commande['livraison'] = array(
            'prenom' => $livraison->getPrenom(),
            'nom' => $livraison->getNom(),
            'telephone' => $livraison->getTelephone(),
            'adresse' => $livraison->getAdresse(),
            'cp' => $livraison->getCp(),
            'ville' => $livraison->getVille(),
            'pays' => $livraison->getPays(),
            'complement' => $livraison->getComplement()
        );

        $commande['facturation'] = array(
            'prenom' => $facturation->getPrenom(),
            'nom' => $facturation->getNom(),
            'telephone' => $facturation->getTelephone(),
            'adresse' => $facturation->getAdresse(),
            'cp' => $facturation->getCp(),
            'ville' => $facturation->getVille(),
            'pays' => $facturation->getPays(),
            'complement' => $facturation->getComplement()
        );
        $commande['totalHT'] = round($totalHT,2);
        $commande['totalTTC'] = round($totalHT+$totalTVA,2);
        $commande['token'] = bin2hex(random_bytes(20));

        return $commande;
    }



    public function prepareCommandeAction(Request $request){

        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        if(!$session->has('commande'))
            $commande = new Commandes();
        else
            $commande = $em->getRepository("EcommerceBundle:Commandes")->find($session->get('commande'));

        $commande->setDate(new \DateTime());
        $commande->setUtilisateur($this->container->get('security.token_storage')->getToken()->getUser() );
        $commande->setValider(0);
        $commande->setReference(0);
        $commande->setCommandes($this->facture($request));

        if( !$session->has('commande')) {
            $em->persist($commande);
            $session->set('commande',$commande);

        }
        $em->flush();


        return new Response($commande->getId());
    }

    // cette methode remplace l'api banque
    public function validationCommandeAction(Request $request, $id){

        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository("EcommerceBundle:Commandes")->find($id);

        if(!$commande || $commande->getValider() == 1)
            throw $this->createNotFoundException("La commande n'existe pas.");

        $commande->setValider(1);
        $commande->setReference($this->container->get('setNewReference')->reference());
        $em->flush();

        $session->remove('adresse');
        $session->remove('panier');
        $session->remove('commande');

        $session->getFlashBag()->add("success", "Votre commande a été validé avec succès.");
        return $this->redirect($this->generateUrl("factures"));

    }

}