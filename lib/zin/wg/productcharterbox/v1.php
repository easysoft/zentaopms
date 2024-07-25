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
        'products?: array',      // 产品下拉列表。
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
        global $lang;
        $products    = $this->prop('products');
        $productsBox = array();

        $productsBox[] = div
        (
            set::className('productBox flex'),
            formGroup
            (
                set::width('1/2'),
                setClass('distributeProduct'),
                set::required(true),
                set::label($lang->charter->product),
                inputGroup
                (
                    div
                    (
                        setClass('grow linkProduct w-1/2'),
                        picker
                        (
                            set::name("product[0]"),
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
                set::className('roadmapBox'),
                set::required(true),
                inputGroup
                (
                    div
                    (
                        setClass('grow linkRoadmap w-1/2'),
                        picker
                        (
                            set::name("roadmap[0]"),
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
            ),
            count($products) ? formGroup
            (
                set::label(''),
                set::className('actionsBox'),
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
                        setClass('btn btn-link text-gray hidden removeLine'),
                        icon('trash')
                    )
                )
            ) : null
        );

        return $productsBox;
    }
}
