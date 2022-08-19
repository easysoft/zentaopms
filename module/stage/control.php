<?php
/**
 * The control file of stage currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     stage
 * @version     $Id: control.php 5107 2013-07-12 01:46:12Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class stage extends control
{
    /**
     * Browse stages.
     *
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function browse($orderBy = "id_asc")
    {
        $this->view->stages      = $this->stage->getStages($orderBy);
        $this->view->orderBy     = $orderBy;
        $this->view->title       = $this->lang->stage->common . $this->lang->colon . $this->lang->stage->browse;
        $this->view->position[]  = $this->lang->stage->common;
        $this->view->position[]  = $this->lang->stage->browse;

        $this->display();
    }

    /**
     * Create a stage.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $stageID = $this->stage->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(!$stageID)
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action')->create('stage', $stageID, 'Opened');
            $response['locate']  = inlink('browse');
            return $this->send($response);
        }

        $this->view->title       = $this->lang->stage->common . $this->lang->colon . $this->lang->stage->create;
        $this->view->position[]  = $this->lang->stage->common;
        $this->view->position[]  = $this->lang->stage->create;

        $this->display();
    }

    /**
     * Batch create stages.
     *
     * @access public
     * @return void
     */
    public function batchCreate()
    {
        if($_POST)
        {
            $this->stage->batchCreate();

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $response['locate']  = inlink('browse');
            return $this->send($response);
        }

        $this->view->title       = $this->lang->stage->common . $this->lang->colon . $this->lang->stage->batchCreate;
        $this->view->position[]  = $this->lang->stage->common;
        $this->view->position[]  = $this->lang->stage->batchCreate;

        $this->display();
    }

    /**
     * Edit a stage.
     *
     * @param  int    $stageID
     * @access public
     * @return void
     */
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
                return $this->send($response);
            }

            $actionID = $this->loadModel('action')->create('stage', $stageID, 'Edited');
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            $response['locate']  = inlink('browse');
            return $this->send($response);
        }

        $this->view->title       = $this->lang->stage->common . $this->lang->colon . $this->lang->stage->edit;
        $this->view->position[]  = $this->lang->stage->common;
        $this->view->position[]  = $this->lang->stage->edit;
        $this->view->stage       = $stage;

        $this->display();
    }

    /**
     * Custom settings stage type.
     *
     * @param  string lang2Set
     * @access public
     * @return void
     */
    public function setType($lang2Set = '')
    {
        $this->loadModel('custom');
        $lang = $this->app->getClientLang();
        if($_POST)
        {
            $data = fixer::input('post')->get();
            $this->custom->deleteItems("lang={$data->lang}&module=stage&section=typeList");

            foreach($data->keys as $index => $key)
            {
                $value = $data->values[$index];
                if(!$value or !$key) continue;
                $this->custom->setItem("all.stage.typeList.{$key}", $value);
            }
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('stage', 'settype', "lang2Set=$data->lang")));
        }

        $this->view->title       = $this->lang->stage->common . $this->lang->colon . $this->lang->stage->setType;
        $this->view->currentLang = $lang;
        $this->view->lang2Set    = !empty($lang2Set) ? $lang2Set : $lang;
        $this->display();
    }

    /**
     * Delete a stage.
     *
     * @param  int    $stageID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($stageID, $confirm = 'no')
    {
        $stage = $this->stage->getById($stageID);

        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->stage->confirmDelete, inlink('delete', "stageID=$stageID&confirm=yes")));
        }
        else
        {
            $this->stage->delete(TABLE_STAGE, $stageID);

            return print(js::reload('parent'));
        }
    }
}
