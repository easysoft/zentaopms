<?php
/**
 * The model file of caselib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     caselib
 * @version     $Id: model.php 5114 2013-07-12 06:02:59Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class caselibModel extends model
{
    /**
     * Set library menu.
     *
     * @param  array  $libraries
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function setLibMenu($libraries, $libID)
    {
        /* Set case lib menu. */
        $products = $this->loadModel('product')->getPairs();
        if(!empty($products) and $this->session->product) $this->loadModel('qa')->setMenu($products, $this->session->product);
        if(empty($products)) $this->loadModel('qa')->setMenu(array(0 => ''), 0);

        $this->lang->qa->menu->caselib['subModule'] .= ',testcase';

        if($libraries)
        {
            $libName = '';
            if(!isset($libraries[$libID])) $libName = $this->dao->select('name')->from(TABLE_TESTSUITE)->where('id')->eq($libID)->fetch('name');
            $currentLibName = zget($libraries, $libID, $libName);
            setCookie("lastCaseLib", $libID, $this->config->cookieLife, $this->config->webRoot, '', false, true);

            $dropMenuLink = helper::createLink('caselib', 'ajaxGetDropMenu', "objectID=$libID&module=caselib&method=browse");

            $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentLibName}'><span class='text'>{$currentLibName}</span> <span class='caret' style='margin-top: 3px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
            $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
            $output .= "</div></div>";

            $this->lang->switcherMenu = $output;
        }
    }

    /**
     * Save lib state.
     *
     * @param  int    $libID
     * @param  array  $libraries
     * @access public
     * @return int
     */
    public function saveLibState($libID = 0, $libraries = array())
    {
        if($libID > 0) $this->session->set('caseLib', (int)$libID);
        if($libID == 0 and $this->cookie->lastCaseLib) $this->session->set('caseLib', $this->cookie->lastCaseLib);
        if($libID == 0 and $this->session->caseLib == '') $this->session->set('caseLib', key($libraries));
        if(!isset($libraries[$this->session->caseLib]))
        {
            $this->session->set('caseLib', key($libraries));
            $libID = $this->session->caseLib;
        }
        return $this->session->caseLib;
    }

    /**
     * Get caselib info by id.
     *
     * @param  int   $libID
     * @param  bool  $setImgSize
     * @access public
     * @return object|bool
     */
    public function getById($libID, $setImgSize = false)
    {
        $lib = $this->dao->select('*')->from(TABLE_TESTSUITE)->where('id')->eq((int)$libID)->fetch();
        if(!$lib) return false;

        $lib = $this->loadModel('file')->replaceImgURL($lib, 'desc');
        if($setImgSize) $lib->desc = $this->file->setImgSize($lib->desc);
        return $lib;
    }

    /**
     * Update a caselib.
     *
     * @param  int   $libID
     * @access public
     * @return bool|array
     */
    public function update($libID)
    {
        $oldLib = $this->dao->select("*")->from(TABLE_TESTSUITE)->where('id')->eq((int)$libID)->fetch();
        $lib    = fixer::input('post')
            ->stripTags($this->config->caselib->editor->edit['id'], $this->config->allowedTags)
            ->add('id', $libID)
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', helper::now())
            ->remove('uid')
            ->get();
        $lib = $this->loadModel('file')->processImgURL($lib, $this->config->caselib->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_TESTSUITE)->data($lib)
            ->autoCheck()
            ->batchcheck($this->config->caselib->edit->requiredFields, 'notempty')
            ->checkFlow()
            ->where('id')->eq($libID)
            ->checkFlow()
            ->exec();
        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $libID, 'caselib');
            return common::createChanges($oldLib, $lib);
        }
        return false;
    }

    /**
     * Delete library.
     *
     * @param  int    $libID
     * @param  string $table
     * @access public
     * @return bool
     */
    public function delete($libID, $table = '')
    {
        $this->dao->update(TABLE_TESTSUITE)->set('deleted')->eq(1)->where('id')->eq($libID)->exec();

        $this->loadModel('action');
        $this->action->create('caselib', $libID, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);
    }

    /**
     * Get libraries.
     *
     * @access public
     * @return array
     */
    public function getLibraries()
    {
        return $this->dao->select("id,name")->from(TABLE_TESTSUITE)
            ->where('product')->eq(0)
            ->andWhere('deleted')->eq(0)
            ->andWhere('type')->eq('library')
            ->orderBy('order_desc, id_desc')
            ->fetchPairs('id', 'name');
    }

    /**
     * Get library list.
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($type = 'all', $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_TESTSUITE)
            ->where('product')->eq(0)
            ->andWhere('deleted')->eq(0)
            ->andWhere('type')->eq('library')
            ->beginIF($type == 'review')->andWhere("FIND_IN_SET('{$this->app->user->account}', `reviewers`)")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Create lib.
     *
     * @access public
     * @return int
     */
    public function create()
    {
        $lib = fixer::input('post')
            ->stripTags($this->config->caselib->editor->create['id'], $this->config->allowedTags)
            ->setForce('type', 'library')
            ->setIF($this->lang->navGroup->caselib != 'qa', 'project', (int)$this->session->project)
            ->add('addedBy', $this->app->user->account)
            ->add('addedDate', helper::now())
            ->remove('uid')
            ->get();
        $lib = $this->loadModel('file')->processImgURL($lib, $this->config->caselib->editor->create['id'], $this->post->uid);

        $this->lang->testsuite->name = $this->lang->caselib->name;
        $this->lang->testsuite->desc = $this->lang->caselib->desc;
        $this->dao->insert(TABLE_TESTSUITE)->data($lib)
            ->batchcheck($this->config->caselib->create->requiredFields, 'notempty')
            ->check('name', 'unique', "deleted = '0'")
            ->checkFlow()
            ->exec();
        if(!dao::isError())
        {
            $libID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $libID, 'caselib');
            return $libID;
        }
        return false;
    }

    /**
     * Get lib cases.
     *
     * @param  int    $libID
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $moduleID
     * @param  string $sort
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLibCases($libID, $browseType, $queryID = 0, $moduleID = 0, $sort = 'id_desc', $pager = null)
    {
        $moduleIdList = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';
        $browseType   = ($browseType == 'bymodule' and $this->session->libBrowseType and $this->session->libBrowseType != 'bysearch') ? $this->session->libBrowseType : $browseType;

        $cases = array();
        if($browseType == 'bymodule' or $browseType == 'all' or $browseType == 'wait' or $browseType == 'review')
        {
            $cases = $this->dao->select('*')->from(TABLE_CASE)
                ->where('lib')->eq((int)$libID)
                ->andWhere('product')->eq(0)
                ->beginIF($moduleIdList)->andWhere('module')->in($moduleIdList)->fi()
                ->beginIF($browseType == 'wait')->andWhere('status')->eq($browseType)->fi()
                ->beginIF($browseType == 'review')->andWhere("FIND_IN_SET('{$this->app->user->account}', `reviewers`)")->fi()
                ->andWhere('deleted')->eq('0')
                ->orderBy($sort)->page($pager)->fetchAll('id');
        }
        /* By search. */
        elseif($browseType == 'bysearch')
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                $this->session->set('caselibQuery', ' 1 = 1');
                if($query)
                {
                    $this->session->set('caselibQuery', $query->sql);
                    $this->session->set('caselibForm', $query->form);
                }
            }
            else
            {
                if($this->session->caselibQuery == false) $this->session->set('caselibQuery', ' 1 = 1');
            }

            $queryLibID = $libID;
            $allLib     = "`lib` = 'all'";
            $caseQuery  = '(' . $this->session->caselibQuery;
            if(strpos($this->session->caselibQuery, $allLib) !== false)
            {
                $caseQuery = str_replace($allLib, '1', $caseQuery);
                $queryLibID = 'all';
            }
            $caseQuery .= ')';

            $cases = $this->dao->select('*')->from(TABLE_CASE)->where($caseQuery)
                ->beginIF($queryLibID != 'all')->andWhere('lib')->eq((int)$libID)->fi()
                ->beginIF($this->app->tab != 'qa')->andWhere('project')->eq($this->session->project)->fi()
                ->andWhere('product')->eq(0)
                ->andWhere('deleted')->eq(0)
                ->orderBy($sort)->page($pager)->fetchAll();
        }
        return $cases;
    }

    /**
     * Build search form.
     *
     * @param  int    $libID
     * @param  array  $libraries
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($libID, $libraries, $queryID, $actionURL)
    {
        $this->config->testcase->search['fields']['lib']              = $this->lang->testcase->lib;
        $this->config->testcase->search['params']['lib']['values']    = array('' => '', $libID => $libraries[$libID], 'all' => $this->lang->caselib->all);
        $this->config->testcase->search['params']['lib']['operator']  = '=';
        $this->config->testcase->search['params']['lib']['control']   = 'select';
        $this->config->testcase->search['params']['module']['values'] = $this->loadModel('tree')->getOptionMenu($libID, 'caselib');
        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);
        unset($this->config->testcase->search['fields']['product']);
        unset($this->config->testcase->search['params']['product']);
        unset($this->config->testcase->search['fields']['branch']);
        unset($this->config->testcase->search['params']['branch']);
        unset($this->config->testcase->search['fields']['lastRunner']);
        unset($this->config->testcase->search['params']['lastRunner']);
        unset($this->config->testcase->search['fields']['lastRunResult']);
        unset($this->config->testcase->search['params']['lastRunResult']);
        unset($this->config->testcase->search['fields']['lastRunDate']);
        unset($this->config->testcase->search['params']['lastRunDate']);

        $this->config->testcase->search['module']    = 'caselib';
        $this->config->testcase->search['actionURL'] = $actionURL;
        $this->config->testcase->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->testcase->search);
    }

    /**
     * Get lib link.
     *
     * @param  string $module
     * @param  string $method
     * @param  string $extra
     * @access public
     * @return string
     */
    public function getLibLink($module, $method, $extra)
    {
        $link = '';
        if($module == 'caselib')
        {
            if($module == 'caselib' && ($method == 'create'))
            {
                $link = helper::createLink($module, 'browse', "libID=%s");
            }
            else
            {
                $link = helper::createLink($module, $method, "libID=%s");
            }
        }
        else if($module == 'tree')
        {
            $link = helper::createLink($module, $method, "libID=%s&type=caselib&currentModuleID=0");
        }
        else
        {
            $link = helper::createLink('caselib', 'browse', "libID=%s");
        }
        return $link;
    }

    /**
     * Create from import.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function createFromImport($libID)
    {
        $this->loadModel('action');
        $this->loadModel('testcase');
        $this->loadModel('file');
        $now  = helper::now();
        $data = fixer::input('post')->get();

        if(!empty($_POST['id']))
        {
            $oldSteps = $this->dao->select('t2.*')->from(TABLE_CASE)->alias('t1')
                ->leftJoin(TABLE_CASESTEP)->alias('t2')->on('t1.id = t2.case')
                ->where('t1.id')->in(($_POST['id']))
                ->andWhere('t1.version=t2.version')
                ->orderBy('t2.id')
                ->fetchGroup('case');
            $oldCases = $this->dao->select('*')->from(TABLE_CASE)->where('id')->in($_POST['id'])->fetchAll('id');
        }

        $cases = array();
        $line  = 1;
        foreach($data->lib as $key => $lib)
        {
            $caseData = new stdclass();

            $caseData->lib          = $lib;
            $caseData->module       = $data->module[$key];
            $caseData->title        = $data->title[$key];
            $caseData->pri          = (int)$data->pri[$key];
            $caseData->type         = $data->type[$key];
            $caseData->status       = $data->status[$key];
            $caseData->stage        = join(',', $data->stage[$key]);
            $caseData->keywords     = $data->keywords[$key];
            $caseData->frequency    = 1;
            $caseData->precondition = $data->precondition[$key];

            if(isset($this->config->testcase->create->requiredFields))
            {
                $requiredFields = explode(',', $this->config->testcase->create->requiredFields);
                foreach($requiredFields as $requiredField)
                {
                    $requiredField = trim($requiredField);
                    if(!isset($caseData->$requiredField)) continue;
                    if(empty($caseData->$requiredField)) dao::$errors[] = sprintf($this->lang->testcase->noRequire, $line, $this->lang->testcase->$requiredField);
                }
            }

            $cases[$key] = $caseData;
            $line++;
        }
        if(dao::isError()) helper::end(js::error(dao::getError()));

        $forceNotReview = $this->testcase->forceNotReview();
        $this->dao->begin();
        foreach($cases as $key => $caseData)
        {
            if(!empty($_POST['id'][$key]) and empty($_POST['insert']))
            {
                $caseID      = $data->id[$key];
                $stepChanged = false;
                $oldStep     = isset($oldSteps[$caseID]) ? $oldSteps[$caseID] : array();
                $oldCase     = $oldCases[$caseID];

                /* Ignore updating cases for different libs. */
                if($oldCase->lib != $caseData->lib) continue;

                /* Remove the empty setps in post. */
                $steps = array();
                if(isset($_POST['desc'][$key]))
                {
                    foreach($data->desc[$key] as $id => $desc)
                    {
                        $desc = trim($desc);
                        if(empty($desc)) continue;
                        $step = new stdclass();
                        $step->type   = $data->stepType[$key][$id];
                        $step->desc   = htmlSpecialString($desc);
                        $step->expect = htmlSpecialString(trim($data->expect[$key][$id]));

                        $steps[] = $step;
                    }
                }

                /* If step count changed, case changed. */
                if((!$oldStep != !$steps) or (count($oldStep) != count($steps)))
                {
                    $stepChanged = true;
                }
                else
                {
                    /* Compare every step. */
                    foreach($oldStep as $id => $oldStep)
                    {
                        if(trim($oldStep->desc) != trim($steps[$id]->desc) or trim($oldStep->expect) != $steps[$id]->expect)
                        {
                            $stepChanged = true;
                            break;
                        }
                    }
                }

                $version           = $stepChanged ? $oldCase->version + 1 : $oldCase->version;
                $caseData->version = $version;
                $changes           = common::createChanges($oldCase, $caseData);
                if(!$changes and !$stepChanged) continue;

                if($changes or $stepChanged)
                {
                    $caseData->lastEditedBy   = $this->app->user->account;
                    $caseData->lastEditedDate = $now;
                    if($stepChanged and !$forceNotReview) $caseData->status = 'wait';
                    $this->dao->update(TABLE_CASE)->data($caseData)->where('id')->eq($caseID)->autoCheck()->exec();
                    if($stepChanged)
                    {
                        $parentStepID = 0;
                        foreach($steps as $id => $step)
                        {
                            $step = (array)$step;
                            if(empty($step['desc'])) continue;
                            $stepData = new stdclass();
                            $stepData->type    = ($step['type'] == 'item' and $parentStepID == 0) ? 'step' : $step['type'];
                            $stepData->parent  = ($stepData->type == 'item') ? $parentStepID : 0;
                            $stepData->case    = $caseID;
                            $stepData->version = $version;
                            $stepData->desc    = $step['desc'];
                            $stepData->expect  = $step['expect'];
                            $this->dao->insert(TABLE_CASESTEP)->data($stepData)->autoCheck()->exec();
                            if($stepData->type == 'group') $parentStepID = $this->dao->lastInsertID();
                            if($stepData->type == 'step')  $parentStepID = 0;
                        }
                    }
                    $oldCase->steps  = $this->testcase->joinStep($oldStep);
                    $caseData->steps = $this->testcase->joinStep($steps);
                    $changes  = common::createChanges($oldCase, $caseData);
                    $actionID = $this->action->create('case', $caseID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }
            else
            {
                $caseData->project    = (int)$this->session->project;
                $caseData->version    = 1;
                $caseData->openedBy   = $this->app->user->account;
                $caseData->openedDate = $now;
                $caseData->status     = $forceNotReview ? 'normal' : 'wait';
                $this->dao->insert(TABLE_CASE)->data($caseData)->autoCheck()->exec();

                if(!dao::isError())
                {
                    $caseID       = $this->dao->lastInsertID();
                    $parentStepID = 0;
                    foreach($data->desc[$key] as $id => $desc)
                    {
                        $desc = trim($desc);
                        if(empty($desc)) continue;
                        $stepData = new stdclass();
                        $stepData->type    = ($data->stepType[$key][$id] == 'item' and $parentStepID == 0) ? 'step' : $data->stepType[$key][$id];
                        $stepData->parent  = ($stepData->type == 'item') ? $parentStepID : 0;
                        $stepData->case    = $caseID;
                        $stepData->version = 1;
                        $stepData->desc    = htmlSpecialString($desc);
                        $stepData->expect  = htmlSpecialString(trim($this->post->expect[$key][$id]));
                        $this->dao->insert(TABLE_CASESTEP)->data($stepData)->autoCheck()->exec();
                        if($stepData->type == 'group') $parentStepID = $this->dao->lastInsertID();
                        if($stepData->type == 'step')  $parentStepID = 0;
                    }
                    $this->action->create('case', $caseID, 'Opened');
                }
            }
        }
        $this->dao->commit();

        if($this->post->isEndPage)
        {
            unlink($this->session->fileImport);
            unset($_SESSION['fileImport']);
        }
    }

    /**
     * Batch create case for lib.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function batchCreateCase($libID)
    {
        $this->loadModel('testcase');
        $this->loadModel('action');

        $now   = helper::now();
        $libID = (int)$libID;
        $cases = fixer::input('post')->get();

        $result = $this->loadModel('common')->removeDuplicate('case', $cases, "lib={$libID}");
        $cases  = $result['data'];

        foreach($cases->title as $i => $title)
        {
            if(!empty($cases->title[$i]) and empty($cases->type[$i])) return print(js::alert(sprintf($this->lang->error->notempty, $this->lang->testcase->type)));
        }

        $module = 0;
        $type   = '';
        $pri    = 3;
        foreach($cases->title as $i => $title)
        {
            $module = $cases->module[$i] == 'ditto' ? $module : $cases->module[$i];
            $type   = $cases->type[$i] == 'ditto'   ? $type   : $cases->type[$i];
            $pri    = $cases->pri[$i] == 'ditto'    ? $pri    : $cases->pri[$i];
            $cases->module[$i] = (int)$module;
            $cases->type[$i]   = $type;
            $cases->pri[$i]    = $pri;
        }

        $forceNotReview = $this->testcase->forceNotReview();
        foreach($cases->title as $i => $title)
        {
            if($cases->type[$i] != '' and $cases->title[$i] != '')
            {
                $data[$i] = new stdclass();
                $data[$i]->lib          = $libID;
                $data[$i]->module       = $cases->module[$i];
                $data[$i]->type         = $cases->type[$i];
                $data[$i]->pri          = $cases->pri[$i];
                $data[$i]->stage        = empty($cases->stage[$i]) ? '' : implode(',', $cases->stage[$i]);
                $data[$i]->color        = $cases->color[$i];
                $data[$i]->title        = $cases->title[$i];
                $data[$i]->precondition = $cases->precondition[$i];
                $data[$i]->keywords     = $cases->keywords[$i];
                $data[$i]->openedBy     = $this->app->user->account;
                $data[$i]->openedDate   = $now;
                $data[$i]->status       = $forceNotReview ? 'normal' : 'wait';
                $data[$i]->version      = 1;
                $data[$i]->project      = 0;
                if($this->lang->navGroup->caselib != 'qa' and $this->session->project) $data[$i]->project = $this->session->project;

                $this->dao->insert(TABLE_CASE)->data($data[$i])
                    ->autoCheck()
                    ->batchCheck($this->config->testcase->create->requiredFields, 'notempty')
                    ->exec();

                if(dao::isError())
                {
                    return helper::end(js::error(dao::getError()));
                }

                $caseID   = $this->dao->lastInsertID();
                $actionID = $this->action->create('case', $caseID, 'Opened');
            }
        }
    }

    /**
     * Build case lib menu.
     *
     * @param  object $object
     * @param  string $type
     * @access public
     * @return string
     */
    public function buildOperateMenu($object, $type = 'view')
    {
        $function = 'buildOperate' . ucfirst($type) . 'Menu';
        return $this->$function($object);
    }

    /**
     * Build case lib view menu.
     *
     * @param  object $lib
     * @access public
     * @return string
     */
    public function buildOperateViewMenu($lib)
    {
        if($lib->deleted) return '';

        $menu   = '';
        $params = "libID=$lib->id";
        $menu  .= $this->buildFlowMenu('caselib', $lib, 'view', 'direct');
        $menu  .= "<div class='divider'></div>";
        $menu  .= $this->buildMenu('caselib', 'edit', $params, $lib, 'view');
        $menu  .= $this->buildMenu('caselib', 'delete', $params, $lib, 'view', 'trash', 'hiddenwin');

        return $menu;
    }

    /**
     * Build case lib browse menu.
     *
     * @param  object $case
     * @access public
     * @return string
     */
    public function buildOperateBrowseMenu($case)
    {
        $menu   = '';
        $params = "caseID=$case->id";

        if($case->status == 'wait' and ($this->config->testcase->needReview or !empty($this->config->testcase->forceReview)))
        {
            $menu .= $this->buildMenu('testcase', 'review', $params, $case, 'browse', 'glasses', '', 'iframe');
        }
        $menu .= $this->buildMenu('testcase', 'edit', $params, $case, 'browse');
        $clickable = $this->buildMenu('testcase', 'delete', $params, $case, 'browse', '', '', '', '', '', '', false);
        if(common::hasPriv('testcase', 'delete'))
        {
            $deleteURL = helper::createLink('testcase', 'delete', "$params&confirm=yes");
            $class = 'btn';
            if(!$clickable) $class .= ' disabled';
            $menu .= html::a("javascript:ajaxDelete(\"$deleteURL\", \"caseList\", confirmDelete)", '<i class="icon icon-trash"></i>', '', "title='{$this->lang->testcase->delete}' class='{$class}'");
        }

        return $menu;
    }
}
