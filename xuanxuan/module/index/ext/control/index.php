<?php
include '../../control.php';
class myIndex extends index
{
    /**
     * The index page of whole zentao system.
     *
     * @param  string $open
     * @access public
     * @return void
     */
    public function index($open = '')
    {
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'xuanxuan') != false)
        {
            $this->view->pageBodyClass = 'xxc-embed';
        }
        else
        {
            $this->loadModel('im');

            $xuanConfig      = new stdclass();
            $token           = $this->im->userGetAuthToken($this->app->user->id, 'zentaoweb');
            $clientUrl       = isset($this->config->webClientUrl) ? $this->config->webClientUrl : 'data/xuanxuan/web/';

            $xuanConfig->clientUrl        =  $clientUrl;
            $xuanConfig->server           = $this->im->getServer('zentao');
            $xuanConfig->account          = $this->app->user->account;
            $xuanConfig->authKey          = $token->token;
            $xuanConfig->debug            = $this->config->debug;

            $this->view->xuanConfig = $xuanConfig;
        }

        return parent::index($open);
    }
}
