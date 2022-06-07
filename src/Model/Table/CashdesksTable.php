<?php
// src/Model/Table/CashdesksTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;

class CashdesksTable extends Table
{
    public function initialize(array $config): void
    {
        $this->addBehavior('Timestamp');
        $this->hasMany('Cashdesks');
        $this->belongsTo('Bdcs');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['asso_id']), [
            'errorField' => 'status',
            'message' => 'L\'id doit être unique'
        ]);
        /*$rules->add($rules->date(['date']), [
            'errorField' => 'status',
            'message' => 'La date n\'a pas un bon format'
        ]);*/
        return $rules;
    }
}
?>