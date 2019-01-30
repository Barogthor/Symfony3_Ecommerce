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
use Ecommerce\EcommerceBundle\Entity\Media;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMedia extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $media1 = new Media();
        $media1->setPath('http://cp.lakanal.free.fr/exercices/jeux/memory/legumes/legumes.png');
        $media1->name = 'Légumes';
        $manager->persist($media1);

        $media2 = new Media();
        $media2->setPath('http://img0.mxstatic.com/wallpapers/238cdfc903a19ad39ea901619dd55d47_large.jpeg');
        $media2->name = 'Fruits';
        $manager->persist($media2);
        $media3 = new Media();
        $media3->setPath('https://bornandprimeurs.fr/66-large_default/poivrons-rouge-espagne.jpg');
        $media3->name = 'Poivron rouge';
        $manager->persist($media3);

        $media4 = new Media();
        $media4->setPath('http://www.hortitecnews.com/wp-content/uploads/2015/02/field_image_piment.png');
        $media4->name = 'Piment';
        $manager->persist($media4);

        $media5 = new Media();
        $media5->setPath('http://www.niffylux.com/image-gratuite/Tomate__7_4b71e7e13f85b.jpg');
        $media5->name = 'Tomate';
        $manager->persist($media5);

        $media6 = new Media();
        $media6->setPath('https://www.totavo.com/1037-large/poivron-vert-unite.jpg');
        $media6->name = 'Poivron vert';
        $manager->persist($media6);

        $media7 = new Media();
        $media7->setPath('http://www.boitearecettes.com/fruits_legumes/raisins-6.gif');
        $media7->name = 'Raisin';
        $manager->persist($media7);

        $media8 = new Media();
        $media8->setPath('https://www.lesfruitsetlegumesfrais.com/_upload/cache/ressources/produits/orange/orange_346_346_filled.jpg');
        $media8->name = 'Orange';
        $manager->persist($media8);

        $manager->flush();

        $this->addReference('media1', $media1);
        $this->addReference('media2', $media2);
        $this->addReference('media3', $media3);
        $this->addReference('media4', $media4);
        $this->addReference('media5', $media5);
        $this->addReference('media6', $media6);
        $this->addReference('media7', $media7);
        $this->addReference('media8', $media8);
    }

    public function getOrder()
    {
        return 1;
    }
}