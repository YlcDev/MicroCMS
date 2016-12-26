<?php

namespace MicroCMS\Domain;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    /**
     * user id
     *
     * @var integer
     */
    private $id;

    /**
     * user name
     *
     * @var string
     */
    private $username;

    /**
     * user password
     *
     * @var string
     */
    private $password;

    /**
     * salt that was originaly used to encode the password
     *
     * @var string
     */
    private $salt;

    /**
     * Role
     * Values : ROLE_USER or ROLE_ADMIN
     *
     * @var string
     */
    private $role;


    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function getUsername() {
        return $this->username;
    }
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function getPassword() {
        return $this->password;
    }
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function getSalt() {
        return $this->salt;
    }
    public function setSalt($salt) {
        $this->salt = $salt;
        return $this;
    }


    public function getRole() {
        return $this->role;
    }
    public function setRole($role) {
        $this->role = $role;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles() {
        return array($this->getRole());
    }


    /**
     * @inheritDoc
     */
    public function eraseCredentials() {
        // nothing to do here
    }

}