<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'settings'         => "//*[@id='navbar']//a[@data-id='settings']/span",
            'editBtn'          => "//*[@id='table-project-browse']/div[2]/div[3]/div/div[1]/div/nav/a[2]/i",
            'projectName'      => "//*[@id='table-project-browse']/div[2]/div[1]/div/div[2]/div/a",
            'scrumName'        => "//*[@id='mainContent']/div[2]/div/div/div[2]/div/div/div[2]/div/a",
            'moreBtn'          => "//*[@id='table-project-browse']/div[2]/div[3]/div/div[1]/div/nav/button[1]/span",
            'closeBtn'         => "//*[@class='icon item-icon icon-off']",
            'closeProject'     => "//*[@class='modal modal-async load-indicator modal-trans show in']/div/div/div[3]/div/div/form/div[3]/div/button",
            'title'            => "//*[@class='modal modal-async load-indicator modal-trans show in']/div/div/div/div/div/span[1]",
            'closed'           => "//*[@id='more']/menu/menu/li[2]/a/div",
            'activeBtn'        => "//*[@id='table-project-browse']/div[2]/div[3]/div/div/div/nav/a[1]/i",
            'activeProject'    => "//*[@class='modal modal-async load-indicator modal-trans show in']/div/div/div[3]/div/div/form/div[3]/div/button",
            'startBtn'         => "//*[@id='table-project-browse']/div[2]/div[3]/div/div/div/nav/a",
            'startProject'     => "//*[@class='modal-content']/div[3]/div/div/form/div[3]/div/button/span",
            'suspendBtn'       => "//*[@class='popover show fade dropdown in']/menu/menu/li/a",
            'suspendProject'   => "//*[@class='modal-content']/div[3]/div/div/form/div[2]/div/button",
            'browseStatus'     => "//*[@id='table-project-browse']/div[2]/div[2]/div/div/div/span",
            'selectBtn'        => "//*[@id='table-project-browse']/div[2]/div[1]/div/div[1]",
            'batchEditBtn'     => "//*[@id='table-project-browse']/div[3]/nav[1]/nav/button/span",
            'begin'            => "//*[@id='table-project-browse']/div[2]/div[2]/div/div[7]/div",
            'end'              => "//*[@id='table-project-browse']/div[2]/div[2]/div/div[8]/div",
            'createProjectBtn' => "//*[@id='mainMenu']/div[2]/a[2]/span",
            /*向导中项目管理方式类型*/
            'scrum'         => "//*[@class='modal-content']/div[2]/div[2]/div[1]/div",
            'waterfall'     => "//*[@class='modal-content']/div[2]/div[2]/div[2]/div",
            'kanban'        => "//*[@class='modal-content']/div[2]/div[2]/div[3]/div",
            'agileplus'     => "//*[@class='modal-content']/div[2]/div[2]/div[4]/div",
            'waterfallplus' => "//*[@class='modal-content']/div[2]/div[2]/div[5]/div",
            /*导出项目*/
            'exportBtn'      => "//*[@id='mainMenu']/div[2]/a/span",
            'fileName'       => "//*[@class='modal-content']/div[3]/div/div/form/div[1]/input",
            'format'         => "//*[@class='modal-content']/div[3]/div/div/form/div[2]/div",
            'encoding'       => "//*[@class='modal-content']/div[3]/div/div/form/div[3]/div/div",
            'data'           => "//*[@class='modal-content']/div[3]/div/div/form/div[4]/div/div/div/span",
            'exportBtnAlert' => "//*[@class='modal-content']/div[3]/div/div/form/div[6]/div/div/button",
            /*项目列表页标签*/
            'all'       => "//*[@id='featureBar']/menu/li[1]/a/span[1]",
            'undone'    => "//*[@id='featureBar']/menu/li[2]/a/span[1]",
            'wait'      => "//*[@id='featureBar']/menu/li[3]/a/span[1]",
            'doing'     => "//*[@id='featureBar']/menu/li[4]/a/span[1]",
            'moreTab'   => "//*[@id='featureBar']/menu/li[5]/a/span[1]",
            'suspended' => "//*[@id='more']/menu/menu/li[1]/a/div/div",
            'closed'    => "//*[@id='more']/menu/menu/li[2]/a/div/div",
            'num'       => "//*[@id='table-project-browse']/div[3]/div[2]/strong",
            /*创建看板项目*/
            'kanbanName' => "//*[@id='mainContent']/div[2]/div/div/div[2]/div[1]/div/div[2]/div/a"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
