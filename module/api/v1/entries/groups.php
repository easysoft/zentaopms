<?php
/**
 * The groups entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class groupsEntry extends entry
{
    /**
     * GET method.
     *
     * @access public
     * @return string
     */
    public function get()
    {
        $groups     = $this->loadModel('group')->getList();
        $privsGroup = $this->dao->select('`group`,module, method')->from(TABLE_GROUPPRIV)->orderBy('module')->fetchGroup('group');
        $usersGroup = $this->dao->select('t1.`group`,t1.account,t2.realname')->from(TABLE_USERGROUP)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
            ->fetchGroup('group');

        foreach($groups as $i => $group)
        {
            if($group->acl)
            {
                $acl = json_decode($group->acl, true);
                $group->acl = array();
                $group->acl['views'] = $acl['views'];
            }
            $privs = zget($privsGroup, $group->id, array());
            $group->privs = array();
            foreach($privs as $priv) $group->privs[$priv->module][$priv->method] = true;

            $accounts = zget($usersGroup, $group->id, array());
            $group->accounts = array();
            foreach($accounts as $account) $group->accounts[$account->account] = $account->realname;

            $groups[$i] = $group;
        }

        return $this->send(200, $groups);
    }
}
