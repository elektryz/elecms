<?php

namespace Elecms\ElecmsBundle\Utils;

use Symfony\Component\Yaml\Dumper;

class DbMailHelper
{
    protected $server;
    protected $database;
    protected $user;
    protected $password;
    public $skip;
    protected $mailhost;
    protected $mailuser;
    protected $mailpassword;
    protected $token;

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

    public function getSkip()
    {
        return $this->skip;
    }

    public function setSkip($skip)
    {
        $this->skip = $skip;
    }

    public function getMailhost()
    {
        return $this->mailhost;
    }

    public function setMailhost($mailhost)
    {
        $this->mailhost = $mailhost;
    }

    public function getMailuser()
    {
        return $this->mailuser;
    }

    public function setMailuser($mailuser)
    {
        $this->mailuser = $mailuser;
    }

    public function getMailpassword()
    {
        return $this->mailpassword;
    }

    public function setMailpassword($mailpassword)
    {
        $this->mailpassword = $mailpassword;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function exportToYml($what = 'all')
    {
        if($what == 'all')
        {
            $params = array(
                'parameters' => array(
                    'database_host' => $this->getServer(),
                    'database_port' => null,
                    'database_name' => $this->getDatabase(),
                    'database_user' => $this->getUser(),
                    'database_password' => $this->getPassword(),
                    'secret' => $this->getToken(),
                    'mailer_transport' => 'smtp',
                    'mailer_host' => $this->getMailhost(),
                    'mailer_user' => $this->getMailuser(),
                    'mailer_password' => $this->getMailpassword()
                )
            );
        } else {
            $params = array(
                'parameters' => array(
                    'database_host' => $this->getServer(),
                    'database_port' => null,
                    'database_name' => $this->getDatabase(),
                    'database_user' => $this->getUser(),
                    'database_password' => $this->getPassword(),
                    'secret' => $this->getToken(),
                )
            );
        }

        $dumper = new Dumper();

        $yaml = $dumper->dump($params);

        return file_put_contents(__DIR__.'/../Resources/config/parameters_test.yml', $yaml) ? true : false;
    }

}

