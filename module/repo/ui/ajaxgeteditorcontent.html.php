<?php
declare(strict_types=1);
/**
 * The monaco view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     repo
 * @link        http://www.zentao.net
 */

namespace zin;

$lang = 'php';
foreach($fileExt as $langName => $ext)
{
    if(str_contains('.' . $file->extension, $ext)) $lang = $langName;
}
monaco(
    set::id('codeContainer'),
    set::options(array(
        'value'                => $content,
        'language'             => $lang,
        'readOnly'             => true,
        'autoIndent'           => true,
        'contextmenu'          => true,
        'automaticLayout'      => true,
        'EditorMinimapOptions' => array('enabled' => false),
    )),
    set::onMouseDown('window.onMouseDown')
);

render('fragment');