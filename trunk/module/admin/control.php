<?php
/**
 * The control file of admin module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     admin
 * @version     $Id: control.php 1280 2009-09-07 05:41:14Z wwccss $
 * @link        http://www.zentao.cn
 */
class admin extends control
{
    /* 构造函数，加载company, user, group模块。*/
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('company');
        $this->loadModel('user');
        $this->loadModel('group');
    }

    /* 首页。*/
    public function index($tab = 'index')
    {
        $header['title'] = $this->lang->admin->index;
        $position[]      = $header['title'];
        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->display();
    }

    /* 公司列表。*/
    public function browseCompany()
    {
        $header['title'] = $this->lang->admin->common . $this->lang->colon . $this->lang->company->browse;
        $position[]      = $this->lang->admin->company;
        $position[]      = $this->lang->company->browse;

        $companies = $this->company->getList();

        $this->assign('header',    $header);
        $this->assign('position',  $position);
        $this->assign('companies', $companies);

        $this->display();
    }

    /* 用户列表。*/
    public function browseUser($companyID = 0)
    {
        if($companyID == 0) $companyID = $this->app->company->id;

        $header['title'] = $this->lang->admin->common . $this->lang->colon . $this->lang->user->browse;
        $position[]      = $this->lang->admin->user;
        $position[]      = $this->lang->user->browse;

        $users = $this->user->getList($companyID);

        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('users',    $users);

        $this->display();
    }
    
    /* 分组列表。*/
    public function browseGroup($companyID = 0)
    {
        if($companyID == 0) $companyID = $this->app->company->id;

        $header['title'] = $this->lang->admin->common . $this->lang->colon . $this->lang->group->browse;
        $position[]      = $this->lang->admin->user;
        $position[]      = $this->lang->group->browse;

        $groups     = $this->group->getList($companyID);
        $groupUsers = array();
        foreach($groups as $group) $groupUsers[$group->id] = $this->group->getUserPairs($group->id);

        $this->assign('header',     $header);
        $this->assign('position',   $position);
        $this->assign('groups',     $groups);
        $this->assign('groupUsers', $groupUsers);

        $this->display();
    }
}
