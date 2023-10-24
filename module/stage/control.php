<?php
/**
 * The control file of stage currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
     * @param  string $type
     * @access public
     * @return void
     */
    public function browse($orderBy = "id_asc", $type = 'waterfall')
    {
        if($type == 'waterfallplus') $this->locate($this->createLink('stage', 'plusBrowse', "orderBy=$orderBy&type=waterfallplus"));

        $this->stage->setMenu($type);

        $this->view->stages      = $this->stage->getStages($orderBy, 0, $type);
        $this->view->orderBy     = $orderBy;
        $this->view->type        = $type;
        $this->view->title       = $this->lang->stage->common . $this->lang->colon . $this->lang->stage->browse;
        $this->view->position[]  = $this->lang->stage->common;
        $this->view->position[]  = $this->lang->stage->browse;

        $this->display();
    }

    /**
     * Browse stages.
     *
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function plusBrowse($orderBy = "id_asc", $type = 'waterfallplus')
    {
        if($type == 'waterfall') $this->locate($this->createLink('stage', 'browse', "orderBy=$orderBy&type=waterfall"));

        $this->stage->setMenu($type);

        $this->view->stages      = $this->stage->getStages($orderBy, 0, $type);
        $this->view->orderBy     = $orderBy;
        $this->view->type        = $type;
        $this->view->title       = $this->lang->stage->common . $this->lang->colon . $this->lang->stage->browse;
        $this->view->position[]  = $this->lang->stage->common;
        $this->view->position[]  = $this->lang->stage->browse;

        $this->display('stage', 'browse');
    }

    /**
     * Create a stage.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function create($type = 'waterfall')
    {
        $this->stage->setMenu($type);
        if($_POST)
        {
            $stageID = $this->stage->create($type);
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
            $response['locate']  = inlink($type == 'waterfall' ? 'browse' : 'plusBrowse', "orderBy=id_asc&type=$type");
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
     * @param  string $type
     * @access public
     * @return void
     */
    public function batchCreate($type = 'waterfall')
    {
        $this->stage->setMenu($type);
        if($_POST)
        {
            $this->stage->batchCreate($type);

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $response['locate']  = inlink($type == 'waterfall' ? 'browse' : 'plusBrowse', "orderBy=id_asc&type=$type");
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
        $this->stage->setMenu($stage->projectType);
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
            $response['locate']  = inlink($type == 'waterfall' ? 'browse' : 'plusBrowse', "orderBy=id_asc&type=$stage->projectType");
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
        if(empty($lang2Set)) $lang2Set = $this->app->getClientLang();
        $currentLang = $this->app->getClientLang();

        $fieldList = zget($this->lang->stage, 'typeList', '');
        if($lang2Set == 'all')
        {
            $fieldList = array();
            $items     = $this->custom->getItems("lang=all&module=stage&section=typeList&vision={$this->config->vision}");
            foreach($items as $key => $item) $fieldList[$key] = $item->value;
        }

        /* Check whether the current language has been customized. */
        $dbFields = $this->custom->getItems("lang=$lang2Set&module=stage&section=typeList&vision={$this->config->vision}");
        if(empty($dbFields)) $dbFields = $this->custom->getItems("lang=" . ($lang2Set == $currentLang ? 'all' : $currentLang) . "&module=stage&section=typeList");
        if($dbFields)
        {
            $dbField = reset($dbFields);
            if($lang2Set != $dbField->lang)
            {
                $lang2Set = $dbField->lang;
                foreach($fieldList as $key => $value)
                {
                    if(isset($dbFields[$key]) and $value != $dbFields[$key]->value) $fieldList[$key] = $dbFields[$key]->value;
                }
            }
        }

        if($_POST)
        {
            $data = fixer::input('post')->get();
            $this->custom->deleteItems("lang={$data->lang}&module=stage&section=typeList");
            if($data->lang == 'all') $this->custom->deleteItems("lang=$currentLang&module=stage&section=typeList");

            foreach($data->keys as $index => $key)
            {
                $value = $data->values[$index];
                if(!$value or !$key) continue;
                $this->custom->setItem("{$data->lang}.stage.typeList.{$key}", $value);
            }
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('stage', 'settype', "lang2Set=" . ($data->lang == 'all' ? 'all' : ''))));
        }

        $this->view->title       = $this->lang->stage->common . $this->lang->colon . $this->lang->stage->setType;
        $this->view->currentLang = $currentLang;
        $this->view->lang2Set    = !empty($lang2Set) ? $lang2Set : $lang;
        $this->view->fieldList   = $fieldList;
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
