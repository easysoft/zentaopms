<?php
class chartZen extends chart
{
    /**
     * 根据 chartList 获取要查看的图表。
     * Get the charts to view by chartList.
     *
     * @param  array  $chartList
     * @access public
     * @return array
     */
    public function getChartsToView(array $chartList): array
    {
        $charts = array();
        foreach($chartList as $chart)
        {
            $group = $chart['groupID'];
            $chart = $this->chart->getByID($chart['chartID']);
            if($chart)
            {
                $chart->currentGroup = $group;
                $charts[] = $chart;
            }
        }

        return $charts;
    }
}
