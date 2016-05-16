<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 02/05/2016
 * Time: 10:54
 */

namespace OC\UserBundle\Entity;


class Roles
{
    public static $roles = [
        'Administrateur du system'=>'ROLE_ADMIN',
        'DAF'=>'ROLE_DAF',
        'Secrétaire'=>'ROLE_SECRETAIRE',
        'Economat'=>'ROLE_ECONOMAT',
        'SG'=>'ROLE_SG'
    ];

}