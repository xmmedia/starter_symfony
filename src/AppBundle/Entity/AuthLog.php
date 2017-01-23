<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XM\SecurityBundle\Entity\AuthLog as BaseAuthLog;

/**
 * AuthLog
 *
 * @ORM\Entity
 * @ORM\Table
 */
class AuthLog extends BaseAuthLog
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}