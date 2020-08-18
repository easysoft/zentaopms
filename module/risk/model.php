<?php
class riskModel extends model
{
    public function create()
    {
        $risk = fixer::input('post')
            ->add('program', $this->session->program)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::today())
            ->stripTags($this->config->risk->editor->create['id'], $this->config->allowedTags)
            ->remove('uid')
            ->get();

        $risk = $this->loadModel('file')->processImgURL($risk, $this->config->risk->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_RISK)->data($risk)->autoCheck()->batchCheck($this->config->risk->create->requiredFields, 'notempty')->exec();

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

            $risk = new stdclass();
            $risk->name        = $name;
            $risk->percent     = $data->percent[$i];
            $risk->type        = $data->type[$i];
            $risk->createdBy   = $this->app->user->account;
            $risk->createdDate = helper::today();

            $this->dao->insert(TABLE_RISK)->data($risk)->autoCheck()->exec();

            $riskID = $this->dao->lastInsertID();
            $this->action->create('risk', $riskID, 'Opened');
        }

        return true;
    }

    public function update($riskID)
    {
        $oldStage = $this->dao->select('*')->from(TABLE_RISK)->where('id')->eq((int)$riskID)->fetch();

        $risk = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldStage, $risk);
        return false;
    }

    public function getList($orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_RISK)
            ->where('deleted')->eq(0)
            ->andWhere('program')->eq($this->session->program)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    public function getPairs()
    {
        return $this->dao->select('id, name')->from(TABLE_RISK)
            ->where('deleted')->eq(0)
            ->andWhere('program')->eq($this->session->program)
            ->fetchPairs();
    }

    public function getByID($riskID)
    {
        return $this->dao->select('*')->from(TABLE_RISK)->where('id')->eq((int)$riskID)->fetch();
    }
}
