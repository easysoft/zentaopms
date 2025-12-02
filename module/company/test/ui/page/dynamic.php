<?php
class dynamicPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $this->xpath = array(
            // 时间周期切换标签
            'all'            => '//*[@id="featureBar"]/menu/li[1]/a',
            'today'          => '//*[@id="featureBar"]/menu/li[2]/a',
            'yesterday'      => '//*[@id="featureBar"]/menu/li[3]/a',
            'thisWeek'       => '//*[@id="featureBar"]/menu/li[4]/a',
            'lastWeek'       => '//*[@id="featureBar"]/menu/li[5]/a',
            'thisMonth'      => '//*[@id="featureBar"]/menu/li[6]/a',
            'lastMonth'      => '//*[@id="featureBar"]/menu/li[7]/a',
            // 时间周期数量 (点击上面对应的标签才会显示)
            'allCount'       => '//*[@id="featureBar"]/menu/li[1]/a/span[2]',
            'todayCount'     => '//*[@id="featureBar"]/menu/li[2]/a/span[2]',
            'yesterdayCount' => '//*[@id="featureBar"]/menu/li[3]/a/span[2]',
            'thisWeekCount'  => '//*[@id="featureBar"]/menu/li[4]/a/span[2]',
            'lastWeekCount'  => '//*[@id="featureBar"]/menu/li[5]/a/span[2]',
            'thisMonthCount' => '//*[@id="featureBar"]/menu/li[6]/a/span[2]',
            'lastMonthCount' => '//*[@id="featureBar"]/menu/li[7]/a/span[2]',
            // 没有组权限用户拒绝信息框
            'denied'         => '//*[@id="denyBox"]/div[1]/div',
            // 下拉选择
            'userSelect'     => '(//div[contains(@class, "picker-select-single")])[1]',
            'productSelect'  => '(//div[contains(@class, "picker-select-single")])[2]',
            'projectSelect'  => '(//div[contains(@class, "picker-select-single")])[3]',
            'execSelect'     => '(//div[contains(@class, "picker-select-single")])[4]',
            // 删除用户选择
            'userDeselect'   => '//span[@class="close"]',
            // 搜索框
            'searchForm'     => '//button[contains(@class, "search-form-toggle")]',
            // 搜索按钮
            'searchBtn'      => '//button[@class= "btn primary"]',
            // 搜索结果验证
            'searchResult'   => '//*[@id="companyDynamic"]/div/ul/li/div[2]/div/ul/li/div/div/div/a',
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }
}