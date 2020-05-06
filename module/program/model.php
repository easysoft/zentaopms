<?php
class programModel extends model
{
    public function create()
    {
        $this->lang->project->team = $this->lang->project->teamname;
        $project = fixer::input('post')
            ->setDefault('status', 'wait')
            ->setDefault('type', 'program')
            ->setIF($this->post->acl != 'custom', 'whitelist', '')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', helper::now())
            ->setDefault('team', substr($this->post->name,0, 30))
            ->join('whitelist', ',')
            ->cleanInt('budget')
            ->stripTags($this->config->project->editor->create['id'], $this->config->allowedTags)
            ->remove('products, workDays, delta, branch, uid, plans')
            ->get();

        $project = $this->loadModel('file')->processImgURL($project, $this->config->project->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->batchcheck($this->config->project->create->requiredFields, 'notempty')
            ->check('name', 'unique', "deleted='0'")
            ->check('code', 'unique', "deleted='0'")
            ->exec();

        /* Add the creater to the team. */
        if(!dao::isError())
        {
            $projectID     = $this->dao->lastInsertId();
            $today         = helper::today();

            /* Save order. */
            $this->dao->update(TABLE_PROJECT)->set('`order`')->eq($projectID * 5)->where('id')->eq($projectID)->exec();
            $this->file->updateObjectID($this->post->uid, $projectID, 'project');

            $product = new stdclass();
            $product->name        = $project->name;
            $product->project     = $projectID;
            $product->status      = 'normal';
            $product->createdBy   = $this->app->user->account;
            $product->createdDate = helper::now();

            $this->dao->insert(TABLE_PRODUCT)->data($product)->exec();

            $productID = $this->dao->lastInsertId();
            $this->dao->update(TABLE_PRODUCT)->set('`order`')->eq($productID * 5)->where('id')->eq($productID)->exec();

            /* Create doc lib. */
            $this->app->loadLang('doc');
            $lib = new stdclass();
            $lib->product = $productID;
            $lib->name    = $this->lang->doclib->main['product'];
            $lib->type    = 'product';
            $lib->main    = '1'; 
            $lib->acl     = 'default';
            $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();

            $docLibID = $this->dao->lastInsertId();
            $this->loadModel('doc')->syncDocModule($docLibID);

            /* Insert into projectproduct. */
            //$data = new stdclass();
            //$data->project = $projectID;
            //$data->product = $productID;
            //$this->dao->insert(TABLE_PROJECTPRODUCT)->data($data)->exec();

            return $projectID;
        }
    }

    public static function isClickable($project, $action)
    {
        $action = strtolower($action);

        if($action == 'start')    return $project->status == 'wait' or $project->status == 'suspended';
        if($action == 'finish')   return $project->status == 'wait' or $project->status == 'doing';
        if($action == 'close')    return $project->status != 'closed';
        if($action == 'suspend')  return $project->status == 'wait' or $project->status == 'doing';
        if($action == 'activate') return $project->status == 'done';

        return true;
    }

    public function getProducts($program)
    {
       return $this->dao->select('*')->from(TABLE_PRODUCT)->where('project')->eq($program)->fetchAll('id');
    }
}
