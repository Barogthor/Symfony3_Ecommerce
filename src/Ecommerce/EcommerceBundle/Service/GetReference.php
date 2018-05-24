<?php
/**
 * Created by PhpStorm.
 * User: florian
 * Date: 23/05/2018
 * Time: 17:09
 */

namespace Ecommerce\EcommerceBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;

class GetReference
{
    public function __construct($securityContext, EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->securityContext = $securityContext;
    }

    public function reference(){
        $reference = $this->em->getRepository('EcommerceBundle:Commandes')->findOneBy(array('valider' => 1), array('id' => 'DESC'), 1, 1);

        if(!$reference)
            return 1;
        else
            return $reference->getReference() + 1;
    }
}