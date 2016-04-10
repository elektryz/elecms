<?php

namespace Elecms\ElecmsBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="Elecms\ElecmsBundle\Entity\UserElecmsRepository")
 * @ORM\Table(name="user_elecms")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $registered;

    /**
     * Here, we won't add any annotations because
     * those fields are not needed in our database. We need it just in the form.
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

    public function setAdmin($boolean)
    {
        if (true === $boolean) {
            $this->addRole('ROLE_ADMIN');
        } else {
            $this->removeRole('ROLE_ADMIN');
        }

        return $this;
    }

    public function setSonataAdmin($boolean)
    {
        if (true === $boolean) {
            $this->addRole('ROLE_SONATA_ADMIN');
        } else {
            $this->removeRole('ROLE_SONATA_ADMIN');
        }

        return $this;
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


    /** @ORM\PreUpdate */
    public function doOtherStuffOnPreUpdate()
    {
        if($this->password)
            $this->setPassword($this->password);
    }

    /** @ORM\PrePersist */
    public function doOtherStuffOnPrePersist()
    {
        $this->setRegistered(new \DateTime());
    }


    /**
     * Set registered
     *
     * @param \DateTime $registered
     * @return UserElecms
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;

        return $this;
    }

    /**
     * Get registered
     *
     * @return \DateTime 
     */
    public function getRegistered()
    {
        return $this->registered;
    }
}
