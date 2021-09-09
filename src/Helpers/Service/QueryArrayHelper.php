<?php

namespace Liushoukun\LaravelHelpers\Helpers\Service;

use Illuminate\Database\Eloquent\Builder;

class QueryArrayHelper
{

    /**
     * 构件查询器
     * @param Builder $query
     * @param $field
     * @param $condition
     * @return Builder
     */
    public static function query(Builder $query, $field, $condition)
    {
        if (blank($condition)) {
            return $query;
        }
        if (is_array($condition)) {
            $list = $condition;
        }
        if (is_string($condition)) {
            $list = explode(',', $condition);
        }
        $list = collect($list)->filter(function ($item) {
            return filled($item);
        });
        $list = $list->values()->toArray();
        if (count($list) < 1) {
            return $query;
        }
        if (count($list) > 1) {
            $query = $query->whereIn($field, $list);
        }
        if (count($list) == 1) {
            $query = $query->where($field, $list[0]);
        }
        return $query;
    }

}
