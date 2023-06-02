<?php
declare(strict_types=1);
/**
 * The batchCreate view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;
/* zin: Set variables to define picker options for form. */

$items = array();

$items[] = array
(
    'name'    => 'date',
    'label'   => $lang->task->date,
    'control' => 'date',
    'width'   => '120px',
    'value'   => helper::today(),
);

$items[] = array
(
    'name'    => 'work',
    'label'   => $lang->task->work,
    'control' => 'input',
    'width'   => 'auto',
);

$items[] = array
(
    'name'    => 'consumed',
    'label'   => $lang->task->consumed,
    'control' => 'input',
    'width'   => '100px',
);

$items[] = array
(
    'name'    => 'left',
    'label'   => $lang->task->left,
    'control' => 'input',
    'width'   => '100px',
);

formBatch
(
    set::items($items),
);

/* ====== Render page ====== */
render(isonlybody() ? 'modalDialog' : 'page');
