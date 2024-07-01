#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class testcase extends tester
{
    public function createTestCase($project = array(), $testcase = array())
    {
        $this->login();
        $form = $this->initForm('testcase', 'create',$project, 'appIframe-qa');
        if(isset($testcase['caseName'])) $form->dom->title->setValue($testcase['caseName']);
        if(isset($testcase['type']))     $form->dom->type->picker($testcase['type']);
        if(isset($testcase['stage']))    $form->dom->{'stage[]'}->multiPicker($testcase['stage']);
        if(isset($testcase['pri']))      $form->dom->pri->picker($testcase['pri']);
        $form->dom->btn($this->lang->save)->click();
        $this->webdriver->wait(1);

        $caseLists = $form->dom->caseName->getElementList($form->dom->page->xpath['caseNameList']);
        $caseList  = array_map(function($element){return $element->getText();}, $caseLists->element);
        var_dump($caseList);
        if(in_array($testcase['caseName'], $caseList)) return $this->success('创建测试用例成功');
        return $this->failed('创建测试用例失败');
    }
}
