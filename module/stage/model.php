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

    public function getStages()
    {
        return $this->dao->select('*')->from(TABLE_STAGE)->where('deleted')->eq(0)->fetchAll('id');
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
}
