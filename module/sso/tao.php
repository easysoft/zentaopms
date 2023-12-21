<?php
declare(strict_types=1);
/**
 * The tao file of sso module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@easycorp.ltd>
 * @package     sso
 * @link        https://www.zentao.net
 */
class ssoTao extends ssoModel
{
    /**
     * Bind ranzhi user to zentao user.
     *
     * @param  object    $data
     * @access protected
     * @return object|false
     */
    protected function bindZTUser(object $data): object|false
    {
        if(empty($data->bindPassword))
        {
            dao::$errors[] = $this->lang->sso->bindNoPassword;
            return false;
        }

        $password = md5($data->bindPassword);
        $user = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($data->bindUser)->andWhere('password')->eq($password)->andWhere('deleted')->eq('0')->fetch();
        if(empty($user))
        {
            dao::$errors[] = $this->lang->sso->bindNoUser;
            return false;
        }

        $user->ranzhi = $this->session->ssoData->account;
        $this->dao->update(TABLE_USER)->set('ranzhi')->eq($user->ranzhi)->where('id')->eq($user->id)->exec();

        return $user;
    }

    /**
     * Add to zentao user.
     *
     * @param  object    $data
     * @access protected
     * @return object|false
     */
    protected function addZTUser(object $data): object|false
    {
        if(!$this->loadModel('user')->checkPassword()) return false;
        $user = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($data->account)->fetch();
        if($user)
        {
            dao::$errors[] = $this->lang->sso->bindHasAccount;
            return false;
        }

        $user = new stdclass();
        $user->account  = $data->account;
        $user->password = md5($data->password1);
        $user->realname = $data->realname;
        $user->gender   = isset($data->gender) ? $data->gender : '';
        $user->email    = $data->email;
        $user->ranzhi   = $this->session->ssoData->account;
        $user->role     = isset($data->role) ? $data->role : '';

        $this->dao->insert(TABLE_USER)->data($user)->autoCheck()
            ->batchCheck($this->config->user->create->requiredFields, 'notempty')
            ->check('account', 'unique')
            ->check('account', 'account')
            ->checkIF($user->email != false, 'email', 'email')
            ->exec();

        return $user;
    }
}

