<?php
/**
 * The model file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
     * @var actionModel
     */
    public $action;

    // api doc type
    const DOC_TYPE_API = 'api';

    /**
     * Get library by id.
     *
     * @param  int $libID
     * @access public
     * @return object
     */
    public function getLibById($libID)
    {
        return $this->dao->findByID($libID)->from(TABLE_DOCLIB)->fetch();
    }

    /**
     * Get api Libraries.
     *
     * @param  int    $appendLib
     * @return array
     * @author thanatos thanatos915@163.com
     */
    public function getApiLibs($appendLib = 0)
    {
        $libs = $this->dao->select('*')->from(TABLE_DOCLIB)
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq('api')
            ->beginIF(!empty($appendLib))->orWhere('id')->eq($appendLib)->fi()
            ->orderBy('`order`_asc, id_desc')
            ->fetchAll('id');
        $libs = array_filter($libs, array($this, 'checkPrivLib'));
        return $libs;
    }

    /**
     * Get libraries.
     *
     * @param  string $type
     * @param  string $extra
     * @param  string $appendLibs
     * @param  int    $objectID
     * @param  string $excludeType
     *
     * @access public
     * @return array
     */
    public function getLibs($type = '', $extra = '', $appendLibs = '', $objectID = 0, $excludeType = '')
    {
        if($type == 'all' or $type == 'includeDeleted')
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)
                ->where('type')->ne('api')
                ->beginIF($type == 'all')->andWhere('deleted')->eq(0)->fi()
                ->beginIF($excludeType)->andWhere('type')->notin($excludeType)->fi()
                ->andWhere('vision')->eq($this->config->vision)
                ->orderBy('`order` asc, id_desc')
                ->query();
        }
        else
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)
                ->where('deleted')->eq(0)
                ->andWhere('vision')->eq($this->config->vision)
                ->beginIF($type)->andWhere('type')->eq($type)->fi()
                ->beginIF(!$type)->andWhere('type')->ne('api')->fi()
                ->beginIF($objectID and strpos(',product,project,execution,', ",$type,") !== false)->andWhere($type)->eq($objectID)->fi()
                ->orderBy("`order` asc, id_desc")->query();
        }

        $products   = $this->loadModel('product')->getPairs();
        $projects   = $this->loadModel('project')->getPairsByProgram();
        $executions = $this->loadModel('execution')->getPairs();
        $waterfalls = array();
        if(empty($objectID) and ($type == 'all' or $type == 'execution'))
        {
            $waterfalls = $this->dao->select('t1.id,t2.name')->from(TABLE_EXECUTION)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.type')->eq('stage')
                ->andWhere('t2.type')->eq('project')
                ->andWhere('t2.model')->eq('waterfall')
                ->andWhere('t1.deleted')->eq('0')
                ->andWhere('t2.deleted')->eq('0')
                ->fetchPairs('id', 'name');
        }

        $libPairs = array();
        while($lib = $stmt->fetch())
        {
            if($lib->product != 0 and !isset($products[$lib->product])) continue;
            if($lib->execution != 0 and !isset($executions[$lib->execution])) continue;
            if($lib->project != 0 and !isset($projects[$lib->project]) and $lib->type == 'project') continue;

            if($this->checkPrivLib($lib, $extra))
            {
                if(strpos($extra, 'withObject') !== false)
                {
                    if($lib->product != 0) $lib->name = zget($products, $lib->product, '') . ' / ' . $lib->name;
                    if($lib->project != 0) $lib->name = zget($projects, $lib->project, '') . ' / ' . $lib->name;
                    if($lib->execution != 0)
                    {
                        $lib->name = zget($executions, $lib->execution, '') . ' / ' . $lib->name;
                        if(!empty($waterfalls[$lib->execution])) $lib->name = $waterfalls[$lib->execution] . ' / ' . $lib->name;
                    }
                }

                $libPairs[$lib->id] = $lib->name;
            }
        }

        if(!empty($appendLibs))
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)->where('id')->in($appendLibs)->orderBy("`order` asc, id_desc")->query();
            while($lib = $stmt->fetch())
            {
                if(!isset($libPairs[$lib->id]) and $this->checkPrivLib($lib, $extra)) $libPairs[$lib->id] = $lib->name;
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
            while ($lib = $stmt->fetch())
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
            $execution    = $this->loadModel('execution')->getByID($lib->execution);
            $lib->project = $execution->project;
        }

        if($lib->acl == 'custom' or $lib->acl == 'private')
        {
            $trimedUsers = ',' . trim($lib->users, ',') . ',';
            if(strpos($trimedUsers, ',' . $this->app->user->account . ',') === false) $lib->users .= ',' . $this->app->user->account;
        }

        $lib->name = trim($lib->name); //Temporary treatment: Code for bug #15528.
        $this->dao->insert(TABLE_DOCLIB)->data($lib)->autoCheck()
            ->batchCheck($this->config->doc->createlib->requiredFields, 'notempty')
            ->exec();

        return $this->dao->lastInsertID();
    }

    /**
     * creat a api doc library.
     *
     * @return int
     * @author thanatos thanatos915@163.com
     */
    public function createApiLib()
    {
        /* Replace doc library name. */
        $this->lang->doclib->name = $this->lang->doclib->apiLibName;

        $data = fixer::input('post')
            ->trim('name')
            ->join('groups', ',')
            ->join('users', ',')
            ->get();

        if($data->acl == 'private') $data->users = $this->app->user->account;
        if($data->acl == 'custom' && strpos($data->users, $this->app->user->account) === false) $data->users .= ',' . $this->app->user->account;

        $data->type = static::DOC_TYPE_API;
        $this->dao->insert(TABLE_DOCLIB)->data($data)->autoCheck()
            ->batchCheck($this->config->api->createlib->requiredFields, 'notempty')
            ->check('name', 'unique', "`type` = '" . static::DOC_TYPE_API . "'")
            ->exec();

        return $this->dao->lastInsertID();
    }

    /**
     * Update api lib.
     *
     * @param  int      $id
     * @param  stdClass $oldDoc
     * @param  array    $data
     * @access public
     * @return array|int
     */
    public function updateApiLib($id, $oldDoc, $data)
    {
        /* Replace doc library name. */
        $this->lang->doclib->name = $this->lang->doclib->apiLibName;

        $data->type = static::DOC_TYPE_API;
        $this->dao->update(TABLE_DOCLIB)->data($data)->autoCheck()
            ->batchCheck($this->config->api->editlib->requiredFields, 'notempty')
            ->where('id')->eq($id)
            ->exec();

        $changes = array();
        if(!dao::isError())
        {
            $this->loadModel('action');
            $changes  = common::createChanges($oldDoc, $data);
            $actionID = $this->action->create('docLib', $id, 'Edited');
            $this->action->logHistory($actionID, $changes);
        }
        return $changes;
    }

    /**
     * Update a library.
     *
     * @param  int $libID
     * @access public
     * @return void
     */
    public function updateLib($libID)
    {
        $libID  = (int)$libID;
        $oldLib = $this->getLibById($libID);
        $lib    = fixer::input('post')
            ->setDefault('users', '')
            ->setDefault('groups', '')
            ->join('groups', ',')
            ->join('users', ',')
            ->get();

        if($oldLib->type == 'project' or $oldLib->type == 'custom')
        {
            $libCreatedBy = $this->dao->select('*')->from(TABLE_ACTION)->where('objectType')->eq('doclib')->andWhere('objectID')->eq($libID)->andWhere('action')->eq('created')->fetch('actor');

            if($oldLib->type == 'custom')
            {
                if($lib->acl == 'private') $lib->users = $libCreatedBy ? $libCreatedBy : $this->app->user->account;
            }
            else
            {
                $openedBy = $this->dao->findById($oldLib->project)->from(TABLE_PROJECT)->fetch('openedBy');
                if($lib->acl == 'private' and $lib->acl == 'custom') $lib->users .= ',' . $libCreatedBy ? $libCreatedBy : $openedBy;
            }
        }

        $lib->name = trim($lib->name); //Temporary treatment: Code for bug #15528.
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
        elseif($browseType == 'bySearch')
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('contributeDocQuery', $query->sql);
                    $this->session->set('contributeDocForm', $query->form);
                }
                else
                {
                    $this->session->set('contributeDocQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->contributeDocQuery == false) $this->session->set('contributeDocQuery', ' 1 = 1');
            }

            $query     = $this->getDocQuery($this->session->contributeDocQuery);
            $docIDList = $this->dao->select('objectID')->from(TABLE_ACTION)
                ->where('objectType')->eq('doc')
                ->andWhere('actor')->eq($this->app->user->account)
                ->andWhere('action')->eq('edited')
                ->fetchAll('objectID');
            $docs = $this->dao->select('*')->from(TABLE_DOC)
                ->where('deleted')->eq(0)
                ->andWhere($query)
                ->andWhere('lib')->in($allLibs)
                ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
                ->andWhere('addedBy', 1)->eq($this->app->user->account)
                ->orWhere('id')->in(array_keys($docIDList))
                ->markRight(1)
                ->orderBy($sort)
                ->page($pager)
                ->fetchAll('id');
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
            $docs      = $this->dao->select('*')->from(TABLE_DOC)
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

        if(empty($docs)) return array();

        /* Get projects, executions and products by docIdList. */
        list($projects, $executions, $products) = $this->getObjectsByDoc(array_keys($docs));

        $docContents = $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->in(array_keys($docs))->orderBy('version,doc')->fetchAll('doc');

        $objects = array('product' => 'products', 'execution' => 'executions', 'project' => 'projects');
        foreach($docs as $index => $doc)
        {
            foreach($objects as $type => $object)
            {
                if(!empty($doc->{$type}))
                {
                    $doc->objectID   = $doc->{$type};
                    $doc->objectName = ${$object}[$doc->{$type}];
                    $doc->objectType = $type;
                    break;
                }
            }

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
                    $fileSize = round($fileSize / 1024 / 1024 / 1024, 2) . 'G';
                }

                $docs[$index]->fileSize = $fileSize;
            }
        }

        return $docs;
    }

    /**
     * Replace all in query.
     *
     * @param  string    $query
     * @access public
     * @return string
     */
    public function getDocQuery($query)
    {
        $allLibs = "`lib` = 'all'";
        if(strpos($query, $allLibs) !== false)
        {
            $libs  = $this->loadModel('doc')->getLibs('all', 'withObject');
            $query = str_replace($allLibs, '1', $query);
            $query = $query . ' AND `lib` ' . helper::dbIN($libs);
        }

        $allProject = "`project` = 'all'";
        if(strpos($query, $allProject) !== false)
        {
            $projectIDList = $this->loadModel('bug')->getAllProjectIds();
            if(is_array($projectIDList)) $projectIDList = implode(',', $projectIDList);
            $query = str_replace($allProject, '1', $query);
            $query = $query . ' AND `project` in (' . $projectIDList . ')';
        }

        $allProduct = "`product` = 'all'";
        if(strpos($query, $allProduct) !== false)
        {
            $products = $this->app->user->view->products;
            $query    = str_replace($allProduct, '1', $query);
            $query    = $query . ' AND `product` ' . helper::dbIN($products);
        }

        $allExecutions = "`execution` = 'all'";
        if(strpos($query, $allExecutions) !== false)
        {
            $executions = $this->loadModel('execution')->getPairs();
            $query      = str_replace($allExecutions, '1', $query);
            $query      = $query . ' AND `execution` ' . helper::dbIN(array_keys($executions));
        }

        return $query;
    }

    /**
     * Get projects, executions and products by docIdList.
     *
     * @param  array $docIdList
     * @access public
     * @return array
     */
    public function getObjectsByDoc($docIdList = array())
    {
        if(empty($docIdList)) return array();

        $projects = $this->dao->select('t1.id, t1.name')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_DOC)->alias('t2')->on('t1.id=t2.project')
            ->where('t2.id')->in($docIdList)
            ->andWhere('t2.execution')->eq(0)
            ->fetchPairs();

        $executions = $this->dao->select('t1.id, t1.name')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_DOC)->alias('t2')->on('t1.id=t2.execution')
            ->where('t2.id')->in($docIdList)
            ->fetchPairs();

        $products = $this->dao->select('t1.id, t1.name')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_DOC)->alias('t2')->on('t1.id=t2.product')
            ->where('t2.id')->in($docIdList)
            ->fetchPairs();

        return array($projects, $executions, $products);
    }

    /**
     * Get docs.
     *
     * @param  int|string $libID
     * @param  int        $module
     * @param  string     $orderBy
     * @param  object     $pager
     * @access public
     * @return void
     */
    public function getDocs($libID, $module, $orderBy, $pager = null)
    {
        $docIdList = $this->getPrivDocs($libID, $module);
        return $this->dao->select('*')->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
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
     * @param  string $mode normal|all
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
        while ($doc = $stmt->fetch())
        {
            if($this->checkPrivDoc($doc)) $docIdList[$doc->id] = $doc->id;
        }
        return $docIdList;
    }

    /**
     * Get doc info by id.
     *
     * @param  int  $docID
     * @param  int  $version
     * @param  bool $setImgSize
     * @access public
     * @return void
     */
    public function getById($docID, $version = 0, $setImgSize = false)
    {
        $doc = $this->dao->select('*')->from(TABLE_DOC)
            ->where('id')->eq((int)$docID)
            ->andWhere('vision')->eq($this->config->vision)
            ->fetch();

        if(!$doc) return false;
        if(!$this->checkPrivDoc($doc))
        {
            echo(js::alert($this->lang->doc->accessDenied));
            $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
            if(strpos($this->server->http_referer, $loginLink) !== false) return print(js::locate(inlink('index')));
            return print(js::locate('back'));
        }
        $version    = $version ? $version : $doc->version;
        $docContent = $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq($doc->id)->andWhere('version')->eq($version)->fetch();

        /* When file change then version add one. */
        $files    = $this->loadModel('file')->getByObject('doc', $docID);
        $docFiles = array();
        if($docContent)
        {
            foreach($files as $file)
            {
                $pathName       = $this->file->getRealPathName($file->pathname);
                $file->webPath  = $this->file->webPath . $pathName;
                $file->realPath = $this->file->savePath . $pathName;
                if(strpos(",{$docContent->files},", ",{$file->id},") !== false) $docFiles[$file->id] = $file;
            }
        }

        /* Check file change. */
        if($version == $doc->version and ((empty($docContent->files) and $docFiles) or ($docContent->files and count(explode(',', trim($docContent->files, ','))) != count($docFiles))))
        {
            unset($docContent->id);
            $doc->version        += 1;
            $docContent->version = $doc->version;
            $docContent->files   = join(',', array_keys($docFiles));
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
            $this->dao->update(TABLE_DOC)->set('version')->eq($doc->version)->where('id')->eq($doc->id)->exec();
        }

        $doc->title       = isset($docContent->title) ? $docContent->title : '';
        $doc->digest      = isset($docContent->digest) ? $docContent->digest : '';
        $doc->content     = isset($docContent->content) ? $docContent->content : '';
        $doc->contentType = isset($docContent->type) ? $docContent->type : '';

        if($doc->type != 'url' and $doc->contentType != 'markdown') $doc = $this->loadModel('file')->replaceImgURL($doc, 'content,draft');
        if($setImgSize) $doc->content = $this->file->setImgSize($doc->content);
        $doc->files = $docFiles;

        $doc->productName   = '';
        $doc->executionName = '';
        $doc->moduleName    = '';
        if($doc->product) $doc->productName = $this->dao->findByID($doc->product)->from(TABLE_PRODUCT)->fetch('name');
        if($doc->execution) $doc->executionName = $this->dao->findByID($doc->execution)->from(TABLE_EXECUTION)->fetch('name');
        if($doc->module) $doc->moduleName = $this->dao->findByID($doc->module)->from(TABLE_MODULE)->fetch('name');
        if(!$doc->module and $doc->type == 'article' and $doc->parent) $doc->moduleName = $this->dao->findByID($doc->parent)->from(TABLE_DOC)->fetch('title');
        return $doc;
    }

    /**
     * Get docs info by id list.
     *
     * @param  array $docIdList
     * @access public
     * @return array
     */
    public function getByIdList($docIdList = array())
    {
        return $this->dao->select('*,t1.id as docID,t1.type as docType,t2.type as contentType')->from(TABLE_DOC)->alias('t1')
            ->leftJoin(TABLE_DOCCONTENT)->alias('t2')->on('t1.id=t2.doc and t1.version=t2.version')
            ->where('t1.id')->in($docIdList)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');

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
            ->callFunc('title', 'trim')
            ->setDefault('content', '')
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

        if(empty($doc->lib))
        {
            dao::$errors['lib'] = sprintf($this->lang->error->notempty, $this->lang->doc->lib);
            return false;
        }

        /* Fix bug #2929. strip_tags($this->post->contentMarkdown, $this->config->allowedTags)*/
        $doc                  = $this->loadModel('file')->processImgURL($doc, $this->config->doc->editor->create['id'], $this->post->uid);
        $doc->contentMarkdown = $this->post->contentMarkdown;
        if($doc->acl == 'private') $doc->users = $this->app->user->account;

        if($doc->title)
        {
            $condition = "lib = '$doc->lib' AND module = $doc->module";
            $result    = $this->loadModel('common')->removeDuplicate('doc', $doc, $condition);
            if($result['stop']) return array('status' => 'exists', 'id' => $result['duplicate']);
        }

        $lib            = $this->getLibByID($doc->lib);
        $doc->product   = $lib->product;
        $doc->project   = $lib->project;
        $doc->execution = $lib->execution;
        if($doc->type == 'url')
        {
            $doc->content     = $doc->url;
            $doc->contentType = 'html';
        }

        $docContent          = new stdclass();
        $docContent->title   = $doc->title;
        $docContent->content = $doc->contentType == 'html' ? $doc->content : $doc->contentMarkdown;
        $docContent->type    = $doc->contentType;
        $docContent->version = 1;
        unset($doc->contentMarkdown);
        unset($doc->contentType);
        unset($doc->url);

        $requiredFields = $this->config->doc->create->requiredFields;
        if(strpos("url|word|ppt|excel", $this->post->type) !== false) $requiredFields = trim(str_replace(",content,", ",", ",{$requiredFields},"), ',');

        $checkContent = strpos(",$requiredFields,", ',content,') !== false;
        if($checkContent and strpos("url|word|ppt|excel|", $this->post->type) === false)
        {
            $requiredFields = trim(str_replace(',content,', ',', ",$requiredFields,"), ',');
            if(empty($docContent->content)) return dao::$errors['content'] = sprintf($this->lang->error->notempty, $this->lang->doc->content);
        }

        $doc->draft = $docContent->content;
        $this->dao->insert(TABLE_DOC)->data($doc, 'content')->autoCheck()
            ->batchCheck($requiredFields, 'notempty')
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
            return array('status' => 'new', 'id' => $docID, 'files' => $files, 'docType' => $doc->type, 'libID' => $doc->lib);
        }
        return false;
    }

    /**
     * Update a doc.
     *
     * @param  int $docID
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
            ->callFunc('title', 'trim')
            ->stripTags($this->config->doc->editor->edit['id'], $this->config->allowedTags)
            ->setDefault('users', '')
            ->setDefault('groups', '')
            ->setDefault('product', 0)
            ->setDefault('execution', 0)
            ->setDefault('mailto', '')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->cleanInt('module')
            ->join('groups', ',')
            ->join('users', ',')
            ->join('mailto', ',')
            ->remove('comment,files,labels,uid,contactListMenu')
            ->get();

        if(!empty($doc->acl) and $doc->acl == 'private') $doc->users = $oldDoc->addedBy;

        $oldDocContent = $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq($docID)->andWhere('version')->eq($oldDoc->version)->fetch();
        if($oldDocContent)
        {
            $oldDoc->title       = $oldDocContent->title;
            $oldDoc->digest      = $oldDocContent->digest;
            $oldDoc->content     = $oldDocContent->content;
            $oldDoc->contentType = $oldDocContent->type;
        }

        $lib = !empty($doc->lib) ? $this->getLibByID($doc->lib) : '';
        $doc = $this->loadModel('file')->processImgURL($doc, $this->config->doc->editor->edit['id'], $this->post->uid);
        if($doc->contentType == 'markdown') $doc->content = $this->post->content;

        if(!empty($lib))
        {
            $doc->product   = $lib->product;
            $doc->execution = $lib->execution;
        }

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

        $requiredFields = $this->config->doc->edit->requiredFields;
        $checkContent   = strpos(",$requiredFields,", ',content,') !== false;
        if($checkContent)
        {
            $requiredFields = trim(str_replace(',content,', ',', ",$requiredFields,"), ',');
            if(isset($doc->content) and empty($doc->content)) return dao::$errors['content'] = sprintf($this->lang->error->notempty, $this->lang->doc->content);
        }

        if($changed)
        {
            $doc->version        = $oldDoc->version + 1;
            $docContent          = new stdclass();
            $docContent->doc     = $docID;
            $docContent->title   = $doc->title;
            $docContent->content = isset($doc->content) ? $doc->content : '';
            $docContent->version = $doc->version;
            $docContent->type    = $oldDocContent->type;
            $docContent->files   = $oldDocContent->files;
            if(isset($doc->digest)) $docContent->digest = $doc->digest;
            if($files) $docContent->files .= ',' . join(',', array_keys($files));
            $docContent->files = trim($docContent->files, ',');
            $this->dao->replace(TABLE_DOCCONTENT)->data($docContent)->exec();
        }
        unset($doc->contentType);

        $doc->draft = isset($doc->content) ? $doc->content : '';
        $this->dao->update(TABLE_DOC)->data($doc, 'content')
            ->autoCheck()
            ->batchCheck($requiredFields, 'notempty')
            ->where('id')->eq((int)$docID)
            ->exec();
        if(!dao::isError())
        {
            unset($doc->draft);
            $this->file->updateObjectID($this->post->uid, $docID, 'doc');
            return array('changes' => $changes, 'files' => $files);
        }
    }

    /**
     * Save draft.
     *
     * @param  int $docID
     * @access public
     * @return void
     */
    public function saveDraft($docID)
    {
        $data       = fixer::input('post')
            ->stripTags($this->config->doc->editor->edit['id'], $this->config->allowedTags)
            ->get();
        $doc        = new stdclass();
        $doc->draft = $data->content;

        $docType = $this->dao->select('type')->from(TABLE_DOCCONTENT)->where('doc')->eq((int)$docID)->orderBy('version_desc')->fetch();
        if($docType == 'markdown') $doc->draft = $this->post->content;

        $this->dao->update(TABLE_DOC)->data($doc)->where('id')->eq($docID)->exec();
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
        $this->loadModel('product');

        /* Fixed search modal for doc view. */
        if($this->app->getMethodName() == 'objectlibs')
        {
            $queryName = $type . 'Doc';
            $type      = 'objectLibs';
        }

        if($this->app->rawMethod == 'contribute')
        {
            $this->config->doc->search['module'] = 'contributeDoc';
            $products = $this->product->getPairs();

            $this->config->doc->search['params']['project']['values']   = array('' => '') + $this->loadModel('project')->getPairsByProgram() + array('all' => $this->lang->doc->allProjects);
            $this->config->doc->search['params']['execution']['values'] = array('' => '') + $this->loadModel('execution')->getPairs() + array('all' => $this->lang->doc->allExecutions);
            $this->config->doc->search['params']['lib']['values']       = array('' => '') + $this->loadModel('doc')->getLibs('all', 'withObject') + array('all' => $this->lang->doclib->all);
            $this->config->doc->search['params']['product']['values']   = array('' => '') + $products + array('all' => $this->lang->doc->allProduct);

            unset($this->config->doc->search['fields']['module']);
        }
        elseif(in_array($type, array('product', 'project', 'execution', 'custom', 'book')))
        {
            $queryName = $type . 'Doc';
            $this->config->doc->search['module']                  = $queryName;
            $this->config->doc->search['params']['lib']['values'] = array('' => '', $libID => (isset($libs[$libID]) ? $libs[$libID]->name : $libID), 'all' => $this->lang->doclib->all);
            unset($this->config->doc->search['fields']['product']);
            unset($this->config->doc->search['fields']['execution']);
            unset($this->config->doc->search['fields']['module']);
        }
        else
        {
            if(isset($queryName)) $this->config->doc->search['module'] = $queryName;
            $products = $this->product->getPairs('nocode', $this->session->project);
            $this->config->doc->search['params']['execution']['values'] = array('' => '') + $this->loadModel('execution')->getPairs($this->session->project, 'all', 'noclosed') + array('all' => $this->lang->doc->allExecutions);
            $this->config->doc->search['params']['lib']['values']       = array('' => '', $libID => ($libID ? $libs[$libID] : 0), 'all' => $this->lang->doclib->all);
            $this->config->doc->search['params']['product']['values']   = array('' => '') + $products + array('all' => $this->lang->doc->allProduct);
        }

        $this->config->doc->search['actionURL'] = $actionURL;
        $this->config->doc->search['queryID']   = $queryID;

        /* Get the modules. */
        $moduleOptionMenu                                        = $this->loadModel('tree')->getOptionMenu($libID, 'doc', $startModuleID = 0);
        $this->config->doc->search['params']['module']['values'] = $moduleOptionMenu;

        if($type == 'index' || $type == 'objectLibs' || ($this->app->rawMethod != 'contribute' and $libID == 0))
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
     * @param  int $libID
     * @param  int $parent
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
     * @param  string $content
     * @access public
     * @return void
     */
    public function extractKETableCSS($content)
    {
        $css  = '';
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
                $borderSize  = isset($border) ? $border . 'px' : '1px';
                $borderColor = isset($bordercolor) ? $bordercolor : 'gray';
                $borderStyle = "{border:$borderSize $borderColor solid}\n";
                $css         .= ".$className$borderStyle";
                $css         .= ".$className td$borderStyle";
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

        if($object->project and $object->acl == 'private')
        {
            $stakeHolders = array();
            $project      = $this->loadModel('project')->getById($object->project);
            $projectTeams = $this->loadModel('user')->getTeamMemberPairs($object->project);
            $stakeHolders = $this->loadModel('stakeholder')->getStakeHolderPairs($object->project);

            $authorizedUsers = $this->user->getProjectAuthedUsers($project, $stakeHolders, $projectTeams, array_flip(explode(",", $project->whitelist)));

            if(strpos(",{$object->users},", $account) !== false) return true;
            if(array_key_exists($this->app->user->account, array_filter($authorizedUsers))) return true;
        }

        if($object->acl == 'custom')
        {
            $userGroups = $this->app->user->groups;
            foreach($userGroups as $groupID)
            {
                if(strpos(",$object->groups,", ",$groupID,") !== false) return true;
            }
            if(strpos(",{$object->users},", $account) !== false) return true;
        }

        if(strpos($extra, 'notdoc') !== false)
        {
            static $extraDocLibs;
            if($extraDocLibs === null) $extraDocLibs = $this->getPrivLibsByDoc();
            if(isset($extraDocLibs[$object->id])) return true;
        }

        if(!empty($object->product) or !empty($object->execution))
        {
            $acls = $this->app->user->rights['acls'];
            if(!empty($object->product) and !empty($acls['products']) and !in_array($object->product, $acls['products'])) return false;
            if(!empty($object->execution) and !empty($acls['sprints']) and !in_array($object->execution, $acls['sprints'])) return false;
            if(!empty($object->execution)) return $this->loadModel('execution')->checkPriv($object->execution);
            if(!empty($object->product)) return $this->loadModel('product')->checkPriv($object->product);
        }

        return false;
    }

    /**
     * Check priv for doc.
     *
     * @param  object $object
     * @access public
     * @return bool
     */
    public function checkPrivDoc($object)
    {
        if($this->app->user->admin) return true;

        static $extraDocLibs;
        if($extraDocLibs === null) $extraDocLibs = $this->getPrivLibsByDoc();

        static $libs;
        if($libs === null) $libs = $this->getLibs('all');
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
        $stmt = $this->dao->select("DISTINCT $key")->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere('vision')->eq($this->config->vision);
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
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere("id")->in(array_keys($libs))
            ->orderBy("product desc,execution desc, `order` asc, id asc")
            ->query();

        $customLibs    = array();
        $productLibs   = array();
        $executionLibs = array();

        $otherLibs = array();
        while ($lib = $stmt->fetch())
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

        $hasLibsPriv      = common::hasPriv('doc', 'allLibs');
        $hasFilesPriv     = common::hasPriv('doc', 'showFiles');
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

        $executions         = $this->dao->select('id,name,status')->from(TABLE_EXECUTION)
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
        if($type == 'product' or $type == 'execution')
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
     * @param  int    $appendLib
     * @access public
     * @return array
     */
    public function getLibsByObject($type, $objectID, $mode = '', $appendLib = 0)
    {
        if($type == 'custom' or $type == 'book')
        {
            $objectLibs = $this->dao->select('*')->from(TABLE_DOCLIB)
                ->where('deleted')->eq(0)
                ->andWhere('vision')->eq($this->config->vision)
                ->andWhere('type')->eq($type)
                ->beginIF(!empty($appendLib))->orWhere('id')->eq($appendLib)->fi()
                ->beginIF($type == 'custom')->orderBy('`order` asc, id_desc')->fi()
                ->beginIF($type == 'book')->orderBy('`order` asc, id_desc')->fi()
                ->fetchAll('id');
        }
        elseif($type != 'product' and $type != 'project' and $type != 'execution')
        {
            return false;
        }
        else
        {
            $objectLibs = $this->dao->select('*')->from(TABLE_DOCLIB)
                ->where('deleted')->eq(0)
                ->andWhere('vision')->eq($this->config->vision)
                ->andWhere($type)->eq($objectID)
                ->beginIF(!empty($appendLib))->orWhere('id')->eq($appendLib)->fi()
                ->beginIF($type == 'project')->andWhere('execution')->eq(0)->fi()
                ->orderBy('`order` asc, id_desc')
                ->fetchAll('id');
        }

        if($type == 'product')
        {
            $hasProject = $this->dao->select('DISTINCT t1.product, count(t1.project) as projectCount')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.product')->eq($objectID)
                ->beginIF(strpos($this->config->doc->custom->showLibs, 'unclosed') !== false)->andWhere('t2.status')->notin('done,closed')->fi()
                ->andWhere('t2.deleted')->eq(0)
                ->groupBy('product')
                ->fetchPairs('product', 'projectCount');
        }

        $docCountPairs = $this->dao->select('lib, count(id) as docCount')->from(TABLE_DOC)
            ->where('lib')->in(array_keys($objectLibs))
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('type')->notin('chapter')
            ->andWhere('deleted')->eq(0)
            ->groupBy('lib')
            ->fetchPairs('lib');

        $libs = array();
        foreach($objectLibs as $lib)
        {
            if($this->checkPrivLib($lib))
            {
                $docCount = zget($docCountPairs, $lib->id, 0);
                $lib->docCount = $docCount > 99 ? '99+' : $docCount;

                $libs[$lib->id] = $lib;
            }
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

            /* Project permissions for DocLib whitelist. */
            if($this->app->tab == 'doc')
            {
                $myObjects = $this->dao->select('t1.id, t1.name')->from(TABLE_PROJECT)->alias('t1')
                    ->leftjoin(TABLE_DOCLIB)->alias('t2')->on('t2.project=t1.id')
                    ->where("CONCAT(',', t2.users, ',')")->like("%,{$this->app->user->account},%")
                    ->andWhere('t1.vision')->eq($this->config->vision)
                    ->andWhere('t1.deleted')->eq(0)
                    ->fetchPairs();
            }

            $objects = $this->dao->select('*')->from(TABLE_PROJECT)
                ->where('type')->eq('project')
                ->andWhere('vision')->eq($this->config->vision)
                ->andWhere('deleted')->eq(0)
                ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
                ->orderBy('order_asc')
                ->fetchAll('id');

            foreach($objects as $objectID => $object)
            {
                $object->parent             = $this->program->getTopByID($object->parent);
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
            $projectPairs = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('type')->eq('project')->fetchPairs('id');

            $executions = $this->dao->select('*')->from(TABLE_EXECUTION)
                ->where('deleted')->eq(0)
                ->andWhere('type')->in('sprint,stage,kanban')
                ->andWhere('vision')->eq($this->config->vision)
                ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
                ->orderBy('order_asc')
                ->fetchAll('id');

            $orderedExecutions = array();
            foreach($executions as $id => $execution)
            {
                $execution->name = $this->config->systemMode == 'new' ? zget($projectPairs, $execution->project) . ' / ' . $execution->name : $execution->name;

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
     * @param  array $idList
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
            $docCounts[$doc->lib]++;
        }

        $itemCounts = array();
        foreach($idList as $libID)
        {
            $docCount           = isset($docCounts[$libID]) ? $docCounts[$libID] : 0;
            $moduleCount        = isset($moduleCounts[$libID]) ? $moduleCounts[$libID] : 0;
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

        $bugIdList = $testReportIdList = $caseIdList = $storyIdList = $planIdList = $releaseIdList = $executionIdList = $taskIdList = $buildIdList = $issueIdList = $meetingIdList = $designIdList = $reviewIdList = 0;

        $userView = $this->app->user->view->products;
        if($type == 'project') $userView = $this->app->user->view->projects;
        if($type == 'execution') $userView = $this->app->user->view->sprints;

        $bugPairs = $this->dao->select('id')->from(TABLE_BUG)->where($type)->eq($objectID)->andWhere('deleted')->eq('0')->andWhere($type)->in($userView)->fetchPairs('id');
        if(!empty($bugPairs)) $bugIdList = implode(',', $bugPairs);

        $testReportPairs = $this->dao->select('id')->from(TABLE_TESTREPORT)->where($type)->eq($objectID)->andWhere('deleted')->eq('0')->andWhere($type)->in($userView)->fetchPairs('id');
        if(!empty($testReportPairs)) $testReportIdList = implode(',', $testReportPairs);

        $field     = $type == 'execution' ? 'project' : $type;
        $casePairs = $this->dao->select('`case`')->from(TABLE_PROJECTCASE)->where($field)->eq($objectID)->andWhere($field)->in($userView)->fetchPairs('case');
        if(!empty($casePairs)) $caseIdList = implode(',', $casePairs);

        $idList      = array_keys($docs);
        $docIdList   = $this->dao->select('id')->from(TABLE_DOC)->where($type)->eq($objectID)->andWhere('id')->in($idList)->get();
        $searchTitle = $this->post->title;
        if($type == 'product')
        {
            $storyIdList = $this->dao->select('id')->from(TABLE_STORY)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('product')->in($userView)->get();
            $planIdList  = $this->dao->select('id')->from(TABLE_PRODUCTPLAN)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('product')->in($userView)->get();

            $releasePairs = $this->dao->select('id')->from(TABLE_RELEASE)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('product')->in($userView)->fetchPairs('id');
            if(!empty($releasePairs)) $releaseIdList = implode(',', $releasePairs);

            $casePairs = $this->dao->select('id')->from(TABLE_CASE)->where($type)->eq($objectID)->andWhere('deleted')->eq('0')->andWhere($type)->in($userView)->fetchPairs('id');
            if(!empty($casePairs)) $caseIdList = implode(',', $casePairs);
        }
        elseif($type == 'project')
        {
            if($this->config->edition == 'max')
            {
                $issueIdList   = $this->dao->select('id')->from(TABLE_ISSUE)->where('project')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('project')->in($this->app->user->view->projects)->get();
                $meetingIdList = $this->dao->select('id')->from(TABLE_MEETING)->where('project')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('project')->in($this->app->user->view->projects)->get();
                $reviewIdList  = $this->dao->select('id')->from(TABLE_REVIEW)->where('project')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('project')->in($this->app->user->view->projects)->get();
            }

            $designIdList    = $this->dao->select('id')->from(TABLE_DESIGN)->where('project')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('project')->in($this->app->user->view->projects)->get();
            $executionIdList = $this->loadModel('execution')->getIdList($objectID);
            $taskPairs       = $this->dao->select('id')->from(TABLE_TASK)->where('execution')->in($executionIdList)->andWhere('deleted')->eq('0')->andWhere('execution')->in($this->app->user->view->sprints)->fetchPairs('id');
            if(!empty($taskPairs)) $taskIdList = implode(',', $taskPairs);

            $buildPairs = $this->dao->select('id')->from(TABLE_BUILD)->where('execution')->in($executionIdList)->andWhere('deleted')->eq('0')->andWhere('execution')->in($this->app->user->view->sprints)->fetchPairs('id');
            if(!empty($buildPairs)) $buildIdList = implode(',', $buildPairs);

            $executionIdList = join(',', $executionIdList);
        }
        elseif($type == 'execution')
        {
            $taskPairs = $this->dao->select('id')->from(TABLE_TASK)->where('execution')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('execution')->in($userView)->fetchPairs('id');
            if(!empty($taskPairs)) $taskIdList = implode(',', $taskPairs);

            $buildPairs = $this->dao->select('id')->from(TABLE_BUILD)->where('execution')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('execution')->in($userView)->fetchPairs('id');
            if(!empty($buildPairs)) $buildIdList = implode(',', $buildPairs);
        }

        $files = $this->dao->select('*')->from(TABLE_FILE)->alias('t1')
            ->where('size')->gt('0')
            ->andWhere("(objectType = '$type' and objectID = $objectID)", true)
            ->orWhere("(objectType = 'doc' and objectID in ($docIdList))")
            ->orWhere("(objectType = 'bug' and objectID in ($bugIdList))")
            ->orWhere("(objectType = 'testreport' and objectID in ($testReportIdList))")
            ->orWhere("(objectType = 'testcase' and objectID in ($caseIdList))")
            ->beginIF($type == 'product')
            ->orWhere("(objectType in ('story','requirement') and objectID in ($storyIdList))")
            ->orWhere("(objectType = 'release' and objectID in ($releaseIdList))")
            ->fi()
            ->beginIF($type == 'project')
            ->orWhere("(objectType = 'execution' and objectID in ('$executionIdList'))")
            ->orWhere("(objectType = 'issue' and objectID in ($issueIdList))")
            ->orWhere("(objectType = 'review' and objectID in ($reviewIdList))")
            ->orWhere("(objectType = 'meeting' and objectID in ($meetingIdList))")
            ->orWhere("(objectType = 'design' and objectID in ($designIdList))")
            ->fi()
            ->beginIF($type == 'project' or $type == 'execution')
            ->orWhere("(objectType = 'task' and objectID in ($taskIdList))")
            ->orWhere("(objectType = 'build' and objectID in ($buildIdList))")
            ->fi()
            ->markRight(1)
            ->beginIF($searchTitle !== false)->andWhere('title')->like("%{$searchTitle}%")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        foreach($files as $fileID => $file)
        {
            $pathName       = $this->file->getRealPathName($file->pathname);
            $file->realPath = $this->file->savePath . $pathName;
            $file->webPath  = $this->file->webPath . $pathName;
        }

        return $files;
    }

    /**
     * Get file source pairs.
     *
     * @param  array $files
     * @access public
     * @return array
     */
    public function getFileSourcePairs($files)
    {
        $sourceList  = array();
        $sourcePairs = array();

        foreach($files as $file)
        {
            if(!isset($sourceList[$file->objectType])) $sourceList[$file->objectType] = array();
            $sourceList[$file->objectType][$file->objectID] = $file->objectID;
        }

        $this->app->loadConfig('action');
        foreach($sourceList as $type => $idList)
        {
            $table = zget($this->config->objectTables, $type, '');
            $field = zget($this->config->action->objectNameFields, $type, '');
            if(empty($table) or empty($field)) continue;

            $name = $this->dao->select('id,' . $field)->from($table)->where('id')->in($idList)->fetchPairs('id', $field);
            $sourcePairs[$type] = $name;
        }

        return $sourcePairs;
    }

    /**
     * Get file icon.
     *
     * @param  array $files
     * @access public
     * @return array
     */
    public function getFileIcon($files)
    {
        $fileIcon = array();
        foreach($files as $file)
        {
            if(in_array($file->extension, $this->config->file->imageExtensions)) continue;

            $iconClass = 'icon-file';
            if(strpos('zip,tar,gz,bz2,rar', $file->extension) !== false) $iconClass = 'icon-file-archive';
            else if(strpos('csv,xls,xlsx', $file->extension) !== false) $iconClass = 'icon-file-excel';
            else if(strpos('doc,docx', $file->extension) !== false) $iconClass = 'icon-file-word';
            else if(strpos('ppt,pptx', $file->extension) !== false) $iconClass = 'icon-file-powerpoint';
            else if(strpos('pdf', $file->extension) !== false) $iconClass = 'icon-file-pdf';
            else if(strpos('mp3,ogg,wav', $file->extension) !== false) $iconClass = 'icon-file-audio';
            else if(strpos('avi,mp4,mov', $file->extension) !== false) $iconClass = 'icon-file-video';
            else if(strpos('txt,md', $file->extension) !== false) $iconClass = 'icon-file-text';
            else if(strpos('html,htm', $file->extension) !== false) $iconClass = 'icon-globe';

            $fileIcon[$file->id] = "<i class='file-icon icon $iconClass'></i>";
        }

        return $fileIcon;
    }

    /**
     * Get doc tree.
     *
     * @param  int $libID
     * @access public
     * @return array
     */
    public function getDocTree($libID)
    {
        $fullTrees = $this->loadModel('tree')->getTreeStructure($libID, 'doc');
        array_unshift($fullTrees, array('id' => 0, 'name' => '/', 'type' => 'doc', 'actions' => false, 'root' => $libID));
        foreach($fullTrees as $i => $tree)
        {
            $tree          = (object)$tree;
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
            $docs      = $this->dao->select('*')->from(TABLE_DOC)->where('lib')->eq((int)$libID)->andWhere('deleted')->eq(0)->fetchAll();
            $docGroups = array();
            foreach($docs as $doc)
            {
                if($this->checkPrivDoc($doc)) $docGroups[$doc->module][$doc->id] = $doc;
            }
        }

        if(!empty($node->children)) foreach($node->children as $i => $child) $node->children[$i] = $this->fillDocsInTree($child, $libID);
        if(!isset($node->id)) $node->id = 0;

        $node->type = 'module';
        $docs       = isset($docGroups[$node->id]) ? $docGroups[$node->id] : array();
        $menu       = !empty($node->children) ? $node->children : array();
        if(!empty($docs))
        {
            $docItems = array();
            foreach($docs as $doc)
            {
                $docItem        = new stdclass();
                $docItem->type  = 'doc';
                $docItem->id    = $doc->id;
                $docItem->title = $doc->title;
                $docItem->acl   = $doc->acl;
                $docItem->url   = helper::createLink('doc', 'view', "doc=$doc->id");

                $buttons = '';
                $buttons .= common::buildIconButton('doc', 'edit', "docID=$doc->id", '', 'list');
                if(common::hasPriv('doc', 'delete')) $buttons .= html::a(helper::createLink('doc', 'delete', "docID=$doc->id"), "<i class='icon icon-remove'></i>", 'hiddenwin', "class='btn-icon' title='{$this->lang->doc->delete}'");
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
     * @param  int $productID
     * @param  int $executionID
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

        $crumb = '';
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
        if($products) $productLibs = $this->dao->select('id')->from(TABLE_DOCLIB)->where('product')->in($products)->fetchPairs();
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
        $allLibs   = array_keys($this->getLibs('all'));
        $docIdList = $this->getPrivDocs($allLibs);

        $today         = date('Y-m-d');
        $lately        = date('Y-m-d', strtotime('-3 day'));
        $statisticInfo = $this->dao->select("count(id) as totalDocs, count(editedDate like '{$today}%' or null) as todayEditedDocs,
            count(editedDate > '{$lately}' or null) as lastEditedDocs, count(addedDate > '{$lately}' or null) as lastAddedDocs,
            count(collector like '%,{$this->app->user->account},%' or null) as myCollection, count(addedBy = '{$this->app->user->account}' or null) as myDocs")->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
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
     * Get the previous and next doc.
     *
     * @param  int $docID
     * @param  int $libID
     * @access public
     * @return object
     */
    public function getPreAndNextDoc($docID, $libID)
    {
        $sortedModules = 0;
        $modules       = $this->dao->select('id')->from(TABLE_MODULE)
            ->where('root')->eq($libID)
            ->andWhere('type')->eq('doc')
            ->andWhere('deleted')->eq(0)
            ->orderBy('grade desc, `order`')
            ->fetchPairs();
        if(!empty($modules)) $sortedModules = implode(',', array_keys($modules)) . ',0';

        $query = $this->dao->select('t1.id,t1.title,t1.acl,t1.groups,t1.users,t1.addedBy,t1.lib,t2.type,t2.product,t2.project,t2.execution')->from(TABLE_DOC)->alias('t1')
            ->leftJoin(TABLE_DOCLIB)->alias('t2')->on('t1.lib=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.lib')->eq($libID)
            ->beginIF($this->config->doc->notArticleType)->andWhere('t1.type')->notIN($this->config->doc->notArticleType)->fi()
            ->get();
        $query .= " order by field(module, $sortedModules)";
        $stmt  = $this->dbh->query($query);
        $docs  = $stmt->fetchAll();

        $preAndNextDoc       = new stdClass();
        $preAndNextDoc->pre  = '';
        $preAndNextDoc->next = '';

        $preDoc = false;
        foreach($docs as $doc)
        {
            $doc->objectType = 'doc';

            /* Get next object. */
            if($preDoc === true and $this->checkPrivDoc($doc))
            {
                $preAndNextDoc->next = $doc;
                break;
            }

            /* Get pre object. */
            if($doc->id == $docID)
            {
                if($preDoc) $preAndNextDoc->pre = $preDoc;
                $preDoc = true;
            }
            if($preDoc !== true and $this->checkPrivDoc($doc)) $preDoc = $doc;
        }

        return $preAndNextDoc;
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
            $title .= html::a(helper::createLink('doc', 'browse', "libID=$libID&browseType=byModule&param={$parentID}"), " <i class='icon icon-chevron-right'></i> " . $moduleName->name, '');
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
        if(!common::hasPriv('doc', 'create') and !common::hasPriv('doc', 'createLib')) return '';

        if($objectType == 'book')
        {
            $html = html::a(helper::createLink('doc', 'createLib', "type=$objectType&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $this->lang->doc->createBook, '', 'class="btn btn-secondary iframe"');
        }
        elseif($libID)
        {
            $html  = "<div class='dropdown' id='createDropdown'>";
            $html .= "<button class='btn btn-primary' type='button' data-toggle='dropdown'><i class='icon icon-plus'></i> " . $this->lang->doc->createAB . " <span class='caret'></span></button>";
            $html .= "<ul class='dropdown-menu pull-right'>";

            if(common::hasPriv('doc', 'create'))
            {
                foreach($this->lang->doc->typeList as $typeKey => $typeName)
                {
                    $class  = (strpos($this->config->doc->officeTypes, $typeKey) !== false or strpos($this->config->doc->textTypes, $typeKey) !== false) ? 'iframe' : '';
                    $icon   = zget($this->config->doc->iconList, $typeKey);
                    $method = strpos($this->config->doc->textTypes, $typeKey) !== false ? 'createBasicInfo' : 'create';
                    $html  .= "<li>";
                    $html  .= html::a(helper::createLink('doc', $method, "objectType=$objectType&objectID=$objectID&libID=$libID&moduleID=0&type=$typeKey", '', $class ? true : false), "<i class='icon-$icon icon'></i> " . $typeName, '', "class='$class' data-app='{$this->app->tab}'");
                    $html  .= "</li>";
                    if($typeKey == 'url') $html .= '<li class="divider"></li>';
                }
            }

            if(common::hasPriv('doc', 'createLib'))
            {
                if(common::hasPriv('doc', 'create')) $html .= '<li class="divider"></li>';
                $html .= '<li>' . html::a(helper::createLink('doc', 'createLib', "type=$objectType&objectID=$objectID"), "<i class='icon-doc-lib icon'></i> " . $this->lang->doc->createLib, '', "class='iframe' data-width='70%'") . '</li>';
            }
            $html .= "</ul></div>";
        }
        else
        {
            $html = html::a(helper::createLink('doc', 'createLib', "type=$objectType&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $this->lang->doc->createLib, '', 'class="btn btn-secondary iframe"');
        }

        return $html;
    }

    /**
     * Build collect button from document.
     *
     * @access public
     * @return string
     */
    public function buildCollectButton4Doc()
    {
        $favoritesLimit = 10;
        $allLibs        = array_keys($this->getLibs('all'));
        $docs           = $this->dao->select('t1.id,t1.title,t1.lib,t2.type,t2.product,t2.project,t2.execution')->from(TABLE_DOC)->alias('t1')
            ->leftJoin(TABLE_DOCLIB)->alias('t2')->on('t1.lib=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.lib')->in($allLibs)
            ->beginIF($this->config->doc->notArticleType)->andWhere('t1.type')->notIN($this->config->doc->notArticleType)->fi()
            ->andWhere('t1.collector')->like("%,{$this->app->user->account},%")
            ->orderBy('t1.id_desc')
            ->limit($favoritesLimit)
            ->fetchAll();

        $html = '';
        $rawMethod = $this->app->rawMethod;
        if($this->app->rawMethod == 'showfiles')
        {
            $html  = '<div class="btn-group">';
            $html .= '<form class="input-control has-icon-right table-col" method="post">';
            $html .= html::input('title', $this->post->title, "class='form-control' placeholder='{$this->lang->doc->fileTitle}'");
            $html .= html::submitButton("<i class='icon icon-search'></i>", '', "btn  btn-icon btn-link input-control-icon-right");
            $html .= '</form></div>';
        }
        elseif(in_array($rawMethod, array('tablecontents', 'objectlibs', 'product', 'project', 'execution', 'book', 'custom')))
        {
            $html  = '<a class="btn btn-link querybox-toggle" id="bysearchTab"><i class="icon icon-search muted"></i> ' . $this->lang->doc->search . '</a>';
        }
        $html .= "<div class='btn-group dropdown-hover'>";
        $html .= "<a href='javascript:;' class='btn btn-link' data-toggle='dropdown'>{$this->lang->doc->myCollection}</a>";
        $html .= "<ul class='dropdown-menu pull-right' id='collection-menu'>";

        if(empty($docs)) $html .= "<li>{$this->lang->noData}</li>";

        foreach($docs as $doc)
        {
            $objectID = 0;
            if($doc->type == 'product') $objectID = $doc->product;
            if($doc->type == 'project') $objectID = $doc->project;
            if($doc->type == 'execution') $objectID = $doc->execution;

            $tab = $this->app->tab;
            if($tab != 'doc') $tab = $objectID ? $doc->type : 'doc';

            $html .= '<li>' . html::a(inlink('objectLibs', "type={$doc->type}&objectID=$objectID&libID={$doc->lib}&docID={$doc->id}"), "<i class='icon icon-file-text'></i> " . $doc->title, '', "data-app='$tab' title='{$doc->title}'") . '</li>';
        }

        $collectionCount = $this->dao->select('count(id) as count')->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->andWhere('lib')->in($allLibs)
            ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
            ->andWhere('collector')->like("%,{$this->app->user->account},%")
            ->fetch('count');
        if($collectionCount > $favoritesLimit) $html .= '<li>' . html::a(inlink('browse', "type=collectedByMe"), $this->lang->doc->allCollections) . '</li>';

        $html .= '</ul></div>';
        return $html;
    }

    /**
     * Build browse switch button.
     *
     * @param  int    $type
     * @param  int    $objectID
     * @param  int    $viewType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  string $searchTitle
     *
     * @access public
     * @return void
     */
    public function buildBrowseSwitch($type, $objectID, $viewType, $orderBy = 't1.id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1, $searchTitle = '')
    {
        $html = "<div class='btn-group'>";
        $html .= html::a(inlink('showFiles', "type=$type&objectID=$objectID&viewType=card&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&searchTitle=$searchTitle"), "<i class='icon icon-cards-view'></i>", '', "title={$this->lang->doc->browseTypeList['grid']} class='btn btn-icon" . ($viewType != 'list' ? ' text-primary' : '') . "' data-app='{$this->app->tab}'");
        $html .= html::a(inlink('showFiles', "type=$type&objectID=$objectID&viewType=list&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&searchTitle=$searchTitle"), "<i class='icon icon-bars'></i>", '', "title={$this->lang->doc->browseTypeList['list']} class='btn btn-icon" . ($viewType == 'list' ? ' text-primary' : '') . "' data-app='{$this->app->tab}'");
        $html .= "</div>";

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
        $actions = '';
        $actions .= '<a class="btn btn-link querybox-toggle" id="bysearchTab"><i class="icon icon-search muted"></i> ' . $this->lang->doc->search . '</a>';
        $actions .= "<a data-toggle='dropdown' class='btn btn-link' title=$fastLib>" . $fastLib . " <span class='caret'></span></a>";
        $actions .= "<ul class='dropdown-menu'>";
        foreach($this->lang->doc->fastMenuList as $key => $fastMenu)
        {
            $link    = helper::createLink('doc', 'browse', "libID=0&browseType={$key}");
            $actions .= '<li>' . html::a($link, "<i class='icon {$this->lang->doc->fastMenuIconList[$key]}'></i> {$fastMenu}") . '</li>';
        }
        $actions .= '</ul>';

        return $actions;
    }

    /**
     * Get toList and ccList.
     *
     * @param  object $doc
     * @access public
     * @return bool|array
     */
    public function getToAndCcList($doc)
    {
        /* Set toList and ccList. */
        $toList = '';
        $ccList = str_replace(' ', '', trim($doc->mailto, ','));

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

        $output            = '';
        $closedObjectsHtml = '';
        $closedObjects     = array();
        $maxHeight         = (in_array($type, array('project', 'execution')) and $this->app->tab == 'doc') ? '260px' : '290px';
        $class             = (in_array($type, array('project', 'execution')) and $this->app->tab == 'doc') ? 'col-left' : '';

        $currentMethod = $this->app->getMethodName();
        $methodName    = in_array($currentMethod, array('tablecontents', 'showfiles')) ? 'tablecontents' : 'objectLibs';

        if($this->app->tab == 'doc' and $type != 'custom' and $type != 'book')
        {
            $objectTitle = ($this->config->systemMode == 'new' and $type == 'execution') ? substr($objects[$objectID], strpos($objects[$objectID], '/') + 1) : $objects[$objectID];

            $output = <<<EOT
<div class='btn-group angle-btn'>
  <div class='btn-group'>
    <button data-toggle='dropdown' type='button' class='btn btn-limit' id='currentItem' title='{$objectTitle}'>
      <span class='text'>{$objectTitle}</span>
      <span class='caret'></span>
    </button>
    <div id='dropMenu' class='dropdown-menu search-list load-indicator' data-ride='searchList'>
    <div class="input-control search-box has-icon-left has-icon-right search-example">
      <input type="search" class="form-control search-input"/>
      <label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
      <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
    </div>
    <div class='list-group'>
      <div class='table-row'>
        <div class='table-col $class'>
          <div class='list-group' style='max-height: $maxHeight'>
EOT;
            if(in_array($type, array('project', 'execution')) and $this->app->tab == 'doc')
            {
                $closedObjects = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in(array_keys($objects))->andWhere('status')->eq('closed')->fetchPairs();
            }

            foreach($objects as $key => $object)
            {
                $selected = $key == $objectID ? 'selected' : '';
                if(isset($closedObjects[$key]))
                {
                    $closedObjectsHtml .= html::a(inlink($methodName, "type=$type&objectID=$key"), $object, '', "class='$selected' title='$object' data-app='{$this->app->tab}'");
                    if($selected == 'selected') $tabActive = 'closed';
                }
                else
                {
                    $output .= html::a(inlink($methodName, "type=$type&objectID=$key"), $object, '', "class='$selected' title='$object' data-app='{$this->app->tab}'");
                }
            }
            if(in_array($type, array('project', 'execution')) and $this->app->tab == 'doc')
            {
                $output .= <<<EOT
            </div>
            <div class='col-footer'>
              <a class='pull-right toggle-right-col not-list-item'>{$this->lang->project->doneProjects}<i class='icon icon-angle-right'></i></a>
            </div>
          </div>
          <div class='table-col col-right'>
            <div class='list-group'>$closedObjectsHtml</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
EOT;
            }
            else
            {
                $output .= "</div></div></div></div></div></div></div>";
            }
        }

        if(!empty($libs))
        {
            $libName = empty($libID) ? $this->lang->doclib->files : $libs[$libID]->name;
            $output  .= <<<EOT
<div class='btn-group angle-btn'>
  <div class='btn-group'>
    <button id='currentBranch' data-toggle='dropdown' type='button' class='btn btn-limit'>{$libName} <span class='caret'></span>
    </button>
    <div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList'>
      <div class="input-control search-box has-icon-left has-icon-right search-example">
        <input type="search" class="form-control search-input" />
        <label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
        <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
      </div>
      <div class='table-col'>
        <div class='list-group'>
EOT;
            foreach($libs as $key => $lib)
            {
                $selected = $key == $libID ? 'selected' : '';
                $docCount = isset($lib->docCount) ? $lib->docCount : 0;
                $output  .= html::a(inlink($methodName, "type=$type&objectID=$objectID&libID=$key"), "<span style='display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>$lib->name</span>&nbsp;<span>($docCount)</span>", '', "class='$selected' data-app='{$this->app->tab}' title='$lib->name ($docCount)' style='display: flex; justify-content: start;'");
            }
            if($type != 'custom' and $type != 'book')
            {
                $files     = $this->getLibFiles($type, $objectID, 't1.id_desc');
                $fileCount = count($files) > 99 ? '99+' : count($files);
                $selected  = empty($libID) ? 'selected' : '';
                $output   .= html::a(inlink('showFiles', "type=$type&objectID=$objectID"), "{$this->lang->doclib->files} ($fileCount)", '', "class='$selected' data-app='{$this->app->tab}' title='{$this->lang->doclib->files} ($fileCount)'");
            }
            if(count($libs) >= 2 and common::hasPriv('doc', 'sortLibs'))
            {
                $output .= '<li class="divider"></li>';
                $output .= html::a(inlink('sortLibs', "type=$type&objectID=$objectID", '', true), "<i class='icon-move'></i>  {$this->lang->doc->sortLibs}", '', "data-title='{$this->lang->doc->sortLibs}' data-toggle='modal' data-type='iframe' data-width='400px' data-app='{$this->app->tab}'");
            }
            $output .= "</div></div></div></div></div>";
        }

        return $output;
    }

    /**
     * Get api doc module tree
     *
     * @param  int     $rootID
     * @param  pointer $docID
     * @param  int     $release
     * @param  int     $moduleID
     * @access public
     * @return string
     */
    public function getApiModuleTree($rootID, &$docID = 0, $release = 0, $moduleID = 0)
    {
        $startModulePath = '';
        $currentMethod   = $this->app->getMethodName();
        $users           = $this->loadModel('user')->getPairs('noletter');
        $this->loadModel('api');

        if($release)
        {
            $rel  = $this->api->getRelease($rootID, 'byId', $release);
            $docs = $this->api->getApiListByRelease($rel);
        }
        else
        {
            $docs = $this->dao->select('*')->from(TABLE_API)
                ->where('lib')->eq($rootID)
                ->andWhere('deleted')->eq(0)
                ->fetchAll();
        }

        $moduleDocs = array();
        foreach($docs as $doc)
        {
            if(!isset($moduleDocs[$doc->module])) $moduleDocs[$doc->module] = array();
            $moduleDocs[$doc->module][] = $doc;
        }

        $treeMenu = array();
        if($release)
        {
            foreach($rel->snap['modules'] as $module)
            {
                $this->buildTree($treeMenu, 'api', 0, $rootID, (object)$module, $moduleDocs, $docID, $moduleID);
            }
        }
        else
        {
            $query = $this->dao->select('*')->from(TABLE_MODULE)
                ->where('root')->eq((int)$rootID)
                ->andWhere('type')->eq('api')
                ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
                ->andWhere('deleted')->eq(0)
                ->orderBy('grade desc, `order`')
                ->get();
            $stmt  = $this->dbh->query($query);
            while ($module = $stmt->fetch())
            {
                $this->buildTree($treeMenu, 'api', 0, $rootID, $module, $moduleDocs, $docID, $moduleID);
            }
        }


        if(isset($moduleDocs[0]))
        {
            if(!isset($treeMenu[0])) $treeMenu[0] = '';

            foreach($moduleDocs[0] as $doc)
            {
                $treeMenu[0] .= '<li' . ($doc->id == $docID ? ' class="active"' : ' class="independent"') . '>';
                $treeMenu[0] .=  "<div class='tree-group'><span class='module-name'>" . html::a(inlink('index', "libID=$rootID&moduelID=0&apiID={$doc->id}"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title, '', "data-app='{$this->app->tab}' class='doc-title' title='{$doc->title}'") . '</span>';

                if(common::hasPriv('api', 'edit'))
                {
                    $treeMenu[0] .= "<div class='tree-actions'>";
                    $treeMenu[0] .= html::a(helper::createLink('api', 'edit', "docID={$doc->id}"), "<i class='icon icon-edit'></i>", '', "title={$this->lang->doc->edit} data-app='{$this->app->tab}'");
                    $treeMenu[0] .= '</div>';
                }

                $treeMenu[0] .= '</div></li>';
            }
        }

        if(empty($treeMenu)) return '';

        $menu = "<ul id='modules' class='tree' data-ride='tree' data-name='tree-lib'>" . $treeMenu[0] . '</ul>';
        return $menu;

    }

    /**
     * Get doc tree menu.
     *
     * @param  string  $type
     * @param  int     $objectID
     * @param  int     $rootID
     * @param  int     $startModule
     * @param  pointer $docID
     * @access public
     * @return string
     */
    public function getTreeMenu($type, $objectID, $rootID, $startModule = 0, &$docID = 0)
    {
        $startModulePath = '';
        $currentMethod   = $this->app->getMethodName();
        $users           = $this->loadModel('user')->getPairs('noletter');
        if($startModule > 0)
        {
            $startModule = $this->tree->getById($startModule);
            if($startModule) $startModulePath = $startModule->path . '%';
        }

        $docs = $this->dao->select('*')->from(TABLE_DOC)
            ->where('lib')->eq($rootID)
            ->andWhere('deleted')->eq(0)
            ->orderBy('`order` asc')
            ->fetchAll();
        $moduleDocs = array();
        foreach($docs as $doc)
        {
            if(!$this->checkPrivDoc($doc)) continue;
            if(!isset($moduleDocs[$doc->module])) $moduleDocs[$doc->module] = array();
            $moduleDocs[$doc->module][] = $doc;
        }

        $treeMenu = array();
        $query    = $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->eq((int)$rootID)
            ->andWhere('type')->eq('doc')
            ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy('grade desc, `order`')
            ->get();
        $stmt     = $this->dbh->query($query);
        while ($module = $stmt->fetch())
        {
            $this->buildTree($treeMenu, $type, $objectID, $rootID, $module, $moduleDocs, $docID);
        }

        if(isset($moduleDocs[0]))
        {
            if(!isset($treeMenu[0])) $treeMenu[0] = '';

            foreach($moduleDocs[0] as $doc)
            {
                if(!$docID and $currentMethod != 'tablecontents') $docID = $doc->id;

                $class = common::hasPriv('doc', 'updateOrder') ? ' sortDoc' : '';
                $treeMenu[0] .= '<li' . " class='" . ($doc->id == $docID ? 'active' : 'doc') . "$class'" . " data-id=$doc->id>";

                if($currentMethod == 'tablecontents')
                {
                    $treeMenu[0] .= '<div class="tree-group"><span class="tail-info">' . zget($users, $doc->editedBy) . ' &nbsp;' . $doc->editedDate . '</span>';
                }
                if($currentMethod == 'objectlibs')
                {
                    $class = common::hasPriv('doc', 'updateOrder') ? 'sortDoc' : '';
                    $treeMenu[0] .= "<div class='tree-group'><span class='module-name'>" . html::a(inlink('objectLibs', "type=$type&objectID=$objectID&libID=$rootID&docID={$doc->id}"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title, '', "data-app='{$this->app->tab}' class='doc-title $class' title='{$doc->title}'") . '</span>';
                    if(common::hasPriv('doc', 'edit') or common::hasPriv('doc', 'updateOrder'))
                    {
                        $treeMenu[0] .= "<div class='tree-actions'>";
                        if(common::hasPriv('doc', 'edit')) $treeMenu[0] .= html::a(helper::createLink('doc', 'edit', "docID={$doc->id}&comment=false&objectType=$type&objectID=$objectID&libID=$rootID"), "<i class='icon icon-edit'></i>", '', "title={$this->lang->doc->edit} data-app='{$this->app->tab}'");
                        if(common::hasPriv('doc', 'updateOrder')) $treeMenu[0] .= html::a('javascript:;', "<i class='icon icon-move sortDoc'></i>", '', "title='{$this->lang->doc->updateOrder}' class='sortDoc'");
                        $treeMenu[0] .= '</div></div>';
                    }
                }
                else
                {
                    $class = common::hasPriv('doc', 'updateOrder') ? 'sortDoc' : '';
                    $treeMenu[0] .= html::a(inlink('objectLibs', "type=$type&objectID=$objectID&libID=$rootID&docID={$doc->id}"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title, '', "data-app='{$this->app->tab}' class='doc-title $class' title='{$doc->title}'");
                    $treeMenu[0] .= '</div>';
                }

                $treeMenu[0] .= '</li>';
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
     * @param  pointer $docID
     * @param  int     $moduleID
     * @access private
     * @return string
     */
    private function buildTree(&$treeMenu, $type, $objectID, $libID, $module, $moduleDocs, &$docID, $moduleID = 0)
    {
        if(!isset($treeMenu[$module->id])) $treeMenu[$module->id] = '';

        $users         = $this->loadModel('user')->getPairs('noletter');
        $currentMethod = $this->app->getMethodName();

        if(isset($moduleDocs[$module->id]))
        {
            foreach($moduleDocs[$module->id] as $doc)
            {
                if($type == static::DOC_TYPE_API)
                {
                    $treeMenu[$module->id] .= '<li' . ($doc->id == $docID ? ' class="active"' : ' class="doc"') . '>';

                    $treeMenu[$module->id] .= html::a(inlink('index', "libID=0&moduleID=0&apiID={$doc->id}"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title, '', "data-app='{$this->app->tab}' class='doc-title' title='{$doc->title}'");

                    $treeMenu[$module->id] .= '</li>';
                }
                else
                {
                    if(!$docID and $currentMethod != 'tablecontents') $docID = $doc->id;
                    $class = common::hasPriv('doc', 'updateOrder') ? ' sortDoc' : '';
                    $treeMenu[$module->id] .= '<li' . " class='" . ($doc->id == $docID ? 'active' : 'doc') . "$class'" . " data-id=$doc->id>";

                    if($currentMethod == 'tablecontents')
                    {
                        $treeMenu[$module->id] .= '<div class="tree-group"><span class="tail-info">' . zget($users, $doc->editedBy) . ' &nbsp;' . $doc->editedDate . '</span>';
                    }

                    if($currentMethod == 'objectlibs')
                    {
                        $class = common::hasPriv('doc', 'updateOrder') ? 'sortDoc' : '';
                        $treeMenu[$module->id] .= "<div class='tree-group'><span class='module-name'>" . html::a(inlink('objectLibs', "type=$type&objectID=$objectID&libID=$libID&docID={$doc->id}"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title, '', "data-app='{$this->app->tab}' class='doc-title $class' title='{$doc->title}'") . '</span>';
                        if(common::hasPriv('doc', 'edit') or common::hasPriv('doc', 'updateOrder'))
                        {
                            $treeMenu[$module->id] .= "<div class='tree-actions'>";
                            if(common::hasPriv('doc', 'edit')) $treeMenu[$module->id] .= html::a(helper::createLink('doc', 'edit', "docID={$doc->id}&comment=false&objectType=$type&objectID=$objectID&libID=$libID"), "<i class='icon icon-edit'></i>", '', "title={$this->lang->doc->edit} data-app={$this->app->tab}");
                            if(common::hasPriv('doc', 'updateOrder')) $treeMenu[$module->id] .= html::a('javascript:;', "<i class='icon icon-move sortDoc'></i>", '', "title='{$this->lang->doc->updateOrder}' class='sortDoc'");
                            $treeMenu[$module->id] .= '</div>';
                        }
                        $treeMenu[$module->id] .= '</div>';
                    }
                    elseif($currentMethod == 'tablecontents')
                    {
                        $class = common::hasPriv('doc', 'updateOrder') ? 'sortDoc' : '';
                        $treeMenu[$module->id] .= html::a(inlink('objectLibs', "type=$type&objectID=$objectID&libID=$libID&docID={$doc->id}"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title, '', "data-app='{$this->app->tab}' class='doc-title $class' title='{$doc->title}'");
                        $treeMenu[$module->id] .= '</div>';
                    }

                    $treeMenu[$module->id] .= '</li>';
                }
            }
        }

        if($type == static::DOC_TYPE_API)
        {
            $li = html::a(inlink('index', "libID=$libID&moduleID={$module->id}"), $module->name, '', "data-app='{$this->app->tab}' class='doc-title' title='{$module->name}'");
        }
        else
        {
            $moduleClass = common::hasPriv('tree', 'updateOrder') ? 'sort-module' : '';
            $li          = "<div class='tree-group'><span class='module-name'><a class='$moduleClass' title='{$module->name}'>" . $module->name . '</a></span>';
            if($currentMethod != 'tablecontents')
            {
                if(common::hasPriv('tree', 'edit') or common::hasPriv('tree', 'browse') or common::hasPriv('tree', 'browse') or common::hasPriv('tree', 'updateOrder'))
                {
                    $li .= "<div class='tree-actions'>";
                    if(common::hasPriv('tree', 'edit'))   $li .= html::a(helper::createLink('tree', 'edit', "module=$module->id&type=doc"), "<i class='icon icon-edit'></i>", '', "data-toggle='modal' title='{$this->lang->doc->editType}'");
                    if(common::hasPriv('tree', 'browse')) $li .= html::a(helper::createLink('tree', 'browse', "rootID=$libID&type=doc&module=$module->id", '', 1), "<i class='icon icon-split'></i>", '', "class='iframe' title='{$this->lang->doc->editChildType}'");
                    if(common::hasPriv('tree', 'updateOrder')) $li .= html::a('javascript:;', "<i class='icon icon-move sortModule'></i>", '', "title='{$this->lang->doc->updateOrder}' class='sortModule'");
                    $li .= '</div>';
                }
            }
            $li .= '</div>';
        }
        if($treeMenu[$module->id])
        {
            $li .= '<ul>' . $treeMenu[$module->id] . '</ul>';
        }

        if(!isset($treeMenu[$module->parent])) $treeMenu[$module->parent] = '';

        $class = array('catalog');
        if($treeMenu[$module->id])
        {
            array_push($class, 'closed');
        }

        if($type == static::DOC_TYPE_API)
        {
            if($moduleID and $moduleID == $module->id)
            {
                array_push($class, 'active');
            }
            else
            {
                array_push($class, 'doc');
            }
        }

        $treeMenu[$module->parent] .= '<li class=" can-sort ' . implode(' ', $class) . '" data-id=' . $module->id . '>' . $li . '</li>';
    }

    /**
     * Count the number and size of files on the current page.
     *
     * @param  arary $files
     * @access public
     * @return string
     */
    public function summary($files)
    {
        $filesCount       = 0;
        $sizeCount        = 0;
        $extensionCount   = array();
        $extensionSummary = '';

        foreach($files as $file)
        {
            if(!isset($extensionCount[$file->extension])) $extensionCount[$file->extension] = 0;

            $filesCount++;

            $sizeCount += $file->size;

            $extensionCount[$file->extension]++;
        }

        /* Unit conversion. */
        $i = 0;
        while ($sizeCount > 1024 and $i <= 4)
        {
            $sizeCount = $sizeCount / 1024;
            $i++;
        }
        $unitList  = array('B', 'K', 'M', 'G', 'T');
        $sizeCount = round($sizeCount, 1) . $unitList[$i];

        /* Summary of each type. */
        foreach($extensionCount as $extension => $count)
        {
            if(in_array($this->app->getClientLang(), array('zh-cn', 'zh-tw')))
            {
                $extensionSummary .= $extension . ' ' . $count . $this->lang->doc->ge . $this->lang->doc->point;
            }
            else
            {
                $extensionSummary .= $extension . ' ' . $this->lang->doc->ge . ' ' . $count . $this->lang->doc->point;
            }
        }
        $extensionSummary = rtrim($extensionSummary, $this->lang->doc->point);

        return sprintf($this->lang->doc->summary, $filesCount, $sizeCount, $extensionSummary);
    }

    /**
     * Set doc menu by type.
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  int    $libID
     * @param  int    $appendLib
     * @access public
     * @return array
     */
    public function setMenuByType($type, $objectID, $libID, $appendLib = 0)
    {
        if(empty($type))
        {
            $doclib   = $this->getLibById($libID);
            $type     = $doclib->type == 'execution' ? 'project' : $doclib->type;
            $objectID = $type == 'custom' or $type == 'book' ? 0 : $doclib->$type;
        }

        $this->session->set('docList', $this->app->getURI(true), $this->app->tab);

        $objects = $this->getOrderedObjects($type);

        if($type == 'custom')
        {
            $libs                 = $this->getLibsByObject('custom', 0, '', $appendLib);
            $this->app->rawMethod = 'custom';
            if($libID == 0 and !empty($libs)) $libID = reset($libs)->id;
            $this->lang->modulePageNav = $this->select($type, $objects, $objectID, $libs, $libID);

            $object     = new stdclass();
            $object->id = 0;
        }
        elseif($type == 'book')
        {
            $libs                 = $this->getLibsByObject('book', 0, '', $appendLib);
            $this->app->rawMethod = 'book';
            if(!empty($libs) and ($libID == 0 or !isset($libs[$libID]))) $libID = reset($libs)->id;
            $this->lang->modulePageNav = $this->select($type, $objects, $objectID, $libs, $libID);

            $object     = new stdclass();
            $object->id = 0;
        }
        else
        {
            $objectID = $this->loadModel($type)->saveState($objectID, $objects);
            $table    = $this->config->objectTables[$type];
            $libs     = $this->getLibsByObject($type, $objectID, '', $appendLib);

            if($libID == 0 and !empty($libs)) $libID = reset($libs)->id;
            $this->lang->modulePageNav = $this->select($type, $objects, $objectID, $libs, $libID);

            if($this->app->tab == 'doc') $this->app->rawMethod = $type;

            $object = $this->dao->select('id,name,status')->from($table)->where('id')->eq($objectID)->fetch();

            if(empty($object))
            {
                $param = ($type == 'project' and $this->config->vision == 'lite') ? 'model=kanban' : '';
                $methodName = ($type == 'project' and $this->config->vision != 'lite') ? 'createGuide' : 'create';
                return print(js::locate(helper::createLink($type, $methodName, $param)));
            }
        }

        $tab = strpos(',doc,product,project,execution,', ",{$this->app->tab},") !== false ? $this->app->tab : 'doc';
        if($tab != 'doc') $this->loadModel($tab)->setMenu($objectID);

        $this->lang->TRActions  = $this->buildCollectButton4Doc();
        $this->lang->TRActions .= $this->buildCreateButton4Doc($type, $objectID, $libID);

        return array($libs, $libID, $object, $objectID);
    }

    /**
     * Whether the url of link type documents needs to be autoloaded.
     *
     * @param  object  $doc
     * @access public
     * @return bool
     */
    public function checkAutoloadPage($doc)
    {
        $autoloadPage = true;
        if(!empty($doc) and $doc->type == 'url')
        {
            if(empty($doc->content)) return false;

            if(!preg_match('/^https?:\/\//', $doc->content)) $doc->content = 'http://' . $doc->content;
            $parsedUrl = parse_url($doc->content);
            $urlPort   = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
            $urlDomain = $parsedUrl['host'] . $urlPort;
            if($urlDomain == $_SERVER['HTTP_HOST']) $autoloadPage = false;
        }

        return $autoloadPage;
    }

    /**
     * Get docs by search.
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  int    $libID
     * @param  int    $queryID
     * @param  object $pager
     *
     * @access public
     * @return array
     */
    public function getDocsBySearch($type, $objectID, $libID, $queryID, $pager)
    {
        $queryName = $type . 'DocQuery';
        $queryForm = $type . 'DocForm';
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set($queryForm, $query->form);
            }
            else
            {
                $this->session->set($queryName, ' 1 = 1');
            }
        }
        else
        {
            if($this->session->$queryName == false) $this->session->set($queryName, ' 1 = 1');
        }

        $libs  = $this->getLibsByObject($type, $objectID);
        $query = $this->session->$queryName;
        $query = strpos($query, "`lib` = 'all'") === false ? "$query and lib = $libID" : str_replace("`lib` = 'all'", '1', $query);
        $docs  = $this->dao->select('*')->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->andWhere($query)
            ->andWhere('lib')->in(array_keys($libs))
            ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
            ->orderBy('id_desc')
            ->page($pager)
            ->fetchAll('id');

        $docContents = $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->in(array_keys($docs))->orderBy('version,doc')->fetchAll('doc');

        $files = $this->dao->select('*')->from(TABLE_FILE)
            ->where('objectType')->eq('doc')
            ->andWhere('objectID')->in(array_keys($docs))
            ->fetchGroup('objectID');
        foreach($docs as $docID => $doc)
        {
            $docs[$docID]->fileSize = 0;
            if(isset($files[$docID]))
            {
                $docContent = $docContents[$docID];
                $fileSize   = 0;
                foreach($files[$docID] as $file)
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
                    $fileSize = round($fileSize / 1024 / 1024 / 1024, 2) . 'G';
                }

                $docs[$docID]->fileSize = $fileSize;
            }
        }
        return $docs;
    }

    /**
     * Update Lib orders.
     *
     * @access public
     * @return void
     */
    public function updateLibOrder()
    {
        $libIdList = $this->post->libIdList;
        $libIdList = explode(',', $libIdList);

        $order = 1;
        foreach($libIdList as $libID)
        {
            if(!$libID) continue;

            $this->dao->update(TABLE_DOCLIB)->set('`order`')->eq($order * 10)->where('id')->eq($libID)->exec();

            $order++;
        }
    }
}
