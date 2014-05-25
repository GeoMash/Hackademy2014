<?php
namespace application\gopher\controller
{
	use nutshell\plugin\mvc\Controller;

	class Install extends Controller
	{
		public function index()
		{
			$operators=
			[
				[
					'debugId'=>'testUser2',
					'GeoJSON'=>
					[
						'type'=>'Feature',
						'geometry'=>
						[
							'type'=>'Point',
							'coordinates'=>[101.688136,3.133855]
						],
						'properties'=>
						[
							'name'=>'Joe',
							'ratings'=>[]
						]
					]
				],
				[
					'debugId'=>'testUser3',
					'GeoJSON'=>
					[
						'type'=>'Feature',
						'geometry'=>
						[
							'type'=>'Point',
							'coordinates'=>[101.688536,3.112055]
						],
						'properties'=>
						[
							'name'=>'Bob',
							'ratings'=>[]
						]
					]
				]
			];
			for ($i=0,$j=count($operators); $i<$j; $i++)
			{
				$this->collection->operator->insert($operators[$i]);
				print "Installed Operator ".($i+1)."<br>";
			}
			print "Installation Complete";
		}
	}
}
?>