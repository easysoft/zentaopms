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
        'charter?: object',     // 所属立项。
        'products?: array'      // 产品下拉列表。
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
            on::click('#loadRoadmapStories', 'window.loadRoadmapStories'),
            on::change('.linkProduct .pick-value', 'window.refreshPicker(e.target)'),
            $productsBox
        );
    }

    protected function initProductsBox(): array
    {
        global $lang, $app;
        $products    = $this->prop('products');
        $charter     = $this->prop('charter');
        $productsBox = array();
        $index       = 0;

        if($charter)
        {
            $roadmapGroup       = $app->control->loadModel('roadmap')->groupByProduct('nolaunching');
            $charterProductMaps = $app->control->loadModel('charter')->getGroupDataByID($charter->id);
            foreach($charterProductMaps as $productID => $roadmaps)
            {
                $nolaunchRoadmaps = array_keys($roadmapGroup[$productID]);
                $roadmaps         = array_intersect($nolaunchRoadmaps, array_keys($roadmaps));

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
                            set::required(true),
                            set::label($lang->charter->product),
                            inputGroup
                            (
                                div
                                (
                                    setClass('grow linkProduct w-1/2'),
                                    picker
                                    (
                                        set::name("product[$index]"),
                                        set::items($products),
                                        set::value($productID)
                                    )
                                )
                            )
                        ),
                        formGroup
                        (
                            set::width('1/2'),
                            set::label($lang->charter->roadmap),
                            set::className('roadmapBox'),
                            $index != 0 ? set::labelClass('hidden') : null,
                            set::required(true),
                            inputGroup
                            (
                                div
                                (
                                    setClass('grow linkRoadmap w-1/2'),
                                    picker
                                    (
                                        set::name("roadmap[$index]"),
                                        set::multiple(true),
                                        set::required(true),
                                        set::items($roadmapGroup[$productID]),
                                        set::value($roadmaps)
                                    )
                                ),
                                div
                                (
                                    common::hasPriv('charter', 'loadRoadmapStories') ? inputGroupAddon
                                    (
                                        setClass('p-0'),
                                        btn
                                        (
                                            setID('loadRoadmapStories'),
                                            setClass('ghost'),
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
                                    setClass('btn btn-link text-gray addLine'),
                                    icon('plus')
                                ),
                                btn
                                (
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
                        set::required(true),
                        set::label($lang->charter->product),
                        set::labelClass($charter ? 'hidden' : ''),
                        inputGroup
                        (
                            div
                            (
                                setClass('grow linkProduct w-1/2'),
                                picker
                                (
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
                        set::width('1/2'),
                        set::label($lang->charter->roadmap),
                        set::labelClass($charter ? 'hidden' : ''),
                        set::className('roadmapBox'),
                        set::required(true),
                        inputGroup
                        (
                            div
                            (
                                setClass('grow linkRoadmap w-1/2'),
                                picker
                                (
                                    set::name("roadmap[$index]"),
                                    set::multiple(true),
                                    set::required(true),
                                    set::items(array())
                                )
                            ),
                            div
                            (
                                common::hasPriv('charter', 'loadRoadmapStories') ? inputGroupAddon
                                (
                                    setClass('p-0'),
                                    btn
                                    (
                                        setID('loadRoadmapStories'),
                                        setClass('ghost'),
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
                                setClass('btn btn-link text-gray addLine'),
                                icon('plus')
                            ),
                            btn
                            (
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
