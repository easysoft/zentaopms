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
            on::change('.productsBox [name^=product]', 'window.loadBranches'),
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
            $defaultBranch = !empty($branches) ? current($branches) : 0;

            $productsBox[] = div
            (
                set::className('productBox flex'),
                formGroup
                (
                    set::width('1/2'),
                    setClass('linkProduct'),
                    set::required(true),
                    $index == 0 ? set::label($lang->demand->distributeProduct) : null,
                    inputGroup
                    (
                        div
                        (
                            setClass('grow'),
                            picker
                            (
                                set::name("product[$index]"),
                                set::value($productID),
                                set::items($products),
                                set::last($productID),
                                $hasBranch ? set::lastBranch($branches ? 0 : implode(',', $branches)) : null,
                            )
                        ),
                        div
                        (
                            setClass('ml-px linkBranch'),
                            $hasBranch ? null : setClass('hidden'),
                            picker
                            (
                                set::width('100px'),
                                set::name("branch[$index]"),
                                set::items($branches),
                                set::value(current($branches)),
                                on::change("")
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
                        set::id("roadmap{$index}"),
                        picker
                        (
                            set::name("roadmap[$index]"),
                            set::items($roadmapGroups[$productID][$defaultBranch])
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
