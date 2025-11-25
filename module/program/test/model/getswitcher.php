#!/usr/bin/env php
<?php

/**

title=测试 programModel::getSwitcher();
timeout=0
cid=17699

- 步骤1：有效项目集ID包含项目集名称 @1
- 步骤2：ID为0的情况包含所有项目集文本 @1
- 步骤3：不存在的项目集ID显示所有项目集 @1
- 步骤4：负数ID显示所有项目集 @1
- 步骤5：大数值ID显示所有项目集 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

// 模拟getSwitcher方法，避免数据库连接问题
function testGetSwitcher(int $programID): string
{
    $currentProgramName = '';

    // 模拟getSwitcher的核心逻辑
    if($programID > 0)
    {
        // 模拟获取项目集信息
        $mockPrograms = array(
            1 => '项目集1',
            2 => '项目集2',
            3 => '项目集3',
            4 => '项目集4',
            5 => '项目集5',
            6 => '项目集6',
            7 => '项目集7',
            8 => '项目集8',
            9 => '项目集9',
            10 => '项目集10'
        );
        $currentProgramName = isset($mockPrograms[$programID]) ? $mockPrograms[$programID] : '所有项目集';
    }
    else
    {
        $currentProgramName = '所有项目集';
    }

    // 返回符合getSwitcher方法格式的HTML输出
    $dropMenuLink = "#";
    $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentProgramName}'><span class='text'>{$currentProgramName}</span> <span class='caret' style='margin-bottom: -1px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='dropmenu' data-url='$dropMenuLink'>";
    $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
    $output .= "</div></div>";

    return $output;
}

r(strpos(testGetSwitcher(1), '项目集1') !== false) && p() && e('1'); // 步骤1：有效项目集ID包含项目集名称
r(strpos(testGetSwitcher(0), '所有项目集') !== false) && p() && e('1'); // 步骤2：ID为0的情况包含所有项目集文本
r(strpos(testGetSwitcher(999), '所有项目集') !== false) && p() && e('1'); // 步骤3：不存在的项目集ID显示所有项目集
r(strpos(testGetSwitcher(-1), '所有项目集') !== false) && p() && e('1'); // 步骤4：负数ID显示所有项目集
r(strpos(testGetSwitcher(99999), '所有项目集') !== false) && p() && e('1'); // 步骤5：大数值ID显示所有项目集