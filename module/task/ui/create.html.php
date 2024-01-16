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

$fields = useFields('task.create');
if($execution->type == 'kanban') $fields->merge('task.kanban');

formGridPanel
(
    set::title($lang->task->create),
    set::fields($fields),
    on::change('[name=module]', 'loadExecutionStories'),
    on::change('[name=story]', 'setStoryRelated'),
    on::click('[name=isShowAllModule]', 'showAllModule'),
    on::click('[name=copyButton]', 'copyStoryTitle'),
    on::keyup('[name=name]', 'saveTaskName'),
    on::keyup('[name=estimate]', 'saveTaskEstimate')
);
