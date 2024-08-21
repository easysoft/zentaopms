<?php
declare(strict_types=1);
/**
 * The thinkMatrixOptions widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zemei Wang<wangzemei@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;
class thinkMatrixOptions extends wg
{
    protected static array $defineProps = array
    (
        'id?: string="$GID"',     // 组件根元素的 ID。
        'name?: string="fields"', // 输入框作为表单项的名称。
    );
}
