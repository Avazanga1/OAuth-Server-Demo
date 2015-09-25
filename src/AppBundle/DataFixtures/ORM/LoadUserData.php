<?php
/**
 * Created by PhpStorm.
 * User: Karol
 * Date: 2015-04-07
 * Time: 17:22
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}

	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
		$user = new User();
		$user->setUsername('karol');
		$user->setEmail('karol.filip.wojcik@gmail.com');

		$encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
		$user->setPassword($encoder->encodePassword('karol', $user->getSalt()));


		$manager->persist($user);
		$manager->flush();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getOrder()
	{
		return 1; // the order in which fixtures will be loaded
	}
}