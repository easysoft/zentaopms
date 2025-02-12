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
            'pie'  => "//*[@data-key='pie']/a",
            'bar'  => "//*[@data-key='bar']/a",
            'line' => "//*[@data-key='line']/a",
            /* 报表标题 */
            'title' => "//div[@class='tab-pane active']/div[2]/div[1]/div[1]/div[1]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
