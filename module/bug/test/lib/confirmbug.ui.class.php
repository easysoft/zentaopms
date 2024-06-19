#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class confirmBugTester extends tester
{
    public function confirmBug($project = array(), $bug = array())
    {
        $this->login();
        $list = $this->initForm('bug', 'browse',$project, 'appIframe-qa');
        $list->dom->btn($this->lang->bug->search)->click();
        $list->dom->field1->picker($bug['search']);
        $list->dom->value1->picker($bug['isConfirm']);
        $list->dom->searchButton->click();
        $bugTitle = $list->dom->bugTitle->getText();
        $list->dom->confirmButton->click();
    }
}
