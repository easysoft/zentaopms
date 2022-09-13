<?php
helper::importControl('report');
class myReport extends report
{
    public function consumed()
    {
        $this->viewType = "json";
        if (!isset($_POST['data'])) {
            $this->send(array('error' => -1, 'msg' => "bad request"));
            return;
        }
        $data = json_decode($_POST['data']);
        if (!isset($data->projects) || !isset($data->beginDate) || !isset($data->endDate) || strtotime($data->beginDate) > strtotime($data->endDate)) {
            $this->send(array('error' => -2, 'msg' => "bad request , wrong params"));
            return;
        }
        $forAll = count($data->projects) == 0; // 如果project id list 为空 则返回符合条件的所有项目
        if($forAll){
            $res = $this->report->getAllProjectConsumeInfo($data->beginDate,$data->endDate);
        }else{
            $res = $this->report->getProjectConsumeInfoByIdList($data->projects,$data->beginDate,$data->endDate);
        }
        echo json_encode($res);
    }
}