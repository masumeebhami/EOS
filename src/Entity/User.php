<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Persistence\ManagerRegistry;

use Ramsey\Uuid\Uuid;
use App\Entity\Traits\TimeStampableTrait;
/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User {
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }
  use TimeStampableTrait;
 /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
   /**
    
     * @ORM\Column(type="uuid", unique=true)
     */
  private $uuid;
  /**
   * @ORM\Column(type="string", length=100)
   * @Assert\NotBlank()
   *
   */
  private $name;
  /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
  private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
  private $password;

    /**
     * @ORM\Column(type="string", length=254, unique=true)
     */
  private $email;

  public function getId()
  {
      return $this->id;
  }
  /**
   * @param mixed $id
   */

  public function getName()
  {
    return $this->name;
  }
  /**
   * @param mixed $name
   */
  public function getUuid()
  {
    return $this->uuid;
  }
  /**
   * @param mixed $uuid
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  public function setUsername($username)
  {
    $this->username = $username;
  }
  public function setPassword($password)
  {
    $this->password = $password;
  }
  public function setEmail($email)
  {
    $this->email = $email;
  }
  public function getUsername()
  {
      return $this->username;
  }
  public function getPassword()
  {
      return $this->password;
  }
 
}