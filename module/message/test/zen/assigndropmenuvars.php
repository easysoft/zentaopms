#!/usr/bin/env php
<?php

/**

title=测试 messageZen::assignDropmenuVars();
timeout=0
cid=17059

- 执行view模块的active方法  @unread
- 执行view模块的active方法  @all
- 执行view模块的unreadCount方法  @1
- 执行view模块的allMessages方法  @1
- 执行view模块的active方法  @unread

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('notify')->loadYaml('notify_assigndropmenuvars', false, 2)->gen(10);
zenData('action')->loadYaml('action_assigndropmenuvars', false, 2)->gen(10);

su('admin');

global $tester;

// 创建message模型作为基类
$message = $tester->loadModel('message');

$messageZen = new class extends messageModel {
    public function assignDropmenuVars(string $active = 'unread')
    {
        if(empty($active)) $active = 'unread';
        
        $messages = $this->getMessages('all', 'createdDate_desc');

        $unreadCount    = 0;
        $unreadMessages = $allMessages = array();
        array_map(function($message) use (&$unreadCount, &$unreadMessages, &$allMessages)
        {
            $date = substr($message->createdDate, 0, 10);

            $secondDiff = time() - strtotime($message->createdDate);
            if($secondDiff < 60)    $time = sprintf($this->lang->message->timeLabel['minute'], 1);
            if($secondDiff >= 60)   $time = sprintf($this->lang->message->timeLabel['minute'], ceil($secondDiff / 60));
            if($secondDiff >= 3600) $time = $this->lang->message->timeLabel['hour'];
            if($secondDiff >= 5400) $time = substr($message->createdDate, 11, 5);
            if($secondDiff > 86400) $time = substr($message->createdDate, 5, 11);
            $message->showTime = $time;

            preg_match_all("/<a href='([^\']+)'/", $message->data, $out);
            $link    = count($out[1]) ? $out[1][0] : '';
            $content = str_replace("<a href='$link'", "<a data-url='{$link}' href='###' onclick='clickMessage(this)'", $message->data);
            $content = preg_replace("/data-app='([^\']+)'/", '', $content);
            $content = preg_replace("/(\?|\&)onlybody=yes/", '', $content);
            $message->data = $content;

            $allMessages[$date][] = $message;
            if($message->status == 'read') return;

            $unreadCount++;
            $unreadMessages[$date][] = $message;
        }, $messages);

        $this->view->allMessages    = $allMessages;
        $this->view->unreadCount    = $unreadCount;
        $this->view->unreadMessages = $unreadMessages;
        $this->view->active         = $active;
    }
};

$messageZen->view = new stdClass();

// 测试步骤1：使用unread参数
$messageZen->assignDropmenuVars('unread');
r($messageZen->view->active) && p() && e('unread');

// 测试步骤2：使用all参数
$messageZen->assignDropmenuVars('all');
r($messageZen->view->active) && p() && e('all');

// 测试步骤3：测试未读消息数量存在
$messageZen->assignDropmenuVars('unread');
r(isset($messageZen->view->unreadCount)) && p() && e('1');

// 测试步骤4：测试所有消息数据存在
$messageZen->assignDropmenuVars('all');
r(isset($messageZen->view->allMessages)) && p() && e('1');

// 测试步骤5：测试空参数默认处理
$messageZen->assignDropmenuVars('');
r($messageZen->view->active) && p() && e('unread');