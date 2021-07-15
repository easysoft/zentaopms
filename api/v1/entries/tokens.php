<?php
/**
 * 禅道API的tokens资源类
 * 版本V1
 *
 * The tokens entry point of zentaopms
 * Version 1
 */
class tokensEntry extends baseEntry 
{
    public function post()
    {
        $account  = $this->request('account');
        $password = $this->request('password');

        $user = $this->loadModel('user')->identify($account, $password);

        if($user)
        {
            $this->user->login($user);
            $this->send(201, array('token' => session_id()));
        }

        $this->sendError(400, $this->app->lang->user->loginFailed);
    }
}
