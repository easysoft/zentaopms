<?php
/**
 * The get repo members priv entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ruogu Liu <liuruogu@chandao.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class repomembersprivEntry extends baseEntry
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
            $this->app->user = new stdclass();
            $this->app->user->account = 'guest';
            $this->app->user->rights  = array(
                'rights' => array(),
                'acls'   => array()
            );
            $this->app->user->groups = array();
            $this->app->user->view   = array('products' => array(), 'projects' => array());
            $this->app->user->admin  = false;
        }

        $repoID = $this->param('id');
        $repo   = $this->loadModel('repo')->fetchByID($repoID);
        if(!$repo) return $this->sendError(404, 'Repo not found');

        $space = $this->loadModel('space')->fetchByID($repo->spaceID);
        if(!$space) return $this->sendError(404, 'Space not found');

        $this->loadModel('user');
        if($repo->acl == 'private')
        {
            $repo->members = $this->repo->getRepoUsers($repo->id);
        }
        else
        {
            $spaceMembers  = $this->space->getSpaceMembers($space->id);
            $repo->members = $space->acl == 'private' ? $spaceMembers : $this->user->getPairs('noletter|noempty|nodeleted|noclosed');
        }

        $privs   = array();
        $members = $this->repo->getRepoMembers($repo);
        foreach(array_keys($members) as $account)
        {
            $canCreateRepo = $this->user->hasRepoPrivByAccount($account, 'createRepo');
            $canEditRepo   = $this->user->hasRepoPrivByAccount($account, 'edit');

            $privs[$account] = array(
                'pull' => true,
                'push' => $canCreateRepo || $canEditRepo
            );
        }

        return $this->send(200, $privs);
    }
}
