<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="statuses")
 * @ORM\HasLifecycleCallbacks
 */
class Status
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $status_id;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $status_name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $status_desc;

    /**
     * @return integer
     */
    public function getStatusId()
    {
        return $this->status_id;
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        return $this->status_name;
    }

    /**
     * @param string $status_name
     * @return Status
     */
    public function setStatusName($status_name)
    {
        $this->status_name = $status_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatusDesc()
    {
        return $this->status_desc;
    }

    /**
     * @param string $status_desc
     * @return Status
     */
    public function setStatusDesc($status_desc)
    {
        $this->status_desc = $status_desc;
        return $this;
    }


}
