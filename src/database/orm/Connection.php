<?php

namespace Freya\orm;

class Connection
{
    private $driver;
    private $host;
    private $schema;
    private $username;
    private $password;
    private $port;

    public function __construct(string $driver, string $host, string $schema, string $username, string $password, int $port)
    {
        $this->setDriver($driver);
        $this->setHost($host);
        $this->setSchema($schema);
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setPort($port);
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getSchema()
    {
        return $this->schema;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setDriver($driver)
    {
        $this->driver = $driver;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function setSchema($schema)
    {
        $this->schema = $schema;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setPort($port)
    {
        $this->port = $port;
    }
}
