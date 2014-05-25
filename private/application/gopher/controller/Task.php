<?php
namespace application\gopher\controller
{
	use application\gopher\base\APIController;
	use \MongoId;

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
			$task=$this->collection->task->insert
			(
				[
					'requested_user_id'	=>$userId,
					'steps'				=>$steps
				]
			);
			if (isset($steps[0]['geometry']['coordinates']))
			{
				//Find the nearest idiot to do the job.
				$operator=$this->collection->operator->find
				(
					[
						'GeoJSON.geometry.coordinates'=>
						[
							'$near'=>$steps[0]['geometry']['coordinates']
						]
					]
				);
				//Notify him that he has to do a stupid job.
				$this->collection->notification->insert
				(
					[
						'user_id'=>$operator->id,
						'type'=>
						[
							'name'	=>'task',
							'id'	=>new MongoId("53810be93e60d34f2182c9aa")
						],
						'title'		=>"Task Progress",
						'message'	=>"Your Operator has just completed another step!",
						'step'		=>1,
						'read'		=>false
					]
				);
			}
			$this->respond();
		}
	}
}
?>