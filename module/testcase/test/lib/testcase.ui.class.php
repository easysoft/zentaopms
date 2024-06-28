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
    }
}
