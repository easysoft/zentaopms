#!/usr/bin/env php
<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';
class createBugTester extends tester
{
    public function __construct()
    {
        parent::__construct();
        $this->login();
    }

    public function createDefaultBug($project = array(), $bug = array())
    {
        $form = $this->initForm('bug', 'create',$project, 'appIframe-qa');
        if(isset($bug['title']))       $form->dom->title->setValue($bug['title']);
        if(isset($bug['openedBuild'])) $form->dom->{'openedBuild[]'}->multipicker($bug['openedBuild']);
        if(isset($bug['assignedTo']))  $form->dom->assignedTo->picker($bug['assignedTo']);
        if(isset($bug['deadline']))    $form->dom->deadline->datePicker($bug['deadline']['datePicker']);
        if(isset($bug['type']))        $form->dom->type->picker($bug['type']);
        if(isset($bug['severity']))    $form->dom->severity->picker($bug['severity']);
        if(isset($bug['pri']))         $form->dom->pri->picker($bug['pri']);
        if(isset($bug['steps']))       $form->dom->steps->setValueInZenEditor($bug['steps']);
        $form->dom->save->click();
        $form->wait(2);
        if($this->response('method') == 'browse')
        {
            if(isset($bug['title'], $bug['openedBuild'])) return $this->success('创建bug成功');
            return $this->failed('创建bug失败');
        }
        else
        {
            if(!isset($bug['title'], $bug['openedBuild'])) return $this->success('bug表单必填项校验成功');
            return $this->failed('bug表单必填项校验失败');
        }
    }

    /**
     * 批量创建bug。
     * batch create bugs.
     *
     * @param  array  $product
     * @param  array  $bugs
     * @access public
     * @return object
     */
    public function batchCreate($product = array(), $bugs = array())
    {
        $form = $this->initForm('bug', 'batchCreate', $product, 'appIframe-qa');
        if(isset($bugs))
        {
            for($i = 0; $i < count($bugs); $i++)
            {
                if(isset($bugs[$i]['title']))    $form->dom->{"title[" . ($i + 1) . "]"}->setValue($bugs[$i]['title']);
                if(isset($bugs[$i]['deadline'])) $form->dom->{"deadline[" . ($i + 1) . "]"}->datePicker($bugs[$i]['deadline']);
                if(isset($bugs[$i]['steps']))    $form->dom->{"steps[" . ($i + 1) . "]"}->setValue($bugs[$i]['steps']);
                if(isset($bugs[$i]['type']))     $form->dom->{"type[" . ($i + 1) . "]"}->picker($bugs[$i]['type']);
                if(isset($bugs[$i]['pri']))      $form->dom->{"pri[" . ($i + 1) . "]"}->picker($bugs[$i]['pri']);
                if(isset($bugs[$i]['severity'])) $form->dom->{"severity[" . ($i + 1) . "]"}->picker($bugs[$i]['severity']);
                if(isset($bugs[$i]['os']))       $form->dom->{"os[" . ($i + 1) . "]"}->multiPicker($bugs[$i]['os']);
                if(isset($bugs[$i]['browser']))  $form->dom->{"browser[" . ($i + 1) . "]"}->multiPicker($bugs[$i]['browser']);
            }
        }
        $form->dom->save->click();
        $form->wait(3);
        if($this->response('method') == 'browse') return $this->success('批量创建bug成功');
        return $this->failed('批量创建bug失败');
    }

    /**
     * 批量编辑bug。
     * batch edit bugs.
     *
     * @param  array  $product
     * @param  array  $bugs
     * @access public
     * @return object
     */
    public function batchEdit($product = array(), $bugs = array())
    {
        $form = $this->initForm('bug', 'browse', $product, 'appIframe-qa');
        $form->wait(3);
        $form->dom->bugLabel->click();
        $form->wait(1);
        $form->dom->batchEdit->click();
        $form->wait(3);
        $bugList = $form->dom->getElementList($form->dom->xpath['bugCount']);
        if(count($bugList->element) != count($bugs)) return $this->failed('zenData测试数据准备有误');
        for($i = count($bugList->element); $i > 0; $i--)
        {
            if(isset($bugs[$i-1]['type']))        $form->dom->{"type[" . $i . "]"}->picker($bugs[$i-1]['type']);
            if(isset($bugs[$i-1]['pri']))         $form->dom->{"pri[" . $i . "]"}->picker($bugs[$i-1]['pri']);
            if(isset($bugs[$i-1]['severity']))    $form->dom->{"severity[" . $i . "]"}->picker($bugs[$i-1]['severity']);
            if(isset($bugs[$i-1]['title']))       $form->dom->{"title[" . $i . "]"}->setValue($bugs[$i-1]['title']);
            if(isset($bugs[$i-1]['openedBuild'])) $form->dom->{"openedBuild[" . $i . "][]"}->multipicker($bugs[$i-1]['openedBuild']);
            if(isset($bugs[$i-1]['deadline']))    $form->dom->{"deadline[" . $i . "]"}->datePicker($bugs[$i-1]['deadline']);
        }
        $form->dom->save->click();
        $form->wait(3);
        if($this->response('method') == 'browse') return $this->success('批量编辑bug成功');
        return $this->failed('批量编辑bug失败');
    }

    /**
     * bug列表页。
     * bug browse.
     *
     * @param  array  $product
     * @param  array  $bugs
     * @access public
     * @return object
     */
    public function browse($product = array(), $bugs = array())
    {
        $form = $this->initForm('bug', 'browse', $product, 'appIframe-qa');
        $bugIDList    = array_map(function($element) {return $element->getText();}, $form->dom->getELementList($form->dom->xpath['bugID'])->element);
        $bugTitleList = array_map(function($element) {return $element->getText();}, $form->dom->getELementList($form->dom->xpath['bugTitle'])->element);
        foreach($bugs as $bug)
        {
            if(in_array($bug->id, $bugIDList) && in_array($bug->title, $bugTitleList)) return $this->success('bug列表页检查成功');
            return $this->failed('bug列表页检查失败');
        }
    }

    /**
     * 查找bug在bug列表中的索引。
     * Find the index of given bug title in the bug table
     *
     * @param  object $form
     * @param  string $bugTitle
     * @access public
     * @return int    $index
     */
    private function findIndex($form = null, $bugTitle = '')
    {
        if(!$form) return $this->failed('form is null!');
        $bugTitles = $form->dom->getElementList($form->dom->xpath['bugTitle'])->element;
        $form->wait(1);
        $index = -1;  // -1 not found
        $found = false;
        foreach($bugTitles as $item)
        {
            $index++;
            if($item->getText() == $bugTitle)
            {
                $found = true;
                break;
            }
        }
        if(!$found) return -1;
        return $index;
    }
}
