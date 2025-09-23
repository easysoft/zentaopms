<?php
class serverroomTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('serverroom');
    }

    /**
     * Test create method.
     *
     * @param  object $roomData
     * @access public
     * @return mixed
     */
    public function createTest(object $roomData)
    {
        $result = $this->objectModel->create($roomData);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test update method.
     *
     * @param  int    $roomID
     * @param  object $roomData
     * @access public
     * @return array|bool
     */
    public function updateTest(int $roomID, object $roomData): array|bool
    {
        $result = $this->objectModel->update($roomID, $roomData);
        if($result) return $result;

        return dao::getError();
    }
}
