<?php
/**
 * The misc module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc
 * @version     $Id: zh-cn.php 5128 2013-07-13 08:59:49Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->misc = new stdclass();
$lang->misc->common  = '杂项';
$lang->misc->ping    = '防超时';
$lang->misc->view    = '查看';
$lang->misc->cancel  = '取消';

$lang->misc->zentao = new stdclass();
$lang->misc->zentao->version           = '开源版 %s';
$lang->misc->zentao->labels['about']   = '关于禅道';
$lang->misc->zentao->labels['support'] = '技术支持';
$lang->misc->zentao->labels['cowin']   = '帮助我们';
$lang->misc->zentao->labels['service'] = '服务列表';
$lang->misc->zentao->labels['others']  = '其他产品';

$lang->misc->zentao->icons['about']   = 'group';
$lang->misc->zentao->icons['support'] = 'question-sign';
$lang->misc->zentao->icons['cowin']   = 'hand-right';
$lang->misc->zentao->icons['service'] = 'heart';

$lang->misc->zentao->about['bizversion']   = '升级企业版本';
$lang->misc->zentao->about['official']     = "官方网站";
$lang->misc->zentao->about['changelog']    = "版本历史";
$lang->misc->zentao->about['license']      = "授权协议";
$lang->misc->zentao->about['extension']    = "插件平台";
$lang->misc->zentao->about['follow']       = "关注我们";

$lang->misc->zentao->support['vip']        = "商业技术支持";
$lang->misc->zentao->support['manual']     = "用户手册";
$lang->misc->zentao->support['faq']        = "常见问题";
$lang->misc->zentao->support['ask']        = "官方问答";
$lang->misc->zentao->support['video']      = "使用视频";
$lang->misc->zentao->support['qqgroup']    = "官方QQ群";

$lang->misc->zentao->cowin['reportbug']    = "反馈Bug";
$lang->misc->zentao->cowin['feedback']     = "反馈需求";
$lang->misc->zentao->cowin['recommend']    = "推荐给朋友";

$lang->misc->zentao->service['zentaotrain'] = '禅道使用培训';
$lang->misc->zentao->service['idc']         = '禅道在线托管';
$lang->misc->zentao->service['custom']      = '禅道定制开发';

global $config;
$lang->misc->zentao->others['chanzhi']  = "<img src='{$config->webRoot}theme/default/images/main/chanzhi.ico' /> 蝉知门户";
$lang->misc->zentao->others['zdoo']     = "<img src='{$config->webRoot}theme/default/images/main/zdoo.ico' /> ZDOO协同";
$lang->misc->zentao->others['xuanxuan'] = "<img src='{$config->webRoot}theme/default/images/main/xuanxuan.ico' /> 喧喧聊天";
$lang->misc->zentao->others['ydisk']    = "<img src='{$config->webRoot}theme/default/images/main/ydisk.ico' /> 悦库网盘";
$lang->misc->zentao->others['meshiot' ] = "<img src='{$config->webRoot}theme/default/images/main/meshiot.ico' /> 易天物联";

$lang->misc->copyright   = "&copy; 2009 - " . date('Y') . " <a href='https://www.easycorp.cn' target='_blank'>禅道软件（青岛）有限公司</a> 电话：4006-8899-23 Email：<a href='mailto:co@zentao.net'>co@zentao.net</a>  QQ：1492153927";
$lang->misc->checkTable  = "检查修复数据表";
$lang->misc->needRepair  = "修复表";
$lang->misc->repairTable = "数据库表可能因为断电原因损坏，需要检查修复！！";
$lang->misc->repairFail  = "修复失败，请到该数据库的数据目录下，尝试执行<code>myisamchk -r -f %s.MYI</code>进行修复。";
$lang->misc->connectFail = "连接数据库失败，错误：%s，<br/> 请检查mysql错误日志，排查错误。";
$lang->misc->tableName   = "表名";
$lang->misc->tableStatus = "状态";
$lang->misc->novice      = "您可能初次使用禅道，是否进入新手模式？";
$lang->misc->showAnnual  = '新增年度总结功能';
$lang->misc->annualDesc  = '12.0版本后，新增年度总结功能，可以到『统计->年度总结』页面查看。 是否现在<a href="%s" target="_blank" id="showAnnual" class="btn mini primary">查看</a>';
$lang->misc->remind      = '新功能提醒';

$lang->misc->expiredTipsTitle    = '尊敬的系统管理员，您好：';
$lang->misc->expiredCountTips    = '系统中有<span class="expired-tips text-blue" data-toggle="tooltip" data-placement="bottom" title="%s">%s个插件</span>即将到期，为避免影响您的正常使用，请联系管理员及时续费或卸载。';
$lang->misc->expiredPluginTips   = '已到期的插件为：%s。';
$lang->misc->expiringPluginTips  = '即将到期的插件为：%s。';
$lang->misc->expiredTipsForAdmin = '当前系统中有%s个插件即将到期，为避免影响功能的正常使用，请尽快到系统后台插件管理中进行续费或卸载处理。';

$lang->misc->noticeRepair = "<h5>普通用户请联系管理员进行修复</h5>
    <h5>管理员请登录禅道所在的服务器，创建<span>%s</span>文件。</h5>
    <p>注意：</p>
    <ol>
    <li>文件内容为空。</li>
    <li>如果之前文件存在，删除之后重新创建。</li>
    </ol>";

$lang->misc->feature = new stdclass();
$lang->misc->feature->lastest           = '最新版本';
$lang->misc->feature->detailed          = '详情';
$lang->misc->feature->introduction      = '新功能介绍';
$lang->misc->feature->tutorial          = '新手引导教程';
$lang->misc->feature->tutorialImage     = 'theme/default/images/main/tutorial.png';
$lang->misc->feature->youngBlueTheme    = '全新青春蓝主题';
$lang->misc->feature->youngBlueImage    = 'theme/default/images/main/new_theme.png';
$lang->misc->feature->visions           = "不同场景界面切换";
$lang->misc->feature->nextStep          = '下一页';
$lang->misc->feature->prevStep          = '上一页';
$lang->misc->feature->close             = '开始体验';
$lang->misc->feature->learnMore         = '了解更多';
$lang->misc->feature->downloadFile      = '下载新版本功能介绍文档';
$lang->misc->feature->tutorialDesc      = "<p>禅道15系列新增了多项功能，您可以通过“<strong>新手引导教程</strong>”快速了解禅道的基本使用方法。</p><p>通过鼠标经过 [<span style='color: #0c60e1'>头像-新手引导</span>]，点击新手引导，即可进入新手引导教程。</p>";
$lang->misc->feature->themeDesc         = "<p>禅道15系列上线了全新的“青春蓝”主题，页面呈现更加美观，体验更加友好。</p><p>通过鼠标经过 [<span style='color: #0c60e1'>头像-主题-青春蓝</span>]，点击青春蓝，即可设置成功。</p>";
$lang->misc->feature->visionsDesc       = "<p>从16.5开始增加了界面概念，用户可以在<span style='color:#0c60e1'>[研发综合界面]</span>中处理研发事务、在<span style='color:#0c60e1'>[运营管理界面]</span>处理日常办公事务。</p><p>在头像右侧即可查看当前所处界面，点击当前界面名称可查看和切换其他的界面。</p>";
$lang->misc->feature->visionsImage      = 'theme/default/images/main/visions.png';
$lang->misc->feature->aiPrompts         = 'AI提词功能';
$lang->misc->feature->aiPromptsImage    = 'theme/default/images/main/ai_prompts.svg';
$lang->misc->feature->promptDesign      = '设计AI提词';
$lang->misc->feature->promptDesignImage = 'theme/default/images/main/prompt_design.svg';
$lang->misc->feature->promptExec        = '执行AI提词';
$lang->misc->feature->promptExecImage   = 'theme/default/images/main/prompt_exec.svg';
$lang->misc->feature->promptLearnMore   = 'https://www.zentao.net/book/zentaopms/1097.html';

/* Release Date. */
$lang->misc->releaseDate['18.9']        = '2023-11-09';
$lang->misc->releaseDate['18.8']        = '2023-09-28';
$lang->misc->releaseDate['18.7']        = '2023-08-29';
$lang->misc->releaseDate['18.6']        = '2023-08-15';
$lang->misc->releaseDate['18.5']        = '2023-07-05';
$lang->misc->releaseDate['18.4']        = '2023-06-14';
$lang->misc->releaseDate['18.4.beta1']  = '2023-05-31';
$lang->misc->releaseDate['18.4.alpha1'] = '2023-04-21';
$lang->misc->releaseDate['18.3']        = '2023-03-15';
$lang->misc->releaseDate['18.2']        = '2023-02-27';
$lang->misc->releaseDate['18.1']        = '2023-02-08';
$lang->misc->releaseDate['18.0']        = '2023-01-03';
$lang->misc->releaseDate['18.0.beta3']  = '2022-12-26';
$lang->misc->releaseDate['18.0.beta2']  = '2022-12-14';
$lang->misc->releaseDate['18.0.beta1']  = '2022-11-16';
$lang->misc->releaseDate['17.8']        = '2022-11-02';
$lang->misc->releaseDate['17.7']        = '2022-10-19';
$lang->misc->releaseDate['17.6.2']      = '2022-09-23';
$lang->misc->releaseDate['17.6.1']      = '2022-09-08';
$lang->misc->releaseDate['17.6']        = '2022-08-26';
$lang->misc->releaseDate['17.5']        = '2022-08-11';
$lang->misc->releaseDate['17.4']        = '2022-07-27';
$lang->misc->releaseDate['17.3']        = '2022-07-13';
$lang->misc->releaseDate['17.2']        = '2022-06-29';
$lang->misc->releaseDate['17.1']        = '2022-06-16';
$lang->misc->releaseDate['17.0']        = '2022-06-02';
$lang->misc->releaseDate['17.0.beta2']  = '2022-05-26';
$lang->misc->releaseDate['17.0.beta1']  = '2022-05-06';
$lang->misc->releaseDate['16.5']        = '2022-03-24';
$lang->misc->releaseDate['16.5.beta1']  = '2022-03-16';
$lang->misc->releaseDate['16.4']        = '2022-02-15';
$lang->misc->releaseDate['16.3']        = '2022-01-26';
$lang->misc->releaseDate['16.2']        = '2022-01-17';
$lang->misc->releaseDate['16.1']        = '2022-01-11';
$lang->misc->releaseDate['16.0']        = '2021-12-24';
$lang->misc->releaseDate['16.0.beta1']  = '2021-12-06';
$lang->misc->releaseDate['15.7.1']      = '2021-11-02';
$lang->misc->releaseDate['15.7']        = '2021-10-18';
$lang->misc->releaseDate['15.6']        = '2021-10-12';
$lang->misc->releaseDate['15.5']        = '2021-09-14';
$lang->misc->releaseDate['15.4']        = '2021-08-23';
$lang->misc->releaseDate['15.3']        = '2021-08-04';
$lang->misc->releaseDate['15.2']        = '2021-07-20';
$lang->misc->releaseDate['15.0.3']      = '2021-06-24';
$lang->misc->releaseDate['15.0.2']      = '2021-06-12';
$lang->misc->releaseDate['15.0.1']      = '2021-06-06';
$lang->misc->releaseDate['15.0']        = '2021-04-30';
$lang->misc->releaseDate['15.0.rc3']    = '2021-04-16';
$lang->misc->releaseDate['15.0.rc2']    = '2021-04-09';
$lang->misc->releaseDate['15.0.rc1']    = '2021-04-05';
$lang->misc->releaseDate['12.5.3']      = '2021-01-06';
$lang->misc->releaseDate['12.5.2']      = '2020-12-18';
$lang->misc->releaseDate['12.5.1']      = '2020-11-30';
$lang->misc->releaseDate['12.5.stable'] = '2020-11-19';
$lang->misc->releaseDate['20.0.alpha1'] = '2020-10-30';
$lang->misc->releaseDate['12.4.4']      = '2020-10-30';
$lang->misc->releaseDate['12.4.3']      = '2020-10-13';
$lang->misc->releaseDate['12.4.2']      = '2020-09-18';
$lang->misc->releaseDate['12.4.1']      = '2020-08-10';
$lang->misc->releaseDate['12.4.stable'] = '2020-07-28';
$lang->misc->releaseDate['12.3.3']      = '2020-07-02';
$lang->misc->releaseDate['12.3.2']      = '2020-06-01';
$lang->misc->releaseDate['12.3.1']      = '2020-05-15';
$lang->misc->releaseDate['12.3']        = '2020-04-08';
$lang->misc->releaseDate['12.2']        = '2020-03-25';
$lang->misc->releaseDate['12.1']        = '2020-03-10';
$lang->misc->releaseDate['12.0.1']      = '2020-02-12';
$lang->misc->releaseDate['12.0']        = '2020-01-03';
$lang->misc->releaseDate['11.7']        = '2019-11-28';
$lang->misc->releaseDate['11.6.5']      = '2019-11-08';
$lang->misc->releaseDate['11.6.4']      = '2019-10-17';
$lang->misc->releaseDate['11.6.3']      = '2019-09-24';
$lang->misc->releaseDate['11.6.2']      = '2019-09-06';
$lang->misc->releaseDate['11.6.1']      = '2019-08-23';
$lang->misc->releaseDate['11.6.stable'] = '2019-07-12';
$lang->misc->releaseDate['11.5.2']      = '2019-06-26';
$lang->misc->releaseDate['11.5.1']      = '2019-06-24';
$lang->misc->releaseDate['11.5.stable'] = '2019-05-08';
$lang->misc->releaseDate['11.4.1']      = '2019-04-08';
$lang->misc->releaseDate['11.4.stable'] = '2019-03-25';
$lang->misc->releaseDate['11.3.stable'] = '2019-02-27';
$lang->misc->releaseDate['11.2.stable'] = '2019-01-30';
$lang->misc->releaseDate['11.1.stable'] = '2019-01-04';
$lang->misc->releaseDate['11.0.stable'] = '2018-12-21';
$lang->misc->releaseDate['10.6.stable'] = '2018-11-20';
$lang->misc->releaseDate['10.5.stable'] = '2018-10-25';
$lang->misc->releaseDate['10.4.stable'] = '2018-09-28';
$lang->misc->releaseDate['10.3.stable'] = '2018-08-10';
$lang->misc->releaseDate['10.2.stable'] = '2018-08-02';
$lang->misc->releaseDate['10.0.stable'] = '2018-06-26';
$lang->misc->releaseDate['9.8.stable']  = '2018-01-17';
$lang->misc->releaseDate['9.7.stable']  = '2017-12-22';
$lang->misc->releaseDate['9.6.stable']  = '2017-11-06';
$lang->misc->releaseDate['9.5.1']       = '2017-09-27';
$lang->misc->releaseDate['9.3.beta']    = '2017-06-21';
$lang->misc->releaseDate['9.1.stable']  = '2017-03-23';
$lang->misc->releaseDate['9.0.beta']    = '2017-01-03';
$lang->misc->releaseDate['8.3.stable']  = '2016-11-09';
$lang->misc->releaseDate['8.2.stable']  = '2016-05-17';
$lang->misc->releaseDate['7.4.beta']    = '2015-11-13';
$lang->misc->releaseDate['7.2.stable']  = '2015-05-22';
$lang->misc->releaseDate['7.1.stable']  = '2015-03-07';
$lang->misc->releaseDate['6.3.stable']  = '2014-11-07';

/* Release Detail. */
$lang->misc->feature->all['18.9'][]        = array('title' => '全面接入AI大模型，客户端引入增强版会议，测试单增加参与人，视频附件增加在线预览，评审检查分类增加自定义等。', 'desc' => '');
$lang->misc->feature->all['18.8'][]        = array('title' => 'BI中新增了度量项功能和应用巡检报告大屏，DevOps平台版增加了配置向导，需求与市场管理界面中增加了市场管理功能，客户端导航及个人中心全新改版。', 'desc' => '');
$lang->misc->feature->all['18.7'][]        = array('title' => 'DevOps新增了云原生平台、制品库和应用管理功能，优化了导航结构和相关UI交互。同时，新增了AI提词设计器功能，支持与大语言模型对接，支持自定义AI应用等。', 'desc' => '');
$lang->misc->feature->all['18.6'][]        = array('title' => '优化了常用列表性能和BI功能的细节，并完善了瀑布项目的功能细节。修复Bug。', 'desc' => '');
$lang->misc->feature->all['18.5'][]        = array('title' => '学堂课程支持从云端导入，支持课程中PDF文件的预览，同时还优化了常用列表的加载速度，修复了多处Bug。', 'desc' => '');
$lang->misc->feature->all['18.4'][]        = array('title' => '本次发布优化了核心列表的性能，兼容达梦数据库，修复了多处Bug。', 'desc' => '');
$lang->misc->feature->all['18.4.beta1'][]  = array('title' => '解Bug。', 'desc' => '');
$lang->misc->feature->all['18.4.alpha1'][] = array('title' => '优化权限、文档交互体验，测试新增场景概念，用例支持xmind导入，并对BI模块中的大屏、透视表、图表、数据表进行了全面升级。', 'desc' => '');
$lang->misc->feature->all['18.3'][]        = array('title' => '二次开发增加语言项自定义,支持对菜单和检索标签的语言项进行定义；二次开发增加编辑器功能，支持用户按需开启和关闭；表单意外退出支持表单暂存，下次进入自动代入填写的未保存信息。', 'desc' => '');
$lang->misc->feature->all['18.2'][]        = array('title' => '新增融合敏捷、融合瀑布管理模型，瀑布项目阶段支持无限级拆分，后台进行全新UI改版。修复Bug。', 'desc' => '');
$lang->misc->feature->all['18.1'][]        = array('title' => '自动化测试解决方案交互优化、新增快照管理功能。禅道客户端实现了 PPT文档在线协作。修复Bug。', 'desc' => '');
$lang->misc->feature->all['18.0'][]        = array('title' => '推出自动化测试解决方案；运营管理界面增加工单功能；审批流支持增加所有类型的通知以及挣值计算规则完善。', 'desc' => '');
$lang->misc->feature->all['18.0.beta3'][]  = array('title' => '统计模块升级为BI，内置5张宏观管理维度大屏。', 'desc' => '');
$lang->misc->feature->all['18.0.beta2'][]  = array('title' => '优化多分支/多平台产品，支持创建孪生需求，计划、版本、发布支持跨分支关联需求和bug，并且禅道客户端实现了机器人会话机制。', 'desc' => '');
$lang->misc->feature->all['18.0.beta1'][]  = array('title' => '主要对禅道多项核心流程进行改进，新增项目型项目、无迭代项目；支持项目跨项目集关联产品；支持轻量管理模式和全生命周期管理模式进行切换。', 'desc' => '');
$lang->misc->feature->all['17.8'][]        = array('title' => '列表状态颜色、仪表盘颜色的改版和任务日志页面的优化。', 'desc' => '');
$lang->misc->feature->all['17.7'][]        = array('title' => '过渡版本表格优化完成。新增工单功能，优化了反馈功能。修复Bug。', 'desc' => '');
$lang->misc->feature->all['17.6.2'][]      = array('title' => '禅道更新叶兰绿、禅道蓝、青春蓝三大主题。实现附件批量上传功能。修复Bug。', 'desc' => '');
$lang->misc->feature->all['17.6.1'][]      = array('title' => '优化了多人任务的处理逻辑，修复Bug。', 'desc' => '');
$lang->misc->feature->all['17.6'][]        = array('title' => '优化了需求的处理逻辑，拆分了用需和软需的权限。甘特图支持手动拖拽维护任务关系。修复Bug。', 'desc' => '');
$lang->misc->feature->all['17.5'][]        = array('title' => '提供高效的可视化统计工具。优化禅道性能，数据库引擎从MyISAM调整为InnoDB。甘特图优化升级，旗舰版的复制项目可以复制任务等更多信息。修复Bug。', 'desc' => '');
$lang->misc->feature->all['17.4'][]        = array('title' => '详情页面的视觉优化和部分页面跳转逻辑优化。看板功能完善。文档创建和编辑页面优化。修复Bug。', 'desc' => '');
$lang->misc->feature->all['17.3'][]        = array('title' => '统计、后台等模块的UI优化，用例库同步用例信息功能优化。修复Bug。', 'desc' => '');
$lang->misc->feature->all['17.2'][]        = array('title' => '调整敏捷项目区块的展示，项目集、项目和测试相关UI优化，细节体验优化。修复Bug。', 'desc' => '');
$lang->misc->feature->all['17.1'][]        = array('title' => '修改执行、项目模块的交互问题，完成客户巴高优先级需求，细节体验优化。修复Bug。', 'desc' => '');
$lang->misc->feature->all['17.0'][]        = array('title' => '细节体验优化。修复Bug。', 'desc' => '');
$lang->misc->feature->all['17.0.beta2'][]  = array('title' => '细节体验优化。修复Bug。', 'desc' => '');
$lang->misc->feature->all['17.0.beta1'][]  = array('title' => '完成客户巴高优先级需求。修复Bug。', 'desc' => '');
$lang->misc->feature->all['16.5'][]        = array('title' => '修复Bug。', 'desc' => '');
$lang->misc->feature->all['16.5.beta1'][]  = array('title' => '将禅道收费版和开源版集成到一个包中，优化升级步骤。', 'desc' => '');
$lang->misc->feature->all['16.4'][]        = array('title' => '实现JIRA导入功能，完善插件扩展机制。', 'desc' => '');
$lang->misc->feature->all['16.3'][]        = array('title' => '看板增加关联计划/发布/版本/迭代功能，细节体验优化。', 'desc' => '');
$lang->misc->feature->all['16.2'][]        = array('title' => '新增专业研发看板，可以创建看板模型项目，修复Bug。', 'desc' => '');
$lang->misc->feature->all['16.1'][]        = array('title' => '计划增加状态管理和看板视图，升级流程优化，修复Bug。', 'desc' => '');
$lang->misc->feature->all['16.0'][]        = array('title' => '新增通用看板，完善分支管理，修复Bug。', 'desc' => '');
$lang->misc->feature->all['16.0.beta1'][]  = array('title' => '新增瀑布模型项目，新增任务看板，完善分支管理和细节，修复Bug。', 'desc' => '');
$lang->misc->feature->all['15.7.1'][]      = array('title' => '修复Bug。', 'desc' => '');
$lang->misc->feature->all['15.7'][]        = array('title' => '新增接口库。修复Bug。', 'desc' => '');
$lang->misc->feature->all['15.6'][]        = array('title' => '修复Bug。', 'desc' => '');
$lang->misc->feature->all['15.5'][]        = array('title' => '增加项目集/产品/项目看板视图、全局添加功能、新手引导。 修复Bug。', 'desc' => '');
$lang->misc->feature->all['15.4'][]        = array('title' => '修复Bug', 'desc' => '');
$lang->misc->feature->all['15.3'][]        = array('title' => '实现界面风格改动和文档优化，修复Bug', 'desc' => '');
$lang->misc->feature->all['15.2'][]        = array('title' => '优化新版本升级流程，增加执行看板。', 'desc' => '');

$lang->misc->feature->all['15.0.3'][]      = array('title' => '修复Bug', 'desc' => '');
$lang->misc->feature->all['15.0.2'][]      = array('title' => '修复Bug', 'desc' => '');
$lang->misc->feature->all['15.0.1'][]      = array('title' => '修复Bug', 'desc' => '');
$lang->misc->feature->all['15.0'][]        = array('title' => '修复Bug', 'desc' => '');
$lang->misc->feature->all['15.0.rc3'][]    = array('title' => '完善细节，修复Bug', 'desc' => '');
$lang->misc->feature->all['15.0.rc2'][]    = array('title' => '修复Bug，优化界面交互', 'desc' => '');
$lang->misc->feature->all['15.0.rc1'][]    = array('title' => '升级到15版本，重构导航、文档库，增加项目集管理', 'desc' => '');
$lang->misc->feature->all['12.5.3'][]      = array('title' => '优化年度总结', 'desc' => '');
$lang->misc->feature->all['12.5.2'][]      = array('title' => '修复Bug', 'desc' => '');
$lang->misc->feature->all['12.5.1'][]      = array('title' => '修复漏洞。', 'desc' => '');
$lang->misc->feature->all['12.5.stable'][] = array('title' => '解决bug，完成高优先级需求。', 'desc' => '');

$lang->misc->feature->all['12.4.4'][] = array('title'=>'兼容专业版和企业版', 'desc' => '');
$lang->misc->feature->all['12.4.3'][] = array('title'=>'修复Bug', 'desc' => '');
$lang->misc->feature->all['12.4.2'][] = array('title'=>'修复Bug', 'desc' => '');
$lang->misc->feature->all['12.4.1'][] = array('title'=>'修复Bug', 'desc' => '');

$lang->misc->feature->all['12.4.stable'][] = array('title'=>'修复Bug', 'desc' => '');

$lang->misc->feature->all['12.3.3'][] = array('title'=>'修复Bug', 'desc' => '');
$lang->misc->feature->all['12.3.2'][] = array('title'=>'修复工作流。', 'desc' => '');
$lang->misc->feature->all['12.3.1'][] = array('title'=>'修复重要程度高的Bug。', 'desc' => '');
$lang->misc->feature->all['12.3'][]   = array('title'=>'集成单元测试，打通持续集成闭环。', 'desc' => '');
$lang->misc->feature->all['12.2'][]   = array('title'=>'增加父子需求，兼容最新喧喧。', 'desc' => '');
$lang->misc->feature->all['12.1'][]   = array('title'=>'增加构建功能', 'desc' => '<p>增加构建功能，集成Jenkins进行构建</p>');
$lang->misc->feature->all['12.0.1'][] = array('title'=>'修复Bug', 'desc' => '');

$lang->misc->feature->all['12.0'][]   = array('title'=>'将代码功能版本浏览功能转移到开源版', 'desc' => '');
$lang->misc->feature->all['12.0'][]   = array('title'=>'增加年度总结', 'desc' => '根据角色显示年度总结。');
$lang->misc->feature->all['12.0'][]   = array('title'=>'完善细节，修复Bug', 'desc' => '');

$lang->misc->feature->all['11.7'][]   = array('title'=>'完善细节，修复Bug', 'desc' => '<p>增加用户是否使用敏捷概念的选择</p><p>webhook类型中增加企业微信</p><p>实现到钉钉个人消息的通知</p>');
$lang->misc->feature->all['11.6.5'][] = array('title'=>'修复Bug', 'desc' => '');
$lang->misc->feature->all['11.6.4'][] = array('title'=>'完善细节，修复Bug', 'desc' => '');
$lang->misc->feature->all['11.6.3'][] = array('title'=>'修复Bug', 'desc' => '');
$lang->misc->feature->all['11.6.2'][] = array('title'=>'完善细节，修复Bug', 'desc' => '');
$lang->misc->feature->all['11.6.1'][] = array('title'=>'完善细节，修复Bug', 'desc' => '');

$lang->misc->feature->all['11.6.stable'][] = array('title'=>'改善国际版界面', 'desc' => '');
$lang->misc->feature->all['11.6.stable'][] = array('title'=>'添加翻译功能', 'desc' => '');

$lang->misc->feature->all['11.5.2'][] = array('title'=>'增加禅道安全性，增加登录禅道弱口令检查', 'desc' => '');
$lang->misc->feature->all['11.5.1'][] = array('title'=>'新增第三方应用免密登录禅道，修复Bug', 'desc' => '');

$lang->misc->feature->all['11.5.stable'][] = array('title'=>'完善细节，修复Bug', 'desc' => '');
$lang->misc->feature->all['11.5.stable'][] = array('title'=>'新增动态过滤机制', 'desc' => '');
$lang->misc->feature->all['11.5.stable'][] = array('title'=>'集成新版本客户端', 'desc' => '');

$lang->misc->feature->all['11.4.1'][]      = array('title'=>'完善细节，修复Bug', 'desc' => '');

$lang->misc->feature->all["11.4.stable"][] = array("title"=>"完善细节，修复Bug", "desc" => "<p>增强测试任务管理</p><p>优化计划、发布、版本关联{$lang->SRCommon}和bug的交互</p><p>文档库可以自定义是否显示子分类里的文档</p><p>修复bug，完善细节</p>");

$lang->misc->feature->all['11.3.stable'][] = array('title'=>'完善细节，修复Bug', 'desc' => '<p>计划添加子计划功能</p><p>优化chosen交互</p><p>添加时区设置</p><p>优化文档库和文档</p>');

$lang->misc->feature->all['11.2.stable'][] = array('title'=>'完善细节，修复Bug', 'desc' => '<p>增加升级日志和升级后数据库检查的功能</p><p>修复禅道集成客户端和其他若干bug，完善细节</p>');

$lang->misc->feature->all['11.1.stable'][] = array('title'=>'主要修复Bug。', 'desc' => '');

$lang->misc->feature->all['11.0.stable'][] = array('title'=>'禅道集成喧喧', 'desc' => '');

$lang->misc->feature->all['10.6.stable'][] = array('title'=>'调整备份机制', 'desc' => '<p>增加备份设置，备份更加灵活</p><p>显示备份进度</p><p>可以更改备份目录</p>');
$lang->misc->feature->all['10.6.stable'][] = array('title'=>'优化和调整菜单', 'desc' => '<p>调整后台菜单</p><p>调整我的地盘和项目的二级菜单</p>');

$lang->misc->feature->all['10.5.stable'][] = array('title'=>'调整文档显示', 'desc' => '<p>调整文档库左侧的布局方式</p><p>文档库左侧导航底部增加筛选条件</p>');
$lang->misc->feature->all['10.5.stable'][] = array('title'=>'调整子任务逻辑，优化父子任务显示。', 'desc' => '');

$lang->misc->feature->all['10.4.stable'][] = array('title'=>'优化调整新界面', 'desc' => '<p>详情页面还原我们之前的排版布局</p><p>重构添加用户页面的表单</p><p>用例执行时，如果用户手工选择了通过，写结果的时候不要更新用例状态</p>');
$lang->misc->feature->all['10.4.stable'][] = array('title'=>'用户机器休眠登录失效后，重新刷新session', 'desc' => '');
$lang->misc->feature->all['10.4.stable'][] = array('title'=>'提升现有的接口机制', 'desc' => '');

$lang->misc->feature->all['10.3.stable'][] = array('title'=>'修复Bug', 'desc' => '');
$lang->misc->feature->all['10.2.stable'][] = array('title'=>'集成喧喧IM', 'desc' => '');

$lang->misc->feature->all['10.0.stable'][] = array('title'=>'全新的界面和交互体验', 'desc' => '<ol><li>全新的我的地盘</li><li>全新的动态页面</li><li>全新的产品主页</li><li>全新的产品概况</li><li>全新的路线图</li><li>全新的项目主页</li><li>全新的项目概况</li><li>全新的测试主页</li><li>全新的文档主页</li><li>我的地盘新增工作统计区块</li><li>我的地盘待办区块可以直接添加、编辑、完成待办</li><li>产品主页新增产品统计区块</li><li>产品主页新增产品总览区块</li><li>项目主页新增项目统计区块</li><li>项目主页新增项目总览区块</li><li>测试主页新增测试统计区块</li><li>所有产品、产品主页、所有项目、项目主页、测试主页等按钮从二级导航右侧移动到了左侧</li><li>项目任务列表看板、燃尽图、树状图、分组查看等按钮从三级导航中移动到二级导航中，树状图、分组查看和任务列表集成到一个下拉列表中</li><li>项目下二级导航中Bug、版本、测试单三个跟测试相关的导航集成到一个下拉列表中</li><li>版本、测试单列表按照产品分组展示，布局更加合理</li><li>文档左侧增加树状图显示</li><li>文档增加快速访问功能，包括最近更新、我的文档、我的收藏三个入口</li><li>文档增加收藏功能</li><ol>');

$lang->misc->feature->all['9.8.stable'][] = array('title'=>'实现集中的消息处理机制', 'desc' => '<p>邮件，短信，webhook都放统一的消息发送</p><p>移植ZDOO里面的消息通知功能</p>');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'实现周期性待办功能', 'desc' => '');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'增加指派给我的区块', 'desc' => '');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'项目可以选择多个测试单生成报告', 'desc' => '');

$lang->misc->feature->all['9.7.stable'][] = array('title'=>'调整国际版，增加英文Demo数据。', 'desc' => '');

$lang->misc->feature->all['9.6.stable'][] = array('title'=>'新增了webhook功能', 'desc' => '实现与倍冾、钉钉的消息通知接口');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'新增禅道操作获取积分的功能', 'desc' => '');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'项目任务新增了多人任务和子任务功能', 'desc' => '');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'产品视图新增了产品线功能', 'desc' => '');

$lang->misc->feature->all['9.5.1'][] = array('title'=>'新增受限操作', 'desc' => '');

$lang->misc->feature->all['9.3.beta'][] = array('title'=>'升级框架，增强程序安全', 'desc' => '');

$lang->misc->feature->all['9.1.stable'][] = array('title'=>'完善测试视图', 'desc' => '<p>增加测试套件、公共测试库和测试总结功能</p>');
$lang->misc->feature->all['9.1.stable'][] = array('title'=>'支持测试步骤分组', 'desc' => '');

$lang->misc->feature->all['9.0.beta'][] = array('title'=>'增加禅道云发信功能', 'desc' => '<p>禅道云发信是禅道联合SendCloud推出的一项免费发信服务，只有用户绑定禅道，并通过验证即可使用。</p>');
$lang->misc->feature->all['9.0.beta'][] = array('title'=>'优化富文本编辑器和markdown编辑器', 'desc' => '');

$lang->misc->feature->all['8.3.stable'][] = array('title'=>'调整文档功能', 'desc' => '<p>增加文档模块首页，重新组织文档库结构，增加权限</p><p>多种文件浏览方式，文档支持Markdown，增加文档权限管理，增加文件版本管理。</p>');

$lang->misc->feature->all['8.2.stable'][] = array('title'=>'首页自定义', 'desc' => '<p>我的地盘由我做主。现在开始，你可以向首页添加多种多样的内容区块，而且还可以决定如何排列和显示他们。</p><p>我的地盘、产品、项目、测试模块下均支持首页自定义功能。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'导航定制', 'desc' => '<p>导航上显示的项目现在完全由你来决定，不仅仅可以决定在导航上展示哪些内容，还可以决定展示的顺序。</p><p>将鼠标悬浮在导航上稍后会在右侧显示定制按钮，点击打开定制对话框，通过点击切换是否显示，拖放操作来更改显示顺序。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'批量添加、编辑自定义', 'desc' => '<p>可以在批量添加和批量编辑页面自定义操作的字段。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>"添加{$lang->SRCommon}、任务、Bug、用例自定义", 'desc' => "<p>可以在添加{$lang->SRCommon}、任务、Bug、用例页面，自定义部分字段是否显示。</p>");
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'导出自定义', 'desc' => "<p>在导出{$lang->SRCommon}、任务、Bug、用例的时候，用户可以自定义导出的字段，也可以保存模板方便每次导出。</p>");
$lang->misc->feature->all['8.2.stable'][] = array('title'=>"{$lang->SRCommon}、任务、Bug、用例组合检索功能", 'desc' => "<p>在{$lang->SRCommon}、任务、Bug、用例列表页面，可以实现模块和标签的组合检索。</p>");
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'增加新手教程', 'desc' => '<p>增加新手教程，方便新用户了解禅道使用。</p>');

$lang->misc->feature->all['7.4.beta'][] = array('title'=>'产品实现分支功能', 'desc' => "<p>产品增加平台/分支类型，相应的{$lang->SRCommon}、计划、Bug、用例、模块等都增加分支。</p>");
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'调整发布模块', 'desc' => '<p>发布增加停止维护操作，当发布停止维护时，创建Bug将不显示这个发布。</p><p>发布中遗留的bug改为手工关联。</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>"调整{$lang->SRCommon}和Bug的创建页面", 'desc' => '');

$lang->misc->feature->all['7.2.stable'][] = array('title'=>'增强安全', 'desc' => '<p>加强对管理员弱口令的检查。</p><p>写插件，上传插件的时候需要创建ok文件。</p><p>敏感操作增加管理员口令的检查</p><p>对输入内容做striptags, specialchars处理。</p>');
$lang->misc->feature->all['7.2.stable'][] = array('title'=>'完善细节', 'desc' => '');

$lang->misc->feature->all['7.1.stable'][] = array('title'=>'提供计划任务框架', 'desc' => '增加计划任务框架，加入每日提醒、更新燃尽图、备份、发信等重要任务。');
$lang->misc->feature->all['7.1.stable'][] = array('title'=>'提供rpm和deb包', 'desc' => '');

$lang->misc->feature->all['6.3.stable'][] = array('title'=>'增加数据表格功能', 'desc' => '<p>可配置数据表格中可显示的字段，按照配置字段显示想看的数据</p>');
$lang->misc->feature->all['6.3.stable'][] = array('title'=>'继续完善细节', 'desc' => '');
