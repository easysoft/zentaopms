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
        'preProducts?: array',  // 预设产品列表。
        'products?: array',     // 产品下拉列表。
        'branchGroups?: array', // 产品分支分组列表。
        'roadmapGroups?: array' // 产品路标分组列表。
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
            on::change('[name^=addRoadmap]', 'window.addRoadmap'),
            $productsBox
        );
    }

    protected function initProductsBox(): array
    {
        global $lang;
        list($preProducts, $products, $branchGroups, $roadmapGroups) = $this->prop(array('preProducts', 'products', 'branchGroups', 'roadmapGroups'));

        $productsBox = array();
        foreach(array_values($preProducts) as $index => $productID)
        {
            $hasBranch     = !empty($branchGroups[$productID]);
            $branches      = isset($branchGroups[$productID]) ? $branchGroups[$productID] : array();
            $defaultBranch = !empty($branches) ? key($branches) : 0;
            $roadmaps      = !empty($roadmapGroups[$productID][$defaultBranch]) ? $roadmapGroups[$productID][$defaultBranch] : array();

            $productsBox[] = div
            (
                set::className('productBox flex'),
                formGroup
                (
                    set::width('1/2'),
                    setClass('distributeProduct'),
                    set::required(true),
                    $index == 0 ? set::label($lang->demand->distributeProduct) : null,
                    count($products) ? null : set::checkbox(array('text' => $lang->demand->addProduct, 'name' => 'addProduct', 'checked' => false)),
                    on::change('[name=addProduct]', 'addProduct'),
                    inputGroup
                    (
                        div
                        (
                            setClass('grow linkProduct'),
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
                    set::width('1/2'),
                    $index == 0 ? set::label($lang->demand->roadmap) : null,
                    set::className('roadmapBox'),
                    inputGroup
                    (
                        div
                        (
                            setClass('grow linkRoadmap'),
                            picker
                            (
                                set::name("roadmap[$index]"),
                                set::items($roadmaps)
                            ),
                            input
                            (
                                setClass('hidden'),
                                set::disabled(true),
                                set::name("roadmapName[$index]")
                            )
                        ),
                        div
                        (
                            setClass('ml-px addRoadmap btn btn-default' . ((count($roadmaps) || empty($products) || !$productID) ? ' hidden' : '')),
                            checkbox
                            (
                                set::name("addRoadmap[$index]"),
                                set::text($lang->demand->addRoadmap)
                            )
                        )
                    )
                ),
                count($products) ? div
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
                        setClass($index == 0 ? 'hidden' : ''),
                        icon('trash')
                    )
                ) : null
            );
        }

        return $productsBox;
    }
}
