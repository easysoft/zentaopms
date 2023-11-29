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
     * @return void
     */
    protected function setChartDefault(string $type, object $component): void
    {
        $chartConfig = $this->config->screen->chart->default->{$type};
        foreach(get_object_vars($chartConfig) as $key => $value) $component->{$key} = $value;
    }
}
