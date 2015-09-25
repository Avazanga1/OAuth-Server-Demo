<?php
/**
 * Created by PhpStorm.
 * User: Karol
 * Date: 2015-04-15
 * Time: 11:51
 */

namespace Ava\Bundle\SecurityBundle\Entity;

use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Client extends BaseClient
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=50)
	 */
	protected $name;

	/**
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="oauthClients", indexBy="id")
	 */
	protected $users;

	public function __construct()
	{
		parent::__construct();
		$this->users = new ArrayCollection();
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUsers()
	{
		return $this->users;
	}

	/**
	 * @param User[] $users
	 * @return $this
	 */
	public function setUsers($users)
	{
		$this->users = $users;
		return $this;
	}

	/**
	 * @param User $user
	 * @return $this
	 */
	public function addUser($user)
	{
		$this->users->set($user->getId(), $user);
		return $this;
	}
}