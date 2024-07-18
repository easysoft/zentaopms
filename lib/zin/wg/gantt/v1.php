<?php
declare(strict_types=1);
namespace zin;

class gantt extends wg
{
    protected static array $defineProps = array(
        'id:string',
        'ganttLang:array',
        'canEdit:bool',
        'canEditDeadline:bool',
        'ganttFields:array',
        'showChart?:bool',
        'zooming?:string',
        'options?:array'
    );

    protected static array $defaultProps = array(
        'showChart' => true,
        'zooming' => 'day'
    );

    public static function getPageCSS(): string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        global $app;
        $currentLang = $app->getClientLang();
        $langJSFile  = $app->getWwwRoot() . 'js/dhtmlxgantt/lang/' . $currentLang . '.js';

        $js = file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
        if($currentLang != 'en' && file_exists($langJSFile)) $js .= "\nwaitGantt(function(){\n" . file_get_contents($langJSFile) . "\n});\n";
        return $js;
    }

    public function getUserList(): array
    {
        $users = data('users');
        if(empty($users)) return array();

        $userList = array();
        foreach($users as $account => $realname)
        {
            $user = array();
            $user['key']   = $account;
            $user['label'] = $realname;
            $userList[]    = $user;
        }
        return $userList;
    }

    protected function build()
    {
        global $app;
        $cssFile = $app->getWebRoot() . 'js/dhtmlxgantt/min.css';
        $jsFile  = $app->getWebRoot() . 'js/dhtmlxgantt/min.js';

        list($id, $zooming, $colsWidth, $showChart) = $this->prop(array('id', 'zooming', 'colsWidth', 'showChart'));
        if(empty($id))           $id        = 'ganttView';
        if(empty($zooming))      $zooming   = 'day';
        if(empty($colsWidth))    $colsWidth = '600';
        if($showChart !== false) $showChart = true;

        $colResize    = $showChart;
        $fileName     = data('fileName');
        $ganttType    = data('ganttType');
        $project      = data('project');
        $showFields   = data('showFields');
        $reviewPoints = ($project && $project->model == 'ipd') ? data('reviewPoints') : array();

        return div
        (
            h::import($cssFile),
            h::import($jsFile),
            jsVar('ganttID',         $id),
            jsVar('projectID',       $project ? $project->id : 0),
            jsVar('module',          $app->rawModule),
            jsVar('method',          $app->rawMethod),
            jsVar('jsRoot',          $app->getWebRoot()),
            jsVar('fileName',        $fileName),
            jsVar('reviewPoints',    $reviewPoints),
            jsVar('ganttType',       $ganttType),
            jsVar('showFields',      $showFields),
            jsVar('colsWidth',       $colsWidth),
            jsVar('showChart',       $showChart),
            jsVar('colResize',       $colResize),
            jsVar('userList',        $this->getUserList()),
            jsVar('ganttLang',       $this->prop('ganttLang')),
            jsVar('canGanttEdit',    $this->prop('canEdit')),
            jsVar('canEditDeadline', $this->prop('canEditDeadline')),
            jsVar('ganttFields',     $this->prop('ganttFields')),
            jsVar('zooming',         $this->prop('zooming')),
            jsVar('options',         $this->prop('options')),
            jsVar('height',          (int)$this->prop('height')),
            jsVar('canViewReview',   common::hasPriv('review', 'view')),
            jsVar('canViewTaskList', common::hasPriv('execution', 'task')),
            jsVar('canViewTask',     common::hasPriv('task', 'view')),
            setID('ganttContainer'),
            div(setID($id), setClass('gantt')),
            div(setID('myCover'), div(setID('gantt_here')))
        );
    }
}
