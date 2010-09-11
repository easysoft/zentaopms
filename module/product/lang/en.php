<?php
/**
 * The product module English file of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->product->common = 'Product';
$lang->product->index  = "Index";
$lang->product->browse = "Browse";
$lang->product->view   = "Info";
$lang->product->edit   = "Edit";
$lang->product->create = "Create";
$lang->product->delete = "Delete";

$lang->product->roadmap   = 'Roadmap';
$lang->product->doc       = 'Coc';

$lang->product->selectProduct   = "Select product";
$lang->product->saveButton      = " Save (S) ";
$lang->product->confirmDelete   = " Are you sure to delete this product?";
$lang->product->ajaxGetProjects = "API: projects of product";
$lang->product->ajaxGetPlans    = "API: plans of product";

$lang->product->errorFormat    = 'Error format.';
$lang->product->errorEmptyName = 'Name can not be empty.';
$lang->product->errorEmptyCode = 'Code can not be empty';
$lang->product->errorNoProduct = 'No product in system yet.';
$lang->product->accessDenied   = 'Access to this product denined.';

$lang->product->id        = 'ID';
$lang->product->company   = 'Company';
$lang->product->name      = 'Name';
$lang->product->code      = 'Code';
$lang->product->order     = 'Order';
$lang->product->status    = 'Status';
$lang->product->desc      = 'Desc';
$lang->product->bugOwner  = 'Bug Owner';
$lang->product->acl       = 'Access limitation';
$lang->product->whitelist = 'Whitelist';

$lang->product->moduleStory = 'By module';
$lang->product->searchStory = 'By search';
$lang->product->allStory    = 'All story';

$lang->product->statusList['']       = '';
$lang->product->statusList['normal'] = 'Normal';
$lang->product->statusList['closed'] = 'Closed';

$lang->product->aclList['open']    = 'Default(Having product module prividge, can visit this product)';
$lang->product->aclList['private'] = 'Private(Only project team members can visit)';
$lang->product->aclList['custom']  = 'Whitelist(Project team members and who belongs to the whilelist groups can visit)';
