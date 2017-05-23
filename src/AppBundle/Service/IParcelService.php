<?php

namespace AppBundle\Service;

use AppBundle\Entity\Parcel;
use AppBundle\Entity\Parcel_Status;
use AppBundle\Entity\Status;
use AppBundle\Entity\Depot;
use Symfony\Component\Form\FormInterface;

interface IParcelService {

    /**
     * @return Parcel[]
     */
    public function getParcels();

    /**
     * @param $parcelId int
     * @return Parcel
     */
    public function getParcelById($parcelId);

    /**
     * @return int
     */
    public function getMaxParcelId();

    /**
     * @param $parcelCode string
     * @return Parcel
     */
    public function getParcelByCode($parcelCode);

    /**
     * @param $userId int
     * @param $direction boolean
     * @return Parcel[]
     */
    public function getParcelByUserId($userId, $direction);

    /**
     * @param $parcelId int
     * @return Parcel_Status[]
     */
    public function getStatusesByParcel($parcelId);

    /**
     * @param $oneParcel Parcel
     * @param $senderId int
     * @return FormInterface
     */
    public function getParcelPostForm($oneParcel, $senderId);

    /**
     * @param $oneParcel Parcel
     * @return FormInterface
     */
    public function getParcelEditForm($oneParcel);

    /**
     * @param $oneParcel Parcel
     * @param $statusId int
     * @param $depotId int
     */
    public function saveParcelStatus($oneParcel, $statusId, $depotId);

    /**
     * @param $oneParcel Parcel
     */
    public function saveParcel($oneParcel);

}