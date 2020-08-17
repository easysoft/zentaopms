<?php
class stage extends control
{
    public function browse($orderBy = "id_desc")
    {
        $this->view->stages      = $this->stage->getStages($orderBy);
        $this->view->orderBy     = $orderBy;
        $this->view->title       = $this->lang->stage->common . $this->lang->colon . $this->lang->stage->browse;
        $this->view->position[]  = $this->lang->stage->common;
        $this->view->position[]  = $this->lang->stage->browse;

        $this->display();
    }

    public function create()
    {
        if($_POST)
        {
            $stageID = $this->stage->create(); 

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(!$stageID)
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $this->loadModel('action')->create('stage', $stageID, 'Opened');
            $response['locate']  = inlink('browse');
            $this->send($response);
        }

        $this->view->title       = $this->lang->stage->common . $this->lang->colon . $this->lang->stage->create;
        $this->view->position[]  = $this->lang->stage->common;
        $this->view->position[]  = $this->lang->stage->create;

        $this->display();
    }

    public function edit($stageID = 0)
    {
        $stage = $this->stage->getByID($stageID);
        if($_POST)
        {
            $changes = $this->stage->update($stageID); 

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $actionID = $this->loadModel('action')->create('stage', $stageID, 'Edited');
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            $response['locate']  = inlink('browse');
            $this->send($response);
        }

        $this->view->title       = $this->lang->stage->common . $this->lang->colon . $this->lang->stage->edit;
        $this->view->position[]  = $this->lang->stage->common;
        $this->view->position[]  = $this->lang->stage->edit;
        $this->view->stage       = $stage;

        $this->display();
    }

    public function setType()
    {
        $this->loadModel('custom');
        if($_POST)
        {
            $data = fixer::input('post')->get();
            $this->custom->deleteItems("lang=all&module=stage&section=typeList");
            foreach($data->keys as $index => $key)
            {   
                $value = $data->values[$index];
                if(!$value or !$key) continue; 
                $this->custom->setItem("all.stage.typeList.{$key}", $value);
            }
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('stage', 'settype')));
        }

        $this->view->title       = $this->lang->stage->common . $this->lang->colon . $this->lang->stage->setType;
        $this->view->position[]  = $this->lang->stage->common;
        $this->view->position[]  = $this->lang->stage->setType;
        $this->display();
    }

    public function delete($stageID, $confirm = 'no')
    {
        $stage = $this->stage->getById($stageID);

        if($confirm == 'no')
        {
            die(js::confirm($this->lang->stage->confirmDelete, inlink('delete', "stageID=$stageID&confirm=yes")));
        }
        else
        {
            $this->stage->delete(TABLE_STAGE, $stageID);

            die(js::reload('parent'));
        }
    }
}
