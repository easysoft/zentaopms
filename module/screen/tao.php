<?php
declare(strict_types=1);
class screenTao extends screenModel
{
    /**
     * 设置默认的图表配置。
     * Set the default chart config.
     *
     * @param string $type
     * @param object $component
     * @return bool
     */
    protected function setChartDefault(string $type, object $component): bool
    {
        if(!isset($this->config->screen->chart->default->{$type})) return false;
        $chartConfig = $this->config->screen->chart->default->{$type};
        foreach(get_object_vars($chartConfig) as $key => $value) $component->{$key} = $value;

        return true;
    }

    /**
     * 处理雷达图表的数据。
     * Set the chart data.
     *
     * @param  object $sql
     * @param  object $settings
     * @param  array  $indicator
     * @param  array  $seriesData
     * @return void
     */
    protected function processRadarData(string $sql, object $settings, array &$indicator, array &$seriesData)
    {
        $results = $this->dao->query($sql)->fetchAll();
        $group   = $settings->group[0]->field;

        /* 通过配置获取指标。 */
        /* Get the metric by the setting. */
        $metrics = array();
        foreach($settings->metric as $metric) $metrics[$metric->key] = array('field' => $metric->field, 'name' => $metric->name, 'value' => 0);

        /* 计算指标的值。 */
        /* Calculate the value of the metric. */
        foreach($results as $result)
        {
            if(isset($metrics[$result->$group]))
            {
                $field = $metrics[$result->$group]['field'];
                $metrics[$result->$group]['value'] += $result->$field;
            }
        }

        $max = 0;
        foreach($metrics as $data) $max = $data['value'] > $max ? $data['value'] : $max;

        /* 设置指标和数据。 */
        /* Set the indicator and data. */
        $data  = array('name' => '', 'value' => array());
        $value = array();
        foreach($metrics as $metric)
        {
            $indicator[]     = array('name' => $metric['name'], 'max' => $max);
            $data['value'][] = $metric['value'];
            $value[]         = $metric['value'];
        }
        $seriesData[] = $data;

        return $value;
    }
}
