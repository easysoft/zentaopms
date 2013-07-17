<?php
class sso extends control
{
    public function auth($key)
    {
        if(!empty($_POST) or (isset($_GET['account']) and isset($_GET['password'])))
        {
            $account  = '';
            $password = '';
            if($this->post->account)  $account  = $this->post->account;
            if($this->get->account)   $account  = $this->get->account;
            if($this->post->password) $password = $this->post->password;
            if($this->get->password)  $password = $this->get->password;
        }

        $user = $this->loadModel('user')->identify($account, $password);
        if($user)
        {
            $response['status'] = 'success';
            $response['data']   = json_encode($user);
            $this->send($response);
        }

        $response['status'] = 'fail';
        $response['data']   = 'auth failed.';
        $this->send($response);
    }

    public function depts($key)
    {
    }

    public function users($key)
    {
    }
}
