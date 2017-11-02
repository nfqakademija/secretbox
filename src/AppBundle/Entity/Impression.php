<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Impression
 *
 * @ORM\Table(name="impressions")
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
     * @var int
     *
     * @ORM\Column(name="userId", type="integer")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="impressions")
     */
    private $userId;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Impression
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
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
}

