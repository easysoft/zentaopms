#!/usr/bin/env php
<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

class viewBugTester extends tester
{

    public function __construct()
    {
        parent::__construct();
        $this->login();
    }

    /**
     * 查看bug标题
     * View bug title.
     *
     * @param  object $bug
     * @access public
     * @return object
     */
    public function viewBugTitle(object $bug)
    {
        $form = $this->initForm('bug', 'view', $bug, 'appIframe-qa');
        $form->wait(2);

        $title = $form->dom->bugTitle->element->getText();
        if($title != $bug->title) return $this->failed('Bug详情页面标题显示失败');

        return $this->success('Bug详情页面标题检查成功');
    }

    /**
     * 测试bug详情页面的各个信息区域
     * Test bug detail page information sections.
     *
     * @param  array  $product
     * @param  object $bug
     * @access public
     * @return object
     */
    public function checkBugDetailSections(object $bug)
    {
        $form = $this->initForm('bug', 'view', $bug, 'appIframe-qa');
        $form->wait(2);

        $validTabs = array(
            $this->lang->bug->legendBasicInfo,
            $this->lang->bug->legendLife,
            $this->lang->bug->legendExecStoryTask,
            $this->lang->bug->legendMisc,
        );

        foreach($form->dom->getElementListByXpathKey('sideTabs', true) as $pane)
        {
            if(!in_array($pane, $validTabs)) return $this->failed("Bug详情页面侧边栏标签页{$pane}未知");
        }

        if(!$form->dom->bugSteps->element->getText()) return $this->failed('Bug详情重现步骤区域标题显示失败');
        if(count($form->dom->getElementListByXPathKey('bugHistory')) < 1) return $this->failed('Bug历史记录检查失败');

        return $this->success('Bug详情页面信息区域检查成功');
    }

    /**
     * 检查bug操作按钮是否显示
     * Check if bug action buttons are displayed.
     *
     * @param  array  $product
     * @param  object $bug
     * @access public
     * @return object
     */
    public function checkActionButtons(object $bug)
    {
        $form = $this->initForm('bug', 'view', $bug, 'appIframe-qa');
        $form->wait(2);

        $buttons = $form->dom->getElementListByXpathKey('actionButtons', true);

        if(count($buttons) < 9) return $this->failed('Bug详情页面下部操作按钮检查失败');
        return $this->success('Bug详情页面下部操作按钮检查成功');
    }

    /**
     * 测试bug详情页面标签页切换
     * Test bug detail page tab switching.
     *
     * @param  array  $product
     * @param  object $bug
     * @access public
     * @return object
     */
    public function testDetailTabs(object $bug)
    {
        $form = $this->initForm('bug', 'view', $bug, 'appIframe-qa');
        $form->wait(2);

        $detailTabs = $form->dom->getElementListByXpathKey('detailTabs');
        foreach($detailTabs as $tab)
        {
            $tab->click();
            $form->wait(1);
            if(!$tab->getText()) $this->failed("{$tab}标签页无标题");

            // 对每一个Tab，抽查它的第一个元素即可
            switch($tab->getText())
            {
                case $this->lang->bug->legendBasicInfo:
                    if(!$form->dom->bugBasicInfo->element->getText()) $this->failed('Bug基本信息标签页无内容');
                    break;
                case $this->lang->bug->legendLife:
                    if(!$form->dom->bugLifeInfo->element->getText()) $this->failed('Bug生命周期信息标签页无内容');
                    break;
                case $this->lang->bug->legendExecStoryTask:
                    if(!$form->dom->bugRelatedInfo->element->getText()) $this->failed('Bug项目/研发需求/任务标签页无内容');
                    break;
                case $this->lang->bug->legendMisc:
                    if(!$form->dom->bugMiscInfo->element->getText()) $this->failed('Bug其他相关信息标签页无内容');
                    break;
            }
        }
        return $this->success('Bug详情页面右部标签切换测试成功');
    }
}
