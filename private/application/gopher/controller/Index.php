<?php
namespace application\gopher\controller
{
	use nutshell\plugin\mvc\Controller;

	class Index extends Controller
	{
		public function index()
		{
			print '<h1>404, Gopher?</h1><br><img src="http://repellex.com/media/wysiwyg/Gopher.JPG">';
		}
	}
}
?>