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

        $this->view->title = $this->lang->caselib->common . $this->lang->hyphen . $this->lang->caselib->create;
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
            $formData = form::data($this->config->caselib->form->edit, $libID);
            $lib      = $this->caselibZen->prepareEditExtras($formData, $libID);

            $this->caselib->update($lib);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($libID);
            if(!$message) $message = $this->lang->saveSuccess;

            $link = inlink('browse', "libID={$libID}");
            return $this->sendSuccess(array('message' => $message, 'closeModal' => true, 'callback' => "loadPage(\"$link\", '#heading #dropmenu, #main');"));
        }

        /* Set lib menu. */
        $libraries = $this->caselib->getLibraries();
        $libID     = $this->caselibZen->saveLibState($libID, $libraries);
        $this->caselib->setLibMenu($libraries, $libID);

        $this->view->title = $libraries[$libID] . $this->lang->hyphen . $this->lang->caselib->edit;
        $this->view->lib   = $this->caselib->getByID($libID);
        $this->display();
    }

    /**
     * 编辑用例。
     * Edit a case.
     *
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function editCase(int $caseID)
    {
        echo $this->fetch('testcase', 'edit', "caseID=$caseID");
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
    public function browse(int $libID = 0, string $browseType = 'all', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $from = 'qa', int $blockID = 0)
    {
        $libraries = $this->caselib->getLibraries();
        if(empty($libraries))
        {
            if($from == 'doc')
            {
                $this->app->loadLang('doc');
                return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->tips->noCaselib));
            }
            $this->locate(inlink('create'));
        }

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
        $actionURL = $this->createLink('caselib', 'browse', "libID={$libID}&browseType=bySearch&queryID=myQueryID&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}&from={$from}&blockID={$blockID}");
        $this->caselibZen->buildSearchForm($libID, $libraries, $queryID, $actionURL);

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Save query session .*/
        $sort  = common::appendOrder($orderBy);
        $cases = $this->caselib->getLibCases($libID, $browseType, (int)$queryID, $moduleID, $sort, $pager, $from);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', true);

        $this->loadModel('testcase');

        $this->view->title         = $this->lang->caselib->common . $this->lang->hyphen . $libraries[$libID];
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

        $this->view->from    = $from;
        $this->view->blockID = $blockID;
        $this->view->idList  = '';
        if($from == 'doc')
        {
            $docBlock = $this->loadModel('doc')->getDocBlock($blockID);
            $this->view->docBlock = $docBlock;
            if($docBlock)
            {
                $content = json_decode($docBlock->content, true);
                if(isset($content['idList'])) $this->view->idList = $content['idList'];
            }
        }

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

            $case = form::data($this->config->testcase->form->create)->add('lib', $_POST['lib'] ? $this->post->lib : $libID)
                ->setIF(($this->config->testcase->needReview && strpos($this->config->testcase->forceNotReview, $this->app->user->account) === false) || (!empty($this->config->testcase->forceReview) && strpos($this->config->testcase->forceReview, $this->app->user->account) !== false), 'status', 'wait')
                ->get();

            $steps   = $case->steps;
            $expects = $case->expects;
            foreach($expects as $key => $value)
            {
                if(!empty($value) and empty($steps[$key])) dao::$errors['message']["steps$key"] = sprintf($this->lang->testcase->stepsEmpty, $key);
            }
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->testcase->create($case);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* If link from no head then reload. */
            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));
            $params = $libID == $case->lib ? "libID={$libID}&browseType=byModule&param={$_POST['module']}" : "libID={$libID}";
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('caselib', 'browse', $params)));
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
        $this->view->title            = $libraries[$libID] . $this->lang->hyphen . $this->lang->testcase->create;
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

        $this->view->title            = $libraries[$libID] . $this->lang->hyphen . $this->lang->testcase->batchCreate;
        $this->view->libID            = $libID;
        $this->view->moduleOptionMenu = $this->loadModel('tree')->getOptionMenu($libID, 'caselib', 0);
        $this->view->currentModuleID  = $moduleID;
        $this->display();
    }

    /**
     * 批量编辑用例。
     * Batch edit case.
     *
     * @param  int        $libID
     * @param  string|int $branch
     * @param  string     $type
     * @access public
     * @return void
     */
    public function batchEditCase(int $libID, string|int $branch = '', string $type = '')
    {
        $this->config->testcase->edit->requiredFields = str_replace(array('story', 'scene'), '', $this->config->testcase->edit->requiredFields);
        $this->config->testcase->list->customBatchEditFields = 'module,stage,precondition,status,pri,keywords';
        echo $this->fetch('testcase', 'batchEdit', "libID=$libID&branch=$branch&type=$type");
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
        $this->view->title   = $lib->name . $this->lang->hyphen . $this->lang->caselib->view;
        $this->view->lib     = $lib;
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions = $this->loadModel('action')->getList('caselib', $libID);
        $this->display();
    }

    /**
     * 查看用例。
     * View a case.
     *
     * @param  int    $caseID
     * @param  int    $version
     * @access public
     * @return void
     */
    public function viewCase(int $caseID, int $version = 0, string $from = 'testcase', int $taskID = 0, $stepsType = '')
    {
        echo $this->fetch('testcase', 'view', "caseID=$caseID&version=$version&from=$from&taskID=$taskID&stepsType=$stepsType");
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

            $modules = $this->loadModel('tree')->getOptionMenu($libID, 'caselib', 0);
            $fields  = $this->caselibZen->getFieldsForExportTemplate();
            $rows    = $this->caselibZen->getRowsForExportTemplate($this->post->num ? (int)$this->post->num : 0, $modules);

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

        if($_POST)
        {
            $file = $this->loadModel('file')->getUpload('file');
            if(empty($_FILES))  return $this->send(array('result' => 'fail', 'message' => $this->lang->file->errorFileFormat));
            if(empty($file[0])) return $this->send(array('result' => 'fail', 'message' => $this->lang->testcase->errorFileNotEmpty));

            $file = $file[0];
            if(!$file || (isset($file['extension']) && $file['extension'] != 'csv')) return $this->send(array('result' => 'fail', 'message' => $this->lang->file->errorFileFormat));

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
            return $this->send(array('result' => 'success', 'load' => inlink('showImport', "libID={$libID}"), 'closeModal' => true));
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

        list($caseData, $stepVars) = $this->caselibZen->getDataForImport($maxImport, $tmpFile, $fields);

        if(empty($maxImport) || !file_exists($tmpFile)) $pageID = 1;
        $this->caselibZen->responseAfterShowImport($libID, $caseData, $maxImport, $pageID, $stepVars);

        $totalPages  = 1;
        $totalAmount = count($caseData);
        if($totalAmount > $this->config->file->maxImport && $maxImport)
        {
            $totalPages = ceil($totalAmount / $maxImport);
            $caseData   = array_slice($caseData, ($pageID - 1) * $maxImport, $maxImport, true);
        }

        $this->view->title       = $this->lang->caselib->common . $this->lang->hyphen . $this->lang->testcase->showImport;
        $this->view->cases       = $this->dao->select('id,module,stage,status,pri,type')->from(TABLE_CASE)->where('lib')->eq($libID)->andWhere('deleted')->eq(0)->andWhere('product')->eq(0)->fetchAll('id');
        $this->view->modules     = $this->loadModel('tree')->getOptionMenu($libID, $viewType = 'caselib', $startModuleID = 0);
        $this->view->caseData    = $caseData;
        $this->view->libID       = $libID;
        $this->view->isEndPage   = $pageID >= $totalPages;
        $this->view->totalAmount = $totalAmount;
        $this->view->totalPages  = $totalPages;
        $this->view->pageID      = $pageID;
        $this->view->maxImport   = $maxImport;
        $this->view->dataInsert  = $insert;
        $this->display();
    }

    /**
     * 导出用例。
     * Export case.
     *
     * @param  int    $libID
     * @param  string $orderBy
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function exportCase(int $libID, string $orderBy = 'id_desc', string $browseType = 'all')
    {
        $lib = $this->caselib->getById($libID);

        if($_POST)
        {
            $fields = $this->caselibZen->getExportCasesFields();
            $cases  = $this->caselib->getCasesToExport($this->post->exportType, $orderBy, (int)$this->post->limit);
            $cases  = $this->caselibZen->processCasesForExport($cases, $libID);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $cases);
            $this->post->set('kind', 'testcase');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $fileName   = $this->lang->testcase->common;
        $browseType = isset($this->lang->caselib->featureBar['browse'][$browseType]) ? $this->lang->caselib->featureBar['browse'][$browseType] : '';

        $this->view->fileName        = $lib->name . $this->lang->dash . $browseType . $fileName;
        $this->view->allExportFields = $this->config->caselib->exportFields;
        $this->view->customExport    = true;
        $this->display();
    }
}
