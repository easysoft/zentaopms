<?php
declare(strict_types=1);

class todoTao extends todoModel
{
    /**
     * 插入待办数据
     * Insert todo data.
     *
     * @param  object $todo
     * @return int
     */
    protected function insert(object $todo): int
    {
        $this->dao->insert(TABLE_TODO)->data($todo)
            ->autoCheck()
            ->checkIF(!in_array($todo->type, $this->config->todo->moduleList), $this->config->todo->create->requiredFields, 'notempty')
            ->checkIF(in_array($todo->type, $this->config->todo->moduleList) && $todo->idvalue == 0, 'idvalue', 'notempty')
            ->exec();

        return (int)$this->dao->lastInsertID();
    }

    /**
     * 更新待办数据
     * Update todo data.
     *
     * @param  int    $todoID
     * @param  object $todo
     * @return bool
     */
    protected function updateRow(int $todoID, object $todo): bool
    {
        $this->dao->update(TABLE_TODO)->data($todo)
            ->autoCheck()
            ->checkIF(in_array($todo->type, array('custom', 'feedback')), $this->config->todo->edit->requiredFields, 'notempty')
            ->checkIF(in_array($todo->type, $this->config->todo->moduleList) && $todo->idvalue == 0, 'idvalue', 'notempty')
            ->where('id')->eq($todoID)
            ->exec();

        return !dao::isError();
    }
}
