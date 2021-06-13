<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * User
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
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
	 * @ORM\Column(type="string", length=100)
	 * @Assert\NotBlank()
	 *
	 */
	private $name;
	/**
	 * @ORM\Column(type="text")
	 * @Assert\NotBlank()
	 */
	private $username;
	/**
	 * @ORM\Column(type="text")
	 * @Assert\NotBlank()
	 */
	private $password;
	/**
	 * @ORM\Column(type="string", unique=true, nullable=true)
	 */
    private $api_token;
	/**
	 * @ORM\Column(name="created_at", type="datetime")
	 */
	private $created_at;
	/**
	 * @ORM\Column(name="updated_at", type="datetime", nullable=true)
	 */
	private $updated_at;
	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}
	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
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
	 */
	public function setName($name)
	{
		$this->name = $name;
	}
	/**
	 * @return mixed
	 */
	public function getUsername()
	{
		return $this->username;
	}
	/**
	 * @param string $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	}
	/**
	 * @return mixed
	 */
	public function getPassword()
	{
		return $this->password;
	}
	/**
	 * @param mixed $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}
	/**
	 * @return mixed
	 */
	public function getApiToken()
	{
		return $this->api_token;
	}
	/**
	 * @param mixed $api_token
	 */
	public function setApiToken($api_token)
	{
		$this->api_token = $api_token;
	}
	/**
	 * @return mixed
	 */
	public function getCreatedAt()
	{
		return $this->created_at;
	}
	/**
	 * @param mixed $created_at
	 */
	public function setCreatedAt($created_at)
	{
		$this->created_at = $created_at;
	}
	/**
	 * @return mixed
	 */
	public function getUpdatedAt()
	{
		return $this->updated_at;
	}
	/**
	 * @param mixed $updated_at
	 */
	public function setUpdatedAt($updated_at)
	{
		$this->updated_at = $updated_at;
	}

	public function serialize()
	{
		return serialize(array(
			$this->id,
			$this->username,
			$this->password,
//			$this->isActive
			// see section on salt below
			// $this->salt,
		));
	}

	public function unserialize($serialized)
	{
		list (
			$this->id,
			$this->username,
			$this->password,
//			$this->isActive
			// see section on salt below
			// $this->salt
			) = unserialize($serialized);
	}

	public function getRoles()
	{
//		if($this->isAdmin){
//			return array('ROLE_ADMIN');
//		}

		return array('ROLE_USER');
	}

	public function getSalt()
	{
		// TODO: Implement getSalt() method.
	}

	public function eraseCredentials()
	{
		// TODO: Implement eraseCredentials() method.
	}
}