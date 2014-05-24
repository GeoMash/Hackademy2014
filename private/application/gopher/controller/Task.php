<?php
namespace application\gopher\controller
{
	use application\gopher\base\APIController;

	class Task extends APIController
	{
		public function index()
		{
			$cursor		=$this->collection->task->find();
			$resultSet	=$this->getAllResults($cursor);
			$this->respond($resultSet);
		}
		
		public function getByUserId($userId)
		{
			$cursor		=$this->collection->task->find(['user_id'=>$userId]);
			$resultSet	=$this->getAllResults($cursor);
			$this->respond($resultSet);
		}
		
		public function add()
		{
			$userId	=$this->request->get('user_id');
			$steps	=$this->request->get('steps');
			$this->collection->task->insert
			(
				[
					'requested_user_id'	=>$userId,
					'steps'				=>$steps
				]
			);
			$this->respond();
		}
	}
}
?>