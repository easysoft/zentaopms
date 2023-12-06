<?php
/**
 * The admin module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: zh-cn.php 4767 2013-05-05 06:10:13Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->admin->index           = '后台管理首页';
$lang->admin->sso             = 'ZDOO集成';
$lang->admin->ssoAction       = 'ZDOO集成';
$lang->admin->safeIndex       = '密码安全设置';
$lang->admin->checkWeak       = '弱口令检查';
$lang->admin->certifyMobile   = '认证手机';
$lang->admin->certifyEmail    = '认证邮箱';
$lang->admin->ztCompany       = '认证公司';
$lang->admin->captcha         = '验证码';
$lang->admin->getCaptcha      = '获取验证码';
$lang->admin->register        = '登记';
$lang->admin->resetPWDSetting = '重置密码设置';
$lang->admin->tableEngine     = '表引擎';
$lang->admin->setModuleIndex  = '系统功能配置';

$lang->admin->mon              = '月';
$lang->admin->day              = '天';
$lang->admin->updateDynamics   = '更新动态';
$lang->admin->updatePatch      = '补丁更新';
$lang->admin->upgradeRecommend = '推荐升级';
$lang->admin->zentaoUsed       = '您已使用禅道';

$lang->admin->api                  = '接口';
$lang->admin->log                  = '日志';
$lang->admin->setting              = '设置';
$lang->admin->pluginRecommendation = '插件推荐';
$lang->admin->zentaoInfo           = '禅道信息';
$lang->admin->officialAccount      = '官方公众号';
$lang->admin->publicClass          = '公开课';
$lang->admin->days                 = '日志保存天数';
$lang->admin->resetPWDByMail       = '通过邮箱重置密码';
$lang->admin->followUs             = '扫码关注公众号';
$lang->admin->followUsContent      = '随时查看禅道动态、活动信息、也可获取帮助支持';

$lang->admin->changeEngine               = "更换到InnoDB";
$lang->admin->changingTable              = '正在更换数据表%s引擎...';
$lang->admin->changeSuccess              = '已经更换数据表%s引擎为InnoDB。';
$lang->admin->changeFail                 = "更换数据表%s引擎失败，原因：<span class='text-red'>%s</span>。";
$lang->admin->errorInnodb                = '您当前的数据库不支持使用InnoDB数据表引擎。';
$lang->admin->changeFinished             = "更换数据库引擎完毕。";
$lang->admin->engineInfo                 = "表<strong>%s</strong>的引擎是<strong>%s</strong>。";
$lang->admin->engineSummary['hasMyISAM'] = "有%s个表不是InnoDB引擎";
$lang->admin->engineSummary['allInnoDB'] = "所有的表都是InnoDB引擎了";

$lang->admin->info = new stdclass();
$lang->admin->info->version = '当前系统的版本是%s，';
$lang->admin->info->links   = '您可以访问以下链接：';
$lang->admin->info->account = "您的禅道社区账户为%s。";
$lang->admin->info->log     = '超出存天数的日志会被删除，需要开启计划任务。';

$lang->admin->notice = new stdclass();
$lang->admin->notice->register = "可%s禅道社区 www.zentao.net，及时获得禅道最新信息。";
$lang->admin->notice->ignore   = "不再提示";
$lang->admin->notice->int      = "『%s』应当是正整数。";

$lang->admin->registerNotice = new stdclass();
$lang->admin->registerNotice->common     = '注册新帐号';
$lang->admin->registerNotice->caption    = '禅道社区登记';
$lang->admin->registerNotice->click      = '点击此处';
$lang->admin->registerNotice->lblAccount = '请设置您的用户名，英文字母和数字的组合，三位以上。';
$lang->admin->registerNotice->lblPasswd  = '请设置您的密码。数字和字母的组合，六位以上。';
$lang->admin->registerNotice->submit     = '登记';
$lang->admin->registerNotice->submitHere = '在此登记';
$lang->admin->registerNotice->bind       = "绑定已有帐号";
$lang->admin->registerNotice->success    = "登记账户成功";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = '关联社区帐号';
$lang->admin->bind->success = "关联账户成功";
$lang->admin->bind->submit  = "绑定";

$lang->admin->setModule = new stdclass();
$lang->admin->setModule->module         = '功能点';
$lang->admin->setModule->optional       = '可选功能';
$lang->admin->setModule->opened         = '已开启';
$lang->admin->setModule->closed         = '已关闭';

$lang->admin->setModule->my             = '地盘';
$lang->admin->setModule->product        = $lang->productCommon;
$lang->admin->setModule->scrum          = '敏捷' . $lang->projectCommon;
$lang->admin->setModule->waterfall      = '瀑布' . $lang->projectCommon;
$lang->admin->setModule->agileplus      = '融合敏捷' . $lang->projectCommon;
$lang->admin->setModule->waterfallplus  = '融合瀑布' . $lang->projectCommon;
$lang->admin->setModule->assetlib       = '资产库';
$lang->admin->setModule->other          = '通用功能';

$lang->admin->setModule->score          = '积分';
$lang->admin->setModule->repo           = '代码';
$lang->admin->setModule->issue          = '问题';
$lang->admin->setModule->risk           = '风险';
$lang->admin->setModule->opportunity    = '机会';
$lang->admin->setModule->process        = '过程';
$lang->admin->setModule->measrecord     = '度量';
$lang->admin->setModule->auditplan      = 'QA';
$lang->admin->setModule->meeting        = '会议';
$lang->admin->setModule->roadmap        = '路线图';
$lang->admin->setModule->track          = '矩阵';
$lang->admin->setModule->UR             = $lang->URCommon;
$lang->admin->setModule->researchplan   = '调研';
$lang->admin->setModule->gapanalysis    = '培训';
$lang->admin->setModule->storylib       = '需求库';
$lang->admin->setModule->caselib        = '用例库';
$lang->admin->setModule->issuelib       = '问题库';
$lang->admin->setModule->risklib        = '风险库';
$lang->admin->setModule->opportunitylib = '机会库';
$lang->admin->setModule->practicelib    = '最佳实践库';
$lang->admin->setModule->componentlib   = '组件库';
$lang->admin->setModule->devops         = 'DevOps';
$lang->admin->setModule->kanban         = '通用看板';
$lang->admin->setModule->OA             = '办公';
$lang->admin->setModule->deploy         = '运维';
$lang->admin->setModule->traincourse    = '学堂';

$lang->admin->safe = new stdclass();
$lang->admin->safe->common                   = '安全策略';
$lang->admin->safe->set                      = '密码安全设置';
$lang->admin->safe->password                 = '密码安全';
$lang->admin->safe->weak                     = '常用弱口令';
$lang->admin->safe->reason                   = '类型';
$lang->admin->safe->checkWeak                = '弱口令扫描';
$lang->admin->safe->changeWeak               = '修改弱口令密码';
$lang->admin->safe->loginCaptcha             = '登录使用验证码';
$lang->admin->safe->modifyPasswordFirstLogin = '首次登录修改密码';
$lang->admin->safe->passwordStrengthWeak     = '密码强度小于系统设置';

$lang->admin->safe->modeList[0] = '不检查';
$lang->admin->safe->modeList[1] = '中';
$lang->admin->safe->modeList[2] = '强';

$lang->admin->safe->modeRuleList[1] = '6位及以上，包含大小写字母，数字。';
$lang->admin->safe->modeRuleList[2] = '10位及以上，包含大小写字母，数字，特殊字符。';

$lang->admin->safe->reasonList['weak']     = '常用弱口令';
$lang->admin->safe->reasonList['account']  = '与帐号相同';
$lang->admin->safe->reasonList['mobile']   = '与手机相同';
$lang->admin->safe->reasonList['phone']    = '与电话相同';
$lang->admin->safe->reasonList['birthday'] = '与生日相同';

$lang->admin->safe->modifyPasswordList[1] = '必须修改';
$lang->admin->safe->modifyPasswordList[0] = '不强制';

$lang->admin->safe->loginCaptchaList[1] = '是';
$lang->admin->safe->loginCaptchaList[0] = '否';

$lang->admin->safe->resetPWDList[1] = '开启';
$lang->admin->safe->resetPWDList[0] = '关闭';

$lang->admin->safe->noticeMode     = '系统会在创建和修改用户、修改密码的时候检查用户口令。';
$lang->admin->safe->noticeWeakMode = '系统会在登录、创建和修改用户、修改密码的时候检查用户口令。';
$lang->admin->safe->noticeStrong   = '密码长度越长，含有大写字母或数字或特殊符号越多，密码字母越不重复，安全度越强！';
$lang->admin->safe->noticeGd       = '系统检测到您的服务器未安装GD模块或未启用FreeType支持，无法使用验证码功能，请安装后使用。';

$lang->admin->menuSetting['system']['name']    = '系统设置';
$lang->admin->menuSetting['system']['desc']    = '备份、聊天、安全等系统各要素配置。';
$lang->admin->menuSetting['user']['name']      = '人员管理';
$lang->admin->menuSetting['user']['desc']      = '维护部门、添加人员、分组配置权限。';
$lang->admin->menuSetting['switch']['name']    = '功能开关';
$lang->admin->menuSetting['switch']['desc']    = '打开、关闭系统部分功能。';
$lang->admin->menuSetting['model']['name']     = '模型配置';
$lang->admin->menuSetting['model']['desc']     = '不同项目管理模型和项目通用要素配置。';
$lang->admin->menuSetting['feature']['name']   = '功能配置';
$lang->admin->menuSetting['feature']['desc']   = '按照功能菜单进行系统的要素配置。';
$lang->admin->menuSetting['template']['name']  = '文档模板';
$lang->admin->menuSetting['template']['desc']  = '配置文档的模板类型和模板内容。';
$lang->admin->menuSetting['message']['name']   = '通知设置';
$lang->admin->menuSetting['message']['desc']   = '配置通知路径，自定义需要通知的动作。';
$lang->admin->menuSetting['extension']['name'] = '插件管理';
$lang->admin->menuSetting['extension']['desc'] = '浏览、安装插件。';
$lang->admin->menuSetting['dev']['name']       = '二次开发';
$lang->admin->menuSetting['dev']['desc']       = '支持对系统进行二次开发。';
$lang->admin->menuSetting['convert']['name']   = '数据导入';
$lang->admin->menuSetting['convert']['desc']   = '第三方系统的数据导入。';
$lang->admin->menuSetting['platform']['name']  = 'DevOps设置';
$lang->admin->menuSetting['platform']['desc']  = '资源、环境等DevOps各要素配置。';
$lang->admin->menuSetting['ai']['name']        = 'AI 配置';
$lang->admin->menuSetting['ai']['desc']        = '支持配置与管理AI提词、AI小程序及大语言模型。';

$lang->admin->updateDynamics   = '更新动态';
$lang->admin->updatePatch      = '补丁更新';
$lang->admin->upgradeRecommend = '推荐升级';
$lang->admin->zentaoUsed       = '您已使用禅道';
$lang->admin->noPriv           = '您没有访问该区块的权限。';

$lang->admin->openTag = '禅道';
$lang->admin->bizTag  = '禅道企业版';
$lang->admin->maxTag  = '禅道旗舰版';
$lang->admin->ipdTag  = '禅道IPD版';

$lang->admin->bizInfoURL    = 'https://www.zentao.net/page/enterprise.html';
$lang->admin->maxInfoURL    = 'https://www.zentao.net/page/max.html';
$lang->admin->productDetail = '查看详情';
$lang->admin->productFeature['biz'][] = '工时管理、甘特图、导入导出';
$lang->admin->productFeature['biz'][] = '40+内置统计报表、自定义报表功能';
$lang->admin->productFeature['biz'][] = '强大的自定义工作流、反馈管理功能';
$lang->admin->productFeature['biz'][] = '价格厚道，专属技术支持服务';
$lang->admin->productFeature['max'][] = '120+概念，全面覆盖瀑布管理模型';
$lang->admin->productFeature['max'][] = '项目管理可视化，精准掌控项目进度';
$lang->admin->productFeature['max'][] = '资产库管理，为项目提供数据支撑';
$lang->admin->productFeature['max'][] = '严格权限控制，方式灵活安全';

$lang->admin->ai = new stdclass();
$lang->admin->ai->model        = '语言模型';
$lang->admin->ai->conversation = '会话';
$lang->admin->ai->prompt       = '提词';

include dirname(__FILE__) . '/menu.php';
