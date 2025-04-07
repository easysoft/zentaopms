<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'dropdown' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';

class visionSwitcher extends wg
{
    public static function getPageCSS(): ?string
    {
        return <<<CSS
        #versionSwitchBtn > .icon, #versionMenu .item-icon {display: flex; width: 18px; height: 18px; border-radius: var(--radius-md); align-items: center; justify-content: center; background: rgba(var(--color-inverse-rgb), .1); opacity: .7;}
        #versionSwitchBtn > .icon, #versionMenu .selected .item-icon {background: var(--color-primary-500); color: #fff; opacity: 1;}
        #versionSwitchBtn > .icon::before, #versionMenu .item-icon::before {transform: scale(.8);}
        #versionMenu {min-width: 160px;}
        #versionMenu .selected:not(:hover) .item-title {color: var(--color-fore)}
        CSS;
    }

    public array $icons = array
    (
        'lite'    => 'target',
        'rnd'     => 'remote',
        'or'      => 'or',
        'manager' => 'manager',
        'ipd'     => 'ipd'
    );

    protected function getVisionIcon(string $vision): string
    {
        return isset($this->icons[$vision]) ? $this->icons[$vision] : 'bars';
    }

    protected function buildVisionTips(): ?node
    {
        global $config, $lang;
        if(!empty($config->global->hideVisionTips)) return null;

        return div
        (
            setClass('vision-tips primary rounded-lg w-72 p-4 absolute left-0 z-100 flex items-center'),
            style::bottom(60),
            div(setClass('flex-auto'), $lang->visionTips),
            btn
            (
                set::type('light-outline'),
                on::click()->do
                (
                    '$this.closest(".vision-tips").remove()',
                    '$.post($.createLink("my", "ajaxSaveVisionTips"), {fields: 1})'
                ),
                $lang->IKnow
            ),
            div
            (
                setClass('bg-inherit w-0.5 h-8 absolute'),
                style::bottom(-31)->left(64),
                div
                (
                    setClass('w-2.5 h-2.5 rounded-full border-2 relative border-primary bg-canvas'),
                    style::left(-4)->top(32)
                )
            )
        );
    }

    protected function build()
    {
        global $lang, $app, $config;
        if(!isset($app->user)) return;

        $user = $app->user;
        if(!isset($user->visions)) $user->visions = trim($config->visions, ',');

        $currentVision = $app->config->vision;
        $userVisions   = array_filter(explode(',', $user->visions));
        $configVisions = array_filter(explode(',', trim($config->visions, ',')));

        pageJS(<<<JS
            window.getUserVisions = () => '{$user->visions}'.split(',');
            window.getCurrentVision = () => '{$currentVision}';
        JS);

        /* The standalone lite version removes the lite interface button */
        if(trim($config->visions, ',') == 'lite') return;

        /* Append the current vision to the user visions to switch vision. */
        if(count($userVisions) == 1 && current($userVisions) != $currentVision)
        {
            $userVisions[] = $currentVision;
        }

        if(count($userVisions) < 2 || count($configVisions) < 2)
        {
            return new btn
            (
                setClass('ghost'),
                set::id('versionSwitchBtn'),
                set::icon($this->getVisionIcon($currentVision)),
                $lang->visionList[$currentVision]
            );
        }

        $items = array();
        $items[] = array
        (
            'type' => 'heading',
            'titleClass' => 'font-normal',
            'text' => $lang->switchTo
        );
        foreach($userVisions as $vision)
        {
            $items[] = array
            (
                'selected'  => $currentVision == $vision,
                'trailingIcon'  => $currentVision == $vision ? 'check' : '',
                'url'       => "javascript:selectVision('$vision')",
                'icon'      => $this->getVisionIcon($vision),
                'data-type' => 'ajax',
                'text'      => isset($lang->visionList[$vision]) ? $lang->visionList[$vision] : $vision,
            );
        }

        $dropdown = new dropdown
        (
            new btn
            (
                setClass('ghost'),
                set::id('versionSwitchBtn'),
                set::text($lang->visionList[$currentVision]),
                set::icon($this->getVisionIcon($currentVision)),
                set::caret('up')
            ),

            set::id('versionMenu'),
            set::placement('top-start'),
            set::menu(array('className' => 'pt-1 space-y-1', 'minWidth' => 200)),
            set::arrow(true),
            set::items($items)
        );

        $tips = $this->buildVisionTips();
        return $tips ? array($dropdown, $tips) : $dropdown;
    }
}
