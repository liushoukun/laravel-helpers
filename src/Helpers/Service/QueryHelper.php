<?php

namespace Liushoukun\LaravelHelpers\Helpers\Service;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class QueryHelper
{

    public static function explode($value, string $valueType = 'int')
    {
        $list = [];
        if (is_array($value)) {
            $list = $value;
        } else {
            $value = (string)$value;
            $list  = explode(',', $value);
        }
        if (blank($list)) {
            return $query;
        }
        $list      = collect($list)->filter(function ($item) {
            return filled($item);
        });
        $valueType = $valueType . 'val';
        $list      = $list->map(function ($item) use ($valueType) {
            return ($valueType)($item);
        });
        return $list->toArray();
    }

    public static function ranges(Builder $query, array $fields, array $conditions)
    {
        foreach ($fields as $key => $value) {
            if (is_numeric($key)) {
                $field = (string)($value);
                $type  = 'int';
            } else {
                $field = $key;
                $type  = $value;
            }
            $query = self::range($query, $field, $conditions[$field] ?? '', $type);
        }
        return $query;
    }

    public static function range(Builder $query, $field, $condition, $valueType = 'int')
    {
        if (blank($condition)) {
            return $query;
        }
        $list = [];
        if (is_array($condition)) {
            $list = $condition;
        } else {
            $condition = (string)$condition;
            $list      = explode(',', $condition);
        }

        $list  = collect($list)->map(function ($item) use ($valueType) {
            if (filled($item)) {
                // 格式格式化数据
                // 如果是时间 datetime // todo
                if ($valueType === 'datetime') {
                    return $item;
                } else {
                    return ($valueType . 'val')($item);
                }
            } else {
                return null;
            }
        });
        $start = $list[0] ?? null;
        $end   = $list[1] ?? null;
        if (filled($start)) {
            $query = $query->where($field, '>=', $start);
        }
        if (filled($end)) {
            $query = $query->where($field, '<', $end);
        }
        return $query;
    }

    public static function arrays(Builder $query, array $fields, array $conditions)
    {
        foreach ($fields as $key => $value) {
            if (is_numeric($key)) {
                $field = (string)($value);
                $type  = 'int';
            } else {
                $field = $key;
                $type  = $value;
            }
            $query = self::array($query, $field, $conditions[$field] ?? '', $type);
        }
        return $query;
    }

    /**
     * 构件查询器
     * @param Builder $query
     * @param $field
     * @param $condition
     * @return Builder
     */
    public static function array(Builder $query, $field, $condition, $valueType = 'int')
    {
        if (blank($condition)) {
            return $query;
        }
        $list = [];
        if (is_array($condition)) {
            $list = $condition;
        } else {
            $condition = (string)$condition;
            $list      = explode(',', $condition);
        }
        if (blank($list)) {
            return $query;
        }
        $list      = collect($list)->filter(function ($item) {
            return filled($item);
        });
        $valueType = $valueType . 'val';
        $list      = $list->map(function ($item) use ($valueType) {
            return ($valueType)($item);
        });
        $list      = $list->values()->toArray();
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
     * 等于
     * @param Builder $query
     * @param array $fields
     * @param array $conditions
     * @return Builder
     */
    public static function equals(Builder $query, array $fields, array $conditions)
    {
        foreach ($fields as $key => $value) {
            if (is_numeric($key)) {
                $field = (string)($value);
                $type  = 'int';
            } else {
                $field = $key;
                $type  = $value;
            }
            if (filled($conditions[$field] ?? null)) {

                $query = $query->where($field, ($type . 'val')($conditions[$field]));
            }
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

    /**
     * 排序 多字段
     * @param Builder $query
     * @param array $sorts
     * @param string $sort
     * @return Builder
     */
    public static function orderBy(Builder $query, array $sorts, string $sort = '')
    {
        $orderBuys = $sorts[$sort]['sorts'] ?? $sorts[$sort] ?? [];

        foreach ($orderBuys as $field => $type) {
             $query = $query->orderBy($field, $type);
        }
        return $query;
    }

}
