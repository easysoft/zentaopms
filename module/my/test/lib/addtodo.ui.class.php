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
