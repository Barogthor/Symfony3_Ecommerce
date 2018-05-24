<?php
/**
 * Created by PhpStorm.
 * User: florian
 * Date: 23/05/2018
 * Time: 17:09
 */

namespace Ecommerce\EcommerceBundle\Service;

use Doctrine\ORM\EntityManager;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;

class GetFacture
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

    }

    public function facture($facture){



        $html = $this->container->get('templating')->render('@Utilisateurs/facturePDF.html.twig', array(
            'facture'  => $facture
        ));

        return new PdfResponse(
            $this->container->get('knp_snappy.pdf')->getOutputFromHtml($html),
            'facture_'.$facture->getReference().'.pdf'
        );

    }
}