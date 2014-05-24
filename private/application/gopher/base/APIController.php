<?php
namespace application\gopher\base
{
	use nutshell\plugin\mvc\Controller;

	class APIController extends Controller
	{
		public function getAllResults($cursor)
		{
			$resultSet=[];
			while($cursor->hasNext())
			{
				$resultSet[]=$cursor->getNext();
			}
			return $resultSet;
		}
		
		public function respond($data=null,$success=true)
		{
			$this->plugin	->Responder('json')
							->setData
							(
								[
									'success'	=>$success,
									'data'		=>$data
								]
							)
							->send();
		}
	}
}
?>