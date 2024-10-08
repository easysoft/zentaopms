<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'firstID'        => "//*[@id='table-productplan-browse']/div[2]/div[1]/div/div[1]/div",
            'firstTitle'     => "//*[@id='table-productplan-browse']/div[2]/div[1]/div/div[2]/div/a",
            'firstBegin'     => "//*[@id='table-productplan-browse']/div[2]/div[2]/div/div[2]/div",
            'firstEnd'       => "//*[@id='table-productplan-browse']/div[2]/div[2]/div/div[3]/div",
            'search'         => "//*[@id='featureBar']/menu/li[last()]",
            'searchBtn'      => "//*[@id='mainContent']/div[1]/div/form/div[2]/button[1]",
            'selectAllBtn'   => "//*[@id='table-productplan-browse']/div[1]/div[1]/div/div[1]/div[1]/div/label",
            'batchEditBtn'   => "//*[@id='table-productplan-browse']/div[3]/nav[1]/button",
            'batchCloseBtn'  => "//*[@id='table-productplan-browse']/div[3]/nav[1]/button[2]",
            'batchStatusBtn' => "//*[@id='table-productplan-browse']/div[3]/nav[1]/a",
            'waitingStatus'  => "//*[@id='navStatus']/li[1]/a",
            'doingStatus'    => "//*[@id='navStatus']/li[2]/a",
            'doneStatus'     => "//*[@id='navStatus']/li[3]/a",
            'allTab'         => "//*[@id='featureBar']/menu/li[1]/a",
            'undoneTab'      => "//*[@id='featureBar']/menu/li[2]/a",
            'waitingTab'     => "//*[@id='featureBar']/menu/li[3]/a",
            'doingTab'       => "//*[@id='featureBar']/menu/li[4]/a",
            'doneTab'        => "//*[@id='featureBar']/menu/li[5]/a",
            'closedTab'      => "//*[@id='featureBar']/menu/li[6]/a",
            'allNum'         => "//*[@id='featureBar']/menu/li[1]/a/span[2]",
            'undoneNum'      => "//*[@id='featureBar']/menu/li[2]/a/span[2]",
            'waitingNum'     => "//*[@id='featureBar']/menu/li[3]/a/span[2]",
            'doingNum'       => "//*[@id='featureBar']/menu/li[4]/a/span[2]",
            'doneNum'        => "//*[@id='featureBar']/menu/li[5]/a/span[2]",
            'closedNum'      => "//*[@id='featureBar']/menu/li[6]/a/span[2]",
            'listBtn'        => "//button[@data-type='list']",
            'kanbanBtn'      => "//button[@data-type='kanban']",
            'orderByBtn'     => "//*[@id='actionBar']/button",//看板中的排序按钮
            'beginDesc'      => "/html/body/div[3]/menu/menu/li[1]",//计划开始时间倒序
            'beginAsc'       => "/html/body/div[3]/menu/menu/li[2]",//计划开始时间正序
            'firBeginToEnd'  => "//div[@class='card-footer']/div/div/span[2]",//第一个计划卡片中的开始时间到结束时间
            'secBeginToEnd'  => "(//div[@class='card-footer'])[2]/div/div/span[2]",//第二个计划卡片中的开始时间到结束时间
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
