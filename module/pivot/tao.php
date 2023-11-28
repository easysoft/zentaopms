<?php
declare(strict_types=1);
class pivotTao extends pivotModel
{
    /**
     * 设置默认的过滤器
     * Set default filter.
     *
     * @param  array  $filters
     * @access public
     * @return void
     */
    protected function setFilterDefault(array &$filters): void
    {
        foreach($filters as &$filter)
        {
            if(empty($filter['default'])) continue;
            if(is_string($filter['default'])) $filter['default']= $this->processDateVar($filter['default']);
        }
    }
}
