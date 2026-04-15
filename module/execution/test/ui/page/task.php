<?php
class taskPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 标签 */
            'all'          => "//*[@id='featureBar']/menu/li[1]/a",
            'unclosed'     => "//*[@id='featureBar']/menu/li[2]/a",
            'assignedtome' => "//*[@id='featureBar']/menu/li[3]/a",
            'myinvolved'   => "//*[@id='featureBar']/menu/li[4]/a",
            'assignedbyme' => "//*[@id='featureBar']/menu/li[5]/a",
            'needconfirm'  => "//*[@id='featureBar']/menu/li[6]/a",
            'more'         => "//*[@id='featureBar']/menu/li[7]/a",
            'wait'         => "//*[@id='status']/menu/menu/li[1]/a",
            'doing'        => "//*[@id='status']/menu/menu/li[2]/a",
            'undone'       => "//*[@id='status']/menu/menu/li[3]/a",
            'finishedbyme' => "//*[@id='status']/menu/menu/li[4]/a",
            'done'         => "//*[@id='status']/menu/menu/li[5]/a",
            'closed'       => "//*[@id='status']/menu/menu/li[6]/a",
            'cancel'       => "//*[@id='status']/menu/menu/li[7]/a",
            'delayed'      => "//*[@id='status']/menu/menu/li[8]/a",
            'num'          => "//*[@id='tasks']/div[3]/div[2]/strong[1]",
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
