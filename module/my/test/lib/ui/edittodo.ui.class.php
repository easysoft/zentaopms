<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
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
        $todoList->dom->btn($this->lang->save)->click();

        $this->openUrl('my', 'todo', array('type' => 'all'));
        $todoList = $this->loadPage('my', 'todo', array('type' => 'all'));
        if($todoList->dom->fstTodoTitle->getText() != $todoTitle->name) return $this->failed('编辑待办失败');
        return $this->success('编辑待办成功');
    }

    /**
     * 开始待办。
     * Start a todo.
     *
     * @param  string $todoStatus
     * @access public
     * @return void
     */
    public function startTodo($todoStatus)
    {
        $this->openUrl('my', 'todo', array('type' => 'all'));
        $todoList = $this->loadPage('my', 'todo', array('type' => 'all'));
        $todoList->dom->fstTodoStart->click();
        $todoList->wait(1);
        if($todoList->dom->fstTodoStatus->getText() != $todoStatus->doing) return $this->failed('开始待办失败');
        return $this->success('开始待办成功');
    }

    /**
     * 完成待办。
     * Finish a todo.
     *
     * @param  string $todoStatus
     * @access public
     * @return void
     */
    public function finishTodo($todoStatus)
    {
        $this->openUrl('my', 'todo', array('type' => 'all'));
        $todoList = $this->loadPage('my', 'todo', array('type' => 'all'));
        $todoList->dom->fstTodoFinish->click();
        $todoList->wait(1);
        if($todoList->dom->fstTodoStatus->getText() != $todoStatus->done) return $this->failed('完成待办失败');
        return $this->success('完成待办成功');
    }

    /**
     * 激活待办。
     * Activate a todo.
     *
     * @param  string $todoStatus
     * @access public
     * @return void
     */
    public function activateTodo($todoStatus)
    {
        $this->openUrl('my', 'todo', array('type' => 'all'));
        $todoList = $this->loadPage('my', 'todo', array('type' => 'all'));
        $todoList->dom->fstTodoActivate->click();
        $todoList->wait(1);
        if($todoList->dom->fstTodoStatus->getText() != $todoStatus->waiting) return $this->failed('激活待办失败');
        return $this->success('激活待办成功');
    }

    /**
     * 关闭待办。
     * Close a todo.
     *
     * @param  string $todoStatus
     * @access public
     * @return void
     */
    public function closeTodo($todoStatus)
    {
        $this->openUrl('my', 'todo', array('type' => 'all'));
        $todoList = $this->loadPage('my', 'todo', array('type' => 'all'));
        $todoList->dom->fstTodoClose->click();
        $todoList->wait(1);
        if($todoList->dom->fstTodoStatus->getText() != $todoStatus->close) return $this->failed('关闭待办失败');
        return $this->success('关闭待办成功');
    }
}
