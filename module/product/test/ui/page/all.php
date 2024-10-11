<?php
class allPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'selectAllBtn' => "//*[@id='products']/div[1]/div[1]/div/div/div[1]/div/label",
            'batchEditBtn' => "//*[@id='products']/div[3]/nav[1]/a",
            'saveBtn'      => "//*[@id='zin_product_batchedit_formBatch']/div[2]/button[1]",
            /*tab标签*/
            'allTab'    => "//*[@id='featureBar']/menu/li[1]/a/span[1]",
            'openTab'   => "//*[@id='featureBar']/menu/li[2]/a/span[1]",
            'closedTab' => "//*[@id='featureBar']/menu/li[3]/a/span[1]",
            'allNum'    => "//*[@id='featureBar']/menu/li[1]/a/span[2]",
            'openNum'   => "//*[@id='featureBar']/menu/li[2]/a/span[2]",
            'closedNum' => "//*[@id='featureBar']/menu/li[3]/a/span[2]",
            /*维护产线*/
            'lineDialog'    => "//*[@id='manageLineModal']/div",
            'manageLineBtn' => "//*[@id='actionBar']/button[2]",
            'newLineName'   => "//*[@id='treeEditor-product-manageline']/li[last()]/div/div/div",
            'delNewLineBtn' => "//*[@id='treeEditor-product-manageline']/li[last()]/div/nav/a",
            'confirm'       => "//button[@z-key='confirm']",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
