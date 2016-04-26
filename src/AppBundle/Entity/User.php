<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity("email", message="app.validation.user.email_unique")
 * @UniqueEntity("username")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     * @Assert\NotBlank
     * @Assert\Length(min=2,max=255)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     * @Assert\NotBlank
     * @Assert\Length(min=2,max=255)
     */
    protected $lastName;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $loginCount = 0;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AuthLog", mappedBy="user")
     * @ORM\OrderBy({"datetime" = "DESC"})
     */
    protected $authLogs;


    public function __construct()
    {
        parent::__construct();

        $this->authLogs = new ArrayCollection();
    }

    /**
     * Returns true if the user is enabled and not locked.
     *
     * @return boolean
     */
    public function isActive()
    {
        if ($this->isEnabled() && !$this->isLocked() && !$this->isExpired() && !$this->isCredentialsExpired()) {
            return true;
        }

        return false;
    }

    /**
     * Set email
     * Also sets the username to the email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        $this->setUsername($email);

        return $this;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Get the combination of the first and last names.
     * If both first & last are empty, an empty string will be returned.
     *
     * @return string
     */
    public function getName()
    {
        $name = $this->getFirstName().' '.$this->getLastName();

        return trim($name);
    }

    /**
     * Set loginCount
     *
     * @param integer $loginCount
     * @return User
     */
    public function setLoginCount($loginCount)
    {
        $this->loginCount = $loginCount;

        return $this;
    }

    /**
     * Get loginCount
     *
     * @return integer
     */
    public function getLoginCount()
    {
        return $this->loginCount;
    }

    /**
     * Increment the login count
     *
     * @return integer
     */
    public function incrementLoginCount()
    {
        ++ $this->loginCount;

        return $this->loginCount;
    }

    /**
     * Get auth logs.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAuthLogs()
    {
        return $this->authLogs;
    }

    public function rolesAsText()
    {
        $roles = $this->getRoles();

        $text = [];
        foreach ($roles as $role) {
            switch ($role) {
                case 'ROLE_SUPER_ADMIN' :
                    $text[] = 'Super Admin';
                    break;
            }
        }

        return implode(', ', $text);
    }

    /**
     * Returns the name if it's not empty.
     * Otherwise, returns the email address.
     *
     * @return string
     */
    public function displayName()
    {
        $name = $this->getName();

        if (!empty($name)) {
            return $name;
        } else {
            return $this->getEmail();
        }
    }
}
