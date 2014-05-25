<?php
namespace application\gopher\controller
{
	use application\gopher\base\APIController;
	use \MongoId;
	use \MongoDate;

	class Task extends APIController
	{
		public function index()
		{
			$cursor		=$this->collection->task->find();
			$resultSet	=$this->getAllResults($cursor);
			$this->respond($resultSet);
		}
		
		public function getByUserId($userId,$type='requested')
		{
			$cursor		=$this->collection->task->find([$type.'_user_id'=>$userId]);
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
			
			$cursor		=$this->collection->operator->find();
			$operators	=$this->getAllResults($cursor);
			$operator	=$operators[mt_rand(0,count($operators)-1)];
			
			//---------- /DEBUG CODE
			
			
			$task=
			[
				'timestamp_created'	=>new MongoDate(time()),
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
		
		public function getTaskSteps($taskId)
		{
			$task=$this->collection->task->findOne(['_id'=>new MongoId($taskId)]);
			$this->respond($task,(bool)$task);
		}
		
		public function setStepStatus($taskId,$stepNumber,$status)
		{
			$task=$this->collection->task->findOne(['_id'=>new MongoId($taskId)]);
			$this->collection->task->update
			(
				['_id'=>new MongoId($taskId)],
				[
					'$set'=>['steps.'.$stepNumber.'.properties.status'=>$status]
				]
			);
			
			$this->collection->notification->insert
			(
				[
					'user_id'=>$task['requested_user_id'],
					'type'=>
					[
						'name'	=>'task',
						'id'	=>$task['_id']
					],
					'title'		=>'Task Progressed',
					'message'	=>'Your Operator has just completed another step!',
					'step'		=>$stepNumber,
					'read'		=>false
				]
			);
			$this->respond();
		}
	}
}
?>