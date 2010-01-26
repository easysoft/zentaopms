<?php
/**
 * The control file of release module of ZenTaoMS.
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
 * @package     release
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class release extends control
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $header['title'] = $this->lang->release->index;
        $this->assign('header', $header);
        $this->display();
    }

    public function create($product = '')
    {
        $header['title'] = $this->lang->release->create;
        $this->assign('header',  $header);
        $this->assign('product', $product);
        if(!empty($_POST))
        {
            $this->release->create($_POST);
            die(js::locate($this->createLink('product', 'index', "product=$product"), 'parent'));
        }
        $this->display();
    }

    public function update($id)
    {
        $header['title'] = $this->lang->page->update;
        $this->assign('header', $header);
        $this->display();
    }

    public function delete($id)
    {
        $header['title'] = $this->lang->page->delete;
        $this->assign('header', $header);
        $this->display();
    }

    public function browse($product = 0)
    {
        $header['title'] = $this->lang->page->browse;
        $this->assign('header',   $header);
        $this->assign('product',  $product);
        $this->assign('releases', $this->release->getList($product));
        $this->display();
    }
}
