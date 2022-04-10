<?php
// src/Model/Table/TransactionsTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;

class TransactionsTable extends Table
{
    public function initialize(array $config): void
    {
        $this->addBehavior('Timestamp');
        $this->hasMany('Transactions');
        $this->belongsTo('Cashdesks');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $check = function($transaction) {
            if ($transaction->name !== 'Adhérent non trouvé') {
                return true;
            }
            else {
                return false;
            }
            //return $commande->prix >= 100;
        };
        $rules->add($check, [
            'errorField' => 'adh',
            'message' => 'Adhérent non renseigné'
        ]);
        return $rules;
    }
}
?>