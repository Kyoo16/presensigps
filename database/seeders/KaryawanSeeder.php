<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('karyawan')->insert([
            [
                'nik' => '12345',
                'nama_lengkap' => 'Muhammad Zaki',
                'jabatan' => 'Manager',
                'no_hp' => '081234567890',
                'password' => Hash::make('password123'),
                'remember_token' => null,
            ],
            [
                'nik' => '12346',
                'nama_lengkap' => 'Robby Saputra',
                'jabatan' => 'Staff',
                'no_hp' => '081234567891',
                'password' => Hash::make('password123'),
                'remember_token' => null,
            ],
            [
                'nik' => '12347',
                'nama_lengkap' => 'Bagaskara Putra',
                'jabatan' => 'Staff',
                'no_hp' => '081234567892',
                'password' => Hash::make('password123'),
                'remember_token' => null,
            ],
            [
                'nik' => '12348',
                'nama_lengkap' => 'Donald Trump',
                'jabatan' => 'Staff',
                'no_hp' => '081234567893',
                'password' => Hash::make('password123'),
                'remember_token' => null,
            ],
            [
                'nik' => '12349',
                'nama_lengkap' => 'Joko Widodo',
                'jabatan' => 'Staff',
                'no_hp' => '081234567894',
                'password' => Hash::make('password123'),
                'remember_token' => null,
            ],
        ]);
    }
}
