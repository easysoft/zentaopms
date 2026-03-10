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
        'users?:array',
        'showFields?:string',
        'exportFileName?:string',
        'toolbar?:bool|array',
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
        $js = file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
        return $js;
    }

    public function getUserList(): array
    {
        $users = $this->prop('users');
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

    protected function getToolbar()
    {
        $ganttLang = $this->prop('ganttLang');
        $toolbar   = $this->prop('toolbar');
        if(empty($toolbar)) return null;

        $ganttToolbar = array();
        $ganttToolbar[] = btn
        (
            set::type('ghost'),
            set::icon('guide'),
            set::url('javascript:scrollToToday()'),
            set::hint($ganttLang->scrollToToday)
        );
        if($toolbar === true || (is_array($toolbar) && in_array('criticalPath', $toolbar)))
        {
            $ganttToolbar[] = btn
            (
                setID('criticalPath'),
                set::type('ghost'),
                set::icon('blame rotate-90'),
                set::url('javascript:updateCriticalPath()'),
                set::hint($ganttLang->showCriticalPath)
            );
        }
        if($toolbar === true || (is_array($toolbar) && in_array('fullscreen', $toolbar)))
        {
            $ganttToolbar[] = btn
            (
                setID('fullScreenBtn'),
                set::type('ghost'),
                set::icon('fullscreen'),
                set::hint($ganttLang->fullScreen)
            );
        }
        if($toolbar === true || (is_array($toolbar) && in_array('setting', $toolbar)))
        {
            $ganttToolbar[] = btn
            (
                set::type('ghost'),
                set::icon('cog-outline'),
                set::url($this->prop('settingLink')),
                setData(array('toggle' => 'modal', 'size' => '45%')),
                set::hint($ganttLang->ganttSetting)
            );
        }

        return div
        (
            setClass('absolute gantt-toolbar'),
            set::style(array('width' => '40px', 'top' => '0', 'right' => '-50px')),
            div(setClass('p-0.5 border border-gray-300'), $ganttToolbar)
        );
    }

    protected function build()
    {
        global $app;

        list($id, $zooming, $colsWidth, $showChart) = $this->prop(array('id', 'zooming', 'colsWidth', 'showChart'));
        if(empty($id))           $id        = 'ganttView';
        if(empty($zooming))      $zooming   = 'day';
        if(empty($colsWidth))    $colsWidth = '600';
        if($showChart !== false) $showChart = true;

        $colResize    = $showChart;
        $ganttType    = data('ganttType');
        $project      = data('project');
        $showFields   = $this->prop('showFields');
        $reviewPoints = ($project && $project->model == 'ipd') ? data('reviewPoints') : array();
        $ganttLang    = $this->prop('ganttLang');
        $ganttFields  = $this->prop('ganttFields');
        $toolbar      = $this->prop('toolbar');
        $holidays     = $this->prop('holidays');
        $options      = $this->prop('options');
        if(is_string($options) && $options == '[]') $options = array();

        return div
        (
            jsVar('ganttID',         $id),
            jsVar('projectID',       $project ? $project->id : 0),
            jsVar('module',          $app->rawModule),
            jsVar('method',          $app->rawMethod),
            jsVar('jsRoot',          $app->getWebRoot()),
            jsVar('ganttType',       $ganttType),
            jsVar('showFields',      empty($showFields) ? array() : explode(',', $showFields)),
            jsVar('showChart',       $showChart),
            jsVar('colResize',       $colResize),
            jsVar('userList',        $this->getUserList()),
            jsVar('ganttLang',       $ganttLang),
            jsVar('canGanttEdit',    $this->prop('canEdit')),
            jsVar('canEditDeadline', $this->prop('canEditDeadline')),
            jsVar('ganttFields',     $ganttFields),
            jsVar('zooming',         $this->prop('zooming')),
            jsVar('options',         $options),
            jsVar('exportFileName',  $this->prop('exportFileName')),
            jsVar('weekend',         $this->prop('weekend')),
            jsVar('holidays',        is_array($holidays) ? array_values($holidays) : array()),
            jsVar('colsWidth',       (float)$colsWidth),
            jsVar('height',          (float)$this->prop('height')),
            jsVar('canViewReview',   common::hasPriv('review', 'view')),
            jsVar('canViewTaskList', common::hasPriv('execution', 'task')),
            jsVar('canViewTask',     common::hasPriv('task', 'view')),
            setID('ganttContainer'),
            on::click('.toggle-all-icon')->call('toggleAllTasks'),
            div
            (
                setClass('relative'),
                empty($toolbar) ? null : setStyle(array('width' => 'calc(100% - 25px)')),
                div(setID($id), setClass('gantt is-collapsed')),
                $this->getToolbar()
            ),
            div(setID('myCover'), div(setID('gantt_here'), setData('reviewpoints', json_encode($reviewPoints)))),
            modal
            (
                setID('changeDeadlineModal'),
                set::size('sm'),
                set::title($ganttLang->edit),
                inputGroup
                (
                    $ganttFields['column_deadline'],
                    datePicker(set::name('deadline')),
                    formHidden('reviewID', ''),
                    formHidden('projectID', ''),
                    btn(setClass('primary'), $ganttLang->edit, on::click('saveDeadline'))
                )
            )
        );
    }
}
