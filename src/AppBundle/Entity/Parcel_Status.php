<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="parcels_statuses")
 * @ORM\HasLifecycleCallbacks
 */
class Parcel_Status
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Parcel")
     * @ORM\JoinColumn(name="parcel_id", referencedColumnName="parcel_id")
     */
    private $parcel;


    /**
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="status_id")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Depot")
     * @ORM\JoinColumn(name="depot_id", referencedColumnName="depot_id")
     */
    private $depot;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @return Parcel
     */
    public function getParcel()
    {
        return $this->parcel;
    }

    /**
     * @param Parcel $parcel
     * @return Parcel_Status
     */
    public function setParcel(Parcel $parcel = null)
    {
        $this->parcel = $parcel;
        return $this;
    }

    /**
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param Status $status
     * @return Parcel_Status
     */
    public function setStatus(Status $status = null)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return Depot
     */
    public function getDepot()
    {
        return $this->depot;
    }

    /**
     * @param Depot $depot
     * @return Parcel_Status
     */
    public function setDepot(Depot $depot = null)
    {
        $this->depot = $depot;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     * @return Parcel_Status
     */
    public function setTimestamp(\DateTime $timestamp = null)
    {
        $this->timestamp = $timestamp;
        return $this;
    }


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamp()
    {
        $this->timestamp=new \DateTime(date("Y-m-d H:i:s"));
        if ($this->timestamp==null){
            $this->timestamp = new \DateTime(date("Y-m-d H:i:s"));
        }
    }



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
