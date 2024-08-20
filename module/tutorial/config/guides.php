<?php
global $lang;

$manageAccount = new stdClass();
$manageAccount->name    = 'manageAccount';
$manageAccount->title   = '账号管理';
$manageAccount->icon    = 'cog-outline text-primary';
$manageAccount->type    = 'starter';
$manageAccount->modules = 'admin,company,dept';
$manageAccount->app     = 'admin';
$manageAccount->tasks   = array();

$manageAccount->tasks['manageDepts'] = array();
$manageAccount->tasks['manageDepts']['name']    = 'manageDepts';
$manageAccount->tasks['manageDepts']['title']   = '部门维护';
$manageAccount->tasks['manageDepts']['steps']   = array();
$manageAccount->tasks['manageDepts']['steps'][] = array('type' => 'openApp', 'app' => 'admin', 'title' => '点击后台', 'desc' => '您可以在这里维护管理账号，进行各类配置项的设置 。');
$manageAccount->tasks['manageDepts']['steps'][] = array('type' => 'click', 'page' => 'admin-index', 'target' => '#settings .setting-box[data-id="company"]', 'title' => '点击人员管理', 'desc' => '您可以在这里维护部门、添加人员和分组配置权限。');
$manageAccount->tasks['manageDepts']['steps'][] = array('type' => 'clickNavbar', 'page' => 'company-browse', 'target' => 'dept', 'title' => '点击部门', 'desc' => '您可以在这里进行部门维护。');
$manageAccount->tasks['manageDepts']['steps'][] = array('type' => 'form', 'page' => 'dept-browse', 'target' => '#mainContentCell', 'title' => '填写部门信息', 'desc' => '可以修改和新加部门，点击左侧部门树切换要修改的部门。');
$manageAccount->tasks['manageDepts']['steps'][] = array('type' => 'saveForm', 'page' => 'dept-browse', 'target' => '#mainContentCell', 'title' => '点击保存', 'desc' => '保存后可以在左侧目录中看到。');

$manageAccount->tasks['addUser'] = array();
$manageAccount->tasks['addUser']['name']     = 'addUser';
$manageAccount->tasks['addUser']['title']    = '添加人员';
$manageAccount->tasks['addUser']['startUrl'] = array('dept', 'browse');
$manageAccount->tasks['addUser']['steps']    = array();
$manageAccount->tasks['addUser']['steps'][]  = array('type' => 'clickNavbar', 'page' => 'dept-browse', 'target' => 'browseUser', 'title' => '点击用户', 'desc' => '你可以在这里添加公司人员。');
$manageAccount->tasks['addUser']['steps'][]  = array('type' => 'click', 'page' => 'company-browse', 'target' => '#actionBar .create-user-btn', 'title' => '点击添加人员', 'desc' => '你可以在这里添加公司人员。');
$manageAccount->tasks['addUser']['steps'][]  = array('type' => 'form', 'page' => 'user-create', 'title' => '输入人员信息', 'desc' => '请输入必填项');
$manageAccount->tasks['addUser']['steps'][]  = array('type' => 'saveForm', 'page' => 'user-create', 'title' => '保存人员信息', 'desc' => '保存后可以在人员列表中查看。');


$managePrograms = new stdClass();
$managePrograms->name    = 'managePrograms';
$managePrograms->title   = '项目集管理';
$managePrograms->icon    = 'program text-success';
$managePrograms->type    = 'starter';
$managePrograms->vision  = 'rnd';
$managePrograms->modules = 'program';
$managePrograms->tasks   = array();
$managePrograms->tasks['browsePrograms'] = array();
$managePrograms->tasks['browsePrograms']['name']    = 'browsePrograms';
$managePrograms->tasks['browsePrograms']['title']   = '项目集浏览';
$managePrograms->tasks['browsePrograms']['steps']   = array();
$managePrograms->tasks['browsePrograms']['steps'][] = array('type' => 'openApp', 'app' => 'program', 'desc' => '您可以在这里管理项目集。');


$manageProducts = new stdClass();
$manageProducts->name    = 'manageProducts';
$manageProducts->title   = '产品管理';
$manageProducts->icon    = 'product text-success';
$manageProducts->type    = 'basic';
$manageProducts->vision  = 'rnd';
$manageProducts->modules = 'product';
$manageProducts->tasks   = array();
$manageProducts->tasks['browseProducts'] = array();
$manageProducts->tasks['browseProducts']['name']    = 'browseProducts';
$manageProducts->tasks['browseProducts']['title']   = '产品浏览';
$manageProducts->tasks['browseProducts']['steps']   = array();
$manageProducts->tasks['browseProducts']['steps'][] = array('type' => 'openApp', 'app' => 'product', 'desc' => '您可以在这里管理产品。');


$manageProjects = new stdClass();
$manageProjects->name    = 'manageProjects';
$manageProjects->title   = '项目管理';
$manageProjects->icon    = 'project text-special';
$manageProjects->type    = 'advance';
$manageProducts->modules = 'project';
$manageProjects->tasks   = array();

$manageProjects->tasks['createProject'] = array();
$manageProjects->tasks['createProject']['name']    = 'createProject';
$manageProjects->tasks['createProject']['title']   = '创建项目';
$manageProjects->tasks['createProject']['steps']   = array();
$manageProjects->tasks['createProject']['steps'][] = array('type' => 'openApp', 'app' => 'project', 'desc' => '您可以在这里创建项目。');


$config->tutorial->guides = array();
$config->tutorial->guides[$manageAccount->name]     = $manageAccount;
$config->tutorial->guides[$managePrograms->name]    = $managePrograms;
$config->tutorial->guides[$manageProducts->name]    = $manageProducts;
$config->tutorial->guides[$manageProjects->name]    = $manageProjects;

if($config->systemMode == 'light') unset($config->tutorial->guides[$managePrograms->name]);
