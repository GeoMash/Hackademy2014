<?php
namespace application\gopher
{
	use nutshell\core\application\Application;
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;

	class Gopher extends Application
	{
		const VERSION					='1.0.0';
		const VERSION_MAJOR				=1;
		const VERSION_MINOR				=0;
		const VERSION_MICRO				=0;
		const VERSION_STAGE_NUM			=0;
		
		public function init()
		{
			$this->session=$this->nutshell->plugin->Session;
			
			header('X-Gopher-Version:'.self::VERSION);
			header('X-Nutshell-Version:'.Nutshell::VERSION);
			header('Access-Control-Allow-Origin: *');
			
			$this->plugin->Mongo();
			
			//Bind collections as a shortcut.
			$this->nutshell->registerGetHook
			(
				'collection',
				function()
				{
					return $this->plugin->Mongo();
				}
			);
			
			//Initiate the MVC.
			try
			{
				if(NS_INTERFACE != Nutshell::INTERFACE_CLI)
				{
					header('Content-Type:text/html;');
				}
				$this->nutshell->plugin->Mvc('gopher');
			}
			catch(NutshellException $exception)
			{
				if(NS_INTERFACE != Nutshell::INTERFACE_CLI)
				{
					header('HTTP/1.1 500 DRAGONS!');
				}
				if (Nutshell::getInstance()->config->application->mode == 'development')
				{
					print $exception->getDescription();
				}
			}
		}
		
	}
}
?>