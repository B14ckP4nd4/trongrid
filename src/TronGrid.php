<?php

namespace BlackPanda\Trongrid;

use BlackPanda\Trongrid\Parse\Transactions;

class TronGrid
{
    protected $tronGridManager;

    public function __construct()
    {
        $this->tronGridManager = new TronGridManager();
    }

    /**
     * @throws \Exception
     */
    public function getAccountTRC20Transactions(string $address, string $startTimeStamp, string $endTimeStamp, string $contractAddress, int $limit, bool $onlyConfirmed = false, bool $onlyUnConfirmed = false, bool $onlyTo = false, bool $onlyFrom = false, string $sort = 'block_timestamp,asc')
    {
        $params = [];

        if ($startTimeStamp)
            $params['min_timestamp'] = $startTimeStamp;

        if ($endTimeStamp)
            $params['max_timestamp'] = $endTimeStamp;

        if ($contractAddress)
            $params['contract_address'] = $contractAddress;

        if ($onlyConfirmed)
            $params['only_confirmed'] = true;

        if ($onlyUnConfirmed)
            $params['only_unconfirmed'] = true;

        if ($limit)
            $params['limit'] = $limit;

        if ($sort)
            $params['order_by'] = $sort;

        if ($onlyTo)
            $params['only_to'] = true;

        if ($onlyFrom)
            $params['only_from'] = true;


        $transactions = $this->getAllTRC20Transactions($address,$params);

        if ($transactions->data)
            return Transactions::parseTrc20($transactions);

        return false;
    }


    private function getAllTRC20Transactions(string $address, $params): \stdClass
    {

        $pages = [];
        $total = 0;
        $fingerPrint = false;
        while (true) {
            if ($fingerPrint)
                $params['fingerprint'] = $fingerPrint;

            $data = $this->tronGridManager->request("v1/accounts/{$address}/transactions/trc20", $params);

            if (!isset($data->success))
                throw new \Exception("There is a problem to get data from Server");

            $pages[] = $data->data;
            $total += $data->meta->page_size;

            if (!isset($data->meta->fingerprint))
                break;

            $fingerPrint = $data->meta->fingerprint;

            // small delay
            sleep(2);
        }

        $result = new \stdClass();

        $result->data = array_merge(...$pages);
        $result->total = $total;


        return $result;

    }


}
