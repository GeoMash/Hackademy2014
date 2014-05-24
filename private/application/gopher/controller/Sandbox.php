<?php
namespace application\gopher\controller
{
	use nutshell\plugin\mvc\Controller;

	class Sandbox extends Controller
	{
		public function index()
		{
			
		}
		
		public function testMongoConnection()
		{
			$cursor=$this->plugin->Mongo->tasks->find(['user_id'=>'test']);
			$cursor=$this->collection->tasks->find(['user_id'=>'test']);
			$resultSet=[];
			while($cursor->hasNext())
			{
				$resultSet[]=$cursor->getNext();
			}
			$this->plugin	->Responder('json')
							->setData($resultSet)
							->send();
		}
	}
}
?>