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
     * 流水线列表。
     * Browse pipeline.
     *
     * @param  int    $space
     * @param  int    $repoID
     * @param  string $type
     * @access public
     * @return void
     */
    public function browse(int $space = 0, int $repoID = 0, string $type = 'space')
    {
        $this->commonAction($space, $repoID);
        $repo = $this->loadModel('repo')->fetchByID($repoID);
        $this->view->title        = $this->lang->artifact->common . $this->lang->hyphen . $this->lang->artifact->browse;
        $this->view->repo         = $repo;
        $this->view->repoID       = $repoID;
        $this->view->repoPairs    = $this->repo->getRepoPairs();
        $this->view->type         = $type;
        $this->view->artifactList = $this->artifact->getList($type == 'repo' && !empty($repo) ? $repo->spaceID : $space, $repoID, $type, 'createdDate_asc');

        $this->display();
    }

    /**
     * 浏览制品库制品。
     * Browse artifact repo.
     *
     * @param  int $artifactID
     * @param  string $selectPath
     * @access public
     * @return void
     */
    public function view(int $artifactID, int $spaceID = 0, int $repoID = 0, string $type = 'space', string $selectPath = '', int $isExpand = 0)
    {
        $selectPath = helper::safe64Decode($selectPath);

        $artifact = $this->artifact->fetchByID($artifactID);
        if(empty($artifact)) return print(js::error($this->lang->artifact->notice->noArtifact));

        $this->commonAction((int)$artifact->spaceID, (int)$artifact->repoID);
        $selectPathList = empty($selectPath) ? array() : explode('/', trim($selectPath, '/'));
        $breadCrumbs    = $this->artifactZen->getBreadCrumbs($artifact, $selectPathList, $spaceID, $repoID, $type);

        $selectNode = empty($selectPathList) ? false : new stdclass();
        foreach($selectPathList as $path)
        {
            $path = helper::safe64Encode('/' . $path);
            $selectNode->$path = true;
        }

        $repo      = $this->loadModel('repo')->fetchByID($repoID);
        $artifacts = $this->artifact->getList($type == 'repo' && !empty($repo) ? $repo->spaceID : $spaceID, $repoID, $type, 'createdDate_asc');

        $artifactList = array();
        foreach($artifacts as $artifactRepo)
        {
            $artifactList[] = array('text' => $artifactRepo->name, 'value' => $artifactRepo->id, 'keys' => $artifactRepo->name, 'url' => $this->createLink('artifact', 'view', "artifactID={$artifactRepo->id}&spaceID={$spaceID}&repoID={$repoID}&type={$type}"));
        }
        $node = $this->artifactZen->getNodeByPath($artifact, $selectPath);

        $this->view->title        = $artifact->name . $this->lang->hyphen . $this->lang->artifact->repoBrowser;
        $this->view->artifact     = $artifact;
        $this->view->browseLink   = $this->createLink('artifact', 'browse', "space={$spaceID}&repoID={$repoID}&type={$type}");
        $this->view->treeItems    = $this->artifactZen->getArtifactTreeData($artifact, '/', $selectPath, $spaceID, $repoID, $type);
        $this->view->selectNode   = $selectNode;
        $this->view->spaceID      = $spaceID;
        $this->view->repoID       = $repoID;
        $this->view->type         = $type;
        $this->view->repo         = $repo;
        $this->view->artifactList = empty($artifactList) ? array() : $artifactList;
        $this->view->breadCrumbs  = $breadCrumbs;
        $this->view->selectPath   = $selectPath ? helper::safe64Encode($selectPath) : '';
        $this->view->isExpand     = $isExpand;
        $this->view->assetList    = $this->artifact->getAssetListByNodeID(empty($node) ? 0 : $node->id, $artifactID);
        $this->view->node         = $node;

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
        if($_POST)
        {
            $repo = $this->loadModel('repo')->fetchByID($repoID);

            $type = $repoID ? 'repo' : 'space';

            $formData = form::data($this->config->artifact->form->create)
                ->add('createdBy', $this->app->user->account)
                ->add('repoID', $repoID)
                ->add('spaceID', $type == 'repo' && !empty($repo) ? $repo->spaceID : $space)
                ->add('type', $type)
                ->get();
            if(in_array($formData->format, array('container', 'helm')) && !preg_match('/[a-zA-Z0-9_\-\.]+$/', $formData->name))
            {
                return $this->sendError(array('name' => $this->lang->artifact->notice->nameNotSupportChinese));
            }

            $id = $this->artifact->create($formData, $type);
            if(dao::isError()) $this->sendError(dao::getError());

            $this->loadModel('action')->create('artifact', $id, 'created');
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
        $artifact = $this->artifact->fetchByID($id);

        if($_POST)
        {
            $formData = form::data($this->config->artifact->form->edit)
                ->add('editedBy', $this->app->user->account)
                ->get();

            if(in_array($artifact->format, array('container', 'helm')) && !preg_match('/[a-zA-Z0-9_\-\.]+$/', $formData->name))
            {
                return $this->sendError(array('name' => $this->lang->artifact->notice->nameNotSupportChinese));
            }

            $this->artifact->update($id, $formData);
            if(dao::isError()) $this->sendError(dao::getError());

            $this->loadModel('action')->create('artifact', (int)$id, 'edited');
            $this->sendSuccess(array('load' => true));
        }

        $this->view->title    = $this->lang->artifact->edit;
        $this->view->artifact = $artifact;
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
        $this->artifact->delete(TABLE_ARTIFACT, $id, 'artifact');
        if(dao::isError()) $this->sendError(dao::getError());

        $this->sendSuccess(array('load' => true));
    }

    /**
     * 创建制品库目录。
     * Create artifact repo directory.
     *
     * @param  int    $artifactID
     * @param  string $path
     * @param  int    $isSubDir
     * @access public
     * @return void
     */
    public function createDir(int $artifactID, string $path = '', int $isSubDir = 0)
    {
        if($_POST)
        {
            $artifact = $this->artifact->fetchByID($artifactID);

            $path = helper::safe64Decode($path);
            if(!$isSubDir) $path = dirname($path);
            $base64Path = $path ? helper::safe64Encode($path) : '';

            $formData = form::data($this->config->artifact->form->createDir)->get();
            if(!preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9\-_]+$/u', $formData->name)) return $this->sendError(array('name' => $this->lang->artifact->notice->dirNameFormatError));
            if($path)
            {
                $formData->name = ltrim($path . '.' . $formData->name, '/');
            }
            if(empty($path) && $isSubDir) $formData->name = '/' . $formData->name;
            $this->loadModel('gitfox')->request('/artifacts/groups', 'POST', array('artifactID' => (int)$artifactID, 'names' => $formData->name, 'format' => $artifact->format));
            if(dao::isError()) $this->sendError(dao::getError());

            $this->loadModel('action')->create('artifactGroup', $artifactID, 'createdDir', $formData->name);

            $response = array();
            $response['result']     = 'success';
            $response['message']    = $this->lang->saveSuccess;
            $response['closeModal'] = true;
            $response['callback']   = "window.expandNode('{$base64Path}');";
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
    public function editDir()
    {
        $this->display();
    }

    /**
     * 获取目录树.
     * Get directory tree.
     *
     * @param  int $artifactID
     * @param  string $path
     * @param  string $selectPath
     * @param  int $spaceID
     * @param  int $repoID
     * @param  string $type
     * @access public
     * @return void
     */
    public function ajaxGetFolders(int $artifactID, string $path = '', string $selectPath = '', int $spaceID = 0, int $repoID = 0, string $type = 'space')
    {
        $artifact   = $this->artifact->fetchByID($artifactID);
        $path       = helper::safe64Decode($path);
        $selectPath = helper::safe64Decode($selectPath);
        return print(json_encode($this->artifactZen->getArtifactTreeData($artifact, $path, $selectPath, $spaceID, $repoID, $type)));
    }

    /**
     * 上传制品.
     * Upload artifact.
     *
     * @param  int $artifactID
     * @param  string $path
     * @access public
     * @return void
     */
    public function uploadArtifact(int $artifactID, string $path = '')
    {
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
            $result = $this->artifact->uploadArtifact($artifactID, $file, $path);
            if(dao::isError()) $this->sendError(dao::getError());

            if($result && !empty($result[0]) && !empty($result[0]->Object))
            {
                $asset = $result[0]->Object;
                $this->loadModel('action')->create('artifactAsset', $asset->id, 'uploaded', $asset->path);
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
     * @param  int $artifactID
     * @param  string $entityID
     * @param  string $path
     * @access public
     * @return void
     */
    public function deleteDir(int $artifactID, string $entityID, string $path = '')
    {
        $result = $this->loadModel('gitfox')->request('/artifacts/entities', 'DELETE', array('entityIDs' => array($entityID)));
        if(dao::isError()) $this->sendError(dao::getError());


        $path       = helper::safe64Decode($path);
        $parentPath = dirname($path);
        $base64Path = $parentPath == '/' ? '' : helper::safe64Encode($parentPath);
        if($result) $this->loadModel('action')->create('artifactGroup', $artifactID, 'deletedDir', $path);

        $response = array();
        $response['result']     = 'success';
        $response['message']    = $this->lang->deleteSuccess;
        $response['closeModal'] = true;
        $response['callback']   = "window.expandNode('{$base64Path}');";
        $this->sendSuccess($response);
    }

    /**
     * 删除制品.
     * Delete artifact.
     *
     * @param  int $artifactID
     * @param  int $assetID
     * @access public
     * @return void
     */
    public function deleteArtifact(int $assetID)
    {
        $asset = $this->loadModel('gitfox')->request('/artifacts/assets/' . $assetID);
        if(dao::isError()) $this->sendError(dao::getError());

        $entityID = 'asset.' . $assetID;
        $result   = $this->loadModel('gitfox')->request('/artifacts/entities', 'DELETE', array('entityIDs' => array($entityID)));
        if(dao::isError()) $this->sendError(dao::getError());

        if($result && $asset && !empty($asset->path)) $this->loadModel('action')->create('artifactAsset', $assetID, 'deletedAsset', $asset->path);
        if(dao::isError()) $this->sendError(dao::getError());
        $response = array();
        $response['result']     = 'success';
        $response['message']    = $this->lang->deleteSuccess;
        $response['closeModal'] = true;
        $response['callback']   = "window.expandNode();";
        $this->sendSuccess($response);
    }
}
