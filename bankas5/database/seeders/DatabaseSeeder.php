<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;


class DatabaseSeeder extends Seeder
{
    private function putRandCode() : string
    {
        $met = rand(1901,2007);
        $men = rand(1, 12);
        $nr = rand(1, 999);
        if(in_array($men,[1,3,5,7,8,10,12])) {
            $dien = rand(1, 31);
        };
        if(in_array($men,[4,6,9,11])) {
            $dien = rand(1, 30);
        };
        if($men == 2) {
            if($met % 4 === 0) {
                $dien = rand(1, 29);
            } else {
                $dien = rand(1, 28);
            }
        }
        if($met > 1999) {
            $ak[] = rand(5, 6);
        } else {
            $ak[] = rand(3, 4);
        }
        $ak[] = floor(($met % 100) / 10);
        $ak[] = $met % 10;
        $ak[] = floor($men / 10);
        $ak[] = $men % 10;
        $ak[] = floor($dien / 10);
        $ak[] = $dien % 10;
        $ak[] = floor($nr / 100);
        $ak[] = floor(($nr % 100) / 10);
        $ak[] = $nr % 10;
        $ks = $ak[0] + $ak[1] * 2 + $ak[2] * 3 +
            $ak[3] * 4 + $ak[4] * 5 + $ak[5] * 6 +
            $ak[6] * 7 + $ak[7] * 8 + $ak[8] * 9 + 
            $ak[9];
        $kss = $ks % 11;    
        if($kss === 10) {
            $ks = $ak[0] * 3 + $ak[1] * 4 + $ak[2] * 5 +
                $ak[3] * 6 + $ak[4] * 7 + $ak[5] * 8 +
                $ak[6] * 9 + $ak[7] + $ak[8] + $ak[9];
            $kss = $ks % 11;
            if($kss === 10) $kss = 0;
        }        
        $ak[] = $kss;
    
        return implode('', $ak);
    }

    
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Briedis',
            'email' => 'briedis@gmail.com',
            'password' => Hash::make('123'),
        ]);
        $faker = Faker::create('lt_LT');
        $iban_time = time();
        foreach(range(1, 50) as $i => $a) {
            $key_prob = rand(1, 100);
            $acc_num = match(true) {
                $key_prob < 6 => 0,
                $key_prob < 61 => 1,
                $key_prob < 86 => 2,
                default => 3,
            };
            DB::table('clients')->insert([
                'name' => $faker->firstName,
                'surname' => $faker->lastName,
                'pid' => self::putRandCode(),
                'accCount' => $acc_num,
            ]);
            if($acc_num > 0) {
                foreach(range(1, $acc_num) as $_) {
                    $key_val = rand(1, 100);
                    $value = match(true) {
                        $key_val < 11 => 0,
                        $key_val < 61 => rand(0, 100000) / 100,
                        $key_val < 91 => rand(100000, 2000000) / 100,
                        default => rand(2000000, 100000000) / 100,
                    };
                    DB::table('accounts')->insert([
                        'iban' => 'LT3306660' . sprintf('%1$011d', $iban_time--),
                        'value' => $value,
                        'client_id' => $i + 1,
                    ]);
                }
            }
        }

    }
}
