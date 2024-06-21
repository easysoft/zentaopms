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
        $this->webdriver->wait(1);
        $bugTitle = $list->dom->bugTitle->getText();
        $list->dom->confirmButton->click();
        $this->webdriver->wait(1);

        if(isset($bug['assignedTo'])) $list->dom->assignedTo->picker($bug['assignedTo']);
        if(isset($bug['type'])) $list->dom->type->picker($bug['type']);
        if(isset($bug['pri'])) $list->dom->pri->picker($bug['pri']);
        if(isset($bug['deadline'])) $list->dom->deadline->datePicker($bug['deadline']);
        if(isset($bug['mailto'])) $list->dom->{'mailto[]'}->multiPicker($bug['mailto']);
        $list->dom->btn($this->lang->bug->confirm)->click();
        $this->webdriver->wait(1);

        $bugTitleLists = $list->dom->bugTitleList->getElementList($list->dom->page->xpath['bugTitleList']);
        $bugList = [];
        foreach($bugTitleLists->element as $bugTitleList)
        {
           $bugList[] = $bugTitleList->getText();
        }
        if(!in_array($bugTitle, $bugList)) return $this->success('确认bug成功');
    }

}
