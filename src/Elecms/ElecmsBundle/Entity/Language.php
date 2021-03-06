<?php
namespace Elecms\ElecmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="language")
 */
class Language
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
    protected $langName;

    /**
     * @ORM\Column(length=100)
     */
    protected $langNameEn;

    /**
     * @ORM\Column(length=10)
     */
    protected $langCode;

    /**
     * @ORM\Column(type="integer")
     */
    protected $enabled;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="language")
     */
    private $languages;

    /**
     * @ORM\OneToMany(targetEntity="UserElecms", mappedBy="language")
     */
    private $languages2;


    public function __construct() {
        $this->languages = new ArrayCollection();
        $this->languages2 = new ArrayCollection();
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
     * Set langName
     *
     * @param string $langName
     * @return Language
     */
    public function setLangName($langName)
    {
        $this->langName = $langName;

        return $this;
    }

    /**
     * Get langName
     *
     * @return string 
     */
    public function getLangName()
    {
        return $this->langName;
    }

    /**
     * Set langNameEn
     *
     * @param string $langNameEn
     * @return Language
     */
    public function setLangNameEn($langNameEn)
    {
        $this->langNameEn = $langNameEn;

        return $this;
    }

    /**
     * Get langNameEn
     *
     * @return string 
     */
    public function getLangNameEn()
    {
        return $this->langNameEn;
    }

    /**
     * Set langCode
     *
     * @param string $langCode
     * @return Language
     */
    public function setLangCode($langCode)
    {
        $this->langCode = $langCode;

        return $this;
    }

    /**
     * Get langCode
     *
     * @return string 
     */
    public function getLangCode()
    {
        return $this->langCode;
    }

    /**
     * Set enabled
     *
     * @param integer $enabled
     * @return Language
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return integer 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    public function __toString()
    {
        return $this->langName;
    }

    /**
     * Add languages
     *
     * @param \Elecms\ElecmsBundle\Entity\Page $languages
     * @return Language
     */
    public function addLanguage(\Elecms\ElecmsBundle\Entity\Page $languages)
    {
        $this->languages[] = $languages;

        return $this;
    }

    /**
     * Remove languages
     *
     * @param \Elecms\ElecmsBundle\Entity\Page $languages
     */
    public function removeLanguage(\Elecms\ElecmsBundle\Entity\Page $languages)
    {
        $this->languages->removeElement($languages);
    }

    /**
     * Get languages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Add languagesList
     *
     * @param \Elecms\ElecmsBundle\Entity\UserElecms $languagesList
     * @return Language
     */
    public function addLanguagesList(\Elecms\ElecmsBundle\Entity\UserElecms $languagesList)
    {
        $this->languagesList[] = $languagesList;

        return $this;
    }

    /**
     * Remove languagesList
     *
     * @param \Elecms\ElecmsBundle\Entity\UserElecms $languagesList
     */
    public function removeLanguagesList(\Elecms\ElecmsBundle\Entity\UserElecms $languagesList)
    {
        $this->languagesList->removeElement($languagesList);
    }

    /**
     * Get languagesList
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLanguagesList()
    {
        return $this->languagesList;
    }

    /**
     * Add languages2
     *
     * @param \Elecms\ElecmsBundle\Entity\UserElecms $languages2
     * @return Language
     */
    public function addLanguages2(\Elecms\ElecmsBundle\Entity\UserElecms $languages2)
    {
        $this->languages2[] = $languages2;

        return $this;
    }

    /**
     * Remove languages2
     *
     * @param \Elecms\ElecmsBundle\Entity\UserElecms $languages2
     */
    public function removeLanguages2(\Elecms\ElecmsBundle\Entity\UserElecms $languages2)
    {
        $this->languages2->removeElement($languages2);
    }

    /**
     * Get languages2
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLanguages2()
    {
        return $this->languages2;
    }
}
