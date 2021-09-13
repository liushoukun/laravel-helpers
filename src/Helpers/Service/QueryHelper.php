<?php

namespace Liushoukun\LaravelHelpers\Helpers\Service;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class QueryHelper
{

    /**
     * 构件查询器
     * @param Builder $query
     * @param $field
     * @param $condition
     * @return Builder
     */
    public static function array(Builder $query, $field, $condition)
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

    /**
     * 时间构件器
     * @param Builder $query
     * @param $field
     * @param $condition
     */
    public static function times(Builder $query, $field, $condition, $timeType = 'd')
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
        $start = $list[0] ?? null;
        $end   = $list[1] ?? null;
        // 存在开始时间
        if (filled($start)) {
            $start = Carbon::parse($start);
            ($timeType === 'd') ? $start->startOfDay() : ''; //日
            ($timeType === 'm') ? $start->startOfMonth() : ''; // 月
            $start = $start->toDateTimeString();
            $query = $query->where($field, '>=', $start);
        }
        if (filled($end)) {
            $end = Carbon::parse($end);
            ($timeType === 'd') ? $end->endOfDay() : '';
            ($timeType === 'm') ? $end->endOfMonth() : '';
            $end   = $end->toDateTimeString();
            $query = $query->where($field, '<=', $end);
        }
        return $query;
    }

}
