<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'sqlbuilderpicker' . DS . 'v1.php';

class sqlBuilderQueryFilter extends wg
{
    protected static array $defineProps = array(
        'querys?: array'
    );

    protected function buildFormHeader()
    {
        global $lang;
        $headers = array();

        foreach($lang->bi->queryFilterFormHeader as $text)
        {
            $headers[] = div
            (
                setClass('form-header-item font-bold text-center'),
                $text
            )
        }

        return $headers;
    }

    protected function buildFormRows()
    {
        return null;
    }

    protected function build()
    {
        return formBase
        (
            set::actions(array()),
            $this->buildFormHeader(),
            $this->buildFormRows()
        );
    }
}
