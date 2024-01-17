<?php
declare(strict_types=1);
/**
 * The create view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tian Shujie<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('window.executionID', $execution->id);
jsVar('vision', $config->vision);
jsVar('window.lifetime', $execution->lifetime);
jsVar('window.attribute', $execution->attribute);
jsVar('window.lifetimeList', $lifetimeList);
jsVar('window.attributeList', $attributeList);

$fields = useFields('task.create');

$fields->orders('desc,module,storyBox');
$fields->fullModeOrders('type,module,storyBox', 'desc,file,mailto,keywords');
if($execution->type == 'kanban')
{
    $fields->orders('desc,module,storyBox', 'type,assignedTo,region,lane');
    $fields->fullModeOrders('name,assignedTo', 'type,module,storyBox', 'desc,file,mailto,keywords');
    if(empty($features['story'])) $fields->fullModeOrders('type,module,storyBox', 'name,assignedTo', 'desc,file,mailto,keywords');
}

if(empty($features['story']) && $execution->type != 'kanban')
{
    $fields->fullModeOrders('type,module,storyBox,assignedTo', 'desc,file,mailto,keywords');
}

$fields->autoLoad('execution', 'execution,type,name,assignedTo,region,lane,module,storyBox,datePlan,pri,estimate,desc,file,mailto,keywords,after');

formGridPanel
(
    set::title($lang->task->create),
    set::fields($fields),
    set::loadUrl($loadUrl),
    on::change('[name=module]', 'loadExecutionStories'),
    on::change('[name=story]', 'setStoryRelated'),
    on::change('[name=type]', 'typeChange'),
    on::change('[name=region]', 'loadLanes'),
    on::click('[name=isShowAllModule]', 'showAllModule'),
    on::click('[name=copyButton]', 'copyStoryTitle'),
    on::keyup('[name=name]', 'saveTaskName'),
    on::keyup('[name=estimate]', 'saveTaskEstimate')
);
