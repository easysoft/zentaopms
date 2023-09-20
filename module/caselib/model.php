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
     * 设置用例库菜单。
     * Set library menu.
     *
     * @param  array  $libraries
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function setLibMenu(array $libraries, int $libID): void
    {
        /* Set case lib menu. */
        $products = $this->loadModel('product')->getPairs();
        if(!empty($products) and $this->session->product) $this->loadModel('qa')->setMenu($this->session->product);
        if(empty($products)) $this->loadModel('qa')->setMenu();

        $this->lang->qa->menu->caselib['subModule'] .= ',testcase';

        if($libraries)
        {
            $libName = '';
            if(!isset($libraries[$libID]))
            {
                $lib     = $this->getByID($libID);
                $libName = $lib ? $lib->name : '';
            }
            $currentLibName = zget($libraries, $libID, $libName);
            setCookie("lastCaseLib", (string)$libID, $this->config->cookieLife, $this->config->webRoot, '', false, true);

            $dropMenuLink = helper::createLink('caselib', 'ajaxGetDropMenu', "objectID={$libID}&module=caselib&method=browse");

            $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentLibName}'><span class='text'>{$currentLibName}</span> <span class='caret' style='margin-top: 3px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
            $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
            $output .= "</div></div>";

            $this->lang->switcherMenu = $output;
        }
    }

    /**
     * 通过 id 获取用例库信息。
     * Get caselib info by id.
     *
     * @param  int         $libID
     * @param  bool        $setImgSize
     * @access public
     * @return object|false
     */
    public function getByID(int $libID, bool $setImgSize = false): object|false
    {
        $lib = $this->dao->select('*')->from(TABLE_TESTSUITE)->where('id')->eq($libID)->fetch();
        if(!$lib) return false;

        $lib = $this->loadModel('file')->replaceImgURL($lib, 'desc');
        if($setImgSize) $lib->desc = $this->file->setImgSize($lib->desc);
        return $lib;
    }

    /**
     * 更新用例库。
     * Update a caselib.
     *
     * @param  object $lib
     * @access public
     * @return bool
     */
    public function update(object $lib): bool
    {
        $oldLib = $this->dao->select('*')->from(TABLE_TESTSUITE)->where('id')->eq($lib->id)->fetch();

        $this->dao->update(TABLE_TESTSUITE)->data($lib, $skip = 'uid')
            ->autoCheck()
            ->batchcheck($this->config->caselib->edit->requiredFields, 'notempty')
            ->checkFlow()
            ->where('id')->eq($lib->id)
            ->checkFlow()
            ->exec();
        if(dao::isError()) return false;

        $this->loadModel('file')->updateObjectID($lib->uid, $lib->id, 'caselib');

        $changes = common::createChanges($oldLib, $lib);
        if(!$changes) return true;

        $actionID = $this->loadModel('action')->create('caselib', $lib->id, 'edited');
        $this->action->logHistory($actionID, $changes);

        return true;
    }

    /**
     * 删除用例库。
     * Delete library.
     *
     * @param  int    $libID
     * @param  string $table
     * @access public
     * @return bool
     */
    public function delete($libID, $table = ''): bool
    {
        $this->dao->update(TABLE_TESTSUITE)->set('deleted')->eq('1')->where('id')->eq($libID)->exec();

        $this->loadModel('action')->create('caselib', $libID, 'deleted', '', actionModel::CAN_UNDELETED);

        return !dao::isError();
    }

    /**
     * 获取用例库键对。
     * Get libraries.
     *
     * @access public
     * @return array
     */
    public function getLibraries(): array
    {
        return $this->dao->select('id, name')->from(TABLE_TESTSUITE)->where('product')->eq(0)->andWhere('deleted')->eq(0)->andWhere('type')->eq('library')->orderBy('order_desc, id_desc')->fetchPairs();
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
     * 创建用例库，插入一个用例库对象到数据库。
     * Create a lib case, insert a lib object into database.
     *
     * @param  object $lib
     * @access public
     * @return int|false
     */
    public function create(object $lib): int|false
    {
        $this->lang->testsuite->name = $this->lang->caselib->name;
        $this->lang->testsuite->desc = $this->lang->caselib->desc;

        $this->dao->insert(TABLE_TESTSUITE)->data($lib)
            ->batchcheck($this->config->caselib->create->requiredFields, 'notempty')
            ->check('name', 'unique', "deleted = '0'")
            ->checkFlow()
            ->exec();

        if(dao::isError()) return false;

        $libID = $this->dao->lastInsertID();
        $this->loadModel('file')->updateObjectID($this->post->uid, $libID, 'caselib');
        $this->loadModel('action')->create('caselib', $libID, 'opened');
        return $libID;
    }

    /**
     * 获取用例库的用例。
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
    public function getLibCases(int $libID, string $browseType, int $queryID = 0, int $moduleID = 0, string $sort = 'id_desc', object $pager = null): array
    {
        $browseType = $browseType == 'bymodule' && $this->session->libBrowseType && $this->session->libBrowseType != 'bysearch' ? $this->session->libBrowseType : $browseType;

        if(!in_array($browseType, array('bymodule', 'all', 'wait', 'review', 'bysearch'))) return array();

        $moduleIdList = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';
        $stmt         = $this->dao->select('*')->from(TABLE_CASE)
            ->where('lib')->eq($libID)
            ->andWhere('product')->eq(0)
            ->andWhere('deleted')->eq('0');
        if($browseType == 'bysearch')
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

            $stmt = $stmt->andWhere($caseQuery)
                ->beginIF($queryLibID != 'all')->andWhere('lib')->eq($libID)->fi()
                ->beginIF($this->app->tab != 'qa')->andWhere('project')->eq($this->session->project)->fi();
        }
        else
        {
            $stmt = $stmt->beginIF($moduleIdList)->andWhere('module')->in($moduleIdList)->fi()
                ->beginIF($browseType == 'wait')->andWhere('status')->eq($browseType)->fi()
                ->beginIF($browseType == 'review')->andWhere("FIND_IN_SET('{$this->app->user->account}', `reviewers`)")->fi();
        }
        return $stmt->orderBy($sort)->page($pager)->fetchAll('id');
    }

    /**
     * 获取用例库 1.5 级下拉的链接。
     * Get lib link.
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return string
     */
    public function getLibLink(string $module, string $method): string
    {
        if($module == 'tree')
        {
            $link = helper::createLink($module, $method, "libID=%s&type=caselib&currentModuleID=0");
        }
        elseif($module == 'caselib' && $method != 'create')
        {
            $link = helper::createLink($module, $method, "libID=%s");
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

        if($this->post->isEndPage)
        {
            if($this->session->fileImport) @unlink($this->session->fileImport);
            unset($_SESSION['fileImport']);
        }
    }
}
