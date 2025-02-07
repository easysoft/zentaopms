<?php
class reportPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 左侧报表 */
            'testTaskPerRunResult' => "//*[@value='testTaskPerRunResult']",
            'testTaskPerType'      => "//*[@value='testTaskPerType']",
            'testTaskPerModule'    => "//*[@value='testTaskPerModule']",
            'testTaskPerRunner'    => "//*[@value='testTaskPerRunner']",
            'createBtn'            => "//*[@id='mainContent']/div[2]/div[1]/button[2]",
            /* 报表类型标签 */
            'pie'  => "//*[@id='pie']",
            'bar'  => "//*[@id='bar']",
            'line' => "//*[@id='line']",
            /* 数据 */
            'title'    => "//div[@class='tab-pane active']/div[2]/div[1]/div[1]/div[1]",
            'itema'    => "//div[@class='tab-pane active']//tbody/tr[2]/td[1]",
            'valuea'   => "//div[@class='tab-pane active']//tbody/tr[2]/td[2]",
            'percenta' => "//div[@class='tab-pane active']//tbody/tr[2]/td[3]",
            'itemb'    => "//div[@class='tab-pane active']//tbody/tr[3]/td[1]",
            'valueb'   => "//div[@class='tab-pane active']//tbody/tr[3]/td[2]",
            'percentb' => "//div[@class='tab-pane active']//tbody/tr[3]/td[3]",
            'itemc'    => "//div[@class='tab-pane active']//tbody/tr[4]/td[1]",
            'valuec'   => "//div[@class='tab-pane active']//tbody/tr[4]/td[2]",
            'percentc' => "//div[@class='tab-pane active']//tbody/tr[4]/td[3]",
            'itemd'    => "//div[@class='tab-pane active']//tbody/tr[5]/td[1]",
            'valued'   => "//div[@class='tab-pane active']//tbody/tr[5]/td[2]",
            'percentd' => "//div[@class='tab-pane active']//tbody/tr[5]/td[3]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
