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
     * Set menus
     * 
     * @param  array  $libs 
     * @param  int    $libID 
     * @param  string $moduleID 
     * @access public
     * @return void
     */
    public function setMenu($libID = 0, $moduleID = 0, $crumb = '')
    {
        $customMenuKey = $this->config->global->flow . '_doc';
        if(isset($this->config->customMenu->{$customMenuKey}))
        {
            $customMenu = json_decode($this->config->customMenu->{$customMenuKey}, true);
            $menuLibIdList = array();
            foreach($customMenu as $i => $menu)
            {
                if(strpos($menu['name'], 'custom') === 0)
                {
                    $menuLibID = (int)substr($menu['name'], 6);
                    if($menuLibID) $menuLibIdList[$i] = $menuLibID;
                }
            }

            $productIdList = array();
            $projectIdList = array();
            if($menuLibIdList)
            {
                $libs = $this->dao->select('id,name,product,project')->from(TABLE_DOCLIB)->where('id')->in($menuLibIdList)->fetchAll('id');
                foreach($libs as $lib)
                {
                    if($lib->product) $productIdList[] = $lib->product;
                    if($lib->project) $projectIdList[] = $lib->project;
                }
            }
            $products = $productIdList ? $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->in($productIdList)->fetchPairs('id', 'name') : array();
            $projects = $projectIdList ? $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in($projectIdList)->fetchPairs('id', 'name') : array();
            foreach($menuLibIdList as $i => $menuLibID)
            {
                $lib = $libs[$menuLibID];
                $libName = '';
                if($lib->product) $libName = isset($products[$lib->product]) ? '[' . $products[$lib->product] . ']' : '';
                if($lib->project) $libName = isset($projects[$lib->project]) ? '[' . $projects[$lib->project] . ']' : '';
                $libName .= $lib->name;
                $customMenu[$i]['link'] = "{$libName}|doc|browse|libID={$menuLibID}";
            }
            $this->config->customMenu->{$customMenuKey} = json_encode($customMenu);
        }

        $this->app->loadLang('project');
        $selectHtml  = "<a id='currentItem' data-lib-id='$libID' href=\"javascript:showLibMenu()\">{$this->lang->doclib->all} <span class='icon-caret-down'></span></a>";
        $selectHtml .= "<div id='dropMenu'>";
        $selectHtml .= "<i class='icon icon-spin icon-spinner'></i>";
        $selectHtml .= "<div id='libMenu'>";
        $selectHtml .= "<div id='libMenuHeading'><input id='searchLib' type='search' placeholder='{$this->lang->doc->searchDoc}' class='form-control'></div>";
        $selectHtml .= "<div id='libMenuGroups' class='clearfix'>";
        if($this->config->global->flow != 'onlyTask')  $selectHtml .= "<div class='lib-menu-group' id='libMenuProductGroup'><div class='lib-menu-list-heading' data-type='product'>{$this->lang->doc->libTypeList['product']}<i class='icon icon-remove'></i></div><div class='lib-menu-list clearfix'></div></div>";
        if($this->config->global->flow != 'onlyStory' and $this->config->global->flow != 'onlyTest') $selectHtml .= "<div class='lib-menu-group' id='libMenuProjectGroup'><div class='lib-menu-list-heading' data-type='project'>{$this->lang->doc->libTypeList['project']}<i class='icon icon-remove'></i></div><div class='lib-menu-project-done'>{$this->lang->project->statusList['done']}<i class='icon icon-remove'></i></div><div class='lib-menu-list clearfix'></div></div>";
        $selectHtml .= "<div class='lib-menu-group' id='libMenuCustomGroup'><div class='lib-menu-list-heading' data-type='custom'>{$this->lang->doc->libTypeList['custom']}<i class='icon icon-remove'></i></div><div class='lib-menu-list clearfix'></div></div>";
        $selectHtml .= "</div></div></div>";
        common::setMenuVars($this->lang->doc->menu, 'list', $selectHtml);
        common::setMenuVars($this->lang->doc->menu, 'crumb', $crumb ? $crumb : $this->getCrumbs($libID, $moduleID));
    }

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
     * @access public
     * @return array
     */
    public function getLibs($type = '')
    {
        if($type == 'product' or $type == 'project')
        {
            $table   = $type == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
            $stmt    = $this->dao->select('t1.*')->from(TABLE_DOCLIB)->alias('t1')
                ->leftJoin($table)->alias('t2')->on("t1.$type=t2.id")
                ->where("t1.$type")->ne(0)
                ->andWhere('t1.deleted')->eq(0)
                ->orderBy("t2.order desc, t1.order, t1.id")
                ->query();
        }
        elseif($type == 'all')
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->orderBy('`order`, id desc')->query();
        }
        else
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere('product')->eq('0')->andWhere('project')->eq(0)->orderBy('`order`, id desc')->query();
        }

        $libPairs = array();
        while($lib = $stmt->fetch())
        {
            if($this->checkPriv($lib)) $libPairs[$lib->id] = $lib->name;
        }
        return $libPairs;
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
            ->setForce('product', $this->post->libType == 'product' ? $this->post->product : 0)
            ->setForce('project', $this->post->libType == 'project' ? $this->post->project : 0)
            ->join('groups', ',')
            ->join('users', ',')
            ->remove('libType')
            ->get();

        if($lib->acl == 'private') $lib->users = $this->app->user->account;
        $this->dao->insert(TABLE_DOCLIB)->data($lib)->autoCheck()
            ->batchCheck('name', 'notempty')
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
        $lib = fixer::input('post')->join('groups', ',')->join('users', ',')->get();
        if($lib->acl == 'private') $lib->users = $this->app->user->account;
        $this->dao->update(TABLE_DOCLIB)->data($lib)->autoCheck()
            ->batchCheck('name', 'notempty')
            ->where('id')->eq($libID)
            ->exec();
        if(!dao::isError()) return common::createChanges($oldLib, $lib);
    }

    /**
     * Get docs by browse type.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @param  string $browseType
     * @param  string $libID
     * @param  int    $queryID
     * @param  int    $moduleID
     * @param  string $sort
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getDocsByBrowseType($libID, $browseType, $queryID, $moduleID, $sort, $pager)
    {
        if($browseType == "all")
        {
            $docs = $this->getDocs($libID, 0, $sort, $pager);
        }
        elseif($browseType == "openedbyme")
        {
            $docs = $this->dao->select('*')->from(TABLE_DOC)
                ->where('deleted')->eq(0)
                ->andWhere('lib')->in($libID)
                ->andWhere('addedBy')->eq($this->app->user->account)
                ->orderBy($sort)
                ->page($pager)
                ->fetchAll();
        }
        elseif($browseType == "bymodule")
        {
            $modules = 0;
            if($moduleID) $modules = $this->loadModel('tree')->getAllChildId($moduleID);
            $docs = $this->getDocs($libID, $modules, $sort, $pager);
        }
        elseif($browseType == "bymenu")
        {
            $docs = $this->getDocs($libID, $moduleID, $sort, $pager);
        }
        elseif($browseType == 'bytree')
        {
            return array();
        }
        elseif($browseType == "bysearch")
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('docQuery', $query->sql);
                    $this->session->set('docForm', $query->form);
                }
                else
                {
                    $this->session->set('docQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->docQuery == false) $this->session->set('docQuery', ' 1 = 1');
            }

            $libCond    = strpos($this->session->docQuery, "`lib` = ") !== false;
            $allLibCond = strpos($this->session->docQuery, "`lib` = 'all'") !== false;

            $docQuery  = str_replace("`product` = 'all'", '1', $this->session->docQuery); // Search all product.
            $docQuery  = str_replace("`project` = 'all'", '1', $docQuery);                // Search all project.
            $docQuery  = str_replace("`lib` = 'all'", '1', $docQuery);                // Search all lib.

            $docs = $this->dao->select('*')->from(TABLE_DOC)->where($docQuery)
                ->beginIF(!$libCond)->andWhere("lib")->eq($libID)->fi()
                ->andWhere('deleted')->eq(0)
                ->fetchAll('id');
            foreach($docs as $docID => $doc)
            {
                if(!$this->checkPriv($doc)) unset($docs[$docID]);
            }
            $docs = $this->dao->select('*')->from(TABLE_DOC)->where('id')->in(array_keys($docs))->orderBy($sort)->page($pager)->fetchAll();
        }

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'doc');
        if($docs) return $docs;

        return array();
    }

    /**
     * Get docs.
     * 
     * @param  int|string   $libID 
     * @param  int          $productID 
     * @param  int          $projectID 
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
     * @access public
     * @return void
     */
    public function getPrivDocs($libID = 0, $module = 0)
    {
        $stmt = $this->dao->select('*')->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->beginIF($libID)->andWhere('lib')->in($libID)->fi()
            ->beginIF($module)->andWhere('module')->in($module)->fi()
            ->query();

        $docIdList = array();
        while($doc = $stmt->fetch())
        {
            if($this->checkPriv($doc)) $docIdList[$doc->id] = $doc->id;
        }
        return $docIdList;
    }

    /**
     * Get doc info by id.
     * 
     * @param  int    $docID 
     * @param  bool   $setImgSize 
     * @access public
     * @return void
     */
    public function getById($docID, $version = 0, $setImgSize = false)
    {
        $doc = $this->dao->select('*')->from(TABLE_DOC)->where('id')->eq((int)$docID)->fetch();
        if(!$doc) return false;
        if(!$this->checkPriv($doc))
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

        $doc = $this->loadModel('file')->replaceImgURL($doc, 'content');
        if($setImgSize) $doc->content = $this->file->setImgSize($doc->content);
        $doc->files = $docFiles;

        $doc->productName = '';
        $doc->projectName = '';
        $doc->moduleName  = '';
        if($doc->product) $doc->productName = $this->dao->findByID($doc->product)->from(TABLE_PRODUCT)->fetch('name');
        if($doc->project) $doc->projectName = $this->dao->findByID($doc->project)->from(TABLE_PROJECT)->fetch('name');
        if($doc->module)  $doc->moduleName  = $this->dao->findByID($doc->module)->from(TABLE_MODULE)->fetch('name');
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
            ->add('version', 1)
            ->setDefault('product,project,module', 0)
            ->stripTags($this->config->doc->editor->create['id'], $this->config->allowedTags)
            ->stripTags($this->config->doc->markdown->create['id'], $this->config->allowedTags)
            ->cleanInt('product,project,module')
            ->join('groups', ',')
            ->join('users', ',')
            ->remove('files,labels,uid')
            ->get();
        if($doc->acl == 'private') $doc->users = $this->app->user->account;
        $condition = "lib = '$doc->lib' AND module = $doc->module";

        $result = $this->loadModel('common')->removeDuplicate('doc', $doc, $condition);
        if($result['stop']) return array('status' => 'exists', 'id' => $result['duplicate']);

        $lib = $this->getLibByID($doc->lib);
        $doc = $this->loadModel('file')->processImgURL($doc, $this->config->doc->editor->create['id'], $this->post->uid);
        $doc->product = $lib->product;
        $doc->project = $lib->project;
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
        unset($doc->content);
        unset($doc->contentMarkdown);
        unset($doc->contentType);
        unset($doc->url);

        $this->dao->insert(TABLE_DOC)->data($doc)->autoCheck()
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
            return array('status' => 'new', 'id' => $docID);
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
        $now = helper::now();
        $doc = fixer::input('post')->setDefault('module', 0)
            ->stripTags($this->config->doc->editor->edit['id'], $this->config->allowedTags)
            ->add('editedBy',   $this->app->user->account)
            ->add('editedDate', $now)
            ->cleanInt('module')
            ->join('groups', ',')
            ->join('users', ',')
            ->remove('comment,files,labels,uid')
            ->get();
        if($doc->acl == 'private') $doc->users = $oldDoc->addedBy;
        if($oldDoc->contentType == 'markdown') $doc->content = str_replace('&gt;', '>', $doc->content);

        $lib = $this->getLibByID($doc->lib);
        $doc = $this->loadModel('file')->processImgURL($doc, $this->config->doc->editor->edit['id'], $this->post->uid);
        $doc->product = $lib->product;
        $doc->project = $lib->project;
        if($doc->type == 'url') $doc->content = $doc->url;
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
            $oldDocContent = $this->dao->select('files,type')->from(TABLE_DOCCONTENT)->where('doc')->eq($docID)->andWhere('version')->eq($oldDoc->version)->fetch();
            $doc->version  = $oldDoc->version + 1;
            $docContent = new stdclass();
            $docContent->doc     = $docID;
            $docContent->title   = $doc->title;
            $docContent->content = $doc->content;
            $docContent->version = $doc->version;
            $docContent->digest  = $doc->digest;
            $docContent->type    = $oldDocContent->type;
            $docContent->files   = $oldDocContent->files;
            if($files) $docContent->files .= ',' . join(',', array_keys($files));
            $docContent->files   = trim($docContent->files, ',');
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
        }
        unset($doc->content);
        unset($doc->contentType);

        $this->dao->update(TABLE_DOC)->data($doc)
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
        $this->config->doc->search['params']['product']['values'] = array(''=>'') + $this->loadModel('product')->getPairs('nocode') + array('all'=>$this->lang->doc->allProduct);
        $this->config->doc->search['params']['project']['values'] = array(''=>'') + $this->loadModel('project')->getPairs('nocode') + array('all'=>$this->lang->doc->allProject);
        $this->config->doc->search['params']['lib']['values']     = array(''=>'', $libID => $libs[$libID], 'all' => $this->lang->doclib->all);

        /* Get the modules. */
        $moduleOptionMenu = $this->loadModel('tree')->getOptionMenu($libID, 'doc', $startModuleID = 0);
        $this->config->doc->search['params']['module']['values'] = $moduleOptionMenu;

        $this->loadModel('search')->setSearchParams($this->config->doc->search);
    }

    /**
     * Get pairs of project modules.
     * 
     * @access public
     * @return array
     */
    public function getProjectModulePairs()
    {
        return $this->dao->select('t1.id,t1.name')->from(TABLE_MODULE)->alias('t1')
            ->leftJoin(TABLE_DOCLIB)->alias('t2')->on('t1.root = t2.id')
            ->andWhere('t1.type')->eq('doc')
            ->andWhere('t2.project')->ne('0')
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
    public function getDocMenu($libID, $parent, $orderBy = 'name_asc')
    {
        return $this->dao->select('*')->from(TABLE_MODULE)->where('root')->eq($libID)
            ->andWhere('type')->eq('doc')
            ->andWhere('parent')->eq($parent)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->fetchAll('id');
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
     * Check priv.
     * 
     * @param  object $object 
     * @access public
     * @return bool
     */
    public function checkPriv($object, $type = 'lib')
    {
        if($object->acl == 'open') return true;

        $account = ',' . $this->app->user->account . ',';
        if(isset($object->addedBy) and $object->addedBy == $this->app->user->account) return true;
        if($this->app->user->admin) return true;
        if($object->acl == 'private' and strpos(",$object->users,", $account) !== false) return true;
        if($object->acl == 'custom')
        {
            if(strpos(",$object->users,", $account) !== false) return true;

            $userGroups = $this->app->user->groups;
            foreach($userGroups as $groupID)
            {
                if(strpos(",$object->groups,", ",$groupID,") !== false) return true;
            }
        }
        
        if(isset($object->lib))
        {
            static $libs;
            if(empty($libs)) $libs = $this->getLibs('all');
            if(!isset($libs[$object->lib])) return false;
            
        }

        if($object->project)
        {
            static $projects;
            if(empty($projects)) $projects = $this->loadModel('project')->getPairs();
            return isset($projects[$object->project]);
        }

        if($object->product)
        {
            static $products;
            if(empty($products)) $products = $this->loadModel('product')->getPairs();
            return isset($products[$object->product]);
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
        if($product and $type == 'project') $projects = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('product')->eq($product)->fetchPairs('project', 'project');

        $libs = $this->getLibs($type);
        $key  = ($type == 'product' or $type == 'project') ? $type : 'id';
        $stmt = $this->dao->select("DISTINCT $key")->from(TABLE_DOCLIB)->where('deleted')->eq(0);
        if($type == 'product' or $type == 'project')
        {
            $stmt = $stmt->andWhere($type)->ne(0);
        }
        else
        {
            $stmt = $stmt->andWhere('project')->eq(0)->andWhere('product')->eq(0);
        }
        if(isset($projects)) $stmt = $stmt->andWhere('project')->in($projects);

        $idList = $stmt->andWhere('id')->in(array_keys($libs))->orderBy("{$key}_desc")->page($pager, $key)->fetchPairs($key, $key);

        if($type == 'product' or $type == 'project')
        {
            $table = $type == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
            $libs = $this->dao->select('id,name,`order`')->from($table)->where('id')->in($idList)->orderBy('`order` desc, id desc')->fetchAll('id');
        }
        else
        {
            $libs = $this->dao->select('id,name')->from(TABLE_DOCLIB)->where('id')->in($idList)->orderBy('`order`, id desc')->fetchAll('id');
        }

        return $libs;
    }

    /**
     * Get all lib groups.
     * 
     * @access public
     * @return array
     */
    public function getAllLibGroups()
    {
        $libs = $this->getLibs('all');
        $stmt = $this->dao->select("id,product,project,name")->from(TABLE_DOCLIB)
            ->where('deleted')->eq(0)
            ->andWhere("id")->in(array_keys($libs))
            ->orderBy("product desc,project desc, `order` asc, id asc")
            ->query();

        $customLibs  = array();
        $productLibs = array();
        $projectLibs = array();
        while($lib = $stmt->fetch())
        {
            if($lib->product)
            {
                $productLibs[$lib->product][$lib->id] = $lib->name;
            }
            elseif($lib->project)
            {
                $projectLibs[$lib->project][$lib->id] = $lib->name;
            }
            else
            {
                $customLibs[$lib->id] = $lib->name;
            }
        }
        $productIdList = array_keys($productLibs);
        $products      = $this->dao->select('id,name,status')->from(TABLE_PRODUCT)->where('id')->in($productIdList)->andWhere('deleted')->eq('0')->orderBy('`order`_desc')->fetchAll();
        if($this->config->global->flow == 'onlyStory' or $this->config->global->flow == 'onlyTest')
        {
            $hasProject = array();
        }
        else
        {
            $hasProject = $this->dao->select('DISTINCT product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.product')->in($productIdList)
                ->andWhere('t2.deleted')->eq(0)
                ->fetchPairs('product', 'product');
        }

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
                if(isset($hasProject[$productID]) and $hasLibsPriv) $productOrderLibs[$productID]['libs']['project'] = $this->lang->doclib->project;
                if($hasFilesPriv) $productOrderLibs[$productID]['libs']['files'] = $this->lang->doclib->files;
            }
        }
        $projects = $this->dao->select('id,name,status')->from(TABLE_PROJECT)->where('id')->in(array_keys($projectLibs))->andWhere('deleted')->eq('0')->orderBy('`order`_desc')->fetchAll();
        $projectOrderLibs = array();
        foreach($projects as $project)
        {
            $projectID   = $project->id;
            $projectName = $project->name;
            if(isset($projectLibs[$projectID]))
            {
                $projectOrderLibs[$projectID]['id']     = $projectID;
                $projectOrderLibs[$projectID]['name']   = $projectName;
                $projectOrderLibs[$projectID]['status'] = $project->status;
                foreach($projectLibs[$projectID] as $libID => $libName) $projectOrderLibs[$projectID]['libs'][$libID] = $libName;
                if($hasFilesPriv) $projectOrderLibs[$projectID]['libs']['files'] = $this->lang->doclib->files;
            }
        }

        return array('product' => array_values($productOrderLibs), 'project' => array_values($projectOrderLibs), 'custom' => $customLibs);
    }

    /**
     * Get limit libs.
     * 
     * @param  string $type 
     * @param  int    $limit 
     * @access public
     * @return array
     */
    public function getLimitLibs($type, $limit = 4)
    {
        if($type == 'project' and ($this->config->global->flow == 'onlyStory' or $this->config->global->flow == 'onlyTest')) return array();
        if($type == 'product' and $this->config->global->flow == 'onlyTask')  return array();

        if($type == 'product' or $type == 'project')
        {
            $table = $type == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
            $stmt  = $this->dao->select('t1.*')->from(TABLE_DOCLIB)->alias('t1')
                ->leftJoin($table)->alias('t2')->on("t1.$type=t2.id")
                ->where('t1.deleted')->eq(0)->andWhere("t1.$type")->ne(0)
                ->orderBy("t2.`order` desc, t1.`order` asc, id desc")
                ->query();
        }
        else
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere('project')->eq(0)->andWhere('product')->eq(0)->orderBy('`order`, id desc')->query();
        }

        $i    = 1;
        $libs = array();
        while($docLib = $stmt->fetch())
        {
            if($i > $limit) break;
            $key = ($type == 'product' or $type == 'project') ? $type : 'id';
            if($this->checkPriv($docLib) and !isset($libs[$docLib->$key]))
            {
                $libs[$docLib->$key] = $docLib->name;
                $i++;
            }
        }

        if($type == 'product' or $type == 'project') $libs = $this->dao->select('id,name,`order`')->from($table)->where('id')->in(array_keys($libs))->orderBy('`order` desc, id desc')->fetchAll('id');

        return $libs;
    }

    /**
     * Get project or product libs groups. 
     * 
     * @param  string $type 
     * @param  array  $idList 
     * @param  int    $limit 
     * @access public
     * @return array
     */
    public function getSubLibGroups($type, $idList)
    {
        if($type != 'product' and $type != 'project') return false;
        $libGroups   = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere($type)->in($idList)->orderBy('`order`, id')->fetchGroup($type, 'id');
        if($type == 'product')
        {
            if($this->config->global->flow == 'onlyStory' or $this->config->global->flow == 'onlyTest')
            {
                $hasProject = array();
            }
            else
            {
                $hasProject = $this->dao->select('DISTINCT product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                    ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                    ->where('t1.product')->in($idList)
                    ->andWhere('t2.deleted')->eq(0)
                    ->fetchPairs('product', 'product');
            }
        }
        $buildGroups = array();
        foreach($libGroups as $objectID => $libs)
        {
            foreach($libs as $lib)
            {
                if($this->checkPriv($lib)) $buildGroups[$objectID][$lib->id] = $lib->name;
            }
            if($type == 'product' and isset($hasProject[$objectID]) and common::hasPriv('doc', 'allLibs')) $buildGroups[$objectID]['project'] = $this->lang->doclib->project;
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
        if($type != 'product' and $type != 'project') return false;
        $objectLibs   = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere($type)->eq($objectID)->orderBy('`order`, id')->fetchAll('id');
        if($type == 'product')
        {
            if($this->config->global->flow == 'onlyStory' or $this->config->global->flow == 'onlyTest')
            {
                $hasProject = array();
            }
            else
            {
                $hasProject  = $this->dao->select('DISTINCT product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                    ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                    ->where('t1.product')->eq($objectID)
                    ->andWhere('t2.deleted')->eq(0)
                    ->fetchPairs('product', 'product');
            }
        }
        $libs = array();
        foreach($objectLibs as $lib)
        {
            if($this->checkPriv($lib)) $libs[$lib->id] = $lib->name;
        }

        if(strpos($mode, 'onlylib') === false)
        {
            if($type == 'product' and isset($hasProject[$objectID]) and common::hasPriv('doc', 'allLibs')) $libs['project'] = $this->lang->doclib->project;
            if(common::hasPriv('doc', 'showFiles')) $libs['files'] = $this->lang->doclib->files;
        }

        return $libs;
    }

    /**
     * Get lib files.
     * 
     * @param  string $type 
     * @param  int    $objectID 
     * @access public
     * @return array
     */
    public function getLibFiles($type, $objectID, $pager = null)
    {
        if($type != 'project' and $type != 'product') return true;
        $this->loadModel('file');
        $docIdList   = $this->dao->select('id')->from(TABLE_DOC)->where($type)->eq($objectID)->get();
        $searchTitle = $this->get->title;
        if($type == 'product')
        {
            $storyIdList   = $this->dao->select('id')->from(TABLE_STORY)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->get();
            $bugIdList     = $this->dao->select('id')->from(TABLE_BUG)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->get();
            $releaseIdList = $this->dao->select('id')->from(TABLE_RELEASE)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->get();
            $planIdList    = $this->dao->select('id')->from(TABLE_PRODUCTPLAN)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->get();
            $files = $this->dao->select('*')->from(TABLE_FILE)->alias('t1')
                ->where('size')->gt('0')
                ->andWhere("(objectType = 'product' and objectID = $objectID)", true)
                ->orWhere("(objectType = 'doc' and objectID in ($docIdList))")
                ->orWhere("(objectType = 'story' and objectID in ($storyIdList))")
                ->orWhere("(objectType = 'bug' and objectID in ($bugIdList))")
                ->orWhere("(objectType = 'release' and objectID in ($releaseIdList))")
                ->orWhere("(objectType = 'productplan' and objectID in ($planIdList))")
                ->markRight(1)
                ->beginIF($searchTitle)->andWhere('title')->like("%{$searchTitle}%")->fi()
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($type == 'project')
        {
            $taskIdList  = $this->dao->select('id')->from(TABLE_TASK)->where('project')->eq($objectID)->andWhere('deleted')->eq('0')->get();
            $buildIdList = $this->dao->select('id')->from(TABLE_BUILD)->where('project')->eq($objectID)->andWhere('deleted')->eq('0')->get();
            $files = $this->dao->select('*')->from(TABLE_FILE)->alias('t1')
                ->where('size')->gt('0')
                ->andWhere("(objectType = 'project' and objectID = $objectID)", true)
                ->orWhere("(objectType = 'doc' and objectID in ($docIdList))")
                ->orWhere("(objectType = 'task' and objectID in ($taskIdList))")
                ->orWhere("(objectType = 'build' and objectID in ($buildIdList))")
                ->markRight(1)
                ->beginIF($searchTitle)->andWhere('title')->like("%{$searchTitle}%")->fi()
                ->page($pager)
                ->fetchAll('id');
        }

        foreach($files as $fileID => $file)
        {
            $pathName = $this->file->getRealPathName($file->pathname);
            $file->realPath = $this->file->savePath . $pathName;
            $file->webPath  = $this->file->webPath . $pathName;
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
                if($this->checkPriv($doc)) $docGroups[$doc->module][$doc->id] = $doc;
            }
        }

        if(!empty($node->children)) foreach($node->children as $i => $child) $node->children[$i] = $this->fillDocsInTree($child, $libID);
        if(!isset($node->id))$node->id = 0;

        $node->type = 'module';
        $docs = isset($docGroups[$node->id]) ? $docGroups[$node->id] : array();
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
                $docItems[] = $docItem;
            }
            $node->docsCount = count($docItems);
            $node->children  = $docItems;
        }

        $node->actions = false;
        return $node;
    }

    /**
     * Get crumbs. 
     * 
     * @param  int    $libID 
     * @param  int    $moduleID 
     * @param  int    $docID 
     * @access public
     * @return string
     */
    public function getCrumbs($libID, $moduleID = 0)
    {
        if(empty($libID)) return '';

        $lib = $this->getLibById($libID);
        if(!$this->checkPriv($lib)) 
        {
            echo(js::alert($this->lang->doc->accessDenied));
            $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
            if(strpos($this->server->http_referer, $loginLink) !== false) die(js::locate(inlink('index')));
            die(js::locate('back'));
        }

        $type = $lib->product ? 'product' : 'custom';
        $type = $lib->project ? 'project' : $type;

        $mainLib = $type == 'product' ? $this->lang->productCommon : $this->lang->doc->customAB;
        $mainLib = $type == 'project' ? $this->lang->projectCommon : $mainLib;

        $crumb     = '';
        $productID = $this->cookie->product ? $this->cookie->product : '0';
        if($productID and $type == 'project' and $this->config->global->flow != 'onlyTask')
        {
            $crumb .= $this->getProductCrumb($productID, $lib->project);
        }
        else
        {
            $crumb .= html::a(helper::createLink('doc', 'allLibs', "type=$type&product=$productID"), $mainLib) . $this->lang->doc->separator;
        }
        if($lib->product or $lib->project)
        {
            $table    = $lib->product ? TABLE_PRODUCT : TABLE_PROJECT;
            $objectID = $lib->product ? $lib->product : $lib->project;
            $object   = $this->dao->select('id,name')->from($table)->where('id')->eq($objectID)->fetch();
            if($object) $crumb .= html::a(helper::createLink('doc', 'objectLibs', "type=$type&objectID=$objectID"), $object->name) . $this->lang->doc->separator;
        }

        $parents = $moduleID ? $this->loadModel('tree')->getParents($moduleID) : array();
        $crumb  .= html::a(helper::createLink('doc', 'browse', "libID=$libID&browseType=all&param=0&orderBy=id_desc"), $lib->name);
        foreach($parents as $module) $crumb .= $this->lang->doc->separator . html::a(helper::createLink('doc', 'browse', "libID=$libID&browseType=byModule&param=$module->id&orderBy=id_desc"), $module->name);
        return $crumb;
    }

    /**
     * Get product crumb.
     * 
     * @param  int    $productID 
     * @param  int    $projectID 
     * @access public
     * @return string
     */
    public function getProductCrumb($productID, $projectID = 0)
    {
        if(empty($productID)) return '';
        if($projectID)
        {
            $projectProduct = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('product')->eq($productID)->andWhere('project')->eq($projectID)->fetch();
            if(empty($projectProduct))
            {
                setcookie('product', 0, $this->config->cookieLife, $this->config->webRoot);
                return html::a(helper::createLink('doc', 'allLibs', "type=project"), $this->lang->projectCommon) . $this->lang->doc->separator;
            }
        }
        $object = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->eq($productID)->fetch();
        if(empty($object)) return '';

        $crumb  = '';
        $crumb .= html::a(helper::createLink('doc', 'allLibs', "type=product"), $this->lang->productCommon) . $this->lang->doc->separator;
        $crumb .= html::a(helper::createLink('doc', 'objectLibs', "type=product&objectID=$productID"), $object->name) . $this->lang->doc->separator;
        $crumb .= html::a(helper::createLink('doc', 'allLibs', "type=project&product=$productID"), $this->lang->doclib->project);
        if($projectID) $crumb .= $this->lang->doc->separator;
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
        if($type != 'project' and $type != 'product') return array();
        if($type == 'product')
        {
            $teams = $this->dao->select('t1.account')->from(TABLE_TEAM)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.project=t2.project')
                ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project=t3.id')
                ->where('t2.product')->eq($objectID)
                ->andWhere('t3.deleted')->eq('0')
                ->fetchPairs('account', 'account');
        }
        elseif($type == 'project')
        {
            $teams = $this->dao->select('account')->from(TABLE_TEAM)->where('project')->eq($objectID)->fetchPairs('account', 'account');
        }

        return $teams;
    }
}
