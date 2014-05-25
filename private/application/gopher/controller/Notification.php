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
			$cursor->sort(['read'=>1]);
			$resultSet	=$this->getAllResults($cursor);
			for ($i=0, $j=count($resultSet); $i<$j; $i++)
			{
				$resultSet[$i]['type']['record']=$this->collection->{$resultSet[$i]['type']['name']}->findOne(['_id'=>$resultSet[$i]['type']['id']]);
			}
			$this->respond($resultSet);
		}
	}
}
?>