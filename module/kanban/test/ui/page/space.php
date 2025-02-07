<?php
class spacePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'spaceName' => '//*[@id="mainContent"]/div[1]/div[1]/div[1]',
            'closed'    => '//*[@id="mainContent"]/div[1]/div[1]/div[1]/span',
            'confirm'   => '//button[@z-key="confirm"]',
            /*空间tab*/
            'involvedTab'    => '//*[@id="featureBar"]/menu/li[1]/a',
            'involvedNum'    => '//*[@id="featureBar"]/menu/li[1]/a/span[2]',
            'cooperationTab' => '//*[@id="featureBar"]/menu/li[2]/a',
            'cooperationNum' => '//*[@id="featureBar"]/menu/li[2]/a/span[2]',
            'publicTab'      => '//*[@id="featureBar"]/menu/li[3]/a',
            'publicNum'      => '//*[@id="featureBar"]/menu/li[3]/a/span[2]',
            'privateTab'     => '//*[@id="featureBar"]/menu/li[4]/a',
            'privateNum'     => '//*[@id="featureBar"]/menu/li[4]/a/span[2]',
            /*看板*/
            'createKanbanBtn' => '//*[@id="mainContent"]/div[1]/div[1]/div[2]/div/a',
            'saveKanbanBtn'   => '//*[@id="zin_kanban_create_form"]/div[16]/div/button',
            'kanbanName'      => '//*[@id="kanban-1"]/div[1]/div[1]',
            'moreBtn'         => '//*[@id="kanban-1"]/div/div[last()]/button',
            'saveEditBtn'     => '//*[@id="zin_kanban_edit_1_form"]/div[7]/div/button/span',
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
