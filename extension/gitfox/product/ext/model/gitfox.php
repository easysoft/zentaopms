<?php
/**
 * 获取 GitFox 应用列表。
 * Get the GitFox application list.
 *
 * @param  string $status
 * @param  string $orderBy
 * @param  string $keyword
 * @param  ?object $pager
 * @param  int     $spaceID
 * @access public
 * @return array
 */
public function getProductsInGitfox(string $status, string $orderBy, string $keyword, ?object $pager, int $spaceID = 0): array
{
    $productIds = array();
    if($spaceID)
    {
        $productIds = $this->loadModel('space')->getProductsBySpace($spaceID);
        if(empty($productIds)) return array();
    }

    $sql = $this->dao->select('*')->from(TABLE_PRODUCT)
        ->where('deleted')->eq(0)
        ->andWhere('shadow')->eq(0)
        ->beginIF(strpos($status, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
        ->beginIF($status != 'noclosed' && $status == 'closed')->andWhere('status')->eq('closed')->fi()
        ->beginIF($keyword)->andWhere('name')->like("%{$keyword}%")->fi();

    if($spaceID) $sql->andWhere('id')->in($productIds);

    return $sql->filterTpl('skip')
        ->orderBy($orderBy)
        ->page($pager)
        ->fetchAll('id', false);
}

/**
 * 编辑应用后同步 zt_system 中的对应应用。
 * Sync the system record of an application after editing it.
 *
 * @param  int    $productID
 * @param  string $productName
 * @access public
 * @return bool
 */
public function updateSystemsByProduct(int $productID, string $productName): bool
{
    $systemID = $this->dao->select('id')->from(TABLE_SYSTEM)
        ->where('product')->eq($productID)
        ->andWhere('deleted')->eq(0)
        ->fetch('id');
    if(!$systemID) return true;

    $this->loadModel('system');

    $system = new stdclass();
    $system->name       = $productName;
    $system->editedBy   = $this->app->user->account;
    $system->editedDate = helper::now();

    return $this->system->update((int)$systemID, $system, 'edit');
}

/**
 * 删除应用后同步 zt_system 中的对应应用。
 * Sync the system record of an application after deleting it.
 *
 * @param  int $productID
 * @access public
 * @return bool
 */
public function deleteSystemsByProduct(int $productID): bool
{
    $this->dao->update(TABLE_SYSTEM)
        ->set('deleted')->eq(1)
        ->where('product')->eq($productID)
        ->exec();
    return !dao::isError();
}

/**
 * 删除产品并同步删除其对应应用。
 * Delete the product and sync its system record.
 *
 * @param  int $productID
 * @access public
 * @return bool
 */
public function deleteProductWithSystems(int $productID): bool
{
    $this->dao->begin();

    if(!$this->deleteByID($productID) || !$this->deleteSystemsByProduct($productID))
    {
        return $this->rollback();
    }

    return $this->dao->commit();
}
