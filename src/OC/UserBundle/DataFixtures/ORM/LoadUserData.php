<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/13/16
 * Time: 1:12 PM
 */

namespace OC\UserBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($userAdmin);

        $userAdmin->setUsername('admin');
        $userAdmin->setPassword($encoder->encodePassword('MascaAdmin',$userAdmin->getSalt()));
        $userAdmin->addRole('ROLE_ADMIN');
        $userAdmin->setEmail('masca.admin@admin.com');
        $userAdmin->setEnabled(true);

        $manager->persist($userAdmin);
        $manager->flush();
    }

}