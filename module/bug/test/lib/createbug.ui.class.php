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
        $this->login();
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
}