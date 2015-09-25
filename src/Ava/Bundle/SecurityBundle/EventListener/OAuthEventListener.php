<?php
/**
 * Created by PhpStorm.
 * User: Karol
 * Date: 2015-04-16
 * Time: 15:33
 */

namespace Ava\Bundle\SecurityBundle\EventListener;

use AppBundle\Entity\User;
use AppBundle\Entity\UserRepository;
use AppBundle\Provider\UserProvider;
use Doctrine\ORM\EntityManager;
use FOS\OAuthServerBundle\Event\OAuthEvent;

class OAuthEventListener
{
	protected $provider;
	protected $em;
	function __construct(UserProvider $provider, EntityManager $em)
	{
		$this->provider = $provider;
		$this->em = $em;
	}

	public function onPreAuthorizationProcess(OAuthEvent $event)
	{
		if ($user = $this->getUser($event)) {
			$event->setAuthorizedClient(
				$user->isAuthorizedClient($event->getClient())
			);
		}
	}

	public function onPostAuthorizationProcess(OAuthEvent $event)
	{
		if ($event->isAuthorizedClient()) {
			if (null !== $client = $event->getClient()) {
				$user = $this->getUser($event);
				$user->addOauthClient($client);
				$this->em->flush();
			}
		}
	}

	protected function getUser(OAuthEvent $event)
	{
		return $this->provider->loadUserByUsername($event->getUser()->getUsername());
	}

}