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
        if(isset($bug['steps']))       $form->dom->steps->setValue($bug['steps']);
        if(isset($bug['deadline']))    $form->dom->deadline->datePicker($bug['deadline']['datePicker']);
        if(isset($bug['type']))        $form->dom->type->picker($bug['type']);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        if($this->response('method') == 'browse')
        {
            if(isset($bug['title'], $bug['openedBuild'])) return $this->success('bug表单项提交成功');
            else return $this->failed('bug必填项校验失败');
        }
        else
        {
            if($this->checkFormTips('bug'))
            {
                if(!isset($bug['title'], $bug['openedBuild'])) return $this->success('bug表单必填项校验成功');
                else return $this->failed('bug表单项校验失败');
            }
            else return $this->failed('bug表单项校验失败asd ');
        }
    }
}
