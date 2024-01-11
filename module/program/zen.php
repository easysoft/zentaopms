<?php
declare(strict_types=1);
/**
 * The zen file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      dingguodong <dingguodong@easycorp.ltd>
 * @link        https://www.zentao.net
 */

class programZen extends program
{
    /**
     * 追加额外的数据到提交的表单数据。
     * Append extras data to post data.
     *
     * @param  object     $postData
     * @access protected
     * @return object
     */
    protected function prepareStartExtras(object $postData): object
    {
        return $postData->add('status', 'doing')
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', helper::now())
            ->get();
    }

    /**
     * 获取创建项目集的数据。
     * Build program data for create.
     *
     * @access protected
     * @return object
     */
    protected function buildProgramForCreate(): object
    {
        $fields = $this->config->program->form->create;
        $editorFields = array_keys(array_filter(array_map(function($config){return $config['control'] == 'editor';}, $fields)));
        foreach(explode(',', trim($this->config->program->create->requiredFields, ',')) as $field)
        {
            if($field == 'end') continue;
            $fields[$field]['required'] = true;
        }

        $this->app->loadConfig('project');
        if($this->post->delta == 999) $_POST['end'] = LONG_TIME;
        $program = form::data($fields)
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', helper::now())
            ->setDefault('code', '')
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->setIF($this->post->budget != 0, 'budget', round((float)$this->post->budget * $this->config->project->budget->tenThousand, 2))
            ->add('type', 'program')
            ->get();

        return $this->loadModel('file')->processImgURL($program, $editorFields, $this->post->uid);
    }

    /**
     * 获取编辑项目集的数据。
     * Build program for edit.
     *
     * @param  int       $programID
     * @access protected
     * @return object
     */
    protected function buildProgramForEdit(int $programID): object
    {
        $oldProgram   = $this->program->fetchByID($programID);
        $fields       = $this->config->program->form->edit;
        $editorFields = array_keys(array_filter(array_map(function($config){return $config['control'] == 'editor';}, $fields)));
        foreach(explode(',', trim($this->config->program->edit->requiredFields, ',')) as $field)
        {
            if($field == 'end') continue;
            $fields[$field]['required'] = true;
        }

        $this->app->loadConfig('project');
        if($this->post->delta == 999) $_POST['end'] = LONG_TIME;
        $program = form::data($fields)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setIF(helper::isZeroDate($this->post->begin), 'begin', '')
            ->setIF(helper::isZeroDate($this->post->end), 'end', '')
            ->setIF($this->post->realBegan != '' and $oldProgram->status == 'wait', 'status', 'doing')
            ->setIF($this->post->future, 'budget', 0)
            ->setIF($this->post->budget != 0, 'budget', round((float)$this->post->budget * $this->config->project->budget->tenThousand, 2))
            ->setIF(!isset($_POST['budgetUnit']), 'budgetUnit', $oldProgram->budgetUnit)
            ->setIF(!isset($_POST['whitelist']), 'whitelist', '')
            ->join('whitelist', ',')
            ->get();

        return $this->loadModel('file')->processImgURL($program, $editorFields, $this->post->uid);
    }

    protected function removeSubjectToCurrent(array $parents, int $programID): array
    {
        $children = $this->dao->select('*')->from(TABLE_PROGRAM)->where('path')->like("%,$programID,%")->fetchPairs('id', 'id');
        foreach($children as $childID) unset($parents[$childID]);

        return $parents;
    }

    /**
     * 根据条件获取项目集。
     * Get programs by type.
     *
     * @param  string      $status
     * @param  string      $orderBy
     * @param  int         $param
     * @param  object|null $pager
     * @access protected
     * @return array
     */
    protected function getProgramsByType(string $status, string $orderBy, int $param = 0, object|null $pager = null): array
    {
        $status = strtolower($status);
        $params = array();
        $this->view->summary = '';

        if(strtolower($status) == 'bysearch')
        {
            $programs = $this->program->getListBySearch($orderBy, $param, true, $pager);
        }
        else
        {
            /* Get top programs and projects. */
            $topObjects = $this->program->getList($status == 'unclosed' ? 'doing,suspended,wait' : $status, $orderBy, 'top', array(), $pager);
            if(!$topObjects) $topObjects = array(0);

            $programs = $this->program->getList($status, $orderBy, 'child', array_keys($topObjects));
        }

        /* Get summary. */
        $topCount = $indCount = 0;
        foreach($programs as $program)
        {
            if($program->type == 'program' and $program->parent == 0) $topCount ++;
            if($program->type == 'project' and $program->parent == 0) $indCount ++;
        }
        $this->view->summary = sprintf($this->lang->program->summary, $topCount, $indCount);

        return $programs;
    }

    /**
     * 根据项目集，获取产品经理列表。
     * Get PM list by programs.
     *
     * @param  array     $programs
     * @access protected
     * @return array
     */
    protected function getPMListByPrograms(array $programs): array
    {
        $accounts   = array();
        $hasProject = false;
        foreach($programs as $program)
        {
            if(!empty($program->PM) and !in_array($program->PM, $accounts)) $accounts[] = $program->PM;
            if($hasProject === false and $program->type != 'program')       $hasProject = true;
        }
        $this->view->hasProject = $hasProject;

        return $this->loadModel('user')->getListByAccounts($accounts, 'account');
    }

    /**
     * 获取项目集看板。
     * Get kanban list for product.
     *
     * @param  string    $browseType
     * @access protected
     * @return array
     */
    protected function getKanbanList(string $browseType = 'my'): array
    {
        $kanbanList = array();

        $programPairs = array(0 => $this->lang->project->noProgram) + $this->program->getPairs(true, 'order_asc');
        $programList  = $this->getProgramList4Kanban($browseType);

        foreach($programList as $programID => $productList)
        {
            $region = array();

            $heading = new stdclass();
            $heading->title = zget($programPairs, $programID, $programID);

            $region['key']     = $programID;
            $region['id']      = $programID;
            $region['heading'] = $heading;

            $lanes       = array();
            $items       = array();
            $columnCards = array();
            foreach($productList as $laneKey => $laneData)
            {
                $lanes[] = array('name' => $laneKey, 'title' => $laneData->name);
                $columns = array();

                foreach(array('unexpiredPlans', 'waitingProjects', 'doing', 'doingProjects', 'doingExecutions', 'normalReleases') as $columnKey)
                {
                    $colTitle = $columnKey == 'doing' ? $this->lang->program->statusList['doing'] : $this->lang->program->kanban->{$columnKey};
                    $column = array('name' => $columnKey, 'title' => $colTitle);
                    if($columnKey == 'doingExecutions' || $columnKey == 'doingProjects') $column['parentName'] = 'doing';
                    $columns[] = $column;

                    $cardList = !empty($laneData->{$columnKey}) ? $laneData->{$columnKey} : array();
                    foreach($cardList as $card)
                    {
                        $items[$laneKey][$columnKey][] = array('id' => $card->id, 'name' => $card->id, 'title' => isset($card->name) ? $card->name : $card->title, 'status' => isset($card->status) ? $card->status : '', 'type' => $columnKey, 'delay' => !empty($card->delay) ? $card->delay : 0, 'progress' => isset($card->progress) ? $card->progress : 0, 'marker' => isset($card->marker) ? $card->marker : 0);

                        if(!isset($columnCards[$columnKey])) $columnCards[$columnKey] = 0;
                        $columnCards[$columnKey] ++;

                        if($columnKey == 'doingProjects')
                        {
                            if(!empty($latestExecutions[$card->id]))
                            {
                                $execution = $latestExecutions[$card->id];
                                $items[$laneKey]['doingExecutions'][] = array('id' => $execution->id, 'name' => $execution->id, 'title' => $execution->name, 'status' => $execution->status, 'type' => 'doingExecution', 'delay' => !empty($execution->delay) ? $execution->delay : 0, 'progress' => $execution->progress);

                                if(!isset($columnCards['doingExecutions'])) $columnCards['doingExecutions'] = 0;
                                $columnCards['doingExecutions'] ++;
                            }
                        }
                    }
                }
            }

            foreach($columns as $key => $column) $columns[$key]['cards'] = !empty($columnCards[$column['name']]) ? $columnCards[$column['name']] : 0;
            $groupData['key']           = $programID;
            $groupData['data']['lanes'] = $lanes;
            $groupData['data']['cols']  = $columns;
            $groupData['data']['items'] = $items;

            $region['items'] = array($groupData);
            $kanbanList[] = $region;
        }

        return $kanbanList;
    }

    /**
     * 获取项目集看板的数据。
     * Get kanban list data for product.
     *
     * @param  string    $browseType
     * @access protected
     * @return array
     */
    protected function getProgramList4Kanban($browseType = 'my'): array
    {
        $programs = $this->program->getTopPairs('noclosed');
        $involvedPrograms = $this->program->getInvolvedPrograms($this->app->user->account);
        foreach($programs as $programID => $programName)
        {
            if($browseType == 'my')
            {
                if(!in_array($programID, $involvedPrograms)) unset($programs[$programID]);
            }
            else
            {
                if(in_array($programID, $involvedPrograms)) unset($programs[$programID]);
            }
        }

        list($productGroup, $planGroup, $releaseGroup, $projectGroup, $doingExecutions) = $this->program->getKanbanStatisticData(array(0 => '') + $programs);

        $programList = array();
        $today       = helper::today();
        foreach($productGroup as $programID => $productList)
        {
            foreach($productList as $product)
            {
                $product->unexpiredPlans = isset($planGroup[$product->id]) ? $planGroup[$product->id] : array();

                if(isset($projectGroup[$product->id]))
                {
                    foreach($projectGroup[$product->id] as $project)
                    {
                        if(helper::diffDate($today, $project->end) > 0) $project->delay = 1;
                        if($project->status == 'wait')
                        {
                            $product->waitingProjects[$project->id] = $project;
                        }
                        elseif($project->status == 'doing')
                        {
                            $product->doingProjects[$project->id] = $project;
                            if(isset($doingExecutions[$project->id]))
                            {
                                $doingExecution = $doingExecutions[$project->id];
                                if(helper::diffDate($today, $doingExecution->end) > 0) $doingExecution->delay = 1;
                                $product->doingExecutions[$doingExecution->id] = $doingExecution;
                            }
                        }
                    }
                }

                $product->normalReleases = isset($releaseGroup[$product->id]) ? $releaseGroup[$product->id] : array();
                $programList[$programID][$product->id] = $product;
            }
        }

        return $programList;
    }

    /**
     * 构造1.5级导航的数据。
     * Build the data of 1.5 level navigation.
     *
     * @param  array     $programs
     * @param  int       $parentID
     * @access protected
     * @return void
     */
    protected function buildTree(array $programs, int $parentID = 0): array
    {
        $result = array();
        foreach($programs as $program)
        {
            if($program->type != 'program') continue;
            if($program->parent == $parentID)
            {
                $itemArray = array
                (
                    'id'    => $program->id,
                    'text'  => $program->name,
                    'keys'  => zget(common::convert2Pinyin(array($program->name)), $program->name, ''),
                    'items' => $this->buildTree($programs, $program->id)
                );

                if(empty($itemArray['items'])) unset($itemArray['items']);
                $result[] = $itemArray;
            }
        }
        return $result;
    }

    /**
     * 获取下拉树菜单的链接。
     * Get link for drop tree menu.
     *
     * @param  string    $moduleName
     * @param  string    $methodName
     * @param  int       $programID
     * @param  string    $vars
     * @param  string    $from
     * @access protected
     * @return string
     */
    protected function getLink(string $moduleName, string $methodName, string $programID, string $vars = '', string$from = 'program'): string
    {
        if($from != 'program') return helper::createLink('product', 'all', "programID={$programID}" . $vars);

        if($moduleName == 'project')
        {
            $moduleName = 'program';
            $methodName = 'project';
        }
        if($moduleName == 'product')
        {
            $moduleName = 'program';
            $methodName = 'product';
        }

        return helper::createLink($moduleName, $methodName, "programID={$programID}");
    }

    /**
     * 在产品视角查看项目时通过浏览类型获取产品列表。
     * Get products by browse type of the program for product view in program list.
     *
     * @param  string $browseType
     * @param  array  $products
     * @return array
     */
    protected function getProductsByBrowseType(string $browseType, array $products): array
    {
        /* Filter the program by browse type. */
        foreach($products as $index => $product)
        {
            $programID = $product->program;
            /* The product associated with the program. */
            if(!empty($programID))
            {
                $program = $this->program->getByID($programID);
                if(!empty($program) && in_array($browseType, array('all', 'unclosed', 'wait', 'doing', 'suspended', 'closed')))
                {
                    if($browseType == 'unclosed' && $program->status == 'closed')
                        unset($products[$index]);
                    elseif($browseType != 'unclosed' && $browseType != 'all' && $program->status != $browseType)
                        unset($products[$index]);
                }
            }
            else
            {
                /* The product without program only can be viewed when browse type is all and not closed. */
                if($browseType != 'all' and $browseType != 'unclosed') unset($products[$index]);
            }
        }
        return $products;
    }
}
