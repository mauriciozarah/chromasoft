<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\UsuarioModel;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UsuarioModel::create([
            'nome'   =>   'Mauricio Zaha',
            'email'  =>   'mzaha@hotmail.com',
            'senha'  =>   Hash::make('102030')
        ]);
    }
}
