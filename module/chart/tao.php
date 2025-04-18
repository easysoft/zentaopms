<?php
declare(strict_types=1);
class chartTao extends chartModel
{
    /**
     * 获取数据库查询结果。
     * Get rows.
     *
     * @param  string $defaultSql
     * @param  array  $filters
     * @param  string $date YEAR|MONTH|YEARWEEK|DATE
     * @param  string $group
     * @param  string $metric
     * @param  string $agg count|distinct|avg|sum|max|min
     * @access protected
     * @return array
     */
    protected function getRows(string $defaultSql, array $filters, string $date, string $group, string $metric, string $agg, string $driver): array
    {
        $groupSql = $groupBySql = "tt.`$group`";
        if(!empty($date))
        {
            $groupSql   = $date == 'MONTH' ? "YEAR(tt.`$group`) AS ttyear, $date(tt.`$group`) AS ttgroup" : "$date(tt.`$group`) AS $group";
            $groupBySql = $date == 'MONTH' ? "YEAR(tt.`$group`), $date(tt.`$group`)" : "$date(tt.`$group`)";
        }

        if($agg == 'distinct')
        {
            $aggSQL = "COUNT($agg tt.`$metric`) AS `$metric`";
        }
        elseif($agg == 'sum' || $agg == 'avg')
        {
            $aggSQL = "ROUND($agg(tt.`$metric`), 2) AS `$metric`";
        }
        else
        {
            $aggSQL = "$agg(tt.`$metric`) AS `$metric`";
        }

        $sql = "SELECT $groupSql,$aggSQL FROM ($defaultSql) tt";
        if(!empty($filters))
        {
            $wheres = array();
            foreach($filters as $field => $filter)
            {
                $wheres[] = "`$field` {$filter['operator']} {$filter['value']}";
            }

            $whereStr = implode(' AND ', $wheres);
            $sql .= " WHERE $whereStr";
        }
        $sql .= " GROUP BY $groupBySql";

        $dbh = $this->app->loadDriver($driver);
        return $dbh->query($sql)->fetchAll();
    }

    /**
     * 处理数据库查询结果。
     * Process rows.
     *
     * @param  array  $rows
     * @param  string $date YEAR|MONTH|YEARWEEK|DATE
     * @param  string $group
     * @param  string $metric
     * @access protected
     * @return array
     */
    protected function processRows(array $rows, string $date, string $group, string $metric): array
    {
        $stat = array();
        foreach($rows as $row)
        {
            if(!empty($date) and $date == 'MONTH')
            {
                $stat[sprintf("%04d", $row->ttyear) . '-' . sprintf("%02d", $row->ttgroup)] = $row->$metric;
            }
            elseif(!empty($date) and $date == 'YEARWEEK')
            {
                $yearweek  = sprintf("%06d", $row->$group);
                $year = substr($yearweek, 0, strlen($yearweek) - 2);
                $week = substr($yearweek, -2);

                $weekIndex = in_array($this->app->getClientLang(), array('zh-cn', 'zh-tw')) ? sprintf($this->lang->chart->groupWeek, $year, $week) : sprintf($this->lang->chart->groupWeek, $week, $year);
                $stat[$weekIndex] = $row->$metric;
            }
            elseif(!empty($date) and $date == 'YEAR')
            {
                $stat[sprintf("%04d", $row->$group)] = $row->$metric;
            }
            else
            {
                $stat[$row->$group] = $row->$metric;
            }
        }

        return $stat;
    }

    /**
     * 根据设置转换字段名称。
     * Switch field name.
     *
     * @param  array  $fields
     * @param  array  $langs
     * @param  array  $metrics
     * @param  string $index
     * @access protected
     * @return string
     */
    protected function switchFieldName(array $fields, array $langs, array $metrics, string $index): string
    {
        $fieldName = $fields[$metrics[$index]]['name'];

        if(!empty($fields[$metrics[$index]]['object']) and !empty($fields[$metrics[$index]]['field']))
        {
            $relatedObject = $fields[$metrics[$index]]['object'];
            $relatedField  = $fields[$metrics[$index]]['field'];

            $this->app->loadLang($relatedObject);
            $fieldName = isset($this->lang->$relatedObject->$relatedField) ? $this->lang->$relatedObject->$relatedField : $fieldName;
        }

        $clientLang = $this->app->getClientLang();
        if(isset($langs[$metrics[$index]]) and !empty($langs[$metrics[$index]][$clientLang])) $fieldName = $langs[$metrics[$index]][$clientLang];

        return $fieldName;
    }
}
