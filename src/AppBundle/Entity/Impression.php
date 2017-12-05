<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * Impression
 *
 * @ORM\Table(name="impressions", indexes={@Index(name="search_idx", columns={"id", "user_id"})})
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImpressionRepository")
 */
class Impression
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="impressions")
     * @ORM\JoinColumn(nullable=false, onDelete="cascade")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="impression", type="text")
     */
    private $impression;

    /**
     * @var int
     *
     * @ORM\Column(name="is_approved", type="boolean")
     */
    private $isApproved;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * Impression constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->isApproved = false;
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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return Impression
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Set impression
     *
     * @param string $impression
     *
     * @return Impression
     */
    public function setImpression($impression)
    {
        $this->impression = $impression;

        return $this;
    }

    /**
     * Get impression
     *
     * @return string
     */
    public function getImpression()
    {
        return $this->impression;
    }

    /**
     * Set approved
     *
     * @param integer $isApproved
     *
     * @return Impression
     */
    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    /**
     * Get approved
     *
     * @return int
     */
    public function getIsApproved()
    {
        return $this->isApproved;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return Impression
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getImpression();
    }
}
