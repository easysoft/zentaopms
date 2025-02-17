<?php
declare(strict_types=1);

namespace zin;

class productCharterBox extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array(
        'charter?: object',      // 所属立项。
        'products?: array',      // 产品下拉列表。
        'objectType?: string'    // 立项关联的对象类型。
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
            setData(array('objecttype' => $this->prop('objectType'))),
            jsVar('multiBranchProducts', data('multiBranchProducts')),
            $productsBox
        );
    }

    protected function initProductsBox(): array
    {
        global $lang, $app, $config;
        $products    = $this->prop('products');
        $charter     = $this->prop('charter');
        $objectType  = $this->prop('objectType');
        $productsBox = array();
        $index       = 0;

        if($charter)
        {
            $objectsGroup = array();
            if($objectType == 'plan')    $objectsGroup = $app->control->loadModel('productplan')->getPlansForCharter(explode(',', trim($charter->product, ',')), trim($charter->plan, ','));
            if($objectType == 'roadmap') $objectsGroup = $app->control->loadModel('roadmap')->groupByProduct('nolaunching', trim($charter->roadmap, ','));

            $charterProductMaps = $app->control->loadModel('charter')->getGroupDataByID($charter->id);
            if(empty($charterProductMaps)) $charterProductMaps = array(array()); // 没有产品时，至少渲染一行数据。
            foreach($charterProductMaps as $productID => $objects)
            {
                $productObjects = isset($objectsGroup[$productID]) ? array_keys($objectsGroup[$productID]) : array();
                $objects        = array_intersect($productObjects, array_keys($objects));

                $productsBox[] = div
                (
                    set::className('productBox'),
                    div
                    (
                        set::className('flex'),
                        formGroup
                        (
                            set::width('1/2'),
                            setClass('distributeProduct text-clip'),
                            $index != 0 ? set::labelClass('hidden') : null,
                            set::label($lang->charter->product),
                            inputGroup
                            (
                                div
                                (
                                    setClass('grow linkProduct w-1/2'),
                                    picker
                                    (
                                        setData(array('on' => 'change', 'call' => 'refreshPicker', 'params' => 'event')),
                                        set::name("product[$index]"),
                                        set::items($products),
                                        set::value($productID)
                                    )
                                )
                            )
                        ),
                        formGroup
                        (
                            set::width('1/4'),
                            setClass('hidden linkBranch ml-px'),
                            set::label(''),
                            inputGroup
                            (
                                setClass('branchBox'),
                                picker
                                (
                                    set::name("branch[$index]"),
                                    set::items(array()),
                                    setData(array('on' => 'change', 'call' => 'branchChange', 'params' => 'event'))
                                )
                            )
                        ),
                        formGroup
                        (
                            set::width('1/2'),
                            set::label($lang->charter->$objectType),
                            $config->edition == 'ipd' ? set::labelControl(array('control' => 'icon', 'name' => 'help', 'data-toggle' => 'tooltip', 'data-title' => $lang->custom->charter->tips->type)) : null,
                            set::className("{$objectType}Box"),
                            $index != 0 ? set::labelClass('hidden') : null,
                            inputGroup
                            (
                                div
                                (
                                    setClass('grow w-1/2', $objectType == 'roadmap' ? 'linkRoadmap' : 'linkPlan'),
                                    picker
                                    (
                                        set::name("{$objectType}[$index]"),
                                        set::multiple(true),
                                        set::items(isset($objectsGroup[$productID]) ? $objectsGroup[$productID] : array()),
                                        set::value(array_values($objects))
                                    )
                                ),
                                div
                                (
                                    $objectType == 'roadmap' && common::hasPriv('charter', 'loadRoadmapStories') ? inputGroupAddon
                                    (
                                        setClass('p-0'),
                                        btn
                                        (
                                            setID('loadRoadmapStories'),
                                            setClass('ghost'),
                                            setData(array('on' => 'click', 'call' => 'loadRoadmapStories', 'params' => 'event')),
                                            $lang->charter->loadStories
                                        )
                                    ) : null
                                )
                            )
                        )
                    ),
                    div
                    (
                        set::className('actionsBox'),
                        formGroup
                        (
                            set::label(''),
                            $index != 0 ? set::labelClass('hidden') : null,
                            div
                            (
                                setClass('pl-2 flex self-center line-btn'),
                                btn
                                (
                                    setData(array('on' => 'click', 'call' => 'addNewLine', 'params' => 'event')),
                                    setClass('btn btn-link text-gray addLine'),
                                    icon('plus')
                                ),
                                btn
                                (
                                    setData(array('on' => 'click', 'call' => 'removeLine', 'params' => 'event')),
                                    setClass('btn btn-link text-gray removeLine', $index == 0 && count($charterProductMaps) <= 1 ? 'hidden' : ''),
                                    icon('trash')
                                )
                            )
                        )
                    )
                );
                $index ++;
            }
        }
        else
        {
            $productsBox[] = div
            (
                set::className('productBox'),
                div
                (
                    set::className('flex'),
                    formGroup
                    (
                        set::width('1/2'),
                        setClass('distributeProduct text-clip'),
                        set::label($lang->charter->product),
                        set::labelClass($charter ? 'hidden' : ''),
                        inputGroup
                        (
                            div
                            (
                                setClass('grow linkProduct w-1/2'),
                                picker
                                (
                                    setData(array('on' => 'change', 'call' => 'refreshPicker', 'params' => 'event')),
                                    set::name("product[$index]"),
                                    set::items($products),
                                    set::defaultValue(''),
                                    set::emptyValue('')
                                )
                            )
                        )
                    ),
                    formGroup
                    (
                        set::width('1/4'),
                        setClass('hidden linkBranch ml-px'),
                        set::label(''),
                        inputGroup
                        (
                            setClass('branchBox'),
                            picker
                            (
                                set::name("branch[$index]"),
                                set::items(array()),
                                setData(array('on' => 'change', 'call' => 'branchChange', 'params' => 'event'))
                            )
                        )
                    ),
                    formGroup
                    (
                        set::width('1/2'),
                        set::label($lang->charter->$objectType),
                        set::labelClass($charter ? 'hidden' : ''),
                        $config->edition == 'ipd' ? set::labelControl(array('control' => 'icon', 'name' => 'help', 'data-toggle' => 'tooltip', 'data-title' => $lang->custom->charter->tips->type)) : null,
                        set::className("{$objectType}Box"),
                        inputGroup
                        (
                            div
                            (
                                setClass('grow w-1/2', $objectType == 'roadmap' ? 'linkRoadmap' : 'linkPlan'),
                                picker
                                (
                                    set::name("{$objectType}[$index]"),
                                    set::multiple(true),
                                    set::items(array())
                                )
                            ),
                            div
                            (
                                $objectType == 'roadmap' && common::hasPriv('charter', 'loadRoadmapStories') ? inputGroupAddon
                                (
                                    setClass('p-0'),
                                    btn
                                    (
                                        setID('loadRoadmapStories'),
                                        setClass('ghost'),
                                        setData(array('on' => 'click', 'call' => 'loadRoadmapStories', 'params' => 'event')),
                                        $lang->charter->loadStories
                                    )
                                ) : null
                            )
                        )
                    )
                ),
                div
                (
                    set::className('actionsBox'),
                    count($products) ? formGroup
                    (
                        set::label(''),
                        set::labelClass($charter ? 'hidden' : ''),
                        div
                        (
                            setClass('pl-2 flex self-center line-btn'),
                            btn
                            (
                                setData(array('on' => 'click', 'call' => 'addNewLine', 'params' => 'event')),
                                setClass('btn btn-link text-gray addLine'),
                                icon('plus')
                            ),
                            btn
                            (
                                setData(array('on' => 'click', 'call' => 'removeLine', 'params' => 'event')),
                                setClass('btn btn-link text-gray removeLine', $charter ? '' : 'hidden'),
                                icon('trash')
                            )
                        )
                    ) : null
                )
            );
        }
        return $productsBox;
    }
}
