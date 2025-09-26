#!/usr/bin/env php
<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

class activateBugTester extends tester
{

    public function __construct()
    {
        parent::__construct();
        $this->login();
    }

    /**
     * 激活bug。
     * Activate a bug.
     *
     * @param  array  $product
     * @param  object $bug
     * @param  string $user
     * @access public
     * @return object
     */
    public function activateBug(array $product, object $bug, string $user)
    {
        $form = $this->initForm('bug', 'browse', $product, 'appIframe-qa');
        $form->wait(1);
        $form->dom->solvedByMe->click();
        $form->wait(1);
        $index = array_search($bug->id, $form->dom->getElementListByXpathKey('bugID', true));
        if($index === false) return $this->failed('bug未找到' . $bug->title);
        $form->dom->getElementListByXpathKey('bugID')[$index]->click();
        $form->wait(1);
        $form->dom->selectActBtn->click();
        $form->wait(1);
        $form->dom->assignedTo->picker($user);
        $form->wait(1);
        $form->dom->activate->click();
        $form->wait(1);
        $form->dom->all->click();
        $form->wait(1);
        $newState = $form->dom->getElementListByXpathKey('bugStatus', true)[$index];
        if($newState == $this->lang->bug->activate) return $this->success('激活bug成功');
        return $this->failed('激活bug失败');
    }
}
