<?php
declare(strict_types=1);
/**
 * The zen file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
class docZen extends doc
{
    /**
     * Process file field for table.
     *
     * @param  array     $files
     * @param  array     $fileIcon
     * @param  array     $sourcePairs
     * @param  bool      $skipImageWidth
     * @access protected
     * @return array
     */
    protected function processFiles(array $files, array $fileIcon, array $sourcePairs, bool $skipImageWidth = false): array
    {
        if(!$skipImageWidth) $this->loadModel('file');

        foreach($files as $fileID => $file)
        {
            if(empty($file->pathname))
            {
                unset($files[$fileID]);
                continue;
            }

            $file->fileIcon   = isset($fileIcon[$file->id]) ? $fileIcon[$file->id] : '';
            $file->fileName   = str_replace('.' . $file->extension, '', $file->title);
            $file->sourceName = isset($sourcePairs[$file->objectType][$file->objectID]) ? $sourcePairs[$file->objectType][$file->objectID] : '';
            $file->sizeText   = number_format($file->size / 1024, 1) . 'K';

            if(!$skipImageWidth)
            {
                $imageSize = $this->file->getImageSize($file);
                $file->imageWidth = isset($imageSize[0]) ? $imageSize[0] : 0;
            }

            if($file->objectType == 'requirement')
            {
                $file->objectName = $this->lang->URCommon . ' : ';
            }
            else
            {
                if(!isset($this->lang->{$file->objectType}->common)) $this->app->loadLang($file->objectType);
                $file->objectName = $this->lang->{$file->objectType}->common . ' : ';
            }
        }
        return $files;
    }

    /**
     * 构造大纲的数据。
     * Build the data of outline.
     *
     * @param  int       $topLevel
     * @param  array     $content
     * @param  array     $includeHeadElement
     * @access protected
     * @return array
     */
    protected function buildOutlineList(int $topLevel, array $content, array $includeHeadElement): array
    {
        $preLevel     = 0;
        $preIndex     = 0;
        $parentID     = 0;
        $currentLevel = 0;
        $outlineList  = array();
        foreach($content as $index => $element)
        {
            preg_match('/<(h[1-6])([\S\s]*?)>([\S\s]*?)<\/\1>/', $element, $headElement);

            /* The current element is existed, the element is in the includeHeadElement, and the text in the element is not null. */
            if(isset($headElement[1]) && in_array($headElement[1], $includeHeadElement) && strip_tags($headElement[3]) != '')
            {
                $currentLevel = (int)ltrim($headElement[1], 'h');

                $item = array();
                $item['id']         = $index;
                $item['title']      = array('html' => strip_tags($headElement[3]));
                $item['hint']       = strip_tags($headElement[3]);
                $item['url']        = '#anchor' . $index;
                $item['level']      = $currentLevel;
                $item['data-level'] = $item['level'];
                $item['data-index'] = $index;

                if($currentLevel == $topLevel)
                {
                    $parentID = -1;
                }
                elseif($currentLevel > $preLevel)
                {
                    $parentID = $preIndex;
                }
                elseif($currentLevel < $preLevel)
                {
                    $parentID = $this->getOutlineParentID($outlineList, $currentLevel);
                }

                $item['parent'] = $parentID;

                $preIndex = $index;
                $preLevel = $currentLevel;
                $outlineList[$index] = $item;
            }
        }
        return $outlineList;
    }

    /**
     * 获取大纲的父级ID。
     * Get the parent ID of the outline.
     *
     * @param  array     $outlineList
     * @param  int       $currentLevel
     * @access protected
     * @return int
     */
    protected function getOutlineParentID(array $outlineList, int $currentLevel): int
    {
        $parentID    = 0;
        $outlineList = array_reverse($outlineList, true);
        foreach($outlineList as $index => $item)
        {
            if($item['level'] < $currentLevel)
            {
                $parentID = $index;
                break;
            }
        }
        return $parentID;
    }

    /**
     * 构造大纲的树形结构。
     * Build the tree structure of the outline.
     *
     * @param  array     $outlineList
     * @param  int       $parentID
     * @access protected
     * @return array
     */
    protected function buildOutlineTree(array $outlineList, int $parentID = -1): array
    {
        $outlineTree = array();
        foreach($outlineList as $index => $item)
        {
            if($item['parent'] != $parentID) continue;

            unset($outlineList[$index]);

            $items = $this->buildOutlineTree($outlineList, $index);
            if(!empty($items)) $item['items'] = $items;

            $outlineTree[] = $item;
        }

        return $outlineTree;
    }

    /**
     * 展示我的空间相关变量。
     * Show my space related variables.
     *
     * @param  string    $type
     * @param  int       $objectID
     * @param  int       $libID
     * @param  int       $moduleID
     * @param  string    $browseType
     * @param  int       $param
     * @param  string    $orderBy
     * @param  array     $docs
     * @param  object    $pager
     * @param  array     $libs
     * @param  string    $objectTitle
     * @access protected
     * @return void
     */
    protected function assignVarsForMySpace(string $type, int $objectID, int $libID, int $moduleID, string $browseType, int $param, string $orderBy, array $docs, object $pager, array $libs, string $objectTitle): void
    {
        $this->view->title          = $this->lang->doc->common;
        $this->view->type           = $type;
        $this->view->libID          = $libID;
        $this->view->moduleID       = $moduleID;
        $this->view->browseType     = $browseType;
        $this->view->param          = $param;
        $this->view->orderBy        = $orderBy;
        $this->view->docs           = $docs;
        $this->view->pager          = $pager;
        $this->view->objectTitle    = $objectTitle;
        $this->view->objectID       = 0;
        $this->view->canUpdateOrder = common::hasPriv('doc', 'sortDoc') && $orderBy == 'order_asc';
        $this->view->libType        = 'lib';
        $this->view->spaceType      = 'mine';
        $this->view->users          = $this->user->getPairs('noletter');
        $this->view->lib            = $this->doc->getLibByID($libID);
        $this->view->libTree        = $this->doc->getLibTree($type != 'mine' ? 0 : $libID, $libs, 'mine', $moduleID, 0, $browseType);
        $this->view->canExport      = ($this->config->edition != 'open' && common::hasPriv('doc', 'mine2export') && $type == 'mine');
        $this->view->linkParams     = "objectID={$objectID}&%s&browseType=&orderBy={$orderBy}&param=0";
    }

    /**
     * 处理创建文档库的访问控制。
     * Handle the access control of creating document library.
     *
     * @param  string    $type api|project|product|execution|custom|mine
     * @access protected
     * @return void
     */
    protected function setAclForCreateLib(string $type): void
    {
        if($type == 'custom')
        {
            unset($this->lang->doclib->aclList['default']);
        }
        elseif($type == 'mine')
        {
            $this->lang->doclib->aclList = $this->lang->doclib->mySpaceAclList;
        }
        elseif(in_array($type, array('product', 'project', 'execution')))
        {
            $this->lang->doclib->aclList['default'] = sprintf($this->lang->doclib->aclList['default'], $this->lang->{$type}->common);
            $this->lang->doclib->aclList['private'] = sprintf($this->lang->doclib->privateACL, $this->lang->{$type}->common);
            unset($this->lang->doclib->aclList['open']);
        }

        if($type != 'mine')
        {
            $this->app->loadLang('api');
            $this->lang->api->aclList['default'] = sprintf($this->lang->api->aclList['default'], $this->lang->{$type}->common);
        }
    }

    /**
     * 为创建文档库构造库数据。
     * Build library data for creating document library.
     *
     * @access protected
     * @return object
     */
    protected function buildLibForCreateLib(): object
    {
        $this->lang->doc->name = $this->lang->doclib->name;
        $lib = form::data()
            ->setDefault('addedBy', $this->app->user->account)
            ->setIF($this->post->type == 'product' && !empty($_POST['product']), 'product', $this->post->product)
            ->setIF($this->post->type == 'project' && !empty($_POST['project']), 'project', $this->post->project)
            ->setIF($this->post->libType != 'api' && !empty($_POST['execution']), 'execution', $this->post->execution)
            ->get();

        return $lib;
    }

    /**
     * 在创建文档库后的返回。
     * Return after create a document library.
     *
     * @param  string    $type     api|project|product|execution|custom|mine
     * @param  int       $objectID
     * @param  int       $libID
     * @access protected
     * @return bool|int
     */
    protected function responseAfterCreateLib(string $type = '', int $objectID = 0, int $libID = 0, string $libName = '', string $orderBy = '')
    {
        if($type == 'project'   && $this->post->project)   $objectID = $this->post->project;
        if($type == 'product'   && $this->post->product)   $objectID = $this->post->product;
        if($type == 'execution' && $this->post->execution)
        {
            if($this->post->execution != $objectID) $diffExecution = true;
            $objectID = $this->post->execution;
        }

        $type = $type == 'execution' && $this->app->tab != 'execution' ? 'project' : $type;

        $this->action->create('docLib', $libID, 'Created');

        if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $libID));
        $lib = array('id' => $libID, 'name' => $libName, 'space' => (int)$objectID, 'orderBy' => $orderBy, 'order' => $libID);

        if(isset($diffExecution) && $diffExecution === true)
        {
           return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));
        }

        $docAppActions = array();
        $docAppActions[] = array('update', 'lib', $lib);
        $docAppActions[] = array('selectSpace', $objectID, $libID);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => array('name' => 'locateNewLib', 'params' => array($type, $objectID, $libID, $libName)), 'docApp' => $docAppActions));
    }

    /**
     * 处理编辑文档库的访问控制。
     * Handle the access control of editing document library.
     *
     * @param  object    $lib
     * @access protected
     * @return void
     */
    protected function setAclForEditLib(object $lib): void
    {
        $libType = $lib->type;

        if($libType == 'custom')
        {
            unset($this->lang->doclib->aclList['default']);
        }
        elseif($libType == 'api')
        {
            $this->app->loadLang('api');
            $type = !empty($lib->product) ? 'product' : 'project';
            $this->lang->api->aclList['default'] = sprintf($this->lang->api->aclList['default'], $this->lang->{$type}->common);
        }
        elseif($libType == 'mine')
        {
            $this->lang->doclib->aclList = $this->lang->doclib->mySpaceAclList;
        }
        elseif($libType != 'custom')
        {
            $type = isset($type) ? $type : $libType;
            $this->lang->doclib->aclList['default'] = sprintf($this->lang->doclib->aclList['default'], $this->lang->{$type}->common);
            $this->lang->doclib->aclList['private'] = sprintf($this->lang->doclib->privateACL, $this->lang->{$type}->common);
            unset($this->lang->doclib->aclList['open']);
        }

        if(!empty($lib->main) && $libType != 'mine') unset($this->lang->doclib->aclList['private'], $this->lang->doclib->aclList['open']);
    }

    /**
     * 检查创建文档库的权限。
     * Check the privilege of creating document.
     *
     * @param  object    $doclib
     * @param  string    $objectType   product|project|execution|custom
     * @access protected
     * @return bool
     */
    protected function checkPrivForCreate(object $doclib, string $objectType): bool
    {
        $canVisit = true;
        if(!empty($doclib->groups)) $groupAccounts = $this->loadModel('group')->getGroupAccounts(explode(',', $doclib->groups));
        switch($objectType)
        {
            case 'custom':
                $account = (string)$this->app->user->account;
                if(($doclib->acl == 'custom' || $doclib->acl == 'private') && strpos($doclib->users, $account) === false && $doclib->addedBy !== $account && !(isset($groupAccounts) && in_array($account, $groupAccounts, true)) && !$this->app->user->admin) $canVisit = false;
                break;
            case 'product':
                $canVisit = $this->loadModel('product')->checkPriv($doclib->product);
                break;
            case 'project':
                $canVisit = $this->loadModel('project')->checkPriv($doclib->project);
                break;
            case 'execution':
                $canVisit = $this->loadModel('execution')->checkPriv($doclib->execution);
                break;
            default:
            break;
        }
        return $canVisit;
    }

    /**
     * 在创建文档后的返回。
     * Return after create a document.
     *
     * @param  array     $docResult
     * @access protected
     * @return void
     */
    protected function responseAfterCreate(array $docResult, string $objectType = 'doc')
    {
        $docID = $docResult['id'];
        $files = zget($docResult, 'files', '');

        $fileAction = '';
        if(!empty($files)) $fileAction = $this->lang->addFiles . implode(',', $files) . "\n";

        $this->action->create($objectType, $docID, 'Created', $fileAction, '', '', false);

        if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $docID));

        $response = array(
            'result'  => 'success',
            'message' => $this->lang->saveSuccess,
            'load'    => $this->createLink('doc', $objectType == 'doc' ? 'view' : 'browseTemplate', "docID={$docResult['id']}"),
            'id'      => $docID,
            'doc'     => $docResult
        );
        return $this->send($response);
    }

    /**
     * 在移动库或文档后的返回。
     * Return after move lib or doc.
     *
     * @param  string     $space
     * @param  int        $libID
     * @param  int        $docID
     * @param  bool       $spaceTypeChanged
     *
     * @access protected
     * @return void
     */
    protected function responseAfterMove(string $space, int $libID = 0, int $docID = 0, bool $spaceTypeChanged = false)
    {
        list($spaceType, $spaceID) = explode('.', $space);
        if($docID)
        {
            $docAppAction = array('executeCommand', 'handleMovedDoc', array($docID, $spaceID, $libID));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'docApp' => $docAppAction));
        }

        if($spaceTypeChanged)
        {
            $method = 'mySpace';
            if($spaceType == 'custom')  $method = 'teamSpace';
            if($spaceType == 'product') $method = 'productSpace';
            if($spaceType == 'project') $method = 'projectSpace';
            $locateLink = $this->createLink('doc', $method, "objectID={$spaceID}&libID={$libID}");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $locateLink));
        }
        else
        {
            $docAppAction = array('selectSpace', $spaceID, $libID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'docApp' => $docAppAction));
        }
    }

    /**
     * 为创建文档设置所属对象下拉值。
     * Set the dropdown values of object for creating document.
     *
     * @param  string      $linkType product|project|execution|custom
     * @param  object|null $lib
     * @param  string      $unclosed
     * @param  string      $objectID
     * @access protected
     * @return void
     */
    protected function setObjectsForCreate(string $linkType, object|null $lib, string $unclosed, int $objectID): void
    {
        if(!empty($objectID))
        {
            $object = $this->loadModel('project')->fetchByID((int)$objectID);
            if(!empty($object->isTpl)) dao::$filterTpl = 'never';
        }

        $objects = array();
        if($linkType == 'project')
        {
            $objects = $this->project->getPairsByProgram(0, 'all', false, 'order_asc');

            $this->view->executions = $this->execution->getPairs($objectID, 'all', 'multiple,leaf,noprefix');
            if(!empty($lib) && $lib->type == 'execution')
            {
                $execution = $this->loadModel('execution')->getByID($lib->execution);
                $objectID  = $execution->project;
                $libs      = $this->doc->getLibs('execution', "withObject,{$unclosed}", $lib->id, $lib->execution);

                $this->view->execution = $execution;
            }
        }
        elseif($linkType == 'execution')
        {
            $execution = $this->loadModel('execution')->getById($lib->execution);
            $objects   = $this->execution->getPairs($execution->project, 'all', "multiple,leaf,noprefix");
        }
        elseif($linkType == 'product' || $linkType == 'api')
        {
            $objects = $this->loadModel('product')->getPairs();
        }
        elseif($linkType == 'mine')
        {
            $this->lang->doc->aclList = $this->lang->doclib->mySpaceAclList;
        }

        $this->view->objects = $objects;
    }

    /**
     * 在上传文件后的返回。
     * Return after upload files.
     *
     * @param  array|string $docResult
     * @access protected
     * @return bool|int
     */
    protected function responseAfterUploadDocs(array|string $docResult): bool|int
    {
        if(!$docResult || dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->loadModel('action');
        if($this->post->uploadFormat == 'combinedDocs')
        {
            $docID = $docResult['id'];
            $files = zget($docResult, 'files', '');

            $fileAction = '';
            if(!empty($files)) $fileAction = $this->lang->addFiles . implode(',', $files) . "\n";

            $this->action->create('doc', $docID, 'created', $fileAction);
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $docID));

            $params   = "docID=" . $docResult['id'];
            $link     = isInModal() ? true : $this->createLink('doc', 'view', $params);
        }
        else
        {
            $docsAction = zget($docResult, 'docsAction', '');
            if(!empty($docsAction))
            {
                foreach($docsAction as $docID => $fileTitle)
                {
                    $fileAction = $this->lang->addFiles . $fileTitle->title . "\n";
                    $this->action->create('doc', $docID, 'created', $fileAction);
                }
            }

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
            $link = true;
        }

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link, 'closeModal' => true, 'docApp' => array('load', null, null, null, array('noLoading' => true, 'picks' => 'doc'))));
    }

    /**
     * 展示创建文档的相关变量。
     * Show the related variables of create.
     *
     * @param  string    $objectType product|project|execution|custom
     * @param  int       $objectID
     * @param  int       $libID
     * @param  int       $moduleID
     * @param  string    $docType    html|word|ppt|excel|attachment
     * @access protected
     * @return void
     */
    protected function assignVarsForCreate(string $objectType, int $objectID, int $libID, int $moduleID = 0, string $docType = ''): void
    {
        $lib = $libID ? $this->doc->getLibByID($libID) : '';
        if(empty($objectID) && $lib) $objectID = zget($lib, $lib->type, 0);
        if(empty($objectID) && $lib && $lib->type == 'custom') $objectID = $lib->parent;

        /* Get libs and the default lib ID. */
        $unclosed = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';
        $libPairs = $this->doc->getLibs($objectType, "{$unclosed}", $libID, $objectID);
        $moduleID = $moduleID ? (int)$moduleID : (int)$this->cookie->lastDocModule;
        if(!$libID && !empty($libPairs)) $libID = key($libPairs);
        if(empty($lib) && $libID) $lib = $this->doc->getLibByID($libID);

        $this->setObjectsForCreate(empty($lib->type) ? '' : $lib->type, empty($lib) ? null : $lib, $unclosed, $objectID);

        $this->view->objectType = $objectType;
        $this->view->spaceType  = $objectType;
        $this->view->type       = $objectType;
        $this->view->libID      = $libID;
        $this->view->lib        = $lib;
        $this->view->objectID   = $objectID;
        $this->view->libs       = $libPairs;
        $this->view->libName    = zget($lib, 'name', '');
        $this->view->moduleID   = $moduleID;
        $this->view->docType    = $docType;
        $this->view->groups     = $this->loadModel('group')->getPairs();
        $this->view->users      = $this->user->getPairs('nocode|noclosed|nodeleted');
        $this->view->optionMenu = empty($libID) ? array() : $this->loadModel('tree')->getOptionMenu($libID, 'doc', 0);
    }

    /**
     * 展示上传文件的相关变量。
     * Show the related variables of uploading files.
     *
     * @param  int       $docID
     * @param  string    $objectType product|project|execution|custom
     * @param  int       $objectID
     * @param  int       $libID
     * @param  int       $moduleID
     * @param  string    $docType    html|word|ppt|excel|attachment
     * @access protected
     * @return object
     */
    protected function assignVarsForUploadDocs(int $docID, string $objectType, int $objectID, int $libID, int $moduleID = 0, string $docType = ''): object
    {
        $doc = !empty($docID) ? $this->doc->getByID($docID) : null;
        if(empty($moduleID) && $doc) $moduleID = (int)$doc->module;

        $this->assignVarsForCreate($objectType, $objectID, $libID, $moduleID, $docType);

        $lib            = $libID ? $this->doc->getLibByID($libID) : '';
        $chapterAndDocs = $this->doc->getDocsOfLibs(array($libID), $objectType, $docID);
        $modulePairs    = empty($libID) ? array() : $this->loadModel('tree')->getOptionMenu($libID, 'doc', 0);
        if(isset($doc) && !empty($doc->parent) && !isset($chapterAndDocs[$doc->parent])) $chapterAndDocs[$doc->parent] = $this->doc->fetchByID($doc->parent);
        $chapterAndDocs = $this->doc->buildNestedDocs($chapterAndDocs, $modulePairs);

        $this->view->title      = empty($lib) ? '' : zget($lib, 'name', '', $lib->name . $this->lang->hyphen) . $this->lang->doc->uploadDoc;
        $this->view->linkType   = $objectType;
        $this->view->spaces     = ($objectType == 'mine' || $objectType == 'custom') ? $this->doc->getSubSpacesByType($objectType, false) : array();
        $this->view->optionMenu = $chapterAndDocs;
        $this->view->docID      = $docID;
        $this->view->doc        = $doc;
        return $this->view;
    }

    /**
     * 为编辑文档设置所属对象下拉值。
     * Set the dropdown values of object for creating document.
     *
     * @param  string    $objectType product|project|execution|custom
     * @param  int       $objectID
     * @access protected
     * @return void
     */
    protected function setObjectsForEdit(string $objectType, int $objectID): void
    {
        $objects = array();
        if($objectType == 'project')
        {
            $objects = $this->project->getPairsByProgram(0, 'all', false, 'order_asc');
        }
        elseif($objectType == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($objectID);
            $objects   = $this->execution->getPairs($execution->project, 'all', "multiple,leaf,noprefix");

            $parentExecutions = $childExecutions = array();
            $executions       = $this->execution->fetchExecutionList($execution->project, 'all', 0, 0, 'order_asc');
            foreach($executions as $execution)
            {
                if($execution->grade == 1) $parentExecutions[$execution->id] = $execution;
                if($execution->grade > 1 && $execution->parent) $childExecutions[$execution->parent][$execution->id] = $execution;
            }

            $objects = $this->execution->resetExecutionSorts($objects, $parentExecutions, $childExecutions);
        }
        elseif($objectType == 'product')
        {
            $objects = $this->loadModel('product')->getPairs();
        }
        elseif($objectType == 'mine')
        {
            $this->lang->doc->aclList = $this->lang->doclib->mySpaceAclList;
        }

        $this->view->objects = $objects;
    }

    /**
     * 在编辑文档后的返回。
     * Return after edit a document.
     *
     * @param  object    $doc
     * @param  array     $changes
     * @param  array     $files
     * @param  array     $deletedFiles
     * @access protected
     * @return array
     */
    protected function responseAfterEdit(object $doc, array $changes = array(), array $files = array(), array $deletedFiles = array()): array
    {
        if($this->post->comment != '' || !empty($changes) || !empty($files) || !empty($deletedFiles))
        {
            $action = 'Commented';
            if(!empty($changes))
            {
                $newType = $_POST['status'];
                if($doc->status == 'draft' && $newType == 'normal')              $action = 'releasedDoc';
                if($changes || $doc->status == $newType || $newType == 'normal') $action = 'Edited';
            }
            if(!empty($deletedFiles)) $deletedFiles = $this->dao->select('id,title')->from(TABLE_FILE)->where('id')->in($deletedFiles)->fetchPairs();

            $fileAction = '';
            if(!empty($files))        $fileAction .= $this->lang->addFiles . implode(',', $files) . "\n";
            if(!empty($deletedFiles)) $fileAction .= $this->lang->delFiles . implode(',', $deletedFiles) . "\n";
            if(!empty($changes))
            {
                $actionID = $this->action->create('doc', $doc->id, $action, $fileAction . $this->post->comment, '', '', false);
                $this->action->logHistory($actionID, $changes);
            }
        }

        $link     = $this->createLink('doc', 'view', "docID={$doc->id}");
        $doc      = $this->doc->getByID($doc->id);
        $lib      = $this->doc->getLibByID((int)$doc->lib);
        $objectID = zget($lib, $lib->type, 0);
        if(!$this->doc->checkPrivDoc($doc))
        {
            $moduleName = 'doc';
            if($this->app->tab == 'execution')
            {
                $moduleName = 'execution';
                $methodName = 'doc';
            }
            else
            {
                $methodName = zget($this->config->doc->spaceMethod, $lib->type);
            }
            $params = "objectID={$objectID}&libID={$doc->lib}";
            $link   = $this->createLink($moduleName, $methodName, $params);
        }

        if(isInModal()) return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true);

        $doc->isCollector = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false;
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link, 'doc' => $doc);
    }

    /**
     * 在编辑文档后的返回。
     * Return after edit a document.
     *
     * @param  object    $doc
     * @param  array     $changes
     * @param  array     $files
     * @access protected
     * @return void
     */
    protected function responseAfterEditTemplate(object $doc, array $changes = array(), array $files = array())
    {
        if($this->post->comment != '' || !empty($changes) || !empty($files))
        {
            $action = 'Commented';
            if(!empty($changes))
            {
                $newType = $_POST['status'];
                if($doc->status == 'draft' && $newType == 'normal') $action = 'releasedDoc';
                if($doc->status == 'normal' && $newType == 'draft') $action = 'savedDraft';
                if($doc->status == $newType) $action = 'Edited';
            }

            $fileAction = '';
            if(!empty($files)) $fileAction = $this->lang->addFiles . implode(',', $files) . "\n";
            $actionID = $this->action->create('docTemplate', $doc->id, $action, $fileAction . $this->post->comment);
            if(!empty($changes) && !empty($actionID)) $this->action->logHistory($actionID, $changes);
        }

        $link = $this->createLink('doc', 'view', "docID={$doc->id}");
        $doc  = $this->doc->getByID($doc->id);
        if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link, 'doc' => $doc));
    }

    /**
     * 处理文档大纲。
     *
     * @param  object    $doc
     * @access protected
     * @return object
     */
    protected function processOutline(object $doc): object
    {
        /* Split content into an array. */
        $content = preg_replace('/(<(h[1-6])[\S\s]*?\>[\S\s]*?<\/\2>)/', "\$1\n", $doc->content);
        $content = explode("\n", $content);

        /* Get the head element, for example h1,h2,etc. */
        $includeHeadElement = array();
        foreach($content as $index => $element)
        {
            preg_match('/<(h[1-6])([\S\s]*?)>([\S\s]*?)<\/\1>/', $element, $headElement);

            if(isset($headElement[1]) && !in_array($headElement[1], $includeHeadElement) && strip_tags($headElement[3]) != '') $includeHeadElement[] = $headElement[1];
        }

        /* Get the two elements with the highest rank. */
        sort($includeHeadElement);

        if($includeHeadElement)
        {
            $topLevel    = (int)ltrim($includeHeadElement[0], 'h');
            $outlineList = $this->buildOutlineList($topLevel, $content, $includeHeadElement);
            $outlineTree = $this->buildOutlineTree($outlineList);
            $this->view->outlineTree = $outlineTree;

            foreach($content as $index => $element)
            {
                preg_match('/<(h[1-6])([\S\s]*?)>([\S\s]*?)<\/\1>/', $element, $headElement);

                /* The current element is existed, the element is in the includeHeadElement, && the text in the element is not null. */
                if(isset($headElement[1]) && in_array($headElement[1], $includeHeadElement) && strip_tags($headElement[3]) != '')
                {
                    $content[$index] = str_replace('<' . $headElement[1] . $headElement[2] . '>', '<' . $headElement[1] . $headElement[2] . " id='anchor{$index}'" . '>', $content[$index]);
                }
            }

            $doc->content = implode("\n", $content);
        }

        return $doc;
    }

    /**
     * 展示文档详情相关变量。
     *
     * @param  int       $docID
     * @param  int       $version
     * @param  string    $type
     * @param  int       $objectID
     * @param  int       $libID
     * @param  object    $doc
     * @param  object    $object
     * @param  string    $objectType
     * @param  array     $libs
     * @param  array     $objectDropdown
     * @access protected
     * @return void
     */
    protected function assignVarsForView(int $docID, int $version, string $type, int $objectID, int $libID, object $doc, object $object, string $objectType, array $libs, array $objectDropdown): void
    {
        if($type == 'execution' && $this->app->tab == 'project')
        {
            $objectType = 'project';
            $objectID   = $object->project;
        }
        $this->view->title          = $this->lang->doc->common . $this->lang->hyphen . $doc->title;
        $this->view->docID          = $docID;
        $this->view->type           = $type;
        $this->view->objectID       = $objectID;
        $this->view->libID          = $libID;
        $this->view->doc            = $doc;
        $this->view->version        = $version;
        $this->view->object         = $object;
        $this->view->objectType     = $objectType;
        $this->view->lib            = isset($libs[$libID]) ? $libs[$libID] : new stdclass();
        $this->view->libs           = $libs;
        $this->view->canBeChanged   = common::canModify($type, $object); // Determines whether an object is editable.
        $this->view->actions        = $docID ? $this->action->getList('doc', $docID) : array();
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed,noletter');
        $this->view->libTree        = $this->doc->getLibTree((int)$libID, (array)$libs, $objectType, (int)$doc->module, (int)$objectID, 'all', 0, $docID);
        $this->view->preAndNext     = $this->loadModel('common')->getPreAndNextObject('doc', $docID);
        $this->view->moduleID       = $doc->module;
        $this->view->objectDropdown = $objectDropdown;
        $this->view->canExport      = ($this->config->edition != 'open' && common::hasPriv('doc', $type . '2export'));
        $this->view->exportMethod   = $type . '2export';
        $this->view->editors        = $this->doc->getEditors($docID);
        $this->view->linkParams     = "objectID={$objectID}&%s&browseType=&orderBy=status,id_desc&param=0";
        $this->view->spaceType      = $objectType;
        $this->view->productID      = $doc->product;
        $this->view->projectID      = $doc->project;
        $this->view->executionID    = $doc->execution;
    }

    /**
     * 设置空间页面的Cookie和Session。
     * Set the cookie and session of the space page.
     *
     * @param  string    $type       custom|product|project|execution
     * @param  string    $browseType all|draft|bysearch
     * @param  int       $objectID
     * @param  int       $libID
     * @param  int       $moduleID
     * @param  int       $param
     * @access protected
     * @return void
     */
    protected function setSpacePageStorage(string $type, string $browseType, int $objectID, int $libID, int $moduleID, int $param): void
    {
        $uri = $this->app->getURI(true);
        $this->session->set('createProjectLocate', $uri, 'doc');
        $this->session->set('structList', $uri, 'doc');
        $this->session->set('spaceType', $type, 'doc');
        $this->session->set('docList', $uri, 'doc');

        $docSpaceParam = new stdclass();
        $docSpaceParam->type       = $type;
        $docSpaceParam->objectID   = $objectID;
        $docSpaceParam->libID      = $libID;
        $docSpaceParam->moduleID   = $moduleID;
        $docSpaceParam->browseType = $browseType;
        $docSpaceParam->param      = $param;
        setCookie("docSpaceParam", json_encode($docSpaceParam), $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
    }

    /**
     * 展示空间相关api变量。
     * Show space related api variables.
     *
     * @param  string    $type       custom|product|project|execution
     * @param  string    $browseType all|draft|bysearch
     * @param  string    $libType
     * @param  int       $libID
     * @param  array     $libs
     * @param  int       $objectID
     * @param  int       $moduleID
     * @param  int       $queryID
     * @param  string    $orderBy
     * @param  int       $recTotal
     * @param  int       $recPerPage
     * @param  int       $pageID
     * @access protected
     * @return void
     */
    protected function assignApiVarForSpace(string $type, string $browseType, string $libType, int $libID, array $libs, int $objectID, int $moduleID, int $queryID, string $orderBy, int $param, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1): void
    {
        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        if($libType == 'api')
        {
            $this->loadModel('api');
            $this->session->set('objectName', $this->lang->doc->api, 'doc');

            $this->view->libs    = $libs;
            $this->view->apiID   = 0;
            $this->view->release = 0;
            $this->view->apiList = $browseType == 'bySearch' ? $this->api->getApiListBySearch($libID, $queryID, $type, array_keys($libs)) : $this->api->getListByModuleId($libID, $moduleID, $param, $pager);
        }
        else
        {
            if(in_array($type, array('product', 'project'))) $this->session->set('objectName', $this->lang->doc->common, 'doc');
            $this->view->docs = $browseType == 'bySearch' ? $this->doc->getDocsBySearch($type, $objectID, $libID, $queryID, $orderBy, $pager) : $this->doc->getDocs($libID, $moduleID, $browseType, $orderBy, $pager);
        }

        $apiObjectType = $type == 'product' || $type == 'project' ? $type : '';
        $apiObjectID   = $apiObjectType ? $objectID : 0;
        $apiLibs       = $apiObjectType ? $this->doc->getApiLibs(0, $apiObjectType, $apiObjectID) : array();

        $canExport = $libType == 'api' ? common::hasPriv('api', 'export') : common::hasPriv('doc', $type . '2export');
        if($this->config->edition == 'open') $canExport = false;

        $this->view->canExport         = $canExport;
        $this->view->apiLibID          = key($apiLibs);
        $this->view->pager             = $pager;
    }

    /**
     * 构建附件库的搜索表单
     * Build search form for showFiles.
     *
     * @param  string  $type     product|project|execution
     * @param  int     $objectID
     * @param  string  $viewType
     * @param  int     $param
     * @access public
     * @return void
     */
    public function buildSearchFormForShowFiles(string $type, int $objectID, string $viewType = '', int $param = 0)
    {
        $this->loadModel('file');
        $actionURL = $this->createLink($this->app->rawModule, $this->app->rawMethod, "type={$type}&objectID={$objectID}&viewType={$viewType}&browseType=bySearch&queryID=myQueryID");

        $objectTypeList = array();
        if($type == 'product')
        {
            $objectTypeList['product']     = $this->lang->product->common;
            $objectTypeList['story']       = $this->lang->doc->story;
            $objectTypeList['productplan'] = $this->lang->productplan->shortCommon;
            $objectTypeList['release']     = $this->lang->release->common;
        }
        if($type == 'project')
        {
            $objectTypeList['project']     = $this->lang->project->common;
            $objectTypeList['design']      = $this->lang->design->common;
            $objectTypeList['review']      = $this->lang->review->common;
            if(isset($this->lang->issue->common))   $objectTypeList['issue']   = $this->lang->issue->common;
            if(isset($this->lang->meeting->common)) $objectTypeList['meeting'] = $this->lang->meeting->common;
        }
        if($type == 'project' || $type == 'execution')
        {
            $objectTypeList['execution'] = $this->lang->execution->common;
            $objectTypeList['task']      = $this->lang->task->common;
            $objectTypeList['story']     = $this->lang->doc->story;
            $objectTypeList['build']     = $this->lang->build->common;
            $objectTypeList['testtask']  = $this->lang->testtask->common;
        }

        $objectTypeList['bug']         = $this->lang->bug->common;
        $objectTypeList['testcase']    = $this->lang->case->common;
        $objectTypeList['testreport']  = $this->lang->testreport->common;
        $objectTypeList['doc']         = $this->lang->doc->common;
        $this->config->file->search['params']['objectType']['values'] = $objectTypeList;

        $this->config->file->search['module']    = "{$type}DocFile";
        $this->config->file->search['onMenuBar'] = 'no';
        $this->config->file->search['queryID']   = (int)$param;
        $this->config->file->search['actionURL'] = $actionURL;
        $this->loadModel('search')->setSearchParams($this->config->file->search);
    }

    /**
     * 初始化我的空间的文档库。
     * Init Lib for mySpace.
     *
     * @access public
     * @return void
     */
    public function initLibForMySpace()
    {
        $systemMineLibCount = $this->dao->select('count(1) as count')->from(TABLE_DOCLIB)
            ->where('type')->eq('mine')
            ->andWhere('main')->eq(1)
            ->andWhere('addedBy')->eq($this->app->user->account)
            ->andWhere('vision')->eq($this->config->vision)
            ->fetch('count');
        if(!empty($systemMineLibCount)) return;

        $mineLib = new stdclass();
        $mineLib->type      = 'mine';
        $mineLib->vision    = $this->config->vision;
        $mineLib->name      = $this->lang->doclib->defaultSpace;
        $mineLib->main      = '1';
        $mineLib->acl       = 'private';
        $mineLib->addedBy   = $this->app->user->account;
        $mineLib->addedDate = helper::now();
        $this->dao->insert(TABLE_DOCLIB)->data($mineLib)->exec();
    }

    /**
     * 初始化团队空间的文档库。
     * Init Lib for teamSpace.
     *
     * @access public
     * @return void
     */
    public function initLibForTeamSpace()
    {
        $customLibCount = $this->dao->select('count(1) as count')->from(TABLE_DOCLIB)
            ->where('type')->eq('custom')
            ->andWhere('vision')->eq($this->config->vision)
            ->fetch('count');
        if(!empty($customLibCount)) return;

        $teamLib = new stdclass();
        $teamLib->type      = 'custom';
        $teamLib->vision    = $this->config->vision;
        $teamLib->name      = $this->lang->doclib->defaultSpace;
        $teamLib->acl       = 'open';
        $teamLib->addedBy   = $this->app->user->account;
        $teamLib->addedDate = helper::now();
        $this->dao->insert(TABLE_DOCLIB)->data($teamLib)->exec();
    }

    /**
     * 获取所有空间。
     * Get all spaces.
     *
     * @param  string  $extra nomine|onlymine
     * @access public
     * @return array
     */
    public function getAllSpaces(string $extra = ''): array
    {
        if(strpos($extra, 'doctemplate') !== false) return $this->doc->getDocTemplateSpaces();
        if(strpos($extra, 'nomine') !== false)   return $this->doc->getTeamSpaces();
        if(strpos($extra, 'onlymine') !== false) return array('mine' => $this->lang->doc->spaceList['mine']);
        return array('mine' => $this->lang->doc->spaceList['mine']) + $this->doc->getTeamSpaces();
    }

    /**
     * Record batch move actions.
     *
     * @param  array  $oldDocList
     * @param  object $data
     * @access public
     * @return void
     */
    public function recordBatchMoveActions(array $oldDocList, object $data)
    {
        $this->loadModel('action');
        foreach($oldDocList as $oldDoc)
        {
            $actionID = $this->action->create('doc', $oldDoc->id, 'Moved', '', json_encode(array('from' => $oldDoc->lib, 'to' => $data->lib)));

            $changes = common::createChanges($oldDoc, $data);
            $this->action->logHistory($actionID, $changes);
        }
    }

    /**
     * 在创建版本类型后的返回。
     * Return after create a template type.
     *
     * @param  int    $scope
     * @access public
     * @return string
     */
    public function responseAfterAddTemplateType(int $scope)
    {
        $response = array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true);
        return $this->send($response);
    }

    /**
     * 从session中获取数据。
     * Get data from session.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    protected function formFromSession(string $type): array
    {
        $sessionName = 'zentaoList' . $type;
        $session = array();
        if(isset($_SESSION[$sessionName]))
        {
            $session = $_SESSION[$sessionName];
            unset($_SESSION[$sessionName]);
        }

        $url    = zget($session, 'url', '');
        $idList = zget($session, 'idList', '');
        $cols   = zget($session, 'cols', array());
        $data   = zget($session, 'data', array());

        return array($url, $idList, $cols, $data);
    }

    /**
     * 处理表格列配置。
     * Handle table column config.
     *
     * @access protected
     * @return void
     */
    protected function prepareCols()
    {
        $cols = $this->view->cols;
        if(isset($cols['actions'])) unset($cols['actions']);
        foreach($cols as $key => $col)
        {
            $cols[$key]['name']     = $key;
            $cols[$key]['sortType'] = false;
            if(isset($col['link']))         unset($cols[$key]['link']);
            if(isset($col['nestedToggle'])) unset($cols[$key]['nestedToggle']);
        }
        $this->view->cols = $cols;
    }

    /**
     * 预览反馈列表。
     * Preview feedback list.
     *
     * @param  string    $view
     * @param  array     $settings
     * @param  string    $idList
     * @access protected
     * @return void
     */
    protected function previewFeedback(string $view, array $settings, string $idList): void
    {
        $cols   = $this->loadModel('datatable')->getSetting('feedback', 'browse');
        $data   = array();
        $action = $settings['action'];

        if($action === 'preview' && $view === 'setting')
        {
            $product   = (int)$settings['product'];
            $condition = $settings['condition'];

            $tmpProduct = isset($_SESSION['feedbackProduct']) ? $_SESSION['feedbackProduct'] : false;
            $_SESSION['feedbackProduct'] = $product;

            if($condition == 'customSearch')
            {
                $where = "`product`=$product";
                foreach($settings['field'] as $index => $field)
                {
                    $where = $this->loadModel('search')->setWhere($where, $field, $settings['operator'][$index], $settings['value'][$index], $settings['andor'][$index]);
                }

                $data = $this->dao->select('*')->from(TABLE_FEEDBACK)->where($where)->fetchAll('', false);
            }
            else
            {
                $data = $this->loadModel('feedback')->getList($condition);
            }

            if(is_bool($tmpProduct) && !$tmpProduct) unset($_SESSION['feedbackProduct']);
            else $_SESSION['feedbackProduct'] = $tmpProduct;
        }
        elseif($view === 'list')
        {
            $data = $this->loadModel('feedback')->getByList($idList);
        }

        $this->view->cols = $cols;
        $this->view->data = $data;
    }

    /**
     * 预览工单列表。
     * Preview ticket list.
     *
     * @param  string    $view
     * @param  array     $settings
     * @param  string    $idList
     * @access protected
     * @return void
     */
    protected function previeweicket(string $view, array $settings, string $idList): void
    {
        $cols   = $this->loadModel('datatable')->getSetting('ticket', 'browse');
        $data   = array();
        $action = $settings['action'];

        if($action === 'preview' && $view === 'setting')
        {
            $tmpProduct = isset($_SESSION['ticketProduct']) ? $_SESSION['ticketProduct'] : false;
            $tmpType    = isset($_SESSION['browseType'])    ? $_SESSION['browseType']    : false;
            $_SESSION['ticketProduct'] = (int)$settings['product'];
            $_SESSION['browseType']    = 'byProduct';

            $product   = (int)$settings['product'];
            $condition = $settings['condition'];
            if($condition == 'customSearch')
            {
                $where = "`product`=$product";
                foreach($settings['field'] as $index => $field)
                {
                    $where = $this->loadModel('search')->setWhere($where, $field, $settings['operator'][$index], $settings['value'][$index], $settings['andor'][$index]);
                }

                $data = $this->dao->select('*')->from(TABLE_TICKET)->where($where)->fetchAll('', false);
            }
            else
            {
                $data = $this->loadModel('ticket')->getList($condition);
            }

            if(is_bool($tmpProduct) && !$tmpProduct) unset($_SESSION['ticketProduct']);
            else  $_SESSION['ticketProduct'] = $tmpProduct;
            if(is_bool($tmpType) && !$tmpType) unset($_SESSION['browseType']);
            else $_SESSION['browseType'] = $tmpType;

        }
        elseif($view === 'list')
        {
            $data = $this->loadModel('ticket')->getByList($idList);
        }

        $this->view->cols = $cols;
        $this->view->data = $data;
    }

    /**
     * 预览产品计划列表。
     * Preview plan list.
     *
     * @param  string    $view
     * @param  array     $settings
     * @param  string    $idList
     * @access protected
     * @return void
     */
    protected function previewProductplan(string $view, array $settings, string $idList): void
    {
        $cols   = $this->loadModel('datatable')->getSetting('productplan', 'browse');
        $data   = array();
        $action = $settings['action'];

        if($action === 'preview' && $view === 'setting')
        {
            $productID    = (int)$settings['product'];
            $productPlans = $this->loadModel('productplan')->getProductPlans(array($productID));
            $data         = isset($productPlans[$productID]) ? $productPlans[$productID] : array();
        }
        elseif($view === 'list')
        {
            $data = $this->loadModel('productplan')->getByIDList(explode(',', $idList));
        }

        $this->view->cols = $cols;
        $this->view->data = $data;
    }

    /**
     * 预览产品计划下的内容列表。
     * Preview plan story.
     *
     * @param  string    $view
     * @param  array     $settings
     * @param  string    $idList
     * @access protected
     * @return void
     */
    protected function previewPlanStory(string $view, array $settings, string $idList): void
    {
        $cols   = $this->loadModel('datatable')->getSetting('bug', 'browse');
        $data   = array();
        $action = $settings['action'];

        if($action === 'preview' && $view === 'setting')
        {
            $data = $this->loadModel('story')->getPlanStories((int)$settings['plan']);
        }
        elseif($view === 'list')
        {
            $data = $this->loadModel('story')->getByList($idList);
        }

        $this->view->cols = $cols;
        $this->view->data = $data;
    }

    /**
     * 预览产品下的Bug列表。
     * Preview product story.
     *
     * @param  string    $view
     * @param  array     $settings
     * @param  string    $idList
     * @access protected
     * @return void
     */
    protected function previewProductBug(string $view, array $settings, string $idList): void
    {
        $cols   = $this->loadModel('datatable')->getSetting('bug', 'browse');
        $data   = array();
        $action = $settings['action'];

        if($action === 'preview' && $view === 'setting')
        {
            $product   = (int)$settings['product'];
            $condition = $settings['condition'];
            if($condition == 'customSearch')
            {
                $where = "`product`=$product";
                foreach($settings['field'] as $index => $field)
                {
                    $where = $this->loadModel('search')->setWhere($where, $field, $settings['operator'][$index], $settings['value'][$index], $settings['andor'][$index]);
                }

                $data = $this->dao->select('*')->from(TABLE_BUG)->where($where)->fetchAll('', false);
            }
            else
            {
                $data = $this->loadModel('bug')->getProductBugs(array((int)$settings['product']));
            }
        }
        elseif($view === 'list')
        {
            $data = $this->loadModel('bug')->getByIdList($idList);
        }

        $this->view->cols = $cols;
        $this->view->data = $data;
    }

    /**
     * 预览产品计划下的内容列表。
     * Preview plan story.
     *
     * @param  string    $view
     * @param  array     $settings
     * @param  string    $idList
     * @access protected
     * @return void
     */
    protected function previewPlanBug(string $view, array $settings, string $idList): void
    {
        $cols   = $this->loadModel('datatable')->getSetting('bug', 'browse');
        $data   = array();
        $action = $settings['action'];

        if($action === 'preview' && $view === 'setting')
        {
            $data = $this->loadModel('bug')->getPlanBugs((int)$settings['plan']);
        }
        elseif($view === 'list')
        {
            $data = $this->loadModel('bug')->getByIdList($idList);
        }

        $this->view->cols = $cols;
        $this->view->data = $data;
    }

    /**
     * 预览产品下的用例。
     * Preview product case.
     *
     * @param  string    $view
     * @param  array     $settings
     * @param  string    $idList
     * @access protected
     * @return void
     */
    protected function previewProductCase(string $view, array $settings, string $idList): void
    {
        $this->loadModel('testcase');
        $cols   = $this->config->testcase->dtable->fieldList;
        $data   = array();
        $action = $settings['action'];

        if($action === 'preview' && $view === 'setting')
        {
            $product   = (int)$settings['product'];
            $condition = $settings['condition'];
            if($condition === 'customSearch')
            {
                $where = "`product`=$product";
                foreach($settings['field'] as $index => $field)
                {
                    $where = $this->loadModel('search')->setWhere($where, $field, $settings['operator'][$index], $settings['value'][$index], $settings['andor'][$index]);
                }
                $data = $this->dao->select('*')->from(TABLE_CASE)->where($where)->fetchAll('', false);
            }
            else
            {
                $data = $this->loadModel('testcase')->getTestCases($product, '', $condition, 0, 0);
            }
        }
        elseif($view === 'list')
        {
            $data = $this->loadModel('testcase')->getByList(explode(',', $idList));
        }

        $this->view->cols = $cols;
        $this->view->data = $data;
    }

    /**
     * 预览产品研发需求列表。
     * Preview product story.
     *
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access protected
     * @return void
     */
    protected function previewProductStory(string $view, array $settings, string $idList): void
    {
        $this->previewStory('story', $view, $settings, $idList);
    }

    /**
     * 预览业务需求列表。
     * Preview epic story.
     *
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access protected
     * @return void
     */
    protected function previewER(string $view, array $settings, string $idList): void
    {
        $this->previewStory('epic', $view, $settings, $idList);
    }

    /**
     * 预览用户需求列表。
     * Preview requirement story.
     *
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access protected
     * @return void
     */
    protected function previewUR(string $view, array $settings, string $idList): void
    {
        $this->previewStory('requirement', $view, $settings, $idList);
    }

    /**
     * 预览项目研发需求列表。
     * Preview project story.
     *
     * @param  string    $view
     * @param  array     $settings
     * @param  string    $idList
     * @access protected
     * @return void
     */
    protected function previewProjectStory(string $view, array $settings, string $idList): void
    {
        $cols   = $this->loadModel('datatable')->getSetting('product', 'browse');
        $data   = array();
        $action = $settings['action'];

        if($action === 'preview' && $view === 'setting')
        {
            $project = (int)$settings['project'];
            $condition = $settings['condition'];
            if($condition === 'customSearch')
            {
                $where = "1=1";
                foreach($settings['field'] as $index => $field)
                {
                    $where = $this->loadModel('search')->setWhere($where, $field, $settings['operator'][$index], $settings['value'][$index], $settings['andor'][$index]);
                }
                $data = $this->dao->select('DISTINCT t1.*, t2.*')->from(TABLE_PROJECTSTORY)->alias('t1')
                    ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
                    ->where('project')->eq($project)
                    ->andWhere($where)
                    ->fetchAll('', false);
            }
            else
            {
                $data = $this->loadModel('story')->getExecutionStories($project, 0, '', $condition);
            }
        }
        elseif($view === 'list')
        {
            $data = $this->loadModel('story')->getByList($idList);
        }

        $this->view->cols = $cols;
        $this->view->data = $data;
    }

    /**
     * 预览执行需求列表。
     * Preview execution story.
     *
     * @param  string    $view
     * @param  array     $settings
     * @param  string    $idList
     * @access protected
     * @return void
     */
    protected function previewExecutionStory(string $view, array $settings, string $idList): void
    {
        $cols   = $this->loadModel('datatable')->getSetting('execution', 'story', false, 'story');
        $data   = array();
        $action = $settings['action'];

        if($action === 'preview' && $view === 'setting')
        {
            $data = $this->loadModel('story')->getExecutionStories((int)$settings['execution'], 0, '', $settings['condition'], '', 'story');
        }
        elseif($view === 'list')
        {
            $data = $this->loadModel('story')->getByList($idList);
        }

        $this->view->cols = $cols;
        $this->view->data = $data;
    }

    /**
     * 预览需求列表。
     * Preview story list.
     *
     * @param  string    $storyType
     * @param  string    $view
     * @param  array     $settings
     * @param  string    $idList
     * @access protected
     * @return void
     */
    protected function previewStory(string $storyType, string $view, array $settings, string $idList): void
    {
        $cols   = $this->loadModel('datatable')->getSetting('product', 'browse');
        $data   = array();
        $action = $settings['action'];

        if($action === 'preview' && $view === 'setting')
        {
            $product = (int)$settings['product'];
            $condition = $settings['condition'];
            if($condition === 'customSearch')
            {
                $where = "`product`=$product and `type`='$storyType'";
                foreach($settings['field'] as $index => $field)
                {
                    $where = $this->loadModel('search')->setWhere($where, $field, $settings['operator'][$index], $settings['value'][$index], $settings['andor'][$index]);
                }
                $data = $this->dao->select('*')->from(TABLE_STORY)->where($where)->fetchAll('', false);
            }
            else
            {
                $data = $this->loadModel('product')->getStories($product, '', $condition, 0, 0, $storyType);
            }
        }
        elseif($view === 'list')
        {
            $data = $this->loadModel('story')->getByList($idList);
        }

        $this->view->cols = $cols;
        $this->view->data = $data;
    }

    /**
     * 预览任务列表。
     * Preview task list.
     *
     * @param  string    $view
     * @param  array     $settings
     * @param  string    $idList
     * @access protected
     * @return void
     */
    protected function previewTask(string $view, array $settings, string $idList): void
    {
        $cols   = $this->loadModel('datatable')->getSetting('execution', 'task');
        $data   = array();
        $action = $settings['action'];

        if($action === 'preview' && $view === 'setting')
        {
            $data = $this->loadModel('task')->getExecutionTasks((int)$settings['execution']);
        }
        elseif($view === 'list')
        {
            $data = $this->loadModel('task')->getByIdList(explode(',', $idList));
        }

        $this->view->cols = $cols;
        $this->view->data = $data;
    }

    /**
     * 预览用例库下的用例。
     * Preview caselib case.
     *
     * @param  string    $view
     * @param  array     $settings
     * @param  string    $idList
     * @access protected
     * @return void
     */
    protected function previewCaselib(string $view, array $settings, string $idList): void
    {
        $this->loadModel('testcase');
        $cols   = $this->config->testcase->dtable->fieldList;
        $data   = array();
        $action = $settings['action'];

        if($action === 'preview' && $view === 'setting')
        {
            $caselib   = (int)$settings['caselib'];
            $condition = $settings['condition'];
            if($condition === 'customSearch')
            {
                $where = "`lib`=$caselib";
                foreach($settings['field'] as $index => $field)
                {
                    $where = $this->loadModel('search')->setWhere($where, $field, $settings['operator'][$index], $settings['value'][$index], $settings['andor'][$index]);
                }
                $data = $this->dao->select('*')->from(TABLE_CASE)->where($where)->fetchAll('', false);
            }
            else
            {
                $data = $this->loadModel('caselib')->getLibCases((int)$caselib, $condition);
            }
        }
        elseif($view === 'list')
        {
            $data = $this->loadModel('testcase')->getByList(explode(',', $idList));
        }

        $this->view->cols = $cols;
        $this->view->data = $data;
    }

    /**
     * 导出禅道列表。
     * Export zentao list.
     *
     * @param  object    $blockData
     * @access protected
     * @return string
     */
    protected function exportZentaoList(object $blockData): string
    {
        $users = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');
        $cols  = zget($blockData->content, 'cols', array());
        $data  = zget($blockData->content, 'data', array());

        $list = array();
        $list[] = array('type' => 'heading', 'props' => array('depth' => 5, 'text' => $blockData->title));

        $tableProps = array();
        foreach($cols as $col)
        {
            if(isset($col->show) && !$col->show) continue;
            $width = null;
            if(isset($col->width) && is_numeric($col->width)) $width = $col->width < 1 ? (($col->width * 100) . '%') : "{$col->width}px";
            $tableProps['cols'][] = array('name' => $col->name, 'text' => $col->title, 'width' => $width);
        }
        foreach($data as $row)
        {
            $rowData = array();
            foreach($cols as $col)
            {
                if(isset($col->show) && !$col->show) continue;
                $value = isset($row->{$col->name}) ? $row->{$col->name} : '';
                if(isset($col->map)) $value = zget($col->map, $value);
                if(isset($col->type) && $col->type == 'user'   && isset($users[$value]))  $value = zget($users, $value);
                if(isset($col->type) && $col->type == 'status' && isset($col->statusMap)) $value = zget($col->statusMap, $value);
                $rowData[$col->name] = array('text' => (is_array($value) || is_object($value)) ? '' : strval($value));
            }
            $tableProps['data'][] = $rowData;
        }

        $list[] = array('type' => 'table', 'props' => $tableProps);
        return json_encode($list);
    }

    /**
     * 展示需求层级关系。
     * Assign story grade data.
     *
     * @param  string    $type
     * @access protected
     * @return void
     */
    protected function assignStoryGradeData(string $type): void
    {
        $gradeGroup = array();
        $gradeList  = $this->loadModel('story')->getGradeList('');
        foreach($gradeList as $grade) $gradeGroup[$grade->type][$grade->grade] = $grade->name;

        if($type != 'planStory' && $type != 'projectStory')
        {
            if($type == 'productStory') $storyType = 'story';
            if($type == 'ER')           $storyType = 'epic';
            if($type == 'UR')           $storyType = 'requirement';
            $this->view->storyType = $storyType;
        }

        $this->view->gradeGroup = $gradeGroup;
    }

    /**
     * 处理发布列表展示数据。
     * Process release list display data.
     *
     * @param  array     $releaseList
     * @param  array     $childReleases
     * @access protected
     * @return array
     */
    protected function processReleaseListData(array $releaseList, array $childReleases): array
    {
        $releases = array();
        foreach($releaseList as $release)
        {
            $release->rowID   = $release->id;
            $release->rowspan = count($release->builds);

            if(!empty($release->builds))
            {
                foreach($release->builds as $build)
                {
                    $releaseInfo = clone $release;
                    $releaseInfo->build = $build;

                    $releases[] = $releaseInfo;
                }
            }
            else
            {
                $releases[] = $release;
            }
            if(empty($release->releases)) continue;

            foreach(explode(',', $release->releases) as $childID)
            {
                if(isset($childReleases[$childID]))
                {
                    $child = clone $childReleases[$childID];
                    $child = current($this->processReleaseListData(array($child)));

                    $child->rowID  = "{$release->id}-{$childID}";
                    $child->parent = $release->id;
                    $releases[$child->rowID] = $child;
                }
            }
        }

        return $releases;
    }

    /*
     * Get authorized subdocuments through recursion.
     * 递归获取有权限的子文档。
     *
     * @param  int    $docID
     * @param  int    $level
     * @access public
     */
    protected function getDocChildrenByRecursion(int $docID, int $level)
    {
        if($level <= 0) return array();

        $docs     = $this->doc->getDocsByParent($docID);
        $privDocs = $this->doc->filterPrivDocs($docs, '');
        foreach($privDocs as $doc) $doc->children = $this->getDocChildrenByRecursion($doc->id, $level - 1);
        return $privDocs;
    }
}
