<?php

namespace App\Services;

use App\Helpers\PaginatorHelper;

abstract class Service
{
    private $listParams = [];

    public function startList(array &$params)
    {
        $paginateArgs = PaginatorHelper::extractArgs($params);

        $this->listParams = [
            'likes' => [],
            'empties' => [],
            'notEmpty' => [],
        ];

        if (isset($params['like'])) {
            $this->listParams['likes'] = json_decode($params['like'], true);
            unset($params['like']);
        }

        if (!empty($params['empty'])) {
            $this->listParams['empties'] = explode(',', $params['empty']);
            unset($params['empty']);
        }

        if (!empty($params['notempty'])) {
            $this->listParams['notEmpty'] = explode(',', $params['notempty']);
            unset($params['notempty']);
        }

        if (!empty($params['limit'])) {
            $this->listParams['limit'] = $params['limit'];
            unset($params['notempty']);
        }
    }

    public function applyToList(&$seek)
    {
        foreach ($this->listParams['likes'] as $like) {
            $seek->where($like['key'], 'ilike', $like['valor']);
        }

        foreach ($this->listParams['empties'] as $field) {
            $seek->whereNull($field);
        }

        foreach ($this->listParams['notEmpty'] as $field) {
            $seek->whereNotNull($field);
        }

        if (isset($this->listParams['limit'])) {
            $seek->limit($this->listParams['limit']);
        }
    }
}
