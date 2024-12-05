<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'searchButton'  => "//*[@id='mainContentCell']//button[text()='搜索']",
            'bugTitle'      => '(//div[@id="table-bug-browse"]/div[@class="dtable-block dtable-body"]/div[@class="dtable-cells dtable-fixed-left"]/div//a)[1]',
            'bugTitleList'  => '//div[@id="table-bug-browse"]/div[@class="dtable-block dtable-body"]/div[@class="dtable-cells dtable-fixed-left"]/div//a',
            'confirmButton' => '(//a[@title="确认"]/i)[1]',
            'resolveButton' => '(//a[@title="解决"]/i)[1]',
            'closeButton'   => '(//a[@title="关闭"]/i)[1]',
            'editButton'    => '(//a[@title="编辑Bug"]/i)[1]',
            'more'          => "//*[@id='mainContentCell']//button[text()='保存搜索条件']/following-sibling::button/i",
            'successTag'    => '//*[contains(@id,"messager")]//div[text() = "保存成功"]',
            'bugName'       => '//*[@id="mainContent"]//span[contains(text(), "bug")]',
            'bugLabel'      => '(//label[last()])',
            'bugCount'      => "//div[@id='mainContainer']//tbody/tr",
            'save'          => "//button[@type='button']/span[text()]/../preceding-sibling::button[@type='submit']/span[text()]"
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
