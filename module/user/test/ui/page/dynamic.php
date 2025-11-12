<?php
class dynamicPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $this->xpath = array(
            // 顶部用户选择器
            'userPicker'     => '//div[contains(@class, "picker-select-single")]',
            // 时间周期切换标签
            'all'            => '//*[@id="mainContent"]/div/div[1]/div/div/menu/li[1]/a',
            'today'          => '//*[@id="mainContent"]/div/div[1]/div/div/menu/li[2]/a',
            'yesterday'      => '//*[@id="mainContent"]/div/div[1]/div/div/menu/li[3]/a',
            'thisWeek'       => '//*[@id="mainContent"]/div/div[1]/div/div/menu/li[4]/a',
            'lastWeek'       => '//*[@id="mainContent"]/div/div[1]/div/div/menu/li[5]/a',
            'thisMonth'      => '//*[@id="mainContent"]/div/div[1]/div/div/menu/li[6]/a',
            'lastMonth'      => '//*[@id="mainContent"]/div/div[1]/div/div/menu/li[7]/a',
            // 时间周期数量 (点击上面对应的标签才会显示)
            'allCount'       => '//*[@id="mainContent"]/div/div[1]/div/div/menu/li[1]/a/span[2]',
            'todayCount'     => '//*[@id="mainContent"]/div/div[1]/div/div/menu/li[2]/a/span[2]',
            'yesterdayCount' => '//*[@id="mainContent"]/div/div[1]/div/div/menu/li[3]/a/span[2]',
            'thisWeekCount'  => '//*[@id="mainContent"]/div/div[1]/div/div/menu/li[4]/a/span[2]',
            'lastWeekCount'  => '//*[@id="mainContent"]/div/div[1]/div/div/menu/li[5]/a/span[2]',
            'thisMonthCount' => '//*[@id="mainContent"]/div/div[1]/div/div/menu/li[6]/a/span[2]',
            'lastMonthCount' => '//*[@id="mainContent"]/div/div[1]/div/div/menu/li[7]/a/span[2]',
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }
}