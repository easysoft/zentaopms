<?php
class bugPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 标签 */
            'allTab'          => "//*[@id='featureBar']/menu/li[1]/a",
            'unresolvedTab'   => "//*[@id='featureBar']/menu/li[2]/a",
            'num'             => "//*[@id='table-execution-bug']/div[3]/div[2]/strong[1]",
            /* 列表 */
            'firstCheckbox'   => "//*[@id='table-execution-bug']/div[2]/div[1]/div/div[1]/div/div",
            'firstName'       => "//*[@id='table-execution-bug']/div[2]/div[1]/div/div[2]/div/a",
            'firstAssignedTo' => "//*[@id='table-execution-bug']/div[2]/div[2]/div/div[7]/div/a/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
