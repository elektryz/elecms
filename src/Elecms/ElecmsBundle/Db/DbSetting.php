<?php

namespace Elecms\ElecmsBundle\Db;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;

use Elecms\ElecmsBundle\Entity\Setting;

class DbSetting
{
    protected $em;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public function get($key, $rawDisplay = false)
    {
        $return = "";
        $setting = $this->em->getRepository('ElecmsBundle:Setting')->findOneBy(array('settingKey' => $key));

        if($setting)
            if($rawDisplay)
                $return = $setting->getSettingValue(true);
            else
                $return = $setting->getSettingValue();

        return $return;
    }

    public function install($form)
    {
        try {
            $data = array();
            $websiteTitle = new Setting();
            $websiteTags = new Setting();
            $websiteDescription = new Setting();
            $onepage = new Setting();

            // Title
            $website_title = $this->em->getRepository('ElecmsBundle:Setting')->findOneBy(array('settingKey' => 'website_title'));

            if (!$website_title) {
                $websiteTitle->setSettingKey('website_title');
                $websiteTitle->setSettingValue($form->get('title')->getData());
                $websiteTitle->setModified(new \DateTime());
                $data[] = $websiteTitle;
            }

            // Tags
            $website_tags = $this->em->getRepository('ElecmsBundle:Setting')->findOneBy(array('settingKey' => 'website_tags'));

            if (!$website_tags) {
                $websiteTags->setSettingKey('website_tags');
                $websiteTags->setSettingValue($form->get('tags')->getData());
                $websiteTags->setModified(new \DateTime());
                $data[] = $websiteTags;
            }

            // Description
            $website_description = $this->em->getRepository('ElecmsBundle:Setting')->findOneBy(array(
                    'settingKey' => 'website_description'));

            if (!$website_description) {
                $websiteDescription->setSettingKey('website_description');
                $websiteDescription->setSettingValue($form->get('description')->getData());
                $websiteDescription->setModified(new \DateTime());
                $websiteDescription->setFieldType('textarea');
                $data[] = $websiteDescription;
            }

            // Onepage
            $one_page = $this->em->getRepository('ElecmsBundle:Setting')->findOneBy(array(
                'settingKey' => 'onepage'));

            if (!$one_page) {
                $onepage->setSettingKey('onepage');
                $onepage->setSettingValue(1);
                $onepage->setModified(new \DateTime());
                $data[] = $onepage;
            }

            count($data) > 0 ? $this->save($data) : '';

        } catch (\PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }


    private function save($data) {
        if(is_array($data)) {
            foreach($data as $item) {
                $this->em->persist($item);
                $this->em->flush();
            }
        } else {
            $this->em->persist($data);
            $this->em->flush();
        }
    }
}