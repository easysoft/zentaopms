<?php
declare(strict_types=1);
/**
 * The zen file of gogs module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     gogs
 * @link        https://www.zentao.net
 */
class gogsZen extends gogs
{
    /**
     * 检查gogs服务器的token是否有效。
     * Check post token has admin permissions.
     *
     * @param  object    $gogs
     * @access protected
     * @return array|bool
     */
    protected function checkToken(object $gogs): array|bool
    {
        $this->dao->update('gogs')->data($gogs)->batchCheck($this->config->gogs->create->requiredFields, 'notempty');
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $result = $this->gogs->checkTokenAccess($gogs->url, $gogs->token);

        if($result === false) return array('result' => 'fail', 'message' => array('url' => array($this->lang->gogs->hostError)));
        if(!$result) return array('result' => 'fail', 'message' => array('token' => array($this->lang->gogs->tokenLimit)));

        return true;
    }

    /**
     * 根据账号，名字，邮箱获取gogs用户匹配的禅道用户。
     * Get matched zentao users by account, name, email.
     *
     * @param  int       $gogsID
     * @param  array     $gogsUsers
     * @access protected
     * @return array
     */
    protected function getMatchedUsers(int $gogsID, array $gogsUsers): array
    {
        $userList    = $this->loadModel('user')->getList('all', 'account,realname,email');
        $zentaoUsers = array();
        foreach($userList as $user) $zentaoUsers[$user->account] = $user;

        $matches = new stdclass();
        foreach($gogsUsers as $gogsUser)
        {
            foreach($zentaoUsers as $zentaoUser)
            {
                if($gogsUser->account == $zentaoUser->account)   $matches->accounts[$gogsUser->account][] = $zentaoUser->account;
                if($gogsUser->realname == $zentaoUser->realname) $matches->names[$gogsUser->realname][]   = $zentaoUser->account;
                if($gogsUser->email == $zentaoUser->email)       $matches->emails[$gogsUser->email][]     = $zentaoUser->account;
            }
        }

        $bindedUsers  = $this->loadModel('pipeline')->getUserBindedPairs($gogsID, 'gogs', 'openID,account');
        $matchedUsers = array();
        foreach($gogsUsers as $gogsUser)
        {
            if(isset($bindedUsers[$gogsUser->id]))
            {
                $gogsUser->zentaoAccount     = $bindedUsers[$gogsUser->id];
                $matchedUsers[$gogsUser->id] = $gogsUser;
                continue;
            }

            $matchedZentaoUsers = array();
            if(isset($matches->accounts[$gogsUser->account])) $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->accounts[$gogsUser->account]);
            if(isset($matches->emails[$gogsUser->email]))     $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->emails[$gogsUser->email]);
            if(isset($matches->names[$gogsUser->realname]))   $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->names[$gogsUser->realname]);

            $matchedZentaoUsers = array_unique($matchedZentaoUsers);
            if(count($matchedZentaoUsers) == 1)
            {
                $gogsUser->zentaoAccount     = current($matchedZentaoUsers);
                $matchedUsers[$gogsUser->id] = $gogsUser;
            }
        }

        return $matchedUsers;
    }
}
