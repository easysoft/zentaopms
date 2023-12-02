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
     * @access protected
     * @return arary
     */
    protected function processFiles(array $files, array $fileIcon, array $sourcePairs): array
    {
        $this->loadModel('file');
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

            $imageSize = $this->file->getImageSize($file);
            $file->imageWidth = isset($imageSize[0]) ? $imageSize[0] : 0;
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
                $item['id']    = $index;
                $item['title'] = strip_tags($headElement[3]);
                $item['hint']  = strip_tags($headElement[3]);
                $item['url']   = '#anchor' . $index;
                $item['level'] = $currentLevel;

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
     * 设置文档树默认展开的节点。
     * Set the default expanded nodes of the document tree.
     *
     * @param  int       $libID
     * @param  int       $moduleID
     * @param  string    $objectType mine|product|project|execution|custom
     * @param  int       $executionID
     * @access protected
     * @return array
     */
    protected function getDefacultNestedShow(int $libID, int $moduleID, string $objectType = '', int $executionID = 0): array
    {
        if(!$libID && !$moduleID) return array();

        $prefix = $objectType == 'mine' ? "0:" : '';
        $prefix = $executionID ? "{$executionID}:" : $prefix;
        if($libID && !$moduleID) return array("{$prefix}{$libID}" => true);

        $module = $this->loadModel('tree')->getByID($moduleID);
        $path   = explode(',', trim($module->path, ','));
        $path   = implode(':', $path);
        return array("{$prefix}{$libID}:{$path}" => true);
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
     * @param  string    $objectDropdown
     * @access protected
     * @return void
     */
    protected function assignVarsForMySpace(string $type, int $objectID, int $libID, int $moduleID, string $browseType, int $param, string $orderBy, array $docs, object $pager, array $libs, string $objectDropdown): void
    {
        $this->view->title             = $this->lang->doc->common;
        $this->view->type              = $type;
        $this->view->libID             = $libID;
        $this->view->moduleID          = $moduleID;
        $this->view->browseType        = $browseType;
        $this->view->param             = $param;
        $this->view->orderBy           = $orderBy;
        $this->view->docs              = $docs;
        $this->view->pager             = $pager;
        $this->view->objectDropdown    = $objectDropdown;
        $this->view->objectID          = 0;
        $this->view->libType           = 'lib';
        $this->view->spaceType         = 'mine';
        $this->view->users             = $this->user->getPairs('noletter');
        $this->view->lib               = $this->doc->getLibByID($libID);
        $this->view->libTree           = $this->doc->getLibTree($type != 'mine' ? 0 : $libID, $libs, 'mine', $moduleID, 0, $browseType);
        $this->view->canExport         = ($this->config->edition != 'open' && common::hasPriv('doc', 'mine2export') && $type == 'mine');
        $this->view->linkParams        = "objectID={$objectID}&%s&browseType=&orderBy={$orderBy}&param=0";
        $this->view->defaultNestedShow = $this->getDefacultNestedShow($libID, $moduleID, $type);
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
        $acl = 'default';
        if($type == 'custom')
        {
            $acl = 'open';
            unset($this->lang->doclib->aclList['default']);
        }
        elseif($type == 'mine')
        {
            $acl = 'private';
            $this->lang->doclib->aclList = $this->lang->doclib->mySpaceAclList;
        }
        $this->view->acl = $acl;

        if($type != 'custom' && $type != 'mine')
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
    protected function responseAfterCreateLib(string $type = '', int $objectID = 0, int $libID = 0): bool|int
    {
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        if($type == 'project'   && $this->post->project)   $objectID = $this->post->project;
        if($type == 'product'   && $this->post->product)   $objectID = $this->post->product;
        if($type == 'execution' && $this->post->execution) $objectID = $this->post->execution;
        if($type == 'custom')                              $objectID = 0;

        $type = $type == 'execution' && $this->app->tab != 'execution' ? 'project' : $type;

        $this->action->create('docLib', $libID, 'Created');

        if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $libID));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "locateNewLib(\"$type\", \"$objectID\", \"$libID\")"));
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
        if($lib->type == 'custom')
        {
            unset($this->lang->doclib->aclList['default']);
        }
        elseif($lib->type == 'api')
        {
            $this->app->loadLang('api');
            $type = !empty($lib->product) ? 'product' : 'project';
            $this->lang->api->aclList['default'] = sprintf($this->lang->api->aclList['default'], $this->lang->{$type}->common);
        }
        elseif($lib->type == 'mine')
        {
            $this->lang->doclib->aclList = $this->lang->doclib->mySpaceAclList['private'];
        }
        elseif($lib->type != 'custom')
        {
            $type = isset($type) ? $type : $lib->type;
            $this->lang->doclib->aclList['default'] = sprintf($this->lang->doclib->aclList['default'], $this->lang->{$type}->common);
            $this->lang->doclib->aclList['private'] = sprintf($this->lang->doclib->privateACL, $this->lang->{$type}->common);
            unset($this->lang->doclib->aclList['open']);
        }

        if(!empty($lib->main)) unset($this->lang->doclib->aclList['private'], $this->lang->doclib->aclList['open']);
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
                if(($doclib->acl == 'custom' || $doclib->acl == 'private') && strpos($doclib->users, $account) === false && $doclib->addedBy !== $account && !(isset($groupAccounts) && in_array($account, $groupAccounts, true))) $canVisit = false;
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
     * @param  int       $libID
     * @param  array     $docResult
     * @access protected
     * @return bool|int
     */
    protected function responseAfterCreate(int $libID, array $docResult): bool|int
    {
        $docID = $docResult['id'];
        $files = zget($docResult, 'files', '');
        $lib   = $this->doc->getLibByID($libID);

        $fileAction = '';
        if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n";

        $actionType = $_POST['status'] == 'draft' ? 'savedDraft' : 'releasedDoc';
        $this->action->create('doc', $docID, $actionType, $fileAction);

        if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $docID));
        $params   = "docID=" . $docResult['id'];
        $response = array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('doc', 'view', $params));

        return $this->send($response);
    }

    /**
     * 为创建文档设置所属对象下拉值。
     * Set the dropdown values of object for creating document.
     *
     * @param  string    $linkType product|project|execution|custom
     * @param  object    $lib
     * @param  string    $unclosed
     * @access protected
     * @return void
     */
    protected function setObjectsForCreate(string $linkType, object $lib, string $unclosed): void
    {
        $objects  = array();
        if($linkType == 'project')
        {
            $excludedModel = $this->config->vision == 'lite' ? '' : 'kanban';
            $objects       = $this->project->getPairsByProgram(0, 'all', false, 'order_asc', $excludedModel);

            $this->view->executions = array();
            if($lib->type == 'execution')
            {
                $execution = $this->loadModel('execution')->getByID($lib->execution);
                $objectID  = $execution->project;
                $libs      = $this->doc->getLibs('execution', "withObject,{$unclosed}", $libID, $lib->execution);

                $this->view->execution  = $execution;
                $this->view->executions = $this->execution->getPairs($objectID, 'sprint,stage', 'multiple,leaf,noprefix');
            }
        }
        elseif($linkType == 'execution')
        {
            $execution = $this->loadModel('execution')->getById($objectID);
            $objects   = $this->execution->getPairs($execution->project, 'sprint,stage', "multiple,leaf,noprefix");
        }
        elseif($linkType == 'product')
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
     * @param  array     $docResult
     * @access protected
     * @return bool|int
     */
    protected function responseAfterUploadDocs(array $docResult): bool|int
    {
        if(!$docResult || dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->loadModel('action');
        if($this->post->uploadFormat == 'combinedDocs')
        {
            $docID = $docResult['id'];
            $files = zget($docResult, 'files', '');

            $fileAction = '';
            if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n";

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

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));
    }

    /**
     * 展示上传文件的相关变量。
     * Show the related variables of uploading files.
     *
     * @param  string    $objectType product|project|execution|custom
     * @param  int       $objectID
     * @param  int       $libID
     * @param  int       $moduleID
     * @param  string    $docType    html|word|ppt|excel|attachment
     * @access protected
     * @return void
     */
    protected function assignVarsForUploadDocs(string $objectType, int $objectID, int $libID, int $moduleID = 0, string $docType = ''): void
    {
        $lib = $libID ? $this->doc->getLibByID($libID) : '';
        if(empty($objectID) && $lib) $objectID = zget($lib, $lib->type, 0);

        /* Get libs and the default lib ID. */
        $moduleID   = $moduleID ? (int)$moduleID : (int)$this->cookie->lastDocModule;
        $unclosed   = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';
        $libs       = $this->doc->getLibs($objectType, "withObject,{$unclosed}", $libID, $objectID);
        if(!$libID && !empty($libs)) $libID = key($libs);
        if(empty($lib) && $libID) $lib = $this->doc->getLibByID($libID);

        $this->setObjectsForCreate($objectType, $lib, $unclosed);

        $this->view->title            = empty($lib) ? '' : zget($lib, 'name', '', $lib->name . $this->lang->colon) . $this->lang->doc->uploadDoc;
        $this->view->linkType         = $objectType;
        $this->view->objectType       = $objectType;
        $this->view->objectID         = empty($lib) ? 0 : zget($lib, $lib->type, 0);
        $this->view->libID            = $libID;
        $this->view->lib              = $lib;
        $this->view->libs             = $libs;
        $this->view->libName          = zget($lib, 'name', '');
        $this->view->moduleOptionMenu = $this->doc->getLibsOptionMenu($libs);
        $this->view->moduleID         =  $libID . '_' . $moduleID;
        $this->view->docType          = $docType;
        $this->view->groups           = $this->loadModel('group')->getPairs();
        $this->view->users            = $this->user->getPairs('nocode|noclosed|nodeleted');
    }
}
