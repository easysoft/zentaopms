<?php
declare(strict_types=1);
/**
 * The thinkQuestion widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zemei Wang<wangzemei@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

requireWg('thinkStepBase');

/**
 * 思引师问题的基础节点。
 * thinmory basic node content.
 */

class thinkQuestion extends thinkStepBase
{
    protected static array $defineProps = array(
        'required?: int=1',      // 是否必填
        'questionType?: string', // 问题类型
        'value?: string',        // 问题的答案
        'isResult?: bool=false', // 是否是结果页
    );

    protected static array $defaultProps = array
    (
        'type' => 'question'
    );
}
