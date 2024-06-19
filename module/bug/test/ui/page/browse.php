<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'searchButton' => "//*[@id='mainContentCell']//button[text()='搜索']",
            'bugTitle' => '(//div[@id="table-bug-browse"]/div[@class="dtable-block dtable-body"]/div[@class="dtable-cells dtable-fixed-left"]/div//a)[1]',
            'confirmButton' => '(//a[@title="确认"])[1]',
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
