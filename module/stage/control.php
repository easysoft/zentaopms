<?php
/**
 * The control file of stage currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     stage
 * @version     $Id: control.php 5107 2013-07-12 01:46:12Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class stage extends control
{
    /**
     * 瀑布模型阶段列表页。
     * Waterfall model stage list page.
     *
     * @param  string $orderBy
     * @param  string $type    waterfall|waterfallplus
     * @access public
     * @return void
     */
    public function browse(string $orderBy = "id_asc", string $type = 'waterfall')
    {
        if($type == 'waterfallplus') $this->locate($this->createLink('stage', 'plusBrowse', "orderBy={$orderBy}&type=waterfallplus"));

        $this->stage->setMenu($type);

        $this->view->title   = $this->lang->stage->common . $this->lang->colon . $this->lang->stage->browse;
        $this->view->stages  = $this->stage->getStages($orderBy, 0, $type);
        $this->view->orderBy = $orderBy;
        $this->view->type    = $type;

        $this->display();
    }

    /**
     * 融合瀑布模型阶段列表页。
     * Waterfall plus model stage list page.
     *
     * @param  string $orderBy
     * @param  string $type    waterfall|waterfallplus
     * @access public
     * @return void
     */
    public function plusBrowse($orderBy = "id_asc", $type = 'waterfallplus')
    {
        if($type == 'waterfall') $this->locate($this->createLink('stage', 'browse', "orderBy={$orderBy}&type=waterfall"));

        $this->stage->setMenu($type);

        $this->view->stages  = $this->stage->getStages($orderBy, 0, $type);
        $this->view->orderBy = $orderBy;
        $this->view->type    = $type;
        $this->view->title   = $this->lang->stage->common . $this->lang->colon . $this->lang->stage->browse;

        $this->display('stage', 'browse');
    }

    /**
     * Create a stage.
     *
     * @param  string $type waterfall|waterfallplus
     * @access public
     * @return void
     */
    public function create(string $type = 'waterfall')
    {
        if($_POST)
        {
            $stageID = $this->stage->create($type);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if(!$stageID)
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action')->create('stage', $stageID, 'Opened');

            $response['result']     = 'success';
            $response['message']    = $this->lang->saveSuccess;
            $response['closeModal'] = true;
            $response['load']       = true;
            return $this->send($response);
        }

        $this->view->title = $this->lang->stage->common . $this->lang->colon . $this->lang->stage->create;

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

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['load']    = inlink($type == 'waterfall' ? 'browse' : 'plusBrowse', "orderBy=id_asc&type=$type");
            return $this->send($response);
        }

        $this->view->title       = $this->lang->stage->common . $this->lang->colon . $this->lang->stage->batchCreate;

        $this->display();
    }

    /**
     * Edit a stage.
     *
     * @param  int    $stageID
     * @access public
     * @return void
     */
    public function edit(int $stageID = 0)
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
            $response['closeModal'] = true;
            $response['load']       = true;
            return $this->send($response);
        }

        $this->view->title = $this->lang->stage->common . $this->lang->colon . $this->lang->stage->edit;
        $this->view->stage = $stage;

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
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
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
     * @access public
     * @return void
     */
    public function delete(int $stageID)
    {
        $stage = $this->stage->getById($stageID);

        $this->stage->delete(TABLE_STAGE, $stageID);

        if(dao::isError())
        {
            $response['result']  = 'fail';
            $response['message'] = dao::getError();
        }
        else
        {
            $response['result'] = 'success';
            $response['load']   = true;
        }

        return $this->send($response);
    }
}
