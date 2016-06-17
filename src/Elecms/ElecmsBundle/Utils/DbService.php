<?php

namespace Elecms\ElecmsBundle\Utils;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;

use Elecms\ElecmsBundle\Entity\SettingPriv;

class DbService
{
    protected $em;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public function checkInstallation()
    {
        $checkInstalled = $this->em->getRepository('ElecmsBundle:SettingPriv')->findOneBy(array(
            'settingKey' => 'installed',
            'settingValue' => 1
        ));

        if(!$checkInstalled) {
            $sp = new SettingPriv();
            $sp->setSettingKey('installed');
            $sp->setSettingValue('1');

            try {
                $this->em->persist($sp);
                $this->em->flush();
            } catch (\PDOException $e) {
                throw new Exception($e->getMessage());
            }
            $return = false;
        } else {
            $return = true;
        }

        return $return;
    }
}