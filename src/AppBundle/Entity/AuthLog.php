<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use XM\SecurityBundle\Entity\AuthLog as BaseAuthLog;

/**
 * AuthLog
 *
 * @ORM\Entity
 * @ORM\Table(options={
 *     "collate"="utf8mb4_unicode_ci",
 *     "charset"="utf8mb4"
 * })
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