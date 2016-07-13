<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/13/16
 * Time: 10:58 AM
 */

namespace OC\UserBundle\Entity;


class ChandePassword
{
    /**
     * @var string
     */
    private $currentPassword;

    /**
     * @var string
     */
    private $newPassword;

    /**
     * @return string
     */
    public function getCurrentPassword()
    {
        return $this->currentPassword;
    }

    /**
     * @param string $currentPassword
     */
    public function setCurrentPassword($currentPassword)
    {
        $this->currentPassword = $currentPassword;
    }

    /**
     * @return string
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param string $newPassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }


}