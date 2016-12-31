<?php

namespace MicroCMS\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UserNameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use MicroCMS\Domain\User;

class UserDAO extends DAO implements UserProviderInterface
{

    /**
     * Returns a list of all users, sorted by role and name
     *
     * @return array A list of all users
     */
    public function findAll() {
        $sql = "SELECT * FROM t_user ORDER BY usr_role, usr_name";
        $result = $this->getDb()->fetchAll($sql);

        // convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['usr_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }


    /**
     * Returns a user matching the supplied id
     *
     * @param integer $id the user id
     *
     * @return \MicroCMS\Domain\User|throws an exception if no matching user is found
     */
    public function find($id) {
        $sql ="SELECT * FROM t_user WHERE usr_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No user matching id " . $id);
    }


    /**
     * Saves auser into the database
     *
     * @param \MicroCMS\Domain\User $user the user to save
     */
    public function save(User $user) {
        $userData = array(
            'usr_name' => $user->getUsername(),
            'usr_salt' => $user->getSalt(),
            'usr_password' => $user->getPassword(),
            'usr_role' => $user->getRole()
        );


        if ($user->getId()) {
            //the user has allready been saved: update it
            $this->getDb()->update('t_user', $userData, array('usr_id' => $user->getId()));
        } else {
            //the user has never been saved: insert it
            $this->getDb()->insert('t_user', $userData);
            // get the id of the newly created user and set it on the entity
            $id = $this->getDb()->lastInsertId();
            $user->setId($id);
        }
    }

    /**
     * Removes an user form the database
     *
     * @param interger $id The user id
     */
    public function delete($id) {
        $this->getDb()->delete('t_user', array('usr_id' => $id));
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username) {
        $sql = "SELECT * FROM t_user WHERE usr_name=?";
        $row = $this->getDb()->fetchAssoc($sql, array($username));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new UserNameNotFoundException(sprintf('User "%s" not found.', $username));
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user) {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instance of "%s" are not supported.', $class));
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc]
     */
    public function supportsClass($class)
    {
        return 'MicroCMS\Domain\User' === $class;
    }

    /**
     * Create a User object based on DB row
     *
     * @param array $row the DB row containing User data
     * @return \MicroCMS\Domain\User
     */
    protected function buildDomainObject(array $row)
    {
        $user = new User();
        $user->setId($row['usr_id']);
        $user->setUsername($row['usr_name']);
        $user->setPassword($row['usr_password']);
        $user->setSalt($row['usr_salt']);
        $user->setRole($row['usr_role']);
        return $user;
    }
}
