<?php
declare(strict_types=1);
namespace zin;

class sqlBuilder extends wg
{
    protected static array $defineProps = array(
        'class?: string',
        'steps?: array',
        'requiredSteps?: array=["table"]',
        'data?: object',
        'tableList?: array',
        'url?: string',
        'onUpdate?: function',
        'afterUpdate?: function'
    );

    protected static array $tablesDesc = array();

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function setSteps()
    {
        global $lang;
        list($steps) = $this->prop(array('steps'));

        if(empty($steps))
        {
            $stepList = $lang->bi->builderStepList;
            if(empty($steps)) $this->setProp('steps', array_keys($stepList));
        }
    }

    protected function buildStepBar()
    {
        global $lang;
        list($steps, $requires, $builder) = $this->prop(array('steps', 'requiredSteps', 'data'));

        $stepList = $lang->bi->builderStepList;
        $lastStep = end($steps);
        $selected = $builder->step;
        $items    = array();

        $selectedClass = 'text-primary ring-secondary font-bold selected';
        $defaultClass  = 'text-gray-900 ring-opacity-0 font-medium';

        foreach($steps as $step)
        {
            if(!isset($stepList[$step])) continue;

            $key  = $step;
            $text = $stepList[$key];

            $isSelected = $selected == $key;
            $required   = in_array($key, $requires);

            $classList = array();
            $classList[$selectedClass] = $isSelected;
            $classList[$defaultClass]  = !$isSelected;
            $classList['required'] = $required;

            $items[] = btn
            (
                setClass('builder-step-btn relative text-md mx-2 bg-inherit ring', $classList),
                set('data-step', $key),
                set::type('default'),
                $text,
                on::click()->do("switchBuilderStep(event)")
            );

            if($key != $lastStep) $items[] = icon
            (
                setClass('self-center text-gray-500 text-lg leading-3'),
                'angle-down'
            );
        }

        return div
        (
            setClass('builder-step-bar flex col justify-evenly basis-40 gap-1 bg-primary-50 h-full'),
            $items
        );
    }

    protected function buildJoins()
    {
        global $lang;
        list($builder, $tableList) = $this->prop(array('data', 'tableList'));
        $joins = $builder->joins;
        if(empty($joins)) return null;

        $selectTableList = $builder->getSelectTables($tableList);

        $items = array();
        foreach($joins as $index => $join)
        {
            $leftTable = $join['table'];
            $alias     = $join['alias'];
            $on        = $join['on'];

            list($columnA, $columnB) = array($on[0], $on[3]);

            $columnAItems = $builder->getTableDescList($columnA);
            $columnBItems = $builder->getTableDescList($columnB);

            $items[] = formRow
            (
                setClass('gap-x-4 mt-4'),
                sqlBuilderPicker
                (
                    set::name("left_$alias"),
                    set::label($lang->bi->leftTable),
                    set::items($tableList),
                    set::value($leftTable),
                    set::placeholder($lang->bi->selectTableTip),
                    set::suffix($alias),
                    set::width('50'),
                    set::labelWidth('60px'),
                    set::onChange('changeBuilderTable(event)'),
                    set::error($builder->hasError('join', 'table', $alias))
                ),
                joinCondition
                (
                    set::index($index),
                    set::name("join_$alias"),
                    set::values($on),
                    set::tables($selectTableList),
                    set::fieldAList($columnAItems),
                    set::fieldBList($columnBItems),
                    set::onChange('changeBuilderTable(event)'),
                    set::onAdd('addJoinTable(event)'),
                    set::onRemove('removeJoinTable(event)'),
                    set::columnAError($builder->hasError('join', 'columnA', $alias)),
                    set::fieldAError($builder->hasError('join', 'fieldA', $alias)),
                    set::fieldBError($builder->hasError('join', 'fieldB', $alias))
                )
            );
        }

        return $items;
    }

    protected function buildTableStep()
    {
        global $lang;
        list($builder, $tableList) = $this->prop(array('data', 'tableList'));
        $from = $builder->from;

        return array
        (
            formRow
            (
                sqlBuilderPicker
                (
                    set::name("from"),
                    set::label($lang->bi->fromTable),
                    set::items($tableList),
                    set::value($from['table']),
                    set::placeholder($lang->bi->selectTableTip),
                    set::suffix($from['alias']),
                    set::width('50'),
                    set::labelWidth('60px'),
                    set::onChange('changeBuilderTable(event)'),
                    set::error($builder->hasError('from', 'table', $from['alias']))
                ),
                div
                (
                    setClass('flex justify-start items-center'),
                    btn
                    (
                        setID('addJoinTable'),
                        setClass('ml-2 text-primary'),
                        set('data-index', -1),
                        set::type('ghost'),
                        set::icon('plus'),
                        $lang->bi->leftTable,
                        on::click()->do('addJoinTable(event)')
                    ),
                    sqlBuilderHelpIcon(set::text($lang->bi->leftTableTip))
                )
            ),
            $this->buildJoins()
        );
    }

    protected function buildFieldStep()
    {
        global $lang;
        list($builder, $tableList) = $this->prop(array('data', 'tableList'));
        $from    = $builder->from;
        $joins   = $builder->joins;

        $tables = array_merge(array($from), $joins);
        $panels = array();
        foreach($tables as $table)
        {
            $name   = \zget($tableList, $table['table']);
            $alias  = $table['alias'];
            $fields = $builder->getTableDescList($alias);
            $select = $table['select'];

            $panels[] = fieldSelectPanel
            (
                set::table($name),
                set::alias($alias),
                set::fields($fields),
                set::values($select),
                set::col(count($tables)),
                set::onChange('handleSelectFieldChange(event)'),
                set::onSelectAll('checkAllField(event)')
            );
        }
        return div
        (
            setClass('flex row gap-4 h-74'),
            $panels
        );
    }

    protected function buildFuncStep()
    {
        global $lang;
        list($builder, $tableList) = $this->prop(array('data', 'tableList'));
        $funcs = $builder->getFuncs('func');
        if(empty($funcs)) return sqlBuilderEmptyContent
        (
            set::btnClass('add-function'),
            set::btnText($lang->bi->addFunc),
            set::emptyText($lang->bi->emptyFuncs),
            set::onClick('addFunction(event)')
        );

        $selectTableList = $builder->getSelectTables($tableList);
        $items = array();
        foreach($funcs as $index => $func)
        {
            $fieldList = $builder->getTableDescList($func['table']);
            if(!isset($fieldList[$func['field']])) $func['field'] = '';
            $items[] = sqlBuilderFuncRow
            (
                set::index($index),
                set::tables($selectTableList),
                set::fields($fieldList),
                set::value($func),
                set::onChange('changeBuilderFunc(event)'),
                set::onAdd('addFunction(event)'),
                set::onRemove('removeFunction(event)'),
                set::tableError($builder->hasError('func', 'table', $index)),
                set::fieldError($builder->hasError('func', 'field', $index)),
                set::functionError($builder->hasError('func', 'function', $index)),
                set::aliasError($builder->hasError('func', 'alias', $index)),
                set::duplicateError($builder->hasError('func', 'duplicate', $index))
            );
        }

        return $items;
    }

    protected function buildWhereStep()
    {
        global $lang;
        list($builder, $tableList) = $this->prop(array('data', 'tableList'));
        $wheres = $builder->wheres;
        if(empty($wheres)) return sqlBuilderEmptyContent
        (
            set::btnClass('add-where'),
            set::btnText($lang->bi->addWhere),
            set::emptyText($lang->bi->emptyWheres),
            set::onClick('addWhereGroup(event)')
        );

        $selectTableList = $builder->getSelectTables($tableList);
        $groups = array();
        foreach($wheres as $index => $group)
        {
            $items    = $group['items'];
            $operator = $group['operator'];
            $isLast   = $index == count($wheres) - 1;

            $groupItems = array();
            foreach($items as $itemIndex => $item)
            {
                $fieldList  = $builder->getTableDescList($item[0]);
                $groupItems[] = sqlBuilderWhereItem
                (
                    set::first($itemIndex === 0),
                    set::index("{$index}_{$itemIndex}"),
                    set::tables($selectTableList),
                    set::fields($fieldList),
                    set::value($item),
                    set::onChange('changeBuilderWhere(event)'),
                    set::onAdd('addWhereItem(event)'),
                    set::onRemove('removeWhereItem(event)'),
                    set::tableError($builder->hasError('where', "{$index}_{$itemIndex}_0")),
                    set::fieldError($builder->hasError('where', "{$index}_{$itemIndex}_1")),
                    set::valueError($builder->hasError('where', "{$index}_{$itemIndex}_4")),
                );
            }

            $groups[] = sqlBuilderWhereGroup
            (
                set::index($index),
                set::operator($operator),
                set::last($isLast),
                set::onChange('changeBuilderWhere(event)'),
                set::onAdd('addWhereGroup(event)'),
                set::onRemove('removeWhereGroup(event)'),
                $groupItems
            );
        }

        return $groups;
    }

    protected function buildQueryStep()
    {
        global $lang;
        list($builder, $tableList) = $this->prop(array('data', 'tableList'));
        $querys  = $builder->querys;
        if(empty($querys)) return sqlBuilderEmptyContent
        (
            set::btnClass('add-query'),
            set::btnText($lang->bi->addQuery),
            set::emptyText($lang->bi->emptyQuerys),
            set::onClick('addBuilderQueryFilter(event)')
        );

        $selectTableList = $builder->getSelectTables($tableList);
        $fields       = array();
        foreach($querys as $query)
        {
            if(!empty($query['table'])) $fields[$query['table']] = $builder->getTableDescList($query['table']);
        }
        return sqlBuilderQueryFilter
        (
            set::querys($querys),
            set::tables($selectTableList),
            set::fields($fields),
            set::defaultItems($builder->queryFilterSelectOptions),
            set::onChange('changeBuilderQuery(event)'),
            set::onAdd('addBuilderQueryFilter(event)'),
            set::onRemove('removeBuilderQueryFilter(event)'),
            set::error($builder->error)
        );
    }

    protected function buildGroupStep()
    {
        global $lang;
        list($builder) = $this->prop(array('data'));
        $groups = $builder->groups;
        $aggs   = $builder->getFuncs('agg');
        if(empty($groups)) return sqlBuilderEmptyContent
        (
            set::btnClass('hidden'),
            set::emptyText($lang->bi->emptyGroups)
        );
        return sqlBuilderGroupBy
        (
            set::groups($groups),
            set::aggs($aggs),
            set::onChangeAgg('changeAgg(event)'),
            set::onChangeType('switchGroupFieldType(event)'),
            set::onSort('sortGroupBy(event)')
        );
    }

    protected function buildStepContent()
    {
        global $lang;
        list($builder) = $this->prop(array('data'));
        $step   = $builder->step;
        $groups = $builder->groups;

        $ucStep = ucfirst($step);
        $contentTitle    = $lang->bi->{"step{$ucStep}Title"};
        $contentTitleTip = $lang->bi->{"step{$ucStep}Tip"};
        $contentFuncName = "build{$ucStep}Step";
        return panel
        (
            setID("builder$step"),
            setClass('w-full builder-content'),
            set::title($contentTitle),
            set::headingClass('justify-start gap-0'),
            set::bodyClass('h-80 overflow-auto'),
            to::heading
            (
                sqlBuilderHelpIcon
                (
                    set::text($contentTitleTip)
                ),
                formGroup
                (
                    setClass('items-center-important', array('hidden' => $step != 'group')),
                    set::label($lang->bi->enable),
                    set::labelClass('p-0'),
                    set::labelWidth('36px'),
                    switcher
                    (
                        setID('useGroup'),
                        set::name('useGroup'),
                        set::checked($groups === false ? false : true),
                        on::change()->do('changeGroupBy(event)')
                    )
                ),
                span
                (
                    setClass('text-danger', array('hidden' => !isset($builder->error['select_field']))),
                    $lang->bi->emptySelect
                )
            ),
            $this->$contentFuncName()
        );
    }

    protected function build()
    {
        global $lang;
        $this->setSteps();

        list($class, $data, $url, $onUpdate, $afterUpdate) = $this->prop(array('class', 'data', 'url', 'onUpdate', 'afterUpdate'));

        return panel
        (
            setID('builderPanel'),
            set('data-url', $url),
            set('data-onupdate', $onUpdate),
            setClass('h-96', $class),
            set::bodyClass('flex h-96'),
            div
            (
                setID('sqlBuilder'),
                setClass('hidden'),
                set('data-sqlbuilder', $data),
                set('data-afterupdate', $afterUpdate),
                div
                (
                    on::init()->do($afterUpdate)
                )
            ),
            $this->buildStepBar(),
            $this->buildStepContent()
        );
    }
}
