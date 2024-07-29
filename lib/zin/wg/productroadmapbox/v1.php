<?php
declare(strict_types=1);

namespace zin;

class productRoadmapBox extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array(
        'preProducts?: array',       // 预设产品列表。
        'products?: array',          // 产品下拉列表。
        'branchGroups?: array',      // 产品分支分组列表。
        'roadmapPlanGroups?: array', // 产品路标分组列表。
        'storyGrades?: array'        // 需求层级列表
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
        $productsBox = $this->initProductsBox();

        return div
        (
            setClass('productsBox'),
            on::click('.productsBox .addLine', 'window.addNewLine'),
            on::click('.productsBox .removeLine', 'window.removeLine'),
            on::change('.linkProduct .pick-value', 'window.refreshPicker(e.target)'),
            on::change('[name^=branch]', 'window.loadBranch'),
            on::change('[name^=storyGrade]', 'window.loadRoadmapPlans'),
            $productsBox
        );
    }

    protected function initProductsBox(): array
    {
        global $lang;
        list($preProducts, $products, $branchGroups, $roadmapPlanGroups, $storyGrades) = $this->prop(array('preProducts', 'products', 'branchGroups', 'roadmapPlanGroups', 'storyGrades'));

        $productsBox = array();
        foreach(array_values($preProducts) as $index => $productID)
        {
            $hasBranch     = !empty($branchGroups[$productID]);
            $branches      = isset($branchGroups[$productID]) ? $branchGroups[$productID] : array();
            $defaultBranch = !empty($branches) ? key($branches) : 0;
            $roadmapPlans  = !empty($roadmapPlanGroups[$productID][$defaultBranch]) ? $roadmapPlanGroups[$productID][$defaultBranch] : array();

            /* Add label for options. */
            $roadmapPlanItems = array();
            foreach($roadmapPlans as $value => $text)
            {
                $type      = strpos($value, '-') !== false ? substr($value, 0, strpos($value, '-')) : 'roadmap';
                $labelName = $type == 'roadmap' ? $lang->roadmap->common : $lang->productplan->shortCommon;
                $roadmapPlanItems[] = array('value' => $value, 'text' => $text, 'leading' => array('html' => "<span class='label gray-pale rounded-xl clip'>{$labelName}</span> "));
            }

            $productsBox[] = div
            (
                set::className('productBox flex'),
                formGroup
                (
                    set::width('1/3'),
                    setClass('distributeProduct text-clip'),
                    set::required(true),
                    set::label($lang->demand->distributeProduct),
                    $index != 0 ? set::labelClass('hidden') : null,
                    count($products) ? null : set::checkbox(array('text' => $lang->demand->addProduct, 'name' => 'addProduct', 'checked' => false)),
                    on::change('[name=addProduct]', 'addProduct'),
                    inputGroup
                    (
                        div
                        (
                            setClass('grow linkProduct w-1/2'),
                            picker
                            (
                                set::name("product[$index]"),
                                set::value($productID),
                                set::items($products),
                                set::last($productID),
                                $hasBranch ? set::lastBranch($branches ? 0 : implode(',', $branches)) : null,
                            ),
                            input
                            (
                                setClass('hidden'),
                                set::disabled(true),
                                set::name("productName")
                            )
                        ),
                        div
                        (
                            setClass('ml-px linkBranch'),
                            $hasBranch ? null : setClass('hidden'),
                            picker
                            (
                                set::name("branch[$index]"),
                                set::items($branches),
                                set::value($defaultBranch),
                                set::emptyValue('')
                            )
                        )
                    )
                ),
                formGroup
                (
                    set::className('storyGradeBox'),
                    set::width('1/5'),
                    set::label($lang->demand->storyGrade),
                    $index != 0 ? set::labelClass('hidden') : null,
                    picker
                    (
                        set::className('storyGrade'),
                        set::name("storyGrade[$index]"),
                        set::required(true),
                        set::items(isset($storyGrades[$productID]) ? $storyGrades[$productID] : array())
                    )
                ),
                formGroup
                (
                    set::width('1/2'),
                    set::label($lang->demand->roadmapOrPlan),
                    $index != 0 ? set::labelClass('hidden') : null,
                    set::className('roadmapBox text-clip'),
                    inputGroup
                    (
                        div
                        (
                            setClass('grow linkRoadmap w-1/2'),
                            picker
                            (
                                set::name("roadmap[$index]"),
                                set::items($roadmapPlanItems)
                            )
                        )
                    )
                ),
                count($products) ? formGroup
                (
                    set::label(''),
                    set::labelClass('hidden'),
                    set::className('actionsBox'),
                    div
                    (
                        setClass('pl-2 flex self-center line-btn c-actions', $index == 0 ? 'first-action' : ''),
                        btn
                        (
                            setClass('btn btn-link text-gray addLine'),
                            icon('plus')
                        ),
                        btn
                        (
                            setClass('btn btn-link text-gray removeLine'),
                            setClass($index == 0 && count(array_filter($preProducts)) <= 1 ? 'hidden' : ''),
                            icon('trash')
                        )
                    )
                ) : null
            );
        }

        return $productsBox;
    }
}
