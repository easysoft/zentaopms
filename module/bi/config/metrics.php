<?php
$config->bi->builtin->metrics = array();

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的所有层级的项目集总数',
    'alias'      => '所有层级的项目集总数',
    'code'       => 'count_of_program',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'program',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的所有层级的项目集总数表示在整个组织范围内的项目集数量。此度量项反映了整个组织所管理的项目集数量。可以作为评估组织规模和复杂度的指标。',
    'definition' => "所有项目集的个数求和\n过滤已删除的项目集"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的所有层级进行中项目集数',
    'alias'      => '所有层级进行中项目集数',
    'code'       => 'count_of_doing_program',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'program',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的所有层级进行中项目集数表示当前正在进行中的项目集数量。此度量项反映了组织当前正在进行中的项目集数量，可以用于评估组织的项目集管理进展和资源分配情况。',
    'definition' => "所有项目集的个数求和\n状态为进行中\n过滤已删除的项目集"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的所有层级已关闭项目集数',
    'alias'      => '所有层级已关闭项目集数',
    'code'       => 'count_of_closed_program',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'program',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的所有层级已关闭项目集数反映了系统关闭的项目集数量，用于评估组织项目集层面的管理成果。',
    'definition' => "所有项目集的个数求和\n状态为已关闭\n过滤已删除的项目集"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的所有层级已挂起项目集数',
    'alias'      => '所有层级已挂起项目集数',
    'code'       => 'count_of_suspended_program',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'program',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的所有层级已挂起项目集数反映了系统内因为某种原因暂时中止或搁置的项目集数量，用于评估组织项目集层面的风险和不确定性。',
    'definition' => "所有项目集的个数求和\n状态为已挂起\n过滤已删除的项目集"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的所有层级未开始项目集数',
    'alias'      => '所有层级未开始项目集数',
    'code'       => 'count_of_wait_program',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'program',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的所有层级未开始项目集数反映了系统尚未启动的项目集数量，用于评估组织项目集层面的计划或储备工作。',
    'definition' => "所有项目集的个数求和\n状态为未开始\n过滤已删除的项目集"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的一级项目集总数',
    'alias'      => '一级项目集总数',
    'code'       => 'count_of_top_program',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'program',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的一级项目集总数反映了组织中不同战略目标的项目集数量及情况，用于评估组织的战略取向、优先事项、资源分配以及管理能力等关键方面，是组织实现长期成功的重要手段和路径。',
    'definition' => "所有一级项目集的个数求和\n过滤已删除的项目集"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已关闭一级项目集数',
    'alias'      => '已关闭一级项目集数',
    'code'       => 'count_of_closed_top_program',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'program',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已关闭一级项目集数反映了系统中不同战略目标的项目集数量及情况，用于评估组织的项目集战略目标管理绩效和成果。',
    'definition' => "所有一级项目集的个数求和\n状态为已关闭\n过滤已删除的项目集"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的未关闭一级项目集数',
    'alias'      => '未关闭的一级项目集数',
    'code'       => 'count_of_unclosed_top_program',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'program',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的未关闭一级项目集数反映了系统中不同战略目标的项目集数量及情况，用于评估组织进行中的项目集战略目标的进展和挑战。',
    'definition' => "复用：\n按系统统计的一级项目集总数\n按系统统计的已关闭一级项目集数\n公式：按系统统计的未关闭一级项目集数=按系统统计的一级项目集总数-按系统统计的已关闭一级项目集数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度新增一级项目集数',
    'alias'      => '新增一级项目集数',
    'code'       => 'count_of_annual_created_top_program',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'program',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度新增一级项目集数反映了系统中每年新增的不同战略目标的项目集数量及情况，用于评估组织的最新的战略取向、优先事项、资源分配以及管理能力等关键方面。',
    'definition' => "所有的一级项目集的个数求和\n创建时间为某年\n过滤已删除的项目集"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度关闭一级项目集数',
    'alias'      => '关闭一级项目集数',
    'code'       => 'count_of_annual_closed_top_program',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'program',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度关闭一级项目集数反映了系统中每年结束的不同战略目标的项目集数量及情况，用于评估组织的战略目标管理的绩效和成果。',
    'definition' => "所有的一级项目集的个数求和\n关闭时间为某年\n状态为已关闭\n过滤已删除的项目集"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的产品线总数',
    'alias'      => '产品线总数',
    'code'       => 'count_of_line',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'line',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的产品线总数反映了组织中产品线的数量和广度，用于评估组织的产品组合策略和业务发展方向。',
    'definition' => "所有产品线的个数求和\n过滤已删除的产品线"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的产品总数',
    'alias'      => '产品总数',
    'code'       => 'count_of_product',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'product',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的产品总数反映了系统中的产品数量，用于评估组织的产品的数量和多样性。',
    'definition' => "所有产品的个数求和\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的正常的产品数',
    'alias'      => '正常的产品数',
    'code'       => 'count_of_normal_product',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'product',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的正常的产品数量反映了组织中处于正常研发和运营状态的产品数量，用于评估组织的产品研发能力和持续的运营能力。',
    'definition' => "所有产品的个数求和\n状态为正常\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的结束的产品数',
    'alias'      => '结束的产品数',
    'code'       => 'count_of_closed_product',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'product',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的结束的产品数反映了组织中已经停止研发和运营的产品数量，用于评估组织的产品生命周期管理和战略调整。',
    'definition' => "所有产品的个数求和\n状态为结束\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度新增产品数',
    'alias'      => '新增产品数',
    'code'       => 'count_of_annual_created_product',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'product',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度新增产品数反映了组织每年新增加的产品数量，用于评估组织的产品创新能力和市场拓展情况。',
    'definition' => "所有的产品个数求和\n创建时间为某年\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度结束产品数',
    'alias'      => '结束产品数',
    'code'       => 'count_of_annual_closed_product',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'product',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度结束产品数反映了组织每年停止研发和运营的产品数量，用于评估组织的产品组合调整和战略转型情况。',
    'definition' => "所有的产品个数求和\n关闭时间为某年\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的项目总数',
    'alias'      => '项目总数',
    'code'       => 'count_of_project',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的项目总数是指目前系统内的总项目数量。这个度量项可以帮助团队了解当前的项目规模和工作量，并作为项目管理的基础数据之一。',
    'definition' => "所有的项目个数求和\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的未开始项目数',
    'alias'      => '未开始项目数',
    'code'       => 'count_of_wait_project',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的未开始项目数是指系统中目前未开始的项目数量。这个度量项可以帮助团队了解当前需要启动的项目数量和未来的项目规划。',
    'definition' => "所有的项目个数求和\n状态为未开始\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的进行中项目数',
    'alias'      => '进行中项目数',
    'code'       => 'count_of_doing_project',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的进行中项目数是指系统中目前正在进行中的项目数量。这个度量项可以帮助团队了解当前正在进行的工作量和资源分配情况，以及项目的执行进度和效率。',
    'definition' => "所有的项目个数求和\n状态为进行中\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已挂起项目数',
    'alias'      => '已挂起项目数',
    'code'       => 'count_of_suspended_project',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已挂起项目数是指因某种原因而暂停或停滞的项目数量。这个度量项可以帮助团队了解存在的挂起项目的数量和原因，并进行适当的调整和解决。',
    'definition' => "所有的项目个数求和\n状态为已挂起\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已关闭项目数',
    'alias'      => '已关闭项目数',
    'code'       => 'count_of_closed_project',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已关闭项目数是指已经完成并关闭的项目数量。这个度量项可以帮助团队了解已经完成的项目数量和整体的项目执行情况。',
    'definition' => "所有的项目个数求和\n状态为已关闭\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的未关闭项目数',
    'alias'      => '未关闭项目数',
    'code'       => 'count_of_unclosed_project',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的未关闭项目数是指在系统目前未开始或仍然在进行中的项目数量。这个度量项可以衡量项目管理和执行的效率。',
    'definition' => "复用：\n按系统统计的已关闭项目数\n按系统统计的项目总数\n公式：\n按系统统计的未关闭项目数=按系统统计的项目总数-按系统统计的已关闭项目数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已完成项目中按期完成项目数',
    'alias'      => '已完成项目中按期完成项目数',
    'code'       => 'count_of_undelayed_finished_project_which_finished',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已完成项目中按期完成项目数是指按预定计划时间完成的项目数量。这个度量项可以帮助团队评估项目的时间管理和执行能力。较高的按期完成项目数表示团队能够按时交付项目，有助于保持项目进展和客户满意度。',
    'definition' => "所有的项目个数求和\n状态为已关闭\n完成日期<=项目启动时的计划截止日期\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已完成项目中延期完成项目数',
    'alias'      => '已完成项目中延期完成项目数',
    'code'       => 'count_of_delayed_finished_project_which_finished',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已完成项目中延期完成项目数是指超过预定计划时间而完成的项目数量。这个度量项可以帮助团队评估项目的时间管理和执行能力，并识别延期原因并采取适当措施。较高的延期完成项目数可能需要团队关注项目计划和资源安排的问题。',
    'definition' => "所有的项目个数求和\n状态为已关闭\n完成日期>项目启动时的计划截止日期\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度新增项目数',
    'alias'      => '新增项目数',
    'code'       => 'count_of_annual_created_project',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度新增项目数是指某年度新创建的项目数量。这个度量项可以帮助团队了解某年度的项目规模和工作负荷，以及项目管理和资源分配的需求。较高的年度新增项目数可能需要团队根据资源和能力进行优先级和规划管理。',
    'definition' => "所有的项目个数求和\n创建时间为某年\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度关闭项目数',
    'alias'      => '关闭项目数',
    'code'       => 'count_of_annual_closed_project',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度关闭项目数是指在某年度关闭的项目数量。这个度量项可以帮助团队了解某年度项目的执行情况和成果，并进行项目交付能力的评估。较高的年度关闭项目数表明团队在项目交付方面具有较高的效率。',
    'definition' => "所有的项目个数求和\n关闭时间为某年\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度启动项目中按期完成项目数',
    'alias'      => '启动项目中按期完成项目数',
    'code'       => 'count_of_undelayed_finished_project_which_annual_started',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度启动项目中按期完成项目数是指在某年度启动的项目中按预定计划时间关闭的项目数量。这个度量项可以帮助团队评估某年度项目的时间管理和执行能力，并衡量项目的进展和交付效果。较高的按时关闭项目数表明团队能够按时交付项目，有助于保持项目的正常进行和客户满意度。',
    'definition' => "所有的项目个数求和\n启动时间为某年\n完成日期<=项目启动时的计划截止日期（根据历史记录推算）\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度完成项目中延期完成项目数',
    'alias'      => '完成项目中延期完成项目数',
    'code'       => 'count_of_delayed_finished_project_which_annual_finished',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度完成项目中延期完成项目数是指在某年度完成的项目中超过预定计划时间关闭的项目数量。这个度量项可以帮助团队评估某年度项目的时间管理和执行能力，并识别延期原因并采取适当措施。较高的延期关闭项目数可能需要团队关注项目计划和资源安排的问题。',
    'definition' => "复用：\n按系统统计的年度关闭项目数\n按系统统计的每年完成项目中按期完成项目数\n公式：\n按系统统计的年度延期完成项目数=按系统统计的年度关闭项目数-按系统统计的每年完成项目中按期完成项目数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度完成项目中按期完成项目数',
    'alias'      => '完成项目中按期完成项目数',
    'code'       => 'count_of_undelayed_finished_project_which_annual_finished',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度完成项目中按期完成项目数是指在某年度完成的项目中按预定计划时间关闭的项目数量。这个度量项可以帮助团队评估某年度项目的时间管理和执行能力，并衡量项目的进展和交付效果。较高的按时关闭项目数表明团队能够按时交付项目，有助于保持项目的正常进行和客户满意度。',
    'definition' => "所有的项目个数求和\n关闭时间为某年\n完成日期<=项目启动时的计划截止日期\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的月度新增项目数',
    'alias'      => '新增项目数',
    'code'       => 'count_of_monthly_created_project',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按系统统计的月度新增项目数是指在某月度新创建的项目数量。这个度量项可以帮助团队了解某年度项目规模和工作负荷，以及项目管理和资源分配的需求。较高的年度新增项目数可能需要团队根据资源和能力进行优先级和规划管理。',
    'definition' => "所有的项目个数求和\n创建时间为某年某月\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的月度关闭项目数',
    'alias'      => '关闭项目数',
    'code'       => 'count_of_monthly_closed_project',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按系统统计的年度关闭项目数是指在某月度关闭的项目数量。这个度量项可以帮助团队了解某年度项目的执行情况和成果，并进行项目交付能力的评估。较高的年度关闭项目数表明团队在项目交付方面具有较高的效率。',
    'definition' => "所有的项目个数求和\n关闭时间为某年某月\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度完成项目数',
    'alias'      => '完成项目数',
    'code'       => 'count_of_annual_finished_project',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度完成项目数是指在某年度完成并关闭的项目数量。反映了团队在某年度项目的执行情况和成果，并进行项目交付能力的评估。较高的年度完成项目数表明团队在项目交付方面具有较高的效率。',
    'definition' => "所有的项目个数求和\n实际完成时间为某年\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度关闭项目的任务预计工时数',
    'alias'      => '关闭项目的任务预计工时数',
    'code'       => 'estimate_of_annual_closed_project',
    'purpose'    => 'hour',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度关闭项目的任务预计工时数是指在某年度关闭项目预计需要花费的总工时数。该度量项可以用来评估团队或组织在任务完成方面的工时规划和估算准确性。较准确的年度完成任务预计工时数可以帮助团队更好地安排资源和时间，提高任务的完成效率和进度控制。',
    'definition' => "所有项目任务的预计工时数求和\n项目状态为已关闭\n关闭时间为某年\n过滤父任务\n过滤已删除的任务\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度关闭项目的任务消耗工时数',
    'alias'      => '关闭项目的任务消耗工时数',
    'code'       => 'consume_of_annual_closed_project',
    'purpose'    => 'hour',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'hour',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度关闭项目的任务消耗工时数是指在某年度关闭的项目中任务消耗的总工时数。该度量项可以用来评估团队或组织在任务执行过程中的工时投入情况和对资源的利用效率。较高的年度关闭项目的任务消耗工时数可能需要审查工作流程和资源分配，以提高工作效率和进度控制。',
    'definition' => "所有项目任务的消耗工时数求和\n项目状态为已关闭\n关闭时间为某年\n过滤父任务\n过滤已删除的任务\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的月度关闭项目的任务消耗工时数',
    'alias'      => '关闭项目的任务消耗工时数',
    'code'       => 'consume_of_monthly_closed_project',
    'purpose'    => 'hour',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'hour',
    'dateType'   => 'month',
    'desc'       => '按系统统计的月度关闭项目的任务消耗工时数是指在某月任务预计需要花费的总工时数。该度量项可以用来评估团队或组织在任务执行过程中的工时投入情况和对资源的利用效率。较高的月度关闭项目的任务消耗工时数可能需要审查工作流程和资源分配，以提高工作效率和进度控制。',
    'definition' => "所有项目任务消耗工时数求和\n项目状态为已关闭\n关闭时间为某年某月\n过滤父任务\n过滤已删除的任务\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度已关闭项目投入总人天',
    'alias'      => '已关闭项目投入总人天',
    'code'       => 'day_of_annual_closed_project',
    'purpose'    => 'hour',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'manday',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度已关闭项目投入总人天是指在某年度关闭项目投入的人天总数。该度量项可以用来评估项目的人力资源投入情况。投入总人天的增加可能意味着项目投入的工作时间和资源的增加。',
    'definition' => "复用：\n按系统统计的年度关闭项目消耗工时数\n公式：\n按系统统计的年度关闭项目投入总人天=按系统统计的年度已关闭项目任务的消耗工时数/后台配置的每天可用工时"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度完成项目中项目的按期完成率',
    'alias'      => '完成项目中项目的按期完成率',
    'code'       => 'rate_of_undelayed_finished_project_which_annual_finished',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度完成项目中项目的按期完成率是指按系统统计的年度完成项目中按期完成项目数与关闭项目数之比。这个度量项可以帮助团队评估某年度项目按期关闭的能力和效果，并作为项目管理的绩效指标之一。较高的按期完成率表示团队能够按时完成项目，说明对项目管理和交付能力较高。',
    'definition' => "复用：\n按系统统计的年度关闭项目数\n按系统统计的年度完成项目中项目的按期完成率\n公式：\n按系统统计的年度项目按期关闭率=按系统统计的年度按时关闭项目数/按系统统计的年度关闭项目数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度完成项目中项目的延期完成率',
    'alias'      => '完成项目中项目的延期完成率',
    'code'       => 'rate_of_delayed_finished_project_which_annual_finished',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'project',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度完成项目中项目的延期完成率是指按系统统计的年度完成项目中延期完成项目数与关闭项目数之比。这个度量项可以帮助团队评估某年度项目按期关闭的能力和效果，并作为项目管理的绩效指标之一。较高的延期完成率可能需要团队关注项目计划和资源安排的问题。',
    'definition' => "复用：\n按系统统计的年度关闭项目数\n按系统统计的年度延期关闭项目数\n公式：\n按系统统计的年度项目延期关闭率=按系统统计的年度延期关闭项目数/按系统统计的年度关闭项目数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的计划总数',
    'alias'      => '计划总数',
    'code'       => 'count_of_productplan',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'productplan',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的计划总数反映了组织中进行中和已完成的计划数量，用于评估组织的规划效率、预测资源需求、优化项目组织与协调，并用于绩效评估和目标设定。',
    'definition' => "所有的计划的个数求和\n过滤已删除的计划"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度新增计划数',
    'alias'      => '新增计划数',
    'code'       => 'count_of_annual_created_productplan',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'productplan',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度新增计划数反映了组织在某年度内新增计划数量，用于评估组织创新能力、市场竞争力和投资决策，并用于绩效评估和目标设定。',
    'definition' => "所有的计划个数求和\n创建时间为某年\n过滤已删除的计划"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度完成计划数',
    'alias'      => '完成计划数',
    'code'       => 'count_of_annual_finished_productplan',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'productplan',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度完成计划数反映了组织在某年度内实际完成的计划数量，用于评估绩效、生产效率和客户满意度，并用于规划和资源优化。',
    'definition' => "所有的计划个数求和\n完成时间为某年\n过滤已删除的计划"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度关闭计划数',
    'alias'      => '关闭计划数',
    'code'       => 'count_of_annual_closed_productplan',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'productplan',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度关闭计划数反映了组织在某年度内关闭的计划数量，用于评估组织的计划管理效能、资源优化和成本控制，并提供学习机会和产品组合优化的参考。',
    'definition' => "所有的计划个数求和\n关闭时间为某年\n过滤已删除的计划"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已完成计划数',
    'alias'      => '已完成计划数',
    'code'       => 'count_of_finished_productplan',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'productplan',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已完成计划数反映了组织在某年度内已经完成的计划数量，用于评估组织的绩效、生产效率和客户满意度，并用于规划和资源优化。',
    'definition' => "所有计划的个数求和\n状态为已完成\n过滤已删除的计划"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的未完成计划数',
    'alias'      => '未完成计划数',
    'code'       => 'count_of_unfinished_productplan',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'productplan',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的未完成的产品计划数量反映了组织在特定年度内未能完成的产品计划数量，用于评估组织的评估绩效、资源管理和风险控制，并用于规划和改进。',
    'definition' => "复用：\n按系统统计的已完成计划数\n按系统统计的计划总数\n公式：\n按系统统计的未完成计划数=按系统统计的计划总数-按系统统计的已完成计划数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的执行总数',
    'alias'      => '执行总数',
    'code'       => 'count_of_execution',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的执行总数表示在整个系统中所有执行项的数量，可以用来评估项目的规模和任务的总量。',
    'definition' => "所有的执行个数求和\n过滤已删除的执行"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的未开始执行数',
    'alias'      => '未开始执行数',
    'code'       => 'count_of_wait_execution',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的未开始执行数表示在整个系统中尚未开始执行的任务数，可以用来了解待办任务的数量。',
    'definition' => "所有的执行个数求和\n状态为未开始\n过滤已删除的执行"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的进行中执行数',
    'alias'      => '进行中执行数',
    'code'       => 'count_of_doing_execution',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的进行中执行数表示在整个系统中正在进行中的执行项的数量，可以用来了解当前正在进行的任务数量，反映团队的工作进展。',
    'definition' => "所有的执行个数求和\n状态为进行中\n过滤已删除的执行"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已挂起执行数',
    'alias'      => '已挂起执行数',
    'code'       => 'count_of_suspended_execution',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已挂起执行数表示在整个系统中已被挂起的执行项的数量，可以用来了解暂停的任务数量，可能是由于需求不明确或其他原因导致。',
    'definition' => "所有的执行个数求和\n状态为已挂起\n过滤已删除的执行"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已关闭执行数',
    'alias'      => '已关闭执行数',
    'code'       => 'count_of_closed_execution',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已关闭执行数表示在整个系统中已关闭的执行项的数量，可以用来了解执行的进度情况。',
    'definition' => "所有的执行个数求和\n状态为已关闭\n过滤已删除的执行"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的未关闭执行数',
    'alias'      => '未关闭执行数',
    'code'       => 'count_of_unclosed_execution',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的未关闭执行数表示在整个系统中未关闭的执行项的数量，可以用来了解执行的进度情况。',
    'definition' => "复用：\n按系统统计的执行总数\n按系统统计的已关闭执行数\n公式：\n按系统统计的未关闭执行数=按系统统计的执行总数-按系统统计的已关闭执行数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度新增执行数',
    'alias'      => '新增执行数',
    'code'       => 'count_of_annual_created_execution',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度新增执行数是指在某年度新添加的执行数。该度量项反映了一个团队或组织在某年的工作量大小。较高的年度新增执行数可能表明团队面临更多的任务和挑战，需要更多的资源和努力来完成执行。同时，对于项目管理方面，该度量项也可以提供管理决策的依据。',
    'definition' => "所有的执行个数求和\n创建时间为某年\n过滤已删除的执行"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度关闭执行数',
    'alias'      => '关闭执行数',
    'code'       => 'count_of_annual_closed_execution',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度关闭执行数是指在关闭时间在某年的执行数。该度量项可以反映团队或组织在某年的工作效率。较高的年度关闭执行数可能表示团队或组织在完成任务方面表现出较高的效率，反之则可能需要审查工作流程和资源分配情况，以提高执行效率。',
    'definition' => "所有的执行个数求和\n关闭时间为某年\n过滤已删除的执行"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度完成执行数',
    'alias'      => '完成执行数',
    'code'       => 'count_of_annual_finished_execution',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度完成执行数是指在某年度已经完成的执行数。该度量项反映了团队或组织在某年的工作效率和完成能力。较高的年度完成执行数表示团队或组织在完成任务方面表现出较高的效率，反之则可能需要审查工作流程和资源分配情况，以提高执行效率。',
    'definition' => "所有的执行个数求和\n实际完成日期为某年\n过滤已删除的执行"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的月度新增执行数',
    'alias'      => '新增执行数',
    'code'       => 'count_of_monthly_created_execution',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按系统统计的月度新增执行数是指在某月度内新添加的执行数。该度量项反映了团队或组织在某月内所面临的新任务或工作量。较高的月度新增执行数可能表明团队需要快速适应新任务和及时调整资源来满足需求。',
    'definition' => "所有的执行个数求和\n创建时间为某年某月\n过滤已删除的执行"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的月度关闭执行数',
    'alias'      => '关闭执行数',
    'code'       => 'count_of_monthly_closed_execution',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按系统统计的月度完成执行数是指在某月度已经关闭的执行数。该度量项反映了团队或组织在某月内的工作效率和完成能力。较高的月度完成执行数表示团队或组织在快速完成任务方面表现出较高的效率，反之则可能需要审查工作流程和资源分配情况，以提高执行效率。',
    'definition' => "所有的执行个数求和\n关闭时间为某年某月\n过滤已删除的执行"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已完成执行中按期完成执行数',
    'alias'      => '已完成执行中按期完成执行数',
    'code'       => 'count_of_undelayed_finished_execution_which_finished',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已完成执行中按时完成执行数表示在整个系统中按期完成执行的数量，可以用来评估团队的执行能力和效率。',
    'definition' => "所有的执行个数求和\n状态为已关闭\n关闭日期<=执行开始时计划截止日期\n过滤已删除的执行"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已完成执行中延期完成执行数',
    'alias'      => '已完成执行中延期完成执行数',
    'code'       => 'count_of_delayed_finished_execution_which_finished',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已完成执行中延期完成执行数表示在整个系统中延期完成的执行项的数量，可以用来评估任务的延期情况和团队的执行能力。',
    'definition' => "所有的执行个数求和\n状态为已关闭\n关闭日期>执行开始时计划截止日期\n过滤已删除的执行"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度完成执行中按期完成执行数',
    'alias'      => '完成执行中按期完成执行数',
    'code'       => 'count_of_undelayed_finished_execution_which_annual_finished',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度完成执行中按期完成执行数是指在某年度关闭的执行中，按预定计划时间关闭的执行数量。这个度量项可以用来衡量团队在某年度的按时完成能力，较高的按期完成执行数表明团队能够按期交付执行，有助于保持执行和项目的正常进行。',
    'definition' => "所有的执行个数求和\n关闭时间为某年\n关闭日期<=执行开始时计划截止日期\n过滤已删除的执行"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度完成执行中延期完成执行数',
    'alias'      => '完成执行中延期完成执行数',
    'code'       => 'count_of_delayed_finished_execution_which_annual_finished',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度完成执行中延期完成执行数是指在某年度关闭的执行中，超过预定计划时间关闭的执行数量。这个度量项可以用来衡量团队在某年度的按时完成能力，并识别延期原因并采取适当措施。较高的延期关闭执行数可能需要团队关注执行计划和资源安排的问题。',
    'definition' => "所有的关闭时间为某年的执行个数求和\n关闭日期>执行开始时计划截止日期\n过滤已删除的执行"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度完成执行中执行的按期关闭率',
    'alias'      => '完成执行中执行的按期关闭率',
    'code'       => 'rate_of_undelayed_closed_execution_which_annual_finished',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'execution',
    'unit'       => 'percentage',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度完成执行中执行的按期关闭率是指某年度按预定计划时间关闭的执行数量与某年度关闭执行执行数量之比。这个度量项可以帮助团队评估某年度执行按期关闭的能力和效果，并作为执行管理的绩效指标之一。较高的执行按期关闭率表示团队能够按时完成执行和项目。',
    'definition' => "复用：\n按系统统计的年度关闭执行数\n按系统统计的年度完成执行中按期完成执行数\n公式：\n按系统统计的年度完成执行中执行的按期关闭率=按系统统计的年度完成执行中按期完成执行数/按系统统计的年度关闭执行数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度完成执行中执行的延期关闭率',
    'alias'      => '完成执行中执行的延期关闭率',
    'code'       => 'rate_of_delayed_closed_execution_which_annual_finished',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'execution',
    'unit'       => 'percentage',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度完成执行中执行的延期关闭率是指某年度超过预定计划时间关闭的执行数量与某年度关闭执行数量之比。这个度量项可以帮助团队评估某年度执行按期关闭的能力和效果，并作为执行管理的绩效指标之一。较高的执行延期关闭率可能需要团队关注执行计划和资源安排的问题。',
    'definition' => "复用：\n按系统统计的年度关闭执行数\n按系统统计的年度完成执行中延期完成执行数\n公式：\n按系统统计的年度完成执行中执行的延期关闭率=按系统统计的年度完成执行中延期完成执行数/按系统统计的年度关闭执行数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的发布总数',
    'alias'      => '发布总数',
    'code'       => 'count_of_release',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'release',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的产品发布数量反映了组织在特定时间段内发布的产品版本数量，用于评估组织的产品开发效率、市场适应能力和产品组合优化，并提供绩效评估和学习机会。',
    'definition' => "所有的发布个数求和\n过滤已删除的发布"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的里程碑发布总数',
    'alias'      => '里程碑发布总数',
    'code'       => 'count_of_marker_release',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'release',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的产品里程碑发布数量反映了组织在特定时间段内达到的产品开发里程碑数量，用于评估组织的产品开发进展情况和重要的产品节点。',
    'definition' => "所有的里程碑发布个数求和\n过滤已删除的发布"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度新增发布数',
    'alias'      => '新增发布数',
    'code'       => 'count_of_annual_created_release',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'release',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度新增产品发布数量反映了组织在特定年度内新增发布的产品数量，用于评估组织的出汗品的创新能力、市场竞争力，以及业务增长和收益潜力。',
    'definition' => "所有的发布个数求和\n发布时间为某年\n过滤已删除的发布"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的月度新增发布数',
    'alias'      => '新增发布数',
    'code'       => 'count_of_monthly_created_release',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'release',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按系统统计的月度新增产品发布数量反映了组织在特定月份内新增发布的产品数量，用于评估组织的产品开发效率、市场适应能力和产品组合优化。',
    'definition' => "所有的发布个数求和\n发布时间为某年某月\n过滤已删除的发布"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的每周新增发布数',
    'alias'      => '新增发布数',
    'code'       => 'count_of_weekly_created_release',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'release',
    'unit'       => 'count',
    'dateType'   => 'week',
    'desc'       => '按系统统计的每周新增发布数表示每周新增加的发布数量。反映了组织每周增加的发布数量，用于评估组织产品发布的速度和规模。',
    'definition' => "所有的发布个数求和\n发布时间为某周\n过滤已删除的发布\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的研发需求总数',
    'alias'      => '研发需求总数',
    'code'       => 'count_of_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的研发需求的数量反映了组织在特定时间段内的研发需求数量，用于评估组织的研发投入、技术创新能力和市场竞争力，并提供绩效评估。',
    'definition' => "所有的研发需求个数求和\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已关闭研发需求数',
    'alias'      => '已关闭研发需求数',
    'code'       => 'count_of_closed_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已关闭的产品研发需求数量反映了组织在特定时间段内已经关闭的产品研发需求数量，用于评估组织的研发决策效果、优化资源管理和提供绩效评估和成果。',
    'definition' => "所有的研发需求个数求和\n状态为已关闭\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已完成研发需求数',
    'alias'      => '已完成研发需求数',
    'code'       => 'count_of_finished_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已完成研发需求数反映了组织在特定时间段内已经完成的产品研发需求数量，用于评估评组织的估研发成果、产品创新和竞争力，并提供绩效评估。',
    'definition' => "所有的研发需求个数求和\n关闭原因为已完成\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的未关闭研发需求数',
    'alias'      => '未关闭研发需求数',
    'code'       => 'count_of_unclosed_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的未关闭的产品研发需求数量反映了组织在特定时间段内尚未关闭的产品研发需求数量，用于评估组织评估研发进度、需求管理和资源规划，并提供对需求可行性和商业价值的评估。',
    'definition' => "复用：\n按系统统计的研发需求总数\n按系统统计的已关闭研发需求数\n公式：按系统统计的未关闭研发需求数=按系统统计的研发需求总数-按系统统计的已关闭研发需求数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的无效研发需求数',
    'alias'      => '无效研发需求数',
    'code'       => 'count_of_invalid_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的无效的产品研发需求数量反映了组织在特定时间段内无效或被废弃的产品研发需求数量，用于评估组织的帮助组织评估需求管理效果、资源利用效率和需求准确性，提供学习和改进的机会。',
    'definition' => "所有的研发需求个数求和\n关闭原因为重复、不做、设计如此和已取消\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的有效研发需求数',
    'alias'      => '有效研发需求数',
    'code'       => 'count_of_valid_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的有效的产品研发需求数量反映了组织在特定时间段内有效的产品研发需求数量，用于评估组织的评估需求质量、市场适应性、研发投资回报和竞争力。',
    'definition' => "复用：\n按系统统计的无效研发需求数\n按系统统计的研发需求总数\n公式：\n按系统统计的有效研发需求数=按系统统计的研发需求总数-按系统统计的无效研发需求数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已交付研发需求数',
    'alias'      => '已交付研发需求数',
    'code'       => 'count_of_delivered_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已交付的产品研发需求数量反映了组织在特定时间段内已交付的产品研发需求数量，用于评估组织的交付能力、项目执行效率、产品质量和客户满意度。',
    'definition' => "所有的研发需求个数求和\n阶段为已发布或关闭原因为已完成\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度新增研发需求数',
    'alias'      => '新增研发需求数',
    'code'       => 'count_of_annual_created_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度新增的产品研发需求数量反映了组织在每年新增的产品研发需求数量，用于评估组织的创新能力、需求发现和优先级制定、投资决策以及绩效评估与持续改进。',
    'definition' => "所有的研发需求个数求和\n创建时间为某年\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度完成研发需求数',
    'alias'      => '完成研发需求数',
    'code'       => 'count_of_annual_finished_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度完成的研发需求数量反映了组织在每年完成的研发需求数量，用于评估组织的研发活动的产出、项目管理能力、产品质量和市场竞争力具有重要意义。有助于优化资源规划、提高研发效率，并推动持续改进和创新。',
    'definition' => "所有的研发需求个数求和\n关闭时间为某年\n关闭原因为已完成\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的月度新增研发需求数',
    'alias'      => '新增研发需求数',
    'code'       => 'count_of_monthly_created_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按系统统计的月度新增的研发需求数量反映了组织每个月内新增的研发需求数量，用于评估组织的研发活动的监测、需求管理、项目规划、绩效评估和决策支持具有重要意义。它提供了一个动态的指标，为组织提供了实时的数据支持，以便更好地管理和优化研发活动。',
    'definition' => "所有的研发需求个数求和\n创建时间为某年某月\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的月度完成研发需求数',
    'alias'      => '完成研发需求数',
    'code'       => 'count_of_monthly_finished_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按系统统计的月度完成研发需求数量反映了组织每个月内完成的研发需求数量，用于评估组织的绩效评估、进度跟踪、资源规划、经验积累和持续改进具有重要意义。',
    'definition' => "所有的研发需求个数求和\n关闭时间为某年某月\n关闭原因为已完成\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度交付研发需求数',
    'alias'      => '交付研发需求数',
    'code'       => 'count_of_annual_delivered_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度交付的研发需求数量反映了组织在一年内交付的研发需求数量，可以用于评估组织的交付能力评估、项目管理、客户满意度、绩效评估和持续改进具有重要意义。',
    'definition' => "所有的研发需求个数求和\n阶段为已发布且发布时间为某年或关闭原因为已完成且关闭时间为某年的\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的研发需求规模总数',
    'alias'      => '研发需求规模总数',
    'code'       => 'scale_of_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的研发需求规模总数反映了组织在所有研发需求上的规模总数，用于评估组织对于研发资源规划、技术能力评估、需求管理、风险评估和绩效评估具有重要意义。',
    'definition' => "所有的研发需求规模数求和\n过滤父研发需求\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已完成研发需求规模数',
    'alias'      => '已完成研发需求规模数',
    'code'       => 'scale_of_finished_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已完成研发需求规模数反映了组织在已完成的研发需求上的规模总数，用于评估组织对于研发进展评估、质量控制、绩效评估和持续改进具有重要意义。',
    'definition' => "所有的研发需求规模数求和\n关闭原因为已完成\n过滤父研发需求\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的无效研发需求规模数',
    'alias'      => '无效研发需求规模数',
    'code'       => 'scale_of_invalid_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的无效的研发需求规模数反映了组织中无效的研发需求的规模总数，用于评估组织对于资源管理、需求管理、质量控制、风险评估和持续改进具有重要意义。',
    'definition' => "所有的研发需求规模数求和\n关闭原因为重复、不做、设计如此和已取消\n过滤父研发需求\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的有效研发需求规模数',
    'alias'      => '有效研发需求规模数',
    'code'       => 'scale_of_valid_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的有效的研发需求规模数反映了组织中有效的研发需求的规模总数，用于评估组织对于项目成果评估、资源规划、目标达成度评估、绩效评估和持续改进具有重要意义。',
    'definition' => "复用：\n按系统统计的无效研发需求规模数\n按系统统计的研发需求规模数\n公式：\n按系统统计的有效研发需求数=按系统统计的研发需求规模数-按系统统计的无效研发需求规模数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度完成研发需求规模数',
    'alias'      => '完成研发需求规模数',
    'code'       => 'scale_of_annual_finished_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度完成的研发需求规模数反映了组织在年度期间完成的研发需求的规模总数，用于评估组织对于绩效评估、规划和资源管理、风险评估、学习和持续改进以及组织透明度和沟通具有重要意义。',
    'definition' => "所有的研发需求规模数求和\n关闭时间为某年\n关闭原因为已完成\n过滤父研发需求\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度交付研发需求规模数',
    'alias'      => '交付研发需求规模数',
    'code'       => 'scale_of_annual_delivered_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度交付的研发需求规模数反映了组织在年度期间交付的研发需求的规模总数，用于评估组织对于项目交付评估、绩效评估、资源规划、风险评估、学习和持续改进具有重要意义。',
    'definition' => "所有研发需求规模数求和\n阶段为已发布且发布时间为某年或关闭原因为已完成且关闭时间为某年\n过滤父研发需求\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度关闭研发需求规模数',
    'alias'      => '关闭研发需求规模数',
    'code'       => 'scale_of_annual_closed_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度关闭的研发需求规模数反映了组织在年度期间关闭的研发需求的规模总数，用于评估组织对于项目管理和控制、绩效评估、资源规划、风险评估、学习和持续改进具有重要意义。',
    'definition' => "所有的研发需求规模数求和\n关闭时间为某年\n过滤父研发需求\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的月度完成研发需求规模数',
    'alias'      => '完成研发需求规模数',
    'code'       => 'scale_of_monthly_finished_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按系统统计的月度完成的研发需求规模数反映了组织在每个月完成的研发需求的规模总数，用于评估组织对于进度监控、绩效评估、资源规划、风险评估、持续改进和敏捷性具有重要意义。',
    'definition' => "所有的研发需求规模数求和\n关闭时间为某年某月\n关闭原因为已完成\n过滤父研发需求\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的月度交付研发需求规模数',
    'alias'      => '交付研发需求规模数',
    'code'       => 'scale_of_monthly_delivered_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按系统统计的月度交付的研发需求规模数反映了组织在每个月交付的研发需求的规模总数，用于评估组织对于交付能力评估、绩效评估、项目管理和控制、客户满意度和信任建立、持续改进和效率提升具有重要意义。',
    'definition' => "所有的研发需求规模数求和\n阶段为已发布且发布时间为某年某月或关闭原因为已完成且关闭时间为某年某月\n过滤父研发需求\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的月度关闭研发需求规模数',
    'alias'      => '关闭研发需求规模数',
    'code'       => 'scale_of_monthly_closed_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按系统统计的月度关闭的研发需求规模数反映了组织在每个月关闭的研发需求的规模总数，用于评估组织对于项目管理和控制、绩效评估、资源规划和利用、风险评估、持续改进和效率提升具有重要意义。',
    'definition' => "所有的研发需求规模数求和\n关闭时间为某年某月\n过滤父研发需求\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的每周完成研发需求规模数',
    'alias'      => '完成研发需求规模数',
    'code'       => 'scale_of_weekly_finished_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'measure',
    'dateType'   => 'week',
    'desc'       => '按系统统计的每周完成研发需求规模数表示每周完成的研发需求的数量。反映了组织每周完成的研发需求数量，用于评估项目进度、资源规划、需求管理、团队绩效和质量控制的有用信息。它对于项目管理和团队协作具有重要意义，并可以帮助团队监控进度、优化资源利用和提高研发效率。',
    'definition' => "所有的研发需求个数求和\n关闭时间为某周\n关闭原因为已完成\n过滤父需求\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的每周完成需求数',
    'alias'      => '完成需求数',
    'code'       => 'count_of_weekly_finished_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'week',
    'desc'       => '按系统统计的每周完成需求数是指每周已关闭且关闭原因为已完成的研发需求数量。反映了团队在每周的开发效率和成果，用于评估需求管理、项目进度、资源规划、绩效评估和质量控制的有用信息。它对于项目管理和团队协作具有重要意义，并可以帮助团队监控进度、优化资源利用和提高工作效率。',
    'definition' => "所有研发需求的个数求和。\n关闭时间在某周。\n关闭原因为已完成。\n过滤已删除的研发需求。\n过滤已删除的产品。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的每日新增研发需求数',
    'alias'      => '新增研发需求数',
    'code'       => 'count_of_daily_created_story',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'day',
    'desc'       => '按系统统计的每日新增研发需求数表示每日新增加的研发需求的数量，可以用于评估组织的研发需求增长和规模扩展情况。',
    'definition' => "所有的研发需求个数求和\n创建时间为某日\n过滤已删除的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的研发需求完成率',
    'alias'      => '研发需求完成率',
    'code'       => 'rate_of_finished_story',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的研发需求完成率反映了组织按系统统计的已完成研发需求数和按系统统计的有效研发需求数之间的比率，用于评估组织对于进度控制、绩效评估、风险评估、资源规划和利用，以及持续改进和效率提升具有重要意义。',
    'definition' => "复用：\n按系统统计的完成研发需求数\n按系统统计的有效研发需求数\n公式：\n按系统统计的研发需求完成率=按系统统计的已完成研发需求数/按系统统计的有效研发需求数*100%"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的研发需求交付率',
    'alias'      => '研发需求交付率',
    'code'       => 'rate_of_delivered_story',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的研发需求交付率反映了组织在研发过程中按时交付需求的能力和表现。用于评估组织对于评估交付能力、客户满意度和信任建立、项目管理和资源优化、竞争力和市场表现，以及持续改进和效率提升具有重要意义。',
    'definition' => "复用：\n按系统统计的已交付研发需求数\n按系统统计的有效研发需求数\n公式：\n按系统统计的研发需求完成率=按系统统计的已交付研发需求数/按系统统计的有效研发需求数*100%"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度研发需求完成率',
    'alias'      => '研发需求完成率',
    'code'       => 'rate_of_annual_finished_story',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'percentage',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度研发需求完成率反映了组织在年度研发过程中完成需求的能力和表现，反映了组织对于评估项目目标达成、资源规划和优化、业务决策和战略执行、绩效评估和激励机制，以及持续改进和效率提升具有重要意义。',
    'definition' => "复用：\n按系统统计的年度完成研发需求数\n按系统统计的年度有效研发需求数\n公式：\n按系统统计的年度研发需求完成率=按系统统计的年度完成研发需求数/按系统统计的年度有效研发需求数*100%"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度研发需求交付率',
    'alias'      => '研发需求交付率',
    'code'       => 'rate_of_annual_delivered_story',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'percentage',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度研发需求交付率反映了组织在年度研发过程中按时交付需求的能力和表现，用于评估组织对于评估项目交付能力、客户满意度和信任建立、项目进度管理和风险控制、绩效评估和激励机制，以及持续改进和效率提升具有重要意义。',
    'definition' => "复用：\n按系统统计的年度交付研发需求数\n按系统统计的年度有效研发需求数\n公式：\n按系统统计的年度研发需求完成率=按系统统计的年度交付研发需求数/按系统统计的年度有效研发需求数*100%"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的任务总数',
    'alias'      => '任务总数',
    'code'       => 'count_of_task',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的任务总数是指整个团队或组织当前存在的任务总量。该度量项可以用来跟踪任务的规模和复杂性，为资源分配和工作计划提供基础。较大的任务总数可能需要更多的资源和时间来完成，而较小的任务总数可能意味着团队负荷较轻或项目进展较好。',
    'definition' => "所有的任务个数求和\n过滤已删除的任务\n过滤已删除项目的任务\n过滤已删除执行的任务"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已完成任务数',
    'alias'      => '已完成任务数',
    'code'       => 'count_of_finished_task',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已完成任务数是指团队或组织已经完成的任务总量。该度量项可以衡量任务完成的进度和效率，以及团队成员或组织的工作质量和产出。较高的已完成任务总数可能表明团队在交付工作方面表现出较好的能力。',
    'definition' => "所有的任务个数求和\n状态为已完成或者状态为已关闭且关闭原因为已完成\n过滤已删除的任务\n过滤已删除项目的任务\n过滤已删除执行的任务"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的未完成任务数',
    'alias'      => '未完成任务数',
    'code'       => 'count_of_unfinished_task',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的未完成任务数是指团队或组织未完成的任务总量。该度量项可以用来评估项目进展和未来工作量，同时也可以帮助进行资源分配和优先级确定。较大的未完成任务总数可能需要更多的努力和调整来确保任务按时完成。',
    'definition' => "复用：\n按系统统计的任务总数\n按系统统计的已完成任务数\n公式：\n按系统统计的未完成任务数=按系统统计的任务总数-按系统统计的已完成任务数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已关闭任务数',
    'alias'      => '已关闭任务数',
    'code'       => 'count_of_closed_task',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已关闭任务数是指团队或组织已经关闭的任务总量。该度量项可以用来评估项目或团队的运营情况和任务管理效果。较高的已关闭任务总数可能表明团队在任务管理方面表现出较好的能力，同时也可以释放资源和优先处理其他任务。',
    'definition' => "所有的任务个数求和\n状态为已关闭\n过滤已删除的任务\n过滤已删除项目的任务\n过滤已删除执行的任务"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度新增任务数',
    'alias'      => '新增任务数',
    'code'       => 'count_of_annual_created_task',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度新增任务数是指一年内新添加的任务总量。该度量项可以用来衡量团队或组织在某年内所承担的新增工作量。较高的年度新增任务数可能需要额外的资源和计划调整来满足需求。',
    'definition' => "所有的任务个数求和\n创建时间为某年\n过滤已删除的任务\n过滤已删除项目的任务\n过滤已删除执行的任务"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度完成任务数',
    'alias'      => '完成任务数',
    'code'       => 'count_of_annual_finished_task',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度完成任务数是指某年内已经完成的任务总量。该度量项可以用来评估团队或组织在某年内的工作效率和完成能力。较高的年度完成任务数表示团队或组织在项目执行方面表现出较好的效率。',
    'definition' => "所有的任务个数求和\n完成时间为某年\n过滤已删除的任务\n过滤已删除项目的任务\n过滤已删除执行的任务"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的月度新增任务数',
    'alias'      => '新增任务数',
    'code'       => 'count_of_monthly_created_task',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按系统统计的月度新增任务数是指在某月新添加的任务总量。该度量项可以用来衡量团队或组织在某月内所承担的新增工作量，以及对项目规划和资源分配的影响。较高的月度新增任务数可能需要额外的资源和计划调整来满足需求。',
    'definition' => "所有的任务个数求和\n创建时间为某年某月\n过滤已删除的任务\n过滤已删除项目的任务\n过滤已删除执行的任务"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的月度完成任务数',
    'alias'      => '完成任务数',
    'code'       => 'count_of_monthly_finished_task',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按系统统计的月度完成任务数是指在某月已经完成的任务总量。该度量项可以用来评估团队或组织在某月内的工作效率和完成能力。较高的月度完成任务数表示团队或组织在项目执行方面表现出较好的效率。',
    'definition' => "所有的任务个数求和\n完成时间为某年某月\n过滤已删除的任务\n过滤已删除项目的任务\n过滤已删除执行的任务"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的任务预计工时数',
    'alias'      => '任务预计工时数',
    'code'       => 'estimate_of_task',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'task',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的任务预计工时数是指所有任务预计完成所需的工时总和。该度量项可以用来规划资源和预估工期，为项目管理和团队协作提供依据。较准确的任务预计工时总数可以帮助团队更好地安排时间和资源，提高任务的完成效率。',
    'definition' => "所有的任务的预计工时数求和\n过滤父任务\n过滤已删除的任务\n过滤已删除项目的任务\n过滤已删除执行的任务"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的任务消耗工时数',
    'alias'      => '任务消耗工时数',
    'code'       => 'consume_of_task',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'task',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的任务消耗工时数是指已经花费的工时总和，用于完成所有任务。该度量项可以用来评估团队或组织在任务执行过程中的工时投入情况，以及在完成任务方面的效率和资源利用情况。较高的任务消耗工时总数可能表明需要审查工作流程和资源分配，以提高工作效率。',
    'definition' => "所有的任务的消耗工时数求和\n过滤父任务\n过滤已删除的任务\n过滤已删除项目的任务\n过滤已删除执行的任务"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的任务剩余工时数',
    'alias'      => '任务剩余工时数',
    'code'       => 'left_of_task',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'task',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的任务剩余工时数是指任务当前剩余工时的总和，用于完成所有任务。该度量项可以用来评估团队或组织在任务执行过程中剩余的工作量和时间，以及为完成任务所需的资源和计划。较小的任务剩余工时总数可能表示团队即将完成任务。',
    'definition' => "所有的任务的剩余工时数求和\n过滤父任务\n过滤已删除的任务\n过滤已删除项目的任务\n过滤已删除执行的任务"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的每日完成任务数',
    'alias'      => '完成任务数',
    'code'       => 'count_of_daily_finished_task',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'day',
    'desc'       => '按系统统计的每日完成任务数是指每日完成的任务总量。该度量项可以用来评估团队或组织每日的工作效率和任务完成能力。',
    'definition' => "所有的任务个数求和\n完成时间为某日\n过滤已删除的任务\n过滤已删除项目的任务\n过滤已删除执行的任务"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的Bug总数',
    'alias'      => 'Bug总数',
    'code'       => 'count_of_bug',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的Bug总数是指在整个系统中发现的所有Bug的数量。这个度量项反映了系统或项目的整体Bug质量情况。Bug总数越多可能代表系统或项目的代码质量存在问题，需要进行进一步的解决和改进。',
    'definition' => "所有Bug个数求和\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的激活Bug数',
    'alias'      => '激活Bug数',
    'code'       => 'count_of_activated_bug',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的激活Bug数是指当前尚未解决的Bug数量。这个度量项反映了系统或项目当前存在的待解决问题数量。激活Bug总数越多可能代表系统或项目的稳定性较低，需要加强Bug解决的速度和质量。',
    'definition' => "所有Bug个数求和\n状态为激活\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已解决Bug数',
    'alias'      => '已解决Bug数',
    'code'       => 'count_of_resolved_bug',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已解决Bug数是指已经被开发团队解决的Bug数量。反映了组织在特定时间段内已解决的Bug数量，用于评估系统质量、用户满意度、资源管理、过程改进和绩效评估等方面。通过跟踪和分析已解决的Bug数，可以及时发现问题、改进开发过程、提高用户满意度，并为团队绩效评估和优化提供依据。',
    'definition' => "所有Bug个数求和\n状态为已解决\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已关闭Bug总数',
    'alias'      => '已关闭Bug数',
    'code'       => 'count_of_closed_bug',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已关闭Bug总数是指已经被关闭的Bug数量。反映了组织特定时间段内已关闭的Bug数量，用于评估系统质量、进度管理、资源管理、过程改进和绩效评估等方面。通过跟踪和分析已关闭的Bug总数，可以及时发现问题、改进开发过程、提高项目进度，并为团队绩效评估和优化提供依据。',
    'definition' => "所有Bug个数求和\n状态为已关闭\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的未关闭Bug数',
    'alias'      => '未关闭Bug数',
    'code'       => 'count_of_unclosed_bug',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的未关闭Bug数是指当前仍然存在但未关闭的Bug数量。反映了组织在特定时间段内尚未解决的Bug数量，用于评估系统质量、问题管理、优先级和计划调整、资源管理以及过程改进等方面。通过跟踪和分析未关闭的Bug数，可以及时发现问题、优化问题处理流程、合理安排资源，并为团队的质量管理和持续改进提供依据。',
    'definition' => "复用：\n按系统统计的Bug总数\n按系统统计的已关闭Bug数\n公式：\n按系统统计的未关闭Bug数=按系统统计的Bug总数-按系统统计的已关闭Bug数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已修复Bug数',
    'alias'      => '已修复Bug数',
    'code'       => 'count_of_fixed_bug',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已修复Bug数是指解决并关闭的Bug数量。反映了组织在特定时间段内已经修复的Bug数量，用于评估系统质量、问题管理、进度管理、资源管理以及过程改进等方面。通过跟踪和分析已修复的Bug数，可以及时发现问题、优化问题处理流程、提高项目进度，并为团队的质量管理和持续改进提供依据。',
    'definition' => "所有Bug个数求和\n状态为已关闭\n解决方案为已解决\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的有效Bug数',
    'alias'      => '有效Bug数',
    'code'       => 'count_of_valid_bug',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的有效Bug数是指系统或项目中真正具有影响和价值的Bug数量。反映了一个系统或软件中有效的Bug数量。有效Bug是指经过验证和确认的真实问题，需要进行修复和解决的Bug。用于评估系统质量、问题管理、资源管理、过程改进以及用户满意度等方面。通过跟踪和分析有效Bug数，可以及时发现问题、优化问题处理流程、合理安排资源，并为团队的质量管理和持续改进提供依据，同时提升用户满意度和系统质量。',
    'definition' => "所有Bug个数求和\n解决方案为已解决和延期处理\n或状态为激活的Bug数\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度新增Bug数',
    'alias'      => '新增Bug数',
    'code'       => 'count_of_annual_created_bug',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度新增Bug数是指在一年内新发现的Bug数量。反映了一个系统或软件每年新增的Bug数量，用于评估评估系统质量、变更管理、资源规划、过程改进和趋势分析等方面。',
    'definition' => "所有Bug个数求和\n创建时间为某年\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度修复Bug数',
    'alias'      => '修复Bug数',
    'code'       => 'count_of_annual_fixed_bug',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度修复Bug数是指在一年内解决并关闭的Bug数量。反映了一个系统或软件在一年内修复的Bug数量，用于评估系统质量改进、用户满意度、故障管理、变更管理和资源规划等方面。通过跟踪和分析年度修复Bug数，可以及时发现和解决问题，改善系统的质量和可靠性。同时，通过Bug修复数的评估，可以提高用户满意度、优化故障管理流程、控制变更质量，合理安排资源，从而提升整体的研发效果和项目交付质量。',
    'definition' => "所有Bug个数求和\n状态为已关闭\n解决方案为已解决\n关闭时间为某年\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的月度新增Bug数',
    'alias'      => '新增Bug数',
    'code'       => 'count_of_monthly_created_bug',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按系统统计的月度新增Bug数是指在一个月内新发现的Bug数量。反映了一个系统或软件每个月新增的Bug数量，用于评估及时发现问题、变更管理与影响评估、趋势分析与问题预测以及资源规划与优化等方面。通过跟踪和分析月度新增Bug数，可以及时发现质量问题、优化变更管理、预测系统质量趋势，并合理安排资源，从而提升系统的质量和可靠性。',
    'definition' => "所有Bug个数求和\n创建时间为某年某月\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的月度修复Bug数',
    'alias'      => '修复Bug数',
    'code'       => 'count_of_monthly_fixed_bug',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按系统统计的月度修复Bug数是指在一个月内解决并关闭的Bug数量。反映了一个系统或软件每个月修复的Bug数量，用于评估质量改进、故障管理、变更管理、资源规划以及趋势分析与问题预测等方面。通过跟踪和分析月度修复Bug数，可以及时发现和解决问题，改善系统的质量和可靠性。',
    'definition' => "所有Bug个数求和\n状态为已关闭\n解决方案为已解决\n关闭时间为某年某月\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的每日关闭Bug数',
    'alias'      => '关闭Bug数',
    'code'       => 'count_of_daily_closed_bug',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'day',
    'desc'       => '按系统统计的每日关闭Bug数是指组织每日被确认并关闭的Bug的数量。该度量项可以帮助我们了解组织对已解决的Bug进行确认与关闭的速度和效率。',
    'definition' => "所有每日关闭的Bug数求和\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的Bug修复率',
    'alias'      => 'Bug修复率',
    'code'       => 'rate_of_fixed_bug',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'bug',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的Bug修复率是指已修复的Bug占相对于有效Bug数量的比例。反映了一个系统或软件中Bug修复的效率和速度，用于评估质量改进、故障管理、用户满意度、变更管理以及团队绩效评估与改进等方面。通过跟踪和分析Bug修复率，可以评估团队在修复Bug方面的效率和能力，及时发现和解决问题，提高系统的质量和可靠性。',
    'definition' => "复用：\n按系统统计的已修复Bug数\n按系统统计的有效Bug数\n公式：\n按系统统计的Bug修复率=按系统统计的已修复Bug数/按系统统计的有效Bug数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的用例总数',
    'alias'      => '用例总数',
    'code'       => 'count_of_case',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'case',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的用例总数是指系统或项目中的测试用例总数量。反映了一个系统或软件的功能广度和复杂性，用于评估功能完整性、需求管理、项目规模评估、测试覆盖度评估以及变更管理等方面。通过统计和跟踪用例总数，可以评估系统的功能广度和复杂性，帮助团队进行需求管理、项目规模评估、测试覆盖和变更管理，从而提高系统的开发效率和质量。',
    'definition' => "所有用例个数求和\n过滤已删除的用例\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度新增用例数',
    'alias'      => '新增用例数',
    'code'       => 'count_of_annual_created_case',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'case',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度新增用例数是指在一年内新增的测试用例数量。统计年度新增用例数可以帮助评估系统或项目在不同阶段的测试覆盖和测试深度。年度新增用例数的增加可能意味着对新功能和需求进行了更充分的测试。',
    'definition' => "所有用例个数求和\n创建时间在某年\n过滤已删除的用例\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的每日执行用例次数',
    'alias'      => '执行用例次数',
    'code'       => 'count_of_daily_run_case',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'case',
    'unit'       => 'count',
    'dateType'   => 'day',
    'desc'       => '按系统统计的每日执行用例次数表示组织每日执行的用例次数，这个度量项可以反映测试团队每日的工作效率和进展情况。',
    'definition' => "所有用例的执行次数求和\n过滤已删除的用例\n过滤已删除的产品\n执行时间为某日"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的用户总数',
    'alias'      => '用户总数',
    'code'       => 'count_of_user',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'user',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的人员总数是指在项目或系统中参与开发和管理的人员总数。反映了系统的用户基础和用户规模，用于评估组织内部资源、增长趋势等方面的有用信息。这对于组织发展、内部管理和战略决策具有重要意义。',
    'definition' => "系统所有用户个数求和\n过滤已删除的用户"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度添加用户数',
    'alias'      => '添加用户数',
    'code'       => 'count_of_annual_created_user',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'user',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度新增人员数是指在一年内新增加到项目或系统中的人员数量。反映了系统或平台在一年内新增用户数量的指标，用于评估团队扩充和人员流动情况。年度新增人员数的增加可能意味着团队的增加或项目的扩大。',
    'definition' => "系统所有用户个数求和\n添加时间为某年"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度日志记录的工时总数',
    'alias'      => '日志记录的工时总数',
    'code'       => 'hour_of_annual_effort',
    'purpose'    => 'hour',
    'scope'      => 'system',
    'object'     => 'effort',
    'unit'       => 'hour',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度日志记录的工时总数是指组织在某年度实际花费的总工时数。该度量项可以用来评估组织的工时投入情况和对资源的利用效率。较高的消耗工时数可能需要审查工作流程和资源分配，以提高工作效率和进度控制。',
    'definition' => "所有日志记录的工时之和\n记录时间在某年"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度投入总人天',
    'alias'      => '投入总人天',
    'code'       => 'day_of_annual_effort',
    'purpose'    => 'hour',
    'scope'      => 'system',
    'object'     => 'effort',
    'unit'       => 'manday',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度投入总人天是指团队总共投入的工作天数。该度量项可以用来评估人力资源投入情况。投入总人天的增加可能意味着项目投入的工作时间和资源的增加。',
    'definition' => "复用：\n按系统统计的年度日志记录的工时总数\n公式：\n按系统统计的年度投入总人天=按系统统计的年度日志记录的工时总数/后台配置的每日可用工时"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的每日投入总人天',
    'alias'      => '投入总人天',
    'code'       => 'day_of_daily_effort',
    'purpose'    => 'hour',
    'scope'      => 'system',
    'object'     => 'effort',
    'unit'       => 'manday',
    'dateType'   => 'day',
    'desc'       => '按系统统计的每日投入总人天是指团队每日投入的工作量。该度量项可以用来评估每日人力资源投入情况。',
    'definition' => "复用：\n按系统统计的每日日志记录的工时总数\n公式：\n按系统统计的每日投入总人天=按系统统计的每日日志记录的工时总数/后台配置的每日可用工时"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的每日日志记录的工时总数',
    'alias'      => '日志记录的工时总数',
    'code'       => 'hour_of_daily_effort',
    'purpose'    => 'hour',
    'scope'      => 'system',
    'object'     => 'effort',
    'unit'       => 'hour',
    'dateType'   => 'day',
    'desc'       => '按系统统计的每日日志记录的工时总数是指组织每日实际花费的总工时数。该度量项可以用来评估组织的工时投入情况和对资源的利用效率。较高的消耗工时数可能需要审查工作流程和资源分配，以提高工作效率和进度控制。',
    'definition' => "所有日志记录的工时之和\n记录时间在某日"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的文档总数',
    'alias'      => '文档总数',
    'code'       => 'count_of_doc',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'doc',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的文档总数是指系统或组织中存在的所有文档数量的统计值。反映了整体文档管理的规模和复杂度。文档总数越大，代表着组织的信息量越丰富，也可能意味着需要更多的资源来维护和管理这些文档。',
    'definition' => "所有文档个数求和\n过滤已删除的文档"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度新增文档个数',
    'alias'      => '新增文档个数',
    'code'       => 'count_of_annual_created_doc',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'doc',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度新增文档个数是指在某年度系统或组织中新建的文档数量。反映了组织中信息产生的速度和增长的趋势。年度新增文档个数越大，说明组织的信息需求和创造力较强，也可能需要投入更多的资源来管理和维护这些新增文档。该度量项还可以用于评估组织的创新能力和知识管理水平。',
    'definition' => "所有文档个数求和\n创建时间为某年\n过滤已删除的文档"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的反馈总数',
    'alias'      => '反馈总数',
    'code'       => 'count_of_feedback',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'feedback',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的反馈总数是指收集到的所有用户反馈的数量。这个度量项可以帮助团队了解用户对产品的关注点和问题，并作为改进产品质量和用户满意度的依据。较高的反馈总数可能暗示着用户的活跃度和关注度较高，需要团队及时响应和处理，同时暗示产品问题可能有很多。',
    'definition' => "所有的反馈个数求和\n过滤已删除的反馈\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的已关闭反馈数',
    'alias'      => '已关闭反馈数',
    'code'       => 'count_of_closed_feedback',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'feedback',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的已关闭反馈数是指已经处理完毕并关闭的用户反馈的数量。这个度量项可以反映团队对用户反馈的关注度和处理效率。较高的已关闭反馈总数可能意味着团队能够及时响应用户反馈，并持续改进产品以解决用户问题。',
    'definition' => "所有的反馈个数求和\n状态为已关闭\n过滤已删除的反馈"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度新增反馈数',
    'alias'      => '新增反馈数',
    'code'       => 'count_of_annual_created_feedback',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'feedback',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度新增反馈数是指在某年度收集到的用户反馈的数量。这个度量项可以帮助团队了解用户对产品的发展趋势和需求变化，并进行产品策略的调整和优化。较高的年度新增反馈数可能暗示着产品的用户基础扩大或者功能迭代带来了更多用户参与。',
    'definition' => "所有的反馈个数求和\n创建时间为某年\n过滤已删除的反馈"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的年度关闭反馈数',
    'alias'      => '关闭反馈数',
    'code'       => 'count_of_annual_closed_feedback',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'feedback',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按系统统计的年度关闭反馈数是指在某年度处理并关闭的用户反馈的数量。这个度量项可以帮助团队评估在某年度对用户反馈的响应能力和问题解决能力。较高的年度关闭反馈数可能暗示着团队能够高效地解决用户反馈并持续改进产品，提升用户满意度和产品质量。',
    'definition' => "所有的反馈个数求和\n关闭时间为某年\n过滤已删除的反馈"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的代码库总数',
    'alias'      => '代码库总数',
    'code'       => 'count_of_codebase',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'code',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的代码库总数是指整个研发团队中维护的所有代码库的总数量。通过统计代码库总数可以了解团队的代码库规模和复杂性。',
    'definition' => "所有代码库的个数求和，不统计已删除xxxxx"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计流水线总数',
    'alias'      => '流水线总数',
    'code'       => 'count_of_pipeline',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'pipeline',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的流水线总数是指系统中所有流水线的数量统计，它反映了项目或组织在软件开发和交付过程中采用自动化流程的程度。',
    'definition' => "所有流水线的个数求和\n不统计已删除"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的流水线执行数',
    'alias'      => '系统流水线执行数',
    'code'       => 'count_of_compile_pipeline',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'pipeline',
    'unit'       => 'count',
    'dateType'   => 'day',
    'desc'       => '按系统统计的流水线执行数是指在一定时间内的流水线执行的数量，反映了团队的开发效率和响应能力。较高的流水线执行数通常意味着团队能够快速地将代码变更集成到主分支，并及时交付新功能或修复。监控这一指标有助于团队优化开发流程，确保高效、稳定的交付。',
    'definition' => "系统的流水线执行数量\n不统计已删除代码库\n不统计已删除流水线"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的流水线执行平均耗时',
    'alias'      => '系统流水线执行平均耗时',
    'code'       => 'avg_of_compile_time_pipeline',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'pipeline',
    'unit'       => 'hour',
    'dateType'   => 'day',
    'desc'       => '按系统统计的流水线执行平均耗时是指在一定时间内的流水线执行时间/执行的数量，通过统计在一定时间范围内每次流水线执行的耗时，并计算出平均值，团队能够深入了解构建和部署过程的性能，及时识别潜在的瓶颈并优化工作流程。',
    'definition' => "系统的流水线执行时间/执行数量\n不统计已删除流水线"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的流水线执行成功率',
    'alias'      => '系统流水线执行成功率',
    'code'       => 'rate_of_success_pipeline',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'pipeline',
    'unit'       => 'percentage',
    'dateType'   => 'day',
    'desc'       => '按系统统计的流水线执行成功率是指在一定时间内的流水线执行成功数量/流水线执行数量，反映了自动化构建和部署过程的稳定性与可靠性。',
    'definition' => "系统的流水线执行成功数量/流水线执行数量\n不统计已删除代码库\n不统计已删除流水线"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计制品库总数',
    'alias'      => '制品库总数',
    'code'       => 'count_of_artifactrepo',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'artifact',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的制品库总数是指统计所有产品的制品库总数，它反映了研发团队所管理的制品数量。该度量项可以帮助团队可以评估制品管理的复杂性和效率，并根据需要进行合理的优化和调整。',
    'definition' => "所有制品库的个数求和\n不统计已删除"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计节点总数',
    'alias'      => '节点总数',
    'code'       => 'count_of_node',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'node',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的节点总数是指在禅道DevOps平台中使用的全部节点总数。',
    'definition' => "所有节点的个数求和"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计应用总数',
    'alias'      => '应用总数',
    'code'       => 'count_of_application',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'application',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的应用总数是指在禅道DevOps平台中使用的全部应用总数。',
    'definition' => "所有安装的应用个数求和"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计代码库待处理问题总数',
    'alias'      => '代码库待处理问题数',
    'code'       => 'count_of_pending_issue',
    'purpose'    => 'qc',
    'scope'      => 'system',
    'object'     => 'codebase',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '系统统计的代码库待处理问题总数是指所有代码库中尚未解决的问题数量的统计，它反映了代码库的健康状况和存在的潜在问题数量，通过对问题总数的监控和分析，可以及时发现并解决和解决问题，提高软件开发过程的效率和质量。',
    'definition' => "所有代码库的未关闭代码问题个数求和\n不统计删除的问题\n不统计删除的代码库里的问题"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计代码库中待处理的合并请求总数',
    'alias'      => '代码库中待处理的合并请求数',
    'code'       => 'count_of_pending_mergeRequest',
    'purpose'    => 'qc',
    'scope'      => 'system',
    'object'     => 'codebase',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '系统统计的待处理的合并请求总数是指代码库中等待合并的合并请求总数量，它反映了团队在合并代码方面的效率和进展情况，高数量可能意味着合并困难、合并冲突多、代码质量低等问题存在，需及时关注和处理以提升研发效能。',
    'definition' => "所有代码库的未关闭的合并请求个数求和 \n不统计已删除的合并请求\n不统计已删除代码库里的合并请求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的计划总数',
    'alias'      => '计划总数',
    'code'       => 'count_of_productplan_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'productplan',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的计划总数是指产品团队创建的所有计划数量。这个度量项可以反映产品团队的规划能力。适当的计划数量可以促进团队高效完成需求。',
    'definition' => "产品中计划的个数求和\n过滤已删除的计划\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的年度新增计划数',
    'alias'      => '新增计划数',
    'code'       => 'count_of_annual_created_productplan_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'productplan',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按产品统计的年度新增计划数是指某年度产品团队新创建的计划数量。这个度量项可以反映产品团队对于新需求的接收能力和规模的扩展。新增计划数越多，说明产品团队在该年度内面临着更多的新挑战和需求。',
    'definition' => "产品中创建时间为某年的计划个数求和\n过滤已删除的计划\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的年度完成计划数',
    'alias'      => '完成计划数',
    'code'       => 'count_of_annual_finished_productplan_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'productplan',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按产品统计的年度完成计划数是指某年度产品团队实际完成的计划数量。这个度量项可以反映产品团队在规划和执行过程中的效率和执行能力。完成计划数越多，说明产品团队在该年度内可能取得了更多的成果和交付物。',
    'definition' => "产品中计划个数求和\n完成时间为某年\n过滤已删除的计划\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的发布总数',
    'alias'      => '发布总数',
    'code'       => 'count_of_release_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'release',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的发布总数是指产品中所有发布的数量。这个度量项可以反映产品团队对产品发布的频率和稳定性的掌控程度。发布总数越多，说明产品团队有更多的迭代和产品版本更新。',
    'definition' => "产品中发布的个数求和\n过滤已删除的发布\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的年度新增发布数',
    'alias'      => '新增发布数',
    'code'       => 'count_of_annual_created_release_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'release',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按产品统计的年度新增发布数是指某年度产品中新增加的发布数量，该度量项可以反映产品团队在该年度内对产品新功能和改进的发布能力和速度。新增发布数越多，说明产品团队在该年度内推出了更多的新功能和改进。',
    'definition' => "产品中发布个数求和\n发布时间为某年\n过滤已删除的发布\n过滤已删除的产品\n过滤无效时间"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的月度新增发布数',
    'alias'      => '新增发布数',
    'code'       => 'count_of_monthly_created_release_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'release',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按产品统计的月度新增发布数是指在某月产品中新增加的发布数量。这个度量项可以反映产品团队在该月内对新功能和改进的发布能力和速度。新增发布数越多，说明产品团队在该月内推出了更多的新功能和改进。',
    'definition' => "产品中发布时间为某年某月的发布个数求和\n过滤已删除的发布\n过滤已删除的产品\n过滤无效时间"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的研发需求总数',
    'alias'      => '研发需求总数',
    'code'       => 'count_of_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的研发需求总数是指产品中创建的所有研发需求的数量。这个度量项可以反映团队需进行研发工作的规模。研发需求总数越多，可能意味着产品规模越大，面临的开发工作越多。',
    'definition' => "产品中研发需求的个数求和\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的已完成研发需求数',
    'alias'      => '已完成研发需求数',
    'code'       => 'count_of_finished_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的已完成研发需求数是指状态为已关闭且关闭原因为已完成的研发需求的数量。这个度量项可以反映产品团队在开发过程中的进展和交付能力。已完成研发需求数越多，说明产品团队可能取得了更多的研发成果。',
    'definition' => "产品中的研发需求个数求和\n阶段为已关闭\n关闭原因为已完成\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的已关闭研发需求数',
    'alias'      => '已关闭研发需求数',
    'code'       => 'count_of_closed_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的已关闭研发需求数是指产品中已经关闭的研发需求的数量。该度量项反映了产品研发的进展，可以用于评估产品的研发需求管理绩效和成果。较高的已关闭研发需求数可能代表团队取得了越多的研发成果。',
    'definition' => "产品中研发需求的个数求和\n阶段为已关闭\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的未关闭研发需求数',
    'alias'      => '未关闭研发需求数',
    'code'       => 'count_of_unclosed_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的未关闭研发需求数是指产品中未关闭的研发需求的数量。这个度量项可以反映产品团队研发需求的开发进度。未关闭研发需求数越多，说明产品团队的开发工作还有一定的进行中，并需要进一步跟进和完成。',
    'definition' => "复用：\n按产品统计的研发需求总数\n按产品统计的已关闭研发需求数\n按产品统计的关闭研发需求总数=按产品统计的研发需求总数-按产品统计的已关闭研发需求数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的已交付研发需求数',
    'alias'      => '已交付研发需求数',
    'code'       => 'count_of_delivered_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的已交付研发需求数表示已交付给用户的研发需求的数量。该度量项反映了产品中已发布或关闭原因为已完成的研发需求的数量，可以用于评估产品的研发需求交付能力。',
    'definition' => "产品中研发需求个数求和\n所处阶段为已发布或关闭原因为已完成\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的无效研发需求数',
    'alias'      => '无效研发需求数',
    'code'       => 'count_of_invalid_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的无效研发需求数是指产品中被判定为无效的研发需求的数量。这个度量项可以反映产品团队进行需求管理的有效性和能力。无效研发需求数越多，可能说明产品团队在需求管理中的团队协作能力较弱或对产品理解有偏差等。',
    'definition' => "产品中研发需求个数求和\n关闭原因为重复、不做、设计如此和已取消\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的有效研发需求数',
    'alias'      => '有效研发需求数',
    'code'       => 'count_of_valid_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的有效研发需求数是指在在产品中被确认为有效的研发需求数量。有效需求指的是符合产品策略和目标，可以实施并且对用户有价值的需求。较高的有效研发需求数通常表示产品的功能和特性满足了用户和市场的期望，有利于实现产品的成功交付和用户满意度。',
    'definition' => "复用：\n按产品统计的研发需求总数\n按产品统计的无效研发需求数\n公式：\n按产品统计的有效研发需求数=按产品统计的研发需求总数-按产品统计的无效研发需求数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的研发完毕的研发需求数',
    'alias'      => '研发完毕的研发需求数',
    'code'       => 'count_of_developed_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的研发完毕的研发需求数是指产品中阶段为研发完毕及以后的研发需求的数量。这个度量项可以反映产品在研发过程中的进展和成就。研发完毕的研发需求数越多，说明产品取得了更多的研发成果。',
    'definition' => "产品中研发需求个数求和\n阶段为（研发完毕、测试中、测试完毕、已验收、已发布）或关闭原因为已完成的\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的研发完毕的研发需求规模数',
    'alias'      => '研发完毕的研发需求规模数',
    'code'       => 'scale_of_developed_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的研发完毕的研发需求规模数是指产品中阶段为研发完毕及以后的研发需求的规模。这个度量项可以反映产品在研发过程中的进展和成就。研发完毕的研发需求规模数越多，说明产品取得了更多的研发成果。',
    'definition' => "产品中研发需求规模数求和\n阶段为（研发完毕、测试中、测试完毕、已验收、已发布）或关闭原因为已完成的\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的已立项研发需求的用例覆盖率',
    'alias'      => '已立项研发需求的用例覆盖率',
    'code'       => 'case_coverage_of_projected_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的已立项研发需求的用例覆盖率是指产品中已立项研发需求的用例覆盖程度。用例覆盖率可以衡量产品团队对于已立项需求的测试计划和测试用例编写的完整度。较高的用例覆盖率可能表示产品团队有较完整的测试计划。',
    'definition' => "复用：\n按产品统计的已立项研发需求数\n按产品统计的有用例的已立项研发需求数\n公式：\n按产品统计的已立项研发需求用例覆盖率=按产品统计的有用例的已立项研发需求数/按产品统计的已立项研发需求数\n过滤已删除的研发需求\n过滤已删除的产品\n过滤已删除的用例"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的年度新增研发需求数',
    'alias'      => '新增研发需求数',
    'code'       => 'count_of_annual_created_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按产品统计的年度新增研发需求数是指产品在某年度新增的研发需求数量。这个度量项可以反映产品团队在该年度内需求的增长或变化情况。',
    'definition' => "产品中研发需求的个数求和\n创建时间为某年\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的年度完成研发需求数',
    'alias'      => '完成研发需求数',
    'code'       => 'count_of_annual_finished_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按产品统计的年度完成研发需求数是指产品在某年度已关闭且关闭原因为已完成的研发需求数量。这个度量项可以反映产品团队在一年时间内的开发效率和成果。完成研发需求数量的增加说明产品团队在该年度内取得了更多的开发成果和交付物。',
    'definition' => "产品中关闭时间在某年且关闭原因为已完成的研发需求的个数求和\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的年度交付研发需求数',
    'alias'      => '交付研发需求数',
    'code'       => 'count_of_annual_delivered_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按产品统计的年度交付研发需求数是指产品在某年度内已经成功交付给用户的研发需求数量。这个度量项可以反映产品团队在开发过程中的交付能力和协作能力，可以用于评估产品的研发需求交付效能和效果。已交付的研发需求数量越多可能说明产品团队在该年度内的交付成果越多。',
    'definition' => "产品中研发需求个数求和\n所处阶段为已发布且发布时间为某年或关闭原因为已完成且关闭时间为某年\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的年度关闭研发需求数',
    'alias'      => '关闭研发需求数',
    'code'       => 'count_of_annual_closed_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按产品统计的年度关闭研发需求规模数表示产品在某年度关闭的研发需求数。该度量项反映了产品团队每年因完成、不做或取消等原因关闭的研发需求数，可以用于评估产品团队的研发需求规模管理和调整情况。',
    'definition' => "产品中关闭时间在某年的研发需求的个数求和\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的月度完成研发需求数',
    'alias'      => '完成研发需求数',
    'code'       => 'count_of_monthly_finished_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按产品统计的月度完成研发需求数表示每月完成的研发需求的数量。该度量项反映了产品的月度研发成果，可以用于评估产品团队的研发需求完成情况和效率。',
    'definition' => "产品中关闭时间为某年某月且关闭原因为已完成的研发需求的个数求和\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的已立项研发需求数',
    'alias'      => '已立项研发需求数',
    'code'       => 'count_of_projected_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的已立项研发需求数是指产品中已关联进项目的研发需求数。该度量项表示产品中获得批准需要投入资源进行开发的需求数量。产品中较高的已立项研发需求数可能表示产品相关项目的规模越大。',
    'definition' => "产品中研发需求个数求和\n过滤已删除的产品\n过滤已删除的研发需求\n研发需求被关联进项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的月度交付研发需求数',
    'alias'      => '交付研发需求数',
    'code'       => 'count_of_monthly_delivered_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按产品统计的月度交付研发需求数表示每月完成或关联到发布的研发需求的数量。该度量项反映了产品团队每月交付给用户的研发需求数量，可以用于评估产品团队的研发需求交付效能。',
    'definition' => "产品中研发需求个数求和\n所处阶段为已发布且发布时间为某年某月或关闭原因为已完成且关闭时间为某年某月\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的有用例的已立项研发需求数',
    'alias'      => '有用例的已立项研发需求数',
    'code'       => 'count_of_projected_story_with_case_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的有用例的已立项研发需求数是指产品中关联进项目且有用例的研发需求数量。该度量项反映了产品中对于已立项需求的测试用例编写情况。产品中较高的有用例的已立项研发需求数量可能表示需求测试用例覆盖度越高。',
    'definition' => "产品中研发需求个数求和\n研发需求关联进项目\n过滤已删除的产品\n过滤已删除的研发需求\n过滤没有用例的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的月度关闭研发需求数',
    'alias'      => '关闭研发需求数',
    'code'       => 'count_of_monthly_closed_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按产品统计的月度关闭研发需求规模数表示产品在某月度关闭的研发需求数。该度量项反映了产品团队每月因完成、不做或取消等原因关闭的研发需求数，可以用于评估产品团队的研发需求规模管理和调整情况。',
    'definition' => "产品中关闭时间为某年某月的研发需求的个数求和\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的月度新增研发需求数',
    'alias'      => '新增研发需求数',
    'code'       => 'count_of_monthly_created_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按产品统计的月度新增研发需求数是指在某月度新增的研发需求数量。这个度量项可以反映产品团队在该月度内需求的增长情况。月度新增研发需求数越多可能表示团队正在不断地推出新功能。',
    'definition' => "产品中研发需求的个数求和\n创建时间在某年某月\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的研发需求规模总数',
    'alias'      => '研发需求规模总数',
    'code'       => 'scale_of_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'measure',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的研发需求规模总数表示产品中所有研发需求的总规模。这个度量项可以反映团队需进行研发工作的规模，可以用于评估产品团队的研发需求规模管理和成果。',
    'definition' => "产品中研发需求的规模数求和\n过滤父研发需求\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的年度完成研发需求规模数',
    'alias'      => '完成研发需求规模数',
    'code'       => 'scale_of_annual_finished_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'measure',
    'dateType'   => 'year',
    'desc'       => '按产品统计的年度完成研发需求规模数是指产品在某年度已关闭且关闭原因为已完成研发需求的总规模数。这个度量项可以反映产品团队在一年时间内的开发效率和成果。完成研发需求规模数的增加说明产品团队在该年度内取得了更多的开发成果和交付物。',
    'definition' => "产品中研发需求的规模数求和\n关闭时间在某年\n关闭原因为已完成\n过滤父研发需求\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的年度交付研发需求规模数',
    'alias'      => '交付研发需求规模数',
    'code'       => 'scale_of_annual_delivered_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'measure',
    'dateType'   => 'year',
    'desc'       => '按产品统计的年度交付研发需求数是指产品在某年度内已经成功交付给用户的研发需求规模数。这个度量项可以反映产品团队在开发过程中的交付能力和协作能力，可以用于评估产品的研发需求交付效能和效果。已交付的研发需求规模数越多可能说明产品团队在该年度内的交付成果越多。',
    'definition' => "产品中研发需求规模数求和\n所处阶段为已发布且发布时间为某年某月或关闭原因为已完成且关闭时间为某年某月\n过滤父研发需求\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的年度关闭研发需求规模数',
    'alias'      => '关闭研发需求规模数',
    'code'       => 'scale_of_annual_closed_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'measure',
    'dateType'   => 'year',
    'desc'       => '按产品统计的年度关闭研发需求规模数表示产品在某年度关闭的研发需求的规模总数。该度量项反映了产品团队每年因完成、不做或取消等原因关闭研发需求数的规模总数，可以用于评估产品的团队研发需求规模管理和调整情况。',
    'definition' => "产品中研发需求的规模数求和\n关闭时间在某年\n过滤父研发需求\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的月度完成研发需求规模数',
    'alias'      => '完成研发需求规模数',
    'code'       => 'scale_of_monthly_finished_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按产品统计的月度完成研发需求规模数表示每月完成的研发需求的规模。该度量项反映了产品团队每月完成的研发需求规模，可以用于评估产品团队的研发需求完成情况和效率。',
    'definition' => "产品中关闭时间为某年某月且关闭原因为已完成的研发需求的规模数求和\n过滤父需求\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的研发需求评审通过率',
    'alias'      => '研发需求评审通过率',
    'code'       => 'rate_of_approved_story_in_product',
    'purpose'    => 'qc',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的研发需求评审通过率表示产品中通过评审的研发需求（不需要评审研发需求的与需要评审并通过的研发需求）相对于评审过的研发需求（不需要评审的研发需求与有评审结果的研发需求数）的比例。该度量项反映了需求评审过程中的成功率。',
    'definition' => "按产品统计的所有研发需求评审通过率=（按产品统计的不需要评审的研发需求数+评审结果确认通过的研发需求数）/（按产品统计的不需要评审的研发需求数+有评审结果的研发需求数）\n过滤已删除的研发需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的研发需求完成率',
    'alias'      => '研发需求完成率',
    'code'       => 'rate_of_finish_story_in_product',
    'purpose'    => 'rate',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的研发需求交付率表示按产品统计的已完成的研发需求规数相对于按产品统计的有效研发需求数。这个度量项衡量了研发团队完成需求的能力。完成率越高，代表研发团队有更多研发成果，保证产品的正常发布。',
    'definition' => "复用：\n按产品统计的已完成研发需求数\n按产品统计的无效研发需求数\n按产品统计的研发需求总数\n公式：\n按产品统计的研发需求完成率=按产品统计的已完成研发需求数/（按产品统计的研发需求总数-按产品统计的无效研发需求数）*100%"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的研发需求交付率',
    'alias'      => '研发需求交付率',
    'code'       => 'rate_of_delivery_story_in_product',
    'purpose'    => 'rate',
    'scope'      => 'product',
    'object'     => 'story',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的研发需求交付率表示按产品统计的已交付的研发需求数相对于按产品统计的有效研发需求数。这个度量项衡量了产品团队按时交付需求的能力。交付率越高，代表产品团队能够将更多的需求交付给用户。',
    'definition' => "复用：\n按产品统计的已交付研发需求数\n按产品统计的无效研发需求数\n按产品统计的研发需求总数\n公式：\n按产品统计的研发需求完成率=按产品统计的已交付研发需求数/（按产品统计的研发需求总数-按产品统计的无效研发需求数）*100%"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的用户需求总数',
    'alias'      => '用户需求总数',
    'code'       => 'count_of_requirement_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'requirement',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的用户需求总数是指产品所有用户需求的总数。该度量项反映了对用户需求量的整体把握和了解程度。越高的用户需求数可能表示市场潜力较大，产品的受欢迎程度较高，有更多的用户对该产品提出了需求。',
    'definition' => "产品中用户需求的个数求和\n过滤已删除的用户需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的年度新增用户需求数',
    'alias'      => '新增用户需求数',
    'code'       => 'count_of_annual_created_requirement_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'requirement',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按产品统计的年度新增用户需求数反映了产品在某年度内新产生的用户对产品的需求数量。越高的用户需求数可能表示产品在该年度获得了更多的用户关注和认可，有更多的用户愿意尝试和使用该产品。',
    'definition' => "产品中用户需求的个数求和\n创建时间为某年\n过滤已删除的用户需求\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的研发完毕研需规模的Bug密度',
    'alias'      => '研发完毕研需规模的Bug密度',
    'code'       => 'bug_concentration_of_developed_story_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的研发完毕研需规模的Bug密度表示按产品统计的有效Bug数相对于按产品统计的研发完成的研发需求规模数。该度量项反映了研发完毕的研需的质量表现，密度越低代表研发完毕的研需质量越高。',
    'definition' => "复用：\n按产品统计的有效Bug数\n按产品统计的研发完成的研发需求规模数\n公式：\n按产品统计的研发完成需求的Bug密度=按产品统计的有效Bug数/按产品统计的研发完成的研发需求规模数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的Bug总数',
    'alias'      => 'Bug总数',
    'code'       => 'count_of_bug_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的Bug总数是指在产品中发现的所有Bug的数量。这个度量项反映了产品整体Bug质量情况。Bug总数越多可能代表产品的代码质量存在问题，需要进行进一步的解决和改进。',
    'definition' => "产品中Bug的个数求和\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的激活Bug数',
    'alias'      => '激活Bug数',
    'code'       => 'count_of_activated_bug_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的激活Bug数是指产品中当前状态为激活的Bug数量。这个度量项反映了产品当前存在的待处理问题数量。激活Bug总数越多可能代表产品的稳定性较低，需要加强Bug解决的速度和质量。',
    'definition' => "产品中激活Bug的个数求和\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的有效Bug数',
    'alias'      => '有效Bug数',
    'code'       => 'count_of_effective_bug_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的有效Bug数是指产品中真正具有影响和价值的Bug数量。有效Bug通常是指导致产品不正常运行或影响用户体验的Bug。统计有效Bug数可以帮助评估产品的稳定性和质量，也可以评估测试人员之间的协作或对产品的了解程度。',
    'definition' => "产品中所有Bug个数求和\n解决方案为已解决、延期处理或状态为激活\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的已修复Bug数',
    'alias'      => '已修复Bug数',
    'code'       => 'count_of_fixed_bug_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的已修复Bug数是指解决方案为已解决并且状态为已关闭的Bug数量。这个度量项反映了产品解决的问题数量。已修复Bug数的可以评估开发团队在Bug解决方面的工作效率。',
    'definition' => "产品中Bug的个数求和\n解决方案为已解决\n状态为已关闭\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的严重程度为1级的Bug数',
    'alias'      => '严重程度为1级的Bug数',
    'code'       => 'count_of_severity_1_bug_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的严重程度为1级的Bug数是指在产品开发过程中发现的、对产品功能或性能产生重大影响的Bug数量。这些Bug可能会导致系统崩溃、功能无法正常运行、数据丢失等严重问题。统计这些Bug的数量可以帮助评估产品的稳定性和可靠性。',
    'definition' => "产品中Bug的个数求和\n严重程度为1级\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的严重程度为2级的Bug数',
    'alias'      => '严重程度为2级的Bug数',
    'code'       => 'count_of_severity_2_bug_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的严重程度为2级的Bug数是指在产品开发过程中发现的、对产品功能或性能产生较大影响的Bug数量。这些Bug可能会给用户带来不便或影响产品的某些功能。统计这些Bug的数量可以帮助评估产品的稳定性和可靠性。',
    'definition' => "产品的Bug个数求和\n严重程度为2级\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的严重程度为1、2级的Bug数',
    'alias'      => '严重程度为1、2级的Bug数',
    'code'       => 'count_of_severe_bug_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的严重程度为1、2级的Bug数是指在产品开发过程中发现的严重程度为1级和2级的Bug数量的总和。统计这些Bug的数量可以评估产品的质量和稳定性，同时也关注影响用户体验和功能完整性的问题。',
    'definition' => "复用：\n按产品统计的严重程度为1级的Bug数\n按产品统计的严重程度为2级的Bug数\n公式：\n按产品统计的严重程度为1、2级的Bug数=按产品统计的严重程度为1级的Bug数+按产品统计的严重程度为2级的Bug数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的年度新增Bug数',
    'alias'      => '新增Bug数',
    'code'       => 'count_of_annual_created_bug_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按产品统计的年度新增Bug数是指产品在某年度新发现的Bug数量。这个度量项反映了产品在某年度出现的新问题数量。年度新增Bug数越多可能意味着质量控制存在问题，需要及时进行处理和改进。',
    'definition' => "产品中Bug的个数求和\n创建时间为某年\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的年度新增有效Bug数',
    'alias'      => '新增有效Bug数',
    'code'       => 'count_of_annual_created_effective_bug_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按产品统计的年度新增有效Bug数是指产品在某年度新发现的真正具有影响和价值的Bug数量。有效Bug通常是指导致产品不正常运行或影响用户体验的Bug。统计有效Bug数可以帮助评估产品的稳定性和质量也可以评估测试人员之前的协作或对产品的了解程度。',
    'definition' => "产品中Bug个数求和\n创建时间为某年\n解决方案为已解决和延期处理或者状态为激活\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的年度修复Bug数',
    'alias'      => '修复Bug数',
    'code'       => 'count_of_annual_fixed_bug_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按产品统计的年度修复Bug数是指在某年度解决并关闭的Bug数量。这个度量项反映了产品在某年度解决的问题数量。年度修复Bug数越多可能说明开发团队在Bug解决方面的工作效率较高。',
    'definition' => "产品中Bug的个数求和\n关闭时间为某年\n解决方案为已解决\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的每日新增Bug数',
    'alias'      => '新增Bug数',
    'code'       => 'count_of_daily_created_bug_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'day',
    'desc'       => '按产品统计的每日新增Bug数是指在每天的产品开发过程中新发现并记录的Bug数量。该度量项可以体现产品开发过程中Bug的发现速度和趋势，较高的新增Bug数可能意味着存在较多的问题需要解决，同时也可以帮助识别产品开发过程中的瓶颈和潜在的质量风险。',
    'definition' => "产品中Bug数求和\n创建时间为某日\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的每日解决Bug数',
    'alias'      => '解决Bug数',
    'code'       => 'count_of_daily_resolved_bug_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'day',
    'desc'       => '按产品统计的每日解决Bug数是指产品每日解决的Bug的数量。该度量项可以帮助我们了解开发团队解决Bug的速度和效率。',
    'definition' => "产品中Bug数求和\n解决日期为某日\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的每日关闭Bug数',
    'alias'      => '关闭Bug数',
    'code'       => 'count_of_daily_closed_bug_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'day',
    'desc'       => '按产品统计的每日关闭Bug数是指每天在产品中每日关闭的Bug的数量。该度量项可以帮助我们了解开发团队对已解决的Bug进行确认与关闭的速度和效率，通过对比不同时间段的关闭Bug数，可以评估开发团队的协作和问题处理能力。',
    'definition' => "产品中Bug数求和\n关闭时间为某日\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的月度修复Bug数',
    'alias'      => '解决Bug数',
    'code'       => 'count_of_monthly_fixed_bug_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按产品统计的月度修复Bug数是指每天在产品开发过程中被解决并关闭的Bug的数量。该度量项可以帮助我们了解开发团队解决Bug的速度和效率。',
    'definition' => "产品中Bug的个数求和\n关闭时间为某年某月\n解决方案为已解决\n过滤已删除的Bug\n过滤已删除的产品\n",
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的月度关闭Bug数',
    'alias'      => '关闭Bug数',
    'code'       => 'count_of_monthly_closed_bug_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按产品统计的月度关闭Bug数是指在某月度关闭的Bug数量。这个度量项反映了产品开发过程中每月被确认并关闭的Bug的数量。该度量项可以帮助我们了解开发团队对Bug进行确认与关闭的速度和效率。',
    'definition' => "产品中关闭时间在某年某月的Bug个数求和\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的月度新增Bug数',
    'alias'      => '新增Bug数',
    'code'       => 'count_of_monthly_created_bug_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按产品统计的月度新增Bug数是指在某年度新发现的Bug数量。这个度量项反映了系统或项目在某月度出现的新问题数量。月度新增Bug数的增加可能意味着质量控制存在问题，需要及时进行处理和改进。',
    'definition' => "产品中创建时间在某年某月的Bug个数求和\n过滤已删除的Bug\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的Bug修复率',
    'alias'      => 'Bug修复率',
    'code'       => 'rate_of_fixed_bug_in_product',
    'purpose'    => 'rate',
    'scope'      => 'product',
    'object'     => 'bug',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的Bug修复率是指按产品统计的修复Bug数相对于按产品统计的有效Bug数的比例。该度量项可以帮助我们了解开发团队对Bug修复的效率和质量，高的修复率可能说明Bug得到及时解决，产品质量得到有效保障。',
    'definition' => "复用：\n按产品统计的修复Bug数\n按产品统计的有效Bug数\n公式：\n按产品统计的Bug修复率=按产品统计的修复Bug数/按产品统计的有效Bug数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的用例总数',
    'alias'      => '用例总数',
    'code'       => 'count_of_case_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'case',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的用例总数是指系统或项目中的测试用例总数量。用例是用来验证系统功能和性能的测试场景。统计用例总数可以帮助评估测试覆盖的广度和深度。用例总数越高可能意味着项目进行了全面和充分的测试。',
    'definition' => "产品中用例的个数求和\n过滤已删除的用例\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的年度新增用例数',
    'alias'      => '新增用例数',
    'code'       => 'count_of_annual_created_case_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'case',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按产品统计的年度新增用例数是指产品在某年度新增的测试用例数量。统计年度新增用例数可以帮助评估系统或项目在不同阶段的测试覆盖和测试深度。年度新增用例数的增加可能意味着对新功能和需求进行了充分的测试。',
    'definition' => "产品中用例的个数求和\n创建时间为某年\n过滤已删除的用例\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的反馈总数',
    'alias'      => '反馈总数',
    'code'       => 'count_of_feedback_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'feedback',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '产品的反馈总数',
    'definition' => "产品中反馈的个数求和\n过滤已删除的反馈\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的年度新增反馈数',
    'alias'      => '新增反馈数',
    'code'       => 'count_of_annual_created_feedback_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'feedback',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按产品统计的年度新增反馈数是指在某年度收集到的用户反馈的数量。这个度量项可以帮助团队了解用户对产品的发展趋势和需求变化，并进行产品策略的调整和优化。较高的年度新增反馈数可能暗示着产品的用户基础扩大或者功能迭代带来了更多用户参与，同时暗示产品问题可能有很多。',
    'definition' => "产品中创建时间为某年的反馈的个数求和\n过滤已删除的反馈\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的年度关闭反馈数',
    'alias'      => '关闭反馈数',
    'code'       => 'count_of_annual_closed_feedback_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'feedback',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按产品统计的年度关闭反馈数是指在某年度处理并关闭的用户反馈的数量。这个度量项可以帮助产品团队评估在某年度对用户反馈的响应能力和问题解决能力。较高的年度关闭反馈数可能暗示着团队能够高效地解决用户反馈并持续改进产品，提升用户满意度和产品质量。',
    'definition' => "产品中关闭时间为某年的反馈的个数求和\n过滤已删除的反馈\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的等待的工单数',
    'alias'      => '等待工单数',
    'code'       => 'count_of_wait_ticket_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'ticket',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的等待的工单数表示产品中状态为等待的工单数量之和。该数值越大，说明产品团队还有较多工单任务需要处理，可以一定程度反映客户问题的堆积。',
    'definition' => "产品中所有工单个数求和，状态为等待的工单，过滤已删除的工单，过滤已删除的产品。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的处理中的工单数',
    'alias'      => '处理中工单数',
    'code'       => 'count_of_doing_ticket_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'ticket',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的处理中的工单数表示产品中状态为处理中的工单数量之和。该数值越大，说明产品团队正在处理的工单数量较多，可以一定程度上反映团队的工作负载。',
    'definition' => "产品中所有工单个数求和，状态为处理中，过滤已删除的工单，过滤已删除的产品。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的已处理的工单数',
    'alias'      => '已处理工单数',
    'code'       => 'count_of_done_ticket_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'ticket',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的已处理的工单数表示产品中状态为已处理的工单数量之和。该数值越大，说明产品团队完成的工单数量越多，可以一定程度反映团队处理客户问题的效率。',
    'definition' => "产品中所有工单个数求和，状态为已处理，过滤已删除的工单，过滤已删除的产品。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的未关闭的工单数',
    'alias'      => '未关闭工单数',
    'code'       => 'count_of_unclosed_ticket_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'ticket',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的未关闭的工单数表示产品中状态为未关闭的工单数量之和。该数值越大，说明产品团队还有一定工单任务需要进一步完成。',
    'definition' => "产品中所有工单个数求和，过滤已关闭的工单，过滤已删除的工单，过滤已删除的产品。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的每周新增工单数',
    'alias'      => '新增工单数',
    'code'       => 'count_of_weekly_created_ticket_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'ticket',
    'unit'       => 'count',
    'dateType'   => 'week',
    'desc'       => '按产品统计的每周新增工单数表示产品中每周新创建的工单数量之和。较高的每周新增工单数可能暗示着产品近期发布的功能存在较多问题，需要及时处理。',
    'definition' => "产品中所有工单个数求和，创建时间为某周，过滤已删除的工单，过滤已删除的产品。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的计划工期',
    'alias'      => '计划工期',
    'code'       => 'planned_period_of_project',
    'purpose'    => 'time',
    'scope'      => 'project',
    'object'     => 'project',
    'unit'       => 'day',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的计划工期是基于项目计划和排期制定的预估工期。该度量项通过确定项目开始和结束日期之间的时间间隔来计算。计划工期用于制定项目的时间目标和进度安排，为项目管理提供了基准。与实际工期进行比较，可以评估项目的进展和时间规划的准确性，帮助团队及时调整工作计划。',
    'definition' => "计划完成日期-计划开始日期\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的剩余工期',
    'alias'      => '剩余工期',
    'code'       => 'left_period_of_project',
    'purpose'    => 'time',
    'scope'      => 'project',
    'object'     => 'project',
    'unit'       => 'day',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的剩余工期表示项目在当前时间点上还剩下的工作时间。这个度量项可以帮助团队评估项目的剩余工作量和进度。通过比较剩余工期和剩余工时，可以预测项目是否能够按时完成，并采取适当的措施来调整进度，以确保项目的成功交付。',
    'definition' => "剩余工期=计划截止日期-当前日期\r\n当剩余工期<0时默认为0\r\n当项目已关闭时剩余工期默认为0\r\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的实际工期',
    'alias'      => '实际工期',
    'code'       => 'count_of_actual_time_in_project',
    'purpose'    => 'time',
    'scope'      => 'project',
    'object'     => 'project',
    'unit'       => 'day',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的实际工期反映了项目在执行过程中实际花费的时间。该度量项通过统计项目实际的开始和完成日期来计算。实际工期的准确记录能够帮助团队评估项目的执行效率和时间管理能力。较短的实际工期可能意味着项目按计划进行，团队高效执行，而较长的实际工期可能表明项目存在一些延迟和挑战。',
    'definition' => "已关闭的项目：\n实际完成日期-实际开始日期\n未关闭的项目：\n当前日期-实际开始日期\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的工期偏差',
    'alias'      => '工期偏差',
    'code'       => 'variance_of_time_in_project',
    'purpose'    => 'time',
    'scope'      => 'project',
    'object'     => 'project',
    'unit'       => 'day',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的工期偏差表示实际工期与计划工期之间的差异。工期偏差的正值表示项目进度延迟，负值表示项目进度提前。工期偏差可以帮助团队及时识别项目进度的偏差，并采取相应的调整措施来重新规划资源和工作计划，以确保项目能够按时完成。',
    'definition' => "复用：\n按项目统计的实际工期\n按项目统计的计划工期\n公式：\n按项目统计的工期偏差=按项目统计的实际工期-按项目统计的计划工期\n其中未开始项目工期偏差为0"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的已关闭执行数',
    'alias'      => '已关闭执行数',
    'code'       => 'count_of_closed_execution_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的已关闭执行数表示在项目中已关闭的执行项的数量，可以用来了解已关闭的执行数量。',
    'definition' => "项目的执行个数求和\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的已挂起执行数',
    'alias'      => '已挂起执行数',
    'code'       => 'count_of_suspended_execution_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的已挂起执行数表示在项目中已挂起的执行项的数量，可以用来了解暂停的任务数量，可能是由于需求不明确或其他原因导致。',
    'definition' => "项目的执行个数求和\n状态为已挂起\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的进行中执行数',
    'alias'      => '进行中执行数',
    'code'       => 'count_of_doing_execution_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的进行中执行数表示在项目中正在进行中的执行项的数量，可以用来了解当前正在进行的任务数量，反映项目团队的工作进展。',
    'definition' => "所有的执行个数求和\n状态为进行中\n过滤已删除的执行"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的未开始执行数',
    'alias'      => '未开始执行数',
    'code'       => 'count_wait_execution_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的未开始执行数表示在项目中未开始的执行数，可以用来了解未开始的执行数量。',
    'definition' => "项目的执行个数求和\n状态为未开始\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的年度关闭执行数',
    'alias'      => '关闭执行数',
    'code'       => 'count_annual_closed_execution_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按项目统计的年度关闭执行数是指在项目中某年度已经关闭的执行数。该度量项反映了项目团队在某年度的工作效率和完成能力。较高的年度关闭执行数表示项目在完成任务方面表现出较高的效率，反之则可能需要审查工作流程和资源分配情况，以提高执行效率。',
    'definition' => "项目的执行个数求和\n关闭时间为某年\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的年度完成执行数',
    'alias'      => '完成执行数',
    'code'       => 'count_of_annual_finished_execution_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按项目统计的年度完成执行数是指项目在某年度已经完成的执行数。该度量项反映了项目团队在某年的工作效率和完成能力。较高的年度完成执行数表示团队在完成任务方面表现出较高的效率，反之则可能需要审查工作流程和资源分配情况，以提高执行效率。',
    'definition' => "项目的执行个数求和\n实际完成日期为某年\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的执行总数',
    'alias'      => '执行总数',
    'code'       => 'count_of_execution_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'execution',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的执行总数表示在项目中所有执行的数量，可以用来评估项目的规模、项目执行进度、工作负荷、绩效评估、风险控制和项目管理的有用信息。',
    'definition' => "项目的执行个数求和\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的研发需求总数',
    'alias'      => '研发需求总数',
    'code'       => 'count_of_story_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的研发需求总数是指项目中创建或关联的所有研发需求的数量，反映了项目的规模和复杂度，提供了关于需求管理、进度控制、资源规划、风险评估和质量控制的有用信息。',
    'definition' => "项目中研发需求个数求和\n过滤已删除的研发需求\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的已关闭研发需求数',
    'alias'      => '已关闭研发需求数',
    'code'       => 'count_of_closed_story_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的已关闭研发需求数是指项目中已经关闭的研发需求的数量反映了项目中已经关闭的研发需求的数量，提供了关于需求管理、项目进度、质量控制、用户满意度和绩效评估的有用信息。',
    'definition' => "项目中研发需求个数求和\n过滤已删除的研发需求\n状态为已关闭\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的未关闭研发需求数',
    'alias'      => '未关闭研发需求数',
    'code'       => 'count_of_unclosed_story_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的未关闭研发需求数是指项目中未关闭的研发需求的数量反映了项目团队在开发过程中的进行中的任务和计划，未关闭研发需求数越多，说明项目团队未完成的开发工作越多，需要进一步跟进从而完成。',
    'definition' => "复用：\n按项目统计的研发需求总数\n按项目统计的已关闭研发需求数\n公式：\n按项目统计的关闭研发需求数=按项目统计的研发需求总数-按项目统计的已关闭研发需求数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的已完成研发需求数',
    'alias'      => '已完成研发需求数',
    'code'       => 'count_of_finished_story_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的已完成研发需求数是指状态为已关闭且关闭原因为已完成的研发需求的数量。反映了项目团队在开发过程中的进展和交付能力，已完成研发需求数越多，说明项目团队在该时间段内取得了更多的开发成果。',
    'definition' => "项目中研发需求的个数求和\n状态为已关闭\n关闭原因为已完成\n过滤已删除的研发需求\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的无效研发需求数',
    'alias'      => '无效研发需求数',
    'code'       => 'count_of_invalid_story_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的无效研发需求数是指被判定为无效的研发需求数量。无效需求可能包括重复需求、不可实现的需求、或者与项目策略和目标不符的需求。通过对无效需求的统计，可以帮助项目团队优化需求管理和筛选机制，以提高需求有效性和资源利用率。较高的无效需求数量可能需要对需求收集和评估流程进行改进。',
    'definition' => "项目中研发需求的个数求和\n关闭原因为重复、不做、设计如此\n过滤已删除的研发需求\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的有效研发需求数',
    'alias'      => '有效研发需求数',
    'code'       => 'count_of_valid_story_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的有效研发需求数是指被确认为有效的研发需求数量。有效需求指的是符合项目策略和目标，可以实施并且对用户有价值的需求。通过对有效需求的统计，可以帮助项目团队评估项目需求的质量和重要性，并进行优先级排序和资源分配。较高的有效需求数量通常表示项目的功能和特性满足了用户和市场的期望，有利于实现项目的成功交付和用户满意度。',
    'definition' => "复用：\n按项目统计的无效研发需求数\n按项目统计的研发需求总数\n公式：\n按执行统计的有效研发需求数=按执行统计的研发需求总数-按执行统计的无效研发需求数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的所有研发需求规模数',
    'alias'      => '所有研发需求规模数',
    'code'       => 'scale_of_story_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'story',
    'unit'       => 'measure',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的所有研发需求规模数表示研发需求的规模总数反映了项目研发需求的规模总数，可以用于评估项目团队的研发需求规模管理和成果。',
    'definition' => "项目中研发需求的规模数求和\n过滤已删除的研发需求\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的年度完成研发需求数',
    'alias'      => '完成研发需求数',
    'code'       => 'count_of_annual_finished_story_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按项目统计的年度完成研发需求数是指在某年度已关闭且关闭原因为已完成的研发需求数量。这个度量项可以反映项目团队在某年度的开发效率和成果。完成研发需求数量的增加说明项目团队在该年度内取得了更多的开发成果和交付物。',
    'definition' => "项目中研发需求的个数求和\n关闭时间在某年\n关闭原因为已完成\n过滤已删除的研发需求\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的年度完成研发需求规模数',
    'alias'      => '完成研发需求规模数',
    'code'       => 'scale_of_annual_finished_story_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'story',
    'unit'       => 'measure',
    'dateType'   => 'year',
    'desc'       => '按项目统计的年度完成研发需求数是指在某年度已关闭且关闭原因为已完成的研发需求规模数。这个度量项可以反映项目团队在某年度的开发效率和成果。完成研发需求规模数的增加说明项目团队在该年度内取得了更多的开发成果和交付物。',
    'definition' => "项目中研发需求的规模数求和\n关闭时间在某年\n关闭原因为已完成\n过滤已删除的研发需求\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的研发需求完成率',
    'alias'      => '研发需求完成率',
    'code'       => 'rate_of_finished_story_in_project',
    'purpose'    => 'rate',
    'scope'      => 'project',
    'object'     => 'story',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的研发需求完成率表示按项目统计的已完成的研发需求数相对于按项目统计的有效研发需求数。衡量了项目研发团队完成需求的能力，完成率越高代表项目研发团队能够将需求交付给用户，实现正常发布的几率越大。',
    'definition' => "复用：\n按项目统计的已完成研发需求数\n按项目统计的有效研发需求数\n公式：\n按项目统计的研发需求完成率=按项目统计的已完成研发需求数/按项目统计的有效研发需求数*100%"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的任务总数',
    'alias'      => '任务总数',
    'code'       => 'count_of_task_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的任务总数是指整个项目当前存在的任务总量。该度量项可以用来跟踪任务的规模和复杂性，为资源分配和工作计划提供基础。较大的任务总数可能需要更多的资源和时间来完成，而较小的任务总数可能意味着项目负荷较轻或项目进展较好。',
    'definition' => "项目中所有的任务个数求和\n过滤已删除的任务\n过滤已删除执行的任务\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的未开始任务数',
    'alias'      => '未开始任务数',
    'code'       => 'count_of_wait_task_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的未开始任务数指的是在项目执行过程中未开始进行的任务数量。这个度量项帮助团队了解项目进展的一部分，即有多少任务未启动。通过统计未开始任务数，团队可以评估项目的准备状况、资源分配以及可能存在的延迟因素。',
    'definition' => "项目中任务个数求和\n状态为未开始\n过滤已删除的任务\n过滤已删除执行的任务\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的进行中任务数',
    'alias'      => '进行中任务数',
    'code'       => 'count_of_doing_task_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的进行中任务数表示项目执行过程中正在进行的任务数量。这个度量项帮助团队了解项目当前的工作负载和进展情况。统计进行中任务数可以帮助团队判断项目的工作量是否合理分配，并进行进一步的资源规划和调整。',
    'definition' => "项目中任务个数求和\n状态为进行中\n过滤已删除的任务\n过滤已删除执行的任务\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的已完成任务数',
    'alias'      => '已完成任务数',
    'code'       => 'count_of_finished_task_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的已完成任务数是指项目已经完成的任务总量。该度量项可以衡量任务完成的进度和效率，以及项目的工作质量和产出。较高的已完成任务总数可能表明项目在交付工作方面表现出较好的能力。',
    'definition' => "项目中任务个数求和\n状态为已完成或者状态为已关闭且关闭原因为已完成\n过滤已删除的任务\n过滤已删除执行的任务\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的任务预计工时数',
    'alias'      => '任务预计工时数',
    'code'       => 'estimate_of_task_in_project',
    'purpose'    => 'hour',
    'scope'      => 'project',
    'object'     => 'task',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的任务预计工时数是指在项目管理中，对所有任务的预计工时进行统计和汇总的度量。这个度量项用于评估项目的工作量和资源需求，并帮助规划和安排项目团队。任务预计工时数是通过对每个任务的工作量估算进行累加而得，可以作为项目计划和进度控制的依据。',
    'definition' => "项目中任务的预计工时数求和\n过滤已删除的任务\n过滤父任务\n过滤已删除执行的任务\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的任务消耗工时数',
    'alias'      => '任务消耗工时数',
    'code'       => 'consume_of_task_in_project',
    'purpose'    => 'hour',
    'scope'      => 'project',
    'object'     => 'task',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的任务消耗工时数是指已经花费的工时总和，用于完成所有任务。该度量项可以用来评估项目在任务执行过程中的工时投入情况，以及在完成任务方面的效率和资源利用情况。较高的任务消耗工时总数可能表明需要审查工作流程和资源分配，以提高工作效率。',
    'definition' => "项目中任务的消耗工时数求和\n过滤已删除的任务\n过滤父任务\n过滤已删除执行的任务\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的任务剩余工时数',
    'alias'      => '任务剩余工时数',
    'code'       => 'left_of_task_in_project',
    'purpose'    => 'hour',
    'scope'      => 'project',
    'object'     => 'task',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的任务剩余工时数是指当前未消耗的工时总和，用于完成所有任务。该度量项可以用来评估项目在任务执行过程中剩余的工作量和时间，以及为完成任务所需的资源和计划。较小的任务剩余工时总数可能表示项目将及时完成任务，而较大的任务剩余工时总数可能需要重新评估进度和资源分配。',
    'definition' => "项目中任务的剩余工时数求和\n过滤已删除的任务\n过滤父任务\n过滤已删除执行的任务\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按瀑布项目统计的截止本周已完成任务工作的预计工时(EV)',
    'alias'      => '截止本周已完成任务工作的预计工时',
    'code'       => 'ev_of_weekly_finished_task_in_waterfall',
    'purpose'    => 'hour',
    'scope'      => 'project',
    'object'     => 'task',
    'unit'       => 'hour',
    'dateType'   => 'week',
    'desc'       => '按瀑布项目统计的截止本周已完成任务工作的预计工时指的是在瀑布项目管理方法中，已经完成的任务的预计工时。这个度量项用来评估项目进展与实际完成情况的一致性。EV的值越高，代表项目团队在按计划完成任务的工作量方面表现得越好。',
    'definition' => "复用： 按项目统计的任务进度、按项目统计的任务预计工时数，公式： 按项目统计的已完成任务工作的预计工时(EV)=按项目统计的任务预计工时数*按项目统计的任务进度；要求项目为瀑布项目，过滤父任务，过滤消耗工时为0的任务，过滤已删除的任务，过滤已取消的任务，过滤已删除执行下的任务，过滤已删除的项目。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按瀑布项目统计的截止本周的任务的计划完成工时(PV)',
    'alias'      => '截止本周的任务的计划完成工时(PV)',
    'code'       => 'pv_of_weekly_task_in_waterfall',
    'purpose'    => 'hour',
    'scope'      => 'project',
    'object'     => 'task',
    'unit'       => 'hour',
    'dateType'   => 'week',
    'desc'       => '按瀑布项目统计的每周的任务的计划完成工时指的是在瀑布项目管理方法中，按计划需要完成的任务的总预计工时。这个度量项用于评估每周的任务的预期工作量，可用作与实际花费工时和已完成任务的预计工时进行比较。',
    'definition' => "1.任务截至日期小于等于本周结束日期，累加预计工时。\n2.任务预计开始日期小于或等于本周结束日期，预计截至日期大于本周结束日期，累加预计工时=(任务的预计工时÷任务工期天数)x 任务预计开始到本周结束日期的天数。\n条件：过滤父任务，过滤已删除的任务，过滤已取消的任务，过滤已删除的执行的任务，过滤已删除的项目；任务未填写预计开始日期时默认取任务所属阶段的计划开始日期；任务未填写预计截至日期，预计截至日期默认取任务所属阶段的计划完成日期，时间只计算后台维护的工作日。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的任务进度',
    'alias'      => '任务进度',
    'code'       => 'progress_of_task_in_project',
    'purpose'    => 'rate',
    'scope'      => 'project',
    'object'     => 'task',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的任务进度是指项目团队按已消耗的工时数与已消耗和剩余的工时数的比率。这个度量项能够反映项目进度的准确性和任务执行的效率。',
    'definition' => "复用：\n按项目统计的任务消耗工时数\n按项目统计的任务剩余工时数\n公式：\n按项目统计的任务进度=按项目统计的任务消耗工时数/（按项目统计的任务消耗工时数+按项目统计的任务剩余工时数）"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按瀑布项目统计的截止本周的进度偏差率',
    'alias'      => '进度偏差率',
    'code'       => 'sv_weekly_in_waterfall',
    'purpose'    => 'rate',
    'scope'      => 'project',
    'object'     => 'task',
    'unit'       => 'percentage',
    'dateType'   => 'week',
    'desc'       => '按瀑布项目统计的截止本周的进度偏差率是用来衡量项目截止本周的进度与计划进度之间的差异。它通过计算已完成的工作量与计划工作量之间的差异来评估项目的进展情况。',
    'definition' => "复用： 按瀑布项目统计的截止本周已完成任务工作的预计工时(EV) 、按瀑布项目统计的截止本周的任务的计划完成工时(PV)，公式： 按瀑布项目统计的截止本周的进度偏差率=(EV-PV)/PV*100%"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按瀑布项目统计的截止本周的成本偏差率',
    'alias'      => '成本偏差率',
    'code'       => 'cv_weekly_in_waterfall',
    'purpose'    => 'rate',
    'scope'      => 'project',
    'object'     => 'task',
    'unit'       => 'percentage',
    'dateType'   => 'week',
    'desc'       => '按瀑布项目统计的截止本周的成本偏差率用于衡量项目的实际成本与计划成本之间的差异。它通过计算已花费的成本与预计花费的成本之间的差异来评估项目的成本绩效。',
    'definition' => "复用： 按瀑布项目统计的截止本周已完成任务工作的预计工时、按瀑布项目统计的截止本周的实际花费工时(AC) 公式： 按瀑布项目统计的截止本周的成本偏差率=(EV-AC)/AC*100%"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的Bug总数',
    'alias'      => 'Bug总数',
    'code'       => 'count_of_bug_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的Bug总数是指在项目中发现的所有Bug的数量。这个度量项反映了项目的整体Bug质量情况。Bug总数越多可能代表项目的代码质量存在问题，需要进行进一步的解决和改进。',
    'definition' => "项目中Bug个数求和\n过滤已删除的Bug\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的激活Bug数',
    'alias'      => '激活Bug数',
    'code'       => 'count_of_activated_bug_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的激活Bug数是指当前未解决的Bug数量。这个度量项反映了项目当前存在的待解决问题数量。激活Bug总数越多可能代表项目的稳定性较低，需要加强Bug解决的速度和质量。',
    'definition' => "项目中Bug个数求和\n状态为激活\n过滤已删除的Bug\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的已关闭Bug数',
    'alias'      => '已关闭Bug数',
    'code'       => 'count_of_closed_bug_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的已关闭Bug总数是指已经被关闭的Bug数量。这个度量项反映了项目中已经关闭的缺陷数量。已关闭Bug总数的增加说明项目进行了持续的改进和修复工作。',
    'definition' => "项目中Bug个数求和\n状态为已关闭\n过滤已删除的Bug\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的人员总数',
    'alias'      => '人员总数',
    'code'       => 'count_of_user_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'user',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的人员总数是指参与项目的全部人员的数量。这个度量项用于了解项目团队的规模和组成，对项目资源的分配和管理起到重要作用。',
    'definition' => "项目中团队成员个数求和\n过滤已移除的人员\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的项目内所有消耗工时数',
    'alias'      => '所有消耗工时数',
    'code'       => 'consume_of_all_in_project',
    'purpose'    => 'hour',
    'scope'      => 'project',
    'object'     => 'effort',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的项目内所有消耗工时数是指项目实际花费的总工时数。该度量项可以用来评估项目的工时投入情况和对资源的利用效率。较高的消耗工时数可能需要审查工作流程和资源分配，以提高工作效率和进度控制。',
    'definition' => "项目中所有日志记录的工时之和\n记录时间在某年\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的已投入人天',
    'alias'      => '已投入人天',
    'code'       => 'day_of_invested_in_project',
    'purpose'    => 'hour',
    'scope'      => 'project',
    'object'     => 'effort',
    'unit'       => 'manday',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的已投入人天是指项目总共投入的工作天数。该度量项可以用来评估项目的人力资源投入情况。投入总人天的增加可能意味着项目投入的工作时间和资源的增加。',
    'definition' => "复用：\n按项目统计的日志记录的工时总数\n公式：\n按项目统计的已投入人天=按项目统计的项目内所有消耗工时数/后台配置的每日可用工时"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按瀑布项目统计截止本周的实际花费工时(AC)',
    'alias'      => '瀑布项目截止本周实际花费工时',
    'code'       => 'ac_of_weekly_all_in_waterfall',
    'purpose'    => 'hour',
    'scope'      => 'project',
    'object'     => 'effort',
    'unit'       => 'hour',
    'dateType'   => 'week',
    'desc'       => '按瀑布项目统计的截止本周实际花费工时指的是在瀑布项目管理方法中，截止本周实际花费的工时总数。这个度量项用于评估实际工作量和预计工作量之间的差异，有助于估计项目的真实进展情况。AC的值越接近EV，代表项目团队在任务执行方面表现得越好。',
    'definition' => "瀑布项目中本周结束之前所有日志记录的工时之和 过滤已删除的项目。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的开放的风险数',
    'alias'      => '开放的风险数',
    'code'       => 'count_of_opened_risk_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'risk',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的开放的风险数是指在项目管理中，正在被跟踪和管理的项目风险的数量。风险是项目中潜在的不确定事件或情况，可能对项目目标的达成产生负面影响。通过跟踪和管理项目风险，项目团队可以及时采取措施降低风险的概率和影响程度。',
    'definition' => "项目中风险的个数求和\n状态为开放\n过滤已删除的风险\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的开放的问题数',
    'alias'      => '开放的问题数',
    'code'       => 'count_of_opened_issue_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'issue',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的开放的问题数指的是在项目管理中，正在被跟踪和解决的项目问题的数量。问题是指在项目执行过程中遇到的障碍、困难或需要解决的事项。通过跟踪和解决项目问题，可以避免问题的积累和对项目目标的影响。',
    'definition' => "项目中问题的个数求和\n状态为开放\n过滤已删除的问题\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的研发需求总数',
    'alias'      => '研发需求总数',
    'code'       => 'count_of_story_in_execution',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的研发需求总数是指执行中创建和关联的所有研发需求的数量。该度量项反映了执行的规模和复杂度，为执行计划和资源分配提供了参考。',
    'definition' => "执行中研发需求个数求和\n过滤已删除的研发需求\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的已完成研发需求数',
    'alias'      => '已完成研发需求数',
    'code'       => 'count_of_finished_story_in_execution',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的已完成研发需求数是指状态为已关闭且关闭原因为已完成的研发需求的数量。这个度量项可以反映执行团队在开发过程中的进展和交付能力。已完成研发需求数越多，说明执行团队在该时间段内取得了更多的开发成果。',
    'definition' => "执行中研发需求的个数求和\n状态为已关闭\n关闭原因为已完成\n过滤已删除的研发需求\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的无效研发需求数',
    'alias'      => '无效研发需求数',
    'code'       => 'count_of_invalid_story_in_execution',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的无效研发需求数是指被判定为无效的研发需求数量。无效需求可能包括重复需求、不可实现的需求、或者与项目策略和目标不符的需求。通过对无效需求的统计，可以帮助执行团队优化需求管理和筛选机制，以提高需求有效性和资源利用率。较高的无效需求数量可能需要对需求收集和评估流程进行改进。',
    'definition' => "执行中研发需求的个数求和\n关闭原因为重复、不做、设计如此和已取消\n过滤已删除的研发需求\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的有效研发需求数',
    'alias'      => '有效研发需求数',
    'code'       => 'count_of_valid_story_in_execution',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的有效研发需求数是指被确认为有效的研发需求数量。有效需求指的是符合项目策略和目标，可以实施并且对用户有价值的需求。通过对有效需求的统计，可以帮助执行团队评估项目需求的质量和重要性，并进行优先级排序和资源分配。较高的有效需求数量通常表示执行的功能和特性满足了用户和市场的期望，有利于实现项目的成功交付和用户满意度。',
    'definition' => "复用：\n按执行统计的无效研发需求数\n按执行统计的研发需求总数\n公式：\n按执行统计的有效研发需求数=按执行统计的研发需求总数-按执行统计的无效研发需求数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的研发完成的研发需求数',
    'alias'      => '研发完成的研发需求数',
    'code'       => 'count_of_developed_story_in_execution',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的研发完成的研发需求数是指执行中研发完成的研发需求的数量。这个度量项可以反映执行的进展。研发完成的研发需求数越多，说明执行团队在该时间段内取得了更多的研发成果。',
    'definition' => "执行中所处阶段为研发完毕、测试中、测试完毕、已验收、已发布和关闭原因为已完成的研发需求个数求和\n过滤已删除的研发需求\n过滤已删除产品的研发需求\n过滤已删除的执行"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的研发需求完成率',
    'alias'      => '研发需求完成率',
    'code'       => 'rate_of_finished_story_in_execution',
    'purpose'    => 'rate',
    'scope'      => 'execution',
    'object'     => 'story',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的研发需求完成率表示按执行统计的已完成的研发需求数相对于按执行统计的有效研发需求数。这个度量项衡量了执行研发团队完成需求的能力。',
    'definition' => "复用：\n按执行统计的已完成研发需求数\n按执行统计的有效研发需求数\n公式：\n按执行统计的研发需求完成率=按执行统计的已完成研发需求数/按执行统计的有效研发需求数*100%"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的研发完成需求占比',
    'alias'      => '研发完成需求占比',
    'code'       => 'rate_of_developed_story_in_execution',
    'purpose'    => 'rate',
    'scope'      => 'execution',
    'object'     => 'story',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的研发完成需求占比表示按执行统计的研发完成的研发需求规数相对于按产品统计的研发需求总数的比例。这个度量项衡量了执行中研发团队完成需求的数量，可以衡量团队的研发进展，帮助团队更好的安排研发资源。',
    'definition' => "复用：\n按执行统计的研发完成的研发需求数\n按执行统计的研发需求总数\n公式：\n按执行统计的研发完成需求占比=按执行统计的研发完成的研发需求数/按执行统计的研发需求总数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的执行关闭时验收通过的研发需求数',
    'alias'      => '执行关闭时验收通过研发需求数',
    'code'       => 'count_of_verified_story_in_execution_when_closing',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的执行关闭时验收通过的研发需求数表示执行关闭时需求阶段为已验收、已发布或状态为已关闭且关闭原因为已完成的研发需求的数量。该度量项反映了执行关闭时能够验收通过的研发需求的数量，可以用于评估执行团队的研发效率和研发质量。',
    'definition' => "执行关闭时，满足以下条件的执行中研发需求个数求和，条件是：所处阶段为已验收、已发布或关闭原因为已完成的研发需求，过滤已删除的研发需求，过滤已删除的执行，过滤已删除的项目，过滤已删除的产品。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的研发需求计划负载',
    'alias'      => '执行计划负载',
    'code'       => 'workload_of_plan_in_execution',
    'purpose'    => 'qc',
    'scope'      => 'execution',
    'object'     => 'execution',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的研发需求计划负载是指执行开始时计划的需求规模数与执行开发人员可用工时数的比率。该度量项反映了团队的工作负载，可以帮助团队进行资源调配和需求规划。',
    'definition' => "复用：按执行统计的截止执行开始当天研发需求规模数、按执行统计的开发人员可用工时；公式：按执行统计的截止执行开始当天研发需求规模数/按执行统计的开发人员可用工时"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的执行关闭时测试缺陷密度',
    'alias'      => '执行关闭时测试缺陷密度',
    'code'       => 'test_concentration_in_execution_when_closing',
    'purpose'    => 'qc',
    'scope'      => 'execution',
    'object'     => 'execution',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的执行测试缺陷密度是指执行产生的有效Bug数与执行交付的研发需求数的比率。该度量项反映了团队交付的研发需求的质量，可以帮助团队识别研发中存在的潜在问题。',
    'definition' => "复用：按执行统计的执行关闭时已交付的研发需求规模数、按执行统计的新增有效Bug数；公式：按执行统计的新增有效Bug数/按执行统计的执行关闭时已交付的研发需求规模数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的执行关闭时执行验收通过率',
    'alias'      => '执行验收通过率',
    'code'       => 'rate_of_verified_story_in_execution_when_closing',
    'purpose'    => 'qc',
    'scope'      => 'execution',
    'object'     => 'execution',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的执行验收通过率是指执行关闭时通过验收需求数量与执行所有需求的比率。该度量项反映了已完成的需求是否符合需求验收标准，可以帮助团队识别研发质量存在的潜在问题。',
    'definition' => "复用：按执行统计的执行关闭时验收通过的研发需求数、按执行统计的有效研发需求数；公式：按执行统计的执行关闭时验收通过的研发需求数/按执行统计的有效研发需求数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的任务总数',
    'alias'      => '任务总数',
    'code'       => 'count_of_task_in_execution',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的任务总数是指整个执行当前存在的任务总量。该度量项可以用来跟踪任务的规模和复杂性，为资源分配和工作计划提供基础，可以帮助团队评估工作负荷和任务分配的合理性。',
    'definition' => "执行中所有的任务个数求和\n过滤已删除的任务\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的已完成任务数',
    'alias'      => '已完成任务数',
    'code'       => 'count_of_finished_task_in_execution',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的已完成任务数是指执行已经完成的任务总量。该度量项可以衡量任务完成的进度和效率，以及项目的工作质量和产出。较高的已完成任务总数可能表明项目在交付工作方面表现出较好的能力。',
    'definition' => "执行中任务个数求和\n状态为已完成或者状态为已关闭且关闭原因为已完成\n过滤已删除的任务\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的未完成任务数',
    'alias'      => '未完成任务数',
    'code'       => 'count_of_unfinished_task_in_execution',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的未完成任务数是指执行未完成的任务总量。该度量项反映了团队的待办工作量和未来的工作压力。较低的未完成任务总数可能表明项目在交付工作方面表现出较好的能力。',
    'definition' => "复用：\n按执行统计的未完成任务数\n按执行统计的任务总数\n公式：\n按执行统计的未完成任务数=按执行统计的任务总数-按执行统计的已完成任务数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的日完成任务数',
    'alias'      => '完成任务数',
    'code'       => 'count_of_daily_finished_task_in_execution',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'day',
    'desc'       => '按执行统计的日完成任务数是指每天完成的任务数量。该度量项反映了团队的日常工作效率和任务完成速度。',
    'definition' => "执行中任务个数求和\n状态为已完成\n实际完成日期为某日\n过滤已删除的任务\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的测试任务数',
    'alias'      => '测试任务数',
    'code'       => 'count_of_test_task_in_execution',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的测试任务数是指执行中任务类型为测试的任务数求和。该度量项反映了执行中测试的工作量，可以帮助团队进行测试资源调配。',
    'definition' => "执行中满足以下条件的任务个数求和，条件是：任务类型为测试，过滤已删除的任务，过滤已删除的执行，过滤已删除的项目。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的截止执行开始当天的测试任务数',
    'alias'      => '截止执行开始当天的测试任务数',
    'code'       => 'count_of_test_task_in_execution_when_starting',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的截止执行开始当天的测试任务数表示执行开始时已创建的测试任务的数量。该度量项反映了本期执行计划完成的测试任务数量，可以用于评估执行团队测试人员的工作负载。',
    'definition' => "截止执行开始当天23:59分的任务个数求和，任务类型为测试，过滤已删除的任务，过滤已取消的任务数，过滤已删除的执行，过滤已删除的项目。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的执行关闭时已完成的测试任务数',
    'alias'      => '执行关闭时已完成测试任务数',
    'code'       => 'count_of_finished_test_task_in_execution_when_closing',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的执行关闭时已完成测试任务数表示执行关闭时任务状态为已完成的测试任务个数求和。该度量项反映了执行关闭时测试人员完成的测试任务个数，可以评估执行中测试人员的实际工作量和测试效率。',
    'definition' => "执行关闭时执行中满足以下条件的测试任务个数求和，条件是：任务类型为测试，状态为已完成或已关闭且关闭原因为已完成，过滤已删除的任务，过滤已删除的执行，过滤已删除的项目。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的任务剩余工时数',
    'alias'      => '任务剩余工时数',
    'code'       => 'left_of_task_in_execution',
    'purpose'    => 'hour',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的任务剩余工时数是指当前未消耗的工时总和，用于完成所有任务。该度量项反映了任务完成的剩余工作量，可以帮助团队预测任务的完成时间和资源需求。',
    'definition' => "执行中任务的剩余工时数求和\n过滤已删除的任务\n过滤父任务\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的任务预计工时数',
    'alias'      => '任务预计工时数',
    'code'       => 'estimate_of_task_in_execution',
    'purpose'    => 'hour',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的任务预计工时数是指在执行管理中，对所有任务的预计工时进行统计和汇总的度量。该度量项反映了任务的预计复杂性和所需的资源投入，可以帮助团队管理者评估任务的难度并安排资源。',
    'definition' => "执行中任务的预计工时数求和\n过滤已删除的任务\n过滤父任务\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的任务消耗工时数',
    'alias'      => '任务消耗工时数',
    'code'       => 'consume_of_task_in_execution',
    'purpose'    => 'hour',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的任务消耗工时数是指已经花费的工时总和，用于完成所有任务。该度量项反映了任务的实际完成情况和资源使用情况，可以帮助团队掌握任务的进展情况和资源利用效率。',
    'definition' => "执行中任务的消耗工时数求和\n过滤已删除的任务\n过滤父任务\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的任务进度',
    'alias'      => '任务进度',
    'code'       => 'progress_of_task_in_execution',
    'purpose'    => 'rate',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的任务进度是指执行团队按已消耗的工时数与已消耗和剩余的工时数的比率。该度量项反映了任务的执行进展情况，可以帮助团队评估任务是否按计划进行并做出相应调整。',
    'definition' => "复用：\n按执行统计的任务消耗工时数\n按执行统计的任务剩余工时数\n公式：\n按执行统计的任务进度=按执行统计的任务消耗工时数/（按执行统计的任务消耗工时数+按执行统计的任务剩余工时数）"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的待评审研发需求数',
    'alias'      => '待评审研发需求数',
    'code'       => 'count_of_reviewing_story_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按人员统计的待评审研发需求数表示每个人需要评审的研发需求数量之和。反映了每个人需要评审的研发需求的规模。该数值越大，说明需要投入越多的时间评审需求。',
    'definition' => "所有研发需求个数求和\n评审人为某人\n评审结果为空\n评审状态为评审中\n过滤已删除的需求\n过滤已删除产品的需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的每日评审研发需求数',
    'alias'      => '评审研发需求数',
    'code'       => 'count_of_daily_review_story_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'day',
    'desc'       => '按人员统计的日评审研发需求数表示每个人每日评审的研发需求数量之和。反映了每个人每日评审研发需求的规模。该数值越大，说明工作量越大。',
    'definition' => "所有研发需求个数求和\n评审者为某人\n评审时间为某日\n过滤已删除的研发需求\n过滤已删除产品的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的被指派的研发需求数',
    'alias'      => '被指派的研发需求数',
    'code'       => 'count_of_pending_story_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按人员统计的被指派的研发需求数表示每个人被指派的研发需求数量之和，反映了每个人员需要处理的研发需求数量的规模，该数值越大，说明需要投入越多的时间处理研发需求',
    'definition' => "所有研发需求个数求和\n指派给为某人\n过滤已删除的研发需求\n过滤状态为已关闭的研发需求\n过滤已删除产品下的研发需求\n过滤已删除的无产品项目下的研发需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的每日完成任务数',
    'alias'      => '完成任务数',
    'code'       => 'count_of_daily_finished_task_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'day',
    'desc'       => '按人员统计的日完成任务数表示每个人每日完成的任务数量之和。反映了每个人每日完成的任务规模。该数值越大，可能说明工作效率越高，任务完成速度越快。',
    'definition' => "某人某日完成的任务个数求和"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的待处理任务数',
    'alias'      => '待处理任务数',
    'code'       => 'count_of_assigned_task_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按人员统计的待处理任务数表示每个人待处理的任务数量之和。反映了每个人在需要处理的任务数量上的规模。该数值越大，说明需要投入越多的时间处理任务。',
    'definition' => "所有任务个数求和\n指派给为某人\n过滤已关闭的任务\n过滤已取消的任务\n过滤已删除的任务\n过滤已删除项目的任务\n过滤已删除执行的任务\n过滤多人任务中某人任务状态为已完成的任务\n过滤任务关联的执行和项目都为挂起状态时的任务"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的日解决Bug数',
    'alias'      => '解决Bug数',
    'code'       => 'count_of_daily_fixed_bug_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'day',
    'desc'       => '按人员统计的日解决Bug数表示每个人每日解决的Bug数量之和。反映了每个人每日解决Bug的规模。该数值越大，可能说明Bug的解决能力越强，工作效率越高。',
    'definition' => "所有Bug个数求和\nbug状态为已解决和已关闭\n解决者为某人\n解决日期为某日\n过滤已删除的bug\n过滤已删除产品的bug"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的待处理Bug数',
    'alias'      => '待处理Bug数',
    'code'       => 'count_of_assigned_bug_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按人员统计的待处理Bug数表示每个人待处理的Bug数量之和。反映了每个人需要处理的Bug数量上的规模。该数值越大，说明需要投入越多的时间解决Bug。',
    'definition' => "所有Bug个数求和\n指派给为某人\n过滤已删除的Bug\n过滤已删除产品的Bug"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的待处理用例数',
    'alias'      => '待处理用例数',
    'code'       => 'count_of_assigned_case_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'case',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按人员统计的待处理用例数表示每个人待处理的用例数量之和。反映了每个人需要处理的用例数量上的规模。该数值越大，说明需要投入越多的时间处理用例。',
    'definition' => "所有测试单中的用例个数求和（不去重）\n指派给某人\n过滤已删除的用例\n过滤已删除的测试单中的用例\n过滤已关闭的测试单中的用例\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的待处理反馈数',
    'alias'      => '待处理反馈数',
    'code'       => 'count_of_assigned_feedback_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'feedback',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按人员统计的待处理反馈数表示每个人待处理的反馈数量之和。反映了每个人需要处理的反馈数量上的规模。该数值越大，说明需要投入越多的时间处理反馈。',
    'definition' => "所有反馈个数求和\n指派给为某人\n过滤已删除的反馈\n过滤已删除产品的反馈"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的待评审反馈数',
    'alias'      => '待评审反馈数',
    'code'       => 'count_of_reviewing_feedback_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'feedback',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按人员统计的待评审反馈数表示每个人待评审的反馈数量之和。反映了每个人需要评审的反馈的规模。该数值越大，说明需要投入越多的时间评审反馈。',
    'definition' => "所有反馈个数求和\n状态为待评审\n指派给为某人\n过滤已删除的反馈\n过滤已删除产品的反馈"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的每日评审反馈数',
    'alias'      => '评审反馈数',
    'code'       => 'count_of_daily_review_feedback_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'feedback',
    'unit'       => 'count',
    'dateType'   => 'day',
    'desc'       => '按人员统计的日评审反馈数表示每个人每日评审的反馈数量之和。反映了每个人每日评审的反馈的规模。该数值越大，说明工作量越大。',
    'definition' => "所有反馈个数求和\n由谁评审为某人\n评审时间为某日\n过滤已删除的反馈\n过滤已删除产品的反馈"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的每周新增反馈数',
    'alias'      => '新增反馈数',
    'code'       => 'count_of_weekly_created_feedback_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'feedback',
    'unit'       => 'count',
    'dateType'   => 'week',
    'desc'       => '按产品统计的每周新增反馈数是指在一个周内收集到的用户反馈的数量。这个度量项可以帮助团队了解用户对产品的发展趋势和需求变化，并进行产品策略的调整和优化',
    'definition' => "产品中创建时间为某个周的反馈的个数求和\n过滤已删除的反馈\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的处理中的反馈数',
    'alias'      => '处理中反馈数',
    'code'       => 'count_of_doing_feedback_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'feedback',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的处理中的反馈数表示产品中状态为处理中的反馈数量之和。该数值越大，说明团队并行处理的反馈越多，可以帮助团队了解当前的工作负载情况',
    'definition' => "产品中所有反馈个数求和\n状态为处理中\n过滤已删除的反馈\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的已处理的反馈数',
    'alias'      => '已处理反馈数',
    'code'       => 'count_of_done_feedback_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'feedback',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的已处理的反馈数表示产品中状态为已处理的反馈数量之和。该数值越大，说明团队成员处理的反馈越多，有利于提高用户满意度',
    'definition' => "产品中所有反馈个数求和\n状态为已处理\n过滤已删除的反馈\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的待完善的反馈数',
    'alias'      => '待完善反馈数',
    'code'       => 'count_of_clarify_feedback_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'feedback',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的待完善的反馈数表示产品中状态为待完善的反馈数量之和。该数值越大，说明有较多的反馈信息不清晰或比较复杂。需要反馈者更多的澄清和解释',
    'definition' => "产品中所有反馈个数求和\n状态为待完善\n过滤已删除的反馈\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的待处理的反馈数',
    'alias'      => '待处理反馈数',
    'code'       => 'count_of_wait_feedback_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'feedback',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的待处理的反馈数表示产品中状态为待处理的反馈数量之和。该度量项可能暗示产品团队的反馈处理效率，待处理反馈数越多，可能会导致客户满意度降低',
    'definition' => "产品中所有反馈个数求和\n状态为待处理\n过滤已删除的反馈\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的追问中的反馈数',
    'alias'      => '追问中反馈数',
    'code'       => 'count_of_asked_feedback_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'feedback',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的追问中的反馈数表示产品中状态为追问中的反馈数量之和。该度量项可能暗示着反馈的复杂性或对处理方案的疑惑，追问中的反馈数量越多，可能意味着团队需要更多时间和资源来回复并解决这些问题',
    'definition' => "产品中所有反馈个数求和\n状态为追问中\n过滤已删除的反馈\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按产品统计的未关闭的反馈数',
    'alias'      => '未关闭反馈数',
    'code'       => 'count_of_unclosed_feedback_in_product',
    'purpose'    => 'scale',
    'scope'      => 'product',
    'object'     => 'feedback',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按产品统计的未关闭的反馈数表示产品中状态为未关闭的反馈数量之和。这个度量项可以一定程度反映产品团队响应用户反馈的效率和及时处理用户问题的能力',
    'definition' => "产品中所有反馈个数求和\n过滤状态为已关闭的反馈\n过滤已删除的反馈\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的已关闭用户需求数',
    'alias'      => '已关闭用户需求数',
    'code'       => 'count_of_closed_requirement_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'requirement',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的已关闭用户需求数是指项目中状态为已关闭的用户需求的数量，反映了项目团队在满足用户期望和需求方面的已完成任务和计划。已关闭用户需求数量的增加表示项目团队已经成功完成了一定数量的用户需求工作，并取得了一定的成果。',
    'definition' => "项目中用户需求个数求和\n过滤已删除的用户需求状态为已关闭\n 过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的用户需求总数',
    'alias'      => '用户需求总数',
    'code'       => 'count_of_requirement_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'requirement',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的用户需求总数是指项目中创建或关联的所有用户需求的数量，反映了项目的规模和复杂度，提供了关于用户需求管理、进度控制、资源规划、风险评估和质量控制的有用信息。',
    'definition' => "项目中用户需求个数求和\n过滤已删除的用户需求\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的被指派的工单数',
    'alias'      => '被指派的工单数',
    'code'       => 'count_of_assigned_ticket_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'ticket',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按人员统计的被指派的工单数表示每个人被指派的工单数量之和，反映了每个人员需要处理的工单数量的规模，该数值越大，说明需要处理的反馈任务越多',
    'definition' => "所有工单个数求和\n指派给为某人\n过滤已删除的工单\n过滤已删除产品的工单"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的被指派的QA数',
    'alias'      => '被指派的QA数',
    'code'       => 'count_of_assigned_qa_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'qa',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按人员统计的被指派的QA数表示每个人被指派的质量保证问题之和，反映了每个人员需要处理的质量保证问题的规模。该数值越大，说明需要处理的质量保证问题越多',
    'definition' => "所有待处理的QA个数求和（包含：待处理质量保证计划、待处理不符合项）\n指派给为某人\n质量保证计划状态为待检查、不符合项状态为待解决\n过滤已删除的质量保证计划和不符合项\n过滤已删除项目的质量保证计划和不符合项"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的被指派的风险数',
    'alias'      => '被指派的风险数',
    'code'       => 'count_of_assigned_risk_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'risk',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按人员统计的被指派的风险数表示每个人被指派的风险数量之和，反映了每个人员需要处理的风险数量的规模。该数值越大，说明需要投入越多的时间处理风险',
    'definition' => "所有风险个数求和\n指派给为某人\n过滤已删除的风险\n过滤已关闭的风险\n过滤已删除项目的风险"
);

$reviewissueMetrics = array();
$reviewissueMetrics['name']       = '按人员统计的被指派的评审意见数';
$reviewissueMetrics['alias']      = '被指派的评审意见数';
$reviewissueMetrics['code']       = 'count_of_assigned_reviewissue_in_user';
$reviewissueMetrics['purpose']    = 'scale';
$reviewissueMetrics['scope']      = 'user';
$reviewissueMetrics['object']     = 'reviewissue';
$reviewissueMetrics['unit']       = 'count';
$reviewissueMetrics['dateType']   = 'nodate';
$reviewissueMetrics['desc']       = '按人员统计的被指派的评审意见数表示每个人被指派的评审意见数量之和，反映了每个人员需要处理的评审意见数量的规模。该数值越大，说明需要投入越多的时间处理评审意见';
$reviewissueMetrics['definition'] = "所有评审意见个数求和\n指派给为某人\n过滤已删除的评审意见\n过滤已关闭的评审意见\n过滤已删除项目的评审意见";
$config->bi->builtin->metrics[]   = $reviewissueMetrics;

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的被指派的问题数',
    'alias'      => '被指派的问题数',
    'code'       => 'count_of_assigned_issue_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'issue',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按人员统计的被指派的问题数表示每个人待处理的问题数量之和。反映了每个人员需要处理的问题数量的规模。该数值越大，项目存在问题越多，需要投入越多的时间处理问题。',
    'definition' => "所有问题个数求和\n指派给为某人\n过滤已删除的问题\n过滤已删除项目的问题"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的被指派的需求池需求数',
    'alias'      => '待处理需求池需求数',
    'code'       => 'count_of_assigned_demand_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'demand',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按人员统计的被指派的需求池需求数表示每个人待处理的需求池需求数量之和。反映了每个人员需要处理的需求池需求数量的规模。该数值越大，说明需要投入越多的时间处理需求池需求',
    'definition' => "所有需求池需求个数求和\n指派给为某人\n过滤已删除的需求池需求\n过滤状态为已关闭的需求池需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的未关闭用户需求数',
    'alias'      => '未关闭用户需求数',
    'code'       => 'count_of_unclosed_requirement_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'requirement',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的未关闭用户需求数是指项目中尚未满足或处理的用户需求的数量，反映了项目团队在满足用户期望和需求方面的进行中任务和计划。未关闭用户需求数量的增加表示项目团队尚未完成的用户需求工作较多，需要进一步跟进和处理，以确保项目能够满足用户的期望',
    'definition' => "复用：\n按项目统计的用户需求总数\n按项目统计的已关闭用户需求数\n公式：\n按项目统计的未关闭用户需求数=按项目统计的用户需求总数-按项目统计的已关闭用户需求数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的已完成用户需求数',
    'alias'      => '已完成用户需求数',
    'code'       => 'count_of_finished_requirement_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'requirement',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的已完成用户需求数是指状态为已关闭且关闭原因为已完成的用户需求的数量。反映了项目团队在满足用户期望和需求方面的已经实现的任务和计划。已完成用户需求数量的增加表示项目团队已经成功完成了一定数量的用户需求工作，并取得了一定的成果',
    'definition' => "项目中用户需求的个数求和\n状态为已关闭\n关闭原因为已完成\n过滤已删除的用户需求\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的被指派的业务需求数',
    'alias'      => '被指派的业务需求数',
    'code'       => 'count_of_assigned_epic_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'epic',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按人员统计的被指派业务需求数表示每个人待处理的业务需求数量之和。反映了每个人员需要处理的业务需求数量的规模。该数值越大，说明需要投入越多的时间处理业务需求。',
    'definition' => "所有业务需求个数求和\n指派给为某人\n过滤已删除的业务需求\n过滤已删除产品的业务需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的被指派的用户需求数',
    'alias'      => '被指派的用户需求数',
    'code'       => 'count_of_assigned_requirement_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'requirement',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按人员统计的被指派用户需求数表示每个人待处理的用户需求数量之和。反映了每个人员需要处理的用户需求数量的规模。该数值越大，说明需要投入越多的时间处理用户需求。',
    'definition' => "所有用户需求个数求和\n指派给为某人\n过滤已删除的用户需求\n过滤已删除产品的用户需求"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的业务需求总数',
    'alias'      => '业务需求总数',
    'code'       => 'count_of_epic_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'epic',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的业务需求总数是指项目中创建或关联的所有业务需求的数量，反映了项目的规模和复杂度，提供了关于业务需求管理、进度控制、资源规划、风险评估和质量控制的有用信息',
    'definition' => "项目中业务需求个数求和\r\n过滤已删除的业务需求\r\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的已关闭业务需求数',
    'alias'      => '已关闭业务需求数',
    'code'       => 'count_of_closed_epic_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'epic',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的已关闭业务需求数是指项目中状态为已关闭的业务需求的数量，反映了项目团队在满足组织业务目标和需求方面的已经实现的任务和计划。已关闭业务需求数量的增加表示项目团队已经成功完成了一定数量的业务需求工作，并取得了一定的成果。',
    'definition' => "项目中业务需求个数求和\r\n过滤已删除的业务需求\r\n状态为已关闭\r\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的未关闭业务需求数',
    'alias'      => '未关闭业务需求数',
    'code'       => 'count_of_unclosed_epic_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'epic',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的未关闭业务需求数是指项目中尚未满足或处理的业务需求的数量，反映了项目团队在满足组织业务目标和需求方面的进行中任务和计划。未关闭业务需求数量的增加表示项目团队尚未完成的业务需求工作较多，需要进一步跟进和处理，以确保项目能够满足组织的业务目标',
    'definition' => "复用：\r\n按项目统计的业务需求总数\r\n按项目统计的已关闭业务需求数\r\n公式：\r\n按项目统计的未关闭业务需求数=按项目统计的业务需求总数-按项目统计的已关闭业务需求数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的已完成业务需求数',
    'alias'      => '已完成业务需求数',
    'code'       => 'count_of_finished_epic_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'epic',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的已完成业务需求数是指状态为已关闭且关闭原因为已完成的业务需求的数量。反映了项目团队在满足组织业务目标和需求方面的已经实现的任务和计划。已完成业务需求数量的增加表示项目团队已经成功完成了一定数量的业务需求工作，并取得了一定的成果',
    'definition' => "项目中业务需求的个数求和\r\n状态为已关闭\r\n关闭原因为已完成\r\n过滤已删除的业务需求\r\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按人员统计的被指派的QA数',
    'alias'      => '被指派的QA数',
    'code'       => 'count_of_assigned_qa_in_user',
    'purpose'    => 'scale',
    'scope'      => 'user',
    'object'     => 'qa',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按人员统计的被指派的QA数表示每个人被指派的质量保证问题之和。反映了每个人员需要处理的质量保证问题的规模。该数值越大，说明需要处理的质量保证问题越多',
    'definition' => "所有待处理的QA个数求和（包含：待处理质量保证计划、待处理不符合项）\n指派给为某人\n质量保证计划状态为待检查、不符合项状态为待解决\n过滤已删除的质量保证计划和不符合项\n过滤已删除项目的质量保证计划和不符合项"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的研发需求规模数',
    'alias'      => '研发需求规模数',
    'code'       => 'scale_of_story_in_execution',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的研发需求规模数表示执行中所有研发需求的总规模，这个度量项可以反映执行周期内团队需要进行研发的工作规模，可以用于评估执行团队的工作负载和研发成果。',
    'definition' => "执行中所有研发需求的规模数求和\n过滤已删除的研发需求\n过滤已删除的执行\n过滤已删除的项目\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的测试任务消耗工时数',
    'alias'      => '测试任务消耗工时数',
    'code'       => 'consume_of_test_task_in_execution',
    'purpose'    => 'hour',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的测试任务消耗工时数是指任务类型为测试时已消耗的工时总和，该度量项反映了测试任务的资源使用情况，可以帮助团队掌握执行的测试成本。',
    'definition' => "执行中满足以下条件的任务消耗工时数求和\n任务类型为测试\n过滤已删除的任务\n过滤父任务\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的开发任务消耗工时数',
    'alias'      => '开发任务消耗工时数',
    'code'       => 'consume_of_devel_task_in_execution',
    'purpose'    => 'hour',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的开发任务消耗工时数是指任务类型为开发时已经消耗的工时总和，该度量项反映了开发任务的资源使用情况，可以帮助团队掌握执行的开发成本。',
    'definition' => "执行中满足以下条件的任务消耗工时数求和\n任务类型为开发\n过滤已删除的任务\n过滤父任务\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的来源Bug的任务消耗工时数',
    'alias'      => '来源Bug的任务消耗工时数',
    'code'       => 'consume_of_frombug_task_in_execution',
    'purpose'    => 'hour',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的来源Bug的任务消耗工时数是指执行中Bug转任务消耗的工时总和。该度量项反映了任务来源为Bug的资源使用情况，可以帮助团队识别缺陷管理中存在的问题。',
    'definition' => "执行中满足以下条件的任务消耗工时数求和\n任务来源为Bug\n过滤已删除的任务\n过滤父任务\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的开发人员可用工时',
    'alias'      => '开发人员可用工时数',
    'code'       => 'hour_of_developer_available_in_execution',
    'purpose'    => 'hour',
    'scope'      => 'execution',
    'object'     => 'user',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的开发人员可用工时是指执行团队中角色为研发的可用工时之和。该度量项反映了团队中开发人员能够投入在本迭代的时间，有助于计算执行团队的工作负载。',
    'definition' => "执行团队成员每日可用工时*可用工日\n人员职位为研发\n过滤已删除的用户\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的测试用例数',
    'alias'      => '测试用例数',
    'code'       => 'count_of_case_in_execution',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'case',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的测试用例数是指执行下的测试用例个数的求和，可以帮助团队评估需求测试用例的覆盖程度。',
    'definition' => "执行中满足以下条件的测试用例个数的求和\n执行用例列表中的用例\n过滤已删除的用例\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的新增有效Bug总数',
    'alias'      => '新增有效Bug总数',
    'code'       => 'count_of_effective_bug_in_execution',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的新增有效Bug总数是指在执行中发现的有效Bug的数量。这个度量项反映了执行的质量情况。新增有效Bug数越多可能代表执行的代码质量存在的问题越多，需要进行进一步的解决和改进。',
    'definition' => "执行中新增Bug个数求和\n解决方案为已解决，延期处理和不予解决或状态为激活\n过滤已删除的Bug\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的执行关闭时已交付的研发需求规模数',
    'alias'      => '执行关闭时已交付研发需求规模数',
    'code'       => 'scale_of_delivered_story_in_execution_when_closing',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'story',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的执行关闭时已交付研发需求规模数表示执行关闭时需求阶段为已发布或状态为已关闭且关闭原因为已完成的研发需求的规模。该度量项反映了执行关闭时能够交付给用户的研发需求的规模，可以用于评估执行团队的研发需求交付能力。',
    'definition' => "执行关闭时，满足以下条件的执行中研发需求规模数求和，条件是：所处阶段为已发布或关闭原因为已完成\n过滤已删除的研发需求\n过滤已删除的执行\n过滤已删除的项目\n过滤已删除的产品\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的截止执行开始当天的研发需求数',
    'alias'      => '截止执行开始当天的研发需求数',
    'code'       => 'count_of_story_in_execution_when_starting',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的截止执行开始当天的研发需求数表示执行开始当天已关联进执行的研发需求的数量。该度量项反映了本期执行计划完成的需求数量，可以用于评估执行团队的工作负载。',
    'definition' => "截止到执行开始当天的23:59分的研发需求个数求和，过滤已删除的研发需求\n过滤已删除的执行\n过滤已删除的项目\n过滤已删除的产品\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的截止执行开始当天的研发需求规模数',
    'alias'      => '截止执行开始当天的研发需求规模数',
    'code'       => 'scale_of_story_in_execution_when_starting',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'story',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的截止执行开始当天的研发需求规模数表示执行开始时已关联进执行的研发需求的规模数。该度量项反映了本期执行计划完成的需求规模，可以用于评估执行团队的工作负载。',
    'definition' => "截止到执行开始当天的23:59分的研发需求规模数求和，过滤已删除的研发需求\n过滤已删除的执行\n过滤已删除的项目\n过滤已删除的产品\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的来源Bug的任务消耗工时占比',
    'alias'      => '来源Bug的任务消耗工时占比',
    'code'       => 'consume_rate_of_frombug_task_in_execution',
    'purpose'    => 'rate',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的来源Bug的任务消耗工时占比是指执行中Bug转任务消耗的工时与执行中所有任务消耗工时的比值。该度量项反映了任务来源为Bug的资源使用情况，可以帮助团队识别缺陷管理中存在的问题，例如历史遗留缺陷过多导致执行一直在补旧账。',
    'definition' => "复用：按执行统计的来源Bug的任务消耗工时数、按执行统计的任务消耗工时数；\n公式：按执行统计的来源Bug的任务消耗工时数/按执行统计的任务消耗工时数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的测试用例预期条目数',
    'alias'      => '测试用例预期条目数',
    'code'       => 'count_of_case_expect_in_execution',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'case',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的测试用例预期条目数是指关联进执行的所有用例的预期条目数之和，可以用于评估测试用例的细致程度，可以帮助团队评估测试的深度和需求的复杂性。',
    'definition' => "执行中满足以下条件的用例预期条目的求和\n执行下用例列表中的用例数\n过滤已删除的用例\n过滤已删除的执行\n过滤已删除的项目"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的执行关闭时已交付研发需求数',
    'alias'      => '执行关闭时已交付研发需求数',
    'code'       => 'count_of_delivered_story_in_execution_when_closing',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的执行关闭时已交付研发需求数表示执行关闭时需求阶段为已发布或状态为已关闭且关闭原因为已完成的研发需求的数量。该度量项反映了执行关闭时能够交付给用户的研发需求的数量，可以用于评估执行团队的研发需求交付能力。',
    'definition' => "执行关闭时，满足以下条件的执行中研发需求个数求和\n所处阶段为已发布或关闭原因为已完成\n过滤已删除的研发需求\n过滤已删除的执行\n过滤已删除的项目\n过滤已删除的产品"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的执行关闭时研发需求按计划完成率',
    'alias'      => '执行关闭时研发需求按计划完成率',
    'code'       => 'rate_of_planned_developed_story_in_execution_when_closing',
    'purpose'    => 'rate',
    'scope'      => 'execution',
    'object'     => 'execution',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的研发需求按计划完成率是指执行关闭时已交付的研发需求与执行开始时计划的研发需求数的比率。该度量项反映了团队能否按期完成规划的需求，可以帮助团队识别研发中存在的潜在问题。',
    'definition' => "复用： 按执行统计的执行关闭时已交付的研发需求数、按执行统计的截止执行开始当天的研发需求数；公式：按执行统计的执行关闭时已交付的研发需求数/按执行统计的截止执行开始当天的研发需求数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的执行关闭时测试任务完成率',
    'alias'      => '执行关闭时测试任务完成率',
    'code'       => 'rate_of_finished_test_task_in_execution_when_closing',
    'purpose'    => 'rate',
    'scope'      => 'execution',
    'object'     => 'execution',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的测试任务按计划完成率是指执行时已完成的测试任务数与执行开始时计划的测试任务数的比率。该度量项反映了团队能否按期完成规划的测试任务，可以帮助团队识别执行中存在的潜在问题，例如测试介入时间晚等。',
    'definition' => "复用：按执行统计的执行关闭时已完成的测试任务数、按执行统计的测试任务数\n公式：按执行统计的执行关闭时已完成的测试任务数/按执行统计的测试任务数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的执行关闭时执行开发效率',
    'alias'      => '执行开发效率',
    'code'       => 'devel_efficiency_in_execution_when_closing',
    'purpose'    => 'rate',
    'scope'      => 'execution',
    'object'     => 'execution',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的执行开发效率是指执行交付研发需求规模数与执行所有任务消耗工时的比率。该度量项反映了执行的开发速度，可以帮助团队识别潜在问题并采取改进措施提高研发效率。',
    'definition' => "复用：按执行统计的任务消耗工时数、按执行统计的执行关闭时已交付的研发需求规模数；\n公式：按执行统计的执行关闭时已交付的研发需求规模数/按执行统计的任务消耗工时数"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的有效Bug数',
    'alias'      => '有效Bug数',
    'code'       => 'count_of_effective_bug_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的有效Bug数是指项目中真正具有影响和价值的Bug数量。有效Bug通常是指导致项目不正常运行或影响用户体验的Bug。统计有效Bug数可以帮助评估项目的稳定性和质量，也可以评估测试人员之间的协作或对项目的了解程度。',
    'definition' => "项目中所有Bug个数求和,解决方案为已解决、延期处理或状态为激活;\n 过滤已删除的Bug\n 过滤已删除的项目\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的已修复Bug数',
    'alias'      => '已修复Bug数',
    'code'       => 'count_of_fixed_bug_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的已修复Bug数是指解决方案为已解决并且状态为已关闭的Bug数量。这个度量项反映了项目解决的问题数量。已修复Bug数的可以评估开发团队在Bug解决方面的工作效率。',
    'definition' => "项目中Bug的个数求和\n 解决方案为已解决\n 状态为已关闭\n 过滤已删除的Bug\n 过滤已删除的项目\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的每日新增Bug数',
    'alias'      => '新增Bug数',
    'code'       => 'count_of_daily_created_bug_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'day',
    'desc'       => '按项目统计的每日新增Bug数是指在每天的项目开发过程中新发现并记录的Bug数量。该度量项可以体现项目开发过程中Bug的发现速度和趋势，较高的新增Bug数可能意味着存在较多的问题需要解决，同时也可以帮助识别项目开发过程中的瓶颈和潜在的质量风险。',
    'definition' => "项目中Bug数求和\n 创建时间为某日\n 过滤已删除的Bug\n 过滤已删除的项目\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的每日解决Bug数',
    'alias'      => '解决Bug数',
    'code'       => 'count_of_daily_resolved_bug_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'day',
    'desc'       => '按项目统计的每日解决Bug数是指项目每日解决的Bug的数量。该度量项可以帮助我们了解开发团队解决Bug的速度和效率。',
    'definition' => "项目中Bug数求和\n 解决日期为某日\n 过滤已删除的Bug\n 过滤已删除的项目\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的每日关闭Bug数',
    'alias'      => '关闭Bug数',
    'code'       => 'count_of_daily_closed_bug_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'day',
    'desc'       => '按项目统计的每日关闭Bug数是指每天在项目中每日关闭的Bug的数量。该度量项可以帮助我们了解开发团队对已解决的Bug进行确认与关闭的速度和效率，通过对比不同时间段的关闭Bug数，可以评估开发团队的协作和问题处理能力。',
    'definition' => "项目中Bug数求和\n 关闭时间为某日\n 过滤已删除的Bug\n 过滤已删除的项目\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的Bug修复率',
    'alias'      => 'Bug修复率',
    'code'       => 'rate_of_fixed_bug_in_project',
    'purpose'    => 'rate',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的Bug修复率是指按项目统计的修复Bug数相对于按项目统计的有效Bug数的比例。该度量项可以帮助我们了解开发团队对Bug修复的效率和质量，高的修复率可能说明Bug得到及时解决，项目质量得到有效保障。',
    'definition' => "复用：按项目统计的修复Bug数、按项目统计的有效Bug数\n 公式：按项目统计的Bug修复率=按项目统计的修复Bug数/按项目统计的有效Bug数\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的严重程度为1级的Bug数',
    'alias'      => '严重程度为1级的Bug数',
    'code'       => 'count_of_severity_1_bug_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的严重程度为1级的Bug数是指在项目开发过程中发现的、对项目功能或性能产生重大影响的Bug数量。这些Bug可能会导致系统崩溃、功能无法正常运行、数据丢失等严重问题。统计这些Bug的数量可以帮助评估项目的稳定性和可靠性。',
    'definition' => "项目中Bug的个数求和\n 严重程度为1级\n 过滤已删除的Bug\n 过滤已删除的项目\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的严重程度为2级的Bug数',
    'alias'      => '严重程度为2级的Bug数',
    'code'       => 'count_of_severity_2_bug_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的严重程度为2级的Bug数是指在项目开发过程中发现的、对项目功能或性能产生重大影响的Bug数量。这些Bug可能会导致系统崩溃、功能无法正常运行、数据丢失等严重问题。统计这些Bug的数量可以帮助评估项目的稳定性和可靠性。',
    'definition' => "项目中Bug的个数求和\n 严重程度为2级\n 过滤已删除的Bug\n 过滤已删除的项目\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的严重程度为1、2级的Bug数',
    'alias'      => '严重程度为1、2级的Bug数',
    'code'       => 'count_of_severe_bug_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按项目统计的严重程度为1、2级的Bug数是指在项目开发过程中发现的严重程度为1级和2级的Bug数量的总和。统计这些Bug的数量可以评估项目开发过程的质量和稳定性，同时也关注影响用户体验和功能完整性的问题',
    'definition' => "复用： 按项目统计的严重程度为1级的Bug数、按项目统计的严重程度为2级的Bug数。公式： 按项目统计的严重程度为1、2级的Bug数=按项目统计的严重程度为1级的Bug数+按项目统计的严重程度为2级的Bug数\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的年度新增Bug数',
    'alias'      => '新增Bug数',
    'code'       => 'count_of_annual_created_bug_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按项目统计的年度新增Bug数是指项目在某年度新发现的Bug数量。这个度量项反映了项目在某年度出现的新问题数量。年度新增Bug数越多可能意味着质量控制存在问题，需要及时进行处理和改进。',
    'definition' => "项目中Bug的个数求和\n 创建时间为某年\n 过滤已删除的Bug\n 过滤已删除的项目\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的月度新增Bug数',
    'alias'      => '新增Bug数',
    'code'       => 'count_of_monthly_created_bug_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按项目统计的月度新增Bug数是指在某年度新发现的Bug数量。这个度量项反映了系统或项目在某月度出现的新问题数量。月度新增Bug数的增加可能意味着质量控制存在问题，需要及时进行处理和改进。',
    'definition' => "项目中创建时间在某年某月的Bug个数求和\n过滤已删除的Bug\n过滤已删除的项目\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的月度关闭Bug数',
    'alias'      => '关闭Bug数',
    'code'       => 'count_of_monthly_closed_bug_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按项目统计的月度关闭Bug数是指在某月度关闭的Bug数量。这个度量项反映了产品开发过程中每月被确认并关闭的Bug的数量。该度量项可以帮助我们了解开发团队对Bug进行确认与关闭的速度和效率。',
    'definition' => "项目中关闭时间在某年某月的Bug个数求和，过滤已删除的Bug，过滤已删除的项目。",
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的月度修复Bug数',
    'alias'      => '修复Bug数',
    'code'       => 'count_of_monthly_fixed_bug_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '按项目统计的月度修复Bug数是指每月在项目开发过程中被解决并关闭的Bug的数量。该度量项可以帮助我们了解开发团队解决Bug的速度和效率。',
    'definition' => "项目中Bug的个数求和\n关闭时间为某年某月\n解决方案为已解决\n过滤已删除的Bug\n过滤已删除的项目\n",
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的年度修复Bug数',
    'alias'      => '修复Bug数',
    'code'       => 'count_of_annual_fixed_bug_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按项目统计的年度修复Bug数是指在某年度解决并关闭的Bug数量。这个度量项反映了项目在某年度解决的问题数量。年度修复Bug数越多可能说明开发团队在Bug解决方面的工作效率较高。',
    'definition' => "项目中Bug的个数求和\n关闭时间为某年\n解决方案为已解决\n过滤已删除的Bug\n过滤已删除的项目\n",
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按项目统计的年度新增有效Bug数',
    'alias'      => '新增有效Bug数',
    'code'       => 'count_of_annual_created_effective_bug_in_project',
    'purpose'    => 'scale',
    'scope'      => 'project',
    'object'     => 'bug',
    'unit'       => 'count',
    'dateType'   => 'year',
    'desc'       => '按项目统计的年度新增有效Bug数是指项目在某年度新发现的真正具有影响和价值的Bug数量。有效Bug通常是指导致项目不正常运行或影响用户体验的Bug。统计有效Bug数可以帮助评估项目的稳定性和质量也可以评估测试人员之前的协作或对项目的了解程度。',
    'definition' => "项目中Bug个数求和\n创建时间为某年\n解决方案为已解决和延期处理或者状态为激活\n过滤已删除的Bug\n过滤已删除的项目\n",
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计合并请求通过率',
    'alias'      => '系统合并请求通过率',
    'code'       => 'rate_of_merged_mr',
    'purpose'    => 'qc',
    'scope'      => 'system',
    'object'     => 'codebase',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的合并请求通过率是指已合并合并请求/总的合并请求数。通过统计在一定时间范围内提交的合并请求中合并的比例，团队能够有效监控其代码审查过程的健康状况，并及时识别潜在的改进空间。',
    'definition' => "系统已合并合并请求/总的合并请求数\n不统计已删除的合并请求\n不统计已删除代码库里的合并请求\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计主机总数',
    'alias'      => '主机总数',
    'code'       => 'count_of_host',
    'purpose'    => 'scale',
    'scope'      => 'system',
    'object'     => 'host',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的主机总数是指在禅道中的全部主机总数。',
    'definition' => "所有主机的个数求和"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按月统计的人均研发效能',
    'alias'      => '人均研发效能',
    'code'       => 'avg_of_dev_efficiency',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'user',
    'unit'       => 'percentage',
    'dateType'   => 'month',
    'desc'       => '指团队成员在单位时间内完成的平均需求规模。它用于评估团队的生产力、资源利用效率以及工作负荷的合理性。',
    'definition' => "按月统计的人均研发效能 = 当月发布的研发需求的规模总数 / 当月禅道系统中的总人数\n当月发布的研发需求，是统计当月阶段状态为已发布、已关闭关闭原因为已完成的研发需求，过滤已删除的研发需求，次月过滤已统计过的研发需求。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按月统计的已发布研发需求平均交付周期',
    'alias'      => '已发布研发需求平均交付周期',
    'code'       => 'avg_of_release_story_delivery_time',
    'purpose'    => 'time',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'day',
    'dateType'   => 'month',
    'desc'       => '指从需求被提出（创建）到最终交付给客户或上线所需的平均时间，反映了团队或组织在需求实现过程中的效率。它可以用于评估需求的响应速度和交付速度。',
    'definition' => "按月统计的已发布研发需求平均交付周期 = sum ( 当月发布的研发需求的发布时间 - 当月发布的研发需求的创建时间 ) / 当月发布的研发需求总数\n当月发布的研发需求，是统计当月阶段状态为已发布、已关闭关闭原因为已完成的研发需求，过滤已删除的研发需求，次月过滤已统计过的研发需求。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按月统计的已发布研发需求平均缺陷密度',
    'alias'      => '已发布研发需求平均缺陷密度',
    'code'       => 'avg_of_release_story_defect_density',
    'purpose'    => 'qc',
    'scope'      => 'system',
    'object'     => 'story',
    'unit'       => 'count',
    'dateType'   => 'month',
    'desc'       => '指在测试阶段发现的缺陷数量与需求规模的比值。它反映了代码或系统的质量状况，用于评估开发过程的质量以及测试的有效性。',
    'definition' => "按月统计的已发布研发需求平均缺陷密度 = sum ( 当月发布的研发需求关联的Bug总数 ) / 当月发布的研发需求的规模总数\n当月发布的研发需求，是统计当月阶段状态为已发布、已关闭关闭原因为已完成的研发需求，过滤已删除的研发需求，次月过滤已统计过的研发需求。\n当月发布的研发需求关联的Bug总数，是统计当月发布的每个研发需求关联Bug，过滤已删除的bug。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的执行关闭时开发任务完成率',
    'alias'      => '执行关闭时开发任务完成率',
    'code'       => 'rate_of_finished_dev_task_in_execution_when_closing',
    'purpose'    => 'rate',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的开发任务按计划完成率是指执行时已完成的开发任务数与执行开始时计划的开发任务数的比率。该度量项反映了团队能否按期完成规划的开发任务，可以帮助团队识别执行中存在的潜在问题。',
    'definition' => "复用：按执行统计的执行关闭时已完成的开发任务数、按执行统计的开发任务数，公式：按执行统计的执行关闭时已完成的开发任务数÷按执行统计的开发任务数。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的开发任务数',
    'alias'      => '开发任务数',
    'code'       => 'count_of_dev_task_in_execution',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的开发任务数是指执行中任务类型为开发的任务数求和。该度量项反映了执行中开发的工作量，可以帮助团队进行开发资源调配。',
    'definition' => "执行中满足以下条件的任务个数求和，条件是：任务类型为开发，过滤已删除的任务，过滤已删除的执行，过滤已删除的项目。"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按执行统计的执行关闭时已完成的开发任务数',
    'alias'      => '执行关闭时已完成的开发任务数',
    'code'       => 'count_of_finished_dev_task_in_execution_when_closing',
    'purpose'    => 'scale',
    'scope'      => 'execution',
    'object'     => 'task',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按执行统计的执行关闭时已完成开发任务数表示执行关闭时任务状态为已完成的开发任务个数求和。该度量项反映了执行关闭时开发人员完成的开发任务个数，可以评估执行中开发人员的实际工作量和开发效率。',
    'definition' => "执行关闭时执行中满足以下条件的开发任务个数求和，条件是：任务类型为开发，状态为已完成或已关闭且关闭原因为已完成，过滤已删除的任务，过滤已删除的执行，过滤已删除的项目。"
);
