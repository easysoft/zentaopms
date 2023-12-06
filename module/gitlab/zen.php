<?php
declare(strict_types=1);
/**
 * The zen file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Zeng<zenggang@easycorp.ltd>
 * @package     gitlab
 * @link        https://www.zentao.net
 */
class gitlabZen extends gitlab
{
    /**
     * 检查绑定用户是否重复。
     * Check bind user repeat.
     *
     * @param  array     $zentaoUsers
     * @access protected
     * @return array
     */
    protected function checkUserRepeat(array $zentaoUsers): array
    {
        $accountList = array();
        $repeatUsers = array();
        foreach($zentaoUsers as $openID => $user)
        {
            if(empty($user)) continue;
            if(isset($accountList[$user])) $repeatUsers[] = zget($userPairs, $user);
            $accountList[$user] = $openID;
        }

        if(count($repeatUsers)) return array('result' => 'fail', 'message' => sprintf($this->lang->gitlab->bindUserError, join(',', array_unique($repeatUsers))));
        return array('result' => 'success');
    }

    /**
     * 绑定用户。
     * Bind user.
     *
     * @param  int       $gitlabID
     * @param  array     $users
     * @param  array     $gitlabNames
     * @param  array     $zentaoUsers
     * @access protected
     * @return void
     */
    protected function bindUsers(int $gitlabID, array $users, array $gitlabNames, array $zentaoUsers): void
    {
        $user = new stdclass;
        $user->providerID   = $gitlabID;
        $user->providerType = 'gitlab';

        $oldUsers = $this->dao->select('*')->from(TABLE_OAUTH)->where('providerType')->eq($user->providerType)->andWhere('providerID')->eq($user->providerID)->fetchAll('openID');
        foreach($users as $openID => $account)
        {
            $existAccount = isset($oldUsers[$openID]) ? $oldUsers[$openID] : '';

            if($existAccount and $existAccount->account != $account)
            {
                $this->dao->delete()
                    ->from(TABLE_OAUTH)
                    ->where('openID')->eq($openID)
                    ->andWhere('providerType')->eq($user->providerType)
                    ->andWhere('providerID')->eq($user->providerID)
                    ->exec();
                $this->loadModel('action')->create('gitlabuser', $openID, 'unbind', '', sprintf($this->lang->gitlab->bindDynamic, $gitlabNames[$openID], $zentaoUsers[$existAccount->account]->realname));
            }
            if(!$existAccount or $existAccount->account != $account)
            {
                if(!$account) continue;
                $user->account = $account;
                $user->openID  = $openID;
                $this->dao->insert(TABLE_OAUTH)->data($user)->exec();
                $this->loadModel('action')->create('gitlabuser', $openID, 'bind', '', sprintf($this->lang->gitlab->bindDynamic, $gitlabNames[$openID], $zentaoUsers[$account]->realname));
            }
        }
    }

    /**
     * 记录webhook日志。
     * Record webhook logs.
     *
     * @param  string    $input
     * @param  object    $result
     * @access protected
     * @return void
     */
    protected function recordWebhookLogs(string $input, object $result): void
    {
        $logFile = $this->app->getLogRoot() . 'webhook.'. date('Ymd') . '.log.php';
        if(!file_exists($logFile)) file_put_contents($logFile, '<?php die(); ?' . '>');

        $fh = @fopen($logFile, 'a');
        if($fh)
        {
            fwrite($fh, date('Ymd H:i:s') . ": " . $this->app->getURI() . "\n");
            fwrite($fh, "JSON: \n  " . $input . "\n");
            fwrite($fh, "Parsed object: {$result->issue->objectType} :\n  " . print_r($result->object, true) . "\n");
            fclose($fh);
        }
    }

    /**
     * 获取gitlab组新增、删除、更新成员数据。
     * Get group added、deleted、updated member data.
     *
     * @param  array     $currentMembers
     * @param  array     $newMembers
     * @access protected
     * @return array
     */
    protected function getGroupMemberData(array $currentMembers, array $newMembers): array
    {
        /* Get the updated,deleted data. */
        $addedMembers = $deletedMembers = $updatedMembers = array();
        foreach($currentMembers as $currentMember)
        {
            $memberID = $currentMember->id;
            if(empty($newMembers[$memberID]))
            {
                $deletedMembers[] = $memberID;
            }
            else
            {
                if($newMembers[$memberID]->access_level != $currentMember->access_level or $newMembers[$memberID]->expires_at != $currentMember->expires_at)
                {
                    $updatedData = new stdClass();
                    $updatedData->user_id      = $memberID;
                    $updatedData->access_level = $newMembers[$memberID]->access_level;
                    $updatedData->expires_at   = $newMembers[$memberID]->expires_at;
                    $updatedMembers[] = $updatedData;
                }
            }
        }
        /* Get the added data. */
        foreach($newMembers as $id => $newMember)
        {
            $exist = false;
            foreach($currentMembers as $currentMember)
            {
                if($currentMember->id == $id)
                {
                    $exist = true;
                    break;
                }
            }
            if($exist == false)
            {
                $addedData = new stdClass();
                $addedData->user_id      = $id;
                $addedData->access_level = $newMembers[$id]->access_level;
                $addedData->expires_at   = $newMembers[$id]->expires_at;
                $addedMembers[] = $addedData;
            }
        }

        return array($addedMembers, $deletedMembers, $updatedMembers);
    }

    /**
     * 获取项目成员数据。
     * Get project member data.
     *
     * @param  array     $gitlabCurrentMembers
     * @param  array     $newGitlabMembers
     * @param  array     $bindedUsers
     * @param  array     $accounts
     * @param  array     $originalUsers
     * @access protected
     * @return array
     */
    protected function getProjectMemberData(array $gitlabCurrentMembers, array $newGitlabMembers, array $bindedUsers, array $accounts, array $originalUsers): array
    {
        $addedMembers = $updatedMembers = $deletedMembers = array();
        /* Get the updated data. */
        foreach($gitlabCurrentMembers as $gitlabCurrentMember)
        {
            $memberID = isset($gitlabCurrentMember->id) ? $gitlabCurrentMember->id : 0;
            if(!isset($newGitlabMembers[$memberID])) continue;
            if($newGitlabMembers[$memberID]->access_level != $gitlabCurrentMember->access_level or $newGitlabMembers[$memberID]->expires_at != $gitlabCurrentMember->expires_at)
            {
                $updatedData = new stdClass();
                $updatedData->user_id      = $memberID;
                $updatedData->access_level = $newGitlabMembers[$memberID]->access_level;
                $updatedData->expires_at   = $newGitlabMembers[$memberID]->expires_at;
                $updatedMembers[] = $updatedData;
            }
        }
        /* Get the added data. */
        foreach($newGitlabMembers as $id => $newMember)
        {
            $exist = false;
            foreach($gitlabCurrentMembers as $gitlabCurrentMember)
            {
                if($gitlabCurrentMember->id == $id)
                {
                    $exist = true;
                    break;
                }
            }
            if($exist == false)
            {
                $addedData = new stdClass();
                $addedData->user_id      = $id;
                $addedData->access_level = $newGitlabMembers[$id]->access_level;
                $addedData->expires_at   = $newGitlabMembers[$id]->expires_at;
                $addedMembers[] = $addedData;
            }
        }
        /* Get the deleted data. */
        foreach($originalUsers as $user)
        {
            if(!in_array($user, $accounts) and isset($bindedUsers[$user]))
            {
                $exist = false;
                foreach($gitlabCurrentMembers as $gitlabCurrentMember)
                {
                    if($gitlabCurrentMember->id == $bindedUsers[$user])
                    {
                        $exist            = true;
                        $deletedMembers[] = $gitlabCurrentMember->id;
                        break;
                    }
                }
            }
        }

        return array($addedMembers, $deletedMembers, $updatedMembers);
    }
}
