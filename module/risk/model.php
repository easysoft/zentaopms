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
        $oldRisk = $this->dao->select('*')->from(TABLE_RISK)->where('id')->eq((int)$riskID)->fetch();

        $risk = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->stripTags($this->config->risk->editor->edit['id'], $this->config->allowedTags)
            ->remove('uid')
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    public function track($riskID)
    {
        $oldRisk = $this->dao->select('*')->from(TABLE_RISK)->where('id')->eq((int)$riskID)->fetch();

        $risk = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->stripTags($this->config->risk->editor->track['id'], $this->config->allowedTags)
            ->remove('ischange,comment,uid')
            ->removeIF($this->post->isChange == 0, 'name,category,strategy,impact,probability,riskindex,pri,prevention,resolution' )
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
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

    public function printAssignedHtml($risk, $users)
    {
        $btnTextClass   = '';
        $assignedToText = zget($users, $risk->assignedTo);

        if(empty($risk->assignedTo))
        {
            $btnTextClass   = 'text-primary';
            $assignedToText = $this->lang->risk->noAssigned;
        }
        if($risk->assignedTo == $this->app->user->account) $btnTextClass = 'text-red';

        $btnClass     = $risk->assignedTo == 'closed' ? ' disabled' : '';
        $btnClass     = "iframe btn btn-icon-left btn-sm {$btnClass}";
        $assignToLink = helper::createLink('risk', 'assignTo', "riskID=$risk->id", '', true);
        $assignToHtml = html::a($assignToLink, "<i class='icon icon-hand-right'></i> <span title='" . zget($users, $risk->assignedTo) . "' class='{$btnTextClass}'>{$assignedToText}</span>", '', "class='$btnClass'");

        echo !common::hasPriv('risk', 'assignTo', $risk) ? "<span style='padding-left: 21px' class='{$btnTextClass}'>{$assignedToText}</span>" : $assignToHtml;
    }

    public function assign($riskID)
    {
        $oldRisk = $this->getByID($riskID);
        
        $risk = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->setDefault('assignedDate', helper::today())
            ->stripTags($this->config->risk->editor->assignto['id'], $this->config->allowedTags)
            ->remove('uid,comment')
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    public function cancel($riskID)
    {
        $oldRisk = $this->getByID($riskID);
        
        $risk = fixer::input('post')
            ->setDefault('status','canceled')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->stripTags($this->config->risk->editor->cancel['id'], $this->config->allowedTags)
            ->remove('uid,comment')
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    public function close($riskID)
    {
        $oldRisk = $this->getByID($riskID);
        
        $risk = fixer::input('post')
            ->setDefault('status','closed')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->stripTags($this->config->risk->editor->close['id'], $this->config->allowedTags)
            ->remove('uid,comment')
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    public function hangup($riskID)
    {
        $oldRisk = $this->getByID($riskID);
        
        $risk = fixer::input('post')
            ->setDefault('status','hangup')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    public function activate($riskID)
    {
        $oldRisk = $this->getByID($riskID);
        
        $risk = fixer::input('post')
            ->setDefault('status','active')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }
}
