<?php

class TaggablesTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('taggables')->delete();

		$taggables = array(
			[	'tag_id' => '1',
				'taggable_id' => '1',
				'taggable_type' => 'post'
			],
			[	'tag_id' => '1',
				'taggable_id' => '2',
				'taggable_type' => 'post'
			],
			[	'tag_id' => '2',
				'taggable_id' => '1',
				'taggable_type' => 'post'
			],
			[	'tag_id' => '1',
				'taggable_id' => '1',
				'taggable_type' => 'work'
			],
			[	'tag_id' => '2',
				'taggable_id' => '1',
				'taggable_type' => 'work'
			]
		);

		// Uncomment the below to run the seeder
		DB::table('taggables')->insert($taggables);
	}

}
