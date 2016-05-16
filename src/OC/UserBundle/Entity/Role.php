<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 02/05/2016
 * Time: 10:32
 */

namespace OC\UserBundle\Entity;


class Role
{
    /**
     * @var $role string
     */
    private $role;

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }


}