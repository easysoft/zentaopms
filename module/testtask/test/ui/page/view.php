<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'begin'     => "//*[@id='basicInfo']/table/tbody/tr[7]/td",
            'status'    => "//*[@id='basicInfo']/table/tbody/tr[10]/td",
            'submitBtn' => "//button[@type='submit']",
            'buttons'   => "//*[@id='mainContent']/div[2]/div[1]/div[3]/div",
            /* 测试单ID及测试单名称 */
            'id'   => "//*[@id='mainContent']/div[1]/div/div/span[1]",
            'name' => "//*[@id='mainContent']/div[1]/div/div/span[2]",
            /* 标题旁的已删除标签 */
            'deletedLabel' => "//*[@id='mainContent']/div[1]/div/span"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
