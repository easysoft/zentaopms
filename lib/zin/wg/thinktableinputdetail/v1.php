<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'thinkstepdetail' . DS . 'v1.php';
/**
 * 思引师表格填空详情部件类。
 * thinmory tableInput detail widget class.
 */

class thinkTableInputDetail extends thinkStepDetail
{
    protected static array $defineProps = array(
        'required?: bool',                  // 是否必填
        'rowsTitle?: array',                // 行标题
        'rowsTitleName: string="fields[]"', // 行标题对应的name
    );
    protected function detailInputControl()
    {
        list($required, $rowsTitle, $rowsTitleName) = $this->prop(array('required', 'rowsTitle', 'rowsTitleName'));
        $tableInputItems = array();
        foreach($rowsTitle as $item)
        {
            $tableInputItems[] = textarea
            (
                set::rows('3'),
                setClass('mt-2'),
                set::name($rowsTitleName),
                set::placeholder($item)
            );
        };

        return div(
            $required ? span(
                setClass('text-xl absolute top-6 text-danger'),
                setStyle(array('left' => '36px')),
                '*'
            ) : null,
            setStyle(array('margin' => '13px 48px 8px')),
            $tableInputItems
        );
    }

    protected function buildBody(): array
    {
        $items   = parent::buildBody();
        $items[] = $this->detailInputControl();
        return $items;
    }
}
