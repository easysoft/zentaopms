<?php
declare(strict_types=1);
/**
 * The model file of caselib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     caselib
 * @version     $Id: model.php 5114 2013-07-12 06:02:59Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
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

        if(!isset($this->lang->qa->menu->testcase['exclude'])) $this->lang->qa->menu->testcase['exclude'] = '';
        $this->lang->qa->menu->testcase['exclude'] .= ',testcase-view';

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

        $this->lang->testsuite->name = $this->lang->caselib->name;
        $this->lang->testsuite->desc = $this->lang->caselib->desc;

        $this->dao->update(TABLE_TESTSUITE)->data($lib, 'uid')
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
     * 获取用例库列表。
     * Get library list.
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(string $type = 'all', string $orderBy = 'id_desc', object $pager = null): array
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
     * @param  object    $lib
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
        if($module == 'tree') return helper::createLink($module, $method, "libID=%s&type=caselib&currentModuleID=0");
        if($module == 'caselib' && $method != 'create') return helper::createLink($module, $method, "libID=%s");
        return helper::createLink('caselib', 'browse', "libID=%s");
    }

    /**
     * 创建导入的用例。
     * Create from import.
     *
     * @param  int    $libID
     * @access public
     * @return bool
     */
    public function createFromImport(int $libID): bool
    {
        $data = fixer::input('post')->get();

        /* 获取表单的用例数据，并且检查必填。*/
        /* Get the form data and check required. */
        $cases = $this->caselibTao->initImportedCase($data);
        if(dao::isError()) return false;

        $forceNotReview = $this->loadModel('testcase')->forceNotReview();
        $this->dao->begin();
        foreach($cases as $key => $caseData)
        {
            if(!empty($data->id[$key]) && !$this->post->insert)
            {
                $this->caselibTao->updateImportedCase($key, $caseData, $data, $forceNotReview);
            }
            else
            {
                $this->caselibTao->insertImportedCase($key, $caseData, $data, $forceNotReview);
            }
        }
        $this->dao->commit();

        if($data->isEndPage)
        {
            if($this->session->fileImport) @unlink($this->session->fileImport);
            unset($_SESSION['fileImport']);
        }

        return true;
    }
}
