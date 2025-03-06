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
            /* 列表 */
            'firstCheckbox'   => "//*[@id='tasks']/div[2]/div[1]/div/div[1]/div/div",
            'firstName'       => "//*[@id='tasks']/div[2]/div[1]/div/div[2]/div/a",
            'firstStatus'     => "//*[@id='tasks']/div[2]/div[2]/div/div[2]/div/div/span",
            'firstAssignedTo' => "//*[@id='tasks']/div[2]/div[2]/div/div[3]/div/a/span",
            'statusBtn'       => "//*[@id='tasks']/div[3]/nav[1]/nav/button[2]",
            'closedBtn'       => "(//div[@class='item-title'])[1]",
            'cancelBtn'       => "(//div[@class='item-title'])[2]",
            'assignedToBtn'   => "//*[@id='tasks']/div[3]/nav[1]/button[2]",
            'users'           => "//*[@data-page='execution-task']/div[2]/menu/menu//div[@class='item-title' and text()='admin']",
            'modal'           => "//div[contains(@id,'modal')]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
