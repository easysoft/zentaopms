<?php
declare(strict_types=1);
namespace zin;

class pager extends wg
{
    protected static array $defineProps = array
    (
        'type?: string="full"',
        'page?: int',
        'recTotal?: int',
        'recPerPage?: int',
        'linkCreator?: string',
        'items?: array'
    );

    protected function buildProps(string $type = 'full'): void
    {
        global $lang;
        $pager = data('pager');
        $pager->setParams();
        $params = $pager->params;
        foreach($params as $key => $value)
        {
            if(strtolower($key) === 'recperpage') $params[$key] = '{recPerPage}';
            if(strtolower($key) === 'pageid')     $params[$key] = '{page}';
        }

        $props = array();
        $props['page']        = $pager->pageID;
        $props['recTotal']    = $pager->recTotal;
        $props['recPerPage']  = $pager->recPerPage;
        $props['linkCreator'] = createLink($pager->moduleName, $pager->methodName, $params);
        $props['items']       = array
        (
            $type == 'short' ? null : array('type' => 'info', 'text' => $lang->pager->totalCountAB),
            $type == 'short' ? null : array('type' => 'size-menu', 'text' => $lang->pager->pageSizeAB),
            array('type' => 'link', 'hint' => $lang->pager->firstPage, 'page' => 'first', 'icon' => 'icon-first-page'),
            array('type' => 'link', 'hint' => $lang->pager->previousPage, 'page' => 'prev', 'icon' => 'icon-angle-left'),
            array('type' => 'info', 'text' => '{page}/{pageTotal}'),
            array('type' => 'link', 'hint' => $lang->pager->nextPage, 'page' => 'next', 'icon' => 'icon-angle-right'),
            array('type' => 'link', 'hint' => $lang->pager->lastPage, 'page' => 'last', 'icon' => 'icon-last-page'),
        );

        $this->setProp($props);
    }

    protected function build(): zui
    {
        $this->buildProps($this->prop('type'));

        return zui::pager(inherit($this));
    }
}
