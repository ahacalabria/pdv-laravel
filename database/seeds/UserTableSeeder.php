<?php

use Illuminate\Database\Seeder;
use App\Pessoa as Pessoa;
use App\User as User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $p = Pessoa::find(1);
        User::create( [
          'id' => 1,
          'name' => 'clever',
          'email' => $p->email,
          'pessoa_id' => 1,
          'level' => 'root',
          'password' => bcrypt('#cle34ve2')
          ] );
    }
}
