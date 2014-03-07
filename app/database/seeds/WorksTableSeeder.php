<?php

class WorksTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('works')->delete();

		$works = array(
			[	'title' => 'Emu Staring into the Sunset',
				'sm_description' => 'Ain\'t no other way',
				'lg_description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a,',
				'img_order' => '/1/2/3',
				'thumbnail_filepath' => '/images/works/placeholder_300.gif',
				'thumbnail_filepath' => '/images/works/placeholder_3002x.gif',
				'featured_filepath' => null,
				'featured' => false
			]
		);

		// Uncomment the below to run the seeder
		DB::table('works')->insert($works);
	}

}
