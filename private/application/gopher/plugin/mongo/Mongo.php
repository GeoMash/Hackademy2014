<?php
namespace application\gopher\plugin\mongo
{
	use nutshell\behaviour\Native;
	use nutshell\behaviour\Singleton;
	use nutshell\core\plugin\Plugin;
	use MongoClient;
	use MongoDB;
	use MongoCollection;
	use MongoConnectionException;

	class Mongo extends Plugin implements Native, Singleton
	{
		private $db			=null;
		private $collections=[];
		
		
		public static function registerBehaviours(){}
		
		public function __get($key)
		{
			if ($key!='config')
			{
				return $this->getCollection($key);
			}
			else
			{
				return parent::__get($key);
			}
		}
		
		public function init()
		{
			try
			{
				$this->mongoClient=new MongoClient
				(
					sprintf
					(
						'mongodb://%s:%s',
						$this->config->host,
						$this->config->port
					),
					[
						'connectTimeoutMS'=>$this->config->timeout
					]
				);
				$this->db=$this->mongoClient->selectDB($this->config->database);
			}
			catch (MongoConnectionException $exception)
			{
				die($exception->getMessage());
			}
		}
		
		private function getCollection($collection)
		{
			if (!isset($this->collections[$collection]))
			{
				$this->collections[$collection]=$this->db->selectCollection($collection);
			}
			return $this->collections[$collection];
		}
	}
}
?>