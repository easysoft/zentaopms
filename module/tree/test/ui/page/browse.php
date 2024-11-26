<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'firsrNullModule'  => "(//*[@name='modules[]'])[1]",
            'secondNullModule' => "(//*[@name='modules[]'])[2]",
            'firstAddBtn'      => "//*[@id='zin_tree_browse_form']/div[2]/div/div[2]/div/button[1]",
            'firstRemoveBtn'   => "//*[@id='zin_tree_browse_form']/div[2]/div/div[2]/div/button[2]",
            'submitBtn'        => "//*[@id='zin_tree_browse_form']//button[@type='submit']",

            'firstModule'  => "//*[@id='treeEditor-tree-browse']/li[1]/div/div/a",
            'firstEditBtn' => "//*[@id='treeEditor-tree-browse']/li[1]/div/nav/a[1]",
            'firstDelBtn'  => "//*[@id='treeEditor-tree-browse']/li[1]/div/nav/a[2]",
            'firstViewBtn' => "//*[@id='treeEditor-tree-browse']/li[1]/div/nav/a[3]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
