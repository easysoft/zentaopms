<?php
class allPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'selectAllBtn'  => "//*[@id='products']/div[1]/div[1]/div/div/div[1]/div/label",
            'batchEditBtn'  => "//*[@id='products']/div[3]/nav[1]/a",
            'allProductTab' => "//*[@id='featureBar']/menu/li[1]/a/span[1]",
            'saveBtn'       => "//*[@id='zin_product_batchedit_formBatch']/div[2]/button[1]",
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
