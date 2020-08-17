<?php
class designModel extends model
{
    public function create()
    {
        $design = fixer::input('post')
            ->stripTags('desc', $this->config->allowedTags)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->add('program', $this->session->program)
            ->add('version', 1)
            ->remove('file,files,labels,children, toList')
            ->get();

        $design = $this->loadModel('file')->processImgURL($design, 'desc', $this->post->uid);
        $this->dao->insert(TABLE_DESIGN)->data($design)->autoCheck()->batchCheck('name,type', 'notempty')->exec();

        if(!dao::isError())
        {    
            $designID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $designID, 'design');
            $files = $this->file->saveUpload('design', $designID);

            $spec = new stdclass();
            $spec->design  = $designID;
            $spec->version = 1;
            $spec->name    = $design->name;
            $spec->desc    = $design->desc;
            $spec->files   = empty($files) ? '' : implode(',', array_keys($files));
            $this->dao->insert(TABLE_DESIGNSPEC)->data($spec)->exec();

            return $designID;
        } 

        return false;
    }

    public function update($designID)
    {
        $oldDesign = $this->getByID($designID);
        $design = fixer::input('post')
            ->stripTags('desc', $this->config->allowedTags)
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('file,files,labels,children, toList')
            ->get();

        $design = $this->loadModel('file')->processImgURL($design, 'desc', $this->post->uid);
        $this->dao->update(TABLE_DESIGN)->data($design)->autoCheck()->batchCheck('name,type', 'notempty')->where('id')->eq($designID)->exec();

        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $designID, 'design');
            $files = $this->file->saveUpload('design', $designID);
            $designChanged = ($oldDesign->name != $design->name || $oldDesign->desc != $design->desc || !empty($files));

            if($designChanged)
            {
                $design  = $this->getByID($designID);
                $version = $design->version + 1; 
                $spec = new stdclass();
                $spec->design  = $designID;
                $spec->version = $version;
                $spec->name    = $design->name;
                $spec->desc    = $design->desc;
                $spec->files   = empty($files) ? '' : implode(',', array_keys($files));
                $this->dao->insert(TABLE_DESIGNSPEC)->data($spec)->exec();

                $this->dao->update(TABLE_DESIGN)->set('version')->eq($version)->where('id')->eq($designID)->exec();
            }

            return common::createChanges($oldDesign, $design);
        } 

        return false;
    }
    
    public function linkCommit($designID)
    {
        $this->dao->delete()->from(TABLE_RELATION)->where('AType')->eq('design')->andWhere('AID')->eq($designID)->andWhere('BType')->eq('commit')->andWhere('relation')->eq('completedin')->exec();
        $this->dao->delete()->from(TABLE_RELATION)->where('AType')->eq('commit')->andWhere('BID')->eq($designID)->andWhere('BType')->eq('design')->andWhere('relation')->eq('completedfrom')->exec();
        $revisions = $_POST['revision'];    

        foreach($revisions as $revision)
        {
            $data = new stdclass();
            $data->program  = $this->session->program;
            $data->product  = $this->session->product;
            $data->AType    = 'design';
            $data->AID      = $designID;
            $data->BType    = 'commit';
            $data->BID      = $revision;
            $data->relation = 'completedin';
            $data->extra    = $this->session->repoID;

            $this->dao->replace(TABLE_RELATION)->data($data)->autoCheck()->exec();

            $data->AType    = 'commit';
            $data->AID      = $revision;
            $data->BType    = 'design';
            $data->BID      = $designID;
            $data->relation = 'completedfrom';

            $this->dao->replace(TABLE_RELATION)->data($data)->autoCheck()->exec();
        }
    }

    public function setFlowActionFields($module, $action)
    {
        $flow   = $this->loadModel('workflow', 'flow')->getByModule($module);
        $action = $this->loadModel('workflowaction', 'flow')->getByModuleAndAction($flow->module, $action);
        $fields = $this->workflowaction->getFields($flow->module, $action->action);

        return array($flow, $action, $fields);
    }

    public function setFlowChild($module, $action, $fields, $dataID = 0)
    {
        $this->loadModel('workflowlayout', 'flow');

        $childFields  = array();
        $childDatas   = array();
        $childModules = $this->loadModel('workflow', 'flow')->getList($module, 'table');
        foreach($childModules as $childModule)
        {
            $key = 'sub_' . $childModule->module;

            if(isset($fields[$key]) && $fields[$key]->show)
            {
                $childFields[$key] = $this->workflowaction->getFields($childModule->module, $action);
                $childDatas[$key]  = $this->flow->getDataList($childModule, '', 0, $dataID);
            }
        }

        return array($childFields, $childDatas);
    }

    public function setProductMenu($productID = 0)
    {   
        $programID = $this->session->program;
        $products  = $this->loadModel('product')->getPairs($programID);
        $productID = in_array($productID, array_keys($products)) ? $productID : key($products);

        $productID = $this->loadModel('product')->saveState($productID, $products);
        $this->loadModel('product')->setMenu($products, $productID);
    }

    public function getByID($designID)
    {
        return $this->dao->select('*')->from(TABLE_DESIGN)->where('id')->eq($designID)->fetch();
    }

    public function getDesignPairs($productID = 0, $type = 'detailed')
    {
        $designs = $this->dao->select('id, name')->from(TABLE_DESIGN)
            ->where('product')->eq($productID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('type')->eq($type)
            ->fetchPairs();
        foreach($designs as $id => $name) $designs[$id] = $id . ':' . $name;  

        return $designs;
    }

    public function getAffectedScope($design)
    {    
        /* Get affected tasks. */
        $design->tasks = $this->dao->select('*')->from(TABLE_TASK)
            ->where('deleted')->eq(0)
            ->andWhere('status')->ne('closed')
            ->andWhere('design')->eq($design->id)
            ->orderBy('id desc')->fetchAll();

        return $design;
    }
}
