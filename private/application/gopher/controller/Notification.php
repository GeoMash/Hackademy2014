<?php
namespace application\gopher\controller
{
	use application\gopher\base\APIController;

	class Notification extends APIController
	{
		public function index()
		{
			
		}
		
		public function getByUserId($userId)
		{
			$cursor		=$this->collection->notification->find(['user_id'=>$userId]);
			$resultSet	=$this->getAllResults($cursor);
			$this->respond($resultSet);
		}
	}
}
?>