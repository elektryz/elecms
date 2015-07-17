<?php

namespace Elecms\ElecmsBundle\Utils;

use Symfony\Component\Yaml\Dumper;

class InstallDbHelper
{
    protected $server;
    protected $database;
    protected $user;
    protected $password;

    public function getServer()
    {
        return $this->server;
    }

    public function setServer($server)
    {
        $this->server = $server;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function setDatabase($database)
    {
        $this->database = $database;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function exportParams()
    {
        $params = array(
            'parameters' => array(
                'database_host' => $this->getServer(),
                'database_port' => '',
                'database_name' => $this->getDatabase(),
                'database_user' => $this->getUser(),
                'database_password' => $this->getPassword(),
            )
        );

        $dumper = new Dumper();

        $yaml = $dumper->dump($params);

        return file_put_contents(__DIR__.'/../Resources/config/parameters_test.yml', $yaml) ? true : false;
    }

}

