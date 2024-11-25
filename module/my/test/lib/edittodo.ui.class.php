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
