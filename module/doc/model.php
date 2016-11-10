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
    public function setMenu($libs, $libID = 0, $moduleID = 0)
    {
        if($libID)
        {
            $lib = $this->getLibById($libID);
            if(!$this->checkPriv($lib)) 
            {
                echo(js::alert($this->lang->doc->accessDenied));
                die(js::locate('back'));
            }

            if($lib->product or $lib->project)
            {
                $table    = $lib->product ? TABLE_PRODUCT : TABLE_PROJECT;
                $objectID = $lib->product ? $lib->product : $lib->project;
                $object   = $this->dao->select('id,name')->from($table)->where('id')->eq($objectID)->fetch();
                if($object) $libs[$libID] = $object->name . ' / ' . $lib->name;
            }
        }

        $selectHtml = "<a id='currentItem' data-lib-id='$libID' href=\"javascript:showLibMenu()\">{$libs[$libID]} <span class='icon-caret-down'></span></a><div id='dropMenu'><i class='icon icon-spin icon-spinner'></i><div id='libMenu'><div id='libMenuHeading'><input id='searchLib' type='search' placeholder='{$this->lang->doc->searchDoc}' class='form-control'></div><div id='libMenuGroups' class='clearfix'><div class='lib-menu-group' id='libMenuProductGroup'><div class='lib-menu-list-heading' data-type='product'>{$this->lang->doc->libTypeList['product']}<i class='icon icon-remove'></i></div><div class='lib-menu-list clearfix'></div></div><div class='lib-menu-group' id='libMenuProjectGroup'><div class='lib-menu-list-heading' data-type='project'>{$this->lang->doc->libTypeList['project']}<i class='icon icon-remove'></i></div><div class='lib-menu-list clearfix'></div></div><div class='lib-menu-group' id='libMenuCustomGroup'><div class='lib-menu-list-heading' data-type='custom'>{$this->lang->doc->libTypeList['custom']}<i class='icon icon-remove'></i></div><div class='lib-menu-list clearfix'></div></div></div></div></div>";
        common::setMenuVars($this->lang->doc->menu, 'list', $selectHtml);
        common::setMenuVars($this->lang->doc->menu, 'crumb', $this->getCrumbs($libID, $moduleID));
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
     * Get lib by object.
     * 
     * @param  string $type 
     * @param  int    $objectID 
     * @access public
     * @return array
     */
    public function getLibByObject($type, $objectID)
    {
        return $this->dao->select('*')->from(TABLE_DOCLIB)->where($type)->eq($objectID)->fetchAll('id');
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
                ->orderBy("t2.order desc, t1.id")
                ->query();
        }
        else
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere('product')->eq('0')->andWhere('project')->eq(0)->orderBy('id desc')->query();
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
        $libID = $this->dao->lastInsertID();

        $libType = $this->post->libType;
        if($lib->acl != 'private' and ($libType == 'project' or $libType == 'product')) $this->setLibUsers($libType, $lib->$libType);

        return $libID;
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
            $condition = $this->buildConditionSQL();
            $docs = $this->dao->select('*')->from(TABLE_DOC)
                ->where('deleted')->eq(0)
                ->andWhere('lib')->in($libID)
                ->andWhere('addedBy')->eq($this->app->user->account)
                ->beginIF($condition)->andWhere("($condition)")->fi()
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
            $condition = $this->buildConditionSQL();
            $docs = $this->dao->select('*')->from(TABLE_DOC)->where('deleted')->eq(0)->andWhere('lib')->in($libID)
                ->andWhere('module')->eq($moduleID)
                ->beginIF($condition)->andWhere("($condition)")->fi()
                ->orderBy($sort)
                ->page($pager)
                ->fetchAll();
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

            $condition = $this->buildConditionSQL();
            $libIdList = $this->dao->select('id')->from(TABLE_DOCLIB)->beginIF($condition)->where("($condition)")->fi()->get();

            $docs = $this->dao->select('*')->from(TABLE_DOC)->where($docQuery)
                ->beginIF($condition)->andWhere("($condition)")->fi()
                ->beginIF(!$libCond)->andWhere("lib")->eq($libID)->fi()
                ->beginIF($allLibCond and $condition)->andWhere("lib in ($libIdList)")->fi()
                ->andWhere('deleted')->eq(0)
                ->orderBy($sort)->page($pager)
                ->fetchAll();
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
    public function getDocs($libID, $module, $orderBy, $pager)
    {
        $condition = $this->buildConditionSQL();
        return $this->dao->select('*')->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->andWhere('lib')->in($libID)
            ->beginIF($condition)->andWhere("($condition)")->fi()
            ->beginIF($module)->andWhere('module')->in($module)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
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
        if(!$doc or !$this->checkPriv($doc)) return false;
        $version = $version ? $version : $doc->version;
        $docContent = $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq($doc->id)->andWhere('version')->eq($version)->fetch();

        /* When file change then version add one. */
        $files = $this->loadModel('file')->getByObject('doc', $docID);
        $docFiles = array();
        foreach($files as $file)
        {
            $file->webPath  = $this->file->webPath . $file->pathname;
            $file->realPath = $this->file->savePath . $file->pathname;
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
        if($setImgSize) $doc->content = $this->loadModel('file')->setImgSize($doc->content);
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
        $doc = $this->loadModel('file')->processEditor($doc, $this->config->doc->editor->create['id'], $this->post->uid);
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
        $oldDoc = $this->getById($docID);
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
        $doc = $this->loadModel('file')->processEditor($doc, $this->config->doc->editor->edit['id'], $this->post->uid);
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
        $moduleOptionMenu = $this->loadModel('tree')->getOptionMenu($libID . 'doc', $startModuleID = 0);
        $this->config->doc->search['params']['module']['values'] = $moduleOptionMenu;

        $this->loadModel('search')->setSearchParams($this->config->doc->search);
    }
 
    /**
     * Get docs of a product.
     * 
     * @param  int    $productID 
     * @access public
     * @return array
     */
    public function getProductDocs($productID)
    {
        $condition = $this->buildConditionSQL('t1');
        return $this->dao->select('t1.*, t2.name as module')->from(TABLE_DOC)->alias('t1')
            ->leftjoin(TABLE_MODULE)->alias('t2')->on('t1.module = t2.id')
            ->where('t1.product')->eq($productID)
            ->beginIF($condition)->andWhere("($condition)")->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.id_desc')
            ->fetchAll();
    }

    /**
     * Get docs of a project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getProjectDocs($projectID)
    {
        $condition = $this->buildConditionSQL();
        return $this->dao->select('*')->from(TABLE_DOC)
            ->where('project')->eq($projectID)
            ->beginIF($condition)->andWhere("($condition)")->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy('id_desc')
            ->fetchAll();
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
    public function checkPriv($object)
    {
        if($object->acl == 'open') return true;

        $account = ',' . $this->app->user->account . ',';
        if(strpos($this->app->company->admins, $account) !== false) return true;
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

        $key = ($type == 'product' or $type == 'project') ? $type : 'id';
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

        $condition = $this->buildConditionSQL();
        $idList = $stmt->beginIF($condition)->andWhere("($condition)")->fi()->orderBy("{$key}_desc")->page($pager, $key)->fetchPairs($key, $key);

        if($type == 'product' or $type == 'project')
        {
            $table = $type == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
            $libs = $this->dao->select('id,name,`order`')->from($table)->where('id')->in($idList)->orderBy('`order` desc, id desc')->fetchAll('id');
        }
        else
        {
            $libs = $this->dao->select('id,name')->from(TABLE_DOCLIB)->where('id')->in($idList)->orderBy('id desc')->fetchAll('id');
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
        $condition = $this->buildConditionSQL();
        $stmt = $this->dao->select("id,product,project,name")->from(TABLE_DOCLIB)
            ->where('deleted')->eq(0)
            ->beginIF($condition)->andWhere("($condition)")->fi()
            ->orderBy("product desc,project desc, id asc")
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
        $products      = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->in($productIdList)->andWhere('deleted')->eq('0')->orderBy('`order`_desc')->fetchPairs('id', 'name');
        $hasProject    = $this->dao->select('DISTINCT product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.product')->in($productIdList)
            ->andWhere('t2.deleted')->eq(0)
            ->fetchPairs('product', 'product');

        $hasLibsPriv  = common::hasPriv('doc', 'allLibs');
        $hasFilesPriv = common::hasPriv('doc', 'showFiles');
        $productOrderLibs = array();
        foreach($products as $productID => $productName)
        {
            if(isset($productLibs[$productID]))
            {
                $productOrderLibs[$productID]['id']   = $productID;
                $productOrderLibs[$productID]['name'] = $productName;
                foreach($productLibs[$productID] as $libID => $libName) $productOrderLibs[$productID]['libs'][$libID] = $libName;
                if(isset($hasProject[$productID]) and $hasLibsPriv) $productOrderLibs[$productID]['libs']['project'] = $this->lang->doc->systemLibs['project'];
                if($hasFilesPriv) $productOrderLibs[$productID]['libs']['files'] = $this->lang->doclib->files;
            }
        }
        $projects = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in(array_keys($projectLibs))->andWhere('deleted')->eq('0')->orderBy('`order`_desc')->fetchPairs('id', 'name');
        $projectOrderLibs = array();
        foreach($projects as $projectID => $projectName)
        {
            if(isset($projectLibs[$projectID]))
            {
                $projectOrderLibs[$projectID]['id']   = $projectID;
                $projectOrderLibs[$projectID]['name'] = $projectName;
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
        if($type == 'product' or $type == 'project')
        {
            $table = $type == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
            $stmt  = $this->dao->select('t1.*')->from(TABLE_DOCLIB)->alias('t1')
                ->leftJoin($table)->alias('t2')->on("t1.$type=t2.id")
                ->where('t1.deleted')->eq(0)->andWhere("t1.$type")->ne(0)
                ->orderBy("t2.`order` desc, id desc")
                ->query();
        }
        else
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere('project')->eq(0)->andWhere('product')->eq(0)->orderBy('id desc')->query();
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
        $libGroups   = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere($type)->in($idList)->orderBy('id')->fetchGroup($type, 'id');
        if($type == 'product')
        {
            $hasProject  = $this->dao->select('DISTINCT product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.product')->in($idList)
                ->andWhere('t2.deleted')->eq(0)
                ->fetchPairs('product', 'product');
        }
        $buildGroups = array();
        foreach($libGroups as $objectID => $libs)
        {
            foreach($libs as $lib)
            {
                if($this->checkPriv($lib)) $buildGroups[$objectID][$lib->id] = $lib->name;
            }
            if($type == 'product' and isset($hasProject[$objectID]) and common::hasPriv('doc', 'allLibs')) $buildGroups[$objectID]['project'] = $this->lang->doc->systemLibs['project'];
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
        $objectLibs   = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere($type)->eq($objectID)->orderBy('id')->fetchAll('id');
        if($type == 'product')
        {
            $hasProject  = $this->dao->select('DISTINCT product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.product')->eq($objectID)
                ->andWhere('t2.deleted')->eq(0)
                ->fetchPairs('product', 'product');
        }
        $libs = array();
        foreach($objectLibs as $lib)
        {
            if($this->checkPriv($lib)) $libs[$lib->id] = $lib->name;
        }

        if(strpos($mode, 'onlylib') === false)
        {
            if($type == 'product' and isset($hasProject[$objectID]) and common::hasPriv('doc', 'allLibs')) $libs['project'] = $this->lang->doc->systemLibs['project'];
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
        $this->loadModel('file');
        $docIdList = $this->dao->select('id')->from(TABLE_DOC)->where($type)->eq($objectID)->get();
        $files = $this->dao->select('*')->from(TABLE_FILE)
            ->where("(objectType = '$type' and objectID = '$objectID')")
            ->orWhere("(objectType = 'doc' and objectID in ($docIdList))")
            ->andWhere('size')->gt('0')
            ->page($pager)
            ->fetchAll('id');

        foreach($files as $fileID => $file)
        {
            $file->realPath = $this->file->savePath . $file->pathname;
            $file->webPath  = $this->file->webPath . $file->pathname;
        }

        return $files;
    }

    /**
     * Build condition SQL for priv.
     * 
     * @param  string $table 
     * @access public
     * @return string
     */
    public function buildConditionSQL($table = '')
    {
        if($table) $table .= '.';
        $condition = '';
        $account   = ',' . $this->app->user->account . ',';
        if(strpos($this->app->company->admins, $account) === false)
        {
            $condition .= "{$table}acl='open'";
            $condition .= " OR ({$table}acl='private' and CONCAT(',', {$table}users, ',') like '%$account%')";
            $condition .= " OR ({$table}acl='custom' and (";
            foreach($this->app->user->groups as $groupID) $condition .= "(CONCAT(',', {$table}groups, ',') like '%,$groupID,%') OR ";
            $condition .= "(CONCAT(',', {$table}users, ',') like '%$account%')";
            $condition .= "))";
        }
        return $condition;
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
            $condition = $this->buildConditionSQL();
            $docs = $this->dao->select('*')->from(TABLE_DOC)
                ->where('lib')->eq((int)$libID)
                ->beginIF($condition)->andWhere("($condition)")->fi()
                ->andWhere('deleted')->eq(0)
                ->fetchAll();
            $docGroups = array();
            foreach($docs as $doc) $docGroups[$doc->module][$doc->id] = $doc;
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
     * Diff doc. 
     * 
     * @param  string $text1 
     * @param  string $text2 
     * @access public
     * @return array
     */
    public function diff($text1, $text2)
    {
        $text1     = explode("\n", trim($text1));
        $text2     = explode("\n", trim($text2));
        $text1Len  = count($text1);
        $text2Len  = count($text2);
        $trace     = array();
        $trace[-1] = array_fill(-1, $text2Len + 1, +1);
        $lcs0      = array_fill(-1, $text2Len + 1, 0);
        $lcs1[-1]  = 0;

        foreach($text1 as $line)
        {
            $temp     = array();
            $temp[-1] = -1;
            for($i = 0; $i < $text2Len; $i++)
            {
                if($line == $text2[$i])
                {
                    $lcs1[$i] = $lcs0[$i - 1] + 1;
                    $temp[$i] = 0;
                }
                elseif($lcs0[$i] > $lcs1[$i - 1])
                {
                    $lcs1[$i] = $lcs0[$i];
                    $temp[$i] = -1;
                }
                else
                {
                    $lcs1[$i] = $lcs1[$i - 1];
                    $temp[$i] = +1;
                }
            }
            array_push($trace, $temp);
            $temp = $lcs1;
            $lcs1 = $lcs0;
            $lcs0 = $temp;
        }

        $same = 0;
        $out  = false;
        $ret  = array();
        for($i = $text1Len - 1, $j = $text2Len - 1; $i != -1 || $j != -1;)
        {
            if($trace[$i][$j] == 0)
            {
                $same++;
                if($out && $same > 0)
                {
                    $outbegina = $i + $same;
                    $outbeginb = $j + $same;
                    for($k = $i + $same - 1; $k >= $outbegina; $k--) array_push($ret, array('cmd' => ' ', 'dat' => $text1[$k]));
                    $dat = array($outbegina, $outenda - $outbegina + 1, $outbeginb, $outendb - $outbeginb + 1);
                    array_push($ret, array('cmd' => '@', 'dat'=> $dat));
                    $out = false;
                }
                $i--;
                $j--;
            }
            else
            {
                if(!$out)
                {
                    $same    = min($same, 0);
                    $out     = true;
                    $outenda = $i + $same;
                    $outendb = $j + $same;
                }
                for($k = $i + $same; $k > $i; $k--) array_push($ret, array('cmd' => ' ', 'dat' => $text1[$k]));
                $same = 0;
                if($trace[$i][$j] == -1)
                {
                    array_push($ret, array('cmd' => '-', 'dat' => $text1[$i]));
                    $i--;
                }
                else
                {
                    array_push($ret, array('cmd' => '+', 'dat' => $text2[$j]));
                    $j--;
                }
            }
        }

        if($out)
        {
            $outbegina = max(0, $same);
            $outbeginb = max(0, $same);
            for($k = $same - 1; $k >= $outbegina; $k--) array_push($ret, array('cmd' => ' ', 'dat' => $text1[$k]));
            $dat = array($outbegina, $outenda - $outbegina + 1, $outbeginb, $outendb - $outbeginb + 1);
            array_push($ret, array('cmd' => '@', 'dat'=> $dat));
        }

        $diffs = array_reverse($ret);  
        if(empty($diffs)) return array();

        $processedDiff = array();
        foreach($diffs as $i => $diff)
        {
            if($diff['cmd'] == '@')
            {
                $max = max($diff['dat'][1], $diff['dat'][3]);
                for($number = 0; $number < $max; $number++)
                {
                    $oldLineNO = $diff['dat'][0] + $number;
                    $newLineNO = $diff['dat'][2] + $number;
                    $oldLine = '';
                    $newLine = '';
                    if($diff['dat'][1] > 0 and $diff['dat'][1] >= $number + 1) $oldLine = $diffs[$i + $number + 1]['dat'];
                    if($diff['dat'][3] > 0 and $diff['dat'][3] >= $number + 1) $newLine = $diffs[$i + $number + 1 + $diff['dat'][1]]['dat'];
                    $processedDiff['old'][$oldLineNO] = $oldLine;
                    $processedDiff['new'][$newLineNO] = $newLine;
                }

            }
        }
        return $processedDiff;
    }

    /**
     * Get line number by diff.
     * 
     * @param  array  $diff 
     * @param  int    $i 
     * @param  int    $lineNO 
     * @access public
     * @return array
     */
    public function getLineNumber($diff, $i, $lineNO)
    {
        $action = '';
        if(isset($diff[$i]))
        {
            if(empty($diff[$i]))
            {
                $showNumber = '';
                $action     = '';
            }
            else
            {
                $showNumber = $lineNO + 1;
                $action     = 'diff';
            }
        }
        else
        {
            $showNumber = $lineNO + 1;
            $action     = '';
        }

        return array($showNumber, $action, 'number' => $showNumber, 'action' => $action);
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

        $parents = $moduleID ? $this->loadModel('tree')->getParents($moduleID) : array();
        $crumb   = html::a(helper::createLink('doc', 'browse', "libID=$libID&browseType=all&param=0&orderBy=id_desc"), '&nbsp;/&nbsp;');
        foreach($parents as $module) $crumb .= $this->lang->arrow . html::a(helper::createLink('doc', 'browse', "libID=$libID&browseType=byModule&param=$module->id&orderBy=id_desc"), $module->name);
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
        if($type != 'project' and $type != 'product') return true;

        $libs  = $this->dao->select('*')->from(TABLE_DOCLIB)->where($type)->eq($objectID)->fetchAll();

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

        foreach($libs as $lib)
        {
            foreach(explode(',', $lib->users) as $account) $teams[$account] = $account;
            $this->dao->update(TABLE_DOCLIB)->set('users')->eq(join(',', $teams))->where('id')->eq($lib->id)->exec();
        }
        return true;
    }
}
