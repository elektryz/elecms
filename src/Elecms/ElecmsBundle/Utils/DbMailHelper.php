<?php

namespace Elecms\ElecmsBundle\Utils;

use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class DbMailHelper
{
    protected $server;
    protected $database;
    protected $user;
    protected $password;
    protected $mailhost;
    protected $mailuser;
    protected $mailpassword;

    /**
     * @Assert\Length(
     *      min = 10,
     *      minMessage = "Security token length must be greater or equal than {{ limit }}."
     * )
     */
    protected $token;
    public $skip;

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
        if($what == 'all') {
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

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        // Check database connection
        try {
            $pdo = new \PDO("mysql:host={$this->getServer()};dbname={$this->getDatabase()}",
                $this->getUser(), $this->getPassword());
        } catch(\Exception $e) {
           $context->buildViolation('Database parameters are incorrect.')
                ->atPath('server')
                ->addViolation();
        }

        // If you don't want to skip SMTP configuration, fill all fields
        if(!$this->skip) {
           if(trim($this->getMailhost()) == "") {
               $context->buildViolation('SMTP host field can not be empty.')
                   ->atPath('mailhost')
                   ->addViolation();
           }
           if(trim($this->getMailuser()) == "") {
                $context->buildViolation('SMTP user field can not be empty.')
                    ->atPath('mailuser')
                    ->addViolation();
            }
           if(trim($this->getMailpassword()) == "") {
                $context->buildViolation('SMTP password field can not be empty.')
                    ->atPath('mailpassword')
                    ->addViolation();
            }
        }
    }

}

