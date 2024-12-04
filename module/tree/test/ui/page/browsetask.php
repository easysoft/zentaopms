<?php
class browsetaskPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 右侧区块中元素 */
            'firsrNullModule' => "//*[@name='modules[1]']",
            'submitBtn'       => "//*[@id='zin_tree_browsetask_form']//button[@type='submit']",
            /* 左侧区块中元素 */
            'firstModule'      => "//*[@id='treeEditor-tree-browsetask']/li[1]/div/div/a",
            'lastModule'       => "(//*[@id='treeEditor-tree-browsetask']/li)[last()]/div/div/a",
            'firstChildModule' => "//*[@id='treeEditor-tree-browsetask']/li/menu/li[1]/div/div/a",
            'lastChildModule'  => "(//*[@id='treeEditor-tree-browsetask']/li/menu/li)[last()]/div/div/a",
            'firstEditBtn'     => "//*[@id='treeEditor-tree-browsetask']/li[1]/div/nav/a[1]",
            'firstChildDelBtn' => "//*[@id='treeEditor-tree-browsetask']/li[1]/menu/li[1]/div/nav/a[2]",
            'firstViewBtn'     => "//*[@id='treeEditor-tree-browsetask']/li[1]/div/nav/a[3]",
            'firstCaret'       => "//*[@id='treeEditor-tree-browsetask']/li/div/span/span",
            /* 删除确认弹窗中元素 */
            'modalText'    => "//div[contains(@class,'modal-body')]//div[text()!='']",
            'modalConfirm' => "//button[@z-key='confirm']",
            /* 编辑弹窗中元素 */
            'editSubmitBtn' => "//*[@id='zin_tree_edit_form']//button[@type='submit']"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
