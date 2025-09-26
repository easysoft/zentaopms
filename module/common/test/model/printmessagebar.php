#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printMessageBar();
timeout=0
cid=0

- 步骤1：消息功能关闭时不输出任何内容 @0
- 步骤2：消息功能开启且无未读消息时输出基础HTML @<li id='messageDropdown' class='relative'><a class='dropdown-toggle' id='messageBar' data-fetcher='/message-ajaxGetDropMenuForOld.html' onclick='fetchMessage()'><i class='icon icon-bell'></i></a><div class='dropdown-menu messageDropdownBox absolute' style='padding:0;left:-320px;'><div id='dropdownMessageMenu' class='not-clear-menu'></div></div></li>
- 步骤3：消息功能开启且有未读消息时显示消息数量 @<li id='messageDropdown' class='relative'><a class='dropdown-toggle' id='messageBar' data-fetcher='/message-ajaxGetDropMenuForOld.html' onclick='fetchMessage()'><i class='icon icon-bell'></i><span class='label label-dot danger absolute rounded-sm' style='top:-3px; right:-5px; aspect-ratio:0; padding:2px'>5</span></a><div class='dropdown-menu messageDropdownBox absolute' style='padding:0;left:-320px;'><div id='dropdownMessageMenu' class='not-clear-menu'></div></div></li>
- 步骤4：未读消息数量超过99时显示99+ @<li id='messageDropdown' class='relative'><a class='dropdown-toggle' id='messageBar' data-fetcher='/message-ajaxGetDropMenuForOld.html' onclick='fetchMessage()'><i class='icon icon-bell'></i><span class='label label-dot danger absolute rounded-sm' style='top:-3px; right:-10px; aspect-ratio:0; padding:2px'>99+</span></a><div class='dropdown-menu messageDropdownBox absolute' style='padding:0;left:-320px;'><div id='dropdownMessageMenu' class='not-clear-menu'></div></div></li>
- 步骤5：设置不显示计数时只显示红点 @<li id='messageDropdown' class='relative'><a class='dropdown-toggle' id='messageBar' data-fetcher='/message-ajaxGetDropMenuForOld.html' onclick='fetchMessage()'><i class='icon icon-bell'></i><span class='label label-dot danger absolute' style='top:-2px; right:-2px; aspect-ratio:1 / 1; padding:2px; width:5px; height:5px'></span></a><div class='dropdown-menu messageDropdownBox absolute' style='padding:0;left:-320px;'><div id='dropdownMessageMenu' class='not-clear-menu'></div></div></li>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

$commonTest = new commonTest();

r($commonTest->printMessageBarTest('turnoff')) && p() && e(0); // 步骤1：消息功能关闭时不输出任何内容
r($commonTest->printMessageBarTest('no_unread')) && p() && e("<li id='messageDropdown' class='relative'><a class='dropdown-toggle' id='messageBar' data-fetcher='/message-ajaxGetDropMenuForOld.html' onclick='fetchMessage()'><i class='icon icon-bell'></i></a><div class='dropdown-menu messageDropdownBox absolute' style='padding:0;left:-320px;'><div id='dropdownMessageMenu' class='not-clear-menu'></div></div></li>"); // 步骤2：消息功能开启且无未读消息时输出基础HTML
r($commonTest->printMessageBarTest('with_count', 5)) && p() && e("<li id='messageDropdown' class='relative'><a class='dropdown-toggle' id='messageBar' data-fetcher='/message-ajaxGetDropMenuForOld.html' onclick='fetchMessage()'><i class='icon icon-bell'></i><span class='label label-dot danger absolute rounded-sm' style='top:-3px; right:-5px; aspect-ratio:0; padding:2px'>5</span></a><div class='dropdown-menu messageDropdownBox absolute' style='padding:0;left:-320px;'><div id='dropdownMessageMenu' class='not-clear-menu'></div></div></li>"); // 步骤3：消息功能开启且有未读消息时显示消息数量
r($commonTest->printMessageBarTest('over_99', 150)) && p() && e("<li id='messageDropdown' class='relative'><a class='dropdown-toggle' id='messageBar' data-fetcher='/message-ajaxGetDropMenuForOld.html' onclick='fetchMessage()'><i class='icon icon-bell'></i><span class='label label-dot danger absolute rounded-sm' style='top:-3px; right:-10px; aspect-ratio:0; padding:2px'>99+</span></a><div class='dropdown-menu messageDropdownBox absolute' style='padding:0;left:-320px;'><div id='dropdownMessageMenu' class='not-clear-menu'></div></div></li>"); // 步骤4：未读消息数量超过99时显示99+
r($commonTest->printMessageBarTest('no_count', 3)) && p() && e("<li id='messageDropdown' class='relative'><a class='dropdown-toggle' id='messageBar' data-fetcher='/message-ajaxGetDropMenuForOld.html' onclick='fetchMessage()'><i class='icon icon-bell'></i><span class='label label-dot danger absolute' style='top:-2px; right:-2px; aspect-ratio:1 / 1; padding:2px; width:5px; height:5px'></span></a><div class='dropdown-menu messageDropdownBox absolute' style='padding:0;left:-320px;'><div id='dropdownMessageMenu' class='not-clear-menu'></div></div></li>"); // 步骤5：设置不显示计数时只显示红点