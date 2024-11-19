<?php
declare(strict_types=1);
/**
 * The model file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: model.php 881 2010-06-22 06:50:32Z chencongzhi520 $
 * @link        https://www.zentao.net
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
     * Adjust the action clickable.
     *
     * @param  object $story
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $doc, string $action): bool
    {
        global $app;
        $action = strtolower($action);

        if($action == 'movedoc') return $doc->addedBy == $app->user->account;
        return true;
    }

    /**
     * Get objectID by Lib.
     *
     * @param  object $lib
     * @param  string $libType
     * @access public
     * @return int
     */
    public function getObjectIDByLib($lib, $libType = '')
    {
        if(empty($libType)) $libType = $lib->type;
        $objectID = ($libType == 'custom' || $libType == 'mine') ? $lib->parent : zget($lib, $libType, 0);

        return (int)$objectID;
    }

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
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getDocLib();

        return $this->dao->findByID($libID)->from(TABLE_DOCLIB)->fetch();
    }

    /**
     * 通过ID获取空间类型
     * Get space type by id.
     *
     * @param  int|string $spaceID
     * @access public
     * @return string
     */
    public function getSpaceType(int|string $spaceID): string
    {
        if(is_string($spaceID) && strpos($spaceID, '.') !== false) return explode('.', $spaceID)[0];
        return $this->dao->findByID((int)$spaceID)->from(TABLE_DOCLIB)->fetch('type');
    }

    /**
     * 获取api文档库。
     * Get api libraries.
     *
     * @param  int    $appendLib
     * @param  string $objectType nolink|product|project
     * @param  int    $objectID
     * @return array
     */
    public function getApiLibs(int $appendLib = 0, string $objectType = '', int $objectID = 0): array
    {
        $libs = $this->dao->select('*')->from(TABLE_DOCLIB)
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq('api')
            ->beginIF(!empty($objectType) && $objectID > 0 && $objectType != 'nolink')->andWhere($objectType)->eq($objectID)->fi()
            ->beginIF($objectType == 'nolink')
            ->andWhere('product')->eq(0)
            ->andWhere('project')->eq(0)
            ->andWhere('execution')->eq(0)
            ->fi()
            ->beginIF(!empty($appendLib))->orWhere('id')->eq($appendLib)->fi()
            ->orderBy('order_asc, id_asc')
            ->fetchAll('id');

        return array_filter($libs, array($this, 'checkPrivLib'));
    }

    /**
     * 获取文档库。
     * Get libraries.
     *
     * @param  string     $type        all|includeDeleted|hasApi|product|project|execution|custom|mine
     * @param  string     $extra       withObject|notdoc
     * @param  int|string $appendLibs
     * @param  int        $objectID
     * @param  string     $excludeType product|project|execution|custom|mine
     * @access public
     * @return array
     */
    public function getLibs(string $type = '', string $extra = '', int|string $appendLibs = '', int $objectID = 0, string $excludeType = ''): array
    {
        $products   = $this->loadModel('product')->getPairs();
        $projects   = $this->loadModel('project')->getPairsByProgram(0, 'all', false, 'order_asc');
        $executions = $this->loadModel('execution')->getPairs(0, 'all', 'multiple,leaf');
        $waterfalls = array();
        if(empty($objectID) && $type == 'execution')
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

        $libPairs = $this->getLibPairs($type, $extra, $objectID, $excludeType, $products, $projects, $executions, $waterfalls);
        if(!empty($appendLibs))
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)->where('id')->in($appendLibs)->orderBy("`order`_asc, id_asc")->query();
            while($lib = $stmt->fetch())
            {
                if($lib->type == 'custom' && $lib->parent == 0) continue;
                if(!isset($libPairs[$lib->id]) && $this->checkPrivLib($lib, $extra)) $libPairs[$lib->id] = $lib->name;
            }
        }

        return $libPairs;
    }

    /**
     * 获取文档库键值对。
     * Get doc liberary pairs.
     *
     * @param  string $type        all|includeDeleted|hasApi|product|project|execution|custom|mine
     * @param  string $extra       withObject|notdoc
     * @param  int    $objectID
     * @param  string $excludeType product|project|execution|custom|mine
     * @param  array  $products
     * @param  array  $projects
     * @param  array  $executions
     * @param  array  $waterfalls
     * @access public
     * @return array
     */
    public function getLibPairs(string $type, string $extra = '', int $objectID = 0, string $excludeType = '', array $products = array(), array $projects = array(), array $executions = array(), array $waterfalls = array()): array
    {
        if(in_array($type, array('all', 'includeDeleted', 'hasApi')))
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)
                ->where('vision')->eq($this->config->vision)
                ->beginIF($type == 'all')->andWhere('deleted')->eq(0)->fi()
                ->beginIF($type != 'hasApi')->andWhere('type')->ne('api')->fi()
                ->beginIF($excludeType)->andWhere('type')->notin($excludeType)->fi()
                ->orderBy('`order`_asc')
                ->query();
        }
        else
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)
                ->where('deleted')->eq(0)
                ->beginIF($type != 'product' or $this->config->vision != 'or')->andWhere('vision')->eq($this->config->vision)->fi()
                ->beginIF($type)->andWhere('type')->eq($type)->fi()
                ->beginIF(!$type)->andWhere('type')->ne('api')->fi()
                ->beginIF($objectID && strpos(',product,project,execution,', ",$type,") !== false)->andWhere($type)->eq($objectID)->fi()
                ->beginIF(($type == 'custom' || $type == 'mine') && !$objectID)->andWhere('parent')->ne(0)->fi()
                ->beginIF(($type == 'custom' || $type == 'mine') && $objectID)->andWhere('parent')->eq($objectID)->fi()
                ->orderBy('`order`_asc')
                ->query();
        }

        $libPairs = array();
        while($lib = $stmt->fetch())
        {
            if($lib->product != 0 && !isset($products[$lib->product])) continue;
            if($lib->execution != 0 && !isset($executions[$lib->execution])) continue;
            if($lib->project != 0 && !isset($projects[$lib->project]) && $lib->type == 'project') continue;
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
                    if($lib->type == 'mine' || $lib->type == 'custom')
                    {
                        if($lib->parent == 0) continue;
                        $parentLib = $this->getLibByID($lib->parent);
                        $lib->name = $parentLib->name . ' / ' . $lib->name;
                    }
                }
                $libPairs[$lib->id] = $lib->name;
            }
        }
        return $libPairs;
    }

    /**
     * 获取一个项目下的执行库。
     * Get execution libraries by project.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getExecutionLibPairsByProject($projectID, $extra = '', $executions = array())
    {
        $libs = $this->dao->select('*')->from(TABLE_DOCLIB)
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq('execution')
            ->andWhere('project')->eq($projectID)
            ->beginIF($this->config->vision != 'or')->andWhere('vision')->eq($this->config->vision)->fi()
            ->fetchAll();

        $libPairs = array();
        foreach($libs as $lib)
        {
            if($this->checkPrivLib($lib))
            {
                if(strpos($extra, 'withObject') !== false)
                {
                    $lib->name = zget($executions, $lib->execution, '') . ' / ' . $lib->name;
                    $lib->name = ltrim($lib->name, '/');
                }
                $libPairs[$lib->id] = $lib->name;
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
        if(!empty($_POST['newSpace']))
        {
            if(!$lib->spaceName)
            {
                dao::$errors['spaceName'] = sprintf($this->lang->error->notempty, $this->lang->doc->space);
                return false;
            }

            $space = clone $lib;
            $space->name   = $space->spaceName;
            $space->parent = 0;
            $space->acl    = 'open';

            $spaceID = $this->docTao->doInsertLib($space);
            if(dao::isError()) return false;

            $lib->parent = $spaceID;
            $this->loadModel('action')->create('docspace', $spaceID, 'created');
        }
        elseif($lib->parent <= 0 && $type == 'custom')
        {
            dao::$errors['parent'] = sprintf($this->lang->error->notempty, $this->lang->doc->space);
            return false;
        }

        if($lib->execution) $lib->type = 'execution';
        if($lib->type == 'execution' && $lib->execution && !$lib->project)
        {
            $execution    = $this->loadModel('execution')->getByID((int)$lib->execution);
            $lib->project = $execution->project;
        }
        if($libType == 'api')
        {
            $lib->type = 'api';
            $this->checkApiLibName($lib, $type);
        }

        $libID = $this->docTao->doInsertLib($lib, $this->config->doc->createlib->requiredFields);
        if(dao::isError()) return false;
        return $libID;
    }

    /**
     * 创建一个API接口库。
     * Creat a api doc library.
     *
     * @param  object   $formData
     * @access public
     * @return bool|int
     */
    public function createApiLib(object $formData = null): bool|int
    {
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

        if(dao::isError()) return false;

        $libID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('doclib', $libID, 'created');

        return $libID;
    }

    /**
     * 更新一个API接口库。
     * Update an api lib.
     *
     * @param  int         $id
     * @param  object      $formData
     * @access public
     * @return array|false
     */
    public function updateApiLib(int $id, object $formData = null): array|bool
    {
        $oldLib = $this->getLibByID($id);

        /* Replace doc library name. */
        $this->app->loadLang('api');
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

        $changes = common::createChanges($oldLib, $formData);
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

        $this->dao->update(TABLE_DOCLIB)->data($lib, 'space')->autoCheck()
            ->batchCheck($this->config->doc->editlib->requiredFields, 'notempty')
            ->where('id')->eq($libID)
            ->exec();

        $this->moveLib($libID, $lib);

        if(dao::isError()) return false;
        return common::createChanges($oldLib, $lib);
    }

    /**
     * 通过类型获取文档列表数据。
     * Get doc list data by browse type.
     *
     * @param  string $browseType all|bySearch|openedbyme|editedbyme|byediteddate|collectedbyme
     * @param  int    $queryID
     * @param  int    $moduleID
     * @param  string $sort
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getDocsByBrowseType(string $browseType, int $queryID, int $moduleID, string $sort, object $pager = null)
    {
        if($browseType == 'all') return $this->getDocs(0, 0, $browseType, $sort, $pager);

        $allLibs          = $this->getLibs('all');
        $allLibIDList     = array_keys($allLibs);
        $hasPrivDocIdList = $this->getPrivDocs($allLibIDList, $moduleID);
        if($browseType == 'bySearch')
        {
            $docs = $this->getMyDocListBySearch($queryID, $hasPrivDocIdList, $allLibIDList, $sort, $pager);
        }
        elseif($browseType == "openedbyme")
        {
            $docs = $this->docTao->getOpenedDocs($hasPrivDocIdList, $sort, $pager);
        }
        elseif($browseType == 'editedbyme')
        {
            $docs = $this->docTao->getEditedDocs($sort, $pager);
        }
        elseif($browseType == 'byediteddate')
        {
            $docs = $this->docTao->getOrderedDocsByEditedDate($hasPrivDocIdList, $allLibIDList, $pager);
        }
        elseif($browseType == "collectedbyme")
        {
            $docs = $this->docTao->getCollectedDocs($hasPrivDocIdList, $sort, $pager);
        }

        if(empty($docs)) return array();
        if(!in_array($browseType, array('bySearch', 'openedbyme', 'editedbyme'))) return $this->processCollector($docs);

        $objects = array();
        list($objects['project'], $objects['execution'], $objects['product']) = $this->getObjectsByDoc(array_keys($docs));
        foreach($docs as $docID => $doc)
        {
            $doc->objectID   = zget($doc, $doc->objectType, 0);
            $doc->objectName = '';
            if(isset($objects[$doc->objectType]))
            {
                $doc->objectName = zget($objects[$doc->objectType], $doc->objectID);
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
     * 获取搜索后的文档列表。
     * Get doc list by search.
     *
     * @param  int    $queryID
     * @param  array  $hasPrivDocIdList
     * @param  array  $allLibIDList
     * @param  string $sort
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getMyDocListBySearch(int $queryID, array $hasPrivDocIdList, array $allLibIDList, string $sort, object $pager = null): array
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
            ->andWhere('actor')->eq($this->app->user->account)
            ->andWhere('action')->eq('edited')
            ->fetchAll('objectID');

        return $this->dao->select('t1.*, t2.name as libName, t2.type as objectType')->from(TABLE_DOC)->alias('t1')
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

    /**
     * 替换查询语句中的all。
     * Replace all in query.
     *
     * @param  string $query
     * @access public
     * @return string
     */
    public function getDocQuery(string $query): string
    {
        $allLib = "`lib` = 'all'";
        if(strpos($query, $allLib) !== false)
        {
            $libPairs  = $this->loadModel('doc')->getLibs('all');
            $libIdList = empty($libPairs) ? 0 : implode(',', array_keys($libPairs));
            $query     = str_replace($allLib, '1', $query);
            $query     = $query . ' AND `lib` ' . helper::dbIN($libIdList);
        }

        $allProject = "`project` = 'all'";
        if(strpos($query, $allProject) !== false)
        {
            $projectPairs  = $this->loadModel('project')->getPairsByProgram();
            $projectIdList = empty($projectPairs) ? 0 : implode(',', array_keys($projectPairs));
            $query         = str_replace($allProject, '1', $query);
            $query         = $query . ' AND `project` in (' . $projectIdList . ')';
        }

        $allProduct = "`product` = 'all'";
        if(strpos($query, $allProduct) !== false)
        {
            $productPairs  = $this->loadModel('product')->getPairs();
            $productIdList = empty($productPairs) ? 0 : implode(',', array_keys($productPairs));
            $query         = str_replace($allProduct, '1', $query);
            $query         = $query . ' AND `product` ' . helper::dbIN($productIdList);
        }

        $allExecution = "`execution` = 'all'";
        if(strpos($query, $allExecution) !== false)
        {
            $executionPairs  = $this->loadModel('execution')->getPairs();
            $executionIdList = empty($executionPairs) ? 0 : implode(',', array_keys($executionPairs));
            $query           = str_replace($allExecution, '1', $query);
            $query           = $query . ' AND `execution` ' . helper::dbIN($executionIdList);
        }

        return $query;
    }

    /**
     * 通过文档ID获取文档所属产品、项目、执行。
     * Get projects, executions and products by docIdList.
     *
     * @param  array $docIdList
     * @access public
     * @return array
     */
    public function getObjectsByDoc(array $docIdList = array()): array
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
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getDocs();

        if(empty($libID) && $browseType != 'all') return array();

        $docIdList = $this->getPrivDocs($libID ? array($libID) : array(), $moduleID, 'children');
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

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'doc', true);

        return $this->processCollector($docs);
    }

    /**
     * Get docs by id list.
     *
     * @param  array  $docIdList
     * @access public
     * @return array
     */
    public function getDocsByIdList(array $docIdList): array
    {
        return $this->dao->select('*')->from(TABLE_DOC)->where('id')->in($docIdList)->fetchAll('id');
    }

    /**
     * 获取文档列表数据。
     * Get doc list.
     *
     * @param  array  $libs
     * @param  string $spaceType
     * @access public
     * @return array
     */
    public function getDocsOfLibs(array $libs, string $spaceType): array
    {
        $docs = $this->dao->select('t1.*')->from(TABLE_DOC)->alias('t1')
            ->leftJoin(TABLE_MODULE)->alias('t2')->on('t1.module=t2.id')
            ->where('t1.lib')->in($libs)
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.templateType')->eq('')
            ->andWhere("(t1.status = 'normal' or (t1.status = 'draft' and t1.addedBy='{$this->app->user->account}'))")
            ->andWhere('t2.type')->eq('doc')
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy('t1.`order` asc, t1.id asc')
            ->fetchAll('id');

        $rootDocs = $this->dao->select('*')->from(TABLE_DOC)
            ->where('lib')->in($libs)
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('templateType')->eq('')
            ->andWhere("(status = 'normal' or (status = 'draft' and addedBy='{$this->app->user->account}'))")
            ->andWhere('module')->eq(0)
            ->andWhere('deleted')->eq('0')
            ->orderBy('`order` asc, id_asc')
            ->fetchAll('id');

        $docs = $docs + $rootDocs;
        $docs = $this->processCollector($docs);
        $docs = $this->filterPrivDocs($docs, $spaceType);

        foreach($docs as &$doc)
        {
            $doc->lib         = (int)$doc->lib;
            $doc->module      = (int)$doc->module;
            $doc->deleted     = boolval($doc->deleted);
            $doc->isCollector = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false;
            $doc->title       = htmlspecialchars_decode($doc->title);
            if(!empty($doc->keywords) && is_string($doc->keywords)) $doc->keywords = htmlspecialchars_decode($doc->keywords);
            unset($doc->content);
            unset($doc->draft);
        }

        $apis = $this->loadModel('api')->getApiListBySearch(0, 0, '', $libs);
        foreach($apis as &$api)
        {
            $api->id          = "api.$api->id";
            $api->lib         = (int)$api->lib;
            $api->module      = (int)$api->module;
            $api->deleted     = boolval($api->deleted);
            $api->originTitle = $api->title;
            $api->icon        = "api is-$api->method";
            $api->title       = "$api->method $api->path $api->title";

            $docs[$api->id] = $api;
        }

        return $docs;
    }

    /**
     * 过滤出有权限的文档列表。
     * Filter docs which has privilege.
     *
     * @param  array  $docs
     * @param  string $spaceType
     * @access public
     * @return array
     */
    public function filterPrivDocs(array $docs, string $spaceType): array
    {
        $currentAccount = $this->app->user->account;
        $userGroups     = $this->app->user->groups;

        $privDocs = array();
        foreach($docs as $doc)
        {
            $isOpen = $doc->acl == 'open';
            $isAuthorOrAdmin = $doc->acl == 'private' && ($doc->addedBy == $currentAccount || ($this->app->user->admin && $spaceType !== 'mine'));
            $isInUsers = strpos(",$doc->users,", ",$currentAccount,") !== false;
            if($isOpen || $isAuthorOrAdmin || $isInUsers)
            {
                $privDocs[] = $doc;
            }
            elseif(!empty($doc->groups))
            {
                foreach($userGroups as $groupID)
                {
                    if(strpos(",$doc->groups,", ",$groupID,") !== false)
                    {
                        $privDocs[] = $doc;
                        break;
                    }
                }
            }
        }

        return $privDocs;
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
            if(!isset($modules[$doc->lib])) $modules[$doc->lib] = $this->tree->getOptionMenu((int)$doc->lib, 'doc', 0, 'all', 'nodeleted', 'all', ' > ');
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
            $docSQL = $this->dao->select('MAX(id)')->from(TABLE_DOCACTION)
                ->where('action')->eq($type)
                ->andWhere('actor')->eq($this->app->user->account)
                ->groupBy('doc')
                ->get();

            $docs = $this->dao->select('t1.*,t2.date,t3.name as libName,t3.type as objectType')->from(TABLE_DOC)->alias('t1')
                ->leftJoin(TABLE_DOCACTION)->alias('t2')->on("t1.id=t2.doc")
                ->leftJoin(TABLE_DOCLIB)->alias('t3')->on("t1.lib=t3.id")
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.lib')->ne('')
                ->andWhere('t1.type')->in($this->config->doc->docTypes)
                ->andWhere('t1.vision')->eq($this->config->vision)
                ->andWhere('t2.id')->subIn($docSQL)
                ->beginIF(!common::hasPriv('doc', 'productSpace'))->andWhere('t3.type')->ne('product')->fi()
                ->beginIF(!common::hasPriv('doc', 'projectSpace'))->andWhere('t3.type')->notIN('project,execution')->fi()
                ->beginIF(!common::hasPriv('doc', 'teamSpace'))->andWhere('t3.type')->ne('custom')->fi()
                ->beginIF(in_array($browseType, array('all', 'bysearch')))->andWhere("(t1.status = 'normal' or (t1.status = 'draft' and t1.addedBy='{$this->app->user->account}'))")->fi()
                ->beginIF($browseType == 'draft')->andWhere('t1.status')->eq('draft')->andWhere('t1.addedBy')->eq($this->app->user->account)->fi()
                ->beginIF($browseType == 'bysearch')->andWhere($query)->fi()
                ->beginIF(!empty($hasPrivDocIdList))->andWhere('t1.id')->in($hasPrivDocIdList)->fi()
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

        /* 团队空间下的空间列出所有子库的文档。 */
        if(count($libIdList) == 1)
        {
            $libID = current($libIdList);
            $lib   = $this->getLibByID((int)$libID);
            if($lib->type == 'custom' && $lib->parent == 0)
            {
                $libs = $this->dao->select('*')->from(TABLE_DOCLIB)->where('parent')->eq($libID)->andWhere('deleted')->eq('0')->fetchAll();
                foreach($libs as $subLib)
                {
                    if($this->checkPrivLib($subLib)) $libIdList[] = $subLib->id;
                }
            }
        }

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
     * 通过ID获取文档信息。
     * Get doc info by id.
     *
     * @param  int          $docID
     * @param  int          $version
     * @param  bool         $setImgSize
     * @access public
     * @return object|false
     */
    public function getByID(int $docID, int $version = 0, bool $setImgSize = false): object|bool
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getDoc();

        $doc = $this->dao->select('*')->from(TABLE_DOC)
            ->where('id')->eq((int)$docID)
            ->andWhere('vision')->eq($this->config->vision)
            ->fetch();
        if(!$doc) return false;

        $docs    = $this->processCollector(array($doc->id => $doc));
        $doc     = $docs[$doc->id];
        $version = $version ? $version : $doc->version;

        $doc->releasedBy = $doc->releasedDate = '';
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

        return $this->processDoc($doc, (int)$version, $setImgSize);
    }

    /**
     * 处理文档数据。
     * Process doc data.
     *
     * @param  object $doc
     * @param  bool   $setImgSize
     * @access public
     * @return object
     */
    public function processDoc(object $doc, int $version, bool $setImgSize = false): object
    {
        /* When file change then version add one. */
        $files      = $this->loadModel('file')->getByObject('doc', $doc->id);
        $docFiles   = array();
        $docContent = $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq($doc->id)->andWhere('version')->eq($version)->fetch();
        if($docContent)
        {
            foreach($files as $file)
            {
                $this->loadModel('file')->setFileWebAndRealPaths($file);
                if(strpos(",{$docContent->files},", ",{$file->id},") !== false) $docFiles[$file->id] = $file;
            }
        }

        /* Check file change. */
        if($version == $doc->version && ((empty($docContent->files) && $docFiles) || (!empty($docContent->files) && count(explode(',', trim($docContent->files, ','))) != count($docFiles))))
        {
            unset($docContent->id);
            $doc->version       += 1;
            $docContent->version = $doc->version;
            $docContent->files   = join(',', array_keys($docFiles));
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
            $this->dao->update(TABLE_DOC)->set('version')->eq($doc->version)->where('id')->eq($doc->id)->exec();
        }

        $doc->title          = isset($docContent->title) ? $docContent->title : $doc->title;
        $doc->digest         = isset($docContent->digest) ? $docContent->digest : '';
        $doc->content        = isset($docContent->content) ? $docContent->content : '';
        $doc->contentType    = isset($docContent->type) ? $docContent->type : '';
        $doc->contentVersion = isset($docContent->version) ? $docContent->version : $version;
        if($doc->type != 'url' && $doc->contentType != 'markdown' && $doc->contentType != 'doc') $doc = $this->loadModel('file')->replaceImgURL($doc, 'content,draft');
        if($setImgSize) $doc->content = $this->file->setImgSize($doc->content);
        $doc->files = $docFiles;

        $doc->productName = $doc->executionName = $doc->moduleName = '';
        if($doc->product)   $doc->productName   = $this->dao->findByID($doc->product)->from(TABLE_PRODUCT)->fetch('name');
        if($doc->execution) $doc->executionName = $this->dao->findByID($doc->execution)->from(TABLE_EXECUTION)->fetch('name');
        if($doc->module)    $doc->moduleName    = $this->dao->findByID($doc->module)->from(TABLE_MODULE)->fetch('name');
        if(!$doc->module && $doc->type == 'article' && $doc->parent) $doc->moduleName = $this->dao->findByID($doc->parent)->from(TABLE_DOC)->fetch('title');

        return $doc;
    }

    /**
     * 通过ID列表获取文档信息。
     * Get docs info by id list.
     *
     * @param  array  $docIdList
     * @access public
     * @return array
     */
    public function getByIdList(array $docIdList = array()): array
    {
        return $this->dao->select('*,t1.id as docID,t1.type as docType,t1.version as docVersion,t2.type as contentType')->from(TABLE_DOC)->alias('t1')
            ->leftJoin(TABLE_DOCCONTENT)->alias('t2')->on('t1.id=t2.doc and t1.version=t2.version')
            ->where('t1.id')->in($docIdList)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('docID');
    }

    /**
     * 获取团队空间下的空间。
     * Get team spaces.
     *
     * @access public
     * @return array
     */
    public function getTeamSpaces(): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getSubSpaces('custom');

        return $this->getSpacePairs('custom');
    }

    /**
     * 获取文档模板中的空间。
     * Get doctemplate spaces.
     *
     * @access public
     * @return array
     */
    public function getDocTemplateSpaces()
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getTeamSpaces();

        $spaces = $this->getSpacePairs('doctemplate');
        if(empty($spaces)) $this->initDocTemplateSpaces();

        return $this->getSpacePairs('doctemplate');
    }

    /**
     * 初始化文档模板空间。
     * Init doctemplate spaces.
     *
     * @access public
     * @return array
     */
    public function initDocTemplateSpaces()
    {
        $this->loadModel('doctempalte');
        $defaultSpaces = $this->config->doctemplate->defaultSpaces;

        foreach($defaultSpaces as $code => $childs)
        {
            $spaceID = $this->doInsertLib($this->initDocDefaultSpaces($code));

            foreach($childs as $childCode) $this->doInsertLib($this->initDocDefaultSpaces($childCode, $spaceID));
        }
    }

    /**
     * 初始化文档模板空间。
     * Init doctemplate spaces.
     *
     * @access public
     * @return array
     */
    public function initDocDefaultSpaces($code, $parent = 0)
    {
        $space = new stdclass();
        $space->type      = 'doctemplate';
        $space->vision    = 'rnd';
        $space->parent    = $parent;
        $space->name      = $this->lang->doctemplate->$code;
        $space->acl       = 'open';
        $space->addedBy   = 'system';
        $space->addedDate = helper::now();

        return $space;
    }

    /**
     * 获取团队空间或我的空间下的空间。
     * Get team spaces or my spaces.
     *
     * @param  string $type all|mine|custom
     * @param  bool   $withType
     * @access public
     * @return array
     */
    public function getSubSpacesByType(string $type = 'all', bool $withType = false): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getSubSpaces($type);

        $objectLibs = $this->dao->select('*')->from(TABLE_DOCLIB)
            ->where('deleted')->eq(0)
            ->andWhere('parent')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($type == 'all')->andWhere('type')->in('mine,custom')->fi()
            ->beginIF($type != 'all')->andWhere('type')->eq($type)->fi()
            ->beginIF($type == 'mine')->andWhere('addedBy')->eq($this->app->user->account)->fi()
            ->orderBy('type_desc')
            ->fetchAll();

        $pairs = array();
        foreach($objectLibs as $key => $lib)
        {
            if($lib->type === 'mine' && $lib->addedBy != $this->app->user->account)
            {
                unset($objectLibs[$key]);
                continue;
            }

            if($this->checkPrivLib($lib))
            {
                $key = $withType ? "{$lib->type}.{$lib->id}" : $lib->id;
                $pairs[$key] = $type == 'all' ? $this->lang->doc->spaceList[$lib->type] . '/' . $lib->name : $lib->name;
            }
        }

        return $pairs;
    }

    /**
     * 获取lib的targetSpace，返回的是type.id。
     *
     * @access public
     * @return string
     */
    public function getLibTargetSpace($lib)
    {
        $type = $lib->type;
        if(in_array($type, array('product', 'project')))
        {
            $targetSpace = "{$type}.{$lib->$type}";
        }
        else
        {
            $targetSpace = "{$type}.{$lib->parent}";
        }

        return $targetSpace;
    }

    /**
     * 解析targetSpace。
     *
     * @param  string $targetSpace
     * @param  string $paramType type|id
     * @access public
     * @return array
     */
    public function getParamFromTargetSpace($targetSpace, $paramType = 'type')
    {
        $params = explode('.', $targetSpace);

        if($paramType == 'type') return $params[0];
        if($paramType == 'id')   return $params[1];
    }

    /**
     * 获取所有子空间。
     * Get all sub spaces.
     *
     * @access public
     * @return array
     */
    public function getAllSubSpaces()
    {
        $productList = $this->loadModel('product')->getPairs('nocode');
        $projectList = $this->loadModel('project')->getPairsByProgram();

        $spaceList = $this->dao->select('*')->from(TABLE_DOCLIB)
            ->where('deleted')->eq(0)
            ->andWhere('parent')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('type', true)->eq('custom')
            ->orWhere('(type')->eq('mine')->andWhere('addedBy')->eq($this->app->user->account)
            ->markRight(2)
            ->orderBy('type_desc')
            ->fetchAll();

        $productPairs = $projectPairs = $spacePairs = array();
        foreach($productList as $productID => $productName) $productPairs["product.{$productID}"] = $this->lang->doc->spaceList['product'] . '/' . $productName;
        foreach($projectList as $projectID => $projectName) $projectPairs["project.{$projectID}"] = $this->lang->doc->spaceList['project'] . '/' . $projectName;
        foreach($spaceList as $space)
        {
            if($this->checkPrivLib($space)) $spacePairs["{$space->type}.{$space->id}"] = $this->lang->doc->spaceList[$space->type] . '/' . $space->name;
        }

        return array_merge($spacePairs, $productPairs, $projectPairs);
    }

    /**
     * 获取空间列表。
     * Get spaces.
     *
     * @param  string $type
     * @param  int    $spaceID
     * @access public
     * @return array
     */
    public function getSpaces($type = 'custom', $spaceID = 0)
    {
        $spaces = array();
        if($type == 'mine' || $type == 'custom')
        {
            $pairs = $this->getSubSpacesByType($type);
            foreach($pairs as $key => $value) $spaces[] = array('id' => $key, 'name' => $value, 'type' => $type, 'canModify' => true);
        }
        if($type == 'product')
        {
            $account  = $this->app->user->account;
            $products = $this->loadModel('product')->getList();
            $spaceID  = $this->product->checkAccess($spaceID, $products);
            foreach($products as $product)
            {
                $isMine    = $product->status == 'normal' && $product->PO == $account;
                $canModify = common::canModify('product', $product);
                $spaces[]  = array('id' => $product->id, 'name' => $product->name, 'isMine' => $isMine, 'type' => $type, 'canModify' => $canModify);
            }
        }
        if($type == 'project')
        {
            $projects         = $this->loadModel('project')->getListByCurrentUser();
            $involvedProjects = $this->project->getInvolvedListByCurrentUser();
            $spaceID          = $this->project->checkAccess($spaceID, $projects);

            foreach($projects as $project)
            {
                $isMine    = $project->status != 'closed' && isset($involvedProjects[$project->id]);
                $canModify = common::canModify('project', $project);
                $spaces[]  = array('id' => $project->id, 'name' => $project->name, 'isMine' => $isMine, 'type' => $type, 'canModify' => $canModify);
            }
        }
        if($type === 'execution' && $spaceID)
        {
            $execution = $this->loadModel('execution')->getByID($spaceID);
            $canModify = common::canModify('execution', $execution);
            $spaces[]  = array('id' => $execution->id, 'name' => $execution->name, 'canModify' => $canModify);
        }

        if(!$spaceID && !empty($spaces)) $spaceID = $spaces[0]['id'];

        return array($spaces, $spaceID);
    }

    /**
     * 创建独立的文档。
     * Create a seperate docs.
     *
     * @param  object             $doc
     * @access public
     * @return array|false|string
     */
    public function createSeperateDocs(object $doc): array|bool|string
    {
        if($doc->acl == 'open') $doc->users = $doc->groups = '';
        if(empty($doc->lib) && strpos((string)$doc->module, '_') !== false) list($doc->lib, $doc->module) = explode('_', $doc->module);
        if(empty($doc->lib)) return dao::$errors['lib'] = sprintf($this->lang->error->notempty, $this->lang->doc->lib);

        $files = $this->loadModel('file')->getUpload();
        if(empty($files) || isset($files['name'])) return dao::$errors['files'] = sprintf($this->lang->error->notempty, $this->lang->doc->uploadFile);

        $lib = $this->getLibByID($doc->lib);
        $doc = $this->loadModel('file')->processImgURL($doc, $this->config->doc->editor->create['id'], (string)$this->post->uid);

        $doc->product   = $lib->product;
        $doc->project   = $lib->project;
        $doc->execution = $lib->execution;

        $docContent          = new stdclass();
        $docContent->title   = $doc->title;
        $docContent->content = '';
        $docContent->type    = $doc->contentType;
        $docContent->digest  = '';
        $docContent->version = 1;
        unset($doc->contentType);

        return $this->insertSeperateDocs($doc, $docContent, $files);
    }

    /**
     * 批量插入独立的文档。
     * Insert seperate docs.
     *
     * @param  object     $doc
     * @param  object     $docContent
     * @param  array      $files
     * @access public
     * @return array|bool
     */
    public function insertSeperateDocs(object $doc, object $docContent, array $files): array|bool
    {
        $this->loadModel('file');

        $doc->draft = '';
        $docsAction = array();
        foreach($files as $file)
        {
            $title    = $file['title'];
            $position = strrpos($title, '.');
            if($position > 0) $title = substr($title, 0, $position);

            $doc->title = $title;
            $this->dao->insert(TABLE_DOC)->data($doc, 'content')->autoCheck()
                ->batchCheck($this->config->doc->create->requiredFields, 'notempty')
                ->exec();
            if(dao::isError()) return false;

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

        if(dao::isError()) return false;
        return array('status' => 'new', 'docsAction' => $docsAction, 'docType' => 'attachment', 'libID' => $doc->lib);
    }

    /**
     * 创建一个文档。
     * Create a doc.
     *
     * @param  object            $doc
     * @access public
     * @return array|bool|string
     */
    public function create(object $doc): array|bool|string
    {
        if($doc->acl == 'open') $doc->users = $doc->groups = '';
        if(empty($doc->lib) && strpos((string)$doc->module, '_') !== false) list($doc->lib, $doc->module) = explode('_', $doc->module);
        if(empty($doc->lib)) return dao::$errors['lib'] = sprintf($this->lang->error->notempty, $this->lang->doc->lib);

        $lib = $this->getLibByID($doc->lib);
        if($doc->contentType != 'doc') $doc = $this->loadModel('file')->processImgURL($doc, $this->config->doc->editor->create['id'], (string)$this->post->uid);
        $doc->product   = $lib->product;
        $doc->project   = $lib->project;
        $doc->execution = $lib->execution;

        $docContent          = new stdclass();
        $docContent->title   = $doc->title;
        $docContent->content = $doc->content;
        $docContent->type    = $doc->contentType;
        $docContent->digest  = '';
        $docContent->version = 1;
        unset($doc->contentType);

        $requiredFields = $this->config->doc->create->requiredFields;
        if($doc->status == 'draft') $requiredFields = 'title';
        if(strpos("url|word|ppt|excel", $doc->type) !== false) $requiredFields = trim(str_replace(",content,", ",", ",{$requiredFields},"), ',');

        $checkContent = strpos(",$requiredFields,", ',content,') !== false;
        if($checkContent && strpos("url|word|ppt|excel|", $lib->type) === false)
        {
            $requiredFields = trim(str_replace(',content,', ',', ",$requiredFields,"), ',');
            if(empty($docContent->content)) return dao::$errors['content'] = sprintf($this->lang->error->notempty, $this->lang->doc->content);
        }

        $files = $this->loadModel('file')->getUpload();
        if($doc->type == 'attachment' && (empty($files) || isset($files['name']))) return dao::$errors['files'] = sprintf($this->lang->error->notempty, $this->lang->doc->uploadFile);

        $doc->draft  = $docContent->content;
        $doc->vision = $this->config->vision;
        $this->dao->insert(TABLE_DOC)->data($doc, 'content')->autoCheck()
            ->batchCheck($requiredFields, 'notempty')
            ->exec();
        if(dao::isError()) return false;

        $docID = $this->dao->lastInsertID();

        $this->dao->update(TABLE_DOC)->set('`order`')->eq($docID)->where('id')->eq($docID)->exec();
        $this->file->updateObjectID($this->post->uid, $docID, 'doc');
        $files = $this->file->saveUpload('doc', $docID);

        $docContent->doc   = $docID;
        $docContent->files = join(',', array_keys($files));
        $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
        $this->loadModel('score')->create('doc', 'create', $docID);
        return array('status' => 'new', 'id' => $docID, 'files' => $files, 'docType' => $doc->type, 'libID' => $doc->lib);
    }

    /**
     * 编辑一个文档。
     * Update a doc.
     *
     * @param  int               $docID
     * @param  object            $doc
     * @access public
     * @return array|string|bool
     */
    public function update(int $docID, object $doc): array|string|bool
    {
        $oldDoc = $this->dao->select('*')->from(TABLE_DOC)->where('id')->eq($docID)->fetch();
        list($doc, $oldDocContent) = $this->processDocForUpdate($oldDoc, $doc);
        if(dao::isError()) return false;

        $requiredFields = $doc->status == 'draft' ? 'title' : $this->config->doc->edit->requiredFields;
        if(strpos(",$requiredFields,", ',content,') !== false)
        {
            $requiredFields = trim(str_replace(',content,', ',', ",$requiredFields,"), ',');
            if(isset($doc->content) && empty($doc->content)) return dao::$errors['content'] = sprintf($this->lang->error->notempty, $this->lang->doc->content);
        }

        $files   = $this->file->saveUpload('doc', $docID);
        $changes = common::createChanges($oldDoc, $doc);
        $changed = $files ? true : false;
        foreach($changes as $change) if($change['field'] == 'content' || $change['field'] == 'title') $changed = true;

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

            if($oldDoc->status == 'draft') $this->dao->update(TABLE_DOCCONTENT)->data($docContent)->where('id')->eq($oldDocContent->id)->exec();
            if($oldDoc->status != 'draft')
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
            ->where('id')->eq($docID)
            ->exec();

        if(dao::isError()) return false;
        $this->file->updateObjectID($this->post->uid, $docID, 'doc');
        return array('changes' => $changes, 'files' => $files);
    }

    /**
     * 为更新文档处理数据。
     * Process data for update a doc.
     *
     * @param  object $oldDoc
     * @param  object $doc
     * @access public
     * @return array
     */
    public function processDocForUpdate(object $oldDoc, object $doc): array
    {
        $editingDate = $oldDoc->editingDate ? json_decode($oldDoc->editingDate, true) : array();
        unset($editingDate[$this->app->user->account]);
        $doc->editingDate = json_encode($editingDate);

        if($doc->acl == 'open') $doc->users = $doc->groups = '';
        if($doc->type == 'chapter' && $doc->parent)
        {
            $parentDoc = $this->dao->select('*')->from(TABLE_DOC)->where('id')->eq((int)$doc->parent)->fetch();
            if(strpos($parentDoc->path, ",$oldDoc->id,") !== false) return dao::$errors['parent'] = $this->lang->doc->errorParentChapter;
        }

        $oldDocContent = $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq($oldDoc->id)->andWhere('version')->eq($oldDoc->version)->fetch();
        if($oldDocContent)
        {
            $oldDoc->title       = $oldDocContent->title;
            $oldDoc->digest      = $oldDocContent->digest;
            $oldDoc->content     = $oldDocContent->content;
            $oldDoc->contentType = $oldDocContent->type;
        }

        $lib = !empty($doc->lib) ? $this->getLibByID($doc->lib) : '';
        if(!isset($doc->contentType) || $doc->contentType !== 'doc') $doc = $this->loadModel('file')->processImgURL($doc, $this->config->doc->editor->edit['id'], (string)$this->post->uid);

        if(!empty($lib))
        {
            $doc->product   = $lib->product;
            $doc->execution = $lib->execution;
        }

        return array($doc, $oldDocContent);
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
        if(strpos($query, "`lib` = ''") !== false) $query = str_replace("`lib` = ''", '1', $query);
        return $query;
    }

    /**
     * 获取执行文档库的所属模块的键值对。
     * Gets the key-value pair of the module by execution ID.
     *
     * @access public
     * @return array
     */
    public function getExecutionModulePairs(): array
    {
        return $this->dao->select('t1.id,t1.name')->from(TABLE_MODULE)->alias('t1')
            ->leftJoin(TABLE_DOCLIB)->alias('t2')->on('t1.root = t2.id')
            ->where('t1.type')->eq('doc')
            ->andWhere('t2.execution')->ne('0')
            ->fetchPairs();
    }

    /**
     * 检查是否有权限访问文档库/文档。
     * Check priv for lib.
     *
     * @param  object|false $object
     * @param  string       $extra
     * @access public
     * @return bool
     */
    public function checkPrivLib(object|bool $object, string $extra = ''): bool
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
     * 检查文档权限。
     * Check privilege for the document.
     *
     * @param  object $doc
     * @access public
     * @return bool
     */
    public function checkPrivDoc(object $doc): bool
    {
        if(!isset($doc->lib)) return false;

        /* Asset document don't check privilege. */
        if((isset($doc->assetLibType) && $doc->assetLibType) || $doc->type == 'article') return true;

        /* My document are accessible only to the creator. */
        if($doc->status == 'draft' && $doc->addedBy != $this->app->user->account) return false;
        if($doc->status == 'normal' && $this->app->user->admin) return true;

        static $libs = array();
        if(!isset($libs[$doc->lib])) $libs[$doc->lib] = $this->getLibByID((int)$doc->lib);
        if(!$this->checkPrivLib($libs[$doc->lib])) return false;
        if(in_array($doc->acl, array('open', 'public'))) return true;

        /* Whitelist users can access private document. */
        $account = ",{$this->app->user->account},";
        if(isset($doc->addedBy) && $doc->addedBy == $this->app->user->account) return true;
        if(strpos(",$doc->users,", $account) !== false) return true;
        if($doc->groups)
        {
            foreach($this->app->user->groups as $groupID)
            {
                if(strpos(",$doc->groups,", ",$groupID,") !== false) return true;
            }
        }

        return false;
    }

    /**
     * 通过对象ID获取文档库。
     * Get libs by object.
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  int    $appendLib
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getLibsByObject(string $type, int $objectID = 0, int $appendLib = 0, int $limit = 0): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getDocLibs();

        if(!in_array($type, array('mine', 'custom', 'product', 'project', 'execution', 'doctemplate'))) return array();
        if(in_array($type, array('mine', 'custom', 'doctemplate')))
        {
            $objectLibs = $this->dao->select('*')->from(TABLE_DOCLIB)
                ->where('deleted')->eq(0)
                ->andWhere('vision')->eq($this->config->vision)
                ->andWhere('type')->eq($type)
                ->andWhere('parent')->eq($objectID)
                ->beginIF(!empty($appendLib))->orWhere('id')->eq($appendLib)->fi()
                ->orderBy('`order` asc, id_asc')
                ->limit($limit)
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
                ->limit($limit)
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
                    ->limit($limit)
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
     * 获取指定类型各个子空间文档库概要信息。
     * Get doclib summary of each sub space.
     *
     * @param  string $type
     * @param  array  $libIDList
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getLibsOfSpaces(string $type, string|array $spaceList, $limit = 5)
    {
        if(is_string($spaceList)) $spaceList = explode(',', $spaceList);
        if(empty($spaceList)) return array();

        $map       = array();
        $libIDList = array();
        foreach($spaceList as $spaceID)
        {
            $libs      = $this->getLibsByObject($type, (int)$spaceID, 0, $limit);
            $libIDList = array_merge($libIDList, array_keys($libs));
            $map[$spaceID] = array_values($libs);
        }

        $docCounts = array();
        $docs = $this->dao->select("`id`,`addedBy`,`type`,`lib`,`acl`,`users`,`groups`,`status`")->from(TABLE_DOC)
            ->where('lib')->in($libIDList)
            ->andWhere('deleted')->eq(0)
            ->fetchAll();

        foreach($docs as $doc)
        {
            if(!$this->checkPrivDoc($doc)) continue;

            if(!isset($docCounts[$doc->lib])) $docCounts[$doc->lib] = 0;
            $docCounts[$doc->lib] ++;
        }

        $executionLibs = array();
        foreach($map as $spaceID => &$libs)
        {
            foreach($libs as &$lib)
            {
                $lib->docs = isset($docCounts[$lib->id]) ? $docCounts[$lib->id] : 0;
                if($lib->type != 'execution') continue;
                if(!isset($executionLibs[$lib->execution])) $executionLibs[$lib->execution] = array();
                $executionLibs[$lib->execution][] = $lib;
            }
        }

        if(!empty($executionLibs))
        {
            $executionPairs = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('id')->in(array_keys($executionLibs))->fetchPairs();

            foreach($executionLibs as $executionID => &$libList)
            {
                foreach($libList as &$lib)
                {
                    $lib->originName    = $lib->name;
                    $lib->executionName = $executionPairs[$executionID];
                    $lib->name          = $lib->executionName . '/' . $lib->name;
                }
            }
        }

        return $map;
    }

    /**
     * 获取空间下的文档库。
     * Get libs of space.
     *
     * @param  string $type
     * @param  int    $spaceID
     * @access public
     * @return array
     */
    public function getLibsOfSpace(string $type, int $spaceID): array
    {
        $libs = $this->getLibsByObject($type, $spaceID);

        $executionIDList = array();
        $apiLibs         = array();
        foreach($libs as $lib)
        {
            if($lib->type == 'api') $apiLibs[$lib->id] = $lib;
            if($lib->type != 'execution') continue;
            $executionIDList[] = $lib->execution;
        }

        if($type == 'project' && !empty($executionIDList))
        {
            $executionPairs = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('id')->in($executionIDList)->fetchPairs();
            foreach($libs as &$lib)
            {
                if($lib->type != 'execution') continue;
                $lib->originName    = $lib->name;
                $lib->executionName = $executionPairs[$lib->execution];
                $lib->name          = $lib->executionName . '/' . $lib->name;
            }
        }

        if(!empty($apiLibs))
        {
            $releases   = $this->loadModel('api')->getReleaseByQuery(array_keys($apiLibs));
            $releaseMap = array();
            foreach($releases as $release) $releaseMap[$release->lib][] = $release;
            foreach($apiLibs as $lib) $lib->versions = isset($releaseMap[$lib->id]) ? $releaseMap[$lib->id] : array();
        }

        return $libs;
    }

    /**
     * 获取已排序的对象数据。
     * Get ordered objects for doc.
     *
     * @param  string $objectType
     * @param  string $returnType nomerge|merge
     * @param  int    $append
     * @access public
     * @return array
     */
    public function getOrderedObjects(string $objectType = 'product', string $returnType = 'merge', int $append = 0): array
    {
        $myObjects = $normalObjects = $closedObjects = array();
        if($objectType == 'product')
        {
            list($myObjects, $normalObjects, $closedObjects) = $this->getOrderedProducts($append);
        }
        elseif($objectType == 'project')
        {
            list($myObjects, $normalObjects, $closedObjects) = $this->getOrderedProjects($append);
        }
        elseif($objectType == 'execution')
        {
            list($myObjects, $normalObjects, $closedObjects) = $this->docTao->getOrderedExecutions($append);
        }

        if($returnType == 'nomerge') return array($myObjects, $normalObjects, $closedObjects);
        return $myObjects + $normalObjects + $closedObjects;
    }

    /**
     * 获取已排序的产品数据。
     * Get ordered products.
     *
     * @param  int    $append
     * @access public
     * @return array
     */
    public function getOrderedProducts(int $append = 0): array
    {
        $myObjects = $normalObjects = $closedObjects = array();
        $products  = $this->loadModel('product')->getList();
        if($append && !isset($products[$append])) $products[$append] = $this->product->getByID($append);
        foreach($products as $id => $product)
        {
            if($product->status != 'closed' && $product->PO == $this->app->user->account)
            {
                $myObjects[$id] = $product->name;
            }
            elseif($product->status != 'closed' && !($product->PO == $this->app->user->account))
            {
                $normalObjects[$id] = $product->name;
            }
            elseif($product->status == 'closed')
            {
                $closedObjects[$id] = $product->name;
            }
        }

        return array($myObjects, $normalObjects, $closedObjects);
    }

    /**
     * 获取已排序的项目数据。
     * Get ordered projects.
     *
     * @param  int    $append
     * @access public
     * @return array
     */
    public function getOrderedProjects(int $append = 0): array
    {
        $this->loadModel('program');

        /* Project permissions for DocLib whitelist. */
        $myObjects       = $normalObjects = $closedObjects = array();
        $orderedProjects = array();
        if($this->app->tab == 'doc')
        {
            $myObjects = $this->dao->select('t1.id, t1.name')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_DOCLIB)->alias('t2')->on('t2.project=t1.id')
                ->where("CONCAT(',', t2.users, ',')")->like("%,{$this->app->user->account},%")
                ->andWhere('t1.vision')->eq($this->config->vision)
                ->andWhere('t1.deleted')->eq(0)
                ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->projects)->fi()
                ->fetchPairs();
        }
        $objects = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('deleted')->eq(0)
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
            if($project->status != 'done' && $project->status != 'closed' && $project->PM == $this->app->user->account)
            {
                $myObjects[$id] = $project->name;
            }
            elseif($project->status != 'done' && $project->status != 'closed' && !($project->PM == $this->app->user->account))
            {
                $normalObjects[$id] = $project->name;
            }
            elseif($project->status == 'done' || $project->status == 'closed')
            {
                $closedObjects[$id] = $project->name;
            }
        }
        return array($myObjects, $normalObjects, $closedObjects);
    }

    /**
     * 统计文档库下的模块和文档数量。
     * Stat module and document counts of lib.
     *
     * @param  array $idList
     * @access public
     * @return array
     */
    public function statLibCounts(array $idList): array
    {
        if(empty($idList)) return array();

        $moduleCounts = $this->dao->select("`root`, count(id) as moduleCount")->from(TABLE_MODULE)
            ->where('type')->eq('doc')
            ->andWhere('root')->in($idList)
            ->andWhere('deleted')->eq(0)
            ->groupBy('root')
            ->fetchPairs();

        $docs = $this->dao->select("`id`,`addedBy`,`type`,`lib`,`acl`,`users`,`groups`,`status`")->from(TABLE_DOC)
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
            $docCount           = isset($docCounts[$libID]) ? $docCounts[$libID] : 0;
            $moduleCount        = isset($moduleCounts[$libID]) ? $moduleCounts[$libID] : 0;
            $itemCounts[$libID] = $docCount + $moduleCount;
        }

        return $itemCounts;
    }

    /**
     * 获取文档库的附件。
     * Get lib files.
     *
     * @param  string      $type        product|project|execution
     * @param  int         $objectID
     * @param  string|bool $searchTitle
     * @param  string      $browseType
     * @param  int         $param
     * @param  string      $orderBy
     * @param  object      $pager
     * @access public
     * @return array
     */
    public function getLibFiles(string $type, int $objectID, string $browseType = '', int $param = 0, string $orderBy = 'id_desc', object $pager = null): array
    {
        if(!in_array($type, array('execution', 'project', 'product'))) return array();

        $searchModule = "{$type}DocFile";
        $queryID      = (int)$param;
        $this->loadModel('search')->setQuery($searchModule, $queryID);
        $docFileQuery = $this->session->{$searchModule . 'Query'};

        list($bugIdList, $testReportIdList, $caseIdList, $docIdList, $storyIdList, $planIdList, $releaseIdList, $issueIdList, $meetingIdList, $reviewIdList, $designIdList, $executionIdList, $taskIdList, $buildIdList, $testtaskIdList) = $this->getLinkedObjectData($type, $objectID);

        $files = $this->dao->select('*')->from(TABLE_FILE)
            ->where('size')->gt('0')
            ->andWhere('deleted')->eq('0')
            ->andWhere("(objectType = '$type' and objectID = $objectID)", true)
            ->orWhere("(objectType = 'doc' and objectID in ($docIdList))")
            ->orWhere("(objectType = 'bug' and objectID in ($bugIdList))")
            ->orWhere("(objectType = 'testreport' and objectID in ($testReportIdList))")
            ->orWhere("(objectType = 'testcase' and objectID in ($caseIdList))")
            ->beginIF($type == 'product')
            ->orWhere("(objectType in ('story','requirement', 'epic') and objectID in ($storyIdList))")
            ->orWhere("(objectType = 'release' and objectID in ($releaseIdList))")
            ->orWhere("(objectType = 'productplan' and objectID in ($planIdList))")
            ->fi()
            ->beginIF($type == 'project')
            ->orWhere("(objectType = 'execution' and objectID in ($executionIdList))")
            ->orWhere("(objectType = 'issue' and objectID in ($issueIdList))")
            ->orWhere("(objectType = 'review' and objectID in ($reviewIdList))")
            ->orWhere("(objectType = 'meeting' and objectID in ($meetingIdList))")
            ->orWhere("(objectType = 'design' and objectID in ($designIdList))")
            ->fi()
            ->beginIF($type == 'project' || $type == 'execution')
            ->orWhere("(objectType = 'task' and objectID in ($taskIdList))")
            ->orWhere("(objectType = 'build' and objectID in ($buildIdList))")
            ->orWhere("(objectType = 'testtask' and objectID in ($testtaskIdList))")
            ->beginIF($storyIdList)->orWhere("(objectType = 'story' and objectID in ($storyIdList))")->fi()
            ->fi()
            ->markRight(1)
            ->beginIF($browseType == 'bySearch')->andWhere("($docFileQuery)")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        $this->loadModel('file');
        foreach($files as $fileID => $file) $this->file->setFileWebAndRealPaths($file);

        return $files;
    }

    /**
     * 获取关联产品/项目/执行的数据。
     * Get linked product/project/execution data.
     *
     * @param  string $type     product|project|execution
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getLinkedObjectData(string $type, int $objectID): array
    {
        if(!in_array($type, array('execution', 'project', 'product'))) return array();

        $userView = $this->app->user->view->products;
        if($type == 'project')   $userView = $this->app->user->view->projects;
        if($type == 'execution') $userView = $this->app->user->view->sprints;

        $bugIdList = $testReportIdList = $caseIdList = $testtaskIdList = $storyIdList = $planIdList = $releaseIdList = $executionIdList = $taskIdList = $buildIdList = $issueIdList = $meetingIdList = $designIdList = $reviewIdList = 0;
        $bugPairs  = $this->dao->select('id')->from(TABLE_BUG)->where($type)->eq($objectID)->andWhere('deleted')->eq('0')->beginIF(!$this->app->user->admin)->andWhere($type)->in($userView)->fi()->fetchPairs('id');
        if(!empty($bugPairs)) $bugIdList = implode(',', $bugPairs);

        $testReportPairs = $this->dao->select('id')->from(TABLE_TESTREPORT)->where($type)->eq($objectID)->andWhere('deleted')->eq('0')->beginIF(!$this->app->user->admin)->andWhere($type)->in($userView)->fi()->fetchPairs('id');
        if(!empty($testReportPairs)) $testReportIdList = implode(',', $testReportPairs);

        $field     = $type == 'execution' ? 'project' : $type;
        $casePairs = $this->dao->select('`case`')->from(TABLE_PROJECTCASE)->where($field)->eq($objectID)->beginIF(!$this->app->user->admin)->andWhere($field)->in($userView)->fi()->fetchPairs('case');
        if(!empty($casePairs)) $caseIdList = implode(',', $casePairs);

        $docs = $this->dao->select('*')->from(TABLE_DOC)->where($type)->eq($objectID)->fetchAll('id');
        foreach($docs as $id => $doc)
        {
            if(!$this->checkPrivDoc($doc)) unset($docs[$id]);
        }
        $docIdList = empty($docs) ? 0 : $this->dao->select('id')->from(TABLE_DOC)->where($type)->eq($objectID)->andWhere('vision')->eq($this->config->vision)->andWhere('id')->in(array_keys($docs))->get();

        if($type == 'product')
        {
            list($storyIdList, $planIdList, $releasePairs, $casePairs) = $this->docTao->getLinkedProductData($objectID, $userView);
            if(!empty($releasePairs)) $releaseIdList = implode(',', $releasePairs);
            if(!empty($casePairs))    $caseIdList    = implode(',', $casePairs);
        }
        elseif($type == 'project')
        {
            list($storyIdList, $issueIdList, $meetingIdList, $reviewIdList, $designIdList, $executionIdList, $taskIdList, $buildIdList) = $this->getLinkedProjectData($objectID);
        }
        elseif($type == 'execution')
        {
            list($storyIdList, $taskIdList, $buildIdList, $testtaskIdList) = $this->getLinkedExecutionData($objectID);
        }

        return array($bugIdList, $testReportIdList, $caseIdList, $docIdList, $storyIdList, $planIdList, $releaseIdList, $issueIdList, $meetingIdList, $reviewIdList, $designIdList, $executionIdList, $taskIdList, $buildIdList, $testtaskIdList);
    }

    /**
     * 获取关联项目的数据。
     * Get linked project data.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getLinkedProjectData(int $projectID): array
    {
        $project     = $this->loadModel('project')->getByID($projectID);
        $storyIdList = $issueIdList = $meetingIdList = $reviewIdList = $designIdList = $executionIdList = $taskIdList = $buildIdList = 0;
        if($project && !$project->hasProduct)
        {
            $projectIDList = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID)->orWhere('project')->eq($projectID)->fetchPairs('id', 'id');
            $storyIdList   = $this->dao->select('story')->from(TABLE_PROJECTSTORY)->where('project')->in($projectIDList)->fetchPairs('story', 'story');
        }

        if(in_array($this->config->edition, array('max', 'ipd')))
        {
            $issueIdList = $this->dao->select('id')->from(TABLE_ISSUE)
                ->where('project')->eq($projectID)
                ->andWhere('deleted')->eq('0')
                ->beginIF(!$this->app->user->admin)->andWhere('project')->in($this->app->user->view->projects)->fi()
                ->get();
            $meetingIdList = $this->dao->select('id')->from(TABLE_MEETING)
                ->where('project')->eq($projectID)
                ->andWhere('deleted')->eq('0')
                ->beginIF(!$this->app->user->admin)->andWhere('project')->in($this->app->user->view->projects)->fi()
                ->get();
            $reviewIdList = $this->dao->select('id')->from(TABLE_REVIEW)
                ->where('project')->eq($projectID)
                ->andWhere('deleted')->eq('0')
                ->beginIF(!$this->app->user->admin)->andWhere('project')->in($this->app->user->view->projects)->fi()
                ->get();
        }

        $designIdList = $this->dao->select('id')->from(TABLE_DESIGN)
            ->where('project')->eq($projectID)
            ->andWhere('deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('project')->in($this->app->user->view->projects)->fi()
            ->get();

        $executionIdList = $this->loadModel('execution')->getIdList($projectID);
        $taskPairs       = $this->dao->select('id')->from(TABLE_TASK)
            ->where('execution')->in($executionIdList)
            ->andWhere('deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('execution')->in($this->app->user->view->sprints)->fi()
            ->fetchPairs('id');
        if(!empty($taskPairs)) $taskIdList = implode(',', $taskPairs);

        $buildPairs = $this->dao->select('id')->from(TABLE_BUILD)
            ->where('execution')->in($executionIdList)
            ->andWhere('deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('execution')->in($this->app->user->view->sprints)->fi()
            ->fetchPairs('id');
        if(!empty($buildPairs)) $buildIdList = implode(',', $buildPairs);

        $executionIdList = $executionIdList ? implode(',', $executionIdList) : 0;
        $storyIdList     = $storyIdList ? implode(',', $storyIdList) : 0;
        return array($storyIdList, $issueIdList, $meetingIdList, $reviewIdList, $designIdList, $executionIdList, $taskIdList, $buildIdList);
    }

    /**
     * 获取关联执行的数据。
     * Get linked execution data.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getLinkedExecutionData(int $executionID): array
    {
        $storyIdList = $taskIdList = $buildIdList = $testtaskIdList = 0;
        $execution   = $this->loadModel('execution')->getByID($executionID);
        $project     = $execution ? $this->loadModel('project')->getByID((int)$execution->project) : '';

        if($project && !$project->hasProduct) $storyIdList = $this->dao->select('story')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->fetchPairs('story', 'story');
        $storyIdList = $storyIdList ? implode(',', $storyIdList) : '';

        $taskPairs = $this->dao->select('id')->from(TABLE_TASK)
            ->where('execution')->eq($executionID)
            ->andWhere('deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('execution')->in($this->app->user->view->sprints)->fi()
            ->fetchPairs('id');
        if(!empty($taskPairs)) $taskIdList = implode(',', $taskPairs);

        $buildPairs = $this->dao->select('id')->from(TABLE_BUILD)
            ->where('execution')->eq($executionID)
            ->andWhere('deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('execution')->in($this->app->user->view->sprints)->fi()
            ->fetchPairs('id');
        if(!empty($buildPairs)) $buildIdList = implode(',', $buildPairs);

        $testtaskPairs = $this->dao->select('id')->from(TABLE_TESTTASK)
            ->where('execution')->eq($executionID)
            ->andWhere('deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('execution')->in($this->app->user->view->sprints)->fi()
            ->fetchPairs('id');
        if(!empty($testtaskPairs)) $testtaskIdList = implode(',', $testtaskPairs);

        return array($storyIdList, $taskIdList, $buildIdList, $testtaskIdList);
    }

    /**
     * 获取附件的来源。
     * Get file source pairs.
     *
     * @param  array  $files
     * @access public
     * @return array
     */
    public function getFileSourcePairs(array $files): array
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
            if(empty($table) || empty($field)) continue;

            $sourcePairs[$type] = $this->dao->select('id,' . $field)->from($table)->where('id')->in($idList)->fetchPairs('id', $field);
        }

        return $sourcePairs;
    }

    /**
     * 获取文件图标。
     * Get file icon.
     *
     * @param  array  $files
     * @access public
     * @return array
     */
    public function getFileIcon(array $files): array
    {
        $this->app->loadConfig('file');

        $fileIcon = array();
        foreach($files as $file)
        {
            if(in_array($file->extension, $this->config->file->imageExtensions)) continue;

            switch($file->extension)
            {
                case in_array($file->extension, array('zip', 'tar', 'gz', 'bz2', 'rar')):
                    $iconClass = 'icon-file-archive';
                    break;
                case in_array($file->extension, array('csv', 'xls', 'xlsx')):
                    $iconClass = 'icon-file-excel';
                    break;
                case in_array($file->extension, array('doc', 'docx')):
                    $iconClass = 'icon-file-word';
                    break;
                case in_array($file->extension, array('ppt', 'pptx')):
                    $iconClass = 'icon-file-powerpoint';
                    break;
                case in_array($file->extension, array('pdf')):
                    $iconClass = 'icon-file-pdf';
                    break;
                case in_array($file->extension, array('mp3', 'ogg', 'wav')):
                    $iconClass = 'icon-file-audio';
                    break;
                case in_array($file->extension, array('avi', 'mp4', 'mov')):
                    $iconClass = 'icon-file-video';
                    break;
                case in_array($file->extension, array('txt', 'md')):
                    $iconClass = 'icon-file-text';
                    break;
                case in_array($file->extension, array('html', 'htm')):
                    $iconClass = 'icon-file-globe';
                    break;
                default:
                    $iconClass = 'icon-file';
                    break;
            }

            $fileIcon[$file->id] = "<i class='file-icon icon $iconClass'></i>";
        }

        return $fileIcon;
    }

    /**
     * 获取文档的树形结构。
     * Get doc tree.
     *
     * @param  int    $libID
     * @access public
     * @return array
     */
    public function getDocTree(int $libID): array
    {
        $docTrees = $this->loadModel('tree')->getTreeStructure($libID, 'doc');
        array_unshift($docTrees, array('id' => 0, 'name' => '/', 'type' => 'doc', 'actions' => false, 'root' => $libID));
        foreach($docTrees as $i => $tree)
        {
            $tree          = (object)$tree;
            $docTrees[$i] = $this->buildDocNode($tree, $libID);
        }
        if(empty($docTrees[0]->children)) array_shift($docTrees);
        return $docTrees;
    }

    /**
     * 构造文档节点。
     * Build doc node.
     *
     * @param  object $node
     * @param  int    $libID
     * @access public
     * @return object
     */
    public function buildDocNode(object $node, int $libID): object
    {
        $node = (object)$node;
        static $docGroups;
        if(empty($docGroups))
        {
            $docs      = $this->dao->select('*')->from(TABLE_DOC)->where('lib')->eq($libID)->andWhere('deleted')->eq(0)->fetchAll();
            $docGroups = array();
            foreach($docs as $doc)
            {
                if($this->checkPrivDoc($doc)) $docGroups[$doc->module][$doc->id] = $doc;
            }
        }

        if(!empty($node->children)) foreach($node->children as $i => $child) $node->children[$i] = $this->buildDocNode($child, $libID);
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
                if(common::hasPriv('doc', 'edit'))   $buttons .= html::a(helper::createLink('doc', 'edit',   "docID={$doc->id}"), "<i class='icon icon-edit'></i>", 'hiddenwin', "class='btn-icon' title='{$this->lang->doc->edit}'");
                if(common::hasPriv('doc', 'delete')) $buttons .= html::a(helper::createLink('doc', 'delete', "docID={$doc->id}"), "<i class='icon icon-remove'></i>", 'hiddenwin', "class='btn-icon' title='{$this->lang->doc->delete}'");
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
     * 获取文档的统计信息。
     * Get statistic information.
     *
     * @access public
     * @return object
     */
    public function getStatisticInfo(): object
    {
        $today     = date('Y-m-d');
        $statistic = new stdclass();
        $statistic->totalDocs = $this->dao->select('COUNT(1) AS count')->from(TABLE_DOC)
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

        $myStatistic = $this->dao->select("COUNT(1) AS myDocs, SUM(views) as docViews, SUM(collects) as docCollects")->from(TABLE_DOC)
            ->where('addedBy')->eq($this->app->user->account)
            ->andWhere('type')->in($this->config->doc->docTypes)
            ->andWhere('deleted')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('lib')->ne('')
            ->fetch();

        $statistic->myDocs = $myStatistic->myDocs;
        $statistic->myDoc  = new stdclass();
        $statistic->myDoc->docViews    = $myStatistic->docViews;
        $statistic->myDoc->docCollects = $myStatistic->docCollects;
        return $statistic;
    }

    /**
     * 获取收信人和抄送人列表。
     * Get toList and ccList.
     *
     * @param  object     $doc
     * @access public
     * @return bool|array
     */
    public function getToAndCcList(object $doc): bool|array
    {
        /* Set toList and ccList. */
        $toList = '';
        $ccList = str_replace(' ', '', trim($doc->mailto, ','));

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
        return array($toList, $ccList);
    }

    /**
     * 获取下拉菜单的链接。
     * Get the dropmenu link.
     *
     * @param  string $type     product|project
     * @param  int    $objectID
     * @access public
     * @return string
     */
    public function getDropMenuLink(string $type, int $objectID): string
    {
        if(!in_array($type, array('product', 'project'))) return '';

        $currentModule = $this->app->rawModule;
        $currentMethod = $this->app->rawMethod;
        if($currentModule == 'api' && $currentMethod == 'index')
        {
            $currentModule = 'doc';
            $currentMethod = $type . 'Space';
        }

        return helper::createLink('doc', 'ajaxGetDropMenu', "objectType={$type}&objectID={$objectID}&module={$currentModule}&method={$currentMethod}");
    }

    /**
     * 统计当前页面上文件的数量和大小。
     * Count the number and size of files on the current page.
     *
     * @param  array  $files
     * @access public
     * @return string
     */
    public function summary(array $files): string
    {
        $filesCount       = 0;
        $sizeCount        = 0;
        $extensionCount   = array();
        $extensionSummary = '';
        foreach($files as $file)
        {
            if(!isset($extensionCount[$file->extension])) $extensionCount[$file->extension] = 0;

            $sizeCount += $file->size;

            $filesCount++;
            $extensionCount[$file->extension] ++;
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
     * @param  string       $type     mine|project|execution|product|custom
     * @param  int          $objectID
     * @param  int          $libID
     * @param  int          $appendLib
     * @access public
     * @return array|string
     */
    public function setMenuByType(string $type, int $objectID, int $libID, int $appendLib = 0): array|string
    {
        if(empty($type))
        {
            $doclib   = $this->getLibByID($libID);
            $type     = $doclib->type == 'execution' ? 'project' : $doclib->type;
            $objectID = isset($doclib->{$type}) ? $doclib->{$type} : 0;
        }
        $isExecution    = in_array($this->app->tab, array('project', 'doc')) && $type == 'execution';
        $type           = $isExecution ? 'project' : $type;
        $objectDropdown = array('text' => '', 'link' => '');
        $appendObject   = $objectID;
        if(in_array($type, array('project', 'product', 'execution')))
        {
            $object = $this->dao->select('id,name,status,deleted' . ($isExecution ? ',project' : ''))->from($this->config->objectTables[$type])->where('id')->eq($objectID)->fetch();
            $objectID = $isExecution ? (int)$object->project : $objectID;
            if(empty($object)) return helper::createLink($type, $type == 'project' && $this->config->vision != 'lite' ? 'createGuide' : 'create', $type == 'project' && $this->config->vision == 'lite' ? 'model=kanban' : '');

            $this->loadModel($type);
            $objects  = $this->getOrderedObjects($type, 'merge', $objectID);
            $objectID = method_exists($this->$type, 'saveState') ? $this->{$type}->saveState($objectID, $objects) : $this->{$type}->checkAccess($objectID, $objects);
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
            if($type == 'custom' || $type == 'doctemplate')
            {
                $spaces = $type == 'custom' ? $this->getTeamSpaces() : $this->getDocTemplateSpaces();

                if(empty($objectID)) $objectID = (int)key($spaces);
                if(!isset($spaces[$objectID])) $objectID = (int)key($spaces);
            }

            $libs = $this->getLibsByObject($type, $objectID, $appendLib);
            if(($libID == 0 || !isset($libs[$libID])) && !empty($libs)) $libID = reset($libs)->id;
            if(isset($libs[$libID])) $objectDropdown['text'] = zget($libs[$libID], 'name', '');
            if($type == 'custom' || $type == 'doctemplate')
            {
                $spaceExtra = $type == 'custom' ? 'nomine' : 'doctemplate';
                $objectDropdown['text'] = zget($spaces, $objectID, '');
                $objectDropdown['link'] = helper::createLink('doc', 'ajaxGetSpaceMenu', "libID={$objectID}&module={$this->app->rawModule}&method={$this->app->rawMethod}&extra={$spaceExtra}");
            }

            $object = $this->dao->select('id,name,deleted')->from(TABLE_DOCLIB)->where('id')->eq($objectID)->fetch();
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

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'doc', true);

        return $this->processCollector($docs);
    }

    /**
     * 获取给定文档库的目录列表。
     * Get modules from libs.
     *
     * @param  array $libs  Lib id list.
     * @param  string $type doc|api
     * @access public
     * @return array
     */
    public function getModulesOfLibs(array $libs, $type = 'doc,api')
    {
        $types = explode(',', $type);
        return $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->in($libs)
            ->beginIF(count($types) > 1)->andWhere('type')->in($types)->fi()
            ->beginIF(count($types) == 1)->andWhere('type')->eq($type)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy('grade desc, `order`')
            ->fetchAll('id');
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
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getLibTree();

        $type = $this->app->tab == 'doc' && $type == 'execution' ? 'project' : $type;
        list($libTree, $apiLibs, $apiLibIDList) = $this->getObjectTree($libID, $libs, $type, $moduleID, $objectID, strtolower($browseType), $param, $docID);
        $libTree = $this->processObjectTree($libTree, $type, $libID, $objectID, $apiLibs, $apiLibIDList);

        if($type != 'project') $libTree = array_values($libTree[$type]);
        if($type == 'mine')
        {
            $children    = $libTree;
            $libType     = isset($this->app->rawParams['type']) ? $this->app->rawParams['type'] : '';
            $libType     = $this->app->rawMethod == 'view' ? 'mine' : $libType;
            $mineMethods = array('mine' => 'myLib', 'view' => 'myView', 'collect' => 'myCollection', 'createdBy' => 'myCreation', 'editedBy' => 'myEdited');
            $libTree     = array();
            foreach($mineMethods as $type => $mineMethod)
            {
                if($mineMethod != 'myLib' && !common::hasPriv('doc', $mineMethod)) continue;

                $myItem = new stdclass();
                $myItem->id         = 0;
                $myItem->name       = $type == 'mine' ? '' : $this->lang->doc->{$mineMethod};
                $myItem->type       = $type;
                $myItem->objectType = 'doc';
                $myItem->objectID   = 0;
                $myItem->hasAction  = false;
                $myItem->active     = strtolower($libType) == strtolower($type) ? 1 : 0;
                if($type == 'mine') $myItem->children = $children;

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
                    $execution->id         = $executionID;
                    $execution->name       = zget($executionPairs, $executionID);
                    $execution->type       = 'execution';
                    $execution->active     = $item->active;
                    $execution->hasAction  = false;
                    $execution->objectType = 'execution';
                    $execution->children   = array();
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
     * @param  int              $libID
     * @param  object           $lib
     * @param  string           $type       mine|product|project|execution|api|custom
     * @param  int              $moduleID
     * @param  int              $objectID
     * @param  string           $browseType bysearch|byrelease
     * @param  int              $docID
     * @param  object|null|bool $release
     * @access public
     * @return object
     */
    public function buildLibItem(int $libID, object $lib, string $type, int $moduleID = 0, int $objectID = 0, string $browseType = '', int $docID = 0, object|null|bool $release = null): object
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
        $item->parent     = isset($lib->parent) ? $lib->parent : '';
        $item->order      = $lib->order;
        $item->main       = zget($lib, 'main', 0);
        $item->objectType = $type;
        $item->objectID   = $objectID;
        $item->addedBy    = $lib->addedBy;
        $item->active     = $lib->id == $libID && $browseType != 'bysearch' ? 1 : 0;
        $item->children   = $this->getModuleTree($lib->id, $moduleID, $lib->type == 'api' ? 'api' : 'doc', 0, $releaseModule, $docID);

        $showDoc = $this->loadModel('setting')->getItem('owner=' . $this->app->user->account . '&module=doc&key=showDoc');
        $showDoc = $showDoc === '0' ? 0 : 1;
        $showDocWithNotAPI = $showDoc && $lib->type != 'api';
        $isSpace = $lib->type == 'custom' && $lib->parent == 0;
        if($this->app->rawMethod == 'view' && $showDocWithNotAPI && !$isSpace)
        {
            $docIDList = $this->getPrivDocs(array($lib->id));
            $docs      = $this->dao->select('*, title as name')->from(TABLE_DOC)
                ->where('id')->in($docIDList)
                ->andWhere("(status = 'normal' or (status = 'draft' && addedBy='{$this->app->user->account}'))")
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
            $annex->id         = 'annex';
            $annex->name       = $this->lang->doclib->files;
            $annex->type       = 'annex';
            $annex->objectType = $type;
            $annex->objectID   = $objectID;
            $annex->active     = empty($libID) ? 1 : 0;
            if($type == 'project')
            {
                $newLibTree = array();
                foreach($libTree as $key => $libs)
                {
                    $newLibTree[$key] = $libs;
                    if($key == 'project') $newLibTree['annex'][''] = $annex;
                }

                $libTree = $newLibTree;
            }
            else
            {
                $libTree[$type][''] = $annex;
            }
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
     * 检查接口库名称。
     * Check api library name.
     *
     * @param  object $lib
     * @param  string $libType product|project
     * @param  int    $libID
     * @access public
     * @return bool
     */
    public function checkApiLibName($lib, $libType, $libID = 0): bool
    {
        $sameNames = $this->dao->select('*')
            ->from(TABLE_DOCLIB)
            ->where('`product`')->eq($lib->product)
            ->andWhere('`project`')->eq($lib->project)
            ->andWhere('`name`')->eq($lib->name)
            ->andWhere('`type`')->eq('api')
            ->beginIF(!empty($libID))->andWhere('`id`')->ne($libID)->fi()
            ->fetchAll();

        if(count($sameNames) > 0 && $libType == 'product') dao::$errors['name'] = $this->lang->doclib->apiNameUnique[$libType] . sprintf($this->lang->error->unique, $this->lang->doclib->name, $lib->name);
        if(count($sameNames) > 0 && $libType == 'project') dao::$errors['name'] = $this->lang->doclib->apiNameUnique[$libType] . sprintf($this->lang->error->unique, $this->lang->doclib->name, $lib->name);
        if(count($sameNames) > 0 && $libType == 'nolink')  dao::$errors['name'] = $this->lang->doclib->apiNameUnique[$libType] . sprintf($this->lang->error->unique, $this->lang->doclib->name, $lib->name);

        return !dao::isError();
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
            $collectCount = $this->dao->select('COUNT(1) AS count')->from(TABLE_DOCACTION)->where('doc')->eq($docID)->andWhere('action')->eq('collect')->fetch('count');
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
            $collectCount = $this->dao->select('COUNT(1) AS count')->from(TABLE_DOCACTION)->where('doc')->eq($action->doc)->andWhere('action')->eq('collect')->fetch('count');
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
    public function processCollector(array $docs): array
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
     * 检查文档是否正在被其他人编辑。
     * Check other editing.
     *
     * @param  int    $docID
     * @access public
     * @return bool
     */
    public function checkOtherEditing(int $docID): bool
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
            if($editingAccount != $account && ($now - $timestamp) <= $this->config->doc->saveDraftInterval)
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
     * 获取文档动态。
     * Get document dynamic.
     *
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getDynamic(object $pager = null): array
    {
        $allLibs          = $this->getLibs('hasApi');
        $hasPrivDocIdList = $this->getPrivDocs(array(), 0, 'all');
        $apiList          = $this->loadModel('api')->getPrivApis();
        $actionCondition  = $this->loadModel('action')->getActionCondition('doc');

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
            ->beginIF($actionCondition)->andWhere("($actionCondition)")->fi()
            ->orderBy('date_desc,id_asc')
            ->page($pager)
            ->fetchAll();

        return $this->loadModel('action')->transformActions($actions);
    }

    /**
     * 将当前用户从文档的正在编辑者列表中移除。
     * Removes the current user from the list of people editing the document.
     *
     * @param  object|bool $doc
     * @access public
     * @return bool
     */
    public function removeEditing(object|bool $doc): bool
    {
        if(empty($doc->id) || empty($doc->editingDate)) return false;

        $account     = $this->app->user->account;
        $editingDate = json_decode($doc->editingDate, true);
        if(!isset($editingDate[$account])) return false;

        unset($editingDate[$account]);
        $this->dao->update(TABLE_DOC)->set('editingDate')->eq(json_encode($editingDate))->where('id')->eq($doc->id)->exec();

        return !dao::isError();
    }

    /**
     * 获取编辑过文档的用户列表。
     * Get editors of a document.
     *
     * @param  int    $docID
     * @access public
     * @return array
     */
    public function getEditors(int $docID = 0): array
    {
        if(!$docID) return array();
        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq('doc')
            ->andWhere('objectID')->eq($docID)
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
            $this->config->doc->search['params']['project']['values']   = $this->loadModel('project')->getPairsByProgram(0, 'all', false, 'order_asc') + array('all' => $this->lang->doc->allProjects);
            $this->config->doc->search['params']['execution']['values'] = $this->loadModel('execution')->getPairs(0, 'all', 'multiple,leaf,noprefix,withobject') + array('all' => $this->lang->doc->allExecutions);
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
                $this->config->doc->search['params']['execution']['values'] = $this->loadModel('execution')->getPairs((int)$this->session->project, 'all', 'multiple,leaf,noprefix') + array('all' => $this->lang->doc->allExecutions);
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

    /**
     * 通过ID获取产品/项目/执行的信息。
     * Get product/project/execution by ID.
     *
     * @param  string       $type     product|project|execution
     * @param  int          $objectID
     * @access public
     * @return object|false
     */
    public function getObjectByID(string $type, int $objectID): object|bool
    {
        $table = zget($this->config->objectTables, $type, '');
        if(!$table) return false;

        return $this->dao->select('*')->from($table)->where('id')->eq($objectID)->fetch();
    }

    /**
     * 更新文档库顺序。
     * Update doclib order.
     *
     * @param  int    $catalogID
     * @param  int    $order
     * @access public
     * @return bool
     */
    public function updateDoclibOrder(int $id, int $order): bool
    {
        $this->dao->update(TABLE_DOCLIB)->set('`order`')->eq($order)->where('id')->eq($id)->exec();

        return !dao::isError();
    }

    /**
     * 更新文档顺序。
     * Update doc order.
     *
     * @param  array $sortedIdList
     * @access public
     * @return void
     */
    public function updateDocOrder(array $sortedIdList): void
    {
        /* Remove programID. */
        $sortedIdList = array_values(array_filter(array_map(function($id){return (is_numeric($id) and $id > 0) ? $id : null;}, $sortedIdList)));
        if(empty($sortedIdList)) return;

        $docs = $this->dao->select('`order`, id')->from(TABLE_DOC)->where('id')->in($sortedIdList)->orderBy('order_asc')->fetchPairs('order', 'id');

        /* Update order by sorted id list. */
        foreach($docs as $order => $id)
        {
            $newID = array_shift($sortedIdList);
            if($id == $newID) continue;
            $this->dao->update(TABLE_DOC)->set('`order`')->eq($order)->where('id')->eq($newID)->exec();
        }
    }

    /**
     * 更新目录顺序。
     * Update catalog order.
     *
     * @param  int    $catalogID
     * @param  int    $order
     * @param  string $order
     * @access public
     * @return bool
     */
    public function updateOrder(int $catalogID, int $order, string $type = 'doc'): bool
    {
        $this->dao->update(TABLE_MODULE)->set('`order`')->eq($order)->where('id')->eq($catalogID)->andWhere('type')->eq($type)->exec();

        return !dao::isError();
    }

    /**
     * 更新文档中的附件信息。
     * Update doc file.
     *
     * @param  int    $docID
     * @param  int    $fileID
     * @access public
     * @return bool
     */
    public function updateDocFile(int $docID, int $fileID): bool
    {
        $docContent = $this->dao->select('t1.*')->from(TABLE_DOCCONTENT)->alias('t1')
            ->leftJoin(TABLE_DOC)->alias('t2')->on('t1.doc=t2.id and t1.version=t2.version')
            ->where('t2.id')->eq($docID)
            ->fetch();

        unset($docContent->id);
        $docContent->files    = trim(str_replace(",{$fileID},", ',', ",{$docContent->files},"), ',');
        $docContent->version += 1;
        $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
        $this->dao->update(TABLE_DOC)->set('version')->eq($docContent->version)->where('id')->eq($docID)->exec();

        return !dao::isError();
    }

    /**
     * 移动文档库。
     * Move library.
     *
     * @param  int    $docID
     * @param  object $data
     * @access public
     * @return bool
     */
    public function moveLib(int $libID, object $data): bool
    {
        if(empty($libID) || empty($data->space)) return false;
        $lib = $this->getLibByID($libID);

        $spaceType = $this->getParamFromTargetSpace($data->space, 'type');
        $spaceID   = $this->getParamFromTargetSpace($data->space, 'id');
        if(is_numeric($spaceID))
        {
            $data->type = $spaceType;
            if($spaceType == 'mine') $data->addedBy = $this->app->user->account;

            /* 先清空所有标志性字段，如果是项目空间、执行空间、产品空间，则直接改对应字段，否则就是我的空间和团队空间 */
            if($lib->product)   $data->product   = 0;
            if($lib->project)   $data->project   = 0;
            if($lib->execution) $data->execution = 0;
            if($lib->parent)    $data->parent    = 0;
            if(in_array($spaceType, array('project', 'product', 'execution')))
            {
                $data->$spaceType = $spaceID;
            }
            else
            {
                $data->parent = $spaceID;
            }
        }
        else
        {
            return false;
        }

        $changes = common::createChanges($lib, $data);
        if(empty($changes)) return false;

        unset($data->space);
        $this->dao->update(TABLE_DOCLIB)->data($data)->where('id')->eq($libID)->exec();

        $getFromTo = function ($obj)
        {
            if(!in_array($obj->type, array('project', 'product'))) return "{$obj->type}.{$obj->parent}";
            if(isset($obj->project)) return "{$obj->type}.{$obj->project}";
            return "{$obj->type}.{$obj->product}";
        };

        $from = $getFromTo($lib);
        $to   = $getFromTo($data);

        $actionID = $this->loadModel('action')->create('docLib', $libID, 'Moved', '', json_encode(array('from' => $from, 'to' => $to)));
        $this->action->logHistory($actionID, $changes);

        return true;
    }

    /**
     * Batch move document.
     *
     * @param  object $data
     * @param  array  $docIdList
     * @access public
     * @return bool
     */
    public function batchMoveDoc(object $data, array $docIdList): bool
    {
        $this->dao->update(TABLE_DOC)->data($data)->where('id')->in($docIdList)->exec();

        return !dao::isError();
    }

    /**
     * 判断文档库下是否有其他人创建的文档。
     * Check if there are other documents created under the document library.
     *
     * @param  object $lib
     * @access public
     * @return bool
     */
    public function hasOthersDoc(object $lib): bool
    {
        if($lib->type != 'custom') return false;

        $docID = $this->dao->select('id')->from(TABLE_DOC)
            ->where('vision')->eq($this->config->vision)
            ->andWhere('templateType')->eq('')
            ->andWhere('deleted')->eq(0)
            ->andWhere('lib')->eq($lib->id)
            ->andWhere('addedBy')->ne($this->app->user->account)
            ->fetch('id');

        return !empty($docID);
    }
}
