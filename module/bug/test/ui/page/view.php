<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'bugTitle'       => "//*[contains(@class, 'entity-title')]/span[contains(@class, 'entity-title-text')]",
            'bugBasicInfo'   => "//*[contains(@class, 'bug-basic-info')]",
            'bugLifeInfo'    => "//*[contains(@class, 'bug-life-info')]",
            'bugRelatedInfo' => "//*[contains(@class, 'bug-related-info')]",
            'bugMiscInfo'    => "//*[contains(@class, 'story-related-list')]",
            'detailTabs'     => "//*[contains(@class, 'tabs-header')]//a",
            'bugSteps'       => "//div[@class='detail-section']",
            'sideTabs'       => "//*[@id='mainContent']//ul/li/a/span",
            'bugHistory'     => "//ul[contains(@class, 'history-panel-body')]/li",
            'actionButtons'  => "//*[contains(@class,'detail-body')]//*[contains(@class, 'toolbar-item')]",
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
