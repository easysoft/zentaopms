<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class addTodoTester extends tester
{
    /**
     * 添加待办。
     * Add a todo.
     *
     * @param  string $todoTitle
     * @access public
     * @return void
     */
    public function addTodo($todoTitle, $todoStatus)
    {
        $this->openUrl('my', 'todo', array('type' => 'all'));
        $todoList = $this->loadPage('my', 'todo', array('type' => 'all'));
        $todoList->dom->addTodo->click();
        $todoList->wait(1);
        $todoList->dom->name->setValue($todoTitle->name);
        $todoList->wait(1);
        $todoList->dom->status->picker($todoStatus->doing);
        $todoList->wait(1);
        $todoList->dom->switchTime->click();
        $todoList->dom->addTodoBtn->click();

        $this->openUrl('my', 'todo', array('type' => 'all'));
        $todoList = $this->loadPage('my', 'todo', array('type' => 'all'));
        if($todoList->dom->fstTodoTitle->getText() != $todoTitle->name) return $this->failed('添加待办失败');
        return $this->success('添加待办成功');
    }
}
