<?php
declare(strict_types=1);
/**
 * The control file of caselib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     caselib
 * @version     $Id: control.php 5114 2013-07-12 06:02:59Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class caselib extends control
{
    /**
     * 主页，跳转到浏览页面。
     * Index page, header to browse.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate($this->createLink('caselib', 'browse'));
    }

    /**
     * 创建一个用例库。
     * Create a lib.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if(!empty($_POST))
        {
            /* Set case lib. */
            $data = form::data($this->config->caselib->form->create);
            $lib  = $data->setForce('type', 'library')
                ->add('addedBy', $this->app->user->account)
                ->add('addedDate', helper::now())
                ->setIF($this->lang->navGroup->caselib != 'qa', 'project', (int)$this->session->project)
                ->stripTags($this->config->caselib->editor->create['id'], $this->config->allowedTags)
                ->get();
            $lib = $this->loadModel('file')->processImgURL($lib, $this->config->caselib->editor->create['id'], $this->post->uid);

            /* Insert case lib. */
            $libID = $this->caselib->create($lib);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->viewType == 'json') return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $libID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->createLink('caselib', 'browse', "libID=$libID")));
        }

        $libraries = $this->caselib->getLibraries();
        $libID     = $this->caselibZen->saveLibState(0, $libraries);
        $this->caselib->setLibMenu($libraries, $libID);

        $this->view->title = $this->lang->caselib->common . $this->lang->colon . $this->lang->caselib->create;
        $this->display();
    }

    /**
     * 编辑用例库。
     * Edit a case lib.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function edit(int $libID)
    {
        if(!empty($_POST))
        {
            $formData = form::data($this->config->caselib->form->edit);
            $lib      = $this->caselibZen->prepareEditExtras($formData, $libID);

            $this->caselib->update($lib);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($libID);
            if(!$message) $message = $this->lang->saveSuccess;

            $link = inlink('view', "libID={$libID}");
            return $this->sendSuccess(array('message' => $message, 'callback' => "loadModal(\"$link\", 'viewLibModal');"));
        }

        /* Set lib menu. */
        $libraries = $this->caselib->getLibraries();
        $libID     = $this->caselibZen->saveLibState($libID, $libraries);
        $this->caselib->setLibMenu($libraries, $libID);

        $this->view->title = $libraries[$libID] . $this->lang->colon . $this->lang->caselib->edit;
        $this->view->lib   = $this->caselib->getByID($libID);
        $this->display();
    }

    /**
     * 删除一个用例库。
     * Delete a case lib.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function delete(int $libID)
    {
        $this->caselib->delete($libID);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $message = $this->executeHooks($libID);
        if(!$message) $message = $this->lang->saveSuccess;

        return $this->send(array('result' => 'success', 'message' => $message, 'load' => $this->createLink('caselib', 'browse'), 'closeModal' => true));
    }

    /**
     * 展示用例库的用例。
     * Show library case.
     *
     * @param  int    $libID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(int $libID = 0, string $browseType = 'all', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $libraries = $this->caselib->getLibraries();
        if(empty($libraries)) $this->locate(inlink('create'));

        /* Set browse type. */
        $browseType = strtolower($browseType);
        $libID = $this->caselibZen->saveLibState($libID, $libraries);

        /* Save session and cookie. */
        $this->caselibZen->setBrowseSessionAndCookie($libID, $browseType, $param);

        /* Set lib menu. */
        $this->caselib->setLibMenu($libraries, $libID);

        /* Set module and query id. */
        $moduleID = ($browseType == 'bymodule') ? $param : ($browseType == 'bysearch' ? 0 : ($this->cookie->libCaseModule ? $this->cookie->libCaseModule : 0));
        $queryID  = ($browseType == 'bysearch') ? $param : 0;

        /* Build the search form. */
        $actionURL = $this->createLink('caselib', 'browse', "libID={$libID}&browseType=bySearch&queryID=myQueryID");
        $this->caselibZen->buildSearchForm($libID, $libraries, $queryID, $actionURL);

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Save query session .*/
        $sort  = common::appendOrder($orderBy);
        $cases = $this->caselib->getLibCases($libID, $browseType, $queryID, $moduleID, $sort, $pager);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', true);

        $this->loadModel('testcase');

        $this->view->title         = $this->lang->caselib->common . $this->lang->colon . $libraries[$libID];
        $this->view->libID         = $libID;
        $this->view->libName       = $libraries[$libID];
        $this->view->cases         = $cases;
        $this->view->orderBy       = $orderBy;
        $this->view->users         = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->modules       = $this->loadModel('tree')->getOptionMenu($libID, 'caselib', 0);
        $this->view->moduleTree    = $this->tree->getTreeMenu($libID, 'caselib', 0, array('treeModel', 'createCaseLibLink'));
        $this->view->modulePairs   = !empty($this->config->caselib->browse->showModule) ? $this->tree->getModulePairs($libID, 'caselib', $this->config->caselib->browse->showModule) : array();
        $this->view->pager         = $pager;
        $this->view->browseType    = $browseType;
        $this->view->moduleID      = $moduleID;
        $this->view->moduleName    = $moduleID ? $this->tree->getById($moduleID)->name : $this->lang->tree->all;
        $this->view->param         = $param;
        $this->view->setModule     = true;
        $this->view->showBranch    = false;
        $this->display();
    }

    /**
     * 创建用例库的测试用例。
     * Create case for library.
     *
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  int    $param
     * @access public
     * @return void
     */
    public function createCase(int $libID, int $moduleID = 0, int $param = 0)
    {
        if(!empty($_POST))
        {
            $this->loadModel('testcase');
            helper::setcookie('lastLibCaseModule', (int)$this->post->module, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, false);

            $case = form::data($this->config->testcase->form->create)->add('lib', $libID)->get();

            $steps   = $case->steps;
            $expects = $case->expects;
            foreach($expects as $key => $value)
            {
                if(!empty($value) and empty($steps[$key])) dao::$errors['message']["steps$key"] = sprintf($this->lang->testcase->stepsEmpty, $key);
            }
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $result = $this->loadModel('common')->removeDuplicate('case', $case, "id!='$param'");
            if($result and $result['stop']) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->duplicate, $this->lang->testcase->common), 'locate' => $this->createLink('testcase', 'view', "caseID={$result['duplicate']}")));

            $this->testcase->create($case);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* If link from no head then reload. */
            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('caselib', 'browse', "libID={$libID}&browseType=byModule&param={$_POST['module']}")));
        }
        /* Set lib menu. */
        $libraries = $this->caselib->getLibraries();
        $libID     = $this->caselibZen->saveLibState($libID, $libraries);
        $this->caselib->setLibMenu($libraries, $libID);

        /* Assign case params. */
        $this->caselibZen->assignCaseParamsForCreateCase($param);

        foreach(explode(',', $this->config->caselib->customCreateFields) as $field) $customFields[$field] = $this->lang->testcase->$field;

        /* Show the variables associated. */
        $this->app->loadLang('testcase');
        $this->view->title            = $libraries[$libID] . $this->lang->colon . $this->lang->testcase->create;
        $this->view->showFields       = $this->config->caselib->custom->createFields;
        $this->view->customFields     = $customFields;
        $this->view->libraries        = $libraries;
        $this->view->libID            = $libID;
        $this->view->currentModuleID  = $moduleID ? $moduleID : $this->cookie->lastLibCaseModule;
        $this->view->moduleOptionMenu = $this->loadModel('tree')->getOptionMenu($libID, 'caselib', 0);
        $this->display();
    }

    /**
     * 批量创建用例。
     * Batch create case.
     *
     * @param  int    $libID
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function batchCreateCase(int $libID, int $moduleID = 0)
    {
        $this->loadModel('testcase');
        if(!empty($_POST))
        {
            $cases = $this->caselibZen->prepareCasesForBathcCreate($libID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            foreach($cases as $case) $this->testcase->create($case);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('caselib', 'browse', "libID={$libID}&browseType=byModule&param={$moduleID}"), 'closeModal' => true));
        }

        $libraries = $this->caselib->getLibraries();
        if(empty($libraries)) $this->locate(inlink('create'));

        /* Set lib menu. */
        $this->caselib->setLibMenu($libraries, $libID);

        $this->view->title            = $libraries[$libID] . $this->lang->colon . $this->lang->testcase->batchCreate;
        $this->view->libID            = $libID;
        $this->view->moduleOptionMenu = $this->loadModel('tree')->getOptionMenu($libID, 'caselib', 0);
        $this->view->currentModuleID  = $moduleID;
        $this->display();
    }

    /**
     * 查看用例库信息。
     * View a library.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function view(int $libID)
    {
        $lib = $this->caselib->getByID($libID, true);
        if(!$lib) return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('qa', 'index'))));

        /* Set lib menu. */
        $libraries = $this->caselib->getLibraries();
        $this->caselib->setLibMenu($libraries, $libID);

        $this->app->loadLang('testcase');
        $this->view->title   = $lib->name . $this->lang->colon . $this->lang->caselib->view;
        $this->view->lib     = $lib;
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions = $this->loadModel('action')->getList('caselib', $libID);
        $this->display();
    }

    /**
     * 通过 AJAX 获取用例库的 1.5 级菜单。
     * Ajax get drop menu.
     *
     * @param  int    $libID
     * @param  string $module
     * @param  string $method
     * @param  string $extra
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu(int $libID, string $module, string $method, string $extra = '')
    {
        $libraries = $this->caselib->getLibraries();
        $this->view->link            = $this->caselib->getLibLink($module, $method);
        $this->view->libID           = $libID;
        $this->view->libraries       = $libraries;
        $this->view->librariesPinyin = common::convert2Pinyin($libraries);
        $this->display();
    }

    /**
     * Export template.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function exportTemplate(int $libID)
    {
        if($_POST)
        {
            $this->loadModel('testcase');

            $modules = $this->loadModel('tree')->getOptionMenu($libID, $viewType = 'caselib', $startModuleID = 0);
            $fields  = $this->caselibZen->getFieldsForExportTemplate();
            $rows    = $this->caselibZen->getRowsForExportTemplate($this->post->num ? $this->post->num : 0, $modules);

            $this->post->set('fields', $fields);
            $this->post->set('kind', 'testcase');
            $this->post->set('rows', $rows);
            $this->post->set('extraNum', $num);
            $this->post->set('fileName', 'template');
            $this->fetch('file', 'export2csv', $_POST);
        }

        $this->display();
    }

    /**
     * 导入用例。
     * Import case by csv file.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function import(int $libID)
    {
        $this->loadModel('testcase');

        if($_FILES)
        {
            $file = $this->loadModel('file')->getUpload('file');
            $file = $file[0];

            $fileName = $this->file->savePath . $this->file->getSaveName($file['pathname']);
            move_uploaded_file($file['tmpname'], $fileName);

            $fields = $this->testcase->getImportFields();
            $fields = array_flip($fields);

            list($header, $columns) = $this->caselibZen->getImportHeaderAndColumns($fileName, $fields);

            if(count($columns) <= 3 || $this->post->encode != 'utf-8')
            {
                $encode = $this->post->encode != 'utf-8' ? $this->post->encode : 'gbk';
                $fc     = file_get_contents($fileName);
                $fc     = helper::convertEncoding($fc, $encode, 'utf-8');
                file_put_contents($fileName, $fc);

                list($header, $columns) = $this->caselibZen->getImportHeaderAndColumns($fileName, $fields);

                if(count($columns) == 0) return $this->send(array('result' => 'fail', 'message' => $this->lang->testcase->errorEncode));
            }

            $this->session->set('fileImport', $fileName);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('showImport', "libID={$libID}"), 'closeModal' => true));
        }

        $this->display();
    }

    /**
     * 展示导入的用例记录。
     * Show import case.
     *
     * @param  int        $libID
     * @param  int        $pageID
     * @param  int        $maxImport
     * @param  string|int $insert  0 is covered old, 1 is insert new.
     * @access public
     * @return void
     */
    public function showImport(int $libID, int $pageID = 1, int $maxImport = 0, string|int $insert = '')
    {
        $this->loadModel('testcase');

        $file    = $this->session->fileImport;
        $tmpPath = $this->loadModel('file')->getPathOfImportedFile();
        $tmpFile = $tmpPath . DS . md5(basename($file));

        if($_POST)
        {
            $this->caselib->createFromImport($libID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!$this->post->isEndPage) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('showImport', "libID=$libID&pageID=" . ((int)$this->post->pageID + 1) . "&maxImport=$maxImport&insert=" . zget($_POST, 'insert', ''))));

            @unlink($tmpFile);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('browse', "libID=$libID")));
        }

        $libraries = $this->caselib->getLibraries();
        if(empty($libraries)) $this->locate(inlink('create'));

        $this->caselib->setLibMenu($libraries, $libID);

        $fields = $this->caselibZen->getFieldsForImport();

        list($caseData, $stepData, $stepVars) = $this->caselibZen->getDataForImport($maxImport, $tmpFile, $fields);

        if(empty($maxImport) || !file_exists($tmpFile)) $pageID = 1;
        $this->caselibZen->responseAfterShowImport($libID, $caseData, $maxImport, $pageID, $stepVars);

        $totalPages  = 1;
        $totalAmount = count($caseData);
        if($totalAmount > $this->config->file->maxImport && $maxImport)
        {
            $totalPages = ceil($totalAmount / $maxImport);
            $caseData   = array_slice($caseData, ($pageID - 1) * $maxImport, $maxImport, true);
        }

        $this->view->title       = $this->lang->caselib->common . $this->lang->colon . $this->lang->testcase->showImport;
        $this->view->cases       = $this->dao->select('id,module,stage,status,pri,type')->from(TABLE_CASE)->where('lib')->eq($libID)->andWhere('deleted')->eq(0)->andWhere('product')->eq(0)->fetchAll('id');
        $this->view->modules     = $this->loadModel('tree')->getOptionMenu($libID, $viewType = 'caselib', $startModuleID = 0);
        $this->view->caseData    = $caseData;
        $this->view->stepData    = $stepData;
        $this->view->libID       = $libID;
        $this->view->isEndPage   = $pageID >= $totalPages;
        $this->view->totalAmount = $totalAmount;
        $this->view->totalPages  = $totalPages;
        $this->view->pageID      = $pageID;
        $this->view->maxImport   = $maxImport;
        $this->view->dataInsert  = $insert;
        $this->display();
    }
}
