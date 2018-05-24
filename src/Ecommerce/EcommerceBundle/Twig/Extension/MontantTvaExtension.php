<?php
/**
 * Created by PhpStorm.
 * User: florian
 * Date: 20/05/2018
 * Time: 21:36
 */

namespace Ecommerce\EcommerceBundle\Twig\Extension;


use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;



class MontantTvaExtension extends \Twig_Extension
{
    public function getFilters(){
        return array(new \Twig_SimpleFilter('montantTva', array($this,'calculMontantTva')));
    }

    function calculMontantTva($prixHT, $tva){
        return round((($prixHT / $tva) - $prixHT),2);
    }

    public function getName(){
        return 'montant_tva_extension';
    }

}