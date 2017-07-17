<?php
/**
 * The misc module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc
 * @version     $Id: zh-cn.php 5128 2013-07-13 08:59:49Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->misc = new stdclass();
$lang->misc->common = '杂项';
$lang->misc->ping   = '防超时';

$lang->misc->zentao = new stdclass();
$lang->misc->zentao->version           = '版本%s';
$lang->misc->zentao->labels['about']   = '关于禅道';
$lang->misc->zentao->labels['support'] = '技术支持';
$lang->misc->zentao->labels['cowin']   = '帮助我们';
$lang->misc->zentao->labels['service'] = '服务列表';

$lang->misc->zentao->icons['about']   = 'group';
$lang->misc->zentao->icons['support'] = 'question-sign';
$lang->misc->zentao->icons['cowin']   = 'hand-right';
$lang->misc->zentao->icons['service'] = 'heart';

$lang->misc->zentao->about['proversion']   = '升级专业版本';
$lang->misc->zentao->about['official']     = "官方网站";
$lang->misc->zentao->about['changelog']    = "版本历史";
$lang->misc->zentao->about['license']      = "授权协议";
$lang->misc->zentao->about['extension']    = "插件平台";

$lang->misc->zentao->support['vip']        = "商业技术支持";
$lang->misc->zentao->support['manual']     = "用户手册";
$lang->misc->zentao->support['faq']        = "常见问题";
$lang->misc->zentao->support['ask']        = "官方问答";
$lang->misc->zentao->support['qqgroup']    = "官方QQ群";

$lang->misc->zentao->cowin['donate']       = "捐助我们";
$lang->misc->zentao->cowin['reportbug']    = "汇报Bug";
$lang->misc->zentao->cowin['feedback']     = "反馈需求";
$lang->misc->zentao->cowin['recommend']    = "推荐给朋友";
$lang->misc->zentao->cowin['cowinmore']    = "更多方式...";

$lang->misc->zentao->service['zentaotrain']= '禅道使用培训';
$lang->misc->zentao->service['scrumtrain'] = '敏捷开发培训';
$lang->misc->zentao->service['idc']        = '禅道在线托管';
$lang->misc->zentao->service['custom']     = '禅道定制开发';
$lang->misc->zentao->service['install']    = '禅道安装服务';
$lang->misc->zentao->service['fixissue']   = '禅道问题解决';
$lang->misc->zentao->service['servicemore']= '更多服务...';

$lang->misc->mobile      = "手机访问";
$lang->misc->noGDLib     = "请用手机浏览器访问：<strong>%s</strong>";
$lang->misc->copyright   = "&copy; 2009 - 2016 <a href='http://www.cnezsoft.com' target='_blank'>青岛易软天创网络科技有限公司</a> 电话：4006-8899-23 Email：<a href='mailto:co@zentao.net'>co@zentao.net</a>  QQ：1492153927";
$lang->misc->checkTable  = "检查修复数据表";
$lang->misc->needRepair  = "修复表";
$lang->misc->repairTable = "数据库表可能因为断电原因损坏，需要检查修复！！";
$lang->misc->repairFail  = "修复失败，请到该数据库的数据目录下，尝试执行<code>myisamchk -r -f %s.MYI</code>进行修复。";
$lang->misc->tableName   = "表名";
$lang->misc->tableStatus = "状态";
$lang->misc->novice      = "您可能初次使用禅道，是否进入新手模式？";

$lang->misc->noticeRepair = "<h5>普通用户请联系管理员进行修复</h5>
    <h5>管理员请登录禅道所在的服务器，创建<span>%s</span>文件。</h5>
    <p>注意：</p>
    <ol>
    <li>文件内容为空。</li>
    <li>如果之前文件存在，删除之后重新创建。</li>
    </ol>"; 

$lang->misc->feature = new stdclass();
$lang->misc->feature->lastest  = '最新版本';
$lang->misc->feature->detailed = '详情';

$lang->misc->feature->all['9.0.beta'][] = array('title'=>'增加禅道云发信功能', 'desc' => '<p>禅道云发信是禅道联合SendCloud推出的一项免费发信服务，只有用户绑定禅道，并通过验证即可使用。</p>');
$lang->misc->feature->all['9.0.beta'][] = array('title'=>'优化富文本编辑器和markdown编辑器', 'desc' => '');

$lang->misc->feature->all['8.3.stable'][] = array('title'=>'调整文档功能', 'desc' => '<p>增加文档模块首页，重新组织文档库结构，增加权限</p><p>多种文件浏览方式，文档支持Markdown，增加文档权限管理，增加文件版本管理。</p>');

$lang->misc->feature->all['8.2.stable'][] = array('title'=>'首页自定义', 'desc' => '<p>我的地盘由我做主。现在开始，你可以向首页添加多种多样的内容区块，而且还可以决定如何排列和显示他们。</p><p>我的地盘、产品、项目、测试模块下均支持首页自定义功能。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'导航定制', 'desc' => '<p>导航上显示的项目现在完全由你来决定，不仅仅可以决定在导航上展示哪些内容，还可以决定展示的顺序。</p><p>将鼠标悬浮在导航上稍后会在右侧显示定制按钮，点击打开定制对话框，通过点击切换是否显示，拖放操作来更改显示顺序。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'批量添加、编辑自定义', 'desc' => '<p>可以在批量添加和批量编辑页面自定义操作的字段。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'添加需求、任务、Bug、用例自定义', 'desc' => '<p>可以在添加需求、任务、Bug、用例页面，自定义部分字段是否显示。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'导出自定义', 'desc' => '<p>在导出需求、任务、Bug、用例的时候，用户可以自定义导出的字段，也可以保存模板方便每次导出。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'需求、任务、Bug、用例组合检索功能', 'desc' => '<p>在需求、任务、Bug、用例列表页面，可以实现模块和标签的组合检索。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'增加新手教程', 'desc' => '<p>增加新手教程，方便新用户了解禅道使用。</p>');

$lang->misc->feature->all['7.4.beta'][] = array('title'=>'产品实现分支功能', 'desc' => '<p>产品增加分支/平台类型，相应的需求、计划、Bug、用例、模块等都增加分支。</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'调整发布模块', 'desc' => '<p>发布增加停止维护操作，当发布停止维护时，创建Bug将不显示这个发布。</p><p>发布中遗留的bug改为手工关联。</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'调整需求和Bug的创建页面', 'desc' => '');

$lang->misc->feature->all['7.2.stable'][] = array('title'=>'增强安全', 'desc' => '<p>加强对管理员弱口令的检查。</p><p>写插件，上传插件的时候需要创建ok文件。</p><p>敏感操作增加管理员口令的检查</p><p>对输入内容做striptags, specialchars处理。</p>');
$lang->misc->feature->all['7.2.stable'][] = array('title'=>'完善细节', 'desc' => '');

$lang->misc->feature->all['7.1.stable'][] = array('title'=>'提供计划任务框架', 'desc' => '增加计划任务框架，加入每日提醒、更新燃尽图、备份、发信等重要任务。');
$lang->misc->feature->all['7.1.stable'][] = array('title'=>'提供rpm和deb包', 'desc' => '');

$lang->misc->feature->all['6.3.stable'][] = array('title'=>'增加数据表格功能', 'desc' => '<p>可配置数据表格中可显示的字段，按照配置字段显示想看的数据</p>');
$lang->misc->feature->all['6.3.stable'][] = array('title'=>'继续完善细节', 'desc' => '');
