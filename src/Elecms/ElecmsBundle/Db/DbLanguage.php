<?php

namespace Elecms\ElecmsBundle\Db;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;

use Elecms\ElecmsBundle\Entity\Language;

class DbLanguage
{
    protected $em;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public function install()
    {
        $checkLangEn = $this->em->getRepository('ElecmsBundle:Language')->findOneBy(array('langCode' => 'en'));

        if(!$checkLangEn) {
            $langEn = new Language();
            $langEn->setLangName("English");
            $langEn->setLangNameEn("English");
            $langEn->setLangCode("en");
            $langEn->setEnabled(1);
            try {
                $this->em->persist($langEn);
                $this->em->flush();
            } catch (\PDOException $e) {
                throw new Exception($e->getMessage());
            }
        }

        $checkLangPl = $this->em->getRepository('ElecmsBundle:Language')->findOneBy(array('langCode' => 'pl'));

        if(!$checkLangPl) {
            $langPl = new Language();
            $langPl->setLangName("Polski");
            $langPl->setLangNameEn("Polish");
            $langPl->setLangCode("pl");
            $langPl->setEnabled(1);
            try {
                $this->em->persist($langPl);
                $this->em->flush();
            } catch (\PDOException $e) {
                throw new Exception($e->getMessage());
            }
        }
    }
}