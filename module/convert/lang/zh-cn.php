<?php
/**
 * The convert module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: zh-cn.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->convert->common  = '从其他系统导入';
$lang->convert->index   = '首页';

$lang->convert->start   = '开始转换';
$lang->convert->desc    = <<<EOT
<p>欢迎使用系统转换向导，本程序会帮助您将其他系统的数据转换到禅道项目管理系统中。</p>
<strong>转换存在一定的风险，转换之前，我们强烈建议您备份数据库及相应的数据文件，并保证转换的时候，没有其他人进行操作。</strong>
EOT;

$lang->convert->setConfig      = '来源系统配置';
$lang->convert->setBugfree     = 'Bugfree配置';
$lang->convert->setRedmine     = 'Redmine配置';
$lang->convert->checkBugFree   = '检查Bugfree';
$lang->convert->checkRedmine   = '检查Redmine';
$lang->convert->convertRedmine = '转换Redmine';
$lang->convert->convertBugFree = '转换BugFree';

$lang->convert->selectSource     = '选择来源系统及版本';
$lang->convert->mustSelectSource = "必须选择一个来源。";

$lang->convert->direction             = "请选择{$lang->executionCommon}问题转换方向";
$lang->convert->questionTypeOfRedmine = 'Redmine中问题类型';
$lang->convert->aimTypeOfZentao       = '转化为Zentao中的类型';

$lang->convert->jiraUserMode = array();
$lang->convert->jiraUserMode['account'] = '使用Jira帐号';
$lang->convert->jiraUserMode['email']   = '使用Jira邮箱';

$lang->convert->confluenceUserMode = array();
$lang->convert->confluenceUserMode['account'] = '使用Confluence帐号';
$lang->convert->confluenceUserMode['email']   = '使用Confluence邮箱';

$lang->convert->directionList['bug']   = 'Bug';
$lang->convert->directionList['task']  = '任务';
$lang->convert->directionList['story'] = $lang->SRCommon;

$lang->convert->sourceList['BugFree'] = array('bugfree_1' => '1.x', 'bugfree_2' => '2.x');
$lang->convert->sourceList['Redmine'] = array('Redmine_1.1' => '1.1');

$lang->convert->setting     = '设置';
$lang->convert->checkConfig = '检查配置';
$lang->convert->add         = '新增';
$lang->convert->title       = '标题';

$lang->convert->ok          = '<span class="text-success"><i class="icon-check-sign"></i> 检查通过</span>';
$lang->convert->fail        = '<span class="text-danger"><i class="icon-remove-sign"></i> 检查失败</span>';

$lang->convert->dbHost      = '数据库服务器';
$lang->convert->dbPort      = '服务器端口';
$lang->convert->dbUser      = '数据库用户名';
$lang->convert->dbPassword  = '数据库密码';
$lang->convert->dbName      = '%s使用的库';
$lang->convert->dbCharset   = '%s数据库编码';
$lang->convert->dbPrefix    = '%s表前缀';
$lang->convert->installPath = '%s安装的根目录';

$lang->convert->checkDB    = '数据库';
$lang->convert->checkTable = '表';
$lang->convert->checkPath  = '安装路径';

$lang->convert->execute    = '执行转换';
$lang->convert->item       = '转换项';
$lang->convert->count      = '转换数量';
$lang->convert->info       = '转换信息';

$lang->convert->bugfree = new stdclass();
$lang->convert->bugfree->users      = '用户';
$lang->convert->bugfree->executions = $lang->executionCommon;
$lang->convert->bugfree->modules    = '模块';
$lang->convert->bugfree->bugs       = 'Bug';
$lang->convert->bugfree->cases      = '测试用例';
$lang->convert->bugfree->results    = '测试结果';
$lang->convert->bugfree->actions    = '历史记录';
$lang->convert->bugfree->files      = '附件';

$lang->convert->redmine = new stdclass();
$lang->convert->redmine->users        = '用户';
$lang->convert->redmine->groups       = '用户分组';
$lang->convert->redmine->products     = $lang->productCommon;
$lang->convert->redmine->executions   = $lang->executionCommon;
$lang->convert->redmine->stories      = $lang->SRCommon;
$lang->convert->redmine->tasks        = '任务';
$lang->convert->redmine->bugs         = 'Bug';
$lang->convert->redmine->productPlans = $lang->productCommon . '计划';
$lang->convert->redmine->teams        = '团队';
$lang->convert->redmine->releases     = '发布';
$lang->convert->redmine->builds       = 'Build';
$lang->convert->redmine->docLibs      = '文档库';
$lang->convert->redmine->docs         = '文档';
$lang->convert->redmine->files        = '附件';

$lang->convert->errorFileNotExits  = '文件 %s 不存在';
$lang->convert->errorUserExists    = '用户 %s 已存在';
$lang->convert->errorGroupExists   = '分组 %s 已存在';
$lang->convert->errorBuildExists   = 'Build %s 已存在';
$lang->convert->errorReleaseExists = '发布 %s 已存在';
$lang->convert->errorCopyFailed    = '文件 %s 拷贝失败';
$lang->convert->importFailed       = '导入失败';

$lang->convert->setParam = '请设置转换参数';

$lang->convert->statusType = new stdclass();
$lang->convert->priType    = new stdclass();

$lang->convert->aimType           = '问题类型转换';
$lang->convert->statusType->bug   = '状态类型转换(Bug状态)';
$lang->convert->statusType->story = '状态类型转换(Story状态)';
$lang->convert->statusType->task  = '状态类型转换(Task状态)';
$lang->convert->priType->bug      = '优先级类型转换(Bug状态)';
$lang->convert->priType->story    = '优先级类型转换(Story状态)';
$lang->convert->priType->task     = '优先级类型转换(Task状态)';

$lang->convert->issue = new stdclass();
$lang->convert->issue->redmine = 'Redmine';
$lang->convert->issue->zentao  = '禅道';
$lang->convert->issue->goto    = '转换为';

$lang->convert->jira = new stdclass();
$lang->convert->jira->method           = '选择导入方式';
$lang->convert->jira->back             = '上一步';
$lang->convert->jira->next             = '下一步';
$lang->convert->jira->importFromDB     = '从数据库导入';
$lang->convert->jira->importFromFile   = '从文件导入';
$lang->convert->jira->mapJira2Zentao   = '设置Jira与禅道数据对应关系';
$lang->convert->jira->database         = 'Jira数据库';
$lang->convert->jira->domain           = 'Jira域名';
$lang->convert->jira->admin            = 'Jira管理员帐号';
$lang->convert->jira->token            = 'Jira密码/Token';
$lang->convert->jira->dbNameNotice     = '请输入Jira数据库名字';
$lang->convert->jira->importNotice     = '注意：导入数据有风险！请务必确保如下操作步骤依次完成，再进行合并。';
$lang->convert->jira->accountNotice    = '使用邮箱的会将@前面的字符串作为用户名，超过30位的会被截断处理。';
$lang->convert->jira->userExceeds      = '当前系统授权人数为%s，请确认授权人数导入后是否超出，超出将会终止导入。';
$lang->convert->jira->apiError         = '无法连接到JiraAPI接口，请检查您的Jira域名和帐号、密码/Token信息。';
$lang->convert->jira->dbDesc           = '如果您的Jira是本地部署版本, 请选择此方式';
$lang->convert->jira->fileDesc         = '如果您的Jira是云版本或不方便获取数据库, 请选择此方式';
$lang->convert->jira->jiraObject       = 'Jira Issues';
$lang->convert->jira->zentaoObject     = '禅道对象';
$lang->convert->jira->jiraLinkType     = 'Jira 关联关系';
$lang->convert->jira->zentaoLinkType   = '禅道关联关系';
$lang->convert->jira->jiraResolution   = 'Jira 解决方案';
$lang->convert->jira->zentaoResolution = '禅道Bug解决方案';
$lang->convert->jira->zentaoReason     = '禅道需求关闭原因';
$lang->convert->jira->jiraStatus       = 'Jira Issues 状态';
$lang->convert->jira->storyStatus      = '禅道需求状态';
$lang->convert->jira->storyStage       = '禅道需求阶段';
$lang->convert->jira->bugStatus        = '禅道Bug状态';
$lang->convert->jira->taskStatus       = '禅道任务状态';
$lang->convert->jira->objectField      = '对象字段映射';
$lang->convert->jira->objectStatus     = '对象状态映射';
$lang->convert->jira->objectAction     = '对象动作映射';
$lang->convert->jira->objectResolution = '对象解决方案映射';
$lang->convert->jira->jiraField        = 'Jira%s字段';
$lang->convert->jira->jiraStatus       = 'Jira%s状态';
$lang->convert->jira->jiraAction       = 'Jira%s动作';
$lang->convert->jira->jiraResolution   = 'Jira%s解决方案';
$lang->convert->jira->zentaoField      = '禅道%s字段';
$lang->convert->jira->zentaoStatus     = '禅道%s状态';
$lang->convert->jira->zentaoStage      = '禅道%s阶段';
$lang->convert->jira->zentaoAction     = '禅道%s动作';
$lang->convert->jira->zentaoReason     = '禅道%s关闭原因';
$lang->convert->jira->zentaoResolution = '禅道%s解决方案';
$lang->convert->jira->initJiraUser     = '设置Jira用户';
$lang->convert->jira->importJira       = '导入Jira';
$lang->convert->jira->start            = '开始导入';

$lang->convert->jira->dbNameEmpty        = 'Jira数据库名字不能为空！';
$lang->convert->jira->invalidDB          = '无效的数据库名！';
$lang->convert->jira->invalidTable       = '本数据库非Jira数据库！';
$lang->convert->jira->notReadAndWrite    = '目录不存在或权限不足！请创建目录%s目录并赋予读写权限！';
$lang->convert->jira->notExistEntities   = '%s 文件不存在！';
$lang->convert->jira->passwordNotice     = '设置用户导入到禅道后的默认密码，用户后续可以在禅道中自行修改密码。';
$lang->convert->jira->groupNotice        = '设置用户导入到禅道后的默认权限分组。';
$lang->convert->jira->mapObjectNotice    = '选择映射关系时，如果选择新增成工作流，导入后将自动在工作流中创建一个新对象。';
$lang->convert->jira->mapFieldNotice     = 'jira内置字段已自动匹配，请选择自定义字段的映射关系，选择映射关系时，若选择新增，导入后将自动创建新字段，未选择的字段则不会导入。';
$lang->convert->jira->mapStatusNotice    = '选择映射关系时，未选择的状态导入后默认匹配为%s。';
$lang->convert->jira->mapReasonNotice    = '选择映射关系时，若选择新增，导入后将自动创建新解决方案，未选择的解决方案导入后默认匹配为已完成。';
$lang->convert->jira->mapRelationNotice  = '选择映射关系时，若选择新增，导入后将自动创建关联关系，未选择的关联关系不导入。';
$lang->convert->jira->changeItems        = "修改了%s，旧值为‘%s’, 新值为‘%s’。";
$lang->convert->jira->passwordDifferent  = '两次密码不一致！';
$lang->convert->jira->passwordEmpty      = '密码不能为空！';
$lang->convert->jira->passwordLess       = '密码不能少于六位！';
$lang->convert->jira->importSuccessfully = 'Jira导入完成！';
$lang->convert->jira->importResult       = "导入 <strong class='text-danger'>%s</strong> 数据, 已处理 <strong class='%scount'>%s</strong> 条记录；";
$lang->convert->jira->importing          = '数据导入中，请不要切换其它页面';
$lang->convert->jira->importingAB        = '数据导入中';
$lang->convert->jira->imported           = '数据导入完成';
$lang->convert->jira->restore            = '上次导入信息没有完成，是否从上次流程继续填写？';

$lang->convert->jira->zentaoObjectList['']            = '';
$lang->convert->jira->zentaoObjectList['epic']        = '业务需求';
$lang->convert->jira->zentaoObjectList['requirement'] = '用户需求';
$lang->convert->jira->zentaoObjectList['story']       = '软件需求';
$lang->convert->jira->zentaoObjectList['task']        = '任务';
$lang->convert->jira->zentaoObjectList['testcase']    = '用例';
$lang->convert->jira->zentaoObjectList['bug']         = 'Bug';

$lang->convert->jira->zentaoLinkTypeList['subTaskLink']  = '父-子任务';
$lang->convert->jira->zentaoLinkTypeList['subStoryLink'] = '父-子需求';
$lang->convert->jira->zentaoLinkTypeList['duplicate']    = '重复对象';
$lang->convert->jira->zentaoLinkTypeList['relates']      = '互相关联';

$lang->convert->jira->steps['object']     = '对象映射';
$lang->convert->jira->steps['objectData'] = '对象数据映射';
$lang->convert->jira->steps['relation']   = '全局关联关系映射';
$lang->convert->jira->steps['user']       = '导入Jira用户';
$lang->convert->jira->steps['confirme']   = '导入数据确认';

$lang->convert->jira->importSteps['db'][1]   = '备份禅道数据库，备份Jira数据库。';
$lang->convert->jira->importSteps['db'][2]   = '导入数据时使用禅道会给服务器造成性能压力，请尽量保证导入数据时无人使用禅道。';
$lang->convert->jira->importSteps['db'][3]   = '将Jira数据库导入到禅道使用的Mysql中，名字和禅道数据库区别开来。';
$lang->convert->jira->importSteps['db'][4]   = "将Jira附件目录<strong class='text-danger'> attachments</strong> 放到 <strong class='text-danger'>%s</strong> 下，确保禅道服务器磁盘空间足够。";
$lang->convert->jira->importSteps['db'][5]   = "上述步骤完成后，请输入Jira数据库名字进行下一步。";

$lang->convert->jira->importSteps['file'][1] = '备份禅道数据库，备份Jira文件。';
$lang->convert->jira->importSteps['file'][2] = '导入数据时使用禅道会给服务器造成性能压力，请尽量保证导入数据时无人使用禅道。';
$lang->convert->jira->importSteps['file'][3] = "将Jira的备份文件 <strong class='text-danger'>entities.xml</strong> 放到 <strong class='text-danger'>%s</strong> 下，并给该目录读写权限。";
$lang->convert->jira->importSteps['file'][4] = "将Jira附件目录<strong class='text-danger'> attachments</strong> 放到 <strong class='text-danger'>%s</strong> 下，确保禅道服务器磁盘空间足够。";
$lang->convert->jira->importSteps['file'][5] = "为了保证导入数据的完整性，请输入当前Jira环境的域名、管理员帐号、密码/Token。";
$lang->convert->jira->importSteps['file'][6] = "上述步骤完成后，点击下一步。";

$lang->convert->jira->objectList['user']       = '用户';
$lang->convert->jira->objectList['project']    = '项目';
$lang->convert->jira->objectList['issue']      = 'Issue';
$lang->convert->jira->objectList['build']      = '构建';
$lang->convert->jira->objectList['issuelink']  = '关联关系';
$lang->convert->jira->objectList['worklog']    = '工作日志';
$lang->convert->jira->objectList['action']     = '评论';
$lang->convert->jira->objectList['changeitem'] = '变更记录';
$lang->convert->jira->objectList['file']       = '附件';

$lang->convert->jira->buildinFields = array();
$lang->convert->jira->buildinFields['summary']              = array('name'=> '标题',     'jiraField' => 'summary',              'control' => 'input',        'optionType' => 'custom', 'type' => 'varchar',    'length' => '255', 'buildin' => false);
$lang->convert->jira->buildinFields['pri']                  = array('name'=> '优先级',   'jiraField' => 'priority',             'control' => 'select',       'optionType' => 'custom', 'type' => 'int',        'length' => '3', 'buildin' => false);
$lang->convert->jira->buildinFields['resolution']           = array('name'=> '解决方案', 'jiraField' => 'resolution',           'control' => 'select',       'optionType' => 'custom', 'type' => 'varchar',    'length' => '255', 'buildin' => false);
$lang->convert->jira->buildinFields['reporter']             = array('name'=> '报告人',   'jiraField' => 'reporter',             'control' => 'select',       'optionType' => 'user',   'type' => 'varchar',    'length' => '255');
$lang->convert->jira->buildinFields['duedate']              = array('name'=> '截止日期', 'jiraField' => 'duedate',              'control' => 'date',         'optionType' => 'custom', 'type' => 'date',       'length' => '0', 'buildin' => false);
$lang->convert->jira->buildinFields['resolutiondate']       = array('name'=> '解决时间', 'jiraField' => 'resolutiondate',       'control' => 'datetime',     'optionType' => 'custom', 'type' => 'datetime',   'length' => '0', 'buildin' => false);
$lang->convert->jira->buildinFields['votes']                = array('name'=> '投票数',   'jiraField' => 'votes',                'control' => 'integer',      'optionType' => 'custom', 'type' => 'int',        'length' => '6');
$lang->convert->jira->buildinFields['environment']          = array('name'=> '环境信息', 'jiraField' => 'environment',          'control' => 'textarea',     'optionType' => 'custom', 'type' => 'text',       'length' => '0');
$lang->convert->jira->buildinFields['timeoriginalestimate'] = array('name'=> '预估工时', 'jiraField' => 'timeoriginalestimate', 'control' => 'decimal',      'optionType' => 'custom', 'type' => 'decimal',    'length' => '0');
$lang->convert->jira->buildinFields['timespent']            = array('name'=> '消耗工时', 'jiraField' => 'timespent',            'control' => 'decimal',      'optionType' => 'custom', 'type' => 'decimal',    'length' => '0');
$lang->convert->jira->buildinFields['desc']                 = array('name'=> '描述',     'jiraField' => 'description',          'control' => 'richtext',     'optionType' => 'custom', 'type' => 'mediumtext', 'length' => '0', 'buildin' => false);
