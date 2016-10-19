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
     * @param  string $extra 
     * @access public
     * @return void
     */
    public function setMenu($libs, $libID = 0, $extra = 'product')
    {
        $currentModule = 'doc';
        $currentMethod = 'browse';
        if($libID)
        {
            $lib = $this->getLibById($libID);
            if(!$this->checkPriv($lib)) 
            {
                echo(js::alert($this->lang->doc->accessDenied));
                die(js::locate('back'));
            }
        }

        $selectHtml = "<a id='currentItem' href=\"javascript:showDropMenu('doc', '$libID', '$currentModule', '$currentMethod', '$extra')\">{$libs[$libID]} <span class='icon-caret-down'></span></a><div id='dropMenu'><i class='icon icon-spin icon-spinner'></i></div>";
        common::setMenuVars($this->lang->doc->menu, 'list', $selectHtml);
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
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)->where($type)->ne(0)->andWhere('deleted')->eq(0)->orderBy('id desc')->query();
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
            $docQuery  = str_replace("`product` = 'all'", '1', $this->session->docQuery); // Search all producti.
            $docQuery  = str_replace("`project` = 'all'", '1', $docQuery);                // Search all project.
            $condition = $this->buildConditionSQL();
            $docs      = $this->dao->select('*')->from(TABLE_DOC)->where($docQuery)
                ->beginIF($condition)->andWhere("($condition)")->fi()
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

        if($version == $doc->version and ((empty($docContent->files) and $docFiles) OR ($docContent->files and count(explode(',', $docContent->files)) != count($docFiles))))
        {
            unset($docContent->id);
            $doc->version        += 1;
            $docContent->version  = $doc->version;
            $docContent->files    = join(',', array_keys($docFiles));
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
            $this->dao->update(TABLE_DOC)->set('version')->eq($doc->version)->where('id')->eq($doc->id)->exec();
        }

        $doc->digest  = isset($docContent->digest)  ? $docContent->digest  : '';
        $doc->content = isset($docContent->content) ? $docContent->content : '';
        $doc->type    = isset($docContent->type)    ? $docContent->type : '';
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

        $docContent = new stdclass();
        $docContent->title   = $doc->title;
        $docContent->digest  = $doc->digest;
        $docContent->content = $doc->content;
        $docContent->version = 1;
        $docContent->type    = 'html';
        unset($doc->digest);
        unset($doc->content);

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

        $lib = $this->getLibByID($doc->lib);
        $doc = $this->loadModel('file')->processEditor($doc, $this->config->doc->editor->edit['id'], $this->post->uid);
        $doc->product = $lib->product;
        $doc->project = $lib->project;

        $files   = $this->file->saveUpload('doc', $docID);
        $changes = common::createChanges($oldDoc, $doc);
        $changed = false;
        if($files) $changed = true;
        foreach($changes as $change)
        {
            if($change['field'] == 'content' or $change['field'] == 'title' or $change['field'] == 'digest') $changed = true;
        }

        if($changed)
        {
            $oldDocContent = $this->dao->select('files,type')->from(TABLE_DOCCONTENT)->where('doc')->eq($docID)->andWhere('version')->eq($oldDoc->version)->fetch();
            $doc->version  = $oldDoc->version + 1;
            $docContent = new stdclass();
            $docContent->doc     = $docID;
            $docContent->title   = $doc->title;
            $docContent->digest  = $doc->digest;
            $docContent->content = $doc->content;
            $docContent->version = $doc->version;
            $docContent->type    = $oldDocContent->type;
            $docContent->files   = $oldDocContent->files;
            if($files) $docContent->files .= ',' . join(',', array_keys($files));
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
        }
        unset($doc->digest);
        unset($doc->content);

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
        $this->config->doc->search['params']['lib']['values']     = array(''=>'') + $libs;

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
        if($object->acl == 'public') return true;

        $account = ',' . $this->app->user->account . ',';
        if(strpos($this->app->company->admins, $account) !== false) return true;
        if($object->acl == 'private' and $object->users == $this->app->user->account) return true;
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
     * Get doc link.
     * 
     * @param  string $module 
     * @param  string $method 
     * @param  string $extra 
     * @access public
     * @return string
     */
    public function getDocLink($module, $method, $extra = '')
    {
        return helper::createLink($module, $method, "libID=%s");
    }

    /**
     * Get all libs by type.
     * 
     * @param  string $type 
     * @param  int    $pager 
     * @param  string $extra 
     * @access public
     * @return void
     */
    public function getAllLibs($type, $pager = null, $extra = '')
    {
        if($extra)
        {
            parse_str($extra);
            if(isset($product) and $type == 'project') $projects = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('product')->eq($product)->fetchPairs('project', 'project');

        }

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
        $idList = $stmt->beginIF($condition)->andWhere("($condition)")->fi()->orderBy("{$key}_desc")->page($pager)->fetchPairs($key, $key);

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
     * Get limit libs.
     * 
     * @param  string $type 
     * @param  int    $limit 
     * @access public
     * @return array
     */
    public function getLimitLibs($type, $limit = 6)
    {
        if($type == 'product' or $type == 'project')
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere($type)->ne(0)->orderBy("$type desc")->query();
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

        if($type == 'product' or $type == 'project')
        {
            $table = $type == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
            $libs = $this->dao->select('id,name,`order`')->from($table)->where('id')->in(array_keys($libs))->orderBy('`order` desc, id desc')->fetchAll('id');
        }

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
    public function getSubLibGroups($type, $idList, $limit = 3)
    {
        $libGroups   = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere($type)->in($idList)->orderBy('id desc')->fetchGroup($type, 'id');
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
            $i = common::hasPriv('doc', 'showFiles') ? 2 : 1;
            if($type == 'product' and isset($hasProject[$objectID]) and common::hasPriv('doc', 'allLibs')) $i += 1;
            foreach($libs as $lib)
            {
                if($limit and $i > $limit) break;
                if($this->checkPriv($lib))
                {
                    $buildGroups[$objectID][$lib->id] = $lib->name;
                    $i++;
                }
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
        $objectLibs   = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere($type)->eq($objectID)->orderBy('id desc')->fetchAll('id');
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
    public function getLibFiles($type, $objectID)
    {
        $this->loadModel('file');
        $joinTable  = $type == 'project' ? TABLE_TASK : TABLE_STORY;
        $objectType = $type == 'project' ? 'task' : 'story';
        $files = $this->dao->select('t1.*')->from(TABLE_FILE)->alias('t1')
            ->leftJoin($joinTable)->alias('t2')->on('t1.objectID=t2.id')
            ->where("t2.$type")->eq($objectID)
            ->andWhere('t1.objectType')->eq($objectType)
            ->andWhere('t2.deleted')->eq(0)
            ->fetchAll('id');

        $docFiles = $this->dao->select('t1.*')->from(TABLE_FILE)->alias('t1')
            ->leftJoin(TABLE_DOC)->alias('t2')->on('t1.objectID=t2.id')
            ->where("t2.$type")->eq($objectID)
            ->andWhere('t1.objectType')->eq('doc')
            ->andWhere('t2.deleted')->eq(0)
            ->fetchAll('id');
        foreach($docFiles as $fileID => $file) $files[$fileID] = $file;
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
            $condition .= "{$table}acl='public'";
            $condition .= " OR ({$table}acl='private' and {$table}users='{$this->app->user->account}')";
            $condition .= " OR ({$table}acl='custom' and (";
            foreach($this->app->user->groups as $groupID) $condition .= "(CONCAT(',', {$table}groups, ',') like '%,$groupID,%') OR ";
            $condition .= "(CONCAT(',', {$table}users, ',') like '%$account%')";
            $condition .= "))";
        }
        return $condition;
    }
}
