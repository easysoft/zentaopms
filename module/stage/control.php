<?php
declare(strict_types=1);
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
     * @param  int    $groupID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function browse(int $groupID = 0, string $orderBy = "order_asc")
    {
        if($this->config->edition != 'open')
        {
            $workflowGroup = $this->loadModel('workflowgroup')->getByID($groupID);
            if($workflowGroup->projectModel == 'ipd')
            {
                $this->config->stage->dtable->fieldList['type']['statusMap'] = $this->lang->stage->ipdTypeList;

                $this->config->stage->dtable->fieldList['TRpoint']['title'] = $this->lang->stage->TRpoint;
                $this->config->stage->dtable->fieldList['TRpoint']['type']  = 'desc';
                $this->config->stage->dtable->fieldList['TRpoint']['width'] = 100;
                $this->config->stage->dtable->fieldList['TRpoint']['flex']  = false;
                $this->config->stage->dtable->fieldList['TRpoint']['group'] = 3;

                $this->config->stage->dtable->fieldList['DCPpoint']['title'] = $this->lang->stage->DCPpoint;
                $this->config->stage->dtable->fieldList['DCPpoint']['type']  = 'desc';
                $this->config->stage->dtable->fieldList['DCPpoint']['width'] = 100;
                $this->config->stage->dtable->fieldList['DCPpoint']['flex']  = false;
                $this->config->stage->dtable->fieldList['DCPpoint']['group'] = 4;

                if(common::hasPriv('stage', 'setTRpoint'))
                {
                    $this->config->stage->actionList['setTRpoint']['icon']        = 'tr-box';
                    $this->config->stage->actionList['setTRpoint']['hint']        = $this->lang->stage->setTRpoint;
                    $this->config->stage->actionList['setTRpoint']['url']         = array('module' => 'stage', 'method' => 'setTRpoint', 'params' => 'stageID={id}');
                    $this->config->stage->actionList['setTRpoint']['data-toggle'] = 'modal';
                }

                if(common::hasPriv('stage', 'setDCPpoint'))
                {
                    $this->config->stage->actionList['setDCPpoint']['icon']        = 'dcp-box';
                    $this->config->stage->actionList['setDCPpoint']['hint']        = $this->lang->stage->setDCPpoint;
                    $this->config->stage->actionList['setDCPpoint']['url']         = array('module' => 'stage', 'method' => 'setDCPpoint', 'params' => 'stageID={id}');
                    $this->config->stage->actionList['setDCPpoint']['data-toggle'] = 'modal';
                }

                $this->config->stage->dtable->fieldList['actions']['menu'] = array('setTRpoint', 'setDCPpoint', 'edit', 'delete');
                $this->config->stage->dtable->fieldList['actions']['list'] = $this->config->stage->actionList;
            }
        }

        $this->view->title   = $this->lang->stage->common . $this->lang->hyphen . $this->lang->stage->browseAB;
        $this->view->stages  = $this->stage->getStages($orderBy, 0, $groupID);
        $this->view->orderBy = $orderBy;
        $this->view->groupID = $groupID;

        $this->display();
    }

    /**
     * 创建一个阶段。
     * Create a stage.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function create(int $groupID = 0)
    {
        $flow = $this->config->edition == 'open' ? new stdClass() : $this->loadModel('workflowgroup')->getByID($groupID);
        if($_POST)
        {
            if(isset($flow->projectModel) && $flow->projectModel == 'ipd') $this->config->stage->create->requiredFields = 'name,type';

            $stageData = form::data()
                ->setDefault('workflowGroup', $groupID)
                ->setDefault('createdBy', $this->app->user->account)
                ->setDefault('createdDate', helper::now())
                ->get();

            $stageID   = $this->stage->create($stageData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('stage', $stageID, 'Opened');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        unset($this->lang->stage->ipdTypeList['lifecycle']);

        $this->view->title   = $this->lang->stage->common . $this->lang->hyphen . $this->lang->stage->create;
        $this->view->groupID = $groupID;
        $this->view->flow    = $flow;

        $this->display();
    }

    /**
     * 批量创建阶段。
     * Batch create stages.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function batchCreate(int $groupID = 0)
    {
        $flow = $this->config->edition == 'open' ? new stdClass() : $this->loadModel('workflowgroup')->getByID($groupID);
        if($_POST)
        {
            if(isset($flow->projectModel) && $flow->projectModel == 'ipd')
            {
                $this->config->stage->create->requiredFields = 'name,type';
                if(isset($this->config->setPercent) && $this->config->setPercent == 1) $this->config->stage->form->batchcreate['percent']['required'] = false;
            }

            $stages = form::batchData()->get();
            $this->stage->batchCreate($groupID, $stages);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => inlink('browse', "groupID={$groupID}")));
        }

        unset($this->lang->stage->ipdTypeList['lifecycle']);

        $this->view->title   = $this->lang->stage->common . $this->lang->hyphen . $this->lang->stage->batchCreate;
        $this->view->groupID = $groupID;
        $this->view->flow    = $flow;

        $this->display();
    }

    /**
     * 编辑一个阶段。
     * Edit a stage.
     *
     * @param  int    $stageID
     * @access public
     * @return void
     */
    public function edit(int $stageID = 0)
    {
        $stage = $this->stage->getByID($stageID);
        $flow  = $this->config->edition == 'open' ? new stdClass() : $this->loadModel('workflowgroup')->getByID($stage->workflowGroup);

        if($_POST)
        {
            if(isset($flow->projectModel) && $flow->projectModel == 'ipd') $this->config->stage->edit->requiredFields = 'name,type';

            $stageData = form::data()
                ->setDefault('editedBy', $this->app->user->account)
                ->setDefault('editedDate', helper::now())
                ->get();
            $changes   = $this->stage->update($stageID, $stageData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('stage', $stageID, 'Edited');
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        unset($this->lang->stage->ipdTypeList['lifecycle']);

        $this->view->title = $this->lang->stage->common . $this->lang->hyphen . $this->lang->stage->edit;
        $this->view->stage = $stage;
        $this->view->flow  = $flow;

        $this->display();
    }

    /**
     * 设置阶段的类型。
     * Custom settings stage type.
     *
     * @param  string lang2Set
     * @access public
     * @return void
     */
    public function setType(string $lang2Set = '')
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
                    if(isset($dbFields[$key]) && $value != $dbFields[$key]->value) $fieldList[$key] = $dbFields[$key]->value;
                }
            }
        }

        if($_POST)
        {
            $data = form::data()->get();
            $this->custom->deleteItems("lang={$this->post->lang}&module=stage&section=typeList");
            if($data->lang == 'all') $this->custom->deleteItems("lang={$currentLang}&module=stage&section=typeList");
            foreach($data->keys as $index => $key)
            {
                $value = empty($data->values[$index]) ? '' : $data->values[$index];
                if(!$value || !$key) continue;
                $this->custom->setItem("{$data->lang}.stage.typeList.{$key}", $value);
            }
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }

        $this->view->title       = $this->lang->stage->common . $this->lang->hyphen . $this->lang->stage->setType;
        $this->view->currentLang = $currentLang;
        $this->view->lang2Set    = !empty($lang2Set) ? $lang2Set : $lang;
        $this->view->fieldList   = $fieldList;
        $this->display();
    }

    /**
     * 删除一个阶段。
     * Delete a stage.
     *
     * @param  int    $stageID
     * @access public
     * @return void
     */
    public function delete(int $stageID)
    {
        $stage = $this->stage->getByID($stageID);
        $this->stage->delete(TABLE_STAGE, $stageID);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->sendSuccess(array('closeModal' => true, 'load' => true));
    }

    /**
     * 设置TR评审点。
     * Set TR point of stage.
     *
     * @param  int    $stageID
     * @access public
     * @return void
     */
    public function setTRpoint(int $stageID)
    {
        $this->app->loadLang('review');

        if(!empty($_POST))
        {
            $this->lang->stage->title = $this->lang->stage->TRname;
            $points = form::batchData()->get();
            $this->stage->setPoint('TR', $stageID, $points);
            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('closeModal' => true, 'load' => true));
        }

        $this->view->type        = 'TR';
        $this->view->approvals   = $this->loadModel('approvalflow')->getPairs('project');
        $this->view->stagePoints = $this->stage->getStagePoints('TR', $stageID);
        $this->display('stage', 'setPoint');
    }

    /**
     * 设置DCP评审点。
     * Set point of stage.
     *
     * @param  int    $stageID
     * @access public
     * @return void
     */
    public function setDCPpoint(int $stageID)
    {
        $this->app->loadLang('review');

        if(!empty($_POST))
        {
            $this->lang->stage->title = $this->lang->stage->DCPname;
            $points = form::batchData()->get();
            $this->stage->setPoint('DCP', $stageID, $points);
            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('closeModal' => true, 'load' => true));
        }

        $this->view->type        = 'DCP';
        $this->view->approvals   = $this->loadModel('approvalflow')->getPairs('project');
        $this->view->stagePoints = $this->stage->getStagePoints('DCP', $stageID);
        $this->display('stage', 'setPoint');
    }

    /**
     * 更新排序。
     * Update order.
     *
     * @access public
     * @return void
     */
    public function updateOrder()
    {
        $sortedIdList = json_decode($this->post->sortedIdList, true);
        $this->stage->updateOrder($sortedIdList);
    }
}
