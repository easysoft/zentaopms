<?php
class sso extends control
{
    /**
     * Browse all auths.
     * 
     * @access public
     * @return void
     */
    public function browse()
    {
        $this->view->title      = $this->lang->sso->common . $this->lang->colon . $this->lang->sso->browse;
        $this->view->position[] = $this->lang->sso->common;
        $this->view->position[] = $this->lang->sso->browse;
        $this->view->auths      = $this->sso->getAuths();
        $this->display();
    }

    /**
     * Create auth.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if(!empty($_POST))
        {
            if(!$this->post->title) die(js::alert($this->lang->sso->error->title));
            if(!$this->post->code)  die(js::alert($this->lang->sso->error->code));
            if(!$this->post->ip)    die(js::alert($this->lang->sso->error->ip));

            $this->sso->createAuth();
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate(inlink('browse'), 'parent'));
        }
        $this->view->title      = $this->lang->sso->common . $this->lang->colon . $this->lang->sso->create;
        $this->view->position[] = $this->lang->sso->common;
        $this->view->position[] = $this->lang->sso->create;
        $this->view->key   = $this->sso->createKey();
        $this->display();
    }

    /**
     * Edit auth.
     * 
     * @param  string $code 
     * @access public
     * @return void
     */
    public function edit($code)
    {
        if(!empty($_POST))
        {
            if(!$this->post->title) die(js::alert($this->lang->sso->error->title));
            if(!$this->post->ip)    die(js::alert($this->lang->sso->error->ip));

            $this->sso->updateAuth($code);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate(inlink('browse'), 'parent'));
        }

        $this->view->title      = $this->lang->sso->common . $this->lang->colon . $this->lang->sso->edit;
        $this->view->position[] = $this->lang->sso->common;
        $this->view->position[] = $this->lang->sso->edit;

        $this->view->auth = $this->sso->getAuth($code);
        $this->view->code = $code;
        $this->display();
    }

    /**
     * Delete auth.
     * 
     * @param  string $code 
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function delete($code, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->sso->confirmDelete, inlink('delete', "code=$code&confirm=yes")));
        }
        else
        {
            $this->sso->deleteAuth($code);
            die(js::locate(inlink('browse'), 'parent'));
        }
    }

    /**
     * Auth user.
     * 
     * @param  string $app 
     * @access public
     * @return void
     */
    public function auth($app)
    {
        $user = $this->sso->identify($app);
        if($user)
        {
            $dept = $this->loadModel('dept')->getByID($user->dept);
            $user->deptName = $dept ? $dept->name : '';

            $response['status'] = 'success';
            $response['data']   = json_encode($user);
            $this->send($response);
        }

        $response['status'] = 'fail';
        $response['data']   = 'auth failed.';
        $this->send($response);
    }

    /**
     * Get all departments.
     * 
     * @param  string $app 
     * @access public
     * @return void
     */
    public function depts($app)
    {
        if($this->post->key) $key = $this->post->key;
        if($this->get->key)  $key = $this->get->key;
        if($this->sso->checkIP($app) and $this->sso->getAppKey($app) == $key)
        {
            $depts = $this->sso->getAllDepts();
            $response['status'] = 'success';
            $response['data']   = json_encode($depts);
            $this->send($response);
        }

        $response['status'] = 'fail';
        $response['data']   = 'key error';
        $this->send($response);
    }

    /**
     * Get all users. 
     * 
     * @param  string $app 
     * @access public
     * @return void
     */
    public function users($app)
    {
        if($this->post->key) $key = $this->post->key;
        if($this->get->key)  $key = $this->get->key;
        if($this->sso->checkIP($app) and $this->sso->getAppKey($app) == $key)
        {
            $depts = $this->sso->getAllUsers();
            $response['status'] = 'success';
            $response['data']   = json_encode($depts);
            $this->send($response);
        }

        $response['status'] = 'fail';
        $response['data']   = 'key error';
        $this->send($response);
    }
}
