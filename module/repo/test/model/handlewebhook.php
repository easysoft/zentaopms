#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->handleWebhook();
timeout=0
cid=1

- 处理push事件的webhook
 - 属性status @doing
 - 属性consumed @11
 - 属性left @3
- 处理merge request事件的webhook
 - 属性status @doing
 - 属性consumed @12
 - 属性left @3
- 处理不支持的事件webhook @0

*/

zdTable('task')->gen(10);
$bug = zdTable('bug');
$bug->execution->range('0');
$bug->gen(10);
zdTable('repo')->config('repo')->gen(4);

$repoID = 1;
$event  = 'Push Hook';
$event2 = 'Merge Request Hook';
$event3 = 'Else Hook';
$data   = '{
  "object_kind": "push",
  "event_name": "push",
  "before": "cf8e97ca2c06bd0da81f97107b185dad64af071c",
  "after": "8c69a6c51d04ad80cd18b4845dc147b32bcf20c7",
  "ref": "refs/heads/master",
  "checkout_sha": "8c69a6c51d04ad80cd18b4845dc147b32bcf20c7",
  "message": null,
  "user_id": 1,
  "user_name": "Administrator",
  "user_username": "root",
  "user_email": "",
  "user_avatar": "http://10.0.7.242:9980/uploads/-/system/user/avatar/1/avatar.png",
  "project_id": 1661,
  "project": {
    "id": 1661,
    "name": "Zentao Casescripts",
    "description": "",
    "web_url": "http://10.0.7.242:9980/BJ/zentao-casescripts",
    "avatar_url": null,
    "git_ssh_url": "ssh://git@10.0.7.242:9922/BJ/zentao-casescripts.git",
    "git_http_url": "http://10.0.7.242:9980/BJ/zentao-casescripts.git",
    "namespace": "BJ",
    "visibility_level": 0,
    "path_with_namespace": "BJ/zentao-casescripts",
    "default_branch": "master",
    "ci_config_path": "",
    "homepage": "http://10.0.7.242:9980/BJ/zentao-casescripts",
    "url": "ssh://git@10.0.7.242:9922/BJ/zentao-casescripts.git",
    "ssh_url": "ssh://git@10.0.7.242:9922/BJ/zentao-casescripts.git",
    "http_url": "http://10.0.7.242:9980/BJ/zentao-casescripts.git"
  },
  "commits": [
    {
      "id": "ae774776f37ae02ad04fc54a252fc1f1be1eef4a",
      "message": "Effort Task #8 Cost:1h Left:3h",
      "title": "Effort Task #8 Cost:1h Left:3h",
      "timestamp": "2023-09-20T15:51:53+08:00",
      "url": "http://10.0.7.242:9980/BJ/zentao-casescripts/-/commit/8c69a6c51d04ad80cd18b4845dc147b32bcf20c7",
      "author": {
        "name": "Administrator",
        "email": "admin@example.com"
      },
      "added": [

      ],
      "modified": [
        "5/test-sdk.php"
      ],
      "removed": [

      ]
    },
    {
      "id": "deac8c5d8254f9f5c563d6e76ea81fd2640f0579",
      "message": "Add new file",
      "title": "Add new file",
      "timestamp": "2023-07-20T15:15:20+08:00",
      "url": "http://10.0.7.242:9980/BJ/zentao-casescripts/-/commit/deac8c5d8254f9f5c563d6e76ea81fd2640f0579",
      "author": {
        "name": "Administrator",
        "email": "admin@example.com"
      },
      "added": [
        "5/test-sdk.php"
      ],
      "modified": [

      ],
      "removed": [

      ]
    },
    {
      "id": "cf8e97ca2c06bd0da81f97107b185dad64af071c",
      "message": "Add new file",
      "title": "Add new file",
      "timestamp": "2023-07-20T15:13:29+08:00",
      "url": "http://10.0.7.242:9980/BJ/zentao-casescripts/-/commit/cf8e97ca2c06bd0da81f97107b185dad64af071c",
      "author": {
        "name": "Administrator",
        "email": "admin@example.com"
      },
      "added": [
        "5/vendor/sdk.php"
      ],
      "modified": [

      ],
      "removed": [

      ]
    }
  ],
  "total_commits_count": 3,
  "push_options": {
  },
  "repository": {
    "name": "Zentao Casescripts",
    "url": "ssh://git@10.0.7.242:9922/BJ/zentao-casescripts.git",
    "description": "",
    "homepage": "http://10.0.7.242:9980/BJ/zentao-casescripts",
    "git_http_url": "http://10.0.7.242:9980/BJ/zentao-casescripts.git",
    "git_ssh_url": "ssh://git@10.0.7.242:9922/BJ/zentao-casescripts.git",
    "visibility_level": 0
  }
}';

$repo = new repoTest();
$repo->handleWebhookTest($event, json_decode($data), $repoID);
$result = $tester->loadModel('task')->getById(8);
r($result) && p('status,consumed,left') && e('doing,11,3'); //处理push事件的webhook

$repo->handleWebhookTest($event2, json_decode($data), $repoID);
$result = $tester->loadModel('task')->getById(8);
r($result) && p('status,consumed,left') && e('doing,12,3'); //处理merge request事件的webhook

r($repo->handleWebhookTest($event3, json_decode($data), $repoID)) && p() && e('0'); //处理不支持的事件webhook