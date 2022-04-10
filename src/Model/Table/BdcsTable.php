<?php
// src/Model/Table/BdcsTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;

class BdcsTable extends Table
{
    public function initialize(array $config): void
    {
        $this->addBehavior('Timestamp');
        $this->hasMany('Bdcs');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        //$rules->add($rules->isUnique(['email']));
        //$rules->add($rules->isUnique(['adh_id']));
        /*$rules->add($rules->isUnique(['email']), [
            'errorField' => 'status',
            'message' => 'L\'email doit être unique'
        ]);
        $rules->add($rules->isUnique(['adh_id']), [
            'errorField' => 'status',
            'message' => 'L\'id doit être unique'
        ]);*/
        return $rules;
    }

    public function getName($id) {
        $bdc = $this->Bdcs->get($id);
        return $bdc->name;
    }
    
}
?>