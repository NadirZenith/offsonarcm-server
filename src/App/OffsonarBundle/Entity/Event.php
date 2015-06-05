<?php

namespace App\OffsonarBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Proxy\Proxy;
use Symfony\Component\Validator\Constraints as Constraints;
use Sonata\MediaBundle\Model\MediaInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Event
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="title", type="text", nullable=false)
     * @Constraints\NotBlank()
     */
    protected $title;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     * @Constraints\NotBlank()
     */
    protected $content;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="status", type="string", length=20, nullable=false)
     */
    protected $status = "publish";

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(type="text", nullable=false)
     * @Constraints\NotBlank()
     */
    protected $place_name;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(type="text", nullable=false)
     * @Constraints\NotBlank()
     */
    protected $place_address;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(type="datetime", nullable=false)
     * @Constraints\NotBlank()
     */
    protected $begin_date;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(type="datetime")
     */
    protected $end_date;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(type="text", nullable=false)
     * @Constraints\NotBlank()
     */
    protected $price;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $price_conditions;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(type="text")
     */
    protected $promoter;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(type="text")
     */
    protected $ticketscript;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="LAZY")
     */
    protected $flyer;

    /**
     * 
     */
    protected $flyerUrls;

    public function __construct()
    {
        
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get begin date
     *
     * @return string
     */
    public function getBeginDate()
    {
        return $this->begin_date;
    }

    /**
     * Set begin date
     *
     * @return string
     */
    public function setBeginDate($begin_date)
    {
        $this->begin_date = $begin_date;
    }

    /**
     * Get place name
     *
     * @return string
     */
    public function getPlaceName()
    {
        return $this->place_name;
    }

    /**
     * Set place name
     *
     * @return string
     */
    public function setPlaceName($place_name)
    {
        $this->place_name = $place_name;
    }

    /**
     * Get place address
     *
     * @return string
     */
    public function getPlaceAddress()
    {
        return $this->place_address;
    }

    /**
     * Set place address
     *
     * @return string
     */
    public function setPlaceAddress($place_address)
    {
        $this->place_address = $place_address;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set price
     *
     * @return string
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get price conditions
     *
     * @return string
     */
    public function getPriceConditions()
    {
        return $this->price_conditions;
    }

    /**
     * Set price conditions
     *
     * @return string
     */
    public function setPriceConditions($price_conditions)
    {
        $this->price_conditions = $price_conditions;
    }

    /**
     * Set end date
     *
     * @return string
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
    }

    /**
     * Get end date
     *
     * @return string
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * Set Promoter
     *
     * @return string
     */
    public function setPromoter($promoter)
    {
        $this->promoter = $promoter;
    }

    /**
     * Get Promoter
     *
     * @return string
     */
    public function getPromoter()
    {
        return $this->promoter;
    }

    /**
     * Set Ticketscript
     *
     * @return string
     */
    public function setTicketscript($ticketscript)
    {
        $this->ticketscript = $ticketscript;
    }

    /**
     * Get Ticketscript
     *
     * @return string
     */
    public function getTicketscript()
    {
        return $this->ticketscript;
    }

    /**
     * Set flyer
     *
     * @return string
     */
    public function setFlyer(MediaInterface $flyer)
    {
        $this->flyer = $flyer;
    }

    /**
     * Get flyer
     *
     * @return string
     */
    public function getFlyer()
    {
        return $this->flyer;
    }

    /**
     * Set flyers urls
     *
     * @return none
     */
    public function setFlyerUrls($flyerUrls)
    {
        $this->flyerUrls = $flyerUrls;
    }

    /**
     * Get flyer
     *
     * @return array
     */
    public function getFlyerUrls()
    {
        return $this->flyerUrls;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        
    }
}
