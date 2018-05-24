<?php
/**
 * Created by PhpStorm.
 * User: florian
 * Date: 15/05/2018
 * Time: 13:50
 */

namespace Ecommerce\EcommerceBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoriesController extends Controller
{
    public function menuAction()
    {

        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository("EcommerceBundle:Categories")->findAll();

        return $this->render("@Ecommerce/Default/categoriesMenu.html.twig", array("categories" => $categories));
    }

}