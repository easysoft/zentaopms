<?php
declare(strict_types=1);
/**
 * The zen file of gitfox module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     gitfox
 * @link        https://www.zentao.net
 */
class gitfoxZen extends gitfox
{
    /**
     * 检查绑定用户是否重复。
     * Check bind user repeat.
     *
     * @param  array     $zentaoUsers
     * @param  array     $userPairs
     * @access protected
     * @return array
     */
    protected function checkUserRepeat(array $zentaoUsers, array $userPairs): array
    {
        $accountList = array();
        $repeatUsers = array();
        foreach($zentaoUsers as $openID => $user)
        {
            if(empty($user)) continue;
            if(isset($accountList[$user])) $repeatUsers[] = zget($userPairs, $user);
            $accountList[$user] = $openID;
        }

        if(count($repeatUsers)) return array('result' => 'fail', 'message' => sprintf($this->lang->gitfox->bindUserError, join(',', array_unique($repeatUsers))));
        return array('result' => 'success');
    }

    /**
     * 绑定用户。
     * Bind user.
     *
     * @param  int       $gitfoxID
     * @param  array     $users
     * @param  array     $gitfoxNames
     * @param  array     $zentaoUsers
     * @access protected
     * @return void
     */
    protected function bindUsers(int $gitfoxID, array $users, array $gitfoxNames, array $zentaoUsers): void
    {
        $user = new stdclass;
        $user->providerID   = $gitfoxID;
        $user->providerType = 'gitfox';

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
                $this->loadModel('action')->create('gitfoxuser', (int)$openID, 'unbind', '', sprintf($this->lang->gitfox->bindDynamic, $gitfoxNames[$openID], $zentaoUsers[$existAccount->account]->realname));
            }
            if(!$existAccount or $existAccount->account != $account)
            {
                if(!$account) continue;
                $user->account = $account;
                $user->openID  = $openID;
                $this->dao->insert(TABLE_OAUTH)->data($user)->exec();
                $this->loadModel('action')->create('gitfoxuser', (int)$openID, 'bind', '', sprintf($this->lang->gitfox->bindDynamic, $gitfoxNames[$openID], $zentaoUsers[$account]->realname));
            }
        }
    }
}

