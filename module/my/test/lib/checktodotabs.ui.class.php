<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class addTodoTester extends tester
{
    /**
     * 检查待办标签。
     * check todo tabs.
     *
     * @param  string $todoTitle
     * @param  string $todoStatus
     * @access public
     * @return void
     */
    public function checkTodoTabs($todoTitle)
    {
        $this->openUrl('my', 'todo', array('type' => 'before'));
        $todoList = $this->loadPage('my', 'todo', array('type' => 'before'));
        $todoList->wait(1);

        $fstTodo = $todoList->dom->fstTodoTitle->getText();
