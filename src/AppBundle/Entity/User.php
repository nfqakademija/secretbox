<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="users", indexes={@Index(name="search_idx", columns={"id", "facebook_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", length=30)
     */
    private $facebookId;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=50)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=50)
     */
    private $lastName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registered_date", type="datetime")
     */
    private $registeredDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="logged_date", type="datetime")
     */
    private $loggedDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="login_count", type="integer")
     */
    private $loginCount = 1;

    /**
     * @var string
     *
     * @ORM\Column(name="picture_url", type="string", length=255)
     */
    private $pictureUrl = "";

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json_array")
     */
    private $roles;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string")
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_no", type="string", length=15)
     */
    private $phoneNo;

    /**
     * @ORM\Column(name="newsletter", type="boolean", options={"default": false})
     */
    private $newsletter;

    /**
     * @ORM\OneToMany(targetEntity="Impression", mappedBy="user")
     * @ORM\OrderBy({"createdAt"="DESC"})
     */
    private $impressions;

    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="user")
     * @ORM\OrderBy({"orderedAt"="DESC"})
     */
    private $orders;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->registeredDate = new \DateTime();
        $this->loggedDate = new \DateTime();
        $this->roles = [
            'ROLE_USER'
        ];
        $this->newsletter = false;
        $this->address = "";
        $this->phoneNo = "";
        $this->impressions = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
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
     *
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
     * Set facebookId
     *
     * @param string $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param  string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPictureUrl()
    {
        return $this->pictureUrl;
    }

    /**
     * @param string $pictureUrl
     * @return User
     */
    public function setPictureUrl($pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRegisteredDate()
    {
        return $this->registeredDate;
    }

    /**
     * @param \DateTime $registeredDate
     * @return User
     */
    public function setRegisteredDate($registeredDate)
    {
        $this->registeredDate = $registeredDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLoggedDate()
    {
        return $this->loggedDate;
    }

    /**
     * @param \DateTime $loggedDate
     * @return User
     */
    public function setLoggedDate($loggedDate)
    {
        $this->loggedDate = $loggedDate;
        return $this;
    }

    /**
     * @return integer
     */
    public function getLoginCount()
    {
        return $this->loginCount;
    }

    /**
     * @param integer $loginCount
     * @return User
     */
    public function setLoginCount($loginCount)
    {
        $this->loginCount = $loginCount;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNo()
    {
        return $this->phoneNo;
    }

    /**
     * @param string $phoneNo
     *
     * @return User
     */
    public function setPhoneNo($phoneNo)
    {
        $this->phoneNo = $phoneNo;
        return $this;
    }



    /**
     * @return boolean
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * @param boolean $newsletter
     *
     * @return User
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return ArrayCollection|Impression[]
     */
    public function getImpressions()
    {
        return $this->impressions;
    }

    /**
     * @return ArrayCollection|Order[]
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     *
     */
    public function getPassword()
    {
        return null;
    }

    /**
     *
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->facebookId;
    }

    /**
     *
     */
    public function eraseCredentials()
    {
        return null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getFirstName() . ' ' . $this->getLastName();
    }
}
