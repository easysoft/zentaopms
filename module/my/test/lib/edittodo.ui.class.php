<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class addTodoTester extends tester
{
    /**
     * 编辑待办。
     * Edit a todo.
     *
     * @param  string $todoTitle
     * @param  string $todoStatus
     * @access public
     * @return void
     */
    public function editTodo($todoTitle, $todoStatus)
    {
        $this->openUrl('my', 'todo', array('type' => 'all'));
        $todoList = $this->loadPage('my', 'todo', array('type' => 'all'));
        $todoList->dom->fstTodoEdit->click();
        $todoList->wait(1);
        $todoList->dom->name->setValue($todoTitle->name);
        $todoList->wait(1);
        $todoList->dom->status->picker($todoStatus->done);
        $todoList->wait(1);
        $todoList->dom->switchTime->click();
        $todoList->dom->editTodoBtn->click();

        $this->openUrl('my', 'todo', array('type' => 'all'));
        $todoList = $this->loadPage('my', 'todo', array('type' => 'all'));
        if($todoList->dom->fstTodoTitle->getText() != $todoTitle->name)
        {
            if($todoList->dom->fstTodoStatus->getText() != $todoStatus->done) return $this->success('添加待办成功');
        }
        return $this->failed('添加待办失败');
    }
}
