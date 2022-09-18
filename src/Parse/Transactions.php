<?php

namespace BlackPanda\Trongrid\Parse;

use BlackPanda\Trongrid\Objects\TRC20Transaction;

class Transactions
{
    public static function parseTrc20($transactions)
    {
        $results = [];
        foreach($transactions->data as $transaction)
        {
            $results[] = new TRC20Transaction($transaction);
        }

        return $results;
    }
}
