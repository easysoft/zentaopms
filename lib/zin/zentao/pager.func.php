<?php
declare(strict_types=1);
namespace zin;

/**
 * Create settings for using pager widget.
 *
 * @param string|array $pagerName
 * @param string $extra
 * @param ?array $userSetting
 * @return array
 */
function usePager(string|array $pagerName = 'pager', string $extra = '', ?array $userSetting = null): ?array
{
    if(is_array($pagerName))
    {
        $userSetting = $pagerName;
        $pagerName = 'pager';
    }

    $pager = data($pagerName);
    if(empty($pager)) return null;

    $pager->setParams();
    $params = $pager->params;
    foreach($params as $key => $value)
    {
        if(strtolower($key) === 'recperpage') $params[$key] = '{recPerPage}';
        if(strtolower($key) === 'pageid')     $params[$key] = '{page}';
    }

    $setting = array();
    $setting['page']        = $pager->pageID;
    $setting['recTotal']    = $pager->recTotal;
    $setting['recPerPage']  = $pager->recPerPage;
    $setting['linkCreator'] = createLink($pager->moduleName, $pager->methodName, $params);
    $setting['items']       = array();
    $setting['gap']         = 0;

    if($pager->recTotal == 0)
    {
        $setting['items'][] = array('type' => 'info', 'text' => $pager->lang->pager->noRecord);
    }
    elseif($extra == 'noTotalCount')
    {
        $setting['items'][] = array('type' => 'size-menu', 'text' => str_replace('<strong>', '', str_replace('</strong>', '', $pager->lang->pager->pageSize)), 'dropdown' => array('placement' => 'top'));
        $setting['items'][] = array('type' => 'link', 'page' => 'prev', 'hint' => $pager->lang->pager->previousPage, 'icon' => 'icon-angle-left');
        $setting['items'][] = array('type' => 'link', 'page' => 'next', 'hint' => $pager->lang->pager->nextPage, 'icon' => 'icon-angle-right');
    }
    elseif($extra == 'shortPageSize')
    {
        $setting['items'][] = array('type' => 'size-menu', 'text' => str_replace('<strong>', '', str_replace('</strong>', '', $pager->lang->pager->shortPageSize)), 'dropdown' => array('placement' => 'top'), 'itemProps' => array('onClick' => jsRaw("(e, item) => $.cookie.set('$pager->pageCookie', item.key)")));
        $setting['items'][] = array('type' => 'link', 'page' => 'prev', 'hint' => $pager->lang->pager->previousPage, 'icon' => 'icon-angle-left');
        $setting['items'][] = array('type' => 'info', 'text' => '{page}/{pageTotal}');
        $setting['items'][] = array('type' => 'link', 'page' => 'next', 'hint' => $pager->lang->pager->nextPage, 'icon' => 'icon-angle-right');
    }
    else
    {
        $setting['items'][] = array('type' => 'info', 'text' => $pager->lang->pager->totalCountAB);
        if(strpos(',short,', ",{$extra},") === false) $setting['items'][] = array('type' => 'size-menu', 'text' => str_replace('<strong>', '', str_replace('</strong>', '', $pager->lang->pager->pageSize)), 'dropdown' => array('placement' => 'top'), 'itemProps' => array('onClick' => jsRaw("(e, item) => $.cookie.set('$pager->pageCookie', item.key)")));
        $setting['items'][] = array('type' => 'link', 'page' => 'first', 'hint' => $pager->lang->pager->firstPage, 'icon' => 'icon-first-page');
        $setting['items'][] = array('type' => 'link', 'page' => 'prev', 'hint' => $pager->lang->pager->previousPage, 'icon' => 'icon-angle-left');
        $setting['items'][] = array('type' => 'info', 'text' => '{page}/{pageTotal}');
        $setting['items'][] = array('type' => 'link', 'page' => 'next', 'hint' => $pager->lang->pager->nextPage, 'icon' => 'icon-angle-right');
        $setting['items'][] = array('type' => 'link', 'page' => 'last', 'hint' => $pager->lang->pager->lastPage, 'icon' => 'icon-last-page');
    }

    if(!empty($userSetting)) $setting = array_merge($setting, $userSetting);

    return $setting;
}
