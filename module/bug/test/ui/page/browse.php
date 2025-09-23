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
            'save'          => "//button[@type='button']/span[text()]/../preceding-sibling::button[@type='submit']/span[text()]",
            'bugID'         => "//div[@data-col='id' and @data-type='checkID']/div[text()]",
            'bugTitle'      => "//div[@data-col='title' and @data-type='title']/div/a[text() and @data-app]",
            'bugStatus'     => "//div[@data-col='status' and @data-type='status']/div/span[text()]",
            'closeComment'  => "//zen-editor[@id='comment']",
            'resolve'       => "//span[text()='解决']",
            'batchEdit'     => "//span[text()='编辑']",
            'saveButton'    => "//span[text()='保存']",
            'assignTo'      => "//span[text()='指派给']",
            'assign'        => "//span[text()='指派']",
            'bugAssigned'   => "//div[@data-col='assignedTo' and @data-type='assign']/div/a/span[text()]",
            'popupMenu'     => '//div[contains(@class,"popover show fade dropdown in")]/menu/menu/li'
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
