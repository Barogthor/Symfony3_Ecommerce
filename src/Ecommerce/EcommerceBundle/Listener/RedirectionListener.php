<?php
/**
 * Created by PhpStorm.
 * User: florian
 * Date: 22/05/2018
 * Time: 11:53
 */

namespace Ecommerce\EcommerceBundle\Listener;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;



class RedirectionListener
{
    public function __construct(ContainerInterface $container, Session $session)
    {
        $this->session = $session;
        $this->router = $container->get('router');
        $this->securityContext = $container->get('security.authorization_checker');
    }

    public function onKernelRequest(GetResponseEvent $event){
        $route = $event->getRequest()->attributes->get('_route');

        if( ($route == "livraison" || $route == "validation") && $this->session->has('panier')) {

            if (count($this->session->get('panier')) == 0) {
                $event->setResponse(new RedirectResponse($this->router->generate('panier')));
            }
            if (!$this->securityContext->isGranted("ROLE_USER")) {
                $this->session->getFlashBag()->add('notification', 'Vous devez vous identifier');
                $event->setResponse(new RedirectResponse($this->router->generate('fos_user_security_login')));
            }
        }
    }

}