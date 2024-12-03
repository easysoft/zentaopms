<?php
declare(strict_types=1);
/**
 * The taskAssignedTo widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

class taskAssignedTo extends wg
{
    protected static array $defineProps = array(
        'id?: string',                // 控件 ID。
        'name?: string="assignedTo"', // 控件名称。
        'value?: string',             // 控件默认值。
        'required?: bool=false',      // 是否必填。
        'items?: array',              // picker 列表项或列表项获取方法。
        'manageLink?: string'         // 维护团队成员链接
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function build()
    {
        global $app, $lang;
        $app->loadLang('execution');

        $pickerToolbar = false;
        $manageLink    = $this->prop('manageLink');
        if($this->prop('multiple') || $manageLink)
        {
            $pickerToolbar = array();
            if($this->prop('multiple'))
            {
                $pickerToolbar[] = array('key' => 'selectAll', 'text' => $lang->selectAll);
                $pickerToolbar[] = array('key' => 'cancelSelect', 'text' => $lang->cancelSelect);

                $this->setProp('menu', array('checkbox' => true));
            }

            if($manageLink) $pickerToolbar[] = array('type' => 'button', 'className' => 'text-primary manageTeamBtn', 'key' => 'manageTeam', 'text' => $lang->execution->manageTeamMember, 'icon' => 'plus-solid-circle', 'url' => $manageLink, 'data-toggle' => 'modal', 'data-size' => 'lg', 'data-dismiss' => 'pick');
        }

        $this->setProp('toolbar', $pickerToolbar);

        return picker
        (
            setClass('taskAssignedToBox'),
            set($this->props->pick(array('id', 'name', 'value', 'required', 'items', 'toolbar', 'menu', 'multiple')))
        );
    }
}
