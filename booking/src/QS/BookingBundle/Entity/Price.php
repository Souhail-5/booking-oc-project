<?php

namespace QS\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Price
 *
 * @ORM\Table(name="price")
 * @ORM\Entity(repositoryClass="QS\BookingBundle\Repository\PriceRepository")
 */
class Price
{
    /**
     * @var guid
     *
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="eur", type="decimal", precision=10, scale=2)
     */
    private $eur;

    /**
     * @var string
     *
     * @ORM\Column(name="usd", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $usd;


    /**
     * Get id
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Prices
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set eur
     *
     * @param string $eur
     *
     * @return Prices
     */
    public function setEur($eur)
    {
        $this->eur = $eur;

        return $this;
    }

    /**
     * Get eur
     *
     * @return string
     */
    public function getEur()
    {
        return $this->eur;
    }

    /**
     * Set usd
     *
     * @param string $usd
     *
     * @return Prices
     */
    public function setUsd($usd)
    {
        $this->usd = $usd;

        return $this;
    }

    /**
     * Get usd
     *
     * @return string
     */
    public function getUsd()
    {
        return $this->usd;
    }
}
