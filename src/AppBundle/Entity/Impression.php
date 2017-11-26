<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Impression
 *
 * @ORM\Table(name="impressions")
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
     * @ORM\Column(name="approved", type="smallint", nullable=true)
     */
    private $approved;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        //todo administratorius turi tvirtinti
        $this->approved = 1;
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
     * @param integer $approved
     *
     * @return Impression
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Get approved
     *
     * @return int
     */
    public function getApproved()
    {
        return $this->approved;
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
}
