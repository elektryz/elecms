<?php

namespace Elecms\ElecmsBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_elecms")
 */
class UserElecms extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(length=100)
     */
    protected $login;

    /**
     * @ORM\Column(length=500)
     */
    protected $password;

    /**
     * @ORM\Column(length=100)
     */
    protected $email;

    /**
     * @ORM\Column(length=50)
     */
    protected $firstName;

    /**
     * @ORM\Column(length=50)
     */
    protected $lastName;

    /**
     * @ORM\Column(length=30)
     */
    protected $ipCreated;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $registerDate;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set login
     *
     * @param string $login
     * @return UserElecms
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return UserElecms
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return UserElecms
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return UserElecms
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return UserElecms
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set ipCreated
     *
     * @param string $ipCreated
     * @return UserElecms
     */
    public function setIpCreated($ipCreated)
    {
        $this->ipCreated = $ipCreated;

        return $this;
    }

    /**
     * Get ipCreated
     *
     * @return string 
     */
    public function getIpCreated()
    {
        return $this->ipCreated;
    }

    /**
     * Set registerDate
     *
     * @param \DateTime $registerDate
     * @return UserElecms
     */
    public function setRegisterDate($registerDate)
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    /**
     * Get registerDate
     *
     * @return \DateTime 
     */
    public function getRegisterDate()
    {
        return $this->registerDate;
    }
}
