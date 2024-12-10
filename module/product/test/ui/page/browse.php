<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'settings'        => "//*[@id='navbar']//a[@data-id='settings']/span",
            'browseStoryName' => "//*[@id='stories']/div[2]/div[1]/div/div[2]/div/a",
            /*批量操作*/
            'firstSelect'     => "//*[@id='stories']/div[2]/div[1]/div/div[1]/div/div",
            'batchEdit'       => "//*[@id='stories']/div[3]/nav[1]/nav/button[1]",
            'batchMore'       => "//*[@id='stories']/div[3]/nav[1]/nav/button[2]",
            'batchClose'      => "//*[@data-page='product-browse']/div[3]/menu/menu//div/div[@class='item-title' and text()='关闭']",
            /*需求列表tab及数量*/
            'all'             => '//*[@id="featureBar"]/menu/li[1]/a',
            'open'            => '//*[@id="featureBar"]/menu/li[2]/a',
            'assignedToMe'    => '//*[@id="featureBar"]/menu/li[3]/a',
            'createdByMe'     => '//*[@id="featureBar"]/menu/li[4]/a',
            'reviewByMe'      => '//*[@id="featureBar"]/menu/li[5]/a',
            'draft'           => '//*[@id="featureBar"]/menu/li[6]/a',
            'more'            => '//*[@id="featureBar"]/menu/li[7]/a',
            'reviewedByMe'    => '//*[@id="more"]/menu/menu/li[1]',
            'assignedByMe'    => '//*[@id="more"]/menu/menu/li[2]',
            'closedByMe'      => '//*[@id="more"]/menu/menu/li[3]',
            'activated'       => '//*[@id="more"]/menu/menu/li[4]',
            'changing'        => '//*[@id="more"]/menu/menu/li[5]',
            'reviewing'       => '//*[@id="more"]/menu/menu/li[6]',
            'toBeClosed'      => '//*[@id="more"]/menu/menu/li[7]',
            'closed'          => '//*[@id="more"]/menu/menu/li[8]',
            'allNum'          => '//*[@id="featureBar"]/menu/li[1]/a/span[2]',
            'openNum'         => '//*[@id="featureBar"]/menu/li[2]/a/span[2]',
            'assignedToMeNum' => '//*[@id="featureBar"]/menu/li[3]/a/span[2]',
            'createdByMeNum'  => '//*[@id="featureBar"]/menu/li[4]/a/span[2]',
            'reviewByMeNum'   => '//*[@id="featureBar"]/menu/li[5]/a/span[2]',
            'draftNum'        => '//*[@id="featureBar"]/menu/li[6]/a/span[2]',
            'moreNum'         => '//*[@id="featureBar"]/menu/li[7]/a/span[2]'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
