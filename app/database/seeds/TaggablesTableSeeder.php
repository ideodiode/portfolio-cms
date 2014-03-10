<?php

class TaggablesTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('taggables')->delete();

		$taggables = array(
			[	'tag_id' => '1',
				'taggable_id' => '1',
				'taggable_type' => 'Post'
			],
			[	'tag_id' => '1',
				'taggable_id' => '2',
				'taggable_type' => 'Post'
			],
			[	'tag_id' => '2',
				'taggable_id' => '1',
				'taggable_type' => 'Post'
			],
			[	'tag_id' => '1',
				'taggable_id' => '1',
				'taggable_type' => 'Work'
			],
			[	'tag_id' => '2',
				'taggable_id' => '1',
				'taggable_type' => 'Work'
			]
		);

		// Uncomment the below to run the seeder
		DB::table('taggables')->insert($taggables);
	}

}
