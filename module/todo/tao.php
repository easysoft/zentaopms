<?php
declare(strict_types=1);

class todoTao extends todoModel
{
    protected function create(object $todo): int
    {
        $this->dao->insert(TABLE_TODO)->data($todo)
            ->autoCheck()
            ->checkIF(!in_array($todo->type, $this->config->todo->moduleList), $this->config->todo->create->requiredFields, 'notempty')
            ->checkIF(in_array($todo->type, $this->config->todo->moduleList) && $todo->idvalue == 0, 'idvalue', 'notempty')
            ->exec();

        return (int)$this->dao->lastInsertID();
    }
}
