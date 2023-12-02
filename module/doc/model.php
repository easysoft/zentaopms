<?php
/**
 * The model file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: model.php 881 2010-06-22 06:50:32Z chencongzhi520 $
 * @link        http://www.zentao.net
 */

class docModel extends model
{

    /**
     * @var actionModel
     */
    public $action;

    // api doc type
    const DOC_TYPE_API  = 'api';
    const DOC_TYPE_REST = 'restapi';

    /**
     * 通过ID获取文档库信息。
     * Get library by id.
     *
     * @param  int         $libID
     * @access public
     * @return object|false
     */
    public function getLibByID(int $libID): object|bool
    {
        return $this->dao->findByID($libID)->from(TABLE_DOCLIB)->fetch();
    }

    /**
     * Get api Libraries.
     *
     * @param  int    $appendLib
     * @param  string $objectType
     * @param  int    $objectID
     * @return array
     */
    public function getApiLibs($appendLib = 0, $objectType = '', $objectID = 0)
    {
        $libs = $this->dao->select('*')->from(TABLE_DOCLIB)
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq('api')
            ->beginIF(!empty($objectType) and $objectID > 0 and $objectType != 'nolink')->andWhere($objectType)->eq($objectID)->fi()
            ->beginIF($objectType == 'nolink')
            ->andWhere('product')->eq(0)
            ->andWhere('project')->eq(0)
            ->andWhere('execution')->eq(0)
            ->fi()
            ->beginIF(!empty($appendLib))->orWhere('id')->eq($appendLib)->fi()
            ->orderBy('order_asc, id_asc')
            ->fetchAll('id');

        $libs = array_filter($libs, array($this, 'checkPrivLib'));
        return $libs;
    }

    /**
     * Get libraries.
     *
     * @param  string $type all|includeDeleted|hasApi|mine|product|project|execution|custom
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
        if(in_array($type, array('all', 'includeDeleted', 'hasApi')))
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)
                ->where('vision')->eq($this->config->vision)
                ->beginIF($type == 'all')->andWhere('deleted')->eq(0)->fi()
                ->beginIF($type != 'hasApi')->andWhere('type')->ne('api')->fi()
                ->beginIF($excludeType)->andWhere('type')->notin($excludeType)->fi()
                ->orderBy('id_asc')
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
                ->orderBy('id_asc')
                ->query();
        }

        $products   = $this->loadModel('product')->getPairs();
        $projects   = $this->loadModel('project')->getPairsByProgram(0, 'all', false, 'order_asc', $this->config->vision == 'rnd' ? 'kanban' : '');
        $executions = $this->loadModel('execution')->getPairs(0, 'sprint,stage', 'multiple,leaf');
        $waterfalls = array();
        if(empty($objectID) and $type == 'execution')
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
                    if($lib->execution != 0)
                    {
                        $lib->name = zget($executions, $lib->execution, '') . ' / ' . $lib->name;
                        $lib->name = ltrim($lib->name, '/');
                        if(!empty($waterfalls[$lib->execution])) $lib->name = $waterfalls[$lib->execution] . ' / ' . $lib->name;
                        $lib->name = trim($lib->name, '/');
                    }

                    if($lib->project != 0)     $lib->name = zget($projects, $lib->project, '') . ' / ' . $lib->name;
                    if($lib->type == 'mine')   $lib->name = $this->lang->doc->person . ' / ' . $lib->name;
                    if($lib->type == 'custom') $lib->name = $this->lang->doc->team . ' / ' . $lib->name;
                }

                $libPairs[$lib->id] = $lib->name;
            }
        }

        if(!empty($appendLibs))
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)->where('id')->in($appendLibs)->orderBy("`order`_asc, id_asc")->query();
            while($lib = $stmt->fetch())
            {
                if(!isset($libPairs[$lib->id]) and $this->checkPrivLib($lib, $extra)) $libPairs[$lib->id] = $lib->name;
            }
        }

        return $libPairs;
    }

    /**
     * 获取有权限访问的文档库。
     * Get grant libs by doc.
     *
     * @access public
     * @return array
     */
    public function getPrivLibsByDoc(): array
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
                elseif($lib->groups)
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
     * 创建一个文档库。
     * Create a lib.
     *
     * @param  object   $lib
     * @param  string   $libType wiki|api
     * @param  string   $type    api|project|product|execution|custom|mine
     * @access public
     * @return int|bool
     */
    public function createLib(object $lib, string $type = '', string $libType = ''): int|bool
    {
        if($lib->execution) $lib->type = 'execution';
        if($lib->type == 'execution' && $lib->execution && !$lib->project)
        {
            $execution    = $this->loadModel('execution')->getByID($lib->execution);
            $lib->project = $execution->project;
        }
        if($libType == 'api')
        {
            $lib->type = 'api';
            $this->checkApiLibName($lib, $type);
        }

        $this->dao->insert(TABLE_DOCLIB)->data($lib)->autoCheck()
            ->batchCheck($this->config->doc->createlib->requiredFields, 'notempty')
            ->exec();

        if(dao::isError()) return false;
        return $this->dao->lastInsertID();
    }

    /**
     * Creat a api doc library.
     *
     * @param  object $formData
     * @access public
     * @return void
     */
    public function createApiLib(object $formData = null)
    {
        if(!$formData)
        {
            $libType = $this->post->libType;
            $formData = fixer::input('post')
                ->trim('name')
                ->join('groups', ',')
                ->join('users', ',')
                ->setForce('product', ($libType == 'product' and !empty($_POST['product'])) ? $this->post->product : 0)
                ->setForce('project', ($libType == 'project' and !empty($_POST['project'])) ? $this->post->project : 0)
                ->setForce('execution', ($libType == 'project' and !empty($_POST['execution'])) ? $this->post->execution : 0)
                ->add('addedBy', $this->app->user->account)
                ->add('addedDate', helper::now())
                ->remove('uid,contactListMenu')
                ->get();
        }

        $this->app->loadLang('api');

        /* Replace doc library name. */
        $this->lang->doclib->name    = $this->lang->doclib->apiLibName;
        $this->lang->doclib->baseUrl = $this->lang->api->baseUrl;
        $this->lang->doclib->project = $this->lang->api->project;
        $this->lang->doclib->product = $this->lang->api->product;

        if($formData->libType == 'product') $this->config->api->createlib->requiredFields .= ',product';
        if($formData->libType == 'project') $this->config->api->createlib->requiredFields .= ',project';

        $this->checkApiLibName($formData, $formData->libType);

        if(dao::isError()) return false;

        $formData->type = static::DOC_TYPE_API;
        $this->dao->insert(TABLE_DOCLIB)->data($formData, 'libType')->autoCheck()
            ->batchCheck($this->config->api->createlib->requiredFields, 'notempty')
            ->exec();

        $libID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('doclib', $libID, 'created');

        return !dao::isError();
    }

    /**
     * Update api lib.
     *
     * @param  int       $id
     * @param  object    $formData
     * @access public
     * @return array|int
     */
    public function updateApiLib($id, $formData = null)
    {
        $oldLib = $this->getLibByID($id);

        if(!$formData)
        {
            $formData = fixer::input('post')
                ->trim('name')
                ->add('product', $oldLib->product)
                ->add('project', $oldLib->project)
                ->join('groups', ',')
                ->join('users', ',')
                ->remove('uid,contactListMenu')
                ->get();
        }

        $this->app->loadLang('api');

        /* Replace doc library name. */
        $this->lang->doclib->name    = $this->lang->doclib->apiLibName;
        $this->lang->doclib->baseUrl = $this->lang->api->baseUrl;
        $this->lang->doclib->project = $this->lang->api->project;
        $this->lang->doclib->product = $this->lang->api->product;

        $this->checkApiLibName($formData, $formData->type, $id);

        if(dao::isError()) return false;

        $formData->type = static::DOC_TYPE_API;
        $this->dao->update(TABLE_DOCLIB)->data($formData, 'type')->autoCheck()
            ->batchCheck($this->config->api->editlib->requiredFields, 'notempty')
            ->where('id')->eq($id)
            ->exec();

        if(dao::isError()) return false;

        $changes  = common::createChanges($oldLib, $formData);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('docLib', $id, 'Edited');
            $this->action->logHistory($actionID, $changes);
        }
        return $changes;
    }

    /**
     * 更新一个文档库。
     * Update a doc lib.
     *
     * @param  int        $libID
     * @param  object     $lib
     * @access public
     * @return array|bool
     */
    public function updateLib(int $libID, object $lib): array|bool
    {
        $oldLib = $this->getLibByID($libID);
        if($oldLib->type == 'project')
        {
            $libCreatedBy = $this->dao->select('*')->from(TABLE_ACTION)->where('objectType')->eq('doclib')->andWhere('objectID')->eq($libID)->andWhere('action')->eq('created')->fetch('actor');

            $openedBy = $this->dao->findById($oldLib->project)->from(TABLE_PROJECT)->fetch('openedBy');
            if($lib->acl == 'private' && $lib->acl == 'custom') $lib->users .= ',' . $libCreatedBy ? $libCreatedBy : $openedBy;
        }
        if($oldLib->acl != $lib->acl && $lib->acl == 'open') $lib->users = '';

        if($oldLib->type == 'api')
        {
            $type = 'nolink';
            if(!empty($oldLib->product)) $type = 'product';
            if(!empty($oldLib->project)) $type = 'project';
            $lib->product = $oldLib->product;
            $lib->project = $oldLib->project;
            $this->checkApiLibName($lib, $type, $libID);
        }

        $this->dao->update(TABLE_DOCLIB)->data($lib)->autoCheck()
            ->batchCheck($this->config->doc->editlib->requiredFields, 'notempty')
            ->where('id')->eq($libID)
            ->exec();

        if(dao::isError()) return false;
        return common::createChanges($oldLib, $lib);
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
        if($browseType == 'all')
        {
            $docs = $this->getDocs(0, 0, $browseType, $sort, $pager);
        }
        else
        {
            $allLibs          = $this->getLibs('all');
            $allLibIDList     = array_keys($allLibs);
            $hasPrivDocIdList = $this->getPrivDocs($allLibIDList, $moduleID);
        }

        if($browseType == 'bySearch')
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
            $query     = preg_replace('/(`\w+`)/', 't1.$1', $query);
            $docIDList = $this->dao->select('objectID')->from(TABLE_ACTION)
                ->where('objectType')->eq('doc')
                ->andWhere('objectID')->in($hasPrivDocIdList)
                ->andWhere('templateType')->eq('')
                ->andWhere('actor')->eq($this->app->user->account)
                ->andWhere('action')->eq('edited')
                ->fetchAll('objectID');
            $docs = $this->dao->select('t1.*, t2.name as libName, t2.type as objectType')->from(TABLE_DOC)->alias('t1')
                ->leftJoin(TABLE_DOCLIB)->alias('t2')->on("t1.lib=t2.id")
                ->where('t1.deleted')->eq(0)
                ->andWhere($query)
                ->andWhere('t1.lib')->in($allLibIDList)
                ->andWhere('t1.templateType')->eq('')
                ->andWhere('t1.vision')->in($this->config->vision)
                ->beginIF($this->config->doc->notArticleType)->andWhere('t1.type')->notIN($this->config->doc->notArticleType)->fi()
                ->andWhere('t1.addedBy', 1)->eq($this->app->user->account)
                ->orWhere('t1.id')->in(array_keys($docIDList))
                ->markRight(1)
                ->orderBy($sort)
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($browseType == "openedbyme")
        {
            $docs = $this->dao->select('t1.*, t2.name as libName, t2.type as objectType')->from(TABLE_DOC)->alias('t1')
                ->leftJoin(TABLE_DOCLIB)->alias('t2')->on("t1.lib=t2.id")
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.lib')->ne('')
                ->andWhere('t1.id')->in($hasPrivDocIdList)
                ->beginIF($this->config->doc->notArticleType)->andWhere('t1.type')->notIN($this->config->doc->notArticleType)->fi()
                ->andWhere('t1.addedBy')->eq($this->app->user->account)
                ->andWhere('t1.vision')->in($this->config->vision)
                ->andWhere('t1.templateType')->eq('')
                ->orderBy($sort)
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($browseType == 'editedbyme')
        {
            $docIdList = $this->dao->select('objectID')->from(TABLE_ACTION)
                ->where('objectType')->eq('doc')
                ->andWhere('actor')->eq($this->app->user->account)
                ->andWhere('action')->eq('edited')
                ->andWhere('vision')->eq($this->config->vision)
                ->fetchAll('objectID');

            $docs = $this->dao->select('t1.*, t2.name as libName, t2.type as objectType')->from(TABLE_DOC)->alias('t1')
                ->leftJoin(TABLE_DOCLIB)->alias('t2')->on("t1.lib=t2.id")
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.id')->in(array_keys($docIdList))
                ->andWhere('t1.lib')->ne('')
                ->andWhere('t1.vision')->in($this->config->vision)
                ->beginIF($this->config->doc->notArticleType)->andWhere('t1.type')->notIN($this->config->doc->notArticleType)->fi()
                ->orderBy($sort)
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($browseType == 'byediteddate')
        {
            $docs = $this->dao->select('*')->from(TABLE_DOC)
                ->where('deleted')->eq(0)
                ->andWhere('id')->in($hasPrivDocIdList)
                ->andWhere('templateType')->eq('')
                ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
                ->andWhere('lib')->in($allLibIDList)
                ->andWhere('vision')->in($this->config->vision)
                ->orderBy('editedDate_desc')
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($browseType == "collectedbyme")
        {
            $docs = $this->dao->select('t1.*')->from(TABLE_DOC)->alias('t1')
                ->leftJoin(TABLE_DOCACTION)->alias('t2')->on("t1.id=t2.doc && t2.action='collect'")
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.lib')->ne('')
                ->andWhere('t1.templateType')->eq('')
                ->andWhere('t1.id')->in($hasPrivDocIdList)
                ->beginIF($this->config->doc->notArticleType)->andWhere('t1.type')->notIN($this->config->doc->notArticleType)->fi()
                ->andWhere('t2.actor')->eq($this->app->user->account)
                ->andWhere('t1.vision')->in($this->config->vision)
                ->orderBy($sort)
                ->page($pager)
                ->fetchAll('id');
        }

        if(empty($docs)) return array();

        if(in_array($browseType, array('bySearch', 'openedbyme', 'editedbyme')))
        {
            $objects = array();
            list($objects['project'], $objects['execution'], $objects['product']) = $this->getObjectsByDoc(array_keys($docs));
            foreach($docs as $docID => $doc)
            {
                $doc->objectID   = zget($doc, $doc->objectType, 0);
                $doc->objectName = '';
                if(isset($objects[$doc->objectType]))
                {
                    $doc->objectName = $objects[$doc->objectType][$doc->objectID];
                }
                else
                {
                    if($doc->objectType == 'mine')   $doc->objectName = $this->lang->doc->person;
                    if($doc->objectType == 'custom') $doc->objectName = $this->lang->doc->team;
                }
            }
        }

        return $this->processCollector($docs);
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
        $projects = $executions = $products = array();
        if(empty($docIdList)) return array($projects, $executions, $products);

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
     * 获取当前文档库下的文档列表数据。
     * Get doc list by libID.
     *
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  string $browseType all|draft
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getDocs(int $libID, int $moduleID, string $browseType, string $orderBy, object $pager = null): array
    {
        if(empty($libID) && $browseType != 'all') return array();

        $docIdList = $this->getPrivDocs(array($libID), $moduleID, 'children');
        $docs = $this->dao->select('*')->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('templateType')->eq('')
            ->andWhere('id')->in($docIdList)
            ->beginIF($browseType == 'all')->andWhere("(status = 'normal' or (status = 'draft' and addedBy='{$this->app->user->account}'))")->fi()
            ->beginIF($browseType == 'draft')->andWhere('status')->eq('draft')->andWhere('addedBy')->eq($this->app->user->account)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        return $this->processCollector($docs);
    }

    /**
     * 获取我的空间下的文档列表数据。
     * Get mine list.
     *
     * @param  string $type       view|collect|createdby|editedby
     * @param  string $browseType all|draft|bysearch
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getMineList(string $type, string $browseType, int $queryID = 0, string $orderBy = 'id_desc', object $pager = null): array
    {
        $query = '';
        if($browseType == 'bysearch')
        {
            $query = $this->buildQuery($type, $queryID);
            $query = preg_replace('/(`\w+`)/', 't1.$1', $query);
        }
        if(in_array($type, array('view', 'collect', 'createdby', 'editedby'))) $docs = $this->getMySpaceDocs($type, $browseType, $query, $orderBy, $pager);

        $this->loadModel('tree');
        $objects = array();
        $modules = array();
        list($objects['project'], $objects['execution'], $objects['product']) = $this->getObjectsByDoc(array_keys($docs));
        foreach($docs as $docID => $doc)
        {
            if(!isset($modules[$doc->lib])) $modules[$doc->lib] = $this->tree->getOptionMenu($doc->lib, 'doc', 0, 0, 'nodeleted', 'all', ' > ');
            $doc->moduleName = zget($modules[$doc->lib], $doc->module);
            $doc->moduleName = ltrim($doc->moduleName, '/');

            $doc->objectID   = zget($doc, $doc->objectType, 0);
            $doc->objectName = '';
            if(isset($objects[$doc->objectType]))
            {
                $doc->objectName = $objects[$doc->objectType][$doc->objectID];
            }
            else
            {
                if($doc->objectType == 'mine')   $doc->objectName = $this->lang->doc->person;
                if($doc->objectType == 'custom') $doc->objectName = $this->lang->doc->team;
            }
        }

        return $this->processCollector($docs);
    }

    /**
     * 获取我的空间下的文档列表数据。
     * Get doc list under the my space.
     *
     * @param  string $type       view|collect|createdby|editedby
     * @param  string $browseType all|draft|bysearch
     * @param  string $orderBy
     * @param  string $query
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getMySpaceDocs(string $type, string $browseType, string $query = '', string $orderBy = 'id_desc', object $pager = null): array
    {
        if(!in_array($type, array('view', 'collect', 'createdby', 'editedby'))) return array();

        $allLibs          = $this->getLibs('all');
        $allLibIDList     = array_keys($allLibs);
        $hasPrivDocIdList = $this->getPrivDocs($allLibIDList);
        if(in_array($type, array('view', 'collect')))
        {
            $docs = $this->dao->select('t1.*,t3.name as libName,t3.type as objectType,max(t2.`date`) as date')->from(TABLE_DOC)->alias('t1')->leftJoin(TABLE_DOCACTION)->alias('t2')->on("t1.id=t2.doc")->leftJoin(TABLE_DOCLIB)->alias('t3')->on("t1.lib=t3.id")
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.lib')->ne('')
                ->andWhere('t1.type')->in($this->config->doc->docTypes)
                ->andWhere('t1.vision')->eq($this->config->vision)
                ->andWhere('t2.action')->eq($type)
                ->andWhere('t2.actor')->eq($this->app->user->account)
                ->beginIF(!common::hasPriv('doc', 'productSpace'))->andWhere('t3.type')->ne('product')->fi()
                ->beginIF(!common::hasPriv('doc', 'projectSpace'))->andWhere('t3.type')->notIN('project,execution')->fi()
                ->beginIF(!common::hasPriv('doc', 'teamSpace'))->andWhere('t3.type')->ne('custom')->fi()
                ->beginIF(in_array($browseType, array('all', 'bysearch')))->andWhere("(t1.status = 'normal' or (t1.status = 'draft' and t1.addedBy='{$this->app->user->account}'))")->fi()
                ->beginIF($browseType == 'draft')->andWhere('t1.status')->eq('draft')->andWhere('t1.addedBy')->eq($this->app->user->account)->fi()
                ->beginIF($browseType == 'bysearch')->andWhere($query)->fi()
                ->beginIF(!empty($hasPrivDocIdList))->andWhere('t1.id')->in($hasPrivDocIdList)->fi()
                ->groupBy('t1.id')
                ->orderBy($orderBy)
                ->page($pager, 't1.id')
                ->fetchAll('id');
        }
        else
        {
            $docIdList = $type == 'editedby' ? $this->docTao->getEditedDocIdList() : array();
            $docs = $this->dao->select('t1.*,t2.name as libName,t2.type as objectType')->from(TABLE_DOC)->alias('t1')->leftJoin(TABLE_DOCLIB)->alias('t2')->on("t1.lib=t2.id")
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.lib')->ne('')
                ->andWhere('t1.vision')->eq($this->config->vision)
                ->andWhere('t1.type')->in($this->config->doc->docTypes)
                ->beginIF($type == 'createdby')->andWhere('t1.addedBy')->eq($this->app->user->account)->fi()
                ->beginIF($type == 'editedby')->andWhere('t1.id')->in($docIdList)->fi()
                ->beginIF(!common::hasPriv('doc', 'productSpace'))->andWhere('t2.type')->ne('product')->fi()
                ->beginIF(!common::hasPriv('doc', 'projectSpace'))->andWhere('t2.type')->notIN('project,execution')->fi()
                ->beginIF(!common::hasPriv('doc', 'teamSpace'))->andWhere('t2.type')->ne('custom')->fi()
                ->beginIF($browseType == 'draft')->andWhere('t1.status')->eq('draft')->andWhere('t1.addedBy')->eq($this->app->user->account)->fi()
                ->beginIF($browseType == 'bysearch')->andWhere($query)->fi()
                ->beginIF(!empty($hasPrivDocIdList))->andWhere('t1.id')->in($hasPrivDocIdList)->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        return $docs;
    }

    /**
     * 获取有权限查看的文档ID列表。
     * Get grant doc id list.
     *
     * @param  array  $libIdList
     * @param  int    $moduleID
     * @param  string $mode normal|all|chidren
     * @access public
     * @return array
     */
    public function getPrivDocs(array $libIdList = array(), int $moduleID = 0, string $mode = 'normal'): array
    {
        $modules = $moduleID && $mode == 'children' ? $this->loadModel('tree')->getAllChildID($moduleID) : $moduleID;

        $stmt = $this->dao->select('*')->from(TABLE_DOC)
            ->where('vision')->eq($this->config->vision)
            ->andWhere('templateType')->eq('')
            ->beginIF(!empty($modules))->andWhere('module')->in($modules)->fi()
            ->beginIF($mode == 'normal')->andWhere('deleted')->eq(0)->fi()
            ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
            ->beginIF($libIdList)->andWhere('lib')->in($libIdList)->fi()
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

        $docs = $this->processCollector(array($doc->id => $doc));
        $doc  = $docs[$doc->id];

        $version    = $version ? $version : $doc->version;
        $docContent = $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq($doc->id)->andWhere('version')->eq($version)->fetch();

        $doc->releasedBy   = '';
        $doc->releasedDate = '';
        if($doc->status == 'normal')
        {
            $releaseInfo = $this->dao->select('*')->from(TABLE_ACTION)
                ->where('objectType')->eq('doc')
                ->andWhere('objectID')->eq($docID)
                ->andWhere('action')->eq('releaseddoc')
                ->fetch();
            $doc->releasedBy   = $releaseInfo ? $releaseInfo->actor : $doc->addedBy;
            $doc->releasedDate = $releaseInfo ? $releaseInfo->date : $doc->addedDate;
        }

        /* When file change then version add one. */
        $files    = $this->loadModel('file')->getByObject('doc', $docID);
        $docFiles = array();
        if($docContent)
        {
            foreach($files as $file)
            {
                $this->loadModel('file')->setFileWebAndRealPaths($file);
                if(strpos(",{$docContent->files},", ",{$file->id},") !== false) $docFiles[$file->id] = $file;
            }
        }

        /* Check file change. */
        if($version == $doc->version and ((empty($docContent->files) and $docFiles) or ($docContent->files and count(explode(',', trim($docContent->files, ','))) != count($docFiles))))
        {
            unset($docContent->id);
            $doc->version       += 1;
            $docContent->version = $doc->version;
            $docContent->files   = join(',', array_keys($docFiles));
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
            $this->dao->update(TABLE_DOC)->set('version')->eq($doc->version)->where('id')->eq($doc->id)->exec();
        }

        $doc->title          = isset($docContent->title) ? $docContent->title : '';
        $doc->digest         = isset($docContent->digest) ? $docContent->digest : '';
        $doc->content        = isset($docContent->content) ? $docContent->content : '';
        $doc->contentType    = isset($docContent->type) ? $docContent->type : '';
        $doc->contentVersion = isset($docContent->version) ? $docContent->version : $version;


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
        return $this->dao->select('*,t1.id as docID,t1.type as docType,t1.version as docVersion,t2.type as contentType')->from(TABLE_DOC)->alias('t1')
            ->leftJoin(TABLE_DOCCONTENT)->alias('t2')->on('t1.id=t2.doc and t1.version=t2.version')
            ->where('t1.id')->in($docIdList)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('docID');
    }

    /**
     * Create a seperate docs.
     *
     * @access public
     * @return void
     */
    public function createSeperateDocs()
    {
        if(!isset($_POST['lib']) and strpos($_POST['module'], '_') !== false) list($_POST['lib'], $_POST['module']) = explode('_', $_POST['module']);
        $now = helper::now();
        $doc = fixer::input('post')
            ->setDefault('content,template,templateType,chapterType', '')
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
            ->remove('files,labels,uid,contactListMenu,uploadFormat')
            ->get();

        if($doc->acl == 'open') $doc->users = $doc->groups = '';
        if(empty($doc->lib) and strpos($doc->module, '_') !== false) list($doc->lib, $doc->module) = explode('_', $doc->module);
        if(empty($doc->lib)) return dao::$errors['lib'] = sprintf($this->lang->error->notempty, $this->lang->doc->lib);
        $files = $this->loadModel('file')->getUpload();
        if(empty($files)) return dao::$errors['files'] = sprintf($this->lang->error->notempty, $this->lang->doc->uploadFile);

        /* Fix bug #2929. strip_tags($this->post->contentMarkdown, $this->config->allowedTags)*/
        $lib = $this->getLibByID($doc->lib);
        $doc = $this->loadModel('file')->processImgURL($doc, $this->config->doc->editor->create['id'], $this->post->uid);

        $doc->product   = $lib->product;
        $doc->project   = $lib->project;
        $doc->execution = $lib->execution;

        $docContent          = new stdclass();
        $docContent->title   = $doc->title;
        $docContent->content = '';
        $docContent->type    = $doc->contentType;
        $docContent->digest  = '';
        $docContent->version = 1;
        unset($doc->contentType, $doc->url);

        $requiredFields = $this->config->doc->create->requiredFields;

        $doc->draft  = '';
        $doc->vision = $this->config->vision;

        $docsAction = array();
        foreach($files as $file)
        {
            $title    = $file['title'];
            $position = strrpos($title, '.');
            if($position !== 'false') $title = substr($title, 0, $position);

            $doc->title = $title;
            $this->dao->insert(TABLE_DOC)->data($doc, 'content')->autoCheck()
                ->batchCheck($requiredFields, 'notempty')
                ->exec();
            if(!dao::isError())
            {
                $docID = $this->dao->lastInsertID();
                $this->file->updateObjectID($this->post->uid, $docID, 'doc');
                $fileTitle = $this->file->saveAFile($file, 'doc', $docID);
                $docsAction[$docID] = $fileTitle;
                if(empty($fileTitle)) continue;
                if(dao::isError())
                {
                    dao::$errors['message'][] = 'doc#' . ($file->title) . dao::getError(true);
                    continue;
                }

                $docContent->doc     = $docID;
                $docContent->files   = ',' . $fileTitle->id;
                $docContent->title   = $title;
                $docContent->content = '';
                $docContent->type    = 'attachment';
                $docContent->digest  = '';
                $docContent->version = 1;
                $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
                $this->loadModel('score')->create('doc', 'create', $docID);
            }
        }

        return dao::isError() ? false : array('status' => 'new', 'docsAction' => $docsAction, 'docType' => 'attachment', 'libID' => $doc->lib);
    }

    /**
     * Create a doc.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if(!isset($_POST['lib']) and strpos($_POST['module'], '_') !== false) list($_POST['lib'], $_POST['module']) = explode('_', $_POST['module']);
        $now = helper::now();
        $doc = fixer::input('post')
            ->callFunc('title', 'trim')
            ->setDefault('content,template,templateType,chapterType', '')
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

        $doc->contentMarkdown = $this->post->contentMarkdown;
        if($doc->acl == 'open') $doc->users = $doc->groups = '';
        if(empty($doc->lib) and strpos($doc->module, '_') !== false) list($doc->lib, $doc->module) = explode('_', $doc->module);
        if(empty($doc->lib)) return dao::$errors['lib'] = sprintf($this->lang->error->notempty, $this->lang->doc->lib);

        /* Fix bug #2929. strip_tags($this->post->contentMarkdown, $this->config->allowedTags)*/
        $lib = $this->getLibByID($doc->lib);
        $doc = $this->loadModel('file')->processImgURL($doc, $this->config->doc->editor->create['id'], $this->post->uid);

        $doc->product   = $lib->product;
        $doc->project   = $lib->project;
        $doc->execution = $lib->execution;

        $docContent          = new stdclass();
        $docContent->title   = $doc->title;
        $docContent->content = $doc->contentType == 'html' ? $doc->content : $doc->contentMarkdown;
        $docContent->type    = $doc->contentType;
        $docContent->digest  = '';
        $docContent->version = 1;
        unset($doc->contentMarkdown, $doc->contentType, $doc->url);

        $requiredFields = $this->config->doc->create->requiredFields;
        if($doc->status == 'draft') $requiredFields = 'title';
        if(strpos("url|word|ppt|excel", $this->post->type) !== false) $requiredFields = trim(str_replace(",content,", ",", ",{$requiredFields},"), ',');

        $checkContent = strpos(",$requiredFields,", ',content,') !== false;
        if($checkContent and strpos("url|word|ppt|excel|", $this->post->type) === false)
        {
            $requiredFields = trim(str_replace(',content,', ',', ",$requiredFields,"), ',');
            if(empty($docContent->content)) return dao::$errors['content'] = sprintf($this->lang->error->notempty, $this->lang->doc->content);
        }

        $files = $this->loadModel('file')->getUpload();
        if($_POST['type'] == 'attachment' && empty($_POST['labels'])) return dao::$errors['files'] = sprintf($this->lang->error->notempty, $this->lang->doc->uploadFile);

        $doc->draft  = $docContent->content;
        $doc->vision = $this->config->vision;
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

        if(!isset($_POST['lib']) and strpos($_POST['module'], '_') !== false) list($_POST['lib'], $_POST['module']) = explode('_', $_POST['module']);
        $account = $this->app->user->account;
        $now     = helper::now();
        $doc     = fixer::input('post')->setDefault('module', 0)
            ->callFunc('title', 'trim')
            ->stripTags($this->config->doc->editor->edit['id'], $this->config->allowedTags)
            ->setDefault('users', '')
            ->setDefault('groups', '')
            ->setDefault('product', 0)
            ->setDefault('execution', 0)
            ->setDefault('mailto', '')
            ->add('editedBy', $account)
            ->add('editedDate', $now)
            ->setIF(strpos(",$oldDoc->editedList,", ",$account,") === false, 'editedList', $oldDoc->editedList . ",$account")
            ->cleanInt('project,product,execution,lib,module')
            ->join('groups', ',')
            ->join('users', ',')
            ->join('mailto', ',')
            ->remove('comment,files,labels,uid,contactListMenu')
            ->get();

        $editingDate = $oldDoc->editingDate ? json_decode($oldDoc->editingDate, true) : array();
        unset($editingDate[$account]);
        $doc->editingDate = json_encode($editingDate);

        if($doc->acl == 'open') $doc->users = $doc->groups = '';
        if($doc->type == 'chapter' and $doc->parent)
        {
            $parentDoc = $this->dao->select('*')->from(TABLE_DOC)->where('id')->eq((int)$doc->parent)->fetch();
            if(strpos($parentDoc->path, ",$docID,") !== false)
            {
                dao::$errors['parent'] = $this->lang->doc->errorParentChapter;
                return false;
            }
        }

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

        $requiredFields = $this->config->doc->edit->requiredFields;
        if($doc->status == 'draft') $requiredFields = 'title';
        if(strpos(",$requiredFields,", ',content,') !== false)
        {
            $requiredFields = trim(str_replace(',content,', ',', ",$requiredFields,"), ',');
            if(isset($doc->content) and empty($doc->content)) return dao::$errors['content'] = sprintf($this->lang->error->notempty, $this->lang->doc->content);
        }

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
            $docContent          = new stdclass();
            $docContent->doc     = $oldDoc->id;
            $docContent->title   = $doc->title;
            $docContent->content = isset($doc->content) ? $doc->content : '';
            $docContent->files   = $oldDocContent->files;
            if($files) $docContent->files .= ',' . join(',', array_keys($files));
            $docContent->files = trim($docContent->files, ',');
            if(isset($doc->digest)) $docContent->digest = $doc->digest;
            if($oldDoc->status == 'draft')
            {
                $this->dao->update(TABLE_DOCCONTENT)->data($docContent)->where('id')->eq($oldDocContent->id)->exec();
            }
            else
            {
                $doc->version        = $oldDoc->version + 1;
                $docContent->version = $doc->version;
                $docContent->type    = $oldDocContent->type;
                $this->dao->replace(TABLE_DOCCONTENT)->data($docContent)->exec();
            }
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
        $docID   = (int)$docID;
        $oldDoc  = $this->dao->select('id,editingDate')->from(TABLE_DOC)->where('id')->eq($docID)->fetch();
        $account = $this->app->user->account;

        $data = fixer::input('post')->stripTags($this->config->doc->editor->edit['id'], $this->config->allowedTags)->get();
        $doc  = new stdclass();
        $doc->draft = $data->content;

        $doc->editingDate = $oldDoc->editingDate ? json_decode($oldDoc->editingDate, true) : array();
        $doc->editingDate[$account] = time();
        $doc->editingDate = json_encode($doc->editingDate);

        $docType = $this->dao->select('type')->from(TABLE_DOCCONTENT)->where('doc')->eq($docID)->orderBy('version_desc')->fetch();
        if($docType == 'markdown') $doc->draft = $this->post->content;

        $this->dao->update(TABLE_DOC)->data($doc)->where('id')->eq($docID)->exec();
    }

    /**
     * 构造搜索条件。
     * Build search query.
     *
     * @param  string $type
     * @param  int    $queryID
     * @access public
     * @return string
     */
    public function buildQuery(string $type, int $queryID = 0): string
    {
        $queryName = $type . 'libDocQuery';
        $queryForm = $type . 'libDocForm';
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

        $query = $this->session->$queryName;
        if(strpos($query, "`lib` = 'all'") !== false) $query = str_replace("`lib` = 'all'", '1', $query);
        return $query;
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
            ->where('1 = 1')
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
     * 检查是否有权限访问文档库/文档。
     * Check priv for lib.
     *
     * @param  object $object
     * @param  string $extra
     * @access public
     * @return bool
     */
    public function checkPrivLib(object $object, string $extra = ''): bool
    {
        if(empty($object)) return false;

        /* Only the creator can access the document library under my space. */
        if($object->type == 'mine' && $object->addedBy == $this->app->user->account) return true;
        if($this->app->user->admin && $object->type != 'mine') return true;
        if($object->acl == 'open') return true;

        /* When view a document, check whether current user has permission to access the document library. */
        $account = ',' . $this->app->user->account . ',';
        if(isset($object->addedBy) && $object->addedBy == $this->app->user->account) return true;
        if(isset($object->users) && strpos(",{$object->users},", $account) !== false) return true;

        if(!empty($object->groups))
        {
            $userGroups = $this->app->user->groups;
            foreach($userGroups as $groupID)
            {
                if(strpos(",$object->groups,", ",$groupID,") !== false) return true;
            }
        }

        $isProjectLib = $object->project && !$object->execution;
        if($isProjectLib && $object->acl == 'default' && $this->loadModel('project')->checkPriv($object->project)) return true;

        /* The user has permission to access the owning document library that can access the document. */
        if(strpos($extra, 'notdoc') !== false)
        {
            static $extraDocLibs;
            if($extraDocLibs === null) $extraDocLibs = $this->getPrivLibsByDoc();
            if(isset($extraDocLibs[$object->id])) return true;
        }

        /* If the acl is default, the document library cannot be accessed without object permission. */
        if($object->acl == 'default' && (!empty($object->product) || !empty($object->execution)))
        {
            $acls = $this->app->user->rights['acls'];
            if(!empty($object->product) && !empty($acls['products']) && !in_array($object->product, $acls['products'])) return false;
            if(!empty($object->execution) && !empty($acls['sprints']) && !in_array($object->execution, $acls['sprints'])) return false;
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
        if(!isset($object->lib)) return false;
        if(isset($object->assetLibType) and $object->assetLibType) return true;
        if($object->status == 'draft' and $object->addedBy != $this->app->user->account) return false;
        if($object->status == 'normal' and $this->app->user->admin) return true;

        static $libs = array();
        if(!isset($libs[$object->lib])) $libs[$object->lib] = $this->getLibByID($object->lib);
        if(!$this->checkPrivLib($libs[$object->lib])) return false;
        if(in_array($object->acl, array('open', 'public'))) return true;

        $account = ",{$this->app->user->account},";
        if(isset($object->addedBy) and $object->addedBy == $this->app->user->account) return true;
        if(strpos(",$object->users,", $account) !== false) return true;
        if($object->groups)
        {
            foreach($this->app->user->groups as $groupID)
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
     * 通过对象ID获取文档库。
     * Get libs by object.
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  int    $appendLib
     * @access public
     * @return array
     */
    public function getLibsByObject(string $type, int $objectID, int $appendLib = 0): array
    {
        if(!in_array($type, array('mine', 'custom', 'product', 'project', 'execution'))) return array();
        if(in_array($type, array('mine', 'custom')))
        {
            $objectLibs = $this->dao->select('*')->from(TABLE_DOCLIB)
                ->where('deleted')->eq(0)
                ->andWhere('vision')->eq($this->config->vision)
                ->andWhere('type')->eq($type)
                ->beginIF(!empty($appendLib))->orWhere('id')->eq($appendLib)->fi()
                ->beginIF($type == 'mine')->andWhere('addedBy')->eq($this->app->user->account)->fi()
                ->orderBy('`order` asc, id_asc')
                ->fetchAll('id');
        }
        else
        {
            $objectLibs = $this->dao->select('*')->from(TABLE_DOCLIB)
                ->where('deleted')->eq(0)
                ->andWhere('vision')->eq($this->config->vision)
                ->andWhere($type)->eq($objectID)
                ->beginIF($type == 'project')->andWhere('type')->in('api,project')->fi()
                ->beginIF(!empty($appendLib))->orWhere('id')->eq($appendLib)->fi()
                ->orderBy('`order` asc, id_asc')
                ->fetchAll('id');

            $executionIDList = array();
            if($type == 'project') $executionIDList = $this->loadModel('execution')->getPairs($objectID, 'all', 'multiple,leaf');
            if($executionIDList)
            {
                $objectLibs += $this->dao->select('*')->from(TABLE_DOCLIB)
                    ->where('deleted')->eq(0)
                    ->andWhere('vision')->eq($this->config->vision)
                    ->andWhere('execution')->in(array_keys($executionIDList))
                    ->andWhere('type')->eq('execution')
                    ->beginIF(!empty($appendLib))->orWhere('id')->eq($appendLib)->fi()
                    ->orderBy('`order` asc, id_asc')
                    ->fetchAll('id');
            }
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
     * @param  string $returnType nomerge|merge
     * @param  int    $append
     * @access public
     * @return array
     */
    public function getOrderedObjects($objectType = 'product', $returnType = 'merge', $append = 0)
    {
        $myObjects = $normalObjects = $closedObjects = array();
        if($objectType == 'product')
        {
            $products = $this->loadModel('product')->getList();
            if($append and !isset($products[$append])) $products[$append] = $this->product->getByID($append);
            foreach($products as $id => $product)
            {
                if($product->status != 'closed' and $product->PO == $this->app->user->account)
                {
                    $myObjects[$id] = $product->name;
                }
                elseif($product->status != 'closed' and !($product->PO == $this->app->user->account))
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
                    ->leftJoin(TABLE_DOCLIB)->alias('t2')->on('t2.project=t1.id')
                    ->where("CONCAT(',', t2.users, ',')")->like("%,{$this->app->user->account},%")
                    ->andWhere('t1.vision')->eq($this->config->vision)
                    ->andWhere('t1.deleted')->eq(0)
                    ->beginIF($this->config->vision == 'rnd')->andWhere('model')->ne('kanban')->fi()
                    ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->projects)->fi()
                    ->fetchPairs();
            }

            $objects = $this->dao->select('*')->from(TABLE_PROJECT)
                ->where('type')->eq('project')
                ->andWhere('vision')->eq($this->config->vision)
                ->andWhere('deleted')->eq(0)
                ->beginIF($this->config->vision == 'rnd')->andWhere('model')->ne('kanban')->fi()
                ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
                ->beginIF($append)->orWhere('id')->eq($append)->fi()
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
                elseif($project->status != 'done' and $project->status != 'closed' and !($project->PM == $this->app->user->account))
                {
                    $normalObjects[$id] = $project->name;
                }
                elseif($project->status == 'done' or $project->status == 'closed')
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
                ->andWhere('type')->in('sprint,stage')
                ->andWhere('multiple')->eq('1')
                ->andWhere('vision')->eq($this->config->vision)
                ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
                ->beginIF($append)->orWhere('id')->eq($append)->fi()
                ->orderBy('order_asc')
                ->fetchAll('id');

            foreach($executions as $id => $execution)
            {
                if($execution->type == 'stage' and $execution->grade != 1)
                {
                    $parentExecutions = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('id')->in(trim($execution->path, ','))->andWhere('type')->in('stage,sprint')->orderBy('grade')->fetchPairs();
                    $execution->name  = implode('/', $parentExecutions);
                }
                $execution->name = zget($projectPairs, $execution->project) . ' / ' . $execution->name;

                if($execution->status != 'done' and $execution->status != 'closed' and $execution->PM == $this->app->user->account)
                {
                    $myObjects[$id] = $execution->name;
                }
                elseif($execution->status != 'done' and $execution->status != 'closed' and !($execution->PM == $this->app->user->account))
                {
                    $normalObjects[$id] = $execution->name;
                }
                elseif($execution->status == 'done' or $execution->status == 'closed')
                {
                    $closedObjects[$id] = $execution->name;
                }
            }
        }

        if($returnType == 'nomerge') return array($myObjects, $normalObjects, $closedObjects);
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

        $docs = $this->dao->select("`id`,`addedBy`,`lib`,`acl`,`users`,`groups`,`status`")->from(TABLE_DOC)
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
        $storyIDList = '';
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
            $project     = $this->loadModel('project')->getByID($objectID);
            $storyIDList = '';
            if(!$project->hasProduct)
            {
                $projectIDList = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($objectID)->orWhere('project')->eq($objectID)->fetchPairs('id', 'id');
                $storyIDList   = $this->dao->select('story')->from(TABLE_PROJECTSTORY)->where('project')->in($projectIDList)->fetchPairs('story', 'story');
            }

            if(in_array($this->config->edition, array('max', 'ipd')))
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

            $executionIdList = $executionIdList ? join(',', $executionIdList) : 0;
            $storyIDList     = $storyIDList ? join(',', $storyIDList) : 0;
        }
        elseif($type == 'execution')
        {
            $execution   = $this->loadModel('execution')->getByID($objectID);
            $project     = $this->loadModel('project')->getByID($execution->project);
            $storyIDList = '';

            if(!$project->hasProduct) $storyIDList = $this->dao->select('story')->from(TABLE_PROJECTSTORY)->where('project')->eq($objectID)->fetchPairs('story', 'story');
            $storyIDList = join(',', $storyIDList);

            $taskPairs = $this->dao->select('id')->from(TABLE_TASK)->where('execution')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('execution')->in($userView)->fetchPairs('id');
            if(!empty($taskPairs)) $taskIdList = implode(',', $taskPairs);

            $buildPairs = $this->dao->select('id')->from(TABLE_BUILD)->where('execution')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('execution')->in($userView)->fetchPairs('id');
            if(!empty($buildPairs)) $buildIdList = implode(',', $buildPairs);
        }

        $files = $this->dao->select('*')->from(TABLE_FILE)->alias('t1')
            ->where('size')->gt('0')
            ->andWhere('deleted')->eq('0')
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
            ->orWhere("(objectType = 'execution' and objectID in ($executionIdList))")
            ->orWhere("(objectType = 'issue' and objectID in ($issueIdList))")
            ->orWhere("(objectType = 'review' and objectID in ($reviewIdList))")
            ->orWhere("(objectType = 'meeting' and objectID in ($meetingIdList))")
            ->orWhere("(objectType = 'design' and objectID in ($designIdList))")
            ->fi()
            ->beginIF($type == 'project' or $type == 'execution')
            ->orWhere("(objectType = 'task' and objectID in ($taskIdList))")
            ->orWhere("(objectType = 'build' and objectID in ($buildIdList))")
            ->beginIF($storyIDList)->orWhere("(objectType = 'story' and objectID in ($storyIDList))")->fi()
            ->fi()
            ->markRight(1)
            ->beginIF($searchTitle !== false)->andWhere('title')->like("%{$searchTitle}%")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        foreach($files as $fileID => $file)
        {
            $this->file->setFileWebAndRealPaths($file);
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
            elseif(strpos('csv,xls,xlsx', $file->extension) !== false) $iconClass = 'icon-file-excel';
            elseif(strpos('doc,docx', $file->extension) !== false) $iconClass = 'icon-file-word';
            elseif(strpos('ppt,pptx', $file->extension) !== false) $iconClass = 'icon-file-powerpoint';
            elseif(strpos('pdf', $file->extension) !== false) $iconClass = 'icon-file-pdf';
            elseif(strpos('mp3,ogg,wav', $file->extension) !== false) $iconClass = 'icon-file-audio';
            elseif(strpos('avi,mp4,mov', $file->extension) !== false) $iconClass = 'icon-file-video';
            elseif(strpos('txt,md', $file->extension) !== false) $iconClass = 'icon-file-text';
            elseif(strpos('html,htm', $file->extension) !== false) $iconClass = 'icon-globe';

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
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getLibIdListByProject($projectID = 0)
    {
        $products   = $this->loadModel('product')->getProductIDByProject($projectID, false);
        $executions = $this->loadModel('execution')->getPairs($projectID, 'all', 'noclosed,leaf');

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
        $today     = date('Y-m-d');
        $statistic = new stdclass();
        $statistic->totalDocs = $this->dao->select('count(*) as count')->from(TABLE_DOC)
            ->where('deleted')->eq('0')
            ->andWhere('type')->in($this->config->doc->docTypes)
            ->andWhere('vision')->eq($this->config->vision)
            ->fetch('count');
        $statistic->todayEditedDocs = $this->dao->select('count(DISTINCT objectID) as count')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin(TABLE_DOC)->alias('t2')->on("t1.objectID=t2.id and t1.objectType='doc'")
            ->where('t1.objectType')->eq('doc')
            ->andWhere('t1.action')->eq('edited')
            ->andWhere('t1.actor')->eq($this->app->user->account)
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('LEFT(t1.date, 10)')->eq($today)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.type')->in($this->config->doc->docTypes)
            ->fetch('count');
        $statistic->myEditedDocs = $this->dao->select('count(DISTINCT t1.objectID) as count')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin(TABLE_DOC)->alias('t2')->on("t1.objectID=t2.id and t1.objectType='doc'")
            ->where('t1.objectType')->eq('doc')
            ->andWhere('t1.action')->eq('edited')
            ->andWhere('t1.actor')->eq($this->app->user->account)
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t2.lib')->ne('')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.type')->in($this->config->doc->docTypes)
            ->fetch('count');

        $my = $this->dao->select("count(*) as myDocs, SUM(views) as docViews, SUM(collects) as docCollects")->from(TABLE_DOC)
            ->where('addedBy')->eq($this->app->user->account)
            ->andWhere('type')->in($this->config->doc->docTypes)
            ->andWhere('deleted')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('lib')->ne('')
            ->fetch();
        $statistic->myDocs = $my->myDocs;
        $statistic->myDoc  = new stdclass();
        $statistic->myDoc->docViews    = $my->docViews;
        $statistic->myDoc->docCollects = $my->docCollects;

        return $statistic;
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
     * Get the dropmenu link.
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  array  $libs
     * @param  int    $libID
     * @access public
     * @return string
     */
    public function getDropMenuLink($type, $objectID)
    {
        if(!in_array($type, array('product', 'project'))) return '';

        $currentModule = $this->app->rawModule;
        $currentMethod = $this->app->rawMethod;
        if($currentModule == 'api' and $currentMethod == 'index')
        {
            $currentModule = 'doc';
            $currentMethod = $type . 'Space';
        }

        return helper::createLink('doc', 'ajaxGetDropMenu', "objectType=$type&objectID=$objectID&module=$currentModule&method=$currentMethod");
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
        $stmt = $this->dbh->query($query);
        while($module = $stmt->fetch()) $this->buildTree($treeMenu, $type, $objectID, $rootID, $module, $moduleDocs, $docID);

        if(isset($moduleDocs[0]))
        {
            if(!isset($treeMenu[0])) $treeMenu[0] = '';

            foreach($moduleDocs[0] as $doc)
            {
                if(!$docID and $currentMethod != 'teamspace') $docID = $doc->id;

                $class = common::hasPriv('doc', 'updateOrder') ? ' sortDoc' : '';
                $treeMenu[0] .= '<li' . " class='" . ($doc->id == $docID ? 'active' : 'doc') . "$class'" . " data-id=$doc->id>";

                if($currentMethod == 'teamspace')
                {
                    $treeMenu[0] .= '<div class="tree-group"><span class="tail-info">' . zget($users, $doc->editedBy) . ' &nbsp;' . $doc->editedDate . '</span>';
                }
                if($currentMethod == 'view')
                {
                    $class = common::hasPriv('doc', 'updateOrder') ? 'sortDoc' : '';
                    $treeMenu[0] .= "<div class='tree-group'><span class='module-name'>" . html::a(inlink('view', "docID={$doc->id}"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title, '', "data-app='{$this->app->tab}' class='doc-title $class' title='{$doc->title}'") . '</span>';
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
                    $treeMenu[0] .= html::a(inlink('view', "docID={$doc->id}"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title, '', "data-app='{$this->app->tab}' class='doc-title $class' title='{$doc->title}'");
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
     * @access public
     * @return string
     */
    public function buildTree(&$treeMenu, $type, $objectID, $libID, $module, $moduleDocs, &$docID, $moduleID = 0)
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
                elseif($type == static::DOC_TYPE_REST)
                {
                    $treeMenu[$module->id] .= '<li' . ($doc->id == $docID ? ' class="active"' : ' class="doc"') . '>';
                    $treeMenu[$module->id] .= html::a(inlink('api', "type=restapi&apiID={$doc->id}"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title, '', "data-app='{$this->app->tab}' class='doc-title' title='{$doc->title}'");
                    $treeMenu[$module->id] .= '</li>';
                }
                else
                {
                    if(!$docID and $currentMethod != 'teamspace') $docID = $doc->id;
                    $class = common::hasPriv('doc', 'updateOrder') ? ' sortDoc' : '';
                    $treeMenu[$module->id] .= '<li' . " class='" . ($doc->id == $docID ? 'active' : 'doc') . "$class'" . " data-id=$doc->id>";

                    if($currentMethod == 'teamspace')
                    {
                        $treeMenu[$module->id] .= '<div class="tree-group"><span class="tail-info">' . zget($users, $doc->editedBy) . ' &nbsp;' . $doc->editedDate . '</span>';
                    }

                    if($currentMethod == 'view')
                    {
                        $class = common::hasPriv('doc', 'updateOrder') ? 'sortDoc' : '';
                        $treeMenu[$module->id] .= "<div class='tree-group'><span class='module-name'>" . html::a(inlink('view', "docID={$doc->id}"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title, '', "data-app='{$this->app->tab}' class='doc-title $class' title='{$doc->title}'") . '</span>';
                        if(common::hasPriv('doc', 'edit') or common::hasPriv('doc', 'updateOrder'))
                        {
                            $treeMenu[$module->id] .= "<div class='tree-actions'>";
                            if(common::hasPriv('doc', 'edit')) $treeMenu[$module->id] .= html::a(helper::createLink('doc', 'edit', "docID={$doc->id}&comment=false&objectType=$type&objectID=$objectID&libID=$libID"), "<i class='icon icon-edit'></i>", '', "title={$this->lang->doc->edit} data-app={$this->app->tab}");
                            if(common::hasPriv('doc', 'updateOrder')) $treeMenu[$module->id] .= html::a('javascript:;', "<i class='icon icon-move sortDoc'></i>", '', "title='{$this->lang->doc->updateOrder}' class='sortDoc'");
                            $treeMenu[$module->id] .= '</div>';
                        }
                        $treeMenu[$module->id] .= '</div>';
                    }
                    elseif($currentMethod == 'teamspace')
                    {
                        $class = common::hasPriv('doc', 'updateOrder') ? 'sortDoc' : '';
                        $treeMenu[$module->id] .= html::a(inlink('view', "docID={$doc->id}"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title, '', "data-app='{$this->app->tab}' class='doc-title $class' title='{$doc->title}'");
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
        elseif($type == static::DOC_TYPE_REST)
        {
            $li = html::a('#', $module->name, '', "data-app='{$this->app->tab}' class='doc-title' title='{$module->name}'");
        }
        else
        {
            $moduleClass = common::hasPriv('tree', 'updateOrder') ? 'sort-module' : '';
            $li          = "<div class='tree-group'><span class='module-name'><a class='$moduleClass' title='{$module->name}'>" . $module->name . '</a></span>';
            if($currentMethod != 'teamspace')
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
            $class = '';
            if($type == static::DOC_TYPE_REST) $class = 'class="menu-active-primary menu-hover-primary"';

            $li .= "<ul $class>" . $treeMenu[$module->id] . '</ul>';
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
     * @param  array  $files
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
     * 设置文档的导航。
     * Set doc menu by type.
     *
     * @param  string $type     mine|project|execution|product|custom
     * @param  int    $objectID
     * @param  int    $libID
     * @param  int    $appendLib
     * @access public
     * @return array
     */
    public function setMenuByType(string $type, int $objectID, int $libID, int $appendLib = 0): array
    {
        if(empty($type))
        {
            $doclib   = $this->getLibByID($libID);
            $type     = $doclib->type == 'execution' ? 'project' : $doclib->type;
            $objectID = isset($doclib->{$type}) ? $doclib->{$type} : 0;
        }

        $type           = $this->app->tab == 'doc' && $type == 'execution' ? 'project' : $type;
        $objectDropdown = array('text' => '', 'link' => '');
        $appendObject   = $objectID;
        if(in_array($type, array('project', 'product', 'execution')))
        {
            $object = $this->dao->select('id,name,status,deleted')->from($this->config->objectTables[$type])->where('id')->eq($objectID)->fetch();
            if(empty($object)) return helper::createLink($type, $type == 'project' && $this->config->vision != 'lite' ? 'createGuide' : 'create', $type == 'project' && $this->config->vision == 'lite' ? 'model=kanban' : '');

            $this->loadModel($type);
            $objects  = $this->getOrderedObjects($type, 'merge', $objectID);
            $objectID =  method_exists($this->$type, 'saveState') ? $this->{$type}->saveState($objectID, $objects) : $this->{$type}->checkAccess($objectID, $objects);
            $libs     = $this->getLibsByObject($type, $objectID, $appendLib);
            if(($libID == 0 || !isset($libs[$libID])) && !empty($libs)) $libID = reset($libs)->id;
            if($this->app->tab != 'doc' && isset($libs[$libID]))
            {
                $objectDropdown['text'] = zget($libs[$libID], 'name', '');
            }
            else
            {
                $objectDropdown['text'] = zget($objects, $objectID, '');
                $objectDropdown['link'] = $this->getDropMenuLink($type, $appendObject);
            }
        }
        else
        {
            $libs = $this->getLibsByObject($type, 0, $appendLib);
            if(($libID == 0 || !isset($libs[$libID])) && !empty($libs)) $libID = reset($libs)->id;
            if(isset($libs[$libID])) $objectDropdown['text'] = zget($libs[$libID], 'name', '');

            $object     = new stdclass();
            $object->id = 0;
        }

        $tab = strpos(',my,doc,product,project,execution,', ",{$this->app->tab},") !== false ? $this->app->tab : 'doc';
        if($type == 'mine')   $type = 'my';
        if($type == 'custom') $type = 'team';
        if($tab == 'doc' && !common::hasPriv('doc', $type . 'Space')) return helper::createLink('user', 'deny', "module=doc&method={$type}Space");
        if($tab != 'doc' && method_exists($type . 'Model', 'setMenu'))
        {
            $this->loadModel($type)->setMenu($objectID);
        }
        elseif($tab == 'doc' && isset($this->lang->doc->menu->{$type}['alias']))
        {
            $this->lang->doc->menu->{$type}['alias'] .= ',' . $this->app->rawMethod;
        }
        return array($libs, $libID, $object, $objectID, $objectDropdown);
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
        if(isset($doc->type) and $doc->type == 'url')
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
     * 通过搜索获取文档列表数据。
     * Get docs by search.
     *
     * @param  string $type     product|project|execution|custom|book
     * @param  int    $objectID
     * @param  int    $libID
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getDocsBySearch(string $type, int $objectID, int $libID, int $queryID, string $orderBy = 'id_desc', object $pager = null): array
    {
        $query     = $this->buildQuery($type, $queryID);
        $libs      = $this->getLibsByObject($type, $objectID);
        $docIdList = $this->getPrivDocs(array_keys($libs));
        $docs      = $this->dao->select('*')->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->andWhere($query)
            ->andWhere('lib')->in(array_keys($libs))
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
            ->beginIF(!empty($docIdList))->andWhere('id')->in($docIdList)->fi()
            ->andWhere("(status = 'normal' or (status = 'draft' and addedBy='{$this->app->user->account}'))")
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        return $this->processCollector($docs);
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

    /**
     * Get module tree.
     *
     * @param  int    $rootID
     * @param  int    $moduleID
     * @param  string $type doc|api
     * @param  int    $parent
     * @param  array  $modules
     * @access public
     * @return array
     */
    public function getModuleTree($rootID, $moduleID = 0, $type = 'doc', $parent = 0, $modules = array(), $docID = 0)
    {
        if(is_array($modules) and empty($modules))
        {
            $modules = $this->dao->select('*')->from(TABLE_MODULE)
                ->where('root')->eq((int)$rootID)
                ->andWhere('type')->eq($type)
                ->andWhere('deleted')->eq(0)
                ->orderBy('grade desc, `order`')
                ->fetchAll('id');
        }

        $moduleTree = array();
        foreach($modules as $module)
        {
            if($module->parent != $parent) continue;
            unset($modules[$module->id]);

            $item = new stdclass();
            $item->id         = $module->id;
            $item->name       = $module->name;
            $item->objectType = $module->type;
            $item->type       = 'module';
            $item->libID      = $rootID;
            $item->active     = $module->id == $moduleID ? 1 : 0;
            $item->order      = $module->order;
            $item->children   = $this->getModuleTree($rootID, $moduleID, $type, $module->id, $modules, $docID);
            $showDoc = $this->loadModel('setting')->getItem('owner=' . $this->app->user->account . '&module=doc&key=showDoc');
            $showDoc = $showDoc === '0' ? 0 : 1;
            if($this->app->rawMethod == 'view' and $module->type != 'api' and $showDoc)
            {
                $docIDList = $this->getPrivDocs(array($rootID), $module->id);
                $docs      = $this->dao->select('*, title as name')->from(TABLE_DOC)
                    ->where('id')->in($docIDList)
                    ->andWhere('deleted')->eq(0)
                    ->fetchAll('id');
                if(!empty($docs))
                {
                    $docs = array_values($docs);
                    foreach($docs as $doc) $doc->active = $doc->id == $docID ? 1 : 0;
                    $item->children = array_merge($docs, $item->children);
                }
            }

            $moduleTree[$module->id] = $item;
        }

        return array_values($moduleTree);
    }

    /**
     * 获取文档库的树形结构。
     * Get lib tree.
     *
     * @param  int    $libID
     * @param  array  $libs
     * @param  string $type       mine|product|project|execution|api|custom
     * @param  int    $moduleID
     * @param  int    $objectID
     * @param  string $browseType bysearch|byrelease
     * @param  int    $param
     * @param  int    $docID
     * @access public
     * @return array
     */
    public function getLibTree(int $libID, array $libs, string $type, int $moduleID, int $objectID = 0, string $browseType = '', int $param = 0, int $docID = 0): array
    {
        list($libTree, $apiLibs, $apiLibIDList) = $this->getObjectTree($libID, $libs, $type, $moduleID, $objectID, strtolower($browseType), $param, $docID);
        $libTree = $this->processObjectTree($libTree, $type, $libID, $objectID, $apiLibs, $apiLibIDList);

        if($type != 'project') $libTree = array_values($libTree[$type]);
        if($type == 'mine')
        {
            $libType     = $this->app->rawMethod == 'view' ? 'mine' : zget($this->app->rawParams, 'type', '');
            $mineMethods = array('mine' => 'myLib', 'view' => 'myView', 'collect' => 'myCollection', 'createdBy' => 'myCreation', 'editedBy' => 'myEdited');
            $libTree     = array();
            foreach($mineMethods as $type => $mineMethod)
            {
                if($mineMethod != 'myLib' && !common::hasPriv('doc', $mineMethod)) continue;

                $myItem = new stdclass();
                $myItem->id         = 0;
                $myItem->name       = $this->lang->doc->{$mineMethod};
                $myItem->type       = $type;
                $myItem->objectType = 'doc';
                $myItem->objectID   = 0;
                $myItem->hasAction  = false;
                $myItem->active     = strtolower($libType) == strtolower($type) ? 1 : 0;

                $libTree[] = $myItem;
            }
        }

        return $libTree;
    }

    /**
     * 获取产品、项目、执行文档库的树形结构。
     * Get a tree structure of the product, project, and execution document library.
     *
     * @param  int    $libID
     * @param  array  $libs
     * @param  string $type       mine|product|project|execution|api|custom
     * @param  int    $moduleID
     * @param  int    $objectID
     * @param  string $browseType bysearch|byrelease
     * @param  int    $docID
     * @param  int    $param
     * @access public
     * @return array
     */
    public function getObjectTree(int $libID, array $libs, string $type, int $moduleID = 0, int $objectID = 0, string $browseType = '', int $param = 0, int $docID = 0): array
    {
        if($type == 'project')
        {
            $executionLibs = array();
            foreach($libs as $lib)
            {
                if($lib->type != 'execution') continue;
                $executionLibs[$lib->execution][$lib->id] = $lib;
            }

            $executionPairs = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('id')->in(array_keys($executionLibs))->fetchPairs();
        }

        $release = $browseType == 'byrelease' && $param ? $this->loadModel('api')->getRelease(0, 'byId', $param) : null;
        $libTree = array($type => array());
        $apiLibs = $apiLibIDList = array();
        foreach($libs as $lib)
        {
            $item = $this->buildLibItem($libID, $lib, $type, $moduleID, $objectID, $browseType, $docID, $release);
            if(($type == 'project' && $lib->type != 'execution') || $type != 'project')
            {
                if($item->type == 'docLib') $libTree[$lib->type][$lib->id] = $item;
                if($item->type == 'apiLib') $apiLibs[$lib->id] = $item;
            }
            else
            {
                $executionID = $lib->execution;
                if(empty($libTree['execution'][$executionID]))
                {
                    $execution = new stdclass();
                    $execution->id        = $executionID;
                    $execution->name      = zget($executionPairs, $executionID);
                    $execution->type      = 'execution';
                    $execution->active    = $item->active;
                    $execution->hasAction = false;
                    $execution->children  = array();
                    if(count($executionLibs[$executionID]) == 1)
                    {
                        $execution->id        = $item->id;
                        $execution->type      = 'docLib';
                        $execution->hasAction = true;
                        $execution->children  = $item->children;
                    }

                    $libTree['execution'][$executionID] = $execution;
                    if(count($executionLibs[$executionID]) == 1) continue;
                }

                $libTree['execution'][$executionID]->active = $item->active ? 1 : $libTree['execution'][$executionID]->active;
                $libTree['execution'][$executionID]->children[] = $item;
            }

            if($item->type == 'apiLib') $apiLibIDList[] = $lib->id;
        }
        return array($libTree, $apiLibs, $apiLibIDList);
    }

    /**
     * 构建文档库树形结构的节点。
     * Build a node of the tree structure of the document library.
     *
     * @param  int         $libID
     * @param  object      $lib
     * @param  string      $type       mine|product|project|execution|api|custom
     * @param  int         $moduleID
     * @param  int         $objectID
     * @param  string      $browseType bysearch|byrelease
     * @param  int         $docID
     * @param  object|null $release
     * @access public
     * @return object
     */
    public function buildLibItem(int $libID, object $lib, string $type, int $moduleID = 0, int $objectID = 0, string $browseType = '', int $docID = 0, object|null $release = null): object
    {
        $releaseModule = array();
        if($release && $release->lib == $lib->id)
        {
            foreach($release->snap['modules'] as $module) $releaseModule[$module['id']] = (object)$module;
        }

        $item = new stdclass();
        $item->id         = $lib->id;
        $item->type       = $lib->type == 'api' ? 'apiLib' : 'docLib';
        $item->name       = $lib->name;
        $item->order      = $lib->order;
        $item->objectType = $type;
        $item->objectID   = $objectID;
        $item->active     = $lib->id == $libID && $browseType != 'bysearch' ? 1 : 0;
        $item->children   = $this->getModuleTree($lib->id, $moduleID, $lib->type == 'api' ? 'api' : 'doc', 0, $releaseModule, $docID);

        $showDoc = $this->loadModel('setting')->getItem('owner=' . $this->app->user->account . '&module=doc&key=showDoc');
        $showDoc = $showDoc === '0' ? 0 : 1;
        if($this->app->rawMethod == 'view' && $lib->type != 'apiLib' && $showDoc)
        {
            $docIDList = $this->getPrivDocs(array($lib->id));
            $docs      = $this->dao->select('*, title as name')->from(TABLE_DOC)
                ->where('id')->in($docIDList)
                ->andWhere("(status = 'normal' || (status = 'draft' && addedBy='{$this->app->user->account}'))")
                ->andWhere('deleted')->eq(0)
                ->andWhere('module')->eq(0)
                ->fetchAll('id');

            if(!empty($docs))
            {
                $docs = array_values($docs);
                foreach($docs as $doc) $doc->active = $doc->id == $docID ? 1 : 0;
                $item->children = array_merge($docs, $item->children);
            }
        }

        return $item;
    }

    /**
     * 处理产品、项目、执行的文档库树形结构。
     * Process the tree structure of the document library of product, project, and execution.
     *
     * @param  array  $libTree
     * @param  array  $apiLibs
     * @param  array  $apiLibIDList
     * @param  string $type         mine|product|project|execution|api|custom
     * @param  int    $libID
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function processObjectTree(array $libTree, string $type, int $libID = 0, int $objectID = 0, array $apiLibs = array(), array $apiLibIDList = array()): array
    {
        if(in_array($type, array('product', 'project', 'execution')))
        {
            $libTree[$type] = array_merge($libTree[$type], $apiLibs);
            if($type == 'project' && !empty($libTree['execution'])) $libTree['execution'] = array_values($libTree['execution']);

            $annex = new stdclass();
            $annex->id         = 0;
            $annex->name       = $this->lang->doclib->files;
            $annex->type       = 'annex';
            $annex->objectType = $type;
            $annex->objectID   = $objectID;
            $annex->active     = empty($libID) ? 1 : 0;
            if($type == 'project') $libTree['annex'][] = $annex;
            if($type != 'project') $libTree[$type][''] = $annex;
        }
        elseif($type == 'api')
        {
            $libTree[$type] = array_merge($libTree[$type], $apiLibs);
        }

        /* Add release for api. */
        $releases = $this->loadModel('api')->getReleaseByQuery($apiLibIDList);
        foreach($libTree as &$libList)
        {
            foreach($libList as &$lib)
            {
                if(!is_object($lib) || $lib->type != 'apiLib') continue;

                $lib->versions = array();
                foreach($releases as $index => $release)
                {
                    if($lib->id != $release->lib) continue;

                    $lib->versions[] = $release;
                    unset($releases[$index]);
                }

                if(empty($lib->versions)) continue;

                /* Set default version. */
                $defaultVersion = new stdclass();
                $defaultVersion->id      = 0;
                $defaultVersion->version = $this->lang->build->common;
                $lib->versions = array_merge(array($defaultVersion), $lib->versions);
            }
        }
        return $libTree;
    }

    /**
     * Print create document button.
     *
     * @param  object $lib
     * @param  int    $moduleID
     * @param  string $from list
     * @access public
     * @return string
     */
    public function printCreateBtn($lib, $moduleID, $from = '')
    {
        if(!common::hasPriv('doc', 'create') or !isset($lib->id)) return null;

        $objectID      = zget($lib, $lib->type, 0);
        $templateParam = in_array($this->config->edition, array('max', 'ipd')) ? '&from=template' : '';
        $class         = $from == 'list' ? 'btn-info' : 'btn-primary';
        $html          = "<div class='dropdown btn-group createDropdown'>";
        $html         .= html::a(helper::createLink('doc', 'create', "objectType={$lib->type}&objectID=$objectID&libID={$lib->id}&moduleID=$moduleID&type=html$templateParam"), "<i class='icon icon-plus'></i> {$this->lang->doc->create}", '', "class='btn $class' data-app='{$this->app->tab}'");
        $html         .= "<button type='button' class='btn $class dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>";
        $html         .= "<ul class='dropdown-menu pull-right'>";

        foreach($this->lang->doc->createList as $typeKey => $typeName)
        {
            $docType  = zget($this->config->doc->iconList, $typeKey);
            $icon     = html::image("static/svg/{$docType}.svg", "class='file-icon'");
            $attr     = "data-app='{$this->app->tab}'";
            $class    = strpos($this->config->doc->officeTypes, $typeKey) !== false || $typeKey == 'attachment' ? 'iframe' : '';
            $params   = "objectType={$lib->type}&objectID=$objectID&libID={$lib->id}&moduleID=$moduleID&type=$typeKey";
            if($typeKey == 'template' and in_array($this->config->edition, array('max', 'ipd'))) $params = "objectType={$lib->type}&objectID=$objectID&libID={$lib->id}&moduleID=$moduleID&type=html&from=template";

            $html .= "<li>";
            $html .= html::a(helper::createLink('doc', $typeKey == 'attachment' ? 'uploadDocs' : 'create', $params, '', $class ? true : false), $icon . ' ' . $typeName, '', "class='$class' $attr");
            $html .= "</li>";

            if($typeKey == 'template' || $typeKey == 'excel') $html .= '<li class="divider"></li>';
        }

        $html .= '</ul></div>';
        return $html;
    }

    /**
     * Get option menu  for libs.
     *
     * @param  array  $libs
     * @param  string $docType
     * @access public
     * @return array
     */
    public function getLibsOptionMenu($libs, $docType = 'doc')
    {
        $this->loadModel('tree');
        $modules = array();
        foreach($libs as $libID => $libName)
        {
            if(strpos($libName, '/') !== false)
            {
                $pausedLibName = explode('/', $libName);
                $libName       = array_pop($pausedLibName);
                $objectName    = array_pop($pausedLibName);
            }
            $moduleOptionMenu = $this->tree->getOptionMenu($libID, $docType, $startModuleID = 0);
            foreach($moduleOptionMenu as $moduleID => $moduleName) $modules["{$libID}_{$moduleID}"] = $libName . $moduleName;
            if(empty($moduleOptionMenu)) $modules["{$libID}_0"] = $libName . $moduleName;
        }
        return $modules;
    }

    /**
     * Check api library name.
     *
     * @param  object $lib
     * @param  string $libType
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function checkApiLibName($lib, $libType, $libID = 0)
    {
        $sameNames = $this->dao->select('*')
            ->from(TABLE_DOCLIB)
            ->where('`product`')->eq($lib->product)
            ->andWhere('`project`')->eq($lib->project)
            ->andWhere('`name`')->eq($lib->name)
            ->andWhere('`type`')->eq('api')
            ->beginIF(!empty($libID))->andWhere('`id`')->ne($libID)->fi()
            ->fetchAll();
        if(count($sameNames) > 0 and $libType == 'product') dao::$errors['name'] = $this->lang->doclib->apiNameUnique[$libType] . sprintf($this->lang->error->unique, $this->lang->doclib->name, $lib->name);
        if(count($sameNames) > 0 and $libType == 'project') dao::$errors['name'] = $this->lang->doclib->apiNameUnique[$libType] . sprintf($this->lang->error->unique, $this->lang->doclib->name, $lib->name);
        if(count($sameNames) > 0 and $libType == 'nolink')  dao::$errors['name'] = $this->lang->doclib->apiNameUnique[$libType] . sprintf($this->lang->error->unique, $this->lang->doclib->name, $lib->name);
    }

    /**
     * 创建一个操作。
     * Create an action.
     *
     * @param  int    $docID
     * @param  string $action   view|collect
     * @param  string $account
     * @access public
     * @return int|bool
     */
    public function createAction(int $docID, string $action, string $account = ''): int|bool
    {
        if(empty($docID)) return false;

        $docStatus = $this->dao->select('status')->from(TABLE_DOC)->where('id')->eq($docID)->fetch('status');

        if(empty($account))$account = $this->app->user->account;
        if($action == 'collect') $this->dao->delete()->from(TABLE_DOCACTION)->where('doc')->eq($docID)->andWhere('action')->eq('collect')->andWhere('actor')->eq($account)->exec();

        if($action == 'view')
        {
            $lastView = $this->dao->select('date')->from(TABLE_DOCACTION)->where('doc')->eq($docID)->andWhere('action')->eq('view')->andWhere('actor')->eq($account)->orderBy('id_desc')->fetch('date');
            if($lastView and (time() - strtotime($lastView) < 4)) return false;
        }

        $data  = new stdclass();
        $data->doc    = $docID;
        $data->action = $action;
        $data->actor  = $account;
        $data->date   = helper::now();
        $this->dao->insert(TABLE_DOCACTION)->data($data)->autoCheck()->exec();
        if(dao::isError()) return false;

        $actionID = $this->dao->lastInsertID();
        if($action == 'view' && $docStatus == 'normal') $this->dao->update(TABLE_DOC)->set('views = views + 1')->where('id')->eq($docID)->exec();
        if($action == 'collect')
        {
            $collectCount = $this->dao->select('count(*) as count')->from(TABLE_DOCACTION)->where('doc')->eq($docID)->andWhere('action')->eq('collect')->fetch('count');
            $this->dao->update(TABLE_DOC)->set('collects')->eq($collectCount)->where('id')->eq($docID)->exec();
        }

        return $actionID;
    }

    /**
     * 获取文档的所有操作信息。
     * Get action by doc ID.
     *
     * @param  int          $docID
     * @param  string       $action    view|collect
     * @param  string       $account
     * @access public
     * @return object|false
     */
    public function getActionByObject(int $docID, string $action, string $account = ''): object|bool
    {
        if(empty($account)) $account = $this->app->user->account;
        return $this->dao->select('*')->from(TABLE_DOCACTION)->where('doc')->eq($docID)->andWhere('action')->eq($action)->andWhere('actor')->eq($account)->fetch();
    }

    /**
     * 删除一个动作。
     * Delete an action.
     *
     * @param  int    $actionID
     * @access public
     * @return bool
     */
    public function deleteAction(int $actionID): bool
    {
        $action = $this->dao->select('*')->from(TABLE_DOCACTION)->where('id')->eq($actionID)->fetch();
        if(!$action) return false;

        $this->dao->delete()->from(TABLE_DOCACTION)->where('id')->eq($actionID)->exec();
        if($action->action == 'collect')
        {
            $collectCount = $this->dao->select('count(*) as count')->from(TABLE_DOCACTION)->where('doc')->eq($action->doc)->andWhere('action')->eq('collect')->fetch('count');
            $this->dao->update(TABLE_DOC)->set('collects')->eq($collectCount)->where('id')->eq($action->doc)->exec();
        }
        return !dao::isError();
    }

    /**
     * 处理文档的收藏者信息。
     * Process collector to account.
     *
     * @param  array    $docs
     * @access public
     * @return array
     */
    public function processCollector($docs): array
    {
        $actionGroup = $this->dao->select('*')->from(TABLE_DOCACTION)->where('doc')->in(array_keys($docs))->andWhere('action')->eq('collect')->fetchGroup('doc', 'actor');
        foreach($docs as $docID => $doc)
        {
            $doc->collector = '';
            if(isset($actionGroup[$docID])) $doc->collector = ',' . implode(',', array_keys($actionGroup[$docID])) . ',';
        }
        return $docs;
    }

    /**
     * Check other editing.
     *
     * @param  int    $docID
     * @access public
     * @return bool
     */
    public function checkOtherEditing($docID)
    {
        $now     = time();
        $account = $this->app->user->account;
        $docID   = (int)$docID;
        $doc     = $this->dao->select('id,editingDate')->from(TABLE_DOC)->where('id')->eq($docID)->fetch();
        if(empty($doc)) return false;

        $editingDate  = $doc->editingDate ? json_decode($doc->editingDate, true) : array();
        $otherEditing = false;
        foreach($editingDate as $editingAccount => $timestamp)
        {
            if($editingAccount != $account and ($now - $timestamp) <= $this->config->doc->saveDraftInterval)
            {
                $otherEditing = true;
                break;
            }
        }

        $editingDate[$account] = $now;
        $this->dao->update(TABLE_DOC)->set('editingDate')->eq(json_encode($editingDate))->where('id')->eq($docID)->exec();

        return $otherEditing;
    }

    /**
     * Get document dynamic.
     *
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getDynamic($pager = null)
    {
        $allLibs          = $this->getLibs('hasApi');
        $hasPrivDocIdList = $this->getPrivDocs(array(), 0, 'all');
        $apiList          = $this->loadModel('api')->getPrivApis();

        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('vision')->eq($this->config->vision)
            ->andWhere('((objectType')->eq('doclib')
            ->andWhere('objectID')->in(array_keys($allLibs))
            ->markRight(1)
            ->orWhere('(objectType')->eq('doc')
            ->andWhere('objectID')->in($hasPrivDocIdList)
            ->markRight(1)
            ->orWhere('(objectType')->eq('api')
            ->andWhere('objectID')->in(array_keys($apiList))
            ->markRight(2)
            ->orderBy('date_desc')
            ->page($pager)
            ->fetchAll();

        return $this->loadModel('action')->transformActions($actions);
    }

    /**
     * Remove editing.
     *
     * @param  object  $doc
     * @access public
     * @return void
     */
    public function removeEditing($doc)
    {
        if(empty($doc->id) or empty($doc->editingDate)) return false;
        $account     = $this->app->user->account;
        $editingDate = json_decode($doc->editingDate, true);
        if(!isset($editingDate[$account])) return false;

        unset($editingDate[$account]);
        $this->dao->update(TABLE_DOC)->set('editingDate')->eq(json_encode($editingDate))->where('id')->eq($doc->id)->exec();
    }

    /**
     * Get editors by doc id.
     *
     * @param  int    $docID
     * @access public
     * @return array
     */
    public function getEditors($docID = 0)
    {
        if(!$docID) return array();
        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq('doc')
            ->andWhere('objectID')->eq((int)$docID)
            ->andWhere('action')->in('edited')
            ->orderBy('date_desc')
            ->fetchAll('id');

        $editors = array();
        foreach($actions as $action)
        {
            $editor = new stdclass();
            $editor->account = $action->actor;
            $editor->date    = $action->date;

            $editors[] = $editor;
        }

        return $editors;
    }

    /**
     * 构造搜索表单。
     * Build search form.
     *
     * @param  string  $libID
     * @param  array   $libs
     * @param  int     $queryID
     * @param  string  $actionURL
     * @param  string  $type       mine|product|project|execution|custom
     * @access public
     * @return void
     */
    public function buildSearchForm(int $libID, array $libs, int $queryID, string $actionURL, string $type): void
    {
        $this->loadModel('product');
        if($this->app->rawMethod == 'contribute')
        {
            $this->config->doc->search['module'] = 'contributeDoc';
            $this->config->doc->search['params']['project']['values']   = $this->loadModel('project')->getPairsByProgram(0, 'all', false, 'order_asc', $this->config->vision == 'rnd' ? 'kanban' : '') + array('all' => $this->lang->doc->allProjects);
            $this->config->doc->search['params']['execution']['values'] = $this->loadModel('execution')->getPairs(0, 'sprint,stage', 'multiple,leaf,noprefix,withobject') + array('all' => $this->lang->doc->allExecutions);
            $this->config->doc->search['params']['lib']['values']       = $this->loadModel('doc')->getLibs('all', 'withObject') + array('all' => $this->lang->doclib->all);
            $this->config->doc->search['params']['product']['values']   = $this->product->getPairs() + array('all' => $this->lang->doc->allProduct);

            unset($this->config->doc->search['fields']['module'], $this->config->doc->search['params']['module']);
        }
        else
        {
            if(!isset($libs[$libID])) $libs[$libID] = $this->getLibByID($libID);

            $libPairs  = array();
            $queryName = $type . 'libDoc';
            foreach($libs as $lib)
            {
                if(empty($lib)) continue;
                if($lib->type == 'api') continue;
                $libPairs[$lib->id] = $lib->name;
            }

            if($type == 'project')
            {
                $this->config->doc->search['params']['execution']['values'] = $this->loadModel('execution')->getPairs($this->session->project, 'sprint,stage', 'multiple,leaf,noprefix') + array('all' => $this->lang->doc->allExecutions);
            }
            else
            {
                if($type == 'mine' || $type == 'createdby')
                {
                    unset($this->config->doc->search['fields']['addedBy'], $this->config->doc->search['params']['addedBy']);
                    if($type == 'mine') unset($this->config->doc->search['fields']['editedBy'], $this->config->doc->search['params']['editedBy']);
                }
                unset($this->config->doc->search['fields']['execution'], $this->config->doc->search['params']['execution']);
            }

            if(in_array($type, array('view', 'collect', 'createdby', 'editedby'))) $libPairs = $this->getLibs('all', 'withObject');

            $this->config->doc->search['module'] = $queryName;
            $this->config->doc->search['params']['lib']['values'] = $libPairs + array('all' => $this->lang->doclib->all);
            unset($this->config->doc->search['fields']['product'], $this->config->doc->search['params']['product']);
            unset($this->config->doc->search['fields']['module'], $this->config->doc->search['params']['module']);
        }

        unset($this->config->doc->search['params']['status']['values']['']);
        $this->config->doc->search['actionURL'] = $actionURL;
        $this->config->doc->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->doc->search);
    }

    /**
     * 删除附件。
     * Delete files.
     *
     * @param  array  $idList
     * @access public
     * @return bool
     */
    public function deleteFiles(array $idList): bool
    {
        if(empty($idList)) return true;

        $this->dao->update(TABLE_FILE)->set('deleted')->eq('1')->where('id')->in($idList)->exec();
        return !dao::isError();
    }
}
