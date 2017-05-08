<?php
namespace Masca\EtudiantBundle\Model;
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/8/17
 * Time: 2:40 PM
 */
class Nb
{
    /**
     * @var string
     */
    private $nb;

    /**
     * @return string
     */
    public function getNb()
    {
        return $this->nb;
    }

    /**
     * @param string $nb
     */
    public function setNb($nb)
    {
        $this->nb = $nb;
    }
    
}