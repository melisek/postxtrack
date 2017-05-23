<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="parcels")
* @ORM\HasLifecycleCallbacks
*/
class Parcel
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $parcel_id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $parcel_code;

    /**
     * @ORM\ManyToOne(targetEntity="User_Address")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     */
    private $sender_address;

    /**
     * @ORM\ManyToOne(targetEntity="User_Address")
     * @ORM\JoinColumn(name="rec_id", referencedColumnName="id")
     */
    private $rec_address;

    /**
     * @return integer
     */
    public function getParcelId()
    {
        return $this->parcel_id;
    }

    /**
     * @return string
     */
    public function getParcelCode()
    {
        return $this->parcel_code;
    }

    /**
     * @param string $parcel_code
     * @return Parcel
     */
    public function setParcelCode($parcel_code)
    {
        $this->parcel_code = $parcel_code;
        return $this;
    }

    /**
     * @return User_Address
     */
    public function getSenderAddress()
    {
        return $this->sender_address;
    }

    /**
     * @param User_Address $sender_address
     * @return Parcel
     */
    public function setSenderAddress(User_Address $sender_address = null)
    {
        $this->sender_address = $sender_address;
        return $this;
    }

    /**
     * @return User_Address
     */
    public function getRecAddress()
    {
        return $this->rec_address;
    }

    /**
     * @param User_Address $rec_address
     * @return Parcel
     */
    public function setRecAddress(User_Address $rec_address = null)
    {
        $this->rec_address = $rec_address;
        return $this;
    }

    public function __construct($maxParcelId)
    {
        $maxParcelId += 1;
        if($maxParcelId > 9)
        {
            $this->parcel_code = "P00".$maxParcelId;
        } else {
            $this->parcel_code = "P000".$maxParcelId;
        }

    }

}
