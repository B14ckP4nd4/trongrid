<?php

namespace BlackPanda\Trongrid\Objects;

use BlackPanda\Trongrid\utils\Math;
use Carbon\Carbon;

class TRC20Transaction
{
    public $hash;
    public $token = [];
    public $insertTime;
    public $dataTime;
    public $from;
    public $to;
    public $type;
    public $amount;

    public function __construct($data)
    {
        $this->hash = $data->transaction_id;
        $this->token = [
            'symbol' => $data->token_info->symbol,
            'contractAddress' => $data->token_info->address,
            'decimals' => $data->token_info->decimals,
            'name' => $data->token_info->name,
        ];
        $insertTime = Carbon::createFromTimestampMs($data->block_timestamp);
        $this->insertTime = $data->block_timestamp;
        $this->dataTime = $insertTime->toDateTimeString();
        $this->from = $data->from;
        $this->to = $data->to;
        $this->type = $data->type;
        $this->amount = Math::toDecimal($data->value,$data->token_info->decimals);
    }

}
