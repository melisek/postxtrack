<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="addresses")
 * @ORM\HasLifecycleCallbacks
 */
class Address
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $address_id;

    /**
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    private $address_postal_code;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $address_country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address_county;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address_city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address_street1;

    /**
    * @ORM\Column(type="string", length=255, nullable=true)
    */
    private $address_street2;

    /**
     * @ORM\Column(type="boolean")
     */
    private $address_active;

    /**
     * Getters & setters
     */

    /**
     * @return integer
     */
    public function getAddressId()
    {
        return $this->address_id;
    }

    /**
     * @return string
     */
    public function getAddressPostalCode()
    {
        return $this->address_postal_code;
    }

    /**
     * @param string $address_postal_code
     * @return Address
     */
    public function setAddressPostalCode($address_postal_code)
    {
        $this->address_postal_code = $address_postal_code;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddressCountry()
    {
        return $this->address_country;
    }

    /**
     * @param string $address_country
     * @return Address
     */
    public function setAddressCountry($address_country)
    {
        $this->address_country = $address_country;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddressCounty()
    {
        return $this->address_county;
    }

    /**
     * @param string $address_county
     * @return Address
     */
    public function setAddressCounty($address_county)
    {
        $this->address_county = $address_county;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddressCity()
    {
        return $this->address_city;
    }

    /**
     * @param string $address_city
     * @return Address
     */
    public function setAddressCity($address_city)
    {
        $this->address_city = $address_city;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddressStreet1()
    {
        return $this->address_street1;
    }

    /**
     * @param string $address_street1
     * @return Address
     */
    public function setAddressStreet1($address_street1)
    {
        $this->address_street1 = $address_street1;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddressStreet2()
    {
        return $this->address_street2;
    }

    /**
     * @param string $address_street2
     * @return Address
     */
    public function setAddressStreet2($address_street2)
    {
        $this->address_street2 = $address_street2;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getAddressActive()
    {
        return $this->address_active;
    }

    /**
     * @param string $address_active
     * @return Address
     */
    public function setAddressActive($address_active)
    {
        $this->address_active = $address_active;
        return $this;
    }
}
