<?php
class bugPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 标签 */
            'allTab'          => "//*[@id='featureBar']/menu/li[1]/a",
            'unresolvedab'    => "//*[@id='featureBar']/menu/li[2]/a",
            'num'             => "//*[@id='table-execution-bug']/div[3]/div[2]/strong[1]",
            /* 列表 */
            'firstCheckbox'   => "//*[@id='table-execution-task']/div[2]/div[1]/div/div[1]/div/div",
            'firstName'       => "//*[@id='table-execution-task']/div[2]/div[1]/div/div[2]/div/a",
            'firstStatus'     => "//*[@id='table-execution-task']/div[2]/div[2]/div/div[2]/div/span",
            'firstAssignedTo' => "//*[@id='table-execution-task']/div[2]/div[2]/div/div[3]/div/a/span",
            'statusBtn'       => "//*[@id='table-execution-task']/div[3]/nav[1]/nav/button[2]",
            'closedBtn'       => "(//*[@data-page='execution-task']/div[2]/menu/menu//div[@class='item-title'])[1]",
            'cancelBtn'       => "(//*[@data-page='execution-task']/div[2]/menu/menu//div[@class='item-title'])[2]",
            'assignedToBtn'   => "//*[@id='table-execution-task']/div[3]/nav[1]/button[2]",
            'users'           => "//*[@data-page='execution-task']/div[2]/menu/menu//div[@class='item-title' and text()='admin']",
            'modal'           => "//div[contains(@id,'modal')]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
