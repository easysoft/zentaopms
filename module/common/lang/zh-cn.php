<?php
/**
 * The common simplified chinese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: zh-cn.php 5116 2013-07-12 06:37:48Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->arrow     = '&nbsp;<i class="icon-angle-right"></i>&nbsp;';
$lang->colon     = '::';
$lang->comma     = '，';
$lang->dot       = '。';
$lang->at        = ' 于 ';
$lang->downArrow = '↓';
$lang->null      = '空';
$lang->ellipsis  = '…';

$lang->zentaoPMS      = '禅道';
$lang->welcome        = "%s项目管理系统";
$lang->logout         = '退出';
$lang->login          = '登录';
$lang->help           = '帮助';
$lang->aboutZenTao    = '关于';
$lang->profile        = '个人档案';
$lang->changePassword = '更改密码';
$lang->runInfo        = "<div class='row'><div class='u-1 a-center' id='debugbar'>时间: %s 毫秒, 内存: %s KB, 查询: %s.  </div></div>";
$lang->agreement      = "已阅读并同意<a href='http://zpl.pub/page/zplv12.html' target='_blank'>《Z PUBLIC LICENSE授权协议1.2》</a>。<span class='text-danger'>未经许可，不得去除、隐藏或遮掩禅道软件的任何标志及链接。</span>";

$lang->reset        = '重填';
$lang->refresh      = '刷新';
$lang->edit         = '编辑';
$lang->delete       = '删除';
$lang->close        = '关闭';
$lang->unlink       = '移除';
$lang->import       = '导入';
$lang->export       = '导出';
$lang->setFileName  = '文件名：';
$lang->submitting   = '稍候...';
$lang->save         = '保存';
$lang->confirm      = '确认';
$lang->preview      = '查看';
$lang->goback       = '返回';
$lang->goPC         = 'PC版';
$lang->more         = '更多';
$lang->day          = '天';
$lang->customConfig = '自定义';
$lang->public       = '公共';
$lang->trunk        = '主干';
$lang->sort         = '排序';
$lang->required     = '必填';

$lang->actions         = '操作';
$lang->comment         = '备注';
$lang->history         = '历史记录';
$lang->attatch         = '附件';
$lang->reverse         = '切换顺序';
$lang->switchDisplay   = '切换显示';
$lang->addFiles        = '上传了附件 ';
$lang->files           = '附件 ';
$lang->pasteText       = '粘贴文本 ';
$lang->uploadImages    = '多图上传 ';
$lang->timeout         = '连接超时，请检查网络环境，或重试！';
$lang->repairTable     = '数据库表可能损坏，请用phpmyadmin或myisamchk检查修复。';
$lang->duplicate       = '已有相同标题的%s';
$lang->ipLimited       = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>抱歉，管理员限制当前IP登录，请联系管理员解除限制。</body></html>";
$lang->unfold          = '+';
$lang->fold            = '-';
$lang->homepage        = '设为模块首页';
$lang->tutorial        = '新手教程';
$lang->changeLog       = '修改日志';
$lang->manual          = '手册';
$lang->customMenu      = '自定义导航';
$lang->tutorialConfirm = '检测到你尚未退出新手教程模式，是否现在退出？';

$lang->preShortcutKey  = '[快捷键:←]';
$lang->nextShortcutKey = '[快捷键:→]';
$lang->backShortcutKey = '[快捷键:Alt+↑]';

$lang->select        = '选择';
$lang->selectAll     = '全选';
$lang->selectReverse = '反选';
$lang->loading       = '稍候...';
$lang->notFound      = '抱歉，您访问的对象并不存在！';
$lang->showAll       = '[[全部显示]]';

$lang->future       = '未来';
$lang->year         = '年';
$lang->workingHour  = '工时';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = '状态';
$lang->openedByAB   = '创建';
$lang->assignedToAB = '指派';
$lang->typeAB       = '类型';

$lang->common = new stdclass();
$lang->common->common = '公有模块';

/* 主导航菜单。*/
$lang->menu = new stdclass();
$lang->menu->my       = '<i class="icon-home"></i><span> 我的地盘</span>|my|index';
$lang->menu->product  = $lang->productCommon . '|product|index';
$lang->menu->project  = $lang->projectCommon . '|project|index';
$lang->menu->qa       = '测试|qa|index';
$lang->menu->doc      = '文档|doc|index';
$lang->menu->report   = '统计|report|index';
$lang->menu->company  = '组织|company|index';
$lang->menu->admin    = '后台|admin|index';

/* 查询条中可以选择的对象列表。*/
$lang->searchObjects['bug']         = 'Bug';
$lang->searchObjects['story']       = '需求';
$lang->searchObjects['task']        = '任务';
$lang->searchObjects['testcase']    = '用例';
$lang->searchObjects['project']     = $lang->projectCommon;
$lang->searchObjects['product']     = $lang->productCommon;
$lang->searchObjects['user']        = '用户';
$lang->searchObjects['build']       = '版本';
$lang->searchObjects['release']     = '发布';
$lang->searchObjects['productplan'] = $lang->productCommon . '计划';
$lang->searchObjects['testtask']    = '测试版本';
$lang->searchObjects['doc']         = '文档';
$lang->searchObjects['caselib']     = '用例库';
$lang->searchObjects['testreport']  = '测试报告';
$lang->searchTips                   = '编号(ctrl+g)';

/* 导入支持的编码格式。*/
$lang->importEncodeList['gbk']   = 'GBK';
$lang->importEncodeList['big5']  = 'BIG5';
$lang->importEncodeList['utf-8'] = 'UTF-8';

/* 导出文件的类型列表。*/
$lang->exportFileTypeList['csv']  = 'csv';
$lang->exportFileTypeList['xml']  = 'xml';
$lang->exportFileTypeList['html'] = 'html';

$lang->exportTypeList['all']      = '全部记录';
$lang->exportTypeList['selected'] = '选中记录';

/* 语言 */
$lang->lang = 'Language';

/* 风格列表。*/
$lang->theme                 = '主题';
$lang->themes['default']     = '默认';
$lang->themes['green']       = '绿色';
$lang->themes['red']         = '红色';
$lang->themes['lightblue']   = '亮蓝';
$lang->themes['blackberry']  = '黑莓';

/* 首页菜单设置。*/
$lang->index = new stdclass();
$lang->index->menu = new stdclass();

$lang->index->menu->product = "浏览{$lang->productCommon}|product|browse";
$lang->index->menu->project = "浏览{$lang->projectCommon}|project|browse";

/* 我的地盘菜单设置。*/
$lang->my = new stdclass();
$lang->my->menu = new stdclass();

$lang->my->menu->account        = array('link' => '<span id="myname"><i class="icon-user"></i> %s' . $lang->arrow . '</span>', 'fixed' => true);
$lang->my->menu->index          = '首页|my|index';
$lang->my->menu->todo           = array('link' => '待办|my|todo|', 'subModule' => 'todo');
$lang->my->menu->task           = array('link' => '任务|my|task|', 'subModule' => 'task');
$lang->my->menu->bug            = array('link' => 'Bug|my|bug|',   'subModule' => 'bug');
$lang->my->menu->testtask       = array('link' => '测试|my|testtask|', 'subModule' => 'testcase,testtask', 'alias' => 'testcase');
$lang->my->menu->story          = array('link' => '需求|my|story|',   'subModule' => 'story');
$lang->my->menu->myProject      = "{$lang->projectCommon}|my|project|";
$lang->my->menu->dynamic        = '动态|my|dynamic|';
$lang->my->menu->profile        = array('link' => '档案|my|profile', 'alias' => 'editprofile');
$lang->my->menu->changePassword = '密码|my|changepassword';
$lang->my->menu->manageContacts = '联系人|my|managecontacts';

$lang->todo = new stdclass();
$lang->todo->menu = $lang->my->menu;

/* 产品视图设置。*/
$lang->product = new stdclass();
$lang->product->menu = new stdclass();

$lang->product->menu->list    = array('link' => '%s', 'fixed' => true);
$lang->product->menu->story   = array('link' => '需求|product|browse|productID=%s', 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->menu->dynamic = '动态|product|dynamic|productID=%s';
$lang->product->menu->plan    = array('link' => '计划|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release = array('link' => '发布|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap = '路线图|product|roadmap|productID=%s';
$lang->product->menu->doc     = array('link' => '文档|doc|objectLibs|type=product&objectID=%s&from=product', 'subModule' => 'doc');
$lang->product->menu->branch  = '@branch@|branch|manage|productID=%s';
$lang->product->menu->module  = '模块|tree|browse|productID=%s&view=story';
$lang->product->menu->view    = array('link' => '概况|product|view|productID=%s', 'alias' => 'edit');
$lang->product->menu->project = "{$lang->projectCommon}|product|project|status=all&productID=%s";
$lang->product->menu->create  = array('link' => "<i class='icon-plus'></i>&nbsp;添加{$lang->productCommon}|product|create", 'float' => 'right');
$lang->product->menu->all     = array('link' => "<i class='icon-cubes'></i>&nbsp;所有{$lang->productCommon}|product|all|productID=%s", 'float' => 'right');
$lang->product->menu->index   = array('link' => "<i class='icon-home'></i>{$lang->productCommon}主页|product|index|locate=no", 'float' => 'right');

$lang->story       = new stdclass();
$lang->productplan = new stdclass();
$lang->release     = new stdclass();
$lang->branch      = new stdclass();

$lang->branch->menu      = $lang->product->menu;
$lang->story->menu       = $lang->product->menu;
$lang->productplan->menu = $lang->product->menu;
$lang->release->menu     = $lang->product->menu;

/* 项目视图菜单设置。*/
$lang->project = new stdclass();
$lang->project->menu = new stdclass();

$lang->project->menu->list      = array('link' => '%s', 'fixed' => true);
$lang->project->menu->task      = array('link' => '任务|project|task|projectID=%s', 'subModule' => 'task,tree', 'alias' => 'grouptask,importtask,burn,importbug,kanban,printkanban,tree');
$lang->project->menu->story     = array('link' => '需求|project|story|projectID=%s', 'subModule' => 'story', 'alias' => 'linkstory');
$lang->project->menu->bug       = 'Bug|project|bug|projectID=%s';
$lang->project->menu->dynamic   = '动态|project|dynamic|projectID=%s';
$lang->project->menu->build     = array('link' => '版本|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->menu->testtask  = array('link' => '测试|project|testtask|projectID=%s');
$lang->project->menu->team      = array('link' => '团队|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->doc       = array('link' => '文档|doc|objectLibs|type=project&objectID=%s&from=project', 'subModule' => 'doc');
$lang->project->menu->product   = $lang->productCommon . '|project|manageproducts|projectID=%s';
$lang->project->menu->view      = array('link' => '概况|project|view|projectID=%s', 'alias' => 'edit,start,suspend,putoff,close');
$lang->project->menu->create    = array('link' => "<i class='icon-plus'></i>&nbsp;添加{$lang->projectCommon}|project|create", 'float' => 'right');
$lang->project->menu->all       = array('link' => "<i class='icon-th-large'></i>&nbsp;所有{$lang->projectCommon}|project|all|status=undone&projectID=%s", 'float' => 'right');
$lang->project->menu->index     = array('link' => "<i class='icon-home'></i>{$lang->projectCommon}主页|project|index|locate=no", 'float' => 'right');

$lang->task  = new stdclass();
$lang->build = new stdclass();
$lang->task->menu  = $lang->project->menu;
$lang->build->menu = $lang->project->menu;

/* QA视图菜单设置。*/
$lang->qa = new stdclass();
$lang->qa->menu = new stdclass();

$lang->qa->menu->product   = array('link' => '%s', 'fixed' => true);
$lang->qa->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->qa->menu->testcase  = array('link' => '用例|testcase|browse|productID=%s');
$lang->qa->menu->testtask  = array('link' => '版本|testtask|browse|productID=%s');
$lang->qa->menu->testsuite = array('link' => '套件|testsuite|browse|productID=%s');
$lang->qa->menu->report    = array('link' => '报告|testreport|browse|productID=%s');
$lang->qa->menu->caselib   = array('link' => '用例库|testsuite|library');
$lang->qa->menu->index     = array('link' => "<i class='icon-home'></i>测试主页|qa|index|locate=no&productID=%s", 'float' => 'right');

$lang->bug = new stdclass();
$lang->bug->menu = new stdclass();

$lang->bug->menu->product   = array('link' => '%s', 'fixed' => true);
$lang->bug->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,confirmbug,assignto', 'subModule' => 'tree');
$lang->bug->menu->testcase  = array('link' => '用例|testcase|browse|productID=%s');
$lang->bug->menu->testtask  = array('link' => '版本|testtask|browse|productID=%s');
$lang->bug->menu->testsuite = array('link' => '套件|testsuite|browse|productID=%s');
$lang->bug->menu->report    = array('link' => '报告|testreport|browse|productID=%s');
$lang->bug->menu->caselib   = array('link' => '用例库|testsuite|library');
$lang->bug->menu->index     = array('link' => "<i class='icon-home'></i>测试主页|qa|index|locate=no&productID=%s", 'float' => 'right');

$lang->testcase = new stdclass();
$lang->testcase->menu = new stdclass();

$lang->testcase->menu->product   = array('link' => '%s', 'fixed' => true);
$lang->testcase->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testcase->menu->testcase  = array('link' => '用例|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree');
$lang->testcase->menu->testtask  = array('link' => '版本|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase,report');
$lang->testcase->menu->testsuite = array('link' => '套件|testsuite|browse|productID=%s', 'alias' => 'view,create,edit,linkcase');
$lang->testcase->menu->report    = array('link' => '报告|testreport|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->testcase->menu->caselib   = array('link' => '用例库|testsuite|library');
$lang->testcase->menu->index     = array('link' => "<i class='icon-home'></i>测试主页|qa|index|locate=no&productID=%s", 'float' => 'right');

$lang->testtask = new stdclass();
$lang->testtask->menu = $lang->testcase->menu;
$lang->testsuite = new stdclass();
$lang->testsuite->menu = $lang->testcase->menu;
$lang->testreport = new stdclass();
$lang->testreport->menu = $lang->testcase->menu;

$lang->caselib = new stdclass();
$lang->caselib->menu = new stdclass();
$lang->caselib->menu->lib       = array('link' => '%s', 'fixed' => true);
$lang->caselib->menu->bug       = array('link' => 'Bug|bug|browse|');
$lang->caselib->menu->testcase  = array('link' => '用例|testcase|browse|');
$lang->caselib->menu->testtask  = array('link' => '版本|testtask|browse|');
$lang->caselib->menu->testsuite = array('link' => '套件|testsuite|browse|');
$lang->caselib->menu->report    = array('link' => '报告|testreport|browse|');
$lang->caselib->menu->caselib   = array('link' => '用例库|testsuite|library', 'alias' => 'createlib,createcase,libview,edit,batchcreatecase,showimport', 'subModule' => 'tree,testcase');
$lang->caselib->menu->create    = array('link' => "<i class='icon-plus'></i>创建库|testsuite|createLib|", 'float' => 'right');
$lang->caselib->menu->index     = array('link' => "<i class='icon-home'></i>测试主页|qa|index|locate=no&productID=%s", 'float' => 'right');

/* 文档视图菜单设置。*/
$lang->doc = new stdclass();
$lang->doc->menu = new stdclass();

$lang->doc->menu->list    = array('link' => '%s', 'fixed' => true);
$lang->doc->menu->crumb   = array('link' => '%s', 'fixed' => true);
$lang->doc->menu->create  = array('link' => '<i class="icon-plus"></i>&nbsp;添加文档库|doc|createLib', 'float' => 'right');

/* 统计视图菜单设置。*/
$lang->report = new stdclass();
$lang->report->menu = new stdclass();

$lang->report->menu->product = array('link' => $lang->productCommon . '|report|productsummary');
$lang->report->menu->prj     = array('link' => $lang->projectCommon . '|report|projectdeviation');
$lang->report->menu->test    = array('link' => '测试|report|bugcreate', 'alias' => 'bugassign');
$lang->report->menu->staff   = array('link' => '组织|report|workload');

$lang->report->notice = new stdclass();
$lang->report->notice->help = '注：统计报表的数据，来源于列表页面的检索结果，生成统计报表前请先在列表页面进行检索。';

/* 组织结构视图菜单设置。*/
$lang->company = new stdclass();
$lang->company->menu = new stdclass();
$lang->company->menu->name         = array('link' => '%s' . $lang->arrow, 'fixed' => true);
$lang->company->menu->browseUser   = array('link' => '用户|company|browse', 'subModule' => 'user');
$lang->company->menu->dept         = array('link' => '部门|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup  = array('link' => '权限|group|browse', 'subModule' => 'group');
$lang->company->menu->view         = array('link' => '公司|company|view', 'alias' => 'edit');
$lang->company->menu->dynamic      = '动态|company|dynamic|';
$lang->company->menu->addGroup     = array('link' => '<i class="icon-group"></i>&nbsp;添加分组|group|create', 'float' => 'right');
$lang->company->menu->batchAddUser = array('link' => '<i class="icon-plus-sign"></i>&nbsp;批量添加|user|batchCreate|dept=%s', 'subModule' => 'user', 'float' => 'right');
$lang->company->menu->addUser      = array('link' => '<i class="icon-plus"></i>&nbsp;添加用户|user|create|dept=%s', 'subModule' => 'user', 'float' => 'right');

$lang->dept  = new stdclass();
$lang->group = new stdclass();
$lang->user  = new stdclass();

$lang->dept->menu  = $lang->company->menu;
$lang->group->menu = $lang->company->menu;
$lang->user->menu  = $lang->company->menu;

/* 后台管理菜单设置。*/
$lang->admin = new stdclass();
$lang->admin->menu = new stdclass();
$lang->admin->menu->index     = array('link' => '首页|admin|index', 'alias' => 'register,certifytemail,certifyztmobile,ztcompany');
$lang->admin->menu->extension = array('link' => '插件|extension|browse', 'subModule' => 'extension');
$lang->admin->menu->custom    = array('link' => '自定义|custom|set', 'subModule' => 'custom');
$lang->admin->menu->mail      = array('link' => '发信|mail|index', 'subModule' => 'mail');
$lang->admin->menu->backup    = array('link' => '备份|backup|index', 'subModule' => 'backup');
$lang->admin->menu->safe      = array('link' => '安全|admin|safe', 'alias' => 'checkweak');
$lang->admin->menu->cron      = array('link' => '计划任务|cron|index', 'subModule' => 'cron');
$lang->admin->menu->trashes   = array('link' => '回收站|action|trash', 'subModule' => 'action');
$lang->admin->menu->dev       = array('link' => '二次开发|dev|api', 'alias' => 'db', 'subModule' => 'dev,editor');
$lang->admin->menu->sso       = '然之集成|admin|sso';

$lang->convert   = new stdclass();
$lang->upgrade   = new stdclass();
$lang->action    = new stdclass();
$lang->backup    = new stdclass();
$lang->extension = new stdclass();
$lang->custom    = new stdclass();
$lang->editor    = new stdclass();
$lang->mail      = new stdclass();
$lang->cron      = new stdclass();
$lang->dev       = new stdclass();
$lang->search    = new stdclass();

$lang->convert->menu   = $lang->admin->menu;
$lang->upgrade->menu   = $lang->admin->menu;
$lang->action->menu    = $lang->admin->menu;
$lang->backup->menu    = $lang->admin->menu;
$lang->cron->menu      = $lang->admin->menu;
$lang->extension->menu = $lang->admin->menu;
$lang->custom->menu    = $lang->admin->menu;
$lang->editor->menu    = $lang->admin->menu;
$lang->mail->menu      = $lang->admin->menu;
$lang->dev->menu       = $lang->admin->menu;

/* 菜单分组。*/
$lang->menugroup = new stdclass();
$lang->menugroup->release     = 'product';
$lang->menugroup->story       = 'product';
$lang->menugroup->branch      = 'product';
$lang->menugroup->productplan = 'product';
$lang->menugroup->task        = 'project';
$lang->menugroup->build       = 'project';
$lang->menugroup->convert     = 'admin';
$lang->menugroup->upgrade     = 'admin';
$lang->menugroup->user        = 'company';
$lang->menugroup->group       = 'company';
$lang->menugroup->bug         = 'qa';
$lang->menugroup->testcase    = 'qa';
$lang->menugroup->testtask    = 'qa';
$lang->menugroup->testsuite   = 'qa';
$lang->menugroup->caselib     = 'qa';
$lang->menugroup->testreport  = 'qa';
$lang->menugroup->people      = 'company';
$lang->menugroup->dept        = 'company';
$lang->menugroup->todo        = 'my';
$lang->menugroup->action      = 'admin';
$lang->menugroup->backup      = 'admin';
$lang->menugroup->cron        = 'admin';
$lang->menugroup->extension   = 'admin';
$lang->menugroup->custom      = 'admin';
$lang->menugroup->editor      = 'admin';
$lang->menugroup->mail        = 'admin';
$lang->menugroup->dev         = 'admin';

/* 错误提示信息。*/
$lang->error = new stdclass();
$lang->error->companyNotFound = "您访问的域名 %s 没有对应的公司。";
$lang->error->length          = array("『%s』长度错误，应当为『%s』", "『%s』长度应当不超过『%s』，且不小于『%s』。");
$lang->error->reg             = "『%s』不符合格式，应当为:『%s』。";
$lang->error->unique          = "『%s』已经有『%s』这条记录了。如果您确定该记录已删除，请到后台管理-回收站还原。。";
$lang->error->gt              = "『%s』应当大于『%s』。";
$lang->error->ge              = "『%s』应当不小于『%s』。";
$lang->error->notempty        = "『%s』不能为空。";
$lang->error->empty           = "『%s』必须为空。";
$lang->error->equal           = "『%s』必须为『%s』。";
$lang->error->int             = array("『%s』应当是数字。", "『%s』应当介于『%s-%s』之间。");
$lang->error->float           = "『%s』应当是数字，可以是小数。";
$lang->error->email           = "『%s』应当为合法的EMAIL。";
$lang->error->date            = "『%s』应当为合法的日期。";
$lang->error->account         = "『%s』应当为合法的用户名。";
$lang->error->passwordsame    = "两次密码应当相等。";
$lang->error->passwordrule    = "密码应该符合规则，长度至少为六位。";
$lang->error->accessDenied    = '您没有访问权限';
$lang->error->pasteImg        = '您的浏览器不支持粘贴图片！';
$lang->error->noData          = '没有数据';
$lang->error->editedByOther   = '该记录可能已经被改动。请刷新页面重新编辑！';
$lang->error->tutorialData    = '新手模式下不会插入数据，请退出新手模式操作';

/* 分页信息。*/
$lang->pager = new stdclass();
$lang->pager->noRecord     = "暂时没有记录";
$lang->pager->digest       = "共 <strong>%s</strong> 条记录，%s <strong>%s/%s</strong> &nbsp; ";
$lang->pager->recPerPage   = "每页 <strong>%s</strong> 条";
$lang->pager->first        = "<i class='icon-step-backward' title='首页'></i>";
$lang->pager->pre          = "<i class='icon-play icon-rotate-180' title='上一页'></i>";
$lang->pager->next         = "<i class='icon-play' title='下一页'></i>";
$lang->pager->last         = "<i class='icon-step-forward' title='末页'></i>";
$lang->pager->locate       = "GO!";
$lang->pager->previousPage = "上一页";
$lang->pager->nextPage     = "下一页";
$lang->pager->summery      = "第 <strong>%s-%s</strong> 项，共 <strong>%s</strong> 项";

$lang->proVersion     = "<a href='http://api.zentao.net/goto.php?item=proversion&from=footer' target='_blank' id='proLink' class='text-important'>专业版 <i class='text-danger icon-pro-version'></i></a> &nbsp; ";
$lang->downNotify     = "下载桌面提醒";

$lang->suhosinInfo   = "警告：数据太多，请在php.ini中修改<font color=red>sohusin.post.max_vars</font>和<font color=red>sohusin.request.max_vars</font>（大于%s的数）。 保存并重新启动apache或php-fpm，否则会造成部分数据无法保存。";
$lang->maxVarsInfo   = "警告：数据太多，请在php.ini中修改<font color=red>max_input_vars</font>（大于%s的数）。 保存并重新启动apache或php-fpm，否则会造成部分数据无法保存。";
$lang->pasteTextInfo = "粘贴文本到文本域中，每行文字作为一条数据的标题。";
$lang->noticeImport  = "<p style='font-size:14px'>导入数据中，含有已经存在系统的数据，请确认这些数据要覆盖或者全新插入</p><p><a href='javascript:submitForm(\"cover\")' class='btn btn-mini'>覆盖</a> <a href='javascript:submitForm(\"insert\")' class='btn btn-mini'>全新插入</a></p>";

$lang->noResultsMatch     = "没有匹配结果";
$lang->searchMore         = "搜索此关键字的更多结果：";
$lang->chooseUsersToMail  = "选择要发信通知的用户...";
$lang->browserNotice      = '你目前使用的浏览器可能无法得到最佳浏览效果，建议使用Chrome、火狐、IE9+、Opera、Safari浏览器。';
$lang->noticePasteImg     = "可以在编辑器直接贴图。";

/* 时间格式设置。*/
define('DT_DATETIME1',  'Y-m-d H:i:s');
define('DT_DATETIME2',  'y-m-d H:i');
define('DT_MONTHTIME1', 'n/d H:i');
define('DT_MONTHTIME2', 'n月d日 H:i');
define('DT_DATE1',     'Y-m-d');
define('DT_DATE2',     'Ymd');
define('DT_DATE3',     'Y年m月d日');
define('DT_DATE4',     'n月j日');
define('DT_TIME1',     'H:i:s');
define('DT_TIME2',     'H:i');

/* datepicker 时间*/
$lang->datepicker = new stdclass();

$lang->datepicker->dpText = new stdclass();
$lang->datepicker->dpText->TEXT_OR          = '或 ';
$lang->datepicker->dpText->TEXT_PREV_YEAR   = '去年';
$lang->datepicker->dpText->TEXT_PREV_MONTH  = '上月';
$lang->datepicker->dpText->TEXT_PREV_WEEK   = '上周';
$lang->datepicker->dpText->TEXT_YESTERDAY   = '昨天';
$lang->datepicker->dpText->TEXT_THIS_MONTH  = '本月';
$lang->datepicker->dpText->TEXT_THIS_WEEK   = '本周';
$lang->datepicker->dpText->TEXT_TODAY       = '今天';
$lang->datepicker->dpText->TEXT_NEXT_YEAR   = '明年';
$lang->datepicker->dpText->TEXT_NEXT_MONTH  = '下月';
$lang->datepicker->dpText->TEXT_CLOSE       = '关闭';
$lang->datepicker->dpText->TEXT_DATE        = '选择时间段';
$lang->datepicker->dpText->TEXT_CHOOSE_DATE = '选择日期';

$lang->datepicker->dayNames     = array('日', '一', '二', '三', '四', '五', '六');
$lang->datepicker->abbrDayNames = array('日', '一', '二', '三', '四', '五', '六');
$lang->datepicker->monthNames   = array('一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月');

/* Common action icons 通用动作图标 */
$lang->icons['todo']      = 'check';
$lang->icons['product']   = 'cube';
$lang->icons['bug']       = 'bug';
$lang->icons['task']      = 'check-sign';
$lang->icons['tasks']     = 'tasks';
$lang->icons['project']   = 'folder-close-alt';
$lang->icons['doc']       = 'file-text';
$lang->icons['doclib']    = 'folder-close';
$lang->icons['story']     = 'lightbulb';
$lang->icons['release']   = 'tags';
$lang->icons['roadmap']   = 'code-fork';
$lang->icons['plan']      = 'flag';
$lang->icons['dynamic']   = 'volume-up';
$lang->icons['build']     = 'tag';
$lang->icons['test']      = 'check';
$lang->icons['testtask']  = 'check';
$lang->icons['group']     = 'group';
$lang->icons['team']      = 'group';
$lang->icons['company']   = 'sitemap';
$lang->icons['user']      = 'user';
$lang->icons['dept']      = 'sitemap';
$lang->icons['tree']      = 'sitemap';
$lang->icons['usecase']   = 'sitemap';
$lang->icons['testcase']  = 'sitemap';
$lang->icons['result']    = 'list-alt';
$lang->icons['mail']      = 'envelope';
$lang->icons['trash']     = 'trash';
$lang->icons['extension'] = 'th-large';
$lang->icons['app']       = 'th-large';

$lang->icons['results']        = 'list-alt';
$lang->icons['create']         = 'plus';
$lang->icons['post']           = 'edit';
$lang->icons['batchCreate']    = 'plus-sign';
$lang->icons['batchEdit']      = 'edit-sign';
$lang->icons['batchClose']     = 'off';
$lang->icons['edit']           = 'pencil';
$lang->icons['delete']         = 'remove';
$lang->icons['copy']           = 'copy';
$lang->icons['report']         = 'bar-chart';
$lang->icons['export']         = 'download-alt';
$lang->icons['report-file']    = 'file-powerpoint';
$lang->icons['import']         = 'upload-alt';
$lang->icons['finish']         = 'ok-sign';
$lang->icons['resolve']        = 'ok-sign';
$lang->icons['start']          = 'play';
$lang->icons['restart']        = 'play';
$lang->icons['run']            = 'play';
$lang->icons['runCase']        = 'play';
$lang->icons['batchRun']       = 'play-sign';
$lang->icons['assign']         = 'hand-right';
$lang->icons['assignTo']       = 'hand-right';
$lang->icons['change']         = 'random';
$lang->icons['link']           = 'link';
$lang->icons['close']          = 'off';
$lang->icons['activate']       = 'magic';
$lang->icons['review']         = 'review';
$lang->icons['confirm']        = 'search';
$lang->icons['confirmBug']     = 'search';
$lang->icons['putoff']         = 'calendar';
$lang->icons['suspend']        = 'pause';
$lang->icons['pause']          = 'pause';
$lang->icons['cancel']         = 'ban-circle';
$lang->icons['recordEstimate'] = 'time';
$lang->icons['customFields']   = 'cogs';
$lang->icons['manage']         = 'cog';
$lang->icons['unlock']         = 'unlock-alt';
$lang->icons['confirmStoryChange'] = 'search';

include (dirname(__FILE__) . '/menuOrder.php');

global $config;
if(isset($config->global->flow) and $config->global->flow == 'onlyStory')
{
    /* Remove project, report and qa module. */
    unset($lang->menu->project);
    unset($lang->menu->report);
    unset($lang->menu->qa);

    unset($lang->menuOrder[15]);
    unset($lang->menuOrder[20]);
    unset($lang->menuOrder[30]);

    /* Adjust sub menu of my dashboard. */
    unset($lang->my->menu->bug);
    unset($lang->my->menu->testtask);
    unset($lang->my->menu->task);
    unset($lang->my->menu->myProject);

    /* Adjust sub menu of product module. */
    unset($lang->product->menu->project);
    unset($lang->product->menu->doc);
    
    /* Rename product module. */
    $lang->menu->product = '产品|product|index';

    /* Adjust search items. */
    unset($lang->searchObjects['bug']);
    unset($lang->searchObjects['task']);
    unset($lang->searchObjects['testcase']);
    unset($lang->searchObjects['project']);
    unset($lang->searchObjects['build']);
    unset($lang->searchObjects['testtask']);
}

if(isset($config->global->flow) and $config->global->flow == 'onlyTask')
{
    /* Remove product, report and qa module. */
    unset($lang->menu->product);
    unset($lang->menu->report);
    unset($lang->menu->qa);

    unset($lang->menuOrder[10]);
    unset($lang->menuOrder[20]);
    unset($lang->menuOrder[30]);

    /* Adjust sub menu of my dashboard. */
    unset($lang->my->menu->bug);
    unset($lang->my->menu->testtask);
    unset($lang->my->menu->story);

    /* Adjust sub menu of project  module. */
    unset($lang->project->menu->story);
    unset($lang->project->menu->build);
    unset($lang->project->menu->bug);
    unset($lang->project->menu->testtask);
    unset($lang->project->menu->product);
    unset($lang->project->menu->doc);

    /* Remove sub menu of product module. */
    unset($lang->product->menu);
    unset($lang->product->menuOrder);

    $lang->project->menu->task['subModule'] = 'task';
    $lang->project->menu->task['alias']     = 'grouptask,importtask';

    unset($lang->searchObjects['bug']);
    unset($lang->searchObjects['story']);
    unset($lang->searchObjects['product']);
    unset($lang->searchObjects['testcase']);
    unset($lang->searchObjects['build']);
    unset($lang->searchObjects['release']);
    unset($lang->searchObjects['productplan']);
    unset($lang->searchObjects['testtask']);
}

if(isset($config->global->flow) and $config->global->flow == 'onlyTest')
{
    /* Remove project and test module. */
    unset($lang->menu->project);
    unset($lang->menu->qa);
    unset($lang->menu->report);

    unset($lang->menuOrder[15]);
    unset($lang->menuOrder[20]);
    unset($lang->menuOrder[30]);

    /* Rename product module. */
    $lang->menu->product = '产品|product|index';

    /* Adjust sub menu of my dashboard. */
    unset($lang->my->menu->task);
    unset($lang->my->menu->myProject);
    unset($lang->my->menu->story);

    /* Remove sub menu of project module. */
    unset($lang->project->menu);
    unset($lang->project->menuOrder);

    /* Add bug, testcase and testtask module. */
    $lang->menu->bug       = 'Bug|bug|index';
    $lang->menu->testcase  = '用例|testcase|index';
    $lang->menu->testsuite = '套件|testsuite|index';
    $lang->menu->testtask  = '测试|testtask|index';
    $lang->menu->caselib   = '用例库|testsuite|library';

    $lang->menuOrder[6]  = 'bug';
    $lang->menuOrder[7]  = 'testcase';
    $lang->menuOrder[8]  = 'testsuite';
    $lang->menuOrder[9]  = 'testtask';
    $lang->menuOrder[10] = 'caselib';
    $lang->menuOrder[11] = 'product';

    /* Adjust sub menu of bug module. */
    $lang->bug->menu = new stdclass();
    $lang->bug->menu->product       = array('link' => '%s', 'fixed' => true);
    $lang->bug->menu->unclosed      = '未关闭|bug|browse|productID=%s&branch=%s&browseType=unclosed&param=%s';
    $lang->bug->menu->all           = '所有|bug|browse|productID=%s&branch=%s&browseType=all&param=%s';
    $lang->bug->menu->assigntome    = '指派给我|bug|browse|productID=%s&branch=%s&browseType=assigntome&param=%s';
    $lang->bug->menu->openedbyme    = '由我创建|bug|browse|productID=%s&branch=%s&browseType=openedbyme&param=%s';
    $lang->bug->menu->resolvedbyme  = '由我解决|bug|browse|productID=%s&branch=%s&browseType=resolvedbyme&param=%s';
    $lang->bug->menu->unconfirmed   = '未确认|bug|browse|productID=%s&branch=%s&browseType=unconfirmed&param=%s';
    $lang->bug->menu->assigntonull  = '未指派|bug|browse|productID=%s&branch=%s&browseType=assigntonull&param=%s';
    $lang->bug->menu->unresolved    = '未解决|bug|browse|productID=%s&branch=%s&browseType=unresolved&param=%s';
    $lang->bug->menu->toclosed      = '待关闭|bug|browse|productID=%s&branch=%s&browseType=toclosed&param=%s';
    $lang->bug->menu->longlifebugs  = '久未处理|bug|browse|productID=%s&branch=%s&browseType=longlifebugs&param=%s';
    $lang->bug->menu->postponedbugs = '被延期|bug|browse|productID=%s&branch=%s&browseType=postponedbugs&param=%s';
    $lang->bug->menu->overduebugs   = '过期Bug|bug|browse|productID=%s&branch=%s&browseType=overduebugs&param=%s';

    $lang->bug->menuOrder[5]  = 'product';
    $lang->bug->menuOrder[10] = 'unclosed';
    $lang->bug->menuOrder[15] = 'all';
    $lang->bug->menuOrder[20] = 'assigntome';
    $lang->bug->menuOrder[25] = 'openedbyme';
    $lang->bug->menuOrder[30] = 'resolvedbyme';
    $lang->bug->menuOrder[35] = 'unresolved';
    $lang->bug->menuOrder[40] = 'assigntonull';
    $lang->bug->menuOrder[45] = 'unresolved';
    $lang->bug->menuOrder[50] = 'toclosed';
    $lang->bug->menuOrder[55] = 'longlifebugs';
    $lang->bug->menuOrder[60] = 'postponedbugs';
    $lang->bug->menuOrder[65] = 'overduebugs';

    /* Adjust sub menu of testcase. */
    $lang->testcase->menu = new stdclass();
    $lang->testcase->menu->product = array('link' => '%s', 'fixed' => true);
    $lang->testcase->menu->all     = '所有|testcase|browse|productID=%s&branch=%s&browseType=all';
    $lang->testcase->menu->wait    = '待评审|testcase|browse|productID=%s&branch=%s&browseType=wait';
    $lang->testcase->menu->suite   = array('link' => '%s', 'fixed' => true);

    $lang->testcase->menuOrder[5]  = 'product';
    $lang->testcase->menuOrder[10] = 'all';
    $lang->testcase->menuOrder[15] = 'wait';
    $lang->testcase->menuOrder[20] = 'suite';

    /* Adjust sub menu of bug module. */
    $lang->testsuite->menu = new stdclass();
    $lang->testsuite->menu->product = array('link' => '%s', 'fixed' => true);
    $lang->testsuite->menu->create  = array('link' => "<i class='icon-plus'></i> 建套件|testsuite|create|productID=%s", 'float' => 'right');

    $lang->testsuite->menuOrder[5]  = 'product';
    $lang->testsuite->menuOrder[10] = 'create';

    /* Adjust sub menu of testtask. */
    $lang->testtask->menu = new stdclass();
    $lang->testtask->menu->product     = array('link' => '%s', 'fixed' => true);
    $lang->testtask->menu->scope       = array('link' => '%s', 'fixed' => true);
    $lang->testtask->menu->wait        = '待测版本|testtask|browse|productID=%s&branch=%s&type=%s,wait';
    $lang->testtask->menu->doing       = '测试中版本|testtask|browse|productID=%s&branch=%s&type=%s,doing';
    $lang->testtask->menu->blocked     = '被阻塞版本|testtask|browse|productID=%s&branch=%s&type=%s,blocked';
    $lang->testtask->menu->done        = '已测版本|testtask|browse|productID=%s&branch=%s&type=%s,done';
    $lang->testtask->menu->totalStatus = '全部|testtask|browse|productID=%s&branch=%s&type=%s,totalStatus';
    $lang->testtask->menu->report      = array('link' => '报告|testreport|browse', 'alias' => 'view,create,edit');
    $lang->testtask->menu->create      = array('link' => "<i class='icon-plus'></i> 提交测试|testtask|create|productID=%s", 'float' => 'right');

    $lang->testtask->menuOrder[5]   = 'product';
    $lang->testtask->menuOrder[10]  = 'scope';
    $lang->testtask->menuOrder[15]  = 'wait';
    $lang->testtask->menuOrder[20]  = 'doing';
    $lang->testtask->menuOrder[25]  = 'blocked';
    $lang->testtask->menuOrder[30]  = 'done';
    $lang->testtask->menuOrder[35]  = 'totalStatus';
    $lang->testtask->menuOrder[40]  = 'report';
    $lang->testtask->menuOrder[45]  = 'create';

    $lang->testreport->menu      = $lang->testtask->menu;
    $lang->testreport->menuOrder = $lang->testtask->menuOrder;

    /* Adjust sub menu of caselib module. */
    $lang->caselib->menu = new stdclass();
    $lang->caselib->menu->lib    = array('link' => '%s', 'fixed' => true);
    $lang->caselib->menu->all    = '所有|testsuite|library|libID=%s&browseType=all';
    $lang->caselib->menu->wait   = '待评审|testsuite|library|libID=%s&browseType=wait';
    $lang->caselib->menu->view   = '概况|testsuite|libview|libID=%s';
    $lang->caselib->menu->create = array('link' => "<i class='icon-plus'></i> 创建库|testsuite|createLib", 'float' => 'right');

    $lang->caselib->menuOrder[5]  = 'lib';
    $lang->caselib->menuOrder[10] = 'all';
    $lang->caselib->menuOrder[15] = 'wait';
    $lang->caselib->menuOrder[20] = 'view';

    /* Adjust sub menu of product module. */
    unset($lang->product->menu->story);
    unset($lang->product->menu->project);
    unset($lang->product->menu->release);
    unset($lang->product->menu->dynamic);
    unset($lang->product->menu->plan);
    unset($lang->product->menu->roadmap);
    unset($lang->product->menu->doc);
    unset($lang->product->menu->module);
    unset($lang->product->menu->index);

    $lang->product->menu->build = array('link' => '版本|product|build', 'subModule' => 'build');

    $lang->product->menuOrder[5]  = 'build';
    $lang->product->menuOrder[10] = 'view';
    $lang->product->menuOrder[15] = 'order';

    $lang->build->menu      = $lang->product->menu;
    $lang->build->menuOrder = $lang->product->menuOrder;
    
    /* Adjust menu group. */
    $lang->menugroup->bug        = 'bug';
    $lang->menugroup->testcase   = 'testcase';
    $lang->menugroup->testtask   = 'testtask';
    $lang->menugroup->testsuite  = 'testsuite';
    $lang->menugroup->testreport = 'testtask';
    $lang->menugroup->build      = 'product';
    
    /* Adjust search objects. */ 
    unset($lang->searchObjects['story']);
    unset($lang->searchObjects['task']);
    unset($lang->searchObjects['release']);
    unset($lang->searchObjects['productplan']);
}
