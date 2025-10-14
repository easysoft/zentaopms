<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class viewTodoTester extends tester
{
    /**
     * View a todo.
     * 查看待办。
     *
     * @access public
     * @return object
     */
    public function viewTodo()
    {
        $form   = $this->initForm('my', 'todo', array('type' => 'all', 'userID' => '', 'status' => 'all'), 'appIframe-my');
        $title  = $form->dom->title->getText();
        $date   = $form->dom->date->getText();
        $status = $form->dom->status->getText();
        $type   = $form->dom->type->getText();
        $form->dom->title->click();
        $form->wait(1);

        /* 详情页检查待办信息。*/
        if($form->dom->name->getText() != $title)     return $this->failed('待办名称不正确');
        if($form->dom->dateA->getText() != $date)     return $this->failed('日期不正确');
        if($form->dom->statusA->getText() != $status) return $this->failed('状态不正确');
        if($form->dom->typeA->getText() != $type)     return $this->failed('类型不正确');
        return $this->success('待办详情页信息正确');
    }
}
