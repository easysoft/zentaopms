<?php
declare(strict_types=1);
/**
 * The blockPanel widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'panel' . DS . 'v1.php';

/**
 * 仪表盘区块面板（blockPanel）部件类。
 * The block panel widget class.
 *
 * @author Hao Sun
 */
class blockPanel extends panel
{
    protected static array $defineProps = array
    (
        'class?: string="rounded bg-canvas panel-block"', // 类名。
        'name?: string',                    // 区块内部名称。
        'title?: string',                   // 标题。
        'headingClass?: string="border-b"', // 标题栏类名。
        'moreLink?: string'                 // 更多链接。
    );

    protected function created()
    {
        global $lang, $app;
        $defaultProps = array();

        $name = $this->prop('name');
        $block = data('block');
        if(empty($name) && !empty($block))
        {
            $name = $block->code;
            $defaultProps['name'] = $name;
        }

        $moreLink = $this->prop('moreLink');
        if(empty($moreLink) && !empty($block) && isset($block->moreLink)) $moreLink = $block->moreLink;
        if(!$this->hasProp('headingActions') && !empty($moreLink))
        {
            $defaultProps['headingActions'] = array(array('type' => 'ghost', 'url' => $moreLink, 'text' => $lang->more, 'caret' => 'right'));
        }

        if(!$this->hasProp('title')) $defaultProps['title'] = $lang->block->titleList[$name];

        $this->setDefaultProps($defaultProps);
    }

    protected function buildProps(): array
    {
        $props = parent::buildProps();
        $name = $this->prop('name');
        if(!empty($name)) $props[] = setData('block', $name);
        return $props;
    }
}
