<?php

/**
 * The ai module zh-cn lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
$lang->aiapp->common           = 'AI';
$lang->aiapp->squareCategories = array('collection' => '我的收藏', 'discovery' => '发现', 'latest' => '最新');
$lang->aiapp->newVersionTip    = '小程序已于 %s 更新，以上为过往记录';
$lang->aiapp->noMiniProgram    = '您访问的小程序不存在';
$lang->aiapp->title            = '小程序';
$lang->aiapp->unpublishedTip   = '您使用的小程序没有发布';
$lang->aiapp->noModelError     = '暂无可用的语言模型，请联系管理员配置。';
$lang->aiapp->chatNoResponse   = '会话发生了错误';
$lang->aiapp->more             = '更多';
$lang->aiapp->collect          = '收藏';
$lang->aiapp->deleted          = '已删除';
$lang->aiapp->clear            = '清空';
$lang->aiapp->modelCurrent     = '当前语言模型';
$lang->aiapp->categoryList     = array('work' => '工作', 'personal' => '个人', 'life' => '生活', 'creative' => '创意', 'others' => '其它');
$lang->aiapp->generate         = '生成';
$lang->aiapp->regenerate       = '重新生成';
$lang->aiapp->emptyNameWarning = '「%s」不能为空';
$lang->aiapp->chatTip          = '请在左侧输入字段内容，生成结果试试吧。';
$lang->aiapp->noModel          = array('尚未配置语言模型，请联系管理员或跳转至后台配置<a id="to-language-model">语言模型</a>。', '若已完成相关配置，请尝试<a id="reload-current">重新加载</a>页面。');
$lang->aiapp->clearContext     = '上下文内容已清除';
$lang->aiapp->newChatTip       = '请在左侧输入字段内容，开启新对话。';
$lang->aiapp->disabledTip      = '当前小程序已被禁用。';
$lang->aiapp->continueasking   = '继续追问';

$lang->aiapp->miniProgramSquare  = '查看通用智能体广场';
$lang->aiapp->collectMiniProgram = '收藏通用智能体';
$lang->aiapp->miniProgramChat    = '执行通用智能体';
$lang->aiapp->view               = '查看通用智能体详情';
$lang->aiapp->browseConversation = '浏览智能会话';
$lang->aiapp->manageGeneralAgent = '管理通用智能体';
$lang->aiapp->models             = '浏览模型列表';

$lang->aiapp->id                 = 'ID';
$lang->aiapp->model              = '模型名称';
$lang->aiapp->converse           = '开始会话';
$lang->aiapp->pageSummary        = '共 %s 项';

$lang->aiapp->tips = new stdClass();
$lang->aiapp->tips->noData = '暂无数据';

$lang->aiapp->langData                      = new stdClass();
$lang->aiapp->langData->name                = '禅道';
$lang->aiapp->langData->storyReview         = '需求评审';
$lang->aiapp->langData->storyReviewHint     = '对当前页面需求进行评审';
$lang->aiapp->langData->storyReviewMessage  = "下面是要进行评审的需求：\n\n### 需求标题\n\n{title}\n\n### 需求描述\n\n{spec}\n\n### 需求验收标准\n\n{verify}";
$lang->aiapp->langData->aiReview            = 'AI 评审';
$lang->aiapp->langData->currentPage         = '当前页面';
$lang->aiapp->langData->story               = '需求';
$lang->aiapp->langData->demand              = '需求池需求';
$lang->aiapp->langData->bug                 = 'BUG';
$lang->aiapp->langData->doc                 = '文档';
$lang->aiapp->langData->design              = '设计';
$lang->aiapp->langData->feedback            = '反馈';
$lang->aiapp->langData->currentDocContent   = '当前文档';
$lang->aiapp->langData->globalMemoryTitle   = '禅道';
$lang->aiapp->langData->zaiConfigNotValid   = '尚未进行ZAI配置，请联系管理员进行<a href="{zaiConfigUrl}">ZAI配置</a>。<br>若已完成相关配置，请尝试重新加载页面。';
$lang->aiapp->langData->unauthorizedError   = '授权失败，无效的 API 密钥，请联系管理员进行<a href="{zaiConfigUrl}">ZAI配置</a>。<br>若已完成相关配置，请尝试重新加载页面。';
$lang->aiapp->langData->applyFormFormat     = '应用到%s表单';
$lang->aiapp->langData->changeDetail        = '变更详情';
$lang->aiapp->langData->beforeChange        = '变更前';
$lang->aiapp->langData->afterChange         = '变更后';
$lang->aiapp->langData->changeProp          = '属性';
$lang->aiapp->langData->changeTitleFormat   = '变更{type} {id}';
$lang->aiapp->langData->applyFormSuccess    = '已成功应用到%s表单';
$lang->aiapp->langData->changeExplainDesc   = '对方案中数据的变化进行解释，尽量对变化的属性分别进行说明。';
$lang->aiapp->langData->promptResultTitle   = '方案标题，如果没有合适标题可以省略';
$lang->aiapp->langData->promptExtraLimit    = '通常工具 `{toolName}` 只需要调用一次，除非用户特殊要求提供多个方案。';
$lang->aiapp->langData->goTesting           = '去调试';
$lang->aiapp->langData->notSupportPreview   = '暂不支持预览该内容';
$lang->aiapp->langData->dataListSizeInfo    = '共 %s 条数据';
$lang->aiapp->langData->promptTestDataIntro = '下面是要进行{name}的示例{type}：';
