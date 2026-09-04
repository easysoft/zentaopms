<?php
/**
 * 获取 GitFox 应用列表。
 * Get the GitFox application list.
 *
 * @param  string $status
 * @param  string $orderBy
 * @param  string $keyword
 * @param  ?object $pager
 * @access public
 * @return array
 */
public function getProductsInGitfox(string $status, string $orderBy, string $keyword, ?object $pager): array
{
    return $this->dao->select('*')->from(TABLE_PRODUCT)
        ->where('deleted')->eq(0)
        ->andWhere('shadow')->eq(0)
        ->beginIF(strpos($status, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
        ->beginIF($status != 'noclosed' && $status == 'closed')->andWhere('status')->eq('closed')->fi()
        ->beginIF($keyword)->andWhere('name')->like("%{$keyword}%")->fi()
        ->filterTpl('skip')
        ->orderBy($orderBy)
        ->page($pager)
        ->fetchAll('id', false);
}
