#!/usr/bin/env php
<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

class linkBugsTester extends tester
{

    public function __construct()
    {
        parent::__construct();
        $this->login();
    }

    /**
     * 关联bug(s)
     * Link bug(s) to a bug.
     *
     * @param  array  $product
     * @param  object $bug
     * @param  array  $linkBugs
     * @access public
     * @return object
     */
    public function linkbugs(array $product, object $bug, array $linkBugs)
    {
        $form = $this->initForm('bug', 'browse', $product, 'appIframe-qa');
        $form->wait(1);
        $index = array_search($bug->id, $form->dom->getElementListByXpathKey('bugID', true));
        if($index === false) return $this->failed('bug未找到' . $bug->title);
        $form->dom->getElementListByXpathKey('bugTitle')[$index]->click();
        $form->wait(1);
        $form->dom->editButton->click();
        $form->wait(1);
        $form->dom->linkBugsBox->scrollToElement();
        $form->wait(1);
        $form->dom->linkBug->click();
        $form->wait(1);
        $bugIDTexts = $form->dom->getElementListByXpathKey('bugID', true);
        $bugIDs     = $form->dom->getElementListByXpathKey('bugID');
        foreach($linkBugs as $bug)
        {
            if(!in_array($bug->id, $bugIDTexts)) return $this->failed('关联bug失败，bug ' . $bug->title . ' 未找到');
            $bugIDs[array_search($bug->id, $bugIDTexts)]->click();
        }
        $form->dom->linkBugsSave->click();
        $form->wait(2);
        $form->dom->saveButton->click();
        $form->wait(2);
        $form->dom->misc->click();
        $form->wait(1);
        $form->dom->relatedBugs->click();
        $form->wait(1);
        $linkedBugs = $form->dom->getElementListByXpathKey('linkedBugs', true);
        foreach($linkBugs as $bug)
        {
            if(!in_array($bug->title, $linkedBugs)) return $this->failed('关联bug失败，bug ' . $bug->title . ' 未找到');
        }
        return $this->success('关联bug成功');
    }
}