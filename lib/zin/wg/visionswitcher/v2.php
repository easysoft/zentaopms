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
        #versionSwitchBtn > .icon, .vision-icon {display: flex; width: 18px; height: 18px; border-radius: var(--radius-md); align-items: center; justify-content: center; background: rgba(var(--color-inverse-rgb), .1); opacity: .7;}
        #versionSwitchBtn > .icon, .selected .vision-icon {background: var(--color-primary-500); color: #fff; opacity: 1;}
        #versionSwitchBtn > .icon::before, .vision-icon::before {transform: scale(.8);}

        #visionSwitchMenu {display: none; min-width: 240px; border-radius: var(--radius-md); --menu-bg: transparent}
        .vision-vision-menu-main {width: 240px}
        #visionSwitchMenu.show {display: flex; flex-direction: column;}
        #visionSwitchMenu .vision-icon {width: 20px; height: 20px; opacity: 1}
        #visionSwitchMenu .menu-heading {font-weight: normal}
        #visionSwitchMenu .vision-workspace-menu {width: 280px; display: flex; align-items: stretch;}
        #visionSwitchMenu .vision-workspace-menu.is-expanded {width: 560px;}
        #visionSwitchMenu .vision-workspace-menu > * {min-width: 0;}
        CSS;
    }

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v2.js');
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

        /* The standalone lite version removes the lite interface button */
        if(trim($config->visions, ',') == 'lite') return;

        /* Append the current vision to the user visions to switch vision. */
        if(count($userVisions) == 1 && current($userVisions) != $currentVision)
        {
            $userVisions[] = $currentVision;
        }

        $visions = [];
        foreach($userVisions as $vision)
        {
            $visions[] = ['key' => $vision, 'icon' => $this->getVisionIcon($vision), 'text' => isset($lang->visionList[$vision]) ? $lang->visionList[$vision] : $vision];
        }

        $spaces = [];
        /* Only show the workspace switcher in the RND vision and not in the XuanXuan client. */
        if($currentVision == 'rnd' && !str_contains($_SERVER['HTTP_USER_AGENT'], 'xuanxuan'))
        {
            if(hasPriv('product', 'all'))    $spaces[] = ['key' => 'product', 'icon' => 'product', 'text' => $lang->workspaceList['product'], 'fetcher' => createLink('product', 'ajaxGetDropMenu', 'productID=0&module=product&method=browse&extra=story')];
            if(hasPriv('project', 'browse')) $spaces[] = ['key' => 'project', 'icon' => 'project', 'text' => $lang->workspaceList['project'], 'fetcher' => createLink('project', 'ajaxGetDropMenu', 'projectID=0')];
            if(hasPriv('execution', 'task')) $spaces[] = ['key' => 'execution', 'icon' => 'run', 'text' => $lang->workspaceList['execution'], 'fetcher' => createLink('execution', 'ajaxGetDropMenu', 'objectID=0')];
        }

        return array
        (
            dropdown
            (
                set::placement('top-start'),
                set::target('#visionSwitchMenu'),
                set::triggerProps(['maxHeight' => 400]),
                btn
                (
                    setClass('ghost'),
                    set::id('versionSwitchBtn'),
                    set::text($lang->visionList[$currentVision]),
                    set::icon($this->getVisionIcon($currentVision)),
                    set::caret('up')
                )
            ),
            $this->buildVisionTips(),
            zui::visionSwitchMenu
            (
                set::_id('visionSwitchMenu'),
                set::visions($visions),
                set::currentVision($currentVision),
                set::spaces($spaces),
                set::switchVisionText($lang->switchVision),
                set::switchWorkspaceText($lang->switchWorkspace)
            ),
            pageJS(<<<JS
                window.getUserVisions = () => '{$user->visions}'.split(',');
                window.getCurrentVision = () => '{$currentVision}';
            JS)
        );
    }
}
