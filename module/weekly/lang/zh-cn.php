<?php
/**
 * The weekly module lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     weekly
 * @version     $Id
 * @link        https://www.zentao.net
 */
$lang->weekly->common   = '报告';
$lang->weekly->index    = '周报总览';
$lang->weekly->progress = '完成百分比';
$lang->weekly->workload = '工作量';
$lang->weekly->total    = '合计';

$lang->weekly->reportTtitle   = $lang->projectCommon . ': %s 周报（第 %s 周）';
$lang->weekly->summary        = $lang->projectCommon . '进展状况';
$lang->weekly->finished       = '本周工作完成情况（100%完成的工作）';
$lang->weekly->postponed      = '本周未完成工作';
$lang->weekly->nextWeek       = '下周工作计划';
$lang->weekly->workloadByType = '工作量统计';

$lang->weekly->term            = '报告周期';
$lang->weekly->project         = $lang->projectCommon . '名称';
$lang->weekly->master          = '项目经理 ';
$lang->weekly->staff           = '本周投入人数';
$lang->weekly->projectTemplate = "{$lang->projectCommon}周报模板";

$lang->weekly->weekDesc       = '第 %s 周( %s ~ %s)';
$lang->weekly->progress       = $lang->projectCommon . '当前进展状况';
$lang->weekly->analysisResult = '分析结果';
$lang->weekly->cost           = $lang->projectCommon . '成本';

$lang->weekly->pv = '计划完成的工作(PV)';
$lang->weekly->ev = '实际完成的工作(EV)';
$lang->weekly->ac = '实际花费的成本(AC)';
$lang->weekly->sv = '进度偏差率(SV%)';
$lang->weekly->cv = '成本偏差率（CV%）';

$lang->weekly->totalCount  = '总计 : %u 个任务';
$lang->weekly->builtinDesc = "系统内置的{$lang->projectCommon}周报模板，每周一在{$lang->projectCommon}下自动生成本周周报。";

$lang->weekly->exportWeeklyReport = '导出周报';

$lang->weekly->builtInScopes = array();
$lang->weekly->builtInScopes['rnd']  = array();
$lang->weekly->builtInScopes['rnd']['project'] = '项目';

$lang->weekly->builtInCategoryList['month']     = '月报';
$lang->weekly->builtInCategoryList['week']      = '周报';
$lang->weekly->builtInCategoryList['day']       = '日报';
$lang->weekly->builtInCategoryList['milestone'] = '里程碑报告';

$lang->weekly->reportHelpNotice = <<<EOD
<h2>PV 计划完成的工作</h2>
计算方式：
<br />1）任务预计开始日期、截止日期在本周起止日期范围内，累加预计工时
<br />2）任务预计开始日期、截止日期在本周起止日期之前，累加预计工时
<br />3）任务预计开始日期小于本周开始日期，截止日期大于本周开始日期，小于本周结束日期，累加预计工时
<br />4）任务预计开始日期大于本周开始日期，小于本周截止日期，截止日期大于本周结束日期，累加 （任务的预计工时÷任务工期天数）×  任务预计开始到本周结束日期的天数
<br />5）任务预计开始日期等于本周开始日期，截止日期大于本周结束日期，累加 （任务的预计工时÷任务工期天数）×  任务预计开始到本周结束日期的天数
<br />6）任务预计开始日期小于本周开始日期，截止日期等于本周结束日期，累加预计工时
<br />7）任务预计开始日期小于本周开始日期，截止日期大于本周结束日期，累加 （任务的预计工时÷任务工期天数）×  任务预计开始到本周结束日期的天数
<p>统计范围：</p>
1）本周开始日期：周一 00:00:00  本周结束日期：根据工作日和节假日的计算来确定
<br />2）为避免重复计算，只包含子任务，不包括父任务
<br />3）不包括已删除的任务
<br />4）不包括已取消的任务
<br />5）不包括已删除执行中的任务
<br />6）任务未填写预计开始日期，预计开始日期默认取任务所属阶段的计划开始日期
<br />7）任务未填写预计截止日期，预计截止日期默认取任务所属阶段的计划完成日期
<br />8）计算公式只计算工作日
<h2>EV实际完成的工作</h2>
计算方式：
<br />1）任务状态为已完成，累加预计工时
<br />2）任务状态为已关闭且关闭原因为已完成，累加预计工时
<br />3）任务状态为进行中、已暂停，累加 预计工时×完成进度
<p>统计范围：</p>
1）本周结束日期之前消耗工时不为0的任务
<br />2）为避免重复计算，只包含子任务，不包括父任务
<br />3）不包括已删除的任务
<br />4）不包括已取消的任务
<br />5）不包括已删除执行中的任务
<br />6）完成进度=已消耗工时÷(已消耗工时+剩余工时)
<h2>AC 实际花费（消耗）的成本</h2>
计算方式：
<br />1）累加本周结束日期之前所有消耗的工时
<p>统计范围：</p>
1）所有消耗的工时包括任务、需求、Bug、用例、构建、测试单、问题、风险、文档、评审的耗时
<br />2）为避免重复计算，只包含子任务，不包括父任务
<br />3）包括已删除的任务、需求、Bug、用例、构建、测试单、问题、风险、文档、评审的耗时
<br />4）包括已删除执行中任务、需求、Bug、用例、构建、测试单、文档的耗时
<br />5）包括取消的任务、问题、风险的耗时
<h2>SV(%)进度偏差率</h2>
计算方式：SV(%) = -1 * (1 - (EV / PV))%
<h2>CV(%) 成本偏差率</h2>
计算方式：CV(%) = -1 * (1 - (EV / AC))%
EOD;
$lang->weekly->blockHelpNotice = <<<EOD
<h2>本周进度</h2>
计算方式：
<br />1）项目进度=已消耗任务工时 /（已消耗任务工时 + 剩余任务工时）*100%
<p>统计范围：</p>
1）只统计任务的工时消耗数据。
<br />2）为避免重复计算，任务工时只包含子任务，不包括父任务
<br />3）包括已取消任务消耗的工时
<br />4）不包括已删除任务中消耗的工时
<br />5）不包括已删除执行中任务消耗的工时
<br />6）不包括已取消任务的剩余工时
<br />7）不包括已删除执行中任务的剩余工时
<h2>PV 计划完成的工作</h2>
计算方式：
<br />1）任务预计开始日期、截止日期在本周起止日期范围内，累加预计工时
<br />2）任务预计开始日期、截止日期在本周起止日期之前，累加预计工时
<br />3）任务预计开始日期小于本周开始日期，截止日期大于本周开始日期，小于本周结束日期，累加预计工时
<br />4）任务预计开始日期大于本周开始日期，小于本周截止日期，截止日期大于本周结束日期，累加 （任务的预计工时÷任务工期天数）×  任务预计开始到本周结束日期的天数
<br />5）任务预计开始日期等于本周开始日期，截止日期大于本周结束日期，累加 （任务的预计工时÷任务工期天数）×  任务预计开始到本周结束日期的天数
<br />6）任务预计开始日期小于本周开始日期，截止日期等于本周结束日期，累加预计工时
<br />7）任务预计开始日期小于本周开始日期，截止日期大于本周结束日期，累加 （任务的预计工时÷任务工期天数）×  任务预计开始到本周结束日期的天数
<p>统计范围：</p>
1）本周开始日期：周一 00:00:00  本周结束日期：根据工作日和节假日的计算来确定
<br />2）为避免重复计算，只包含子任务，不包括父任务
<br />3）不包括已删除的任务
<br />4）不包括已取消的任务
<br />5）不包括已删除执行中的任务
<br />6）任务未填写预计开始日期，预计开始日期默认取任务所属阶段的计划开始日期
<br />7）任务未填写预计截止日期，预计截止日期默认取任务所属阶段的计划完成日期
<br />8）计算公式只计算工作日
<h2>EV实际完成的工作</h2>
计算方式：
<br />1）任务状态为已完成，累加预计工时
<br />2）任务状态为已关闭且关闭原因为已完成，累加预计工时
<br />3）任务状态为进行中、已暂停，累加 预计工时×完成进度
<p>统计范围：</p>
1）本周结束日期之前消耗工时不为0的任务
<br />2）为避免重复计算，只包含子任务，不包括父任务
<br />3）不包括已删除的任务
<br />4）不包括已取消的任务
<br />5）不包括已删除执行中的任务
<br />6）完成进度=已消耗工时÷(已消耗工时+剩余工时)
<h2>AC 实际花费（消耗）的成本</h2>
计算方式：
<br />1）累加本周结束日期之前所有消耗的工时
<p>统计范围：</p>
1）所有消耗的工时包括任务、需求、Bug、用例、构建、测试单、问题、风险、文档、评审的耗时
<br />2）为避免重复计算，只包含子任务，不包括父任务
<br />3）包括已删除的任务、需求、Bug、用例、构建、测试单、问题、风险、文档、评审的耗时
<br />4）包括已删除执行中任务、需求、Bug、用例、构建、测试单、文档的耗时
<br />5）包括取消的任务、问题、风险的耗时
<br />
<h2>SV(%)进度偏差率</h2>
计算方式：SV(%) = -1 * (1 - (EV / PV))%
<h2>CV(%) 成本偏差率</h2>
计算方式：CV(%) = -1 * (1 - (EV / AC))%
EOD;

$lang->weekly->builtinRawContent = '{"type":"page","meta":{"id":"mKJhETwxpP","title":"项目周报模板","createDate":1758524215597,"tags":[]},"blocks":{"type":"block","id":"leP1pQM_0N","flavour":"affine:page","version":2,"props":{"title":{"$blocksuite:internal:text$":true,"delta":[{"insert":"项目周报模板"}]}},"children":[{"type":"block","id":"cDel0u6OKK","flavour":"affine:note","version":1,"props":{"xywh":"[0,0,498,92]","background":"--affine-note-background-white","index":"a0","lockedBySelf":false,"hidden":false,"displayMode":"both","edgeless":{"style":{"borderRadius":8,"borderSize":4,"borderStyle":"none","shadowType":"--affine-note-shadow-box"}}},"children":[{"type":"block","id":"57JpxtRtgl","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"text","text":{"$blocksuite:internal:text$":true,"delta":[{"insert":" ","attributes":{"holder":{"id":"-bGoKXonda","name":"weekly_term","text":"报告周期","hint":"筛选条件：“日期范围”介于本周","data":{"type":"weekly_term","blockID":1538,"hint":"筛选条件：“日期范围”介于本周","text":"报告周期"}}}},{"insert":"周报："},{"insert":" ","attributes":{"holder":{"id":"ZZ2iJ1NbSm","name":"property_name","text":"项目名称","hint":"项目名称"}}},{"insert":"项目由项目经理"},{"insert":" ","attributes":{"holder":{"id":"No8p_noVvo","name":"property_PM","text":"负责人","hint":"负责人"}}},{"insert":"负责，投入人数为"},{"insert":" ","attributes":{"holder":{"id":"yMx6IXU_PN","name":"weekly_staff","text":"投入人数","hint":"筛选条件：“日期范围”介于本周","data":{"type":"weekly_staff","blockID":1539,"hint":"筛选条件：“日期范围”介于本周","text":"投入人数"}}}}]},"collapsed":false},"children":[]},{"type":"block","id":"u_7TkQplvX","flavour":"affine:embed-zui-custom","version":1,"props":{"index":"a0","xywh":"[0,0,0,0]","lockedBySelf":false,"rotate":0,"content":{"exportUrl":"exportZentaoChart___TML_ZENTAOCHART__{project_progress_summary}","fetcher":[{"module":"reporttemplate","method":"ajaxZentaoChart","params":"type=project_progress_summary&blockID=__TML_ZENTAOCHART__{project_progress_summary}"}],"clearBeforeLoad":false,"isTemplate":true,"title":"项目进展状况"}},"children":[]},{"type":"block","id":"JF8LjhZ00l","flavour":"affine:embed-zui-custom","version":1,"props":{"index":"a0","xywh":"[0,0,0,0]","lockedBySelf":false,"rotate":0,"content":{"exportUrl":"exportZentaoChart___TML_ZENTAOCHART__{task_basicStatistic_finished}","fetcher":[{"module":"reporttemplate","method":"ajaxZentaoChart","params":"type=task_basicStatistic_finished&blockID=__TML_ZENTAOCHART__{task_basicStatistic_finished}"}],"clearBeforeLoad":false,"isTemplate":true,"title":"已完成任务情况"}},"children":[]},{"type":"block","id":"vLxMdWbsaL","flavour":"affine:embed-zui-custom","version":1,"props":{"index":"a0","xywh":"[0,0,0,0]","lockedBySelf":false,"rotate":0,"content":{"exportUrl":"exportZentaoChart___TML_ZENTAOCHART__{task_basicStatistic_unfinished}","fetcher":[{"module":"reporttemplate","method":"ajaxZentaoChart","params":"type=task_basicStatistic_unfinished&blockID=__TML_ZENTAOCHART__{task_basicStatistic_unfinished}"}],"clearBeforeLoad":false,"isTemplate":true,"title":"未完成任务情况"}},"children":[]},{"type":"block","id":"2kIWtGbWIc","flavour":"affine:embed-zui-custom","version":1,"props":{"index":"a0","xywh":"[0,0,0,0]","lockedBySelf":false,"rotate":0,"content":{"exportUrl":"exportZentaoChart___TML_ZENTAOCHART__{task_basicStatistic_workplan}","fetcher":[{"module":"reporttemplate","method":"ajaxZentaoChart","params":"type=task_basicStatistic_workplan&blockID=__TML_ZENTAOCHART__{task_basicStatistic_workplan}"}],"clearBeforeLoad":false,"isTemplate":true,"title":"工作计划"}},"children":[]},{"type":"block","id":"YQQsS51bpa","flavour":"affine:embed-zui-custom","version":1,"props":{"index":"a0","xywh":"[0,0,0,0]","lockedBySelf":false,"rotate":0,"content":{"exportUrl":"exportZentaoChart___TML_ZENTAOCHART__{project_basicStatistic_workload}","fetcher":[{"module":"reporttemplate","method":"ajaxZentaoChart","params":"type=project_basicStatistic_workload&blockID=__TML_ZENTAOCHART__{project_basicStatistic_workload}"}],"clearBeforeLoad":false,"isTemplate":true,"title":"项目计划工作量统计"}},"children":[]},{"type":"block","id":"woAbzWK8vw","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"text","text":{"$blocksuite:internal:text$":true,"delta":[]},"collapsed":false},"children":[]}]}]}}';
