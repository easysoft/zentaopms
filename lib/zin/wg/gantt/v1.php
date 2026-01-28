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
        $js = file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
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
        $ganttLang    = $this->prop('ganttLang');
        $ganttFields  = $this->prop('ganttFields');

        return div
        (
            jsVar('ganttID',         $id),
            jsVar('projectID',       $project ? $project->id : 0),
            jsVar('module',          $app->rawModule),
            jsVar('method',          $app->rawMethod),
            jsVar('jsRoot',          $app->getWebRoot()),
            jsVar('fileName',        $fileName),
            jsVar('ganttType',       $ganttType),
            jsVar('showFields',      $showFields),
            jsVar('showChart',       $showChart),
            jsVar('colResize',       $colResize),
            jsVar('userList',        $this->getUserList()),
            jsVar('ganttLang',       $ganttLang),
            jsVar('canGanttEdit',    $this->prop('canEdit')),
            jsVar('canEditDeadline', $this->prop('canEditDeadline')),
            jsVar('ganttFields',     $ganttFields),
            jsVar('zooming',         $this->prop('zooming')),
            jsVar('options',         $this->prop('options')),
            jsVar('colsWidth',       (float)$colsWidth),
            jsVar('height',          (float)$this->prop('height')),
            jsVar('canViewReview',   common::hasPriv('review', 'view')),
            jsVar('canViewTaskList', common::hasPriv('execution', 'task')),
            jsVar('canViewTask',     common::hasPriv('task', 'view')),
            setID('ganttContainer'),
            on::click('.toggle-all-icon')->call('toggleAllTasks'),
            div(setID($id), setClass('gantt is-collapsed')),
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
