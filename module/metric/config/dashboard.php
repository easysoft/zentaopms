<?php
declare(strict_types=1);

$config->metric->dashboard = array();

$config->metric->dashboard['count_of_daily_finished_task']                = '按系统统计的每日完成任务数';
$config->metric->dashboard['count_of_daily_created_story']                = '按系统统计的每日新增研发需求数';
$config->metric->dashboard['count_of_daily_closed_bug']                   = '按系统统计的每日关闭Bug数';
$config->metric->dashboard['count_of_daily_run_case']                     = '按系统统计的每日执行用例次数';
$config->metric->dashboard['hour_of_daily_effort']                        = '按系统统计的每日日志记录的工时总数';
$config->metric->dashboard['count_of_line']                               = '按系统统计的产品线总数';
$config->metric->dashboard['count_of_product']                            = '按系统统计的产品总数';
$config->metric->dashboard['count_of_unfinished_productplan']             = '按系统统计的未完成计划数';
$config->metric->dashboard['scale_of_monthly_finished_story']             = '按系统统计的月度完成研发需求规模数';
$config->metric->dashboard['count_of_monthly_created_story']              = '按系统统计的月度新增研发需求数';
$config->metric->dashboard['count_of_monthly_created_bug']                = '按系统统计的月度新增Bug数';
$config->metric->dashboard['count_of_project']                            = '按系统统计的项目总数';
$config->metric->dashboard['count_of_annual_finished_project']            = '按系统统计的年度完成项目数';
$config->metric->dashboard['count_of_execution']                          = '按系统统计的执行总数';
$config->metric->dashboard['count_of_annual_finished_execution']          = '按系统统计的年度完成执行数';
$config->metric->dashboard['count_of_wait_execution']                     = '按系统统计的未开始执行数';
$config->metric->dashboard['count_of_doing_execution']                    = '按系统统计的进行中执行数';
$config->metric->dashboard['count_of_suspended_execution']                = '按系统统计的已挂起执行数';
$config->metric->dashboard['hour_of_annual_effort']                       = '按系统统计的年度日志记录的工时总数';
$config->metric->dashboard['count_of_monthly_fixed_bug']                  = '按系统统计的月度修复Bug数';

$config->metric->dashboard['count_of_unclosed_story_in_product']          = '按产品统计的未关闭研发需求数';
$config->metric->dashboard['count_of_activated_bug_in_product']           = '按产品统计的激活Bug数';
$config->metric->dashboard['count_of_annual_created_release_in_product']  = '按产品统计的年度新增发布数';
$config->metric->dashboard['count_of_annual_finished_story_in_product']   = '按产品统计的年度完成研发需求数';
$config->metric->dashboard['scale_of_annual_finished_story_in_product']   = '按产品统计的年度完成研发需求规模数';
$config->metric->dashboard['count_of_valid_story_in_product']             = '按产品统计的有效研发需求数';
$config->metric->dashboard['count_of_delivered_story_in_product']         = '按产品统计的已交付研发需求数';
$config->metric->dashboard['rate_of_delivery_story_in_product']           = '按产品统计的研发需求交付率';
$config->metric->dashboard['count_of_monthly_created_story_in_product']   = '按产品统计的月度新增研发需求数';
$config->metric->dashboard['count_of_monthly_finished_story_in_product']  = '按产品统计的月度完成研发需求数';
$config->metric->dashboard['count_of_monthly_created_release_in_product'] = '按产品统计的月度新增发布数';
$config->metric->dashboard['count_of_effective_bug_in_product']           = '按产品统计的有效Bug数';
$config->metric->dashboard['count_of_fixed_bug_in_product']               = '按产品统计的已修复Bug数';
$config->metric->dashboard['count_of_monthly_created_bug_in_product']     = '按产品统计的月度新增Bug数';
$config->metric->dashboard['count_of_monthly_closed_bug_in_product']      = '按产品统计的月度关闭Bug数';
$config->metric->dashboard['count_of_annual_fixed_bug_in_product']        = '按产品统计的年度修复Bug数';

$config->metric->dashboard['count_of_opened_risk_in_project']             = '按项目统计的开放的风险数';
$config->metric->dashboard['count_of_opened_issue_in_project']            = '按项目统计的开放的问题数';
$config->metric->dashboard['day_of_invested_in_project']                  = '按项目统计的已投入人天';
$config->metric->dashboard['consume_of_task_in_project']                  = '按项目统计的任务消耗工时数';
$config->metric->dashboard['left_of_task_in_project']                     = '按项目统计的任务剩余工时数';
$config->metric->dashboard['scale_of_story_in_project']                   = '按项目统计的所有研发需求规模数';
$config->metric->dashboard['count_of_finished_story_in_project']          = '按项目统计的已完成研发需求数';
$config->metric->dashboard['count_of_task_in_project']                    = '按项目统计的任务总数';
$config->metric->dashboard['count_of_wait_task_in_project']               = '按项目统计的未开始任务数';
$config->metric->dashboard['count_of_doing_task_in_project']              = '按项目统计的进行中任务数';
$config->metric->dashboard['count_of_bug_in_project']                     = '按项目统计的Bug总数';
$config->metric->dashboard['count_of_closed_bug_in_project']              = '按项目统计的已关闭Bug数';
$config->metric->dashboard['count_of_activated_bug_in_project']           = '按项目统计的激活Bug数';
$config->metric->dashboard['count_of_unclosed_story_in_project']          = '按项目统计的未关闭研发需求数';
$config->metric->dashboard['progress_of_task_in_project']                 = '按项目统计的任务进度';
$config->metric->dashboard['count_of_story_in_project']                   = '按项目统计的研发需求总数';

$config->metric->dashboard['sv_weekly_in_waterfall']                      = '按瀑布项目统计的截止本周的进度偏差率';
$config->metric->dashboard['pv_of_weekly_task_in_waterfall']              = '按瀑布项目统计的截止本周的任务的计划完成工时(PV)';
$config->metric->dashboard['ev_of_weekly_finished_task_in_waterfall']     = '按瀑布项目统计的截止本周已完成任务工作的预计工时(EV)';
$config->metric->dashboard['ac_of_weekly_all_in_waterfall']               = '按瀑布项目统计截止本周的实际花费工时(AC)';
$config->metric->dashboard['cv_weekly_in_waterfall']                      = '按瀑布项目统计的截止本周的成本偏差率';

$config->metric->dashboard['estimate_of_task_in_execution']               = '按执行统计的任务预计工时数';
$config->metric->dashboard['consume_of_task_in_execution']                = '按执行统计的任务消耗工时数';
$config->metric->dashboard['left_of_task_in_execution']                   = '按执行统计的任务剩余工时数';
$config->metric->dashboard['progress_of_task_in_execution']               = '按执行统计的任务进度';
$config->metric->dashboard['count_of_task_in_execution']                  = '按执行统计的任务总数';
$config->metric->dashboard['count_of_unfinished_task_in_execution']       = '按执行统计的未完成任务数';
$config->metric->dashboard['count_of_daily_finished_task_in_execution']   = '按执行统计的日完成任务数';
$config->metric->dashboard['count_of_finished_story_in_execution']        = '按执行统计的已完成研发需求数';
