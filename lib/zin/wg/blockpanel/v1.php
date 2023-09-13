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
        'id?: string',                      // ID。
        'name?: string',                    // 区块内部名称。
        'block?: object|array',             // 区块对象。
        'title?: string',                   // 标题。
        'headingClass?: string="border-b"', // 标题栏类名。
        'longBlock?: bool',                 // 是否为长区块。
        'moreLink?: string'                 // 更多链接。
    );

    protected function created()
    {
        global $lang;
        $props = array();

        $name = $this->prop('name');
        $block = $this->prop('block', data('block'));

        if(is_array($block)) $block = (object)$block;
        if(empty($name) && !empty($block))
        {
            $name = $block->code;
            $props['name'] = $name;

            if(empty($this->prop('id'))) $props['id'] = $block->module . '-' . $block->code . '-' . $block->id;
        }

        $moreLink = $this->prop('moreLink');
        if(empty($moreLink) && !empty($block) && isset($block->moreLink)) $moreLink = $block->moreLink;
        if(empty($this->prop('headingActions')) && !empty($moreLink))
        {
            $props['headingActions'] = array(array('type' => 'ghost', 'url' => $moreLink, 'text' => $lang->more, 'caret' => 'right', 'size' => 'sm'));
        }

        if(empty($this->prop('title'))) $props['title'] = empty($block) ? $lang->block->titleList[$name] : $block->title;

        if($this->prop('longBlock') === null) $props['longBlock'] = data('longBlock');

        $this->setProp($props);
    }

    protected function buildProps(): array
    {
        $props = parent::buildProps();
        $name  = $this->prop('name');
        if(!empty($name))
        {
            $props[] = setData('block', $name);
            $props[] = setClass("block-{$name}");
            $props[] = setID($this->prop('id'));
        }

        $props[] = setClass($this->prop('longBlock') ? 'is-long' : 'is-short');

        return $props;
    }
}
