<?php
/**
 * The get repo members priv entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      DaoGang Li <lidaogang@chandao.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class artifactmembersprivEntry extends baseEntry
{
    /**
     * GET method.
     *
     * @access public
     * @return string
     */
    public function get()
    {
        $header = getallheaders();
        $token  = isset($header['Authorization']) ? $header['Authorization'] : '';
        $entry  = $this->loadModel('entry')->getByCode('gitfox');

        if(empty($token) || empty($entry->key) || $token != $entry->key)
        {
            return $this->sendError(401, 'Unauthorized');
        }

        if(!isset($this->app->user))
        {
            $this->app->user          = new stdclass();
            $this->app->user->account = 'guest';
            $this->app->user->rights  = array(
                'rights' => array(),
                'acls'   => array()
            );
            $this->app->user->groups = array();
            $this->app->user->view   = array('products' => array(), 'projects' => array());
            $this->app->user->admin  = false;
        }

        $artifactLibID = $this->param('id');
        $artifactLib   = $this->loadModel('artifact')->fetchByID($artifactLibID);
        if(!$artifactLib) return $this->sendError(404, 'Artifact lib not found');

        $spaceID = (int)$artifactLib->spaceID;
        $repoID  = (int)$artifactLib->repoID;

        if(empty($spaceID) && !empty($repoID)) return $this->sendError(400, 'Invalid artifact lib: repo without space');

        $this->loadModel('user');
        $this->loadModel('repo');
        $this->loadModel('space');

        $members = array();
        if(empty($spaceID) && empty($repoID))
        {
            /* 公开制品库：所有有效用户均可访问。 */
            $members = $this->user->getPairs('noletter|noempty|nodeleted|noclosed');
        }
        elseif(!empty($spaceID) && empty($repoID))
        {
            /* 仅 space：直接取 space 成员。 */
            $space = $this->space->fetchByID($spaceID);
            if(!$space) return $this->sendError(404, 'Space not found');

            $spaceMembers      = $this->space->getSpaceMembers($space->id);
            $fakeRepo          = new stdclass();
            $fakeRepo->members = $space->acl == 'private' ? $spaceMembers : $this->user->getPairs('noletter|noempty|nodeleted|noclosed');
            $members           = $this->repo->getRepoMembers($fakeRepo);
        }
        else
        {
            /* space + repo：套 repo.acl 取 space 下该 repo 的成员。 */
            $repo = $this->repo->fetchByID($repoID);
            if(!$repo) return $this->sendError(404, 'Repo not found');

            $space = $this->space->fetchByID($repo->spaceID ? $repo->spaceID : $spaceID);
            if(!$space) return $this->sendError(404, 'Space not found');

            if($repo->acl == 'private')
            {
                $repo->members = $this->repo->getRepoUsers($repo->id);
            }
            else
            {
                $spaceMembers  = $this->space->getSpaceMembers($space->id);
                $repo->members = $space->acl == 'private' ? $spaceMembers : $this->user->getPairs('noletter|noempty|nodeleted|noclosed');
            }
            $members = $this->repo->getRepoMembers($repo);
        }

        $privs = array();
        foreach(array_keys($members) as $account)
        {
            $canCreateRepo = $this->user->hasRepoPrivByAccount($account, 'createRepo');
            $canEditRepo   = $this->user->hasRepoPrivByAccount($account, 'edit');
            $canUploadRepo   = $this->user->hasRepoPrivByAccount($account, 'uploadArtifact');
            $privs[$account] = array(
                'pull' => true,
                'push' => $canUploadRepo && ($canCreateRepo || $canEditRepo)
            );
        }

        return $this->send(200, $privs);
    }
}
