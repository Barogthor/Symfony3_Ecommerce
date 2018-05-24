<?php
/**
 * Created by PhpStorm.
 * User: florian
 * Date: 20/05/2018
 * Time: 21:36
 */

namespace Ecommerce\EcommerceBundle\Twig\Extension;


use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;



class TvaExtension extends \Twig_Extension
{
    public function getFilters(){
        return array(new \Twig_SimpleFilter('tva', array($this,'calculTva')));
    }

    function calculTva($prixHT, $tva){
        return round($prixHT / $tva,2);
    }

    public function getName(){
        return 'tva_extension';
    }

}