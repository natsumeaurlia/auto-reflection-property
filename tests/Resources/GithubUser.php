<?php

namespace Tests\Resources;

use Natsumeaurlia\Reflection\PropertyReflector;

class GithubUser extends PropertyReflector
{
    public $id;
    public $login;
    public $testProperty;
    protected $node_id;
    protected $avatar_url;
    private $name;

    public function getNodeId()
    {
        return $this->node_id;
    }

    public function getAvatarUrl()
    {
        return $this->avatar_url;
    }

    public function getName()
    {
        return $this->name;
    }
}