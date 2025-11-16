<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::updateOrCreate(
            ['edrpou' => '37027819'],
            [
                'name' => 'ТОВ Українська енергетична біржа',
                'address' => '01001, Україна, м. Київ, вул. Хрещатик, 44',
            ]
        );

        $company->storeVersion($company);
    }
}
