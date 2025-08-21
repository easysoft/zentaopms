<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class assignToTester extends tester
{
    /**
     * Assign a todo.
     * 指派待办。
     *
     * @param  array $todo
     * @access public
     * @return object
     */
    public function assignTo(array $todo)
    {
        $form = $this->initForm('my', 'todo', array('type' => 'all', 'userID' => '', 'status' => 'all'), 'appIframe-my');
        $form->wait(2);
        $form->dom->assignBtn->click();
        $form = $this->loadPage('todo', 'assignTo');
        if(isset($todo['assignTo']))   $form->dom->assignedTo->picker($todo['assignTo']);
        if(isset($todo['assignDate'])) $form->dom->date->datePicker($todo['assignDate']);

        $form->wait(2);
        $form->dom->submitBtn->click();
        $form->wait(1);

        /* 跳转到指派他人列表，检查指派给字段信息。*/
        $todoPage = $this->loadPage('my', 'todo', array('type' => 'all', 'userID' => '', 'status' => 'all'));
        $todoPage->dom->more->click();
        $todoPage->dom->assignedToOther->click();
        $todoPage->wait(2);
        if($todoPage->dom->assignedTo->getText() != $todo['assignTo']) return $this->failed('指派人不正确');
        if($todoPage->dom->date->getText()       != $todo['assignDate']) return $this->failed('日期不正确');
        return $this->success('指派他人待办成功');
    }
}
