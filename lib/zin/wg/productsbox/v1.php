<?php
declare(strict_types=1);

namespace zin;

class productsBox extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array(
        'productItems?: array',          // 产品列表。
        'branchGroups?: array',          // 产品分支分组列表。
        'planGroups?: array',            // 产品计划分组列表。
        'roadmapGroups?: array=array()', // 产品路标分组列表。
        'linkedProducts?: array',        // 关联的产品。
        'linkedBranches?: array',        // 关联的分支。
        'currentProduct?: int=0',        // 来源产品ID。
        'currentPlan?: int=0',           // 来源计划。
        'currentRoadmap?: int=0',        // 来源路标。
        'productPlans?: array=array()',  // 同来源计划所属产品的计划列表。
        'project?: object',              // 关联的项目。
        'isStage?: bool',                // 是否是阶段类型。
        'hasNewProduct?: bool=false',    // 是否有新产品。
        'errorSameProducts?: string',    // 选择同一个产品的提示。
        'required?: bool=false',         // 是否是必填。
        'from?: string=project',         // 来源类型。
        'type?: string="plan"',          // 类型。 plan|roadmap
        'selectTip?: string=""',         // 产品下拉提示。
        'hidden?: bool=false'            // 是否隐藏
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
        list($project, $productItems, $linkedProducts, $errorSameProducts, $type, $hidden) = $this->prop(array('project', 'productItems', 'linkedProducts', 'errorSameProducts', 'type', 'hidden'));

        $productsBox = array();

        if((!empty($project->hasProduct) || is_null($project) || $type == 'roadmap') && $linkedProducts)
        {
            $productsBox = $this->buildLinkedProducts($linkedProducts);
        }
        elseif(!empty($project) && empty($project->hasProduct) && !in_array($project->model, array('waterfall', 'kanban', 'waterfallplus')))
        {
            $productsBox = $this->buildOnlyLinkPlans($linkedProducts);
        }
        else
        {
            $productsBox = $this->initProductsBox();
        }

        return div
        (
            setClass('productsBox', $hidden ? ' hidden' : ''),
            jsVar('multiBranchProducts', data('multiBranchProducts')),
            jsVar('project', \zget($project, 'id', 0)),
            jsVar('errorSameProducts', $errorSameProducts),
            $productsBox
        );
    }

    protected function initProductsBox(): array
    {
        global $lang, $app;
        list($productItems, $project, $isStage, $hasNewProduct, $type) = $this->prop(array('productItems', 'project', 'isStage', 'hasNewProduct', 'type'));

        $typeLang     = $type == 'plan' ? $lang->project->associatePlan : $lang->project->manageRoadmap;
        $typeClass    = $type == 'plan' ? 'planBox'    : 'roadmapBox';
        $typeIdAttr   = $type == 'plan' ? 'plan0'      : 'roadmap0';
        $typeNameAttr = $type == 'plan' ? 'plans[0][]' : 'roadmaps[0][]';

        $productsBox   = array();
        $hidden        = !empty($project) && empty($project->hasProduct) ? 'hidden' : '';
        $productsBox[] = $hasNewProduct ? div
        (
            setClass('addProductBox flex hidden'),
            formGroup
            (
                on::change()->call('toggleNewProduct'),
                set::width('1/2'),
                set::checkbox(array('text' => $lang->project->addProduct, 'name' => 'newProduct', 'checked' => false)),
                set::required(true),
                set::label($lang->project->manageProducts),
                set::name('productName')
            )
        ) : null;
        $productsBox[] = div
        (
            set::className("productBox flex items-center $hidden"),
            formGroup
            (
                on::change()->call('toggleNewProduct'),
                set::width('1/2'),
                setClass('linkProduct'),
                set::required($this->prop('required') || ($project && in_array($project->model, array('waterfall', 'waterfallplus')))),
                set::label($lang->project->manageProducts),
                set::labelFor('productBox'),
                $hasNewProduct ? set::checkbox(array('text' => $lang->project->addProduct, 'name' => 'addProduct', 'checked' => false)) : false,
                picker
                (
                    bind::change('loadBranches(event)'),
                    set::name('products[0]'),
                    set::items($productItems),
                    !empty($project) && empty($project->hasProduct) ? set::value(current(array_keys($productItems))) : null,
                    set::placeholder($this->prop('selectTip'))
                ),
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
                        set::name('branch[0][]'),
                        set::items(array()),
                        set::multiple(true),
                        on::change("branchChange")
                    )
                )
            ),
            formGroup
            (
                set::width('1/2'),
                set::label($typeLang),
                set::className($typeClass),
                inputGroup
                (
                    set::id($typeIdAttr),
                    picker
                    (
                        set::name($typeNameAttr),
                        set::items(array()),
                        set::multiple(true)
                    )
                ),
            ),
            ($isStage && isset($project->stageBy) && $project->stageBy == 'product') ? null : div
            (
                setClass('pl-2 flex self-center line-btn c-actions first-action'),
                btn
                (
                    bind::click('addNewLine(event)'),
                    setClass('btn btn-link text-gray addLine'),
                    icon('plus')
                ),
                btn
                (
                    bind::click('removeLine(event)'),
                    setClass('btn btn-link text-gray removeLine'),
                    setClass('hidden'),
                    icon('trash')
                )
            )
        );

        return $productsBox;
    }

    protected function buildOnlyLinkPlans(array $productItems): array
    {
        global $lang;
        list($currentPlan, $productPlans, $from) = $this->prop(array('currentPlan', 'productPlans', 'from'));

        $planProductID = current(array_keys($productItems));
        $productsBox   = array();
        $productsBox[] = $from == 'execution' ? div
        (
            set::className('productBox noProductBox'),
            formGroup
            (
                set::width('1/2'),
                set::label($lang->execution->linkPlan),
                set::className('planBox'),
                picker
                (
                    set::className('pr-2.5'),
                    set::name("plans[{$planProductID}][]"),
                    set::items(!empty($productPlans) ? $productPlans : array()),
                    set::value($currentPlan),
                    set::multiple(true),
                    formHidden("products[{$planProductID}]", $planProductID),
                    formHidden("branch[{$planProductID}][0]", 0)
                )
            ),
            formHidden("products[{$planProductID}]", $planProductID),
            formHidden("branch[{$planProductID}][0]", 0)
        ) : div
        (
            set::className('productBox'),
            formHidden("products[{$planProductID}]", $planProductID),
            formHidden("branch[{$planProductID}][0]", 0)
        );

        return $productsBox;
    }

    protected function buildLinkedProducts(array $linkedProducts): array
    {
        if(empty($linkedProducts)) return array();

        global $lang;
        list($productItems, $branchGroups, $planGroups, $productPlans, $type, $roadmapGroups) = $this->prop(array('productItems', 'branchGroups', 'planGroups', 'productPlans', 'type', 'roadmapGroups'));
        list($linkedBranches, $currentProduct, $currentPlan, $project, $isStage) = $this->prop(array('linkedBranches', 'currentProduct', 'currentPlan', 'project', 'isStage'));

        $unmodifiableProducts = data('unmodifiableProducts') ? data('unmodifiableProducts') : array();

        $typeLang  = $type == 'plan' ? $lang->project->associatePlan : $lang->project->manageRoadmap;
        $typeClass = $type == 'plan' ? 'planBox' : 'roadmapBox';

        $linkedProductsBox = array();
        $hiddenPlusBtn     = $type == 'roadmap' && count($productItems) == count(array_keys($linkedProducts)) ? ' hidden' : '';
        foreach(array_values($linkedProducts) as $i => $product)
        {
            $hasBranch = $product->type != 'normal' && isset($branchGroups[$product->id]);
            $branches  = isset($branchGroups[$product->id]) ? $branchGroups[$product->id] : array();

            $disabledProduct = !empty($project) && (in_array($product->id, $unmodifiableProducts) || $isStage || data('disableModel'));

            $branchIdList = '';
            if(isset($product->branches))             $branchIdList = $product->branches;
            if(!empty($linkedBranches[$product->id])) $branchIdList = is_array($linkedBranches) ? array_keys($linkedBranches[$product->id]) : $linkedBranches[$product->id];

            $objectID = '';
            if(empty($currentProduct) || ($currentProduct != $product->id))
            {
                $objects      = array();
                $objectGroups = $type == 'plan' ? $planGroups : $roadmapGroups;
                if(is_array($branchIdList) && isset($objectGroups[$product->id]))
                {
                    foreach($branchIdList as $branchID)
                    {
                        if(isset($objectGroups[$product->id][$branchID])) $objects += $objectGroups[$product->id][$branchID];
                    }
                }
                if($type == 'roadmap' && empty($product->branches) && !empty($objectGroups[$product->id])) $objects += $objectGroups[$product->id];

                if($type == 'plan')
                {
                    $objectID = isset($product->plans) && is_array($product->plans) ? implode(',', $product->plans) : '';
                }
                elseif($type == 'roadmap')
                {
                    $objectID = isset($product->roadmaps) && is_array($product->roadmaps) ? implode(',', $product->roadmaps) : '';
                }

                $objectID = trim($objectID, ',');
            }
            else
            {
                $plans  = !empty($productPlans) ? $productPlans : array();
                $planID = isset($currentPlan) && isset($productPlans[$currentPlan]) ? $currentPlan : '';

                $roadmaps  = array();
                $roadmapID = '';

                $objects  = $type == 'plan' ? $plans  : $roadmaps;
                $objectID = $type == 'plan' ? $planID : $roadmapID;
            }
            if($objectID && empty($objects)) $objectID = '';

            $linkedProductsBox[] = div
            (
                set::className('productBox flex'),
                formGroup
                (
                    set::width($hasBranch ? '1/4' : '1/2'),
                    setClass('linkProduct'),
                    set::required($this->prop('required') || ($project && in_array($project->model, array('waterfall', 'waterfallplus'))) || $type == 'roadmap'),
                    $i == 0 ? set::label($lang->project->manageProducts) : null,
                    inputGroup
                    (
                        div
                        (
                            setClass('grow w-full'),
                            picker
                            (
                                setData(array('on' => 'change', 'call' => 'loadBranches', 'params' => 'event')),
                                set::name("products[$i]"),
                                set::value($product->id),
                                set::items($productItems),
                                set::last($product->id),
                                set::disabled($disabledProduct),
                                $i === 0 && $type != 'roadmap' ? set::placeholder($this->prop('selectTip')) : null,
                                $hasBranch ? set::lastBranch(empty($product->branches) ? 0 : implode(',', $product->branches)) : null,
                                $disabledProduct ? formHidden("products[$i]", $product->id) : null
                            )
                        )
                    )
                ),
                formGroup
                (
                    set::width('1/4'),
                    setClass('ml-px linkBranch'),
                    count($linkedProductsBox) == 0 ? set::label('') : null,
                    $hasBranch ? null : setClass('hidden'),
                    inputGroup
                    (
                        setClass('branchBox'),
                        picker
                        (
                            set::name("branch[$i][]"),
                            set::items($branches),
                            set::value(is_array($branchIdList) ? implode(',', $branchIdList) : $branchIdList),
                            set::disabled($disabledProduct),
                            set::multiple(true),
                            setData(array('on' => 'change', 'call' => 'branchChange', 'params' => 'event'))
                        )
                    )
                ),
                $disabledProduct ? div
                (
                    setClass('hidden branchBoxHidden'),
                    picker
                    (
                        set::name("branch[$i][]"),
                        set::items($branches),
                        set::multiple(true),
                        set::value(is_array($branchIdList) ? implode(',', $branchIdList) : $branchIdList)
                    )
                ) : null,
                formGroup
                (
                    set::width('1/2'),
                    $i == 0 ? set::label($typeLang) : null,
                    set::required($type == 'roadmap'),
                    $type == 'roadmap' ? set::checkbox(array('text' => $lang->project->linkStoryToProject, 'name' => 'isLinkStory', 'checked' => true)) : null,
                    set::className($typeClass),
                    inputGroup
                    (
                        set::id($type == 'plan' ? "plan{$i}" : "roadmap{$i}"),
                        picker
                        (
                            set::name($type == 'plan' ? "plans[$product->id][]" : "roadmaps[$product->id][]"),
                            set::items($objects),
                            set::value($objectID),
                            set::multiple(true),
                            set::disabled($disabledProduct && $type == 'roadmap')
                        )
                    )
                ),
                $disabledProduct && $type == 'roadmap' ? div
                (
                    setClass('hidden roadmapBoxHidden'),
                    picker
                    (
                        set::name("roadmaps[$product->id][]"),
                        set::items($objects),
                        set::value($objectID),
                        set::multiple(true)
                    )
                ) : null,
                (!empty($project) && $isStage) ? null : div
                (
                    setClass('pl-2 flex self-center line-btn c-actions', $i == 0 ? 'first-action' : ''),
                    btn
                    (
                        bind::click('addNewLine(event)'),
                        setClass("btn btn-link text-gray addLine $hiddenPlusBtn"),
                        icon('plus')
                    ),
                    btn
                    (
                        bind::click('removeLine(event)'),
                        setClass('btn btn-link text-gray removeLine'),
                        setClass(($i == 0 || $disabledProduct || in_array($product->id, $unmodifiableProducts)) ? 'hidden' : ''),
                        icon('trash')
                    )
                )
            );
        }

        return $linkedProductsBox;
    }
}
