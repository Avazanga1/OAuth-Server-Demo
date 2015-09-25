<?php
/**
 * Created by PhpStorm.
 * User: Karol
 * Date: 2015-04-07
 * Time: 13:54
 */

namespace AppBundle\Entity;


use Ava\Bundle\SecurityBundle\Entity\Client;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * AppBundle\Entity\User
 *
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 */
class User implements UserInterface, \Serializable
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=25, unique=true)
	 */
	private $username;

	/**
	 * @ORM\Column(type="string", length=50, unique=true)
	 */
	private $email;

	/**
	 * @ORM\Column(type="string", length=100)
	 */
	private $salt;

	/**
	 * @ORM\Column(type="string", length=200)
	 */
	private $password;

	/**
	 * @ORM\Column(name="is_active", type="boolean")
	 */
	private $isActive;

	/**
	 * @ORM\ManyToMany(targetEntity="Ava\Bundle\SecurityBundle\Entity\Client", inversedBy="users", indexBy="id")
	 * @ORM\JoinTable(name="oauth_users_clients")
	 */
	private $oauthClients;

	public function __construct()
	{
		$this->isActive = true;
		$this->salt = md5(uniqid(null, true));
		$this->oauthClients =  new ArrayCollection();
	}

	public function getId(){
		return $this->id;
	}

	/**
	 * @inheritDoc
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @inheritDoc
	 */
	public function setUsername($username)
	{
		$this->username = $username;
		$this->email = $username;
	}

	/**
	 * @inheritDoc
	 */
	public function getSalt()
	{
		return $this->salt;
	}

	public function setSalt($salt)
	{
		$this->salt = $salt;
	}

	/**
	 * @inheritDoc
	 */
	public function getPassword()
	{
		return $this->password;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	/**
	 * @inheritDoc
	 */
	public function getRoles()
	{
		return array('ROLE_USER');
	}

	/**
	 * @inheritDoc
	 */
	public function eraseCredentials()
	{
	}

	/**
	 * @see \Serializable::serialize()
	 */
	public function serialize()
	{
		return serialize(
			array(
				$this->id,
				$this->username,
				$this->password,
			)
		);
	}

	/**
	 * @return mixed
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param mixed $email
	 * @return $this
	 */
	public function setEmail($email)
	{
		$this->email = $email;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getIsActive()
	{
		return $this->isActive;
	}

	/**
	 * @param mixed $isActive
	 * @return $this
	 */
	public function setIsActive($isActive)
	{
		$this->isActive = $isActive;
		return $this;
	}

	/**
	 * @see \Serializable::unserialize()
	 */
	public function unserialize($serialized)
	{
		list (
			$this->id,
			$this->username,
			$this->password,
			) = unserialize($serialized);
	}

	/**
	 * @return mixed
	 */
	public function getOauthClients()
	{
		return $this->oauthClients;
	}

	/**
	 * @param mixed $oauthClients
	 * @return $this
	 */
	public function setOauthClients($oauthClients)
	{
		$this->oauthClients = $oauthClients;
		return $this;
	}

	/**
	 * @param Client $oauthClient
	 * @return $this
	 */
	public function addOauthClient(Client $oauthClient)
	{
		$this->oauthClients->set($oauthClient->getId(), $oauthClient);
		return $this;
	}

	public function isAuthorizedClient(Client $client)
	{
		return $this->oauthClients->get($client->getId()) ? true : false;
	}
}