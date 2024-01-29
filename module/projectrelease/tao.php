<?php
declare(strict_types=1);
class projectreleaseTao extends projectreleaseModel
{
    /**
     * 处理项目发布信息，包括分支名称、版本信息等。
     * Process release.
     *
     * @param  object    $release
     * @param  array     $branchGroup
     * @param  array     $builds
     * @access protected
     * @return void
     */
    protected function processRelease(object $release, array $branchGroup, array $builds): void
    {
        $release->project = trim($release->project, ',');
        $release->branch  = trim($release->branch, ',');
        $release->build   = trim($release->build, ',');

        $release->branchName = $this->getBranchName($release->product, $release->branch, $branchGroup);

        $release->buildInfos = array();
        foreach(explode(',', $release->build) as $buildID)
        {
            if(empty($buildID)) continue;
            if(!isset($builds[$buildID])) continue;

            $build = $builds[$buildID];
            $build->branchName = $this->getBranchName($build->product, $build->branch, $branchGroup);
            $release->buildInfos[$buildID] = $build;
        }
    }

    /**
     * 获取分支名称。
     * Get branch name.
     *
     * @param  int     $productID
     * @param  string  $branch
     * @param  array   $branchGroup
     * @access private
     * @return string
     */
    private function getBranchName(int $productID, string $branch, array $branchGroup): string
    {
        $branchName = '';
        if(!isset($branchGroup[$productID])) return $branchName;

        $branches = $branchGroup[$productID];
        foreach(explode(',', $branch) as $branchID)
        {
            if($branchID == '') continue;

            $branchName .= zget($branches, $branchID, '');
            $branchName .= ',';
        }
        return trim($branchName, ',');
    }
}
