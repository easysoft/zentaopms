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
    public function confirmBug(array $project, array $bug)
    {
        $this->login();
        $list = $this->searchBug($bug, $project);
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

    public function searchBug(array $bug, array $project = array())
    {
        if(empty($project)) $project['project'] = 1;
        $list = $this->initForm('bug', 'browse',$project, 'appIframe-qa');
        $list->dom->btn($this->lang->bug->search)->click();
        if(count($bug['search']) > 2) $list->dom->more->click();
        foreach($bug['search'] as $searchList)
        {
            foreach($searchList as $key=>$value)
            {
                $list->dom->{$key}->picker($value);
                $this->webdriver->wait(1);
            }
        }
        $list->dom->searchButton->click();
        $this->webdriver->wait(1);
        return $list;
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
    public function resolveBug(array $project, array $bug)
    {
        $this->login();
        $list = $this->searchBug($bug, $project);
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

    /**
     * 关闭bug。
     * Close a bug.
     *
     * @param  array  $product
     * @param  array  $bug
     * @access public
     * @return object
     */
    public function closeBug(array $product, array $bug)
    {
        $this->login();
        $list = $this->initForm('bug', 'browse', $product, 'appIframe-qa');
        $id = $list->dom->bugID->getText();
        $list->dom->closeButton->click();
        $this->webdriver->wait(1);

        if(isset($bug['comment'])) $list->dom->closeComment->setValueInZenEditor($bug['comment']);
        $list->dom->btn($this->lang->bug->close)->click();
        $this->webdriver->wait(1);

        $list->dom->search($searchList = array("bug编号,=,$id"));
        $this->webdriver->wait(1);
        if($list->dom->bugStatus->getText() == '已关闭') return $this->success('关闭bug成功');
        return $this->failed('bug关闭失败');
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
        if(empty($bugTitle) || !is_object($list)) return $this->failed('获取bug标题失败');

        $bugTitleLists = $list->dom->getElementList($list->dom->xpath['bugTitleList']);
        $bugList = array_map(function($element){return $element->getText();}, $bugTitleLists->element);
        if(!in_array($bugTitle, $bugList)) return $this->success('操作bug成功');
        return $this->failed('操作bug失败');
    }

    /**
     * 编辑一个bug。
     * Edit a bug.
     *
     * @param  array  $project
     * @param  array  $bug
     * @access public
     * @return object
     */
    public function editBug(array $project, array $bug)
    {
        $this->login();
        $list = $this->searchBug($bug, $project);
        $bugTitle = $list->dom->bugTitle->getText();
        $list->dom->editButton->click();
        $this->webdriver->wait(1);
        if(isset($bug['bugName'])) $list->dom->title->setValue($bug['bugName']);
        $list->dom->btn($this->lang->save)->click();
        $this->webdriver->wait(1);

        $alertTitle   = $list->dom->successTag->getText();
        $bugEditTitle = $list->dom->bugName->getText();
        if(empty($alertTitle)) return $this->failed('编辑bug失败');
        if($bugEditTitle == $bugTitle) return $this->success('编辑bug成功');
        return $this->success('编辑bug名称成功');
    }
}
