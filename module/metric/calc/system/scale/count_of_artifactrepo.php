<?php
/**
 * 按系统统计制品库总数。
 * Count of artifactrepo.
 *
 * 范围：system
 * 对象：artifact
 * 目的：scale
 * 度量名称：按系统统计制品库总数
 * 单位：无
 * 描述：按系统统计的制品库总数是指统计所有产品的制品库总数，它反映了研发团队所管理的制品数量。该度量项可以帮助团队可以评估制品管理的复杂性和效率，并根据需要进行合理的优化和调整。
 * 定义：所有制品库的个数求和;不统计已删除;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_artifactrepo extends baseCalc
{
    public $dataset = 'getArtifactRepos';

    public $fieldList = array('id');

    public $result = 0;

    public function calculate($row)
    {
        $this->result ++;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
