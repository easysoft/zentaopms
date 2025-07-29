<?php
class taskPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 标签 */
            'allTab'          => "//*[@id='featureBar']/menu/li[1]/a",
            'unclosedTab'     => "//*[@id='featureBar']/menu/li[2]/a",
            'assignedtomeTab' => "//*[@id='featureBar']/menu/li[3]/a",
            'myInvolvedTab'   => "//*[@id='featureBar']/menu/li[4]/a",
            'assignedByMeTab' => "//*[@id='featureBar']/menu/li[5]/a",
            'needConfirmTab'  => "//*[@id='featureBar']/menu/li[6]/a",
            'MoreTab'         => "//*[@id='featureBar']/menu/li[7]/a",
            'waitingTab'      => "//*[@id='status']/menu/menu/li[1]/a",
            'doingTab'        => "//*[@id='status']/menu/menu/li[2]/a",
            'undoneTab'       => "//*[@id='status']/menu/menu/li[3]/a",
            'finushedByMeTab' => "//*[@id='status']/menu/menu/li[4]/a",
            'doneTab'         => "//*[@id='status']/menu/menu/li[5]/a",
            'closedTab'       => "//*[@id='status']/menu/menu/li[6]/a",
            'cancelTab'       => "//*[@id='status']/menu/menu/li[7]/a",
            'delayedTab'      => "//*[@id='status']/menu/menu/li[8]/a",
            'num'             => "//*[@id='tasks']/div[3]/div[2]/strong[1]",
            'numInLite'       => "//*[@id='table-execution-task']/div[3]/div[2]/strong[1]",
            /* 列表 */
            'firstCheckbox'   => "(//div[@data-type='checkID' and @data-col='id'])[2]//label",
            'firstName'       => "(//div[@data-col='name' and @data-type='nestedTitle'])[2]//a",
            'firstStatus'     => "//div[@class='dtable-block dtable-body'][.//div[@data-type='checkID' and @data-col='id']]/div[2]//div[@data-type='status']//span",
            'firstAssignedTo' => "//div[@class='dtable-block dtable-body'][.//div[@data-type='checkID' and @data-col='id']]/div[2]//div[@data-type='assign']//span",
            'statusBtn'       => "(//div[@class='dtable-footer']//span[@class='caret-up'])[1]",
            'closedBtn'       => "(//div[@class='item-title'])[1]",
            'cancelBtn'       => "(//div[@class='item-title'])[2]",
            'assignedToBtn'   => "(//div[@class='dtable-footer']//span[@class='caret-up'])[3]",
            'users'           => "//div[@class='item-title' and text()='admin']",
            'modal'           => "//div[contains(@id,'modal')]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
