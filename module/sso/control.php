<?php
/**
 * The control file of sso module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     sso
 * @version     $Id: control.php 4460 2013-02-26 02:28:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class sso extends control
{
    /**
     * SSO login.
     * 
     * @param  string $type 
     * @access public
     * @return void
     */
    public function login($type = 'notify')
    {
        $referer = empty($_GET['referer']) ? '' : $this->get->referer;
        $locate  = empty($referer) ? '/' : base64_decode($referer);
        if($this->loadModel('user')->isLogon()) die($this->locate($locate));

        $this->app->loadConfig('sso');
        if(!$this->config->sso->turnon) die($this->locate($locate));

        $userIP = $this->server->remote_addr;
        $code   = $this->config->sso->code;
        $key    = $this->config->sso->key;
        if($type != 'return')
        {
            $token  = $this->get->token;
            $auth   = md5($code . $userIP . $token . $key);

            $callback = urlencode(common::getSysURL() . inlink('login', "type=return"));
            $location = $this->config->sso->addr;
            if(strpos($location, '&') !== false)
            {
                $location = rtrim($location, '&') . "&token=$token&auth=$auth&userIP=$userIP&callback=$callback&referer=$referer";
            }   
            else
            {   
                $location = rtrim($location, '?') . "?token=$token&auth=$auth&userIP=$userIP&callback=$callback&referer=$referer";
            }
            $this->locate($location);
        }

        if($this->get->status == 'success' and md5($this->get->data) == $this->get->md5)
        {
            $last = $this->server->request_time;
            $data = json_decode(base64_decode($this->get->data));

            $token = $data->token;
            if($data->auth == md5($code . $userIP . $token . $key))
            {
                $user = $this->loadModel('user')->getById($data->account);
                if(!$user) $this->locate($this->createLink('user', 'login', empty($referer) ? '' : "referer=$referer"));

                $this->user->cleanLocked($user->account);
                /* Authorize him and save to session. */
                $user->rights = $this->user->authorize($user->account);
                $user->groups = $this->user->getGroups($user->account);
                $this->dao->update(TABLE_USER)->set('visits = visits + 1')->set('ip')->eq($userIP)->set('last')->eq($last)->where('account')->eq($user->account)->exec();

                $user->last   = date(DT_DATETIME1, $last);
                $this->session->set('user', $user);
                $this->app->user = $this->session->user;
                $this->loadModel('action')->create('user', $user->id, 'login');
                die($this->locate($locate));
            }
        }
        $this->locate($this->createLink('user', 'login', empty($referer) ? '' : "referer=$referer"));
    }

    /**
     * SSO logout.
     * 
     * @param  string $type 
     * @access public
     * @return void
     */
    public function logout($type = 'notify')
    {
        if($type != 'return')
        {
            $code   = $this->config->sso->code;
            $userIP = $this->server->remote_addr;
            $token  = $this->get->token;
            $key    = $this->config->sso->key;
            $auth   = md5($code . $userIP . $token . $key);

            $callback = urlencode($common->getSysURL() . inlink('logout', "type=return"));
            $location = $this->config->sso->addr;
            if(strpos($location, '&') !== false)
            {
                $location = rtrim($location, '&') . "&token=$token&auth=$auth&userIP=$userIP&callback=$callback";
            }   
            else
            {   
                $location = rtrim($location, '?') . "?token=$token&auth=$auth&userIP=$userIP&callback=$callback";
            }
            $this->locate($location);
        }

        if($this->get->status == 'success')
        {
            session_destroy();
            setcookie('za', false);
            setcookie('zp', false);
            $this->locate($this->createLink('user', 'login'));
        }
        $this->locate($this->createLink('user', 'logout'));
    }

    /**
     * Ajax set config.
     * 
     * @access public
     * @return void
     */
    public function ajaxSetConfig()
    {
        if($_POST)
        {
            $ssoConfig = new stdclass();
            $ssoConfig->turnon = 1;
            $ssoConfig->addr   = $this->post->addr;
            $ssoConfig->code   = trim($this->post->code);
            $ssoConfig->key    = trim($this->post->key);

            $this->loadModel('setting')->setItems('system.sso', $ssoConfig);
            if(dao::isError()) die('fail');
            die('success');
        }
    }
}
