<?php

use Illuminate\Database\Seeder;

class WheelPrizePatternSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		\App\Models\Wheel\WheelPattern::create(
			[
				'pattern_name' => 'Default',
				'pattern_alias' => 'default'
			]
		);
	}
}
