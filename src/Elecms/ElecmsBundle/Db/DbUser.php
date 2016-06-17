<?php

namespace Elecms\ElecmsBundle\Db;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Session\Session;

use Elecms\ElecmsBundle\Entity\UserElecms;

class DbUser
{
    protected $em;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public function install(UserElecms $admin, $userManager, $form, Session $session)
    {
            $session->set('username', $admin->getUsername());
            $session->set('email', $admin->getEmail());

            $db_admin = $this->em->getRepository('ElecmsBundle:UserElecms')->findOneBy(array('email' => $admin->getEmail()));

            // If user edits email which was already saved to database, perform an update
            if ($db_admin) {
                $db_admin->setUsername($admin->getUsername());
                $db_admin->setPlainPassword($form->get('password')->getData());
                $userManager->updatePassword($db_admin);
                $db_admin->setEmail($admin->getEmail());
                $db_admin->setEnabled(true);
                $db_admin->setSuperAdmin(true);
                $db_admin->setAdmin(true);
                $this->em->flush();
            } else {
                // Otherwise, insert a new row
                $admin->setUsername($admin->getUsername());
                $admin->setPlainPassword($form->get('password')->getData());
                $userManager->updatePassword($admin);
                $admin->setEmail($admin->getEmail());
                $admin->setEnabled(true);
                $admin->setSuperAdmin(true);
                $admin->setAdmin(true);
                try {
                    $this->em->persist($admin);
                    $this->em->flush();
                } catch (\PDOException $e) {
                    throw new Exception($e->getMessage());
                }
            }
    }
}