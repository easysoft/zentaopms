#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createBugTester extends tester
{
    public function createDefaultBug($project = array(), $bug = array())
    {
        $this->login();
        $form = $this->initForm('bug', 'create',$project, 'appIframe-qa');
        if(isset($bug['title']))       $form->dom->title->setValue($bug['title']);
        if(isset($bug['openedBuild'])) $form->dom->{'openedBuild[]'}->multipicker($bug['openedBuild']);
        if(isset($bug['assignedTo']))  $form->dom->assignedTo->picker($bug['assignedTo']);
        if(isset($bug['deadline']))    $form->dom->deadline->datePicker($bug['deadline']['datePicker']);
        if(isset($bug['type']))        $form->dom->type->picker($bug['type']);
        if(isset($bug['severity']))    $form->dom->severity->picker($bug['severity']);
        if(isset($bug['pri']))         $form->dom->pri->picker($bug['pri']);
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
}
