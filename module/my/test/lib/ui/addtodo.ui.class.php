<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class addTodoTester extends tester
{
    /**
     * 添加待办。
     * Add a todo.
     *
     * @param  string $todoTitle
     * @param  string $todoStatus
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

    /**
     * 批量添加待办。
     * Batch add todo
     *
     * @param  string $todoTitle
     * @access public
     * @return void
     */
    public function batchAddTodo($todoTitle)
    {
        $this->openUrl('my', 'todo', array('type' => 'all'));
        $todoList = $this->loadPage('my', 'todo', array('type' => 'all'));
        $todoList->dom->moreBtn->click();
        $todoList->dom->batchAddBtn->click();
        $todoList->wait(1);

        $todoList->dom->fstTitle->setValue($todoTitle->first);
        $todoList->dom->secTitle->setValue($todoTitle->second);
        $todoList->dom->trdTitle->setValue($todoTitle->third);
        $todoList->wait(1);
        $todoList->dom->saveBtn->click();
        $todoList->wait(1);

        $fstTodo = $todoList->dom->fstTodoTitle->getText();
        $secTodo = $todoList->dom->secTodoTitle->getText();
        $trdTodo = $todoList->dom->trdTodoTitle->getText();

        $this->openUrl('my', 'todo', array('type' => 'all'));
        $todoList = $this->loadPage('my', 'todo', array('type' => 'all'));
        $nameList    = [$todoTitle->first, $todoTitle->second, $todoList->third];
        $nameToCheck = [$fstTodo, $secTodo, $trdTodo];
        foreach ($nameToCheck as $name)
        {
            echo "$name 创建" . (in_array($name, $nameList)
                ? '成功'
                : '失败'
            );
        }
    }
}
