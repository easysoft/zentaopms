<?php
/**
 * The tokens entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class tokensEntry extends baseEntry
{
    /**
     * POST method.
     *
     * @access public
     * @return string
     */
    public function post()
    {
        $account   = $this->request('account');
        $password  = $this->request('password');
        $addAction = $this->request('addAction', false);

        if($this->loadModel('user')->checkLocked($account)) return $this->sendError(400, sprintf($this->lang->user->loginLocked, $this->config->user->lockMinutes));

        $user = $this->user->identify($account, $password);
        if($user)
        {
            $this->user->login($user, $addAction);
            return $this->send(201, array('token' => session_id()));
        }
        else
        {
            $fails = $this->user->failPlus($account);
            $remainTimes = $this->config->user->failTimes - $fails;
            if($remainTimes <= 0)
            {
                return $this->sendError(400, sprintf($this->lang->user->loginLocked, $this->config->user->lockMinutes));
            }
            else if($remainTimes <= 3)
            {
                return $this->sendError(400, sprintf($this->lang->user->lockWarning, $remainTimes));
            }

            return $this->sendError(400, $this->lang->user->loginFailed);
        }

        $this->sendError(400, $this->app->lang->user->loginFailed);
    }
}
