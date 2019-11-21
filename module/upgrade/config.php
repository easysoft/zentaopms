<?php
$config->upgrade = new stdclass();
$config->upgrade->lowerTables = array();
$config->upgrade->lowerTables[$config->db->prefix . 'caseStep']       = $config->db->prefix . 'casestep';
$config->upgrade->lowerTables[$config->db->prefix . 'docLib']         = $config->db->prefix . 'doclib';
$config->upgrade->lowerTables[$config->db->prefix . 'groupPriv']      = $config->db->prefix . 'grouppriv';
$config->upgrade->lowerTables[$config->db->prefix . 'productPlan']    = $config->db->prefix . 'productplan';
$config->upgrade->lowerTables[$config->db->prefix . 'projectProduct'] = $config->db->prefix . 'projectproduct';
$config->upgrade->lowerTables[$config->db->prefix . 'projectStory']   = $config->db->prefix . 'projectstory';
$config->upgrade->lowerTables[$config->db->prefix . 'storySpec']      = $config->db->prefix . 'storyspec';
$config->upgrade->lowerTables[$config->db->prefix . 'taskEstimate']   = $config->db->prefix . 'taskestimate';
$config->upgrade->lowerTables[$config->db->prefix . 'testResult']     = $config->db->prefix . 'testresult';
$config->upgrade->lowerTables[$config->db->prefix . 'testRun']        = $config->db->prefix . 'testrun';
$config->upgrade->lowerTables[$config->db->prefix . 'testTask']       = $config->db->prefix . 'testtask';
$config->upgrade->lowerTables[$config->db->prefix . 'userContact']    = $config->db->prefix . 'usercontact';
$config->upgrade->lowerTables[$config->db->prefix . 'userGroup']      = $config->db->prefix . 'usergroup';
$config->upgrade->lowerTables[$config->db->prefix . 'userQuery']      = $config->db->prefix . 'userquery';
$config->upgrade->lowerTables[$config->db->prefix . 'userTPL']        = $config->db->prefix . 'usertpl';

$config->upgrade->bearychat = array();
$config->upgrade->bearychat['zh-cn'] = '倍洽';
$config->upgrade->bearychat['zh-tw'] = '倍洽';
$config->upgrade->bearychat['en']    = 'Bearychat';
$config->upgrade->bearychat['de']    = 'Bearychat';

$config->upgrade->discardedBugTypes['de']['interface']    = 'UI Optimierung';
$config->upgrade->discardedBugTypes['de']['newfeature']   = 'Neues Feature';
$config->upgrade->discardedBugTypes['de']['designchange'] = 'Design Änderung';
$config->upgrade->discardedBugTypes['de']['trackthings']  = 'Arbeit Verfolgen';

$config->upgrade->discardedBugTypes['en']['interface']    = 'Interface';
$config->upgrade->discardedBugTypes['en']['designchange'] = 'DesignChange';
$config->upgrade->discardedBugTypes['en']['newfeature']   = 'NewFeature';
$config->upgrade->discardedBugTypes['en']['trackthings']  = 'Tracking';

$config->upgrade->discardedBugTypes['fr']['interface']    = 'Interface';
$config->upgrade->discardedBugTypes['fr']['designchange'] = 'Design Change';
$config->upgrade->discardedBugTypes['fr']['newfeature']   = 'Nouvelle fonctionnalité';
$config->upgrade->discardedBugTypes['fr']['trackthings']  = 'Tracking';

$config->upgrade->discardedBugTypes['zh-cn']['interface']    = '界面优化';
$config->upgrade->discardedBugTypes['zh-cn']['designchange'] = '设计变更';
$config->upgrade->discardedBugTypes['zh-cn']['newfeature']   = "新增需求";
$config->upgrade->discardedBugTypes['zh-cn']['trackthings']  = '事务跟踪';

$config->upgrade->discardedBugTypes['zh-tw']['interface']    = '界面優化';
$config->upgrade->discardedBugTypes['zh-tw']['designchange'] = '設計變更';
$config->upgrade->discardedBugTypes['zh-tw']['newfeature']   = "新增需求";
$config->upgrade->discardedBugTypes['zh-tw']['trackthings']  = '事務跟蹤';

$config->delete['10.6'][] = 'module/chat/ext/control/extensions.php';
$config->delete['10.6'][] = 'module/common/ext/model/xuanxuan.php';
