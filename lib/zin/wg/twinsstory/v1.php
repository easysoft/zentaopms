<?php
declare(strict_types=1);

namespace zin;
class twinsStory extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array(
        'productType?: string="normal"', // 产品类型。
        'branchItems?: array',           // 分支列表。
        'defaultBranch?: int=0',         // 默认分支。
        'moduleItems?: array',           // 模块列表。
        'defaultModule?: int=0',         // 默认模块。
        'planItems?: array',             // 计划列表。
        'defaultPlan?: int=0',           // 默认计划。
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build()
    {
        global $lang, $config;
        list($productType, $branchItems, $defaultBranch, $moduleItems, $defaultModule, $planItems, $defaultPlan) = $this->prop(array('productType', 'branchItems', 'defaultBranch', 'moduleItems', 'defaultModule', 'planItems', 'defaultPlan'));

        return div
        (
            setClass('twinsStoryBox'),
            div
            (
                setClass('switchBranch'),
                formGroup
                (
                    set::style(array('padding' => '0', 'padding-right' => "var(--form-grid-gap-x-half);")),
                    set::width('1/2'),
                    set::label(sprintf($lang->product->branch, $lang->product->branchName[$productType]) . '&' . $lang->story->module),
                    row
                    (
                        cell
                        (
                            set::width('1/2'),
                            setClass('w-1/2'),
                            setID('branchBox'),
                            picker
                            (
                                setID('branches_0'),
                                set::name('branches[0]'),
                                set::items($branchItems),
                                set::value($defaultBranch),
                                setData(array('index' => 0, 'on' => 'change', 'call' => 'loadBranchRelation', 'params' => 'event'))
                            )
                        ),
                        cell
                        (
                            set::width('1/2'),
                            setClass('w-1/2'),
                            setID('moduleIdBox'),
                            picker(setID('modules_0'), set::name('modules[0]'), set::items($moduleItems), set::value($defaultModule), set::required(true))
                        )
                    )
                ),
                formGroup
                (
                    set::style(array('padding' => '0', 'padding-left' => "var(--form-grid-gap-x-half);")),
                    set::width('1/2'),
                    set::label($lang->story->plan),
                    set::required(strpos(",{$config->story->create->requiredFields},", ",plan,") !== false),
                    inputGroup
                    (
                        setID('planIdBox'),
                        picker(setID('plans_0'), set::name('plans[0]'), set::items($planItems), set::value($defaultPlan))
                    )
                ),
                !empty($branchItems) && count($branchItems) > 1 ? formGroup
                (
                    setClass('c-actions'),
                    btn(setClass('btn-link addNewLine'), setData(array('on' => 'click', 'call' => 'addBranchesBox', 'params' => 'event')), set::title(sprintf($lang->story->addBranch, $lang->product->branchName[$productType])), icon('plus'))
                ) : null
            ),
            div
            (
                setID('storyNoticeBranch'),
                setClass('hidden'),
                set::width('full'),
                div(setClass('text-gray'), icon(setClass('text-warning'), 'help'), set::style(array('font-size' => '12px')), $lang->story->notice->branch)
            ),
            div
            (
                setID('addBranchesBox'),
                setClass('hidden'),
                formGroup
                (
                    set::style(array('padding' => '0', 'padding-right' => "var(--form-grid-gap-x-half);")),
                    set::width('1/2'),
                    row
                    (
                        cell
                        (
                            set::width('1/2'),
                            setClass('w-1/2'),
                            setID('branchBox'),
                            div(setID('branches'), setClass('form-group-wrapper'))
                        ),
                        cell
                        (
                            set::width('1/2'),
                            setClass('w-1/2'),
                            setID('moduleIdBox'),
                            div(setID('modules'), setClass('form-group-wrapper'))
                        )
                    )
                ),
                formGroup
                (
                    set::style(array('padding' => '0', 'padding-left' => "var(--form-grid-gap-x-half);")),
                    set::width('1/2'),
                    inputGroup
                    (
                        setID('planIdBox'),
                        div(setID('plans'), setClass('form-group-wrapper'))
                    )
                ),
                formGroup
                (
                    setClass('c-actions'),
                    btn(setClass('btn-link addNewLine'),    set::title(sprintf($lang->story->addBranch,    $lang->product->branchName[$productType])), icon('plus')),
                    btn(setClass('btn-link removeNewLine'), set::title(sprintf($lang->story->deleteBranch, $lang->product->branchName[$productType])), icon('trash'))
                )
            )
        );
    }
}
