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
            'batchClose'      => "//*[@data-page='product-browse']/div[3]/menu/menu//div/div[@class='item-title' and text()='关闭']"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
