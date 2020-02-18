<?php

namespace App\Entity\SearchEntity;

class UserSearch
{
    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $firstname;

    /**
     * @var string|null
     */
    private $lastname;

    /**
     * Get the value of lastname
     *
     * @return  string|null
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @param  string|null  $lastname
     *
     * @return  self
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of firstname
     *
     * @return  string|null
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @param  string|null  $firstname
     *
     * @return  self
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of email
     *
     * @return  string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param  string|null  $email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
}
