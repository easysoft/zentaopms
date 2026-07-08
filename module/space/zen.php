<?php
declare(strict_types=1);
/**
 * The zen file of space module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     space
 * @link        https://www.zentao.net
 */
class spaceZen extends space
{
    /**
     * 构建管理成员字段。
     * Build manage members fields.
     *
     * @param  int $spaceID
     * @access public
     * @return array
     */
    public function buildManageMembersFields(int $spaceID): array
    {
        $fields = $this->config->space->form->manageMembers;

        $group         = $this->loadModel('group')->getList(0, $spaceID);
        $spaceRepos    = $this->space->getReposBySpace($spaceID, 'private');
        //$artifactRepos = $this->space->getArtifactReposBySpace($spaceID);

        $fields['account']['options']      = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        $fields['role']['options']         = $this->lang->space->roleList;
        $fields['group']['options']        = empty($group)         ? array() : array_column($group, 'name', 'id');
        $fields['repo']['options']         = empty($spaceRepos)    ? array() : array_column($spaceRepos, 'name', 'id');
        //$fields['artifactrepo']['options'] = empty($artifactPairs) ? array() : array_column($artifactRepos, 'name', 'id');

        return $fields;
    }

    /**
     * 构建管理成员数据。
     * Build manage members data.
     *
     * @param  array $formData
     * @param  array $members
     * @access public
     * @return array
     */
    public function buildManageMembersData(array $formData, array $members): array
    {
        if(empty($formData)) return array();

        $data       = array();
        $newMembers = array();
        $oldMembers = array_keys($members);
        foreach($formData as $form)
        {
            $account = $form->account;
            $repo    = $form->repo;
            $group   = $form->group;
            if($form->role == 'member') $newMembers[] = $account;

            $oldMember = zget($members, $account, array());
            $oldGroup  = array_keys(zget($oldMember, 'group', array()));
            $oldRepo   = array_keys(zget($oldMember, 'repo', array()));
            foreach($group as $groupID) $data['group'][$groupID][$account] = $account;

            $delGroup = array_diff($oldGroup, $group);
            if(!empty($delGroup))
            {
                foreach($delGroup as $delGroupID)
                {
                    if(isset($data['group'][$delGroupID])) continue;
                    $data['group'][$delGroupID] = array();
                }
            }

            foreach($repo as $repoID) $data['repo'][$repoID][$account] = $account;

            $delRepo = array_diff($oldRepo, $repo);
            if(!empty($delRepo))
            {
                foreach($delRepo as $delRepoID)
                {
                    if(isset($data['repo'][$delRepoID])) continue;
                    $data['repo'][$delRepoID] = array();
                }
            }
            if(!empty($account) && !in_array($account, $oldMembers)) $data['space'][] = $account;
        }

        $delUsers = array_diff($oldMembers, $newMembers);
        if(!empty($delUsers))
        {
            foreach($delUsers as $delUser)
            {
                if(!empty($members[$delUser]) && $members[$delUser]->role == 'manager') continue;
                $data['delete']['group'][] = $delUser;
                $data['delete']['repo'][]  = $delUser;
                $data['delete']['space'][] = $delUser;
            }
        }
        return $data;
    }
}
