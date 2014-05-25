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
			if (isset($steps[0]['geometry']['coordinates']))
			{
				//Find the nearest idiot to do the job.
				$operator=$this->collection->operator->findOne
				(
					[
						'GeoJSON.geometry.coordinates'=>
						[
							'$near'=>$steps[0]['geometry']['coordinates']
						]
					]
				);
			}
			else if (isset($steps[0]['properties']['address']))
			{
				//TODO: Geocode Address
				
			}
			//---------- DEBUG CODE
			
			$cursor=$this->collection->operator->find();
			$operators	=$this->getAllResults($cursor);
			$operator	=$operators[mt_rand(0,count($operators)-1)];
			
			//---------- /DEBUG CODE
			
			
			$task=
			[
				'requested_user_id'	=>$userId,
				'assigned_user_id'	=>$operator['debugId'],
				'steps'				=>$steps
			];
			$this->collection->task->insert($task);
			
			
			//Notify him that he has to do a stupid job.
			$this->collection->notification->insert
			(
				[
					'user_id'=>$operator['debugId'],
					'type'=>
					[
						'name'	=>'task',
						'id'	=>$task['_id']
					],
					'title'		=>'New Job',
					'message'	=>'You have received a new job. Click to view the first task.',
					'step'		=>0,
					'read'		=>false
				]
			);
			$this->respond();
		}
	}
}
?>