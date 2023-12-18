<?php
declare(strict_types=1);
/**
 * The zen file of gitea module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     gitea
 * @link        https://www.zentao.net
 */
class giteaZen extends gitea
{
    /**
     * 检测token是否有效。
     * Check post token has admin permissions.
     *
     * @param  object    $gitea
     * @access protected
     * @return array|bool
     */
    protected function checkToken(object $gitea): array|bool
    {
        $this->dao->update('gitea')->data($gitea)->batchCheck($this->config->gitea->create->requiredFields, 'notempty');
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $result = $this->gitea->checkTokenAccess($gitea->url, $gitea->token);

        if($result === false) return array('result' => 'fail', 'message' => array('url' => array($this->lang->gitea->hostError)));
        if(!$result) return array('result' => 'fail', 'message' => array('token' => array($this->lang->gitea->tokenLimit)));

        return true;
    }

    /**
     * 根据账号，名字，邮箱获取gitea用户匹配的禅道用户。
     * Get matched zentao users by account, name, email.
     *
     * @param  int       $giteaID
     * @param  array     $giteaUsers
     * @access protected
     * @return array
     */
    protected function getMatchedUsers(int $giteaID, array $giteaUsers): array
    {
        $userList    = $this->loadModel('user')->getList('all', 'account,realname,email');
        $zentaoUsers = array();
        foreach($userList as $user) $zentaoUsers[$user->account] = $user;

        $matches = new stdclass();
        foreach($giteaUsers as $giteaUser)
        {
            foreach($zentaoUsers as $zentaoUser)
            {
                if($giteaUser->account == $zentaoUser->account)   $matches->accounts[$giteaUser->account][] = $zentaoUser->account;
                if($giteaUser->realname == $zentaoUser->realname) $matches->names[$giteaUser->realname][]   = $zentaoUser->account;
                if($giteaUser->email == $zentaoUser->email)       $matches->emails[$giteaUser->email][]     = $zentaoUser->account;
            }
        }

        $bindedUsers  = $this->loadModel('pipeline')->getUserBindedPairs($giteaID, 'gitea', 'openID,account');
        $matchedUsers = array();
        foreach($giteaUsers as $giteaUser)
        {
            if(isset($bindedUsers[$giteaUser->id]))
            {
                $giteaUser->zentaoAccount     = $bindedUsers[$giteaUser->id];
                $matchedUsers[$giteaUser->id] = $giteaUser;
                continue;
            }

            $matchedZentaoUsers = array();
            if(isset($matches->accounts[$giteaUser->account])) $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->accounts[$giteaUser->account]);
            if(isset($matches->emails[$giteaUser->email]))     $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->emails[$giteaUser->email]);
            if(isset($matches->names[$giteaUser->realname]))   $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->names[$giteaUser->realname]);

            $matchedZentaoUsers = array_unique($matchedZentaoUsers);
            if(count($matchedZentaoUsers) == 1)
            {
                $giteaUser->zentaoAccount     = current($matchedZentaoUsers);
                $matchedUsers[$giteaUser->id] = $giteaUser;
            }
        }

        return $matchedUsers;
    }
}
