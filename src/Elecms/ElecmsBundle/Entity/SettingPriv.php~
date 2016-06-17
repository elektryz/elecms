<?php
namespace Elecms\ElecmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="setting_priv")
 */
class SettingPriv
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(length=100)
     */
    protected $settingKey;

    /**
     * @ORM\Column(length=500, nullable=true)
     */
    protected $settingValue;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $modified;

    /** @ORM\PrePersist */
    public function doOtherStuffOnPrePersist()
    {
        $this->setModified(new \DateTime());
    }

    /** @ORM\PreUpdate */
    public function doOtherStuffOnPreUpdate()
    {
        $this->setModified(new \DateTime());
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
     * Set settingKey
     *
     * @param string $settingKey
     * @return SettingPriv
     */
    public function setSettingKey($settingKey)
    {
        $this->settingKey = $settingKey;

        return $this;
    }

    /**
     * Get settingKey
     *
     * @return string 
     */
    public function getSettingKey()
    {
        return $this->settingKey;
    }

    /**
     * Set settingValue
     *
     * @param string $settingValue
     * @return SettingPriv
     */
    public function setSettingValue($settingValue)
    {
        $this->settingValue = $settingValue;

        return $this;
    }

    /**
     * Get settingValue
     *
     * @return string 
     */
    public function getSettingValue()
    {
        return $this->settingValue;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     * @return SettingPriv
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime 
     */
    public function getModified()
    {
        return $this->modified;
    }
}
