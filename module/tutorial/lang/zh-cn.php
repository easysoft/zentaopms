<?php
/**
 * The tutorial lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: zh-cn.php 5116 2013-07-12 06:37:48Z sunhao@cnezsoft.com $
 * @link        http://www.zentao.net
 */

$lang->tutorial = new stdclass();
$lang->tutorial->common          = '新手教程';
$lang->tutorial->desc            = '通过完成一系列任务，快速了解禅道的基本使用方法。这可能会花费您10分钟，你可以随时退出任务。';
$lang->tutorial->start           = '立即开始';
$lang->tutorial->exit            = '退出任务';
$lang->tutorial->congratulation  = '恭喜，你已完成了所有任务！';
$lang->tutorial->restart         = '重新开始';
$lang->tutorial->currentTask     = '当前任务';
$lang->tutorial->allTasks        = '所有任务';
$lang->tutorial->previous        = '上一个';
$lang->tutorial->nextTask        = '下一个任务';

$lang->tutorial->tasks = array();

$lang->tutorial->tasks['createAccount']         = array('title' => '创建账号');
$lang->tutorial->tasks['createAccount']['nav']  = array('module' => 'company', 'method' => 'create', 'menu' => 'addUser', 'form' => '#dataform', 'submit' => '#submit');
$lang->tutorial->tasks['createAccount']['desc'] = "<p>在系统创建一个新的用户账号：</p><ul><li>打开 <span class='task-nav'>组织 <i class='icon icon-angle-right'></i> 用户 <i class='icon icon-angle-right'></i> 添加用户</span> 页面；</li><li>在添加用户表单中填写新用户信息；</li><li>保存用户信息。</li></ul>";

$lang->tutorial->tasks['createProduct']         = array('title' => '创建产品');
$lang->tutorial->tasks['createProduct']['nav']  = array('module' => 'product', 'method' => 'create', 'menu' => 'create', 'form' => '#dataform', 'submit' => '#submit');
$lang->tutorial->tasks['createProduct']['desc'] = "<p>在系统创建一个新的产品：</p><ul><li>打开 <span class='task-nav'>产品 <i class='icon icon-angle-right'></i> 添加产品</span> 页面；</li><li>在添加产品表单中填写要创建的产品信息；</li><li>保存产品信息。</li></ul>";

$lang->tutorial->tasks['createStory']         = array('title' => '创建需求');
$lang->tutorial->tasks['createStory']['nav']  = array('module' => 'story', 'method' => 'create', 'menu' => 'story', 'feature' => '.create-story-btn', 'form' => '#dataform', 'submit' => '#submit');
$lang->tutorial->tasks['createStory']['desc'] = "<p>在系统创建一个新的需求：</p><ul><li>打开 <span class='task-nav'>产品 <i class='icon icon-angle-right'></i> 需求列表 <i class='icon icon-angle-right'></i> 添加需求</span> 页面；</li><li>在添加需求表单中填写要创建的需求信息；</li><li>保存需求信息。</li></ul>";

$lang->tutorial->tasks['createProject']         = array('title' => '创建项目');
$lang->tutorial->tasks['createProject']['nav']  = array('module' => 'project', 'method' => 'create', 'menu' => 'create', 'form' => '#dataform', 'submit' => '#submit');
$lang->tutorial->tasks['createProject']['desc'] = "<p>在系统创建一个新的项目：</p><ul><li>打开 <span class='task-nav'> 项目 <i class='icon icon-angle-right'></i> 添加项目</span> 页面；</li><li>在添加项目表单中填写要创建的 项目信息；</li><li>保存 项目信息。</li></ul>";

$lang->tutorial->tasks['linkStory']         = array('title' => '关联需求');
$lang->tutorial->tasks['linkStory']['nav']  = array('module' => 'project', 'method' => 'linkStory', 'menu' => 'story', 'feature' => '.link-story-btn', 'form' => '#linkStoryForm', 'formType' => 'table', 'submit' => '#submit');
$lang->tutorial->tasks['linkStory']['desc'] = "<p>将需求关联到项目：</p><ul><li>打开 <span class='task-nav'> 项目 <i class='icon icon-angle-right'></i> 需求列表 <i class='icon icon-angle-right'></i> 关联需求</span> 页面；</li><li>在关联需求表单中勾选要关联的需求；</li><li>保存关联的需求信息。</li></ul>";

$lang->tutorial->tasks['createTask']         = array('title' => '分解任务');
$lang->tutorial->tasks['createTask']['nav']  = array('module' => 'task', 'method' => 'create', 'menu' => 'story', 'target' => '.btn-task-create', 'form' => '#dataform', 'submit' => '#submit');
$lang->tutorial->tasks['createTask']['desc'] = "<p>将项目需求分解为任务：</p><ul><li>打开 <span class='task-nav'> 项目 <i class='icon icon-angle-right'></i> 需求列表 <i class='icon icon-angle-right'></i> 分解任务</span> 页面；</li><li>在创建任务表单中填写任务信息；</li><li>保存任务信息。</li></ul>";

$lang->tutorial->tasks['createBug']         = array('title' => '提Bug');
$lang->tutorial->tasks['createBug']['nav']  = array('module' => 'bug', 'method' => 'create', 'menu' => 'bug', 'target' => '.btn-bug-create', 'form' => '#dataform', 'submit' => '#submit');
$lang->tutorial->tasks['createBug']['desc'] = "<p>在系统中提交一个Bug：</p><ul><li>打开 <span class='task-nav'> 项目 <i class='icon icon-angle-right'></i> Bug列表 <i class='icon icon-angle-right'></i> 提Bug</span> 页面，或者打开 <span class='task-nav'> 测试 <i class='icon icon-angle-right'></i> Bug列表 <i class='icon icon-angle-right'></i> 提Bug</span>；</li><li>在创建任务表单中填写任务信息；</li><li>保存任务信息。</li></ul>";

foreach ($lang->tutorial->tasks as $name => $task)  $lang->tutorial->tasks[$name]['name'] = $name;
