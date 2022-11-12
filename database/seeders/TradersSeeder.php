<?php

namespace Database\Seeders;

use App\Models\Trader;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TradersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $traders = [
            1 => [
                'name' => 'A2Stakes',
                'uid' => 'D64DDD2177FA081E3F361F70C703A562',
            ],
            2 => [
                'name' => 'Anonymous User-29e293',
                'uid' => 'ECCA7DC4F94AA8C37D6DA819397A93A9',
            ],
            3 => [
                'name' => 'Anonymous User-4eb68',
                'uid' => '91CDA0C27F7A387F7FDA5D0CA4781194',
            ],
            4 => [
                'name' => 'BCW',
                'uid' => 'FFEDA67C7194EB6C57E2B4445E838AC9',
            ],
            5 => [
                'name' => 'Anonymous User-77fd01',
                'uid' => 'B7AD1F58CD810F805C09337D34DD3D2E',
            ],
            6 => [
                'name' => 'Anonymous User-eff33',
                'uid' => '49A7275656A7ABF56830126ACC619FEB',
            ],
            7 => [
                'name' => 'TreeOfAlpha1',
                'uid' => 'FB23E1A8B7E2944FAAEC6219BBDF8243',
            ],
            8 => [
                'name' => 'snowball_kyd',
                'uid' => 'D2EE8B6D70AAC0181B6D0AB857D6EF60',
            ],
            9 => [
                'name' => 'nRavensky-Ciel',
                'uid' => '20D6A8AE696C8BB969B67BE3ACA6C02A',
            ],
            10 => [
                'name' => 'Nothingness',
                'uid' => '8D27A8FA0C0A726CF01A7D11E0095577',
            ],
        ];


        foreach ($traders as $trader) {
            Trader::create([
                'name' => $trader['name'],
                'uid' => $trader['uid'],
            ]);
        }
    }
}

