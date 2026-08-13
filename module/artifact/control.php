<?php
declare(strict_types=1);
/**
 * The control file of artifact module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     atifact
 * @link        https://www.zentao.net
 */
class artifact extends control
{
    public $spaces = array();
    public $repos  = array();

    function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);

        if(in_array($this->app->rawMethod, array('ajaxgetfolders'))) return;
        $this->spaces = $this->loadModel('space')->getPairs($this->app->user->account);
        $this->repos  = $this->loadModel('repo')->getRepoPairs($this->app->user->account);
    }

    /**
     * 检查用户权限。
     * Check user access.
     *
     * @param  int    $spaceID
     * @param  int    $repoID
     * @access public
     * @return void
     */
    function checkAccess($spaceID = 0, $repoID = 0)
    {
        if($spaceID && isset($this->spaces[$spaceID])) return true;
        if($repoID && isset($this->repos[$repoID])) return true;

        return $this->sendError($this->lang->error->accessDenied);
    }

    /**
     * 设置页面公共数据。
     * Common actions.
     *
     * @param  int    $spaceID
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function commonAction(int $spaceID = 0, int $repoID = 0)
    {
        $this->loadModel('space')->setMenu($spaceID);
        if($repoID)
        {
            $repoID = $this->loadModel('repo')->saveState($repoID);
            $this->loadModel('ci')->setMenu($repoID);
        }
        else
        {
            $this->session->set('repoID', '');
        }

        $this->view->spaceID = $spaceID;
        $this->view->repoID  = $repoID;
        $this->view->inSpace = !empty($spaceID);
    }

    /**
     * 制品库列表。
     * Browse pipeline.
     *
     * @param  int    $space
     * @param  int    $repoID
     * @param  string $type
     * @access public
     * @return void
     */
    public function browse(int $space = 0, int $repoID = 0, string $type = 'space', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->commonAction($space, $repoID);
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $repo = $this->loadModel('repo')->fetchByID($repoID);
        $this->view->title           = $this->lang->artifact->common . $this->lang->hyphen . $this->lang->artifact->browse;
        $this->view->repo            = $repo;
        $this->view->repoID          = $repoID;
        $this->view->spaceID         = $space;
        $this->view->repoPairs       = $this->repo->getRepoPairs();
        $this->view->type            = $type;
        $this->view->artifactLibList = $this->artifact->getLibList($type == 'repo' && !empty($repo) ? $repo->spaceID : $space, $repoID, $type, 'createdDate_asc', $pager);
        $this->view->pager           = $pager;

        $this->display();
    }

    /**
     * 浏览制品库制品。
     * Browse artifact repo.
     *
     * @param  int    $artifactLibID
     * @param  int    $spaceID
     * @param  int    $repoID
     * @param  string $type
     * @param  string $selectPath
     * @param  int    $leaf
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function view(int $artifactLibID, int $spaceID = 0, int $repoID = 0, string $type = 'space', string $selectPath = '', int $leaf = 0, string $orderBy = 'edited_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->session->set('artifactLibViewLink', $this->inLink('view', "artifactLibID={$artifactLibID}&spaceID={$spaceID}&repoID={$repoID}&type={$type}&selectPath={$selectPath}&leaf={$leaf}"));

        $this->config->file->dangers = '';
        $this->checkAccess($spaceID, $repoID);
        $selectPath = helper::safe64Decode($selectPath);

        $artifactLib = $this->artifact->fetchByID($artifactLibID);
        if(empty($artifactLib)) return print(js::error($this->lang->artifact->notice->noArtifact));


        $this->commonAction((int)$artifactLib->spaceID, (int)$artifactLib->repoID);
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $selectPathList = empty($selectPath) ? array() : explode('/', trim($selectPath, '/'));
        $breadCrumbs    = $this->artifactZen->getBreadCrumbs($artifactLib, $selectPathList, $spaceID, $repoID, $type);

        $selectNode  = empty($selectPathList) ? false : new stdclass();
        $keyPath     = '';
        $currentPath = '';
        foreach($selectPathList as $path)
        {
            $currentPath .= '/' . $path;
            $pathKey = helper::safe64Encode($currentPath);
            $keyPath = empty($keyPath) ? $pathKey : "{$keyPath}:{$pathKey}";
            $selectNode->$keyPath = true;
        }

        $repo         = $this->loadModel('repo')->fetchByID($repoID);
        $artifactLibs = $this->artifact->getLibList($type == 'repo' && !empty($repo) ? $repo->spaceID : $spaceID, $repoID, $type, 'createdDate_asc');

        $artifactLibList = array();
        foreach($artifactLibs as $lib)
        {
            $artifactLibList[] = array('text' => $lib->name, 'value' => $lib->id, 'keys' => $lib->name, 'url' => $this->createLink('artifact', 'view', "artifactLibID={$lib->id}&spaceID={$spaceID}&repoID={$repoID}&type={$type}"));
        }
        $node      = $this->artifactZen->getNodeByPath($artifactLib, $selectPath);
        $assetList = $this->artifact->getAssetListByNodeID(empty($node) || empty($node->entityID) ? '' : $node->entityID, $artifactLibID, $orderBy, $pager);

        $this->view->title           = $artifactLib->name . $this->lang->hyphen . $this->lang->artifact->repoBrowser;
        $this->view->artifact        = $artifactLib;
        $this->view->browseLink      = $this->createLink('artifact', 'browse', "space={$spaceID}&repoID={$repoID}&type={$type}");
        $this->view->treeItems       = $this->artifactZen->getArtifactLibTreeData($artifactLib, '/', $selectPath, $spaceID, $repoID, $type, $leaf);
        $this->view->selectNode      = $selectNode;
        $this->view->spaceID         = $spaceID;
        $this->view->repoID          = $repoID;
        $this->view->type            = $type;
        $this->view->repo            = $repo;
        $this->view->artifactLibList = empty($artifactLibList) ? array() : $artifactLibList;
        $this->view->breadCrumbs     = $breadCrumbs;
        $this->view->selectPath      = $selectPath ? helper::safe64Encode($selectPath) : '';
        $this->view->leaf            = $leaf;
        $this->view->assetList       = $assetList;
        $this->view->node            = $node;
        $this->view->orderBy         = $orderBy;
        $this->view->users           = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager           = $pager;

        $this->display();
    }

    /**
     * 创建制品库。
     * create artifact repo.
     *
     * @param  int $space
     * @param  int $repoID
     * @param  string $type
     * @access public
     * @return void
     */
    public function create(int $space = 0, int $repoID = 0, string $type = 'space')
    {
        $this->checkAccess($space, $repoID);

        if($_POST)
        {
            $repo = $this->loadModel('repo')->fetchByID($repoID);

            $formData = form::data($this->config->artifact->form->create)
                ->add('repoID', (int)$repoID)
                ->add('spaceID', $type == 'repo' && !empty($repo) ? $repo->spaceID : (int)$space)
                ->get();
            if(in_array($formData->format, array('container', 'helm')) && !preg_match('/[a-zA-Z0-9_\-\.]+$/', $formData->code))
            {
                return $this->sendError(array('name' => $this->lang->artifact->notice->nameNotSupportChinese));
            }
            $result = $this->loadModel('gitfox')->request('/artifacts/views', 'POST', $formData);
            if(dao::isError()) $this->sendError(dao::getError());

            if(!empty($result->id))$this->loadModel('action')->create('artifact', $result->id, 'created');
            $loadURL = $this->createLink('artifact', 'browse', "space={$space}&repoID={$repoID}&type={$type}");
            $this->sendSuccess(array('locate' => $loadURL));
        }

        $this->view->title = $this->lang->artifact->create;
        $this->display();
    }

    /**
     * 编辑制品库。
     * edit artifact repo.
     *
     * @param  int $id
     * @access public
     * @return void
     */
    public function edit(int $id)
    {
        $artifactLib = $this->artifact->fetchByID($id);
        $this->checkAccess($artifactLib->spaceID, $artifactLib->repoID);

        if($_POST)
        {
            $formData = form::data($this->config->artifact->form->edit)->get();

            if(in_array($artifactLib->type, array('container', 'helm')) && !preg_match('/[a-zA-Z0-9_\-\.]+$/', $formData->code))
            {
                return $this->sendError(array('name' => $this->lang->artifact->notice->nameNotSupportChinese));
            }

            $param = array();
            $param['entityID'] = 'artifact.' . $id;
            $param['newName']  = $formData->name;
            $this->loadModel('gitfox')->request('/artifacts/entities/relocate', 'POST', $param);
            if(dao::isError()) $this->sendError(dao::getError());

            $this->loadModel('action')->create('artifact', (int)$id, 'edited');
            $this->sendSuccess(array('load' => true));
        }

        $this->view->title       = $this->lang->artifact->edit;
        $this->view->artifactLib = $artifactLib;
        $this->display();
    }

    /**
     * 删除制品库。
     * Delete artifact repo.
     *
     * @param  int $id
     * @access public
     * @return void
     */
    public function delete(int $id)
    {
        $artifactLib = $this->artifact->fetchByID($id);
        $this->checkAccess($artifactLib->spaceID, $artifactLib->repoID);

        $result = $this->loadModel('gitfox')->request('/artifacts/entities', 'DELETE', array('entityIDs' => array("artifact.{$id}")));
        if(dao::isError())
        {
            $error = dao::getError();
            $this->sendError(!empty($error['apiMessage']) ? $error['apiMessage'] : $error);
        }
        if($result) $this->loadModel('action')->create('artifact', $id, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);

        $this->sendSuccess(array('load' => true));
    }

    /**
     * 创建制品库目录。
     * Create artifact repo directory.
     *
     * @param  int    $artifactLibID
     * @param  string $path
     * @param  int    $isSubDir
     * @access public
     * @return void
     */
    public function createDir(int $artifactLibID, string $path = '', int $isSubDir = 0)
    {
        $artifactLib = $this->artifact->fetchByID($artifactLibID);
        $this->checkAccess($artifactLib->spaceID, $artifactLib->repoID);

        if($_POST)
        {
            $path = helper::safe64Decode($path);
            if(!$isSubDir) $path = $this->artifact->parseDirname($path);
            $base64Path = $path ? helper::safe64Encode($path) : '';

            $formData = form::data($this->config->artifact->form->createDir)->get();
            if(!preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9\-_]+$/u', $formData->name)) return $this->sendError(array('name' => $this->lang->artifact->notice->dirNameFormatError));
            if(mb_strlen($formData->name) > 15) return $this->sendError(array('name' => $this->lang->artifact->notice->dirNameTooLong));
            if($path)
            {
                $formData->name = ltrim($path . '.' . $formData->name, '/');
            }
            if(empty($path) && $isSubDir) $formData->name = '/' . $formData->name;
            $result = $this->loadModel('gitfox')->request('/artifacts/groups', 'POST', array('artifactID' => (int)$artifactLibID, 'names' => $formData->name, 'format' => $artifactLib->type));
            if(dao::isError()) $this->sendError(dao::getError());

            if($result) $this->loadModel('action')->create('artifactDir', $result->id, 'created', '', $artifactLibID . '|' . $formData->name . '|group.' . $result->id);

            $response = array();
            $response['result']     = 'success';
            $response['message']    = $this->lang->saveSuccess;
            $response['closeModal'] = true;
            $response['callback']   = !$path || $path == '/' ? "window.expandNode();" : "window.expandNode('{$base64Path}');";
            $this->sendSuccess($response);
        }
        $this->view->title = $this->lang->artifact->createDir;
        $this->display();
    }

    /**
     * 编辑制品库目录。
     * Edit artifact repo directory.
     *
     * @access public
     * @return void
     */
    public function editDir(int $artifactLibID, string $path = '')
    {
        $artifactLib = $this->artifact->fetchByID($artifactLibID);
        $this->checkAccess($artifactLib->spaceID, $artifactLib->repoID);

        $currentPath = $path ? helper::safe64Decode($path) : '';
        $parentPath  = $currentPath ? $this->artifact->parseDirname($currentPath) : '/';
        if($parentPath === '' || $parentPath === '.') $parentPath = '/';
        $parentNode = $this->artifactZen->getNodeByPath($artifactLib, $parentPath);

        $artifactLibs = $this->artifactZen->getArtifactLibPickerItems($artifactLib->scope, $artifactLib->type, $this->spaces, $this->repos);
        if($_POST)
        {
            $node = $this->artifactZen->getNodeByPath($artifactLib, $currentPath);
            if(empty($node)) return $this->sendError($this->lang->fail);

            $formData = form::data($this->config->artifact->form->editDir)->get();
            if(!preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9\-_]+$/u', $formData->name)) return $this->sendError(array('name' => $this->lang->artifact->notice->dirNameFormatError));
            if(mb_strlen($formData->name) > 15) return $this->sendError(array('name' => $this->lang->artifact->notice->dirNameTooLong));

            $targetGroupID = $formData->parent == '/' ? 0 : explode('.', $formData->parent)[1];
            $params = array();
            $params['entityID']         = $node->entityID;
            $params['newName']          = $formData->name;
            $params['targetArtifactID'] = (int)$formData->artifactID;
            $params['targetGroupID']    = (int)$targetGroupID;
            $result = $this->loadModel('gitfox')->request('/artifacts/entities/relocate', 'POST', $params);
            if(dao::isError()) $this->sendError(dao::getError());

            if($result)
            {
                $dirID = empty(explode('.', $node->entityID)[1]) ? 0 : explode('.', $node->entityID)[1];
                $this->loadModel('action')->create('artifactDir', (int)$dirID, 'edited', '', $artifactLibID . '|' . $currentPath . '|' . $node->entityID);
            }

            $response = array();
            $response['result']     = 'success';
            $response['message']    = $this->lang->saveSuccess;
            $response['closeModal'] = true;

            $currentParent = empty($parentNode) || $parentPath == '/' ? '/' : $parentNode->entityID;
            $isSameParent  = $formData->parent === $currentParent;
            $isSameRepo    = (int)$formData->artifactID === (int)$artifactLibID;

            $base64Path = !$parentPath || $parentPath == '/' ? '' : helper::safe64Encode($parentPath);
            $response['callback'] = $isSameRepo && $isSameParent && $base64Path ? "window.expandNode('{$base64Path}');" : "window.expandNode();";
            $this->sendSuccess($response);
        }

        $this->view->title              = $this->lang->artifact->editDir;
        $this->view->artifactLibs       = $artifactLibs;
        $this->view->dirName            = $currentPath ? ltrim(baseName($currentPath), '/') : '';
        $this->view->artifactLib        = $artifactLib;
        $this->view->currentPath        = $currentPath;
        $this->view->currentPathEncoded = $path;
        $this->view->parentPath         = empty($parentNode) || $parentPath == '/' ? '/' : $parentNode->entityID;
        $this->display();
    }

    /**
     * 编辑制品。
     * Edit artifact.
     *
     * @param  int $assetID
     * @access public
     * @return void
     */
    public function editArtifact(int $assetID, int $artifactLibID)
    {
        $artifactLib = $this->artifact->fetchByID($artifactLibID);
        $this->checkAccess($artifactLib->spaceID, $artifactLib->repoID);

        $asset = $this->loadModel('gitfox')->request('/artifacts/assets/' . $assetID);

        if($_POST)
        {
            $formData = form::data($this->config->artifact->form->editArtifact)->get();
            if(preg_match('/[\\/:*?"<>|]/', $formData->name)) return $this->sendError(array('name' => $this->lang->artifact->notice->assetNameFormatError));

            $param = array();
            $param['entityID'] = 'asset.' . $assetID;
            $param['newName']  = $formData->name;

            $result = $this->gitfox->request('/artifacts/entities/relocate', 'POST', $param);
            if(dao::isError()) $this->sendError(dao::getError());

            if($result && $asset && !empty($asset->path))
            {
                $oldName = !empty($asset->metadata) && !empty($asset->metadata->name) ? $asset->metadata->name : basename($asset->path);
                $extra   = sprintf($this->lang->artifact->actionComment->edited, $oldName, $formData->name);
                $this->loadModel('action')->create('artifactAsset', $assetID, 'editedasset', '', "{$artifactLibID}|{$extra}");
                if($this->session->artifactLibViewLink && $asset->metadata->group)
                {
                    $oldPath = helper::safe64Encode('/' . $asset->metadata->group . '/' . $oldName);
                    $newPath = helper::safe64Encode('/' . $asset->metadata->group . '/' . $formData->name);
                    $link = str_replace($oldPath, $newPath, $this->session->artifactLibViewLink);
                }
            }
            if(dao::isError()) $this->sendError(dao::getError());
            $response = array();
            $response['result']     = 'success';
            $response['message']    = $this->lang->saveSuccess;
            $response['closeModal'] = true;
            if(!empty($link) && $link != $this->session->artifactLibViewLink)
            {

                $response['locate'] = $link;
            }
            else
            {
                $response['callback'] = "window.expandNode();";
            }
            $this->sendSuccess($response);
        }

        $this->view->title = $this->lang->artifact->editArtifact;
        $this->view->asset = $asset;
        $this->display();
    }

    /**
     * 下载制品。
     * Download artifact.
     *
     * @param  int $assetID
     * @param  int $artifactLibID
     * @access public
     * @return void
     */
    public function downloadArtifact(int $assetID, int $artifactLibID = 0)
    {
        $artifactLib = $this->artifact->fetchByID($artifactLibID);
        $this->checkAccess($artifactLib->spaceID, $artifactLib->repoID);

        $asset = $this->loadModel('gitfox')->request('/artifacts/assets/' . $assetID);
        if(dao::isError()) $this->sendError(dao::getError());
        if(empty($asset)) return $this->sendError($this->lang->notFound);

        $fileName = '';
        if(!empty($asset->path)) $fileName = basename($asset->path);
        if(!$fileName && !empty($asset->metadata) && !empty($asset->metadata->name)) $fileName = $asset->metadata->name;
        if(!$fileName && !empty($asset->name)) $fileName = $asset->name;
        if(!$fileName) $fileName = 'artifact-' . $assetID;

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        if(!$apiRoot) return $this->sendError($this->lang->artifact->notice->noArtifact);

        $tempDownloadDir = $this->app->getTmpRoot() . 'cache/artifact/';
        if(!is_dir($tempDownloadDir)) mkdir($tempDownloadDir, 0755, true);

        $url      = sprintf($apiRoot->url, "/artifacts/assets/{$assetID}/download");
        $context  = stream_context_create(array('http' => array('method' => 'GET', 'header' => implode("\r\n", $apiRoot->header), 'timeout' => 1000)));
        $filePath = $tempDownloadDir . $fileName;
        file_put_contents($filePath, file_get_contents($url, false, $context));

        $downArtifact = file_get_contents($filePath);
        unlink($filePath);
        if($downArtifact) $this->loadModel('action')->create('artifactAsset', $assetID, 'downloaded', '', $artifactLibID . '|' . $fileName);

        $this->loadModel('file')->sendDownHeader($fileName, $extension, $downArtifact);
    }

    /**
     * 查看制品历史记录。
     * View artifact history.
     *
     * @param  int $id
     * @param  int $artifactLibID
     * @access public
     * @return void
     */
    public function history(int $id, int $artifactLibID)
    {
        $artifactLib = $this->artifact->fetchByID($artifactLibID);
        $this->checkAccess($artifactLib->spaceID, $artifactLib->repoID);

        $asset = $this->loadModel('gitfox')->request('/artifacts/assets/' . $id);

        $this->view->title   = $asset->path . ' - ' . $this->lang->artifact->history;
        $this->view->assetID = $id;
        $this->display();
    }

    /**
     * 移动制品。
     * Move artifact.
     *
     * @param  int $assetID
     * @param  int $artifactLibID
     * @access public
     * @return void
     */
    public function moveArtifact(int $assetID, int $artifactLibID)
    {
        $artifactLib = $this->artifact->fetchByID($artifactLibID);
        $this->checkAccess($artifactLib->spaceID, $artifactLib->repoID);

        $asset = $this->loadModel('gitfox')->request('/artifacts/assets/' . $assetID);
        if(dao::isError()) $this->sendError(dao::getError());

        $parentID = '/';
        if(!empty($asset->metadata) && !empty($asset->metadata->group))
        {
            $parentPath = '/' . $asset->metadata->group;
            $parentNode = $this->artifactZen->getNodeByPath($artifactLib, $parentPath);
            $parentID   = $parentNode->entityID;
        }

        if($_POST)
        {
            $formData = form::data($this->config->artifact->form->moveArtifact)->get();
            if($formData->parent == '/') return $this->sendError(array('parent' => $this->lang->artifact->notice->rootNotAllowed));

            $fromRepo = $artifactLib->name;
            $fromPath = !empty($asset->metadata) && !empty($asset->metadata->group) ? $asset->metadata->group : $this->artifact->parseDirname($asset->path);

            $targetArtifactLib = $this->artifact->fetchByID((int)$formData->artifactID);
            $toRepo            = empty($targetArtifactLib) ? '' : $targetArtifactLib->name;

            $targetGroupID = $formData->parent == '/' ? 0 : explode('.', $formData->parent)[1];
            $params = array();
            $params['entityID']         = 'asset.' . $assetID;
            $params['targetArtifactID'] = $formData->artifactID;
            $params['targetGroupID']    = (int)$targetGroupID;

            $result = $this->gitfox->request('/artifacts/entities/relocate', 'POST', $params);
            if(dao::isError()) $this->sendError(dao::getError());

            if($result && $asset && !empty($asset->path))
            {
                $movedAsset = $this->gitfox->request('/artifacts/assets/' . $assetID);
                if(dao::isError()) $this->sendError(dao::getError());

                $toPath = !empty($movedAsset->metadata) && !empty($movedAsset->metadata->group) ? $movedAsset->metadata->group : (!empty($movedAsset->path) ? $this->artifact->parseDirname($movedAsset->path) : '/');
                $extra  = $artifactLibID . '|' . sprintf($this->lang->artifact->actionComment->moved, $fromRepo, $fromPath, $toRepo, $toPath);
                $this->loadModel('action')->create('artifactAsset', $assetID, 'movedasset', '', $extra);
            }
            if(dao::isError()) $this->sendError(dao::getError());

            $response = array();
            $response['result']     = 'success';
            $response['message']    = $this->lang->saveSuccess;
            $response['closeModal'] = true;
            $response['callback']   = "window.expandNode();";
            $this->sendSuccess($response);
        }

        $this->view->title              = $this->lang->artifact->moveArtifact;
        $this->view->asset              = $asset;
        $this->view->artifactLib        = $artifactLib;
        $this->view->artifactLibs       = $this->artifactZen->getArtifactLibPickerItems($artifactLib->scope, $artifactLib->type, $this->spaces, $this->repos);
        $this->view->currentPathEncoded = '';
        $this->view->parentPath         = $parentID;
        $this->display();
    }

    /**
     * 获取编辑目录页所属上级选项。
     * Get parent directory options for edit dir page.
     *
     * @param  int    $artifactLibID
     * @param  string $path
     * @access public
     * @return void
     */
    public function ajaxGetDirParentItems(int $artifactLibID, string $path = '')
    {
        $artifactLib = $this->artifact->fetchByID($artifactLibID);
        $path        = helper::safe64Decode($path);
        return print(json_encode($this->artifactZen->getParentPickerItems($artifactLib, $path)));
    }

    /**
     * 获取目录树.
     * Get directory tree.
     *
     * @param  int $artifactLibID
     * @param  string $path
     * @param  string $selectPath
     * @param  int $spaceID
     * @param  int $repoID
     * @param  string $type
     * @access public
     * @return void
     */
    public function ajaxGetFolders(int $artifactLibID, string $path = '', string $selectPath = '', int $spaceID = 0, int $repoID = 0, string $type = 'space', int $leaf = 0)
    {
        $artifactLib = $this->artifact->fetchByID($artifactLibID);
        $path        = helper::safe64Decode($path);
        $selectPath  = helper::safe64Decode($selectPath);
        return print(json_encode($this->artifactZen->getArtifactLibTreeData($artifactLib, $path, $selectPath, $spaceID, $repoID, $type, $leaf)));
    }

    /**
     * 上传制品.
     * Upload artifact.
     *
     * @param  int $artifactLibID
     * @param  string $path
     * @access public
     * @return void
     */
    public function uploadArtifact(int $artifactLibID, string $path = '')
    {
        $this->config->file->dangers = '';
        $artifactLib = $this->artifact->fetchByID($artifactLibID);
        $this->checkAccess($artifactLib->spaceID, $artifactLib->repoID);

        $originalPath = $path;
        if(!empty($_FILES))
        {
            set_time_limit(0);

            $response = array();
            $response['result']     = 'success';
            $response['message']    = $this->lang->saveSuccess;
            $response['closeModal'] = true;
            $response['callback']   = "window.expandNode('{$originalPath}');";

            $path = helper::safe64Decode($path);

            $file = $_FILES['file'];
            $result = $this->artifact->uploadArtifact($artifactLibID, $file, $path);
            if(dao::isError()) $this->sendError(dao::getError());

            if($result && !empty($result[0]) && !empty($result[0]->Object))
            {
                $asset = $result[0]->Object;
                $this->loadModel('action')->create('artifactAsset', $asset->id, 'uploaded', '', $artifactLibID . '|' . $asset->path);
            }
            $this->sendSuccess($response);

        }
        $this->view->title = $this->lang->artifact->uploadArtifact;
        $this->display();
    }

    /**
     * 删除制品库目录。
     * Delete artifact repo directory.
     *
     * @param  int $artifactLibID
     * @param  string $entityID
     * @param  string $path
     * @access public
     * @return void
     */
    public function deleteDir(int $artifactLibID, string $entityID, string $path = '')
    {
        $artifactLib = $this->artifact->fetchByID($artifactLibID);
        $this->checkAccess($artifactLib->spaceID, $artifactLib->repoID);

        $result = $this->loadModel('gitfox')->request('/artifacts/entities', 'DELETE', array('entityIDs' => array($entityID)));
        if(dao::isError())
        {
            $error = dao::getError();
            $this->sendError(!empty($error['apiMessage']) ? $error['apiMessage'] : $error);
        }

        $path       = helper::safe64Decode($path);
        $parentPath = $this->artifact->parseDirname($path);
        $selectPath = $parentPath == '/' ? '' : helper::safe64Encode($parentPath);
        if($result)
        {
            list($type, $id) = explode('.', $entityID);
            $this->loadModel('action')->create('artifactDir', (int)$id, 'deleted', $artifactLibID . '|' . $path . '|' . $entityID, ACTIONMODEL::CAN_UNDELETED);
        }

        $viewURL = $this->createLink('artifact', 'view', "artifactID={$artifactLibID}&spaceID={$artifactLib->spaceID}&repoID={$artifactLib->repoID}&type={$artifactLib->scope}&selectPath={$selectPath}");
        return $this->send(array('result' => 'success', 'message' => $this->lang->deleteSuccess, 'closeModal' => true, 'locate' => $viewURL));
    }

    /**
     * 删除制品.
     * Delete artifact.
     *
     * @param  int $artifactLibID
     * @param  int $assetID
     * @param  int $artifactLibID
     * @access public
     * @return void
     */
    public function deleteArtifact(int $assetID, int $artifactLibID = 0)
    {
        $artifactLib = $this->artifact->fetchByID($artifactLibID);
        $this->checkAccess($artifactLib->spaceID, $artifactLib->repoID);

        $asset = $this->loadModel('gitfox')->request('/artifacts/assets/' . $assetID);
        if(dao::isError())
        {
            $error = dao::getError();
            $this->sendError(!empty($error['apiMessage']) ? $error['apiMessage'] : $error);
        }

        $entityID = 'asset.' . $assetID;
        $result   = $this->gitfox->request('/artifacts/entities', 'DELETE', array('entityIDs' => array($entityID)));
        if(dao::isError()) $this->sendError(dao::getError());

        if($result && $asset && !empty($asset->path))
        {
            $asset->path = $asset->format == 'container' ? $asset->metadata->image . ':' . $asset->metadata->version : $asset->path;
            $this->loadModel('action')->create('artifactAsset', $assetID, 'deleted', $asset->path, ACTIONMODEL::CAN_UNDELETED);
        }
        if(dao::isError()) $this->sendError(dao::getError());
        $response = array();
        $response['result']     = 'success';
        $response['message']    = $this->lang->deleteSuccess;
        $response['closeModal'] = true;
        $response['callback']   = "window.expandNode();";
        $this->sendSuccess($response);
    }

    /**
     * 批量删除制品。
     * Batch delete artifacts.
     *
     * @access public
     * @return void
     */
    public function ajaxBatchDeleteArtifact(int $artifactLibID)
    {
        if(!common::hasPriv('artifact', 'deleteArtifact')) return $this->sendError($this->lang->error->accessDenied);

        $assetIDList = $this->post->assetIDList;

        $artifactLib = $this->artifact->fetchByID((int)$artifactLibID);
        $this->checkAccess($artifactLib->spaceID, $artifactLib->repoID);

        $assetIDList = array_values(array_unique(array_filter(array_map('intval', $assetIDList))));

        $entityIDs = array();
        $assetList = array();
        foreach($assetIDList as $assetID)
        {
            $asset = $this->loadModel('gitfox')->request('/artifacts/assets/' . $assetID);
            if(dao::isError()) $this->sendError(dao::getError());
            if(empty($asset)) continue;

            $assetList[$assetID] = $asset;
            $entityIDs[] = 'asset.' . $assetID;
        }

        $result = $this->gitfox->request('/artifacts/entities', 'DELETE', array('entityIDs' => $entityIDs));
        if(dao::isError()) $this->sendError(dao::getError());

        if($result)
        {
            foreach($assetList as $assetID => $asset)
            {
                $asset->path = $asset->format == 'container' ? $asset->metadata->image . ':' . $asset->metadata->version : $asset->path;
                if(!empty($asset->path)) $this->loadModel('action')->create('artifactAsset', $assetID, 'deleted', $asset->path, ACTIONMODEL::CAN_UNDELETED);
            }
        }
        if(dao::isError()) $this->sendError(dao::getError());

        $response = array();
        $response['result']   = 'success';
        $response['message']  = $this->lang->deleteSuccess;
        $response['callback'] = "window.expandNode();";
        $this->sendSuccess($response);
    }

    /**
     * 复制制品拉取命令。
     * Copy artifact pull command.
     *
     * @param  int $assetID
     * @param  int $artifactLibID
     * @access public
     * @return void
     */
    public function copyCMD(int $assetID)
    {
        $asset   = $this->loadModel('gitfox')->request('/artifacts/assets/' . $assetID);
        $command = 'docker pull ' . $asset->link;

        $title = $this->lang->artifact->copyCMD;
        return $this->send(array('result' => 'success', 'message' => '', 'closeModal' => true, 'callback' => array('name' => 'showCommand', 'params' => array("$command", "$title"))));
    }
}
