<?php
declare(strict_types=1);
/**
 * The ssh view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;
featureBar
(
    $this->app->tab != 'my' ? backBtn
    (
        set::icon('back'),
        set::type('secondary'),
        $lang->goback
    ) : null
);

$createLink = $this->createLink('my', 'createSSH');
$createItem = array('text' => $lang->my->createSSH, 'url' => $createLink, 'class' => 'primary', 'icon' => 'plus', 'data-size' => 'md', 'data-toggle' => 'modal');
toolbar(item(set($createItem)));

$tableData = initTableData($sshList, $config->my->ssh->dtable->fieldList, $this->my);
dtable
(
    set::cols($config->my->ssh->dtable->fieldList),
    set::data($tableData)
);
