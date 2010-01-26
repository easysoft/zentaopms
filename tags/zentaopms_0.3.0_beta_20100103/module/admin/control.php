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
 * @version     $Id$
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
        $this->locate($this->createLink('admin', 'browseCompany'));
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
}
