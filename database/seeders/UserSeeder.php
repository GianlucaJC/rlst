<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
	{
		/**
		 * Run the database seeds.
		 *
		 * @return void
		 */
		public function run()
		{

			$user = [

					'name'=>'F0001',
					'email'=>'morescogianluca@gmail.com',
					'password'=> bcrypt('123456'),

				];
			User::create($user);
		}
	}
?>
