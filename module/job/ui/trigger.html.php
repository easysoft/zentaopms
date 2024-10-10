<?php
declare(strict_types=1);
/**
 * The trigger view file of job module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     job
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('repo', $repo);
jsVar('dirs', !empty($dirs) ? $dirs : '');
jsVar('buildTag', $lang->job->buildTag);
jsVar('dirChange', $lang->job->dirChange);
jsVar('triggerTypeList', $lang->job->triggerTypeList);

jsVar('svnField', formRow
(
    setClass('svn-fields linkage-fields hidden'),
    formGroup
    (
        set::name('svnDir[]'),
        set::width('1/2'),
        set::label($lang->job->svnDir),
        set::items(!empty($dirs) ? $dirs : array()),
        set::value($job->svnDir)
    )
)->render());
jsVar('commentField', formRow
(
    setClass('comment-fields linkage-fields hidden mt-4'),
    formGroup
    (
        set::name('comment'),
        set::label($lang->job->comment),
        set::value($job->comment),
        set::width('1/2'),
        set::required(true)
    ),
    h::span
    (
        setClass('leading-8 ml-2'),
        html($lang->job->commitEx)
    )
)->render());
jsVar('scheduleField', div
(
    setClass('linkage-fields'),
    formRow
    (
        setClass('custom-fields hidden mt-2'),
        formGroup
        (
            set::label(''),
            set::name('atDay[]'),
            set::control('checkListInline'),
            set::items($lang->datepicker->dayNames),
            set::value($job->atDay)
        )
    ),
    formRow
    (
        setClass('custom-fields hidden'),
        formGroup
        (
            set::label(''),
            set::width('1/2'),
            inputGroup
            (
                $lang->job->atTime,
                timePicker
                (
                    set::name('atTime'),
                    set::value($job->atTime)
                )
            )
        )
    )
)->render());
jsVar('triggerField', div
(
    setClass('trigger-box border py-4 hidden bg-gray-100'),
    icon('trash pull-right mr-4 delete-trigger cursor-pointer'),
    formGroup
    (
        set::name("triggerType[%s]"),
        set::width('1/2'),
        set::required(true),
        set::label($lang->job->triggerType),
        set::control('static'),
        div(setID('triggerPicker%s'))
    )
)->render());

$triggers = array();
if(empty($job->triggerType)) $job->triggerType = key($lang->job->triggerTypeList);
foreach(explode(',', $job->triggerType) as $index => $trigger)
{
    $triggers[] = div
    (
        setClass('trigger-box border py-4 bg-gray-100'),
        icon
        (
            'trash pull-right mr-4 delete-trigger cursor-pointer',
            !strpos($job->triggerType, ',') ? setClass('hidden') : null
        ),
        formGroup
        (
            set::width('1/2'),
            set::name("triggerType[$index]"),
            set::required(true),
            set::label($lang->job->triggerType),
            set::items($lang->job->triggerTypeList),
            set::value($trigger)
        ),
        $trigger == 'tag' && $repo->SCM == 'Subversion' ? formRow
        (
            setClass('svn-fields hidden'),
            formGroup
            (
                set::name('svnDir[]'),
                set::width('1/2'),
                set::label($lang->job->svnDir),
                set::items(!empty($dirs) ? $dirs : array()),
                set::value($job->svnDir)
            )
        ) : null,
        $trigger == 'commit' ? formRow
        (
            setClass('comment-fields hidden mt-4'),
            formGroup
            (
                set::name('comment'),
                set::label($lang->job->comment),
                set::value($job->comment),
                set::width('1/2'),
                set::required(true)
            ),
            h::span
            (
                setClass('leading-8 ml-2'),
                html($lang->job->commitEx)
            )
        ) : null,
        $trigger == 'schedule' ? div
        (
            formRow
            (
                setClass('custom-fields hidden mt-2'),
                formGroup
                (
                    set::label(''),
                    set::name('atDay[]'),
                    set::control('checkListInline'),
                    set::items($lang->datepicker->dayNames),
                    set::value($job->atDay)
                )
            ),
            formRow
            (
                setClass('custom-fields hidden'),
                formGroup
                (
                    set::label(''),
                    set::width('1/2'),
                    inputGroup
                    (
                        $lang->job->atTime,
                        timePicker
                        (
                            set::name('atTime'),
                            set::value($job->atTime)
                        )
                    )
                )
            )
        ) : null,
    );
}
