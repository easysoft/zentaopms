<?php
declare(strict_types=1);
/**
 * The model file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: model.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<?php
class projectreleaseModel extends model
{
    /**
     * 获取项目发布列表。
     * Get list of releases.
     *
     * @param  int    $projectID
     * @param  string $type
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(int $projectID, string $type = 'all', string $orderBy = 't1.date_desc', object $pager = null): array
    {
        $releases = $this->dao->select('t1.*, t2.name AS productName, t2.type AS productType')->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere("FIND_IN_SET($projectID, t1.project)")
            ->beginIF($type != 'all' && $type != 'review')->andWhere('t1.status')->eq($type)->fi()
            ->beginIF($type == 'review')->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        $buildIdList   = array();
        $productIdList = array();
        foreach($releases as $release)
        {
            $buildIdList = array_merge($buildIdList, explode(',', $release->build));
            $productIdList[$release->product] = $release->product;
        }

        $branchGroup = $this->loadModel('branch')->getByProducts($productIdList);
        $builds      = $this->dao->select("id, project, product, branch, execution, name, scmPath, filePath")->from(TABLE_BUILD)->where('id')->in(array_unique($buildIdList))->fetchAll('id');

        foreach($releases as $release) $this->projectreleaseTao->processRelease($release, $branchGroup, $builds);

        return $releases;
    }

    /**
     * 获取最新的发布。
     * Get last release.
     *
     * @param  int         $projectID
     * @access public
     * @return bool|object
     */
    public function getLast(int $projectID): object|bool
    {
        return $this->dao->select('id, name')->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->andWhere("FIND_IN_SET({$projectID}, project)")
            ->orderBy('date DESC')
            ->limit(1)
            ->fetch();
    }

    /**
     * 获取项目已发布的版本。
     * Get released builds from project.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getReleasedBuilds(int $projectID): array
    {
        /* Get release. */
        $releases = $this->dao->select('shadow,build')->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->andWhere("FIND_IN_SET({$projectID}, project)")
            ->fetchAll();

        /* Get released builds. */
        $buildIdList = '';
        foreach($releases as $release) $buildIdList .= ",{$release->build},{$release->shadow}";
        $buildIdList = explode(',', trim($buildIdList, ','));
        return array_unique($buildIdList);
    }

    /**
     * 判断按钮是否可以点击。
     * Judge button is clickable or not.
     *
     * @param  object $release
     * @param  string $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable(object $release, string $action): bool
    {
        $action = strtolower($action);

        if($action == 'notify') return $release->bugs or $release->stories;
        if($action == 'play')   return $release->status == 'terminate';
        if($action == 'pause')  return $release->status == 'normal';
        return true;
    }
}
