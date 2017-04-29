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
        'Economa université'=>'ROLE_ECO_U',
        'Economa lycée'=>'ROLE_ECO_L',
        'SG université'=>'ROLE_SG_U',
        'SG lycée'=>'ROLE_SG_L'
    ];

}