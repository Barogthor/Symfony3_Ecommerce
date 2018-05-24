<?php
/**
 * Created by PhpStorm.
 * User: florian
 * Date: 18/05/2018
 * Time: 17:02
 */

namespace Ecommerce\EcommerceBundle\DataFixtures;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Ecommerce\EcommerceBundle\Entity\Commandes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCommandes extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $commande1 = new Commandes();
        $commande1->setUtilisateur($this->getReference('utilisateur1'));
        $commande1->setValider('1');
        $commande1->setDate(new \DateTime());
        $commande1->setReference('1');
        $commande1->setCommandes(array('0' => array('1' => '2'),
            '1' => array('2' => '1'),
            '2' => array('4' => '5')
        ));
        $manager->persist($commande1);

        $commande2 = new Commandes();
        $commande2->setUtilisateur($this->getReference('utilisateur3'));
        $commande2->setValider('1');
        $commande2->setDate(new \DateTime());
        $commande2->setReference('2');
        $commande2->setCommandes(array('0' => array('1' => '2'),
            '1' => array('2' => '1'),
            '2' => array('4' => '5')
        ));
        $manager->persist($commande2);
        $manager->flush();
    }
    public function getOrder()
    {
        return 7;
    }
}