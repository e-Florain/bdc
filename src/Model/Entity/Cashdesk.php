<?php
// src/Model/Entity/Cashdesk.php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Cashdesk extends Entity
{
    protected $_accessible = [
        '*' => true
        //'id' => false
    ];
}
?>