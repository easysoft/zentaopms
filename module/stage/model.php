<?php
class stageModel extends model
{
    public function create()
    {
        $stage = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::today())
            ->get();

        $this->dao->insert(TABLE_STAGE)->data($stage)->autoCheck()->exec();

        if(!dao::isError()) return $this->dao->lastInsertID();
        return false;
    }

    public function batchCreate()
    {
        $data = fixer::input('post')->get(); 

        $this->loadModel('action');
        foreach($data->name as $i => $name)
        {
            if(!$name) continue; 

            $stage = new stdclass();
            $stage->name        = $name;
            $stage->percent     = $data->percent[$i];
            $stage->type        = $data->type[$i];
            $stage->createdBy   = $this->app->user->account;
            $stage->createdDate = helper::today();

            $this->dao->insert(TABLE_STAGE)->data($stage)->autoCheck()->exec();

            $stageID = $this->dao->lastInsertID();
            $this->action->create('stage', $stageID, 'Opened');
        }

        return true;
    }

    public function update($stageID)
    {
        $oldStage = $this->dao->select('*')->from(TABLE_STAGE)->where('id')->eq((int)$stageID)->fetch();

        $stage = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->get();

        $this->dao->update(TABLE_STAGE)->data($stage)->autoCheck()->where('id')->eq((int)$stageID)->exec();

        if(!dao::isError()) return common::createChanges($oldStage, $stage);
        return false;
    }

    public function getStages($orderBy = 'id_desc')
    {
        return $this->dao->select('*')->from(TABLE_STAGE)->where('deleted')->eq(0)->orderBy($orderBy)->fetchAll('id');
    }

    public function getPairs()
    {
        $stages = $this->getStages();

        $pairs = array();
        foreach($stages as $stageID => $stage)
        {
            $pairs[$stageID] = $stage->name;
        }

        return $pairs;
    }

    public function getByID($stageID)
    {
        return $this->dao->select('*')->from(TABLE_STAGE)->where('deleted')->eq(0)->andWhere('id')->eq((int)$stageID)->fetch();
    }
}
