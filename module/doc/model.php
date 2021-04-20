<?php
/**
 * The model file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: model.php 881 2010-06-22 06:50:32Z chencongzhi520 $
 * @link        http://www.zentao.net
 */
?>
<?php
class docModel extends model
{
    /**
     * Get library by id.
     *
     * @param  int    $libID
     * @access public
     * @return object
     */
    public function getLibById($libID)
    {
        return $this->dao->findByID($libID)->from(TABLE_DOCLIB)->fetch();
    }

    /**
     * Get libraries.
     *
     * @param  string $type
     * @param  string $extra
     * @param  string $appendLibs
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getLibs($type = '', $extra = '', $appendLibs = '', $objectID = 0)
    {
        if($type == 'all')
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)
                ->where('deleted')->eq(0)
                ->orderBy('id_desc')
                ->query();
        }
        else
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)
                ->where('deleted')->eq(0)
                ->beginIF($type)->andWhere('type')->eq($type)->fi()
                ->orderBy('`order`, id desc')->query();
        }

        $products   = $this->loadModel('product')->getPairs();
        $projects   = $this->loadModel('project')->getPairsByProgram();
        $executions = $this->loadModel('execution')->getPairs();

        $libPairs = array();
        while($lib = $stmt->fetch())
        {
            if($this->checkPrivLib($lib, $extra))
            {
                if(strpos($extra, 'withObject') !== false)
                {
                    if($lib->product   != 0) $lib->name = zget($products, $lib->product, '') . '/' . $lib->name;
                    if($lib->project   != 0) $lib->name = zget($projects, $lib->project, '') . '/' . $lib->name;
                    if($lib->execution != 0) $lib->name = zget($executions, $lib->execution, '') . '/' . $lib->name;
                }

                $libPairs[$lib->id] = '/' . $lib->name;
            }
        }

        if(!empty($appendLibs))
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)->where('id')->in($appendLibs)->orderBy('`order`, id desc')->query();
            while($lib = $stmt->fetch())
            {
                if(!isset($libPairs[$lib->id]) and $this->checkPrivLib($lib, $extra)) $libPairs[$lib->id] = '/' . $lib->name;
            }
        }

        return $libPairs;
    }

    /**
     * Get grant libs by doc.
     *
     * @access public
     * @return array
     */
    public function getPrivLibsByDoc()
    {
        static $libs;
        if($libs === null)
        {
            $libs = array();
            $stmt = $this->dao->select('lib,`groups`,users')->from(TABLE_DOC)->where('acl')->ne('open')->andWhere("(`groups` != '' or users != '')")->query();

            $account    = ",{$this->app->user->account},";
            $userGroups = $this->app->user->groups;
            while($lib = $stmt->fetch())
            {
                if(strpos(",$lib->users,", $account) !== false)
                {
                    $libs[$lib->lib] = $lib->lib;
                }
                else
                {
                    foreach($userGroups as $groupID)
                    {
                        if(strpos(",$lib->groups,", ",$groupID,") !== false)
                        {
                            $libs[$lib->lib] = $lib->lib;
                            break;
                        }
                    }
                }
            }
        }
        return $libs;
    }

    /**
     * Create a library.
     *
     * @access public
     * @return void
     */
    public function createLib()
    {
        $lib = fixer::input('post')
            ->setForce('product', $this->post->type == 'product' ? $this->post->product : 0)
            ->setForce('execution', $this->post->type == 'execution' ? $this->post->execution : 0)
            ->join('groups', ',')
            ->join('users', ',')
            ->get();

        if($lib->type == 'execution' and $lib->execution)
        {
            $execution = $this->loadModel('execution')->getByID($lib->execution);
            $lib->project = $execution->project;
        }

        if($lib->acl == 'private') $lib->users = $this->app->user->account;
        $this->dao->insert(TABLE_DOCLIB)->data($lib)->autoCheck()
            ->batchCheck($this->config->doc->createlib->requiredFields, 'notempty')
            ->exec();

        return $this->dao->lastInsertID();
    }

    /**
     * Update a library.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function updateLib($libID)
    {
        $libID  = (int)$libID;
        $oldLib = $this->getLibById($libID);
        $lib = fixer::input('post')
            ->setDefault('users', '')
            ->setDefault('groups', '')
            ->join('groups', ',')
            ->join('users', ',')
            ->get();
        if($lib->acl == 'private')
        {
            $libCreatedBy = $this->dao->select('*')->from(TABLE_ACTION)->where('objectType')->eq('doclib')->andWhere('objectID')->eq($libID)->andWhere('action')->eq('created')->fetch('actor');
            $lib->users   = $libCreatedBy ? $libCreatedBy : $this->app->user->account;
        }
        $this->dao->update(TABLE_DOCLIB)->data($lib)->autoCheck()
            ->batchCheck($this->config->doc->editlib->requiredFields, 'notempty')
            ->where('id')->eq($libID)
            ->exec();
        if(!dao::isError()) return common::createChanges($oldLib, $lib);
    }

    /**
     * Get docs by browse type.
     *
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $moduleID
     * @param  string $sort
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getDocsByBrowseType($browseType, $queryID, $moduleID, $sort, $pager)
    {
        $allLibs   = array_keys($this->getLibs('all'));
        $docIdList = $this->getPrivDocs(0, $moduleID);

        $files = $this->dao->select('*')->from(TABLE_FILE)
            ->where('objectType')->eq('doc')
            ->andWhere('objectID')->in($docIdList)
            ->fetchGroup('objectID');

        if($browseType == "all")
        {
            $docs = $this->getDocs(0, 0, $sort, $pager);
        }
        elseif($browseType == "openedbyme")
        {
            $docs = $this->dao->select('*')->from(TABLE_DOC)
                ->where('deleted')->eq(0)
                ->andWhere('lib')->in($allLibs)
                ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
                ->andWhere('addedBy')->eq($this->app->user->account)
                ->orderBy($sort)
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($browseType == 'editedbyme')
        {
            $docIDList = $this->dao->select('objectID')->from(TABLE_ACTION)
                ->where('objectType')->eq('doc')
                ->andWhere('actor')->eq($this->app->user->account)
                ->andWhere('action')->eq('edited')
                ->fetchAll('objectID');
            $docs = $this->dao->select('*')->from(TABLE_DOC)
                ->where('deleted')->eq(0)
                ->andWhere('id')->in(array_keys($docIDList))
                ->andWhere('lib')->in($allLibs)
                ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
                ->orderBy($sort)
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($browseType == 'byediteddate')
        {
            $docs = $this->dao->select('*')->from(TABLE_DOC)
                ->where('deleted')->eq(0)
                ->andWhere('id')->in($docIdList)
                ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
                ->andWhere('lib')->in($allLibs)
                ->orderBy('editedDate_desc')
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($browseType == "collectedbyme")
        {
            $docs = $this->dao->select('*')->from(TABLE_DOC)
                ->where('deleted')->eq(0)
                ->andWhere('lib')->in($allLibs)
                ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
                ->andWhere('collector')->like("%,{$this->app->user->account},%")
                ->orderBy($sort)
                ->page($pager)
                ->fetchAll('id');
        }

        $docContents = $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->in(array_keys($docs))->orderBy('version,doc')->fetchAll('doc');
        foreach($docs as $index => $doc)
        {
            $docs[$index]->fileSize = 0;
            if(isset($files[$index]))
            {
                $docContent = $docContents[$index];
                $fileSize   = 0;
                foreach($files[$index] as $file)
                {
                    if(strpos(",{$docContent->files},", ",{$file->id},") === false) continue;
                    $fileSize += $file->size;
                }

                if($fileSize < 1024)
                {
                    $fileSize .= 'B';
                }
                elseif($fileSize < 1024 * 1024)
                {
                    $fileSize = round($fileSize / 1024, 2) . 'KB';
                }
                elseif($fileSize < 1024 * 1024 * 1024)
                {
                    $fileSize = round($fileSize / 1024 / 1024, 2) . 'MB';
                }
                else
                {
                    $fileSize = round($fileSize / 1024 / 1024 /1024, 2) . 'G';
                }

                $docs[$index]->fileSize = $fileSize;
            }
        }

        return $docs;
    }

    /**
     * Get docs.
     *
     * @param  int|string   $libID
     * @param  int          $module
     * @param  string       $orderBy
     * @param  object       $pager
     * @access public
     * @return void
     */
    public function getDocs($libID, $module, $orderBy, $pager = null)
    {
        $docIdList = $this->getPrivDocs($libID, $module);
        return $this->dao->select('*')->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->andWhere('id')->in($docIdList)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get priv docs.
     *
     * @param  int    $libID
     * @param  int    $module
     * @param  string $mode  normal|all
     * @access public
     * @return void
     */
    public function getPrivDocs($libIdList = array(), $module = 0, $mode = 'normal')
    {
        $stmt = $this->dao->select('*')->from(TABLE_DOC)
            ->where('1=1')
            ->beginIF($mode == 'normal')->andWhere('deleted')->eq(0)->fi()
            ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
            ->beginIF($libIdList)->andWhere('lib')->in($libIdList)->fi()
            ->beginIF(strpos($this->config->doc->custom->showLibs, 'children') === false)->andWhere('module')->in($module)->fi()
            ->beginIF(!empty($module) and strpos($this->config->doc->custom->showLibs, 'children') !== false)->andWhere('module')->in($module)->fi()
            ->query();

        $docIdList = array();
        while($doc = $stmt->fetch())
        {
            if($this->checkPrivDoc($doc)) $docIdList[$doc->id] = $doc->id;
        }
        return $docIdList;
    }

    /**
     * Get doc info by id.
     *
     * @param  int    $docID
     * @param  int    $version
     * @param  bool   $setImgSize
     * @access public
     * @return void
     */
    public function getById($docID, $version = 0, $setImgSize = false)
    {
        $doc = $this->dao->select('*')->from(TABLE_DOC)->where('id')->eq((int)$docID)->fetch();
        if(!$doc) return false;
        if(!$this->checkPrivDoc($doc))
        {
            echo(js::alert($this->lang->doc->accessDenied));
            $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
            if(strpos($this->server->http_referer, $loginLink) !== false) die(js::locate(inlink('index')));
            die(js::locate('back'));
        }
        $version = $version ? $version : $doc->version;
        $docContent = $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq($doc->id)->andWhere('version')->eq($version)->fetch();

        /* When file change then version add one. */
        $files = $this->loadModel('file')->getByObject('doc', $docID);
        $docFiles = array();
        foreach($files as $file)
        {
            $pathName = $this->file->getRealPathName($file->pathname);
            $file->webPath  = $this->file->webPath . $pathName;
            $file->realPath = $this->file->savePath . $pathName;
            if(strpos(",{$docContent->files},", ",{$file->id},") !== false) $docFiles[$file->id] = $file;
        }

        /* Check file change. */
        if($version == $doc->version and ((empty($docContent->files) and $docFiles) OR ($docContent->files and count(explode(',', trim($docContent->files, ','))) != count($docFiles))))
        {
            unset($docContent->id);
            $doc->version        += 1;
            $docContent->version  = $doc->version;
            $docContent->files    = join(',', array_keys($docFiles));
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
            $this->dao->update(TABLE_DOC)->set('version')->eq($doc->version)->where('id')->eq($doc->id)->exec();
        }

        $doc->title       = isset($docContent->title)   ? $docContent->title  : '';
        $doc->digest      = isset($docContent->digest)  ? $docContent->digest  : '';
        $doc->content     = isset($docContent->content) ? $docContent->content : '';
        $doc->contentType = isset($docContent->type)    ? $docContent->type : '';

        if($doc->type != 'url' and $doc->contentType != 'markdown') $doc  = $this->loadModel('file')->replaceImgURL($doc, 'content');
        if($setImgSize) $doc->content = $this->file->setImgSize($doc->content);
        $doc->files = $docFiles;

        $doc->productName   = '';
        $doc->executionName = '';
        $doc->moduleName    = '';
        if($doc->product)   $doc->productName   = $this->dao->findByID($doc->product)->from(TABLE_PRODUCT)->fetch('name');
        if($doc->execution) $doc->executionName = $this->dao->findByID($doc->execution)->from(TABLE_EXECUTION)->fetch('name');
        if($doc->module)    $doc->moduleName    = $this->dao->findByID($doc->module)->from(TABLE_MODULE)->fetch('name');
        return $doc;
    }

    /**
     * Create a doc.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        $now = helper::now();
        $doc = fixer::input('post')
            ->add('addedBy', $this->app->user->account)
            ->add('addedDate', $now)
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->add('version', 1)
            ->setDefault('product,execution,module', 0)
            ->stripTags($this->config->doc->editor->create['id'], $this->config->allowedTags)
            ->cleanInt('product,execution,module,lib')
            ->join('groups', ',')
            ->join('users', ',')
            ->join('mailto', ',')
            ->remove('files,labels,uid,contactListMenu')
            ->get();

        /* Fix bug #2929. strip_tags($this->post->contentMarkdown, $this->config->allowedTags)*/
        $doc->contentMarkdown = $this->post->contentMarkdown;
        if($doc->acl == 'private') $doc->users = $this->app->user->account;

        if($doc->title)
        {
            $condition = "lib = '$doc->lib' AND module = $doc->module";
            $result = $this->loadModel('common')->removeDuplicate('doc', $doc, $condition);
            if($result['stop']) return array('status' => 'exists', 'id' => $result['duplicate']);
        }

        $lib = $this->getLibByID($doc->lib);
        $doc = $this->loadModel('file')->processImgURL($doc, $this->config->doc->editor->create['id'], $this->post->uid);
        $doc->product   = $lib->product;
        $doc->project   = $lib->project;
        $doc->execution = $lib->execution;
        if($doc->type == 'url')
        {
            $doc->content     = $doc->url;
            $doc->contentType = 'html';
        }

        $docContent = new stdclass();
        $docContent->title   = $doc->title;
        $docContent->content = $doc->contentType == 'html' ? $doc->content : $doc->contentMarkdown;
        $docContent->type    = $doc->contentType;
        $docContent->version = 1;
        if($doc->contentType == 'markdown') $docContent->content = str_replace('&gt;', '>', $docContent->content);
        unset($doc->contentMarkdown);
        unset($doc->contentType);
        unset($doc->url);

        $this->dao->insert(TABLE_DOC)->data($doc, 'content')->autoCheck()
            ->batchCheck($this->config->doc->create->requiredFields, 'notempty')
            ->exec();
        if(!dao::isError())
        {
            $docID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $docID, 'doc');
            $files = $this->file->saveUpload('doc', $docID);

            $docContent->doc   = $docID;
            $docContent->files = join(',', array_keys($files));
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
            $this->loadModel('score')->create('doc', 'create', $docID);
            return array('status' => 'new', 'id' => $docID, 'files' => $files);
        }
        return false;
    }

    /**
     * Update a doc.
     *
     * @param  int    $docID
     * @access public
     * @return void
     */
    public function update($docID)
    {
        $oldDoc = $this->dao->select('*')->from(TABLE_DOC)->where('id')->eq((int)$docID)->fetch();
        if(!empty($_POST['editedDate']) and $oldDoc->editedDate != $this->post->editedDate)
        {
            dao::$errors[] = $this->lang->error->editedByOther;
            return false;
        }

        $now = helper::now();
        $doc = fixer::input('post')->setDefault('module', 0)
            ->stripTags($this->config->doc->editor->edit['id'], $this->config->allowedTags)
            ->setDefault('users', '')
            ->setDefault('groups', '')
            ->add('editedBy',   $this->app->user->account)
            ->add('editedDate', $now)
            ->cleanInt('module')
            ->join('groups', ',')
            ->join('users', ',')
            ->join('mailto', ',')
            ->remove('comment,files,labels,uid,contactListMenu')
            ->get();
        if($doc->contentType == 'markdown') $doc->content = $this->post->content;
        if($doc->acl == 'private') $doc->users = $oldDoc->addedBy;

        $oldDocContent = $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq($docID)->andWhere('version')->eq($oldDoc->version)->fetch();
        if($oldDocContent)
        {
            $oldDoc->title       = $oldDocContent->title;
            $oldDoc->digest      = $oldDocContent->digest;
            $oldDoc->content     = $oldDocContent->content;
            $oldDoc->contentType = $oldDocContent->type;

            if($oldDocContent->type == 'markdown') $doc->content = str_replace('&gt;', '>', $doc->content);
        }

        $lib = $this->getLibByID($doc->lib);
        $doc = $this->loadModel('file')->processImgURL($doc, $this->config->doc->editor->edit['id'], $this->post->uid);
        $doc->product   = $lib->product;
        $doc->execution = $lib->execution;
        if(isset($doc->type) and $doc->type == 'url') $doc->content = $doc->url;
        unset($doc->url);

        $files   = $this->file->saveUpload('doc', $docID);
        $changes = common::createChanges($oldDoc, $doc);
        $changed = false;
        if($files) $changed = true;
        foreach($changes as $change)
        {
            if($change['field'] == 'content' or $change['field'] == 'title') $changed = true;
        }

        if($changed)
        {
            $doc->version  = $oldDoc->version + 1;
            $docContent = new stdclass();
            $docContent->doc     = $docID;
            $docContent->title   = $doc->title;
            $docContent->content = isset($doc->content) ? $doc->content : '';
            $docContent->version = $doc->version;
            $docContent->type    = $oldDocContent->type;
            $docContent->files   = $oldDocContent->files;
            if(isset($doc->digest)) $docContent->digest  = $doc->digest;
            if($files) $docContent->files .= ',' . join(',', array_keys($files));
            $docContent->files   = trim($docContent->files, ',');
            $this->dao->replace(TABLE_DOCCONTENT)->data($docContent)->exec();
        }
        unset($doc->contentType);

        $this->dao->update(TABLE_DOC)->data($doc, 'content')
            ->autoCheck()
            ->batchCheck($this->config->doc->edit->requiredFields, 'notempty')
            ->where('id')->eq((int)$docID)
            ->exec();
        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $docID, 'doc');
            return array('changes' => $changes, 'files' => $files);
        }
    }

    /**
     * Build search form.
     *
     * @param  string $libID
     * @param  array  $libs
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($libID, $libs, $queryID, $actionURL, $type)
    {
        $this->config->doc->search['actionURL'] = $actionURL;
        $this->config->doc->search['queryID']   = $queryID;
        $this->config->doc->search['params']['product']['values']   = array(''=>'') + $this->loadModel('product')->getPairs('nocode', $this->session->project) + array('all'=>$this->lang->doc->allProduct);
        $this->config->doc->search['params']['execution']['values'] = array(''=>'') + $this->loadModel('execution')->getPairs($this->session->project, 'all', 'noclosed') + array('all'=>$this->lang->doc->allExecutions);
        $this->config->doc->search['params']['lib']['values']     = array(''=>'', $libID => ($libID ? $libs[$libID] : 0), 'all' => $this->lang->doclib->all);

        /* Get the modules. */
        $moduleOptionMenu = $this->loadModel('tree')->getOptionMenu($libID, 'doc', $startModuleID = 0);
        $this->config->doc->search['params']['module']['values'] = $moduleOptionMenu;

        if($type == 'index' || $type == 'objectLibs' || $libID == 0)
        {
            unset($this->config->doc->search['fields']['module']);
            unset($this->config->doc->search['fields']['lib']);
        }

        $this->loadModel('search')->setSearchParams($this->config->doc->search);
    }

    /**
     * Get pairs of execution modules.
     *
     * @access public
     * @return array
     */
    public function getExecutionModulePairs()
    {
        return $this->dao->select('t1.id,t1.name')->from(TABLE_MODULE)->alias('t1')
            ->leftJoin(TABLE_DOCLIB)->alias('t2')->on('t1.root = t2.id')
            ->andWhere('t1.type')->eq('doc')
            ->andWhere('t2.execution')->ne('0')
            ->fetchPairs('id', 'name');
    }

    /**
     * Get doc menu.
     *
     * @param  int    $libID
     * @param  int    $parent
     * @access public
     * @return array
     */
    public function getDocMenu($libID, $parent, $orderBy = 'name_asc', $browseType = '')
    {
        if($libID == 0 and $browseType != 'collectedbyme') return array();

        $modules = $this->dao->select('*')->from(TABLE_MODULE)
            ->where(1)
            ->beginIF($browseType != "collectedbyme")->andWhere('root')->eq($libID)->fi()
            ->beginIF($browseType == "collectedbyme")->andWhere('collector')->like("%,{$this->app->user->account},%")->fi()
            ->andWhere('type')->eq('doc')
            ->andWhere('parent')->eq($parent)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->fetchAll('id');

        $docCounts = $this->dao->select("module, count(id) as docCount")->from(TABLE_DOC)
            ->where('module')->in(array_keys($modules))
            ->andWhere('deleted')->eq(0)
            ->groupBy('module')
            ->fetchPairs();

        foreach($modules as $moduleID => $module) $modules[$moduleID]->docCount = isset($docCounts[$moduleID]) ? $docCounts[$moduleID] : 0;

        return $modules;
    }

    /**
     * Extract css styles for tables created in kindeditor.
     *
     * Like this: <table class="ke-table1" style="width:100%;" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000">
     *
     * @param  string    $content
     * @access public
     * @return void
     */
    public function extractKETableCSS($content)
    {
        $css = '';
        $rule = '/<table class="ke(.*)" .*/';
        if(preg_match_all($rule, $content, $results))
        {
            foreach($results[0] as $tableLine)
            {
                $attributes = explode(' ', str_replace('"', '', $tableLine));
                foreach($attributes as $attribute)
                {
                    if(strpos($attribute, '=') === false) continue;
                    list($attributeName, $attributeValue) = explode('=', $attribute);
                    $$attributeName = trim(str_replace('>', '', $attributeValue));
                }

                if(!isset($class)) continue;
                $className   = $class;
                $borderSize  = isset($border)      ? $border . 'px' : '1px';
                $borderColor = isset($bordercolor) ? $bordercolor : 'gray';
                $borderStyle = "{border:$borderSize $borderColor solid}\n";
                $css .= ".$className$borderStyle";
                $css .= ".$className td$borderStyle";
            }
        }
        return $css;
    }

    /**
     * Check priv for lib.
     *
     * @param  object $object
     * @param  string $extra
     * @access public
     * @return bool
     */
    public function checkPrivLib($object, $extra = '')
    {
        if($this->app->user->admin) return true;

        if($object->acl == 'open') return true;

        $account = ',' . $this->app->user->account . ',';
        if(isset($object->addedBy) and $object->addedBy == $this->app->user->account) return true;
        if(isset($object->users) and strpos(",{$object->users},", $account) !== false) return true;
        if($object->acl == 'custom')
        {
            $userGroups = $this->app->user->groups;
            foreach($userGroups as $groupID)
            {
                if(strpos(",$object->groups,", ",$groupID,") !== false) return true;
            }
        }

        if(strpos($extra, 'notdoc') === false)
        {
            static $extraDocLibs;
            if($extraDocLibs === null) $extraDocLibs = $this->getPrivLibsByDoc();
            if(isset($extraDocLibs[$object->id])) return true;
        }

        if(!empty($object->product) or !empty($object->execution))
        {
            $acls = $this->app->user->rights['acls'];
            if(!empty($object->product)   and !empty($acls['products']) and !in_array($object->product, $acls['products'])) return false;
            if(!empty($object->execution) and !empty($acls['sprints']) and !in_array($object->execution, $acls['sprints'])) return false;
            if(!empty($object->execution)) return $this->loadModel('execution')->checkPriv($object->execution);
            if(!empty($object->product)) return $this->loadModel('product')->checkPriv($object->product);
        }

        return false;
    }

    /**
     * Check priv for doc.
     *
     * @param  object    $object
     * @access public
     * @return bool
     */
    public function checkPrivDoc($object)
    {
        if($this->app->user->admin) return true;

        static $extraDocLibs;
        if($extraDocLibs === null) $extraDocLibs = $this->getPrivLibsByDoc();

        static $libs;
        if($libs === null) $libs = $this->getLibs('all', 'notdoc');
        if(isset($libs[$object->lib]) and isset($extraDocLibs[$object->lib])) unset($extraDocLibs[$object->lib]);

        if($object->acl == 'open' and !isset($extraDocLibs[$object->lib])) return true;
        if($object->acl == 'public' and !isset($extraDocLibs[$object->lib])) return true;

        $account = ',' . $this->app->user->account . ',';
        if(isset($object->addedBy) and $object->addedBy == $this->app->user->account) return true;
        if(strpos(",$object->users,", $account) !== false) return true;
        if($object->acl == 'custom')
        {
            $userGroups = $this->app->user->groups;
            foreach($userGroups as $groupID)
            {
                if(strpos(",$object->groups,", ",$groupID,") !== false) return true;
            }
        }

        return false;
    }

    /**
     * Get all libs by type.
     *
     * @param  string $type
     * @param  int    $pager
     * @param  string $extra
     * @access public
     * @return array
     */
    public function getAllLibsByType($type, $pager = null, $product = '')
    {
        if($product and $type == 'execution') $executions = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('product')->eq($product)->fetchPairs('project', 'project');

        $libs = $this->getLibs($type == 'collector' ? 'all' : $type);
        $key  = ($type == 'product' or $type == 'execution') ? $type : 'id';
        $stmt = $this->dao->select("DISTINCT $key")->from(TABLE_DOCLIB)->where('deleted')->eq(0);
        if($type == 'product' or $type == 'execution')
        {
            $stmt = $stmt->andWhere($type)->ne(0);
        }
        elseif($type == 'collector')
        {
            $stmt = $stmt->andWhere('collector')->like("%,{$this->app->user->account},%");
        }
        else
        {
            $stmt = $stmt->andWhere('type')->eq($type);
        }
        if(isset($executions)) $stmt = $stmt->andWhere('execution')->in($executions);

        $idList = $stmt->andWhere('id')->in(array_keys($libs))->orderBy("{$key}_desc")->fetchPairs($key, $key);

        if($type == 'product' or $type == 'execution')
        {
            $orderBy = '`order` desc, id desc';
            if($type == 'execution')
            {
                $project = $this->loadModel('project')->getByID($this->session->project);
                $orderBy = (isset($project->model) and $project->model) == 'waterfall' ? 'begin_asc,id_asc' : 'begin_desc,id_desc';
            }

            $table  = $type == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
            $fields = $type == 'product' ? "createdBy, createdDate" : "openedBy AS createdBy, openedDate AS createdDate";
            $libs   = $this->dao->select("id, name, `order`, {$fields}")->from($table)
                ->where('id')->in($idList)
                ->beginIF($type == 'execution' and strpos($this->config->doc->custom->showLibs, 'unclosed') !== false)->andWhere('status')->notin('done,closed')->fi()
                ->orderBy($orderBy)
                ->page($pager, 'id')
                ->fetchAll('id');
        }
        else
        {
            $libs = $this->dao->select('id, name, collector')->from(TABLE_DOCLIB)->where('id')->in($idList)->orderBy('`order`, id desc')->page($pager, 'id')->fetchAll('id');
        }

        return $libs;
    }

    /**
     * Get all lib groups.
     *
     * @param  string $appendLibs
     * @access public
     * @return array
     */
    public function getAllLibGroups($appendLibs = '')
    {
        $libs = $this->getLibs('all', '', $appendLibs);
        $stmt = $this->dao->select("id,type,product,execution,name")->from(TABLE_DOCLIB)
            ->where('deleted')->eq(0)
            ->andWhere("id")->in(array_keys($libs))
            ->orderBy("product desc,execution desc, `order` asc, id asc")
            ->query();

        $customLibs    = array();
        $productLibs   = array();
        $executionLibs = array();

        $otherLibs = array();
        while($lib = $stmt->fetch())
        {
            if($lib->type == 'product')
            {
                $productLibs[$lib->product][$lib->id] = $lib->name;
            }
            elseif($lib->type == 'execution')
            {
                $executionLibs[$lib->execution][$lib->id] = $lib->name;
            }
            else
            {
                $otherLibs[$lib->type][$lib->id] = $lib->name;
            }
        }

        $productIdList = array_keys($productLibs);
        $products      = $this->dao->select('id,name,status')->from(TABLE_PRODUCT)->where('id')->in($productIdList)->andWhere('deleted')->eq('0')->orderBy('`order`_desc')->fetchAll();
        $hasProject    = $this->dao->select('DISTINCT t1.product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.product')->in($productIdList)
            ->andWhere('t2.deleted')->eq(0)
            ->fetchPairs('product', 'product');

        $hasLibsPriv  = common::hasPriv('doc', 'allLibs');
        $hasFilesPriv = common::hasPriv('doc', 'showFiles');
        $productOrderLibs = array();
        foreach($products as $product)
        {
            $productID   = $product->id;
            $productName = $product->name;
            if(isset($productLibs[$productID]))
            {
                $productOrderLibs[$productID]['id']     = $productID;
                $productOrderLibs[$productID]['name']   = $productName;
                $productOrderLibs[$productID]['status'] = $product->status;
                foreach($productLibs[$productID] as $libID => $libName) $productOrderLibs[$productID]['libs'][$libID] = $libName;
                if(isset($hasProject[$productID]) and $hasLibsPriv) $productOrderLibs[$productID]['libs']['project'] = $this->lang->doclib->execution;
                if($hasFilesPriv) $productOrderLibs[$productID]['libs']['files'] = $this->lang->doclib->files;
            }
        }

        $executions = $this->dao->select('id,name,status')->from(TABLE_EXECUTION)
            ->where('id')->in(array_keys($executionLibs))
            ->andWhere('deleted')->eq('0')
            ->beginIF(strpos($this->config->doc->custom->showLibs, 'unclosed') !== false)->andWhere('status')->notin('done,closed')->fi()
            ->orderBy('`order`_desc')
            ->fetchAll();
        $executionOrderLibs = array();
        foreach($executions as $execution)
        {
            $executionID   = $execution->id;
            $executionName = $execution->name;
            if(isset($executionLibs[$executionID]))
            {
                $executionOrderLibs[$executionID]['id']     = $executionID;
                $executionOrderLibs[$executionID]['name']   = $executionName;
                $executionOrderLibs[$executionID]['status'] = $execution->status;
                foreach($executionLibs[$executionID] as $libID => $libName) $executionOrderLibs[$executionID]['libs'][$libID] = $libName;
                if($hasFilesPriv) $executionOrderLibs[$executionID]['libs']['files'] = $this->lang->doclib->files;
            }
        }

        return array('product' => $productOrderLibs, 'execution' => $executionOrderLibs) + $otherLibs;
    }

    /**
     * Get limit libs.
     *
     * @param  string $type
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getLimitLibs($type, $limit = 0)
    {
        $libs    = array();
        $docLibs = array();
        if($type == 'product' or $type == 'extension')
        {
            $nonzeroLibs = array();
            if(strpos($this->config->doc->custom->showLibs, 'zero') === false)
            {
                $nonzeroLibs = $this->dao->select('lib,count(*) as count')->from(TABLE_DOC)->where('deleted')->eq('0')->groupBy('lib')->having('count')->ne(0)->fetchPairs('lib', 'lib');
            }

            $idList          = array();
            $projectID       = $this->session->project;
            $executionStatus = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'undone' : 'all';

            /* If it is a extension module, query the extension related products. */
            if($type == 'product' && $this->lang->navGroup->doc == 'project')
            {
                $objectList = $this->loadModel('product')->getProductPairsByProject($projectID, 'all');
            }
            elseif($type == 'product' && $this->lang->navGroup->doc == 'doc')
            {
                $objectList = $this->loadModel('product')->getPairs();
            }

            if($type == 'execution') $objectList = $this->loadModel('execution')->getByProject($projectID, $executionStatus, 0, true);
            if(empty($objectList)) return $libs;

            $docLibs = $this->dao->select('*')->from(TABLE_DOCLIB)
                ->where('deleted')->eq(0)
                ->andWhere("$type")->in(array_keys($objectList))
                ->orderBy("`order` asc, id asc")
                ->fetchAll();
        }
        else
        {
            $docLibs = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere('type')->eq($type)->orderBy('`order`, id desc')->fetchAll();
            if(empty($docLibs)) return $libs;
        }

        $i = 1;
        foreach($docLibs as $docLib)
        {
            if($limit && $i > $limit) break;

            if($this->checkPrivLib($docLib))
            {
                if($type == 'product' or $type == 'execution')
                {
                    $docLib->name = isset($objectList[$docLib->$type]) ? $objectList[$docLib->$type] : '';
                    $docLib->id   = $docLib->$type;
                }
                $libs[$docLib->id] = $docLib->name;

                $i++;
            }
        }

        return $libs;
    }

    /**
     * Get execution or product libs groups.
     *
     * @param  string $type
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function getSubLibGroups($type, $idList)
    {
        if($type != 'product' and $type != 'execution') return false;
        $libGroups = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere($type)->in($idList)->orderBy('`order`, id')->fetchGroup($type, 'id');

        $buildGroups = array();
        foreach($libGroups as $objectID => $libs)
        {
            foreach($libs as $lib)
            {
                if($this->checkPrivLib($lib)) $buildGroups[$objectID][$lib->id] = $lib->name;
            }

            if(common::hasPriv('doc', 'showFiles')) $buildGroups[$objectID]['files'] = $this->lang->doclib->files;
        }

        return $buildGroups;
    }

    /**
     * Get libs by object.
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  string $mode
     * @access public
     * @return array
     */
    public function getLibsByObject($type, $objectID, $mode = '')
    {
        if($type == 'custom' or $type == 'book')
        {
            $objectLibs = $this->dao->select('*')->from(TABLE_DOCLIB)
                ->where('deleted')->eq(0)
                ->andWhere('type')->eq($type)
                ->beginIF($type == 'custom')->orderBy('`order`, id')->fi()
                ->beginIF($type == 'book')->orderBy('id_desc')->fi()
                ->fetchAll('id');
        }
        else if($type != 'product' and $type != 'project' and $type != 'execution')
        {
            return false;
        }
        else
        {
            $objectLibs = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere($type)->eq($objectID)->orderBy('`order`, id')->fetchAll('id');
        }

        if($type == 'product')
        {
            $hasProject  = $this->dao->select('DISTINCT t1.product, count(t1.project) as projectCount')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.product')->eq($objectID)
                ->beginIF(strpos($this->config->doc->custom->showLibs, 'unclosed') !== false)->andWhere('t2.status')->notin('done,closed')->fi()
                ->andWhere('t2.deleted')->eq(0)
                ->groupBy('product')
                ->fetchPairs('product', 'projectCount');
        }

        $libs = array();
        foreach($objectLibs as $lib)
        {
            if($this->checkPrivLib($lib)) $libs[$lib->id] = $lib;
        }

        $itemCounts = $this->statLibCounts(array_keys($libs));
        foreach($libs as $libID => $lib) $libs[$libID]->allCount = $itemCounts[$libID];

        return $libs;
    }

    /**
     * Get ordered objects for dic.
     *
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getOrderedObjects($objectType = 'product')
    {
        $myObjects = $normalObjects = $closedObjects = array();
        if($objectType == 'product')
        {
            $products = $this->loadModel('product')->getList();
            foreach($products as $id => $product)
            {
                if($product->status == 'normal' and $product->PO == $this->app->user->account)
                {
                    $myObjects[$id] = $product->name;
                }
                elseif($product->status == 'normal' and !($product->PO == $this->app->user->account))
                {
                    $normalObjects[$id] = $product->name;
                }
                elseif($product->status == 'closed')
                {
                    $closedObjects[$id] = $product->name;
                }
            }
        }
        elseif($objectType == 'project')
        {
            /* Load module. */
            $this->loadModel('program');

            /* Sort project. */
            $orderedProjects = array();
            $objects         = $this->program->getList('all', 'order_asc', null, true);
            foreach($objects as $objectID => $object)
            {
                if($object->type == 'program') continue;
                $object->parent = $this->program->getTopByID($object->parent);
                $orderedProjects[$objectID] = $object;
                unset($objects[$object->id]);
            }

            foreach($orderedProjects as $id => $project)
            {
                if($project->status != 'done' and $project->status != 'closed' and $project->PM == $this->app->user->account)
                {
                    $myObjects[$id] = $project->name;
                }
                else if($project->status != 'done' and $project->status != 'closed' and !($project->PM == $this->app->user->account))
                {
                    $normalObjects[$id] = $project->name;
                }
                else if($project->status == 'done' or $project->status == 'closed')
                {
                    $closedObjects[$id] = $project->name;
                }
            }
        }
        elseif($objectType == 'execution')
        {
            $executions = $this->dao->select('*')->from(TABLE_EXECUTION)
                ->where('deleted')->eq(0)
                ->andWhere('type')->in('sprint,stage')
                ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
                ->orderBy('order_asc')
                ->fetchAll('id');

            $orderedExecutions = array();
            foreach($executions as $id => $execution)
            {
                if($execution->status != 'done' and $execution->status != 'closed' and $execution->PM == $this->app->user->account)
                {
                    $myObjects[$id] = $execution->name;
                }
                else if($execution->status != 'done' and $execution->status != 'closed' and !($execution->PM == $this->app->user->account))
                {
                    $normalObjects[$id] = $execution->name;
                }
                else if($execution->status == 'done' or $execution->status == 'closed')
                {
                    $closedObjects[$id] = $execution->name;
                }
            }
        }

        return $myObjects + $normalObjects + $closedObjects;
    }

    /**
     * Stat module and document counts of lib.
     *
     * @param  array    $idList
     * @access public
     * @return array
     */
    public function statLibCounts($idList)
    {
        $moduleCounts = $this->dao->select("`root`, count(id) as moduleCount")->from(TABLE_MODULE)
            ->where('type')->eq('doc')
            ->andWhere('root')->in($idList)
            ->andWhere('deleted')->eq(0)
            ->groupBy('root')
            ->fetchPairs();

        $docs = $this->dao->select("`id`,`addedBy`,`lib`,`acl`,`users`,`groups`")->from(TABLE_DOC)
            ->where('lib')->in($idList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('module')->eq(0)
            ->fetchAll();

        $docCounts = array();
        foreach($docs as $doc)
        {
            if(!$this->checkPrivDoc($doc)) continue;
            if(!isset($docCounts[$doc->lib])) $docCounts[$doc->lib] = 0;
            $docCounts[$doc->lib] ++;
        }

        $itemCounts = array();
        foreach($idList as $libID)
        {
            $docCount    = isset($docCounts[$libID]) ? $docCounts[$libID] : 0;
            $moduleCount = isset($moduleCounts[$libID]) ? $moduleCounts[$libID] : 0;
            $itemCounts[$libID] = $docCount + $moduleCount;
        }

        return $itemCounts;
    }

    /**
     * Get lib files.
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLibFiles($type, $objectID, $orderBy, $pager = null)
    {
        if($type != 'execution' and $type != 'project' and $type != 'product') return true;

        $this->loadModel('file');
        $docs = $this->dao->select('*')->from(TABLE_DOC)->where($type)->eq($objectID)->fetchAll('id');
        foreach($docs as $id => $doc)
        {
            if(!$this->checkPrivDoc($doc)) unset($docs[$id]);
        }

        $idList    = array_keys($docs);
        $docIdList = $this->dao->select('id')->from(TABLE_DOC)->where($type)->eq($objectID)->andWhere('id')->in($idList)->get();
        $searchTitle = $this->get->title;
        if($type == 'product')
        {
            $storyIdList      = $this->dao->select('id')->from(TABLE_STORY)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('product')->in($this->app->user->view->products)->get();
            $planIdList       = $this->dao->select('id')->from(TABLE_PRODUCTPLAN)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('product')->in($this->app->user->view->products)->get();

            $bugIdList        = 0;
            $releaseIdList    = 0;
            $testReportIdList = 0;
            $caseIdList       = 0;

            $bugPairs = $this->dao->select('id')->from(TABLE_BUG)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('product')->in($this->app->user->view->products)->fetchPairs('id');
            if(!empty($bugPairs))
            {
                $bugIdList = array_keys($bugPairs);
                $bugIdList = implode(',', $bugIdList);
            }

            $releasePairs = $this->dao->select('id')->from(TABLE_RELEASE)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('product')->in($this->app->user->view->products)->fetchPairs('id');
            if(!empty($releasePairs))
            {
                $releaseIdList = array_keys($releasePairs);
                $releaseIdList = implode(',', $releaseIdList);
            }

            $testReportPairs = $this->dao->select('id')->from(TABLE_TESTREPORT)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('product')->in($this->app->user->view->products)->fetchPairs('id');
            if(!empty($testReportPairs))
            {
                $testReportIdList = array_keys($testReportPairs);
                $testReportIdList = implode(',', $testReportIdList);
            }

            $casePairs = $this->dao->select('id')->from(TABLE_CASE)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('product')->in($this->app->user->view->products)->fetchPairs('id');
            if(!empty($casePairs))
            {
                $caseIdList = array_keys($casePairs);
                $caseIdList = implode(',', $caseIdList);
            }

            $files = $this->dao->select('*')->from(TABLE_FILE)->alias('t1')
                ->where('size')->gt('0')
                ->andWhere("(objectType = 'product' and objectID = $objectID)", true)
                ->orWhere("(objectType = 'doc' and objectID in ($docIdList))")
                ->orWhere("(objectType = 'story' and objectID in ($storyIdList))")
                ->orWhere("(objectType = 'bug' and objectID in ($bugIdList))")
                ->orWhere("(objectType = 'release' and objectID in ($releaseIdList))")
                ->orWhere("(objectType = 'productplan' and objectID in ($planIdList))")
                ->orWhere("(objectType = 'testreport' and objectID in ($testReportIdList))")
                ->orWhere("(objectType = 'testcase' and objectID in ($caseIdList))")
                ->markRight(1)
                ->beginIF($searchTitle)->andWhere('title')->like("%{$searchTitle}%")->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($type == 'project')
        {
            $executionIdList = $this->loadModel('execution')->getIdList($objectID);
            $taskPairs  = $this->dao->select('id')->from(TABLE_TASK)->where('execution')->in($executionIdList)->andWhere('deleted')->eq('0')->andWhere('execution')->in($this->app->user->view->sprints)->fetchPairs('id');
            $taskIdList = 0;
            if(!empty($taskPairs))
            {
                $taskIdList = array_keys($taskPairs);
                $taskIdList = implode(',', $taskIdList);
            }

            $buildPairs  = $this->dao->select('id')->from(TABLE_BUILD)->where('execution')->in($executionIdList)->andWhere('deleted')->eq('0')->andWhere('execution')->in($this->app->user->view->sprints)->fetchPairs('id');
            $buildIdList = 0;
            if(!empty($buildPairs))
            {
                $buildIdList = array_keys($buildPairs);
                $buildIdList = implode(',', $buildIdList);
            }

            $executionIdList = join(',', $executionIdList);
            $files = $this->dao->select('*')->from(TABLE_FILE)->alias('t1')
                ->where('size')->gt('0')
                ->andWhere("(objectType = 'project' and objectID = $objectID)", true)
                ->orWhere("(objectType = 'execution' and objectID in ($executionIdList))")
                ->orWhere("(objectType = 'doc' and objectID in ($docIdList))")
                ->orWhere("(objectType = 'task' and objectID in ($taskIdList))")
                ->orWhere("(objectType = 'build' and objectID in ($buildIdList))")
                ->markRight(1)
                ->beginIF($searchTitle)->andWhere('title')->like("%{$searchTitle}%")->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($type == 'execution')
        {
            $taskPairs  = $this->dao->select('id')->from(TABLE_TASK)->where('execution')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('execution')->in($this->app->user->view->sprints)->fetchPairs('id');
            $taskIdList = 0;
            if(!empty($taskPairs))
            {
                $taskIdList = array_keys($taskPairs);
                $taskIdList = implode(',', $taskIdList);
            }

            $buildPairs  = $this->dao->select('id')->from(TABLE_BUILD)->where('execution')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('execution')->in($this->app->user->view->sprints)->fetchPairs('id');
            $buildIdList = 0;
            if(!empty($buildPairs))
            {
                $buildIdList = array_keys($buildPairs);
                $buildIdList = implode(',', $buildIdList);
            }
            $files = $this->dao->select('*')->from(TABLE_FILE)->alias('t1')
                ->where('size')->gt('0')
                ->andWhere("(objectType = 'execution' and objectID = $objectID)", true)
                ->orWhere("(objectType = 'doc' and objectID in ($docIdList))")
                ->orWhere("(objectType = 'task' and objectID in ($taskIdList))")
                ->orWhere("(objectType = 'build' and objectID in ($buildIdList))")
                ->markRight(1)
                ->beginIF($searchTitle)->andWhere('title')->like("%{$searchTitle}%")->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        foreach($files as $fileID => $file)
        {
            if($type == 'product' && $file->objectType == 'bug')        $file->project = $bugPairs[$file->objectID];
            if($type == 'product' && $file->objectType == 'release')    $file->project = $releasePairs[$file->objectID];
            if($type == 'product' && $file->objectType == 'testreport') $file->project = $testReportPairs[$file->objectID];
            if($type == 'product' && $file->objectType == 'testcase')   $file->project = $casePairs[$file->objectID];

            if($type == 'execution' && $file->objectType == 'task')  $file->project = $taskPairs[$file->objectID];
            if($type == 'execution' && $file->objectType == 'build') $file->project = $buildPairs[$file->objectID];

            $pathName       = $this->file->getRealPathName($file->pathname);
            $file->realPath = $this->file->savePath . $pathName;
            $file->webPath  = $this->file->webPath  . $pathName;
        }

        return $files;
    }

    /**
     * Get doc tree.
     *
     * @param  int    $libID
     * @access public
     * @return array
     */
    public function getDocTree($libID)
    {
        $fullTrees = $this->loadModel('tree')->getTreeStructure($libID, 'doc');
        array_unshift($fullTrees, array('id' => 0, 'name' => '/', 'type' => 'doc', 'actions' => false, 'root' => $libID));
        foreach($fullTrees as $i => $tree)
        {
            $tree = (object)$tree;
            $fullTrees[$i] = $this->fillDocsInTree($tree, $libID);
        }
        if(empty($fullTrees[0]->children)) array_shift($fullTrees);
        return $fullTrees;
    }

    /**
     * Fill docs in tree.
     *
     * @param  object $node
     * @param  int    $libID
     * @access public
     * @return array
     */
    public function fillDocsInTree($node, $libID)
    {
        $node = (object)$node;
        static $docGroups;
        if(empty($docGroups))
        {
            $docs = $this->dao->select('*')->from(TABLE_DOC)
                ->where('lib')->eq((int)$libID)
                ->andWhere('deleted')->eq(0)
                ->fetchAll();
            $docGroups = array();
            foreach($docs as $doc)
            {
                if($this->checkPrivDoc($doc)) $docGroups[$doc->module][$doc->id] = $doc;
            }
        }

        if(!empty($node->children)) foreach($node->children as $i => $child) $node->children[$i] = $this->fillDocsInTree($child, $libID);
        if(!isset($node->id))$node->id = 0;

        $node->type = 'module';
        $docs = isset($docGroups[$node->id]) ? $docGroups[$node->id] : array();
        $menu = !empty($node->children) ? $node->children : array();
        if(!empty($docs))
        {
            $docItems = array();
            foreach($docs as $doc)
            {
                $docItem = new stdclass();
                $docItem->type         = 'doc';
                $docItem->id           = $doc->id;
                $docItem->title        = $doc->title;
                $docItem->url          = helper::createLink('doc', 'view', "doc=$doc->id");

                $buttons  = '';
                $buttons .= common::buildIconButton('doc', 'edit',    "docID=$doc->id", '', 'list');
                if(common::hasPriv('doc', 'delete'))$buttons .= html::a(helper::createLink('doc', 'delete', "docID=$doc->id"), "<i class='icon icon-remove'></i>", 'hiddenwin', "class='btn-icon' title='{$this->lang->doc->delete}'");
                $docItem->buttons = $buttons;
                $docItem->actions = false;
                $docItems[]       = $docItem;
            }

            /* Reorder children. The doc is top of menu. */
            if($menu) $docItems = array_merge($docItems, $menu);

            $node->children = $docItems;
        }

        $node->docsCount = isset($node->children) ? count($node->children) : 0;
        $node->actions   = false;
        return $node;
    }

    /**
     * Get product crumb.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function getProductCrumb($productID, $executionID = 0)
    {
        if(empty($productID)) return '';
        if($executionID)
        {
            $executionProduct = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('product')->eq($productID)->andWhere('project')->eq($executionID)->fetch();
            if(empty($executionProduct))
            {
                setcookie('product', 0, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
                return html::a(helper::createLink('doc', 'allLibs', "type=execution"), $this->lang->executionCommon) . $this->lang->doc->separator;
            }
        }
        $object = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->eq($productID)->fetch();
        if(empty($object)) return '';

        $crumb  = '';
        $crumb .= html::a(helper::createLink('doc', 'allLibs', "type=product"), $this->lang->productCommon) . $this->lang->doc->separator;
        $crumb .= html::a(helper::createLink('doc', 'objectLibs', "type=product&objectID=$productID"), $object->name) . $this->lang->doc->separator;
        $crumb .= html::a(helper::createLink('doc', 'allLibs', "type=execution&product=$productID"), $this->lang->doclib->execution);
        if($executionID) $crumb .= $this->lang->doc->separator;
        return $crumb;
    }

    /**
     * Set lib users.
     *
     * @param  string $type
     * @param  int    $objectID
     * @access public
     * @return bool
     */
    public function setLibUsers($type, $objectID)
    {
        if($type != 'execution' and $type != 'product') return array();
        if($type == 'product')
        {
            $teams = $this->dao->select('t1.account')->from(TABLE_TEAM)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.root=t2.project')
                ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.root=t3.id')
                ->where('t2.product')->eq($objectID)
                ->beginIF(strpos($this->config->doc->custom->showLibs, 'unclosed') !== false)->andWhere('t3.status')->notin('done,closed')->fi()
                ->andWhere('t1.type')->eq('execution')
                ->andWhere('t3.deleted')->eq('0')
                ->fetchPairs('account', 'account');
        }
        elseif($type == 'execution')
        {
            $teams = $this->dao->select('account')->from(TABLE_TEAM)->where('root')->eq($objectID)->andWhere('type')->eq('execution')->fetchPairs('account', 'account');
        }

        return $teams;
    }

    /**
     * Get project-related document library IDs.
     *
     * @param  $projectID
     * @access public
     * @return array
     */
    public function getLibIdListByProject($projectID = 0)
    {
        $products   = $this->loadModel('product')->getProductIDByProject($projectID, false);
        $executions = $this->loadModel('execution')->getPairs($projectID, 'all', 'noclosed');

        $executionLibs = array();
        $productLibs   = array();
        if($executions) $executionLibs = $this->dao->select('id')->from(TABLE_DOCLIB)->where('execution')->in(array_keys($executions))->fetchPairs();
        if($products)   $productLibs   = $this->dao->select('id')->from(TABLE_DOCLIB)->where('product')->in($products)->fetchPairs();
        $customLibs = $this->dao->select('id')->from(TABLE_DOCLIB)->where('type')->eq('custom')->fetchPairs();

        $libIdList = array_merge($customLibs, $executionLibs, $productLibs);
        return $libIdList;
    }

    /**
     * Get statistic information.
     *
     * @access public
     * @return object
     */
    public function getStatisticInfo()
    {
        $libIdList = array();
        $libIdList = $this->getLibIdListByProject($this->session->project);
        $docIdList = $this->getPrivDocs($libIdList);

        $today  = date('Y-m-d');
        $lately = date('Y-m-d', strtotime('-3 day'));
        $statisticInfo = $this->dao->select("count(id) as totalDocs, count(editedDate like '{$today}%' or null) as todayEditedDocs,
            count(editedDate > '{$lately}' or null) as lastEditedDocs, count(addedDate > '{$lately}' or null) as lastAddedDocs,
            count(collector like '%,{$this->app->user->account},%' or null) as myCollection, count(addedBy = '{$this->app->user->account}' or null) as myDocs")->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->andWhere('id')->in($docIdList)
            ->fetch();

        $statisticInfo->pastEditedDocs       = $statisticInfo->totalDocs - $statisticInfo->todayEditedDocs;
        $statisticInfo->lastEditedProgress   = $statisticInfo->totalDocs ? round($statisticInfo->lastEditedDocs / $statisticInfo->totalDocs, 2) * 100 : 0;
        $statisticInfo->lastAddedProgress    = $statisticInfo->totalDocs ? round($statisticInfo->lastAddedDocs / $statisticInfo->totalDocs, 2) * 100 : 0;
        $statisticInfo->myCollectionProgress = $statisticInfo->totalDocs ? round($statisticInfo->myCollection / $statisticInfo->totalDocs, 2) * 100 : 0;
        $statisticInfo->myDocsProgress       = $statisticInfo->totalDocs ? round($statisticInfo->myDocs / $statisticInfo->totalDocs, 2) * 100 : 0;

        return $statisticInfo;
    }

    /**
     * Print doc child module.
     *
     * @access public
     */
    public function printChildModule($module, $libID, $methodName, $browseType, $moduleID)
    {
        if(isset($module->children))
        {
            foreach($module->children as $childModule)
            {
                $active = '';
                if($methodName == 'browse' && $browseType == 'bymodule' && $moduleID == $childModule->id) $active = "class='active'";
                echo '<ul>';
                echo "<li $active>";
                echo html::a(helper::createLink('doc', 'browse', "libID=$libID&browseType=byModule&param={$childModule->id}"), "<i class='icon icon-folder-outline'></i> " . $childModule->name, '', "class='text-ellipsis' title='{$childModule->name}'");
                if(isset($childModule->children)) $this->printChildModule($childModule, $libID, $methodName, $browseType, $moduleID);
                echo '</li>';
                echo '</ul>';
            }
        }
    }

    /**
     * Build doc bread title.
     *
     * @access public
     * @return string
     */
    public function buildCrumbTitle($libID = 0, $param = 0, $title = '')
    {
        $path = $this->dao->select('path')->from(TABLE_MODULE)->where('id')->eq($param)->fetch('path');

        $parantMoudles = $this->dao->select('id, name')->from(TABLE_MODULE)
            ->where('id')->in($path)
            ->andWhere('deleted')->eq(0)
            ->orderBy('`grade`')
            ->fetchAll('id');

        foreach($parantMoudles as $parentID => $moduleName)
        {
            $title .= html::a(helper::createLink('doc', 'browse', "libID=$libID&browseType=byModule&param={$parentID}"), " <i class='icon icon-chevron-right'></i> " . $moduleName->name , '');
        }

        return $title;
    }

    /**
     * Build document module index page create document button.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  int    $libID
     * @access public
     * @return string
     */
    public function buildCreateButton4Doc($objectType, $objectID, $libID)
    {
        if($objectType == 'book')
        {
            $html = html::a(helper::createLink('doc', 'createLib', "type=$objectType&objectID=$objectID"), '<i class="icon icon-plus"></i>' . $this->lang->doc->createBook, '', 'class="btn btn-secondary iframe"');
        }
        elseif($libID)
        {
            $html  = "<div class='dropdown' id='createDropdown'>";
            $html .= "<button class='btn btn-primary' type='button' data-toggle='dropdown'><i class='icon icon-plus'></i>" . $this->lang->doc->create . " <span class='caret'></span></button>";
            $html .= "<ul class='dropdown-menu' style='left:0px'>";
            foreach($this->lang->doc->typeList as $typeKey => $typeName)
            {
                $class = strpos($this->config->doc->officeTypes, $typeKey) !== false ? 'iframe' : '';
                $html .= "<li>";
                $html .= html::a(helper::createLink('doc', 'create', "objectType=$objectType&objectID=$objectID&libID=$libID&moduleID=0&type=$typeKey", '', $class ? true : false), $typeName, '', "class='$class' data-app='{$this->app->openApp}'");
                $html .= "</li>";

            }
            if(common::hasPriv('doc', 'createLib'))
            {
                $html .= '<li class="divider"></li>';
                $html .= '<li>' . html::a(helper::createLink('doc', 'createLib', "type=$objectType&objectID=$objectID"), $this->lang->doc->createLib, '', "class='iframe' data-width='70%'") . '</li>';
            }
            $html .= "</ul></div>";
        }
        else
        {
            $html = html::a(helper::createLink('doc', 'createLib', "type=$objectType&objectID=$objectID"), '<i class="icon icon-plus"></i>' . $this->lang->doc->createLib, '', 'class="btn btn-secondary iframe"');
        }

        return $html;
    }

    /**
     * Set past menu.
     *
     * @param  string $fastLib
     * @access public
     * @return string
     */
    public function setFastMenu($fastLib)
    {
        $actions  = '';
        $actions .= '<a class="btn btn-link querybox-toggle" id="bysearchTab"><i class="icon icon-search muted"></i>' . $this->lang->doc->search . '</a>';
        $actions .= "<a data-toggle='dropdown' class='btn btn-link' title=$fastLib>" . $fastLib . " <span class='caret'></span></a>";
        $actions .= "<ul class='dropdown-menu'>";
        foreach($this->lang->doc->fastMenuList as $key => $fastMenu)
        {
            $link     = helper::createLink('doc', 'browse', "libID=0&browseType={$key}");
            $actions .= '<li>' . html::a($link, "<i class='icon {$this->lang->doc->fastMenuIconList[$key]}'></i> {$fastMenu}") . '</li>';
        }
        $actions .='</ul>';

        return $actions;
    }

    /**
     * Send mail.
     *
     * @param  int    $docID
     * @param  int    $actionID
     * @access public
     * @return void
     */
    public function sendmail($docID, $actionID)
    {
        /* Load module and get doc and users. */
        $this->loadModel('mail');
        $doc   = $this->getById($docID);
        $users = $this->loadModel('user')->getPairs('noletter');

        /* When the content type is markdown format, add attributes to the table. */
        if($doc->contentType == 'markdown')
        {
            $doc->content = $this->app->loadClass('hyperdown')->makeHtml($doc->content);
            $doc->content = str_replace("<table>", "<table style='border-collapse: collapse;'>", $doc->content);
            $doc->content = str_replace("<th>", "<th style='word-break: break-word; border:1px solid #000;'>", $doc->content);
            $doc->content = str_replace("<td>", "<td style='word-break: break-word; border:1px solid #000;'>", $doc->content);
        }

        /* Get action info. */
        $action          = $this->loadModel('action')->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();

        /* Get mail content. */
        $modulePath = $this->app->getModulePath($appName = '', 'doc');
        $oldcwd     = getcwd();
        $viewFile   = $modulePath . 'view/sendmail.html.php';
        chdir($modulePath . 'view');
        if(file_exists($modulePath . 'ext/view/sendmail.html.php'))
        {
            $viewFile = $modulePath . 'ext/view/sendmail.html.php';
            chdir($modulePath . 'ext/view');
        }
        ob_start();
        include $viewFile;
        foreach(glob($modulePath . 'ext/view/sendmail.*.html.hook.php') as $hookFile) include $hookFile;
        $mailContent = ob_get_contents();
        ob_end_clean();
        chdir($oldcwd);

        /* Get sender and subject. */
        $sendUsers = $this->getToAndCcList($doc);
        if(!$sendUsers) return;
        list($toList, $ccList) = $sendUsers;
        $subject = $this->getSubject($doc, $action->action);

        /* Send mail. */
        $this->mail->send($toList, $subject, $mailContent, $ccList);
        if($this->mail->isError()) error_log(join("\n", $this->mail->getError()));
    }

    /**
     * Get mail subject.
     *
     * @param  object $doc
     * @param  string $actionType created|edited
     * @access public
     * @return string
     */
    public function getSubject($doc, $actionType)
    {
        /* Set email title. */
        if($actionType == 'created')
        {
            return sprintf($this->lang->doc->mail->create->title, $this->app->user->realname, $doc->id, $doc->title);
        }
        else
        {
            return sprintf($this->lang->doc->mail->edit->title, $this->app->user->realname, $doc->id, $doc->title);
        }
    }

    /**
     * Get toList and ccList.
     *
     * @param  object     $doc
     * @access public
     * @return bool|array
     */
    public function getToAndCcList($doc)
    {
        /* Set toList and ccList. */
        $toList   = '';
        $ccList   = str_replace(' ', '', trim($doc->mailto, ','));

        if(empty($toList))
        {
            if(empty($ccList)) return false;
            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList   = substr($ccList, 0, $commaPos);
                $ccList   = substr($ccList, $commaPos + 1);
            }
        }
        return array($toList, $ccList);
    }

    /**
     * Create the select code of doc.
     *
     * @param  string $type
     * @param  array  $objects
     * @param  int    $objectID
     * @param  array  $libs
     * @param  int    $libID
     * @access public
     * @return string
     */
    public function select($type, $objects, $objectID, $libs, $libID = 0)
    {
        if($type != 'custom' and $type != 'book' and empty($objects)) return '';

        $output = '';

        if($this->app->openApp == 'doc' and $type != 'custom' and $type != 'book')
        {
            $output  = "<div class='btn-group angle-btn'><div class='btn-group'><button data-toggle='dropdown' type='button' class='btn btn-limit' id='currentItem' title='{$objects[$objectID]}'><span class='text'>{$objects[$objectID]}</span> <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList'>";
            $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
            $output .= "<div class='table-col'><div class='list-group'>";
            foreach($objects as $key => $object)
            {
                $selected = $key == $objectID ? 'selected' : '';
                $output  .= html::a(inlink('objectLibs', "type=$type&objectID=$key"), $object, '', "class='$selected' data-app='{$this->app->openApp}'");
            }
            $output .= "</div></div></div></div></div>";
        }

        if(!empty($libs))
        {
            $output .= "<div class='btn-group angle-btn'><div class='btn-group'><button id='currentBranch' data-toggle='dropdown' type='button' class='btn btn-limit'>{$libs[$libID]->name} <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList'>";
            $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
            $output .= "<div class='table-col'><div class='list-group'>";
            foreach($libs as $key => $lib)
            {
                $selected = $key == $libID ? 'selected' : '';
                $output  .= html::a(inlink('objectLibs', "type=$type&objectID=$objectID&libID=$key"), $lib->name, '', "class='$selected' data-app='{$this->app->openApp}'");
            }
            $output .= "</div></div></div></div></div>";
        }

        return $output;
    }

    /**
     * Get doc tree menu.
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  int    $rootID
     * @param  int    $startModule
     * @access public
     * @return string
     */
    public function getTreeMenu($type, $objectID, $rootID, $startModule = 0, & $docID = 0)
    {
        $startModulePath = '';
        if($startModule > 0)
        {
            $startModule = $this->tree->getById($startModule);
            if($startModule) $startModulePath = $startModule->path . '%';
        }

        $docs = $this->dao->select('*')->from(TABLE_DOC)->where('lib')->eq($rootID)->andWhere('deleted')->eq(0)->fetchAll();
        $moduleDocs = array();
        foreach($docs as $doc)
        {
            if(!isset($moduleDocs[$doc->module])) $moduleDocs[$doc->module] = array();
            $moduleDocs[$doc->module][] = $doc;
        }

        $treeMenu = array();
        $query = $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->eq((int)$rootID)
            ->andWhere('type')->eq('doc')
            ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy('grade desc, `order`')
            ->get();
        $stmt = $this->dbh->query($query);
        while($module = $stmt->fetch())
        {
            $this->buildTree($treeMenu, $type, $objectID, $rootID, $module, $moduleDocs, $docID);
        }

        if(isset($moduleDocs[0]))
        {
            if(!isset($treeMenu[0])) $treeMenu[0] = '';

            foreach($moduleDocs[0] as $doc)
            {
                if(!$docID) $docID = $doc->id;
                $treeMenu[0] .= '<li' . ($doc->id == $docID ? ' class="active"' : '') . '>' . html::a(inlink('objectLibs', "type=$type&objectID=$objectID&libID=$rootID&docID={$doc->id}"), $doc->title, '', "data-app='{$this->app->openApp}'") . '</li>';
            }
        }

        if(empty($treeMenu)) return '';

        $menu = "<ul id='modules' class='tree' data-ride='tree' data-name='tree-lib'>" . $treeMenu[0] . '</ul>';
        return $menu;
    }

    /**
     * Build doc tree menu.
     *
     * @param  pointer $treeMenu
     * @param  string  $type
     * @param  int     $objectID
     * @param  int     $libID
     * @param  object  $module
     * @param  array   $moduleDocs
     * @param  int     $docID
     * @access public
     * @return string
     */
    private function buildTree(& $treeMenu, $type, $objectID, $libID, $module, $moduleDocs, & $docID)
    {
        if(!isset($treeMenu[$module->id])) $treeMenu[$module->id] = '';

        if(isset($moduleDocs[$module->id]))
        {
            foreach($moduleDocs[$module->id] as $doc)
            {
                if(!$docID) $docID = $doc->id;
                $treeMenu[$module->id] .= '<li' . ($doc->id == $docID ? ' class="active"' : '') . '>' . html::a(inlink('objectLibs', "type=$type&objectID=$objectID&libID=$libID&docID={$doc->id}"), $doc->title, '', "data-app='{$this->app->openApp}'") . '</li>';
            }
        }

        $li = '<a>' . $module->name . '</a>';
        if($treeMenu[$module->id])
        {
            $li .= '<ul>' . $treeMenu[$module->id] . '</ul>';
        }

        if(!isset($treeMenu[$module->parent])) $treeMenu[$module->parent] = '';
        $treeMenu[$module->parent] .= '<li' . ($treeMenu[$module->id] ? ' class="closed"' : '') . '>' . $li . '</li>';
    }
}
