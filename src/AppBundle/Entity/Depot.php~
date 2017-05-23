<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="depots")
 * @ORM\HasLifecycleCallbacks
 */
class Depot
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $depot_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $depot_code;

    /**
     * @ORM\OneToOne(targetEntity="Address")
     * @ORM\JoinColumn(name="address_id", referencedColumnName="address_id")
     */
    private $depot_address;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @return integer
     */
    public function getDepotId()
    {
        return $this->depot_id;
    }

    /**
     * @return string
     */
    public function getDepotCode()
    {
        return $this->depot_code;
    }

    /**
     * @param string $depot_code
     * @return Depot
     */
    public function setDepotCode($depot_code)
    {
        $this->depot_code = $depot_code;
        return $this;
    }

    /**
     * @return Address
     */
    public function getDepotAddress()
    {
        return $this->depot_address;
    }

    /**
     * @param Address $depot_address
     * @return Depot
     */
    public function setDepotAddress(Address $depot_address = null)
    {
        $this->depot_address = $depot_address;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     * @return Depot
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

}
