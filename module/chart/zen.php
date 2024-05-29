<?php
class chartZen extends chart
{
    /**
     * 根据 chartList 获取要查看的图表。
     * Get the charts to view by chartList.
     *
     * @param  array  $chartList
     * @access protected
     * @return array
     */
    protected function getChartsToView(array $chartList): array
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

    /**
     * 根据 chartID 和 filterValues 获取要筛选的图表。
     * Get the charts to filter by chartID and filterValues.
     *
     * @param  int    $groupID
     * @param  int    $chartID
     * @param  array  $filterValues
     * @access protected
     * @return object|null
     */
    protected function getChartToFilter(int $groupID, int $chartID, array $filterValues = array()): object|null
    {
        $chart = $this->chart->getByID($chartID);
        if(!$chart) return null;

        $chart->currentGroup = $groupID;

        foreach($filterValues as $key => $value) $chart->filters[$key]['default'] = $value;

        return $chart;
    }

    /**
     * 获取菜单项。
     * Get menu items.
     *
     * @param  array $menus
     * @access protected
     * @return array
     */
    protected function getMenuItems(array $menus): array
    {
        $items = array();
        foreach($menus as $menu)
        {
            if($menu->parent == 0) continue;
            $items[] = $menu;
        }

        return $items;
    }
}
