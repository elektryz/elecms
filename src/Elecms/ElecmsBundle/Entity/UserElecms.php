<?php

namespace Elecms\ElecmsBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
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
     * @var string
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    protected $email;

    /**
     * Here, we won't add any Doctrine annotations because
     * that field is not needed in our database, just in our form
     */
    protected $password_confirm;
    protected $skip_validation;

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

    public function getPasswordConfirm()
    {
        return $this->password_confirm;
    }

    public function setPasswordConfirm($password_confirm)
    {
        $this->password_confirm = $password_confirm;
    }

    public function getSkipValidation()
    {
        return $this->skip_validation;
    }

    public function setSkipValidation($skip_validation)
    {
        $this->skip_validation = $skip_validation;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if($this->getPasswordConfirm() && $this->getPassword()) {
        if($this->getPassword() != $this->getPasswordConfirm())
        {
            $context->buildViolation('Inserted passwords are different.')
                ->atPath('password')
                ->addViolation();
        }
        }
    }


    /**
     * @ORM\PreUpdate
     */
    public function doStuffOnPreUpdate()
    {
        if($this->password)
            $this->setPlainPassword($this->password);
    }

    /**
     * @ORM\PrePersist
     */
    public function doStuffOnPrePersist()
    {
        if($this->password)
            $this->setPlainPassword($this->password);
    }

}
