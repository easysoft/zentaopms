#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class confirmBugTester extends tester
{
    /**
     * 确认bug。
     * Confirm a bug.
     *
     * @param  array  $project
     * @param  array  $bug
     * @access public
     * @return object
     */
    public function confirmBug($project = array(), $bug = array())
    {
        $this->login();
        $list = $this->initForm('bug', 'browse',$project, 'appIframe-qa');
        $list->dom->btn($this->lang->bug->search)->click();
        $list->dom->field1->picker($bug['search']);
        $list->dom->value1->picker($bug['isConfirm']);
        $list->dom->searchButton->click();
        $this->webdriver->wait(1);
        $bugTitle = $list->dom->bugTitle->getText();
        $list->dom->confirmButton->click();
        $this->webdriver->wait(1);

        if(isset($bug['assignedTo'])) $list->dom->assignedTo->picker($bug['assignedTo']);
        if(isset($bug['type']))       $list->dom->type->picker($bug['type']);
        if(isset($bug['pri']))        $list->dom->pri->picker($bug['pri']);
        if(isset($bug['deadline']))   $list->dom->deadline->datePicker($bug['deadline']);
        if(isset($bug['mailto']))     $list->dom->{'mailto[]'}->multiPicker($bug['mailto']);
        $list->dom->btn($this->lang->bug->confirm)->click();
        $this->webdriver->wait(1);

        return $this->bugAssert($bugTitle, $list);
    }

    /**
     * 解决bug。
     * Resolve a bug.
     *
     * @param  array  $project
     * @param  array  $bug
     * @access public
     * @return object
     */
    public function resolveBug($project = array(), $bug = array())
    {
        $this->login();
        $list = $this->initForm('bug', 'browse',$project, 'appIframe-qa');
        $list->dom->btn($this->lang->bug->search)->click();
        $list->dom->field1->picker($bug['search']);
        $list->dom->value1->picker($bug['isResolved']);
        $list->dom->searchButton->click();
        $this->webdriver->wait(1);
        $bugTitle = $list->dom->bugTitle->getText();
        $list->dom->resolveButton->click();
        $this->webdriver->wait(1);

        if(isset($bug['resolution']))    $list->dom->resolution->picker($bug['resolution']);
        if(isset($bug['resolvedBuild'])) $list->dom->resolvedBuild->picker($bug['resolvedBuild']);
        if(isset($bug['resolvedDate']))  $list->dom->resolvedDate->datePicker($bug['resolvedDate']);
        if(isset($bug['assignedTo']))    $list->dom->assignedTo->picker($bug['assignedTo']);
        $list->dom->btn($this->lang->bug->resolve)->click();
        $this->webdriver->wait(1);

        return $this->bugAssert($bugTitle, $list);
    }

    public function closeBug($project = array(), $bug = array())
    {
        $this->login();
        $list = $this->initForm('bug', 'browse',$project, 'appIframe-qa');
        $list->dom->btn($this->lang->bug->search)->click();
        $list->dom->field1->picker($bug['search']);
        $list->dom->value1->picker($bug['isResolved']);
        $list->dom->searchButton->click();
        $this->webdriver->wait(1);
        $bugTitle = $list->dom->bugTitle->getText();
        $list->dom->closeButton->click();
        $this->webdriver->wait(1);

        $list->dom->btn($this->lang->bug->close)->click();
        $this->webdriver->wait(1);

        return $this->bugAssert($bugTitle, $list);
    }

    /**
     * 给bug列表上的操作增加断言。
     * Add a assertion on bug list.
     *
     * @param  string  $bugTitle
     * @param  object  $list
     * @access public
     * @return object
     */
    public function bugAssert(string $bugTitle = '', object $list = null)
    {
        if(!isset($bugTitle) || !is_object($list)) return $this->failed('获取bug标题失败');

        $bugTitleLists = $list->dom->bugTitleList->getElementList($list->dom->page->xpath['bugTitleList']);
        $bugList = array_map(function($element){return $element->getText();}, $bugTitleLists->element);
        if(!in_array($bugTitle, $bugList)) return $this->success('操作bug成功');
        return $this->failed('操作bug失败');
    }
}
