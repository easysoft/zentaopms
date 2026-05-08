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
    public function view(int $artifactID, string $selectPath = '')
    {
        $selectPath = helper::safe64Decode($selectPath);

        $artifact = $this->artifact->fetchByID($artifactID);
        if(empty($artifact)) return print(js::error($this->lang->artifact->notice->noArtifact));

        $this->commonAction((int)$artifact->spaceID, (int)$artifact->repoID);
        $selectPathList = explode('/', trim($selectPath, '/'));

        $selectNode = new stdclass();
        foreach($selectPathList as $path)
        {
            $path = helper::safe64Encode('/' . $path);
            $selectNode->$path = true;
        }

        $this->view->title      = $artifact->name . $this->lang->hyphen . $this->lang->artifact->repoBrowser;
        $this->view->artifact   = $artifact;
        $this->view->browseLink = $this->createLink('artifact', 'browse', "space={$artifact->spaceID}&repoID={$artifact->repoID}&type={$artifact->type}");
        $this->view->treeItems  = $this->artifact->getArtifactTreeData($artifact, '/', $selectPath);
        $this->view->selectNode = $selectNode;

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
        if($_POST)
        {
            $formData = form::data($this->config->artifact->form->edit)
                ->add('editedBy', $this->app->user->account)
                ->get();

            $this->artifact->update($id, $formData);
            if(dao::isError()) $this->sendError(dao::getError());

            $this->loadModel('action')->create('artifact', (int)$id, 'edited');
            $this->sendSuccess(array('load' => true));
        }

        $this->view->title    = $this->lang->artifact->edit;
        $this->view->artifact = $this->artifact->fetchByID($id);
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
     * @access public
     * @return void
     */
    public function createDir(int $artifactID, string $path = '')
    {
        if($_POST)
        {
            $formData = form::data($this->config->artifact->form->createDir)->get();
            $this->loadModel('gitfox')->request('/artifacts/groups', 'POST', array('artifactID' => (int)$artifactID, 'names' => $formData->name, 'format' => $formData->format));
            if(dao::isError()) $this->sendError(dao::getError());

            $this->loadModel('action')->create('artifact', $artifactID, 'createdDir', $formData->name);
            $this->sendSuccess(array('load' => true));
        }
        $this->view->title = $this->lang->artifact->createDir;
        $this->display();
    }

    /**
     * 获取目录树.
     * Get directory tree.
     *
     * @param  int $artifactID
     * @param  string $path
     * @param  string $selectPath
     * @access public
     * @return void
     */
    public function ajaxGetFolders(int $artifactID, string $path = '', string $selectPath = '')
    {
        $artifact   = $this->artifact->fetchByID($artifactID);
        $path       = helper::safe64Decode($path);
        $selectPath = helper::safe64Decode($selectPath);
        return print(json_encode($this->artifact->getArtifactTreeData($artifact, $path, $selectPath)));
    }
}
