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

    /**
     * 批量激活bug。
     * batch activate bugs.
     *
     * @param  array  $product
     * @param  array  $bugs
     * @access public
     * @return object
     */
    public function batchActivate($product = array(), $bugs = array())
    {
        $form = $this->initForm('bug', 'browse', $product, 'appIframe-qa');
        $form->wait(3);
        $form->dom->all->click();
        $form->wait(1);
        $form->dom->bugLabel->click();
        $form->wait(1);
        $form->dom->editDropdown->click();
        $form->wait(1);
        $form->dom->dropdownPicker($this->lang->bug->activate);
        $form->wait(1);
        $bugList = $form->dom->getElementListByXpathKey('bugCount');
        if(count($bugList) != count($bugs)) return $this->failed('zenData测试数据准备有误');
        for($i = count($bugList); $i > 0; $i--)
        {
            if(isset($bugs[$i-1]['assignedTo']))  $form->dom->{"assignedTo[{$i}]"}->picker($bugs[$i-1]['assignedTo']);
            if(isset($bugs[$i-1]['openedBuild'])) $form->dom->{"openedBuild[{$i}][]"}->multiPicker($bugs[$i-1]['openedBuild']);
            if(isset($bugs[$i-1]['comment']))     $form->dom->{"comment[{$i}]"}->setValue($bugs[$i-1]['comment']);
        }
        $form->dom->save->click();
        $form->wait(3);
        $bugStatus = $form->dom->getElementListByXpathKey('bugStatus', true);
        foreach($bugStatus as $state)
        {
            if($state != $this->lang->bug->activate) return $this->failed('批量激活bug失败');
        }
        return $this->success('批量激活bug成功');
    }
}
