<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /*泳道*/
            'laneMoreBtn'     => '//*[@id="kanbanList"]/div/div/div/div[2]/div[2]/div[1]/div[1]/nav/button',
            'editLaneNameBtn' => '//*[@z-key-path="editLaneName"]',
            'firLaneName'     => '//*[@id="kanbanList"]/div/div/div/div[2]/div[2]/div[1]/div[1]/div',
            /*看板列*/
            'createBtn'     => '//*[@id="kanbanList"]/div/div/div/div[2]/div[1]/div[2]/div[1]/div/nav/button[1]',
            'columnMoreBtn' => '//*[@id="kanbanList"]/div/div/div/div[2]/div[1]/div[2]/div[1]/div/nav/button[2]',
            'setColumnBtn'  => '//li[@z-type="item"][1]',
            'firColumnName' => '//*[@id="kanbanList"]/div/div/div/div[2]/div[1]/div[2]/div[1]/div/div/span[1]',
            /*卡片*/
            'firCardName' => '//*[@id="kanbanList"]/div/div/div/div[2]/div[2]/div/div[2]/div[1]/div/div/div/div/div[1]/a',
            'moreBtn'     => '//*[@id="kanbanList"]/div/div/div/div[2]/div[2]/div/div[2]/div[1]/div/div/div[1]/div/nav/button',
            'progressNum' => '//*[@id="kanbanList"]/div/div/div/div[2]/div[2]/div/div[2]/div[1]/div/div/div/div/div[3]/div/div/div[2]'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
