<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user_type_admin = UserType::updateOrCreate(['code' => config('app.user_type_admin'), 'name' => ucfirst(config('app.user_type_admin'))]);
        $user_type_customer = UserType::updateOrCreate(['code' => config('app.user_type_customer'), 'name' => ucfirst(config('app.user_type_customer'))]);

        $user = User::where('email', config('app.admin_user_email'))->first();
        if (!$user) {
            User::create([
                'id_user_type' => $user_type_admin->id,
                'name' => config('app.admin_user_name'),
                'email' => config('app.admin_user_email'),
                'email_verified_at' => now(),
                'password' => Hash::make(config('app.admin_user_pass')),
                'document_type' => 'cpf',
                'document' => '30030030030',
            ]);
        }

        \App\Models\User::factory(10)->create();
    }
}
