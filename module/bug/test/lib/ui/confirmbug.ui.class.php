#!/usr/bin/env php
<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class confirmBugTester extends tester
{
    /**
     * 确认bug。
     * Confirm a bug.
     *
     * @param  array  $product
     * @param  array  $bug
     * @access public
     * @return object
     */
    public function confirmBug(array $product, array $bug)
    {
        $this->login();
        $list = $this->searchBug($bug, $product);
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
     * @param  array  $product
     * @param  array  $bug
     * @access public
     * @return object
     */
    public function resolveBug(array $product, array $bug)
    {
        $this->login();
        $list = $this->searchBug($bug, $product);
        $bugTitle = $list->dom->bugTitle->getText();
        $list->dom->resolveButton->click();
        $this->webdriver->wait(1);

        if(isset($bug['resolution']))    $list->dom->resolution->picker($bug['resolution']);
        if(isset($bug['resolvedBuild'])) $list->dom->resolvedBuild->picker($bug['resolvedBuild']);
        if(isset($bug['resolvedDate']))  $list->dom->resolvedDate->setValue($bug['resolvedDate']);
        if(isset($bug['assignedTo']))    $list->dom->assignedTo->picker($bug['assignedTo']);
        $list->dom->resolve->click();
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
    public function bugAssert(string $bugTitle = '', ?object $list = null)
    {
        if(empty($bugTitle) || !is_object($list)) return $this->failed('获取bug标题失败');

        $backtrace = debug_backtrace();
        if(!$backtrace[1]['function']) return $this->failed("代码有误");
        switch($backtrace[1]['function']){
            case 'confirmBug':
                $action = '确认';
                break;
            case 'resolveBug':
                $action = '解决';
                break;
            case 'closeBug';
                $action = '关闭';
                break;
            case 'editBug';
                $action = '编辑';
                break;
            default:
                $action = '操作';
        }

        $bugTitleLists = $list->dom->getElementList($list->dom->xpath['bugTitleList']);
        $bugList = array_map(function($element){return $element->getText();}, $bugTitleLists->element);
        if(!in_array($bugTitle, $bugList)) return $this->success($action . "bug成功");
        return $this->failed($action . "bug失败");
    }

    /**
     * 编辑一个bug。
     * Edit a bug.
     *
     * @param  array  $product
     * @param  array  $bug
     * @access public
     * @return object
     */
    public function editBug(array $product, array $bug)
    {
        $this->login();
        $list = $this->initForm('bug', 'browse', $product, 'appIframe-qa');
        $this->webdriver->wait(3);
        $bugTitle = $list->dom->bugTitle->getText();
        $list->dom->editButton->click();
        $this->webdriver->wait(3);
        if(isset($bug['bugName'])) $list->dom->title->setValue($bug['bugName']);
        $list->dom->saveButton->click();
        $this->webdriver->wait(3);

        if($this->response('method') == 'view') return $this->success('编辑bug成功');
        return $this->failed('编辑bug失败');
    }

    /**
     * 验证bug表单
     * test bug report.
     *
     * @param  array  $product
     * @param  array  $bug
     * @access public
     * @return object
     */
    public function report(array $product, array $bug)
    {
        $this->login();
        $form = $this->initForm('bug', 'report', $product, 'appIframe-qa');
        $form->dom->selectAll->click();
        $form->dom->clickInit->click();
        if($this->response('method') == 'report') return $this->success('bug表单验证成功');
        return $this->failed('bug表单验证失败');
    }
}
