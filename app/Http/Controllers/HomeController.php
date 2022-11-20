<?php

namespace App\Http\Controllers;

use App\Models\Trader;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Lin\Bybit\BybitLinear;

class HomeController extends Controller
{
    public function index()
    {

        $key = 'n3BEJcF4w6DEENnBOh';
        $secret = 'LsLK3Wel45ryR4fPQrLwMGfltoChqtQF5sZU';
        $bybit = new BybitLinear();

        $bybit = new BybitLinear($key, $secret);


        try {
            $result = $bybit->privates()->getOrderList([
                'symbol' => 'SFPUSDT',
            ]);
            dd($result);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }

//        try {
//            $result=$bybit->privates()->getPositionList([
//                'symbol'=>'SFPUSDT',
//            ]);
//            dd($result);
//        }catch (\Exception $e){
//            print_r($e->getMessage());
//        }


//        try {
//            $result=$bybit->publics()->getKline([
//                'symbol'=>'BTCUSDT',
//                'interval'=>'15',
//                'from'=>time()-3600,
//            ]);
//            print_r($result);
//        }catch (\Exception $e){
//            print_r($e->getMessage());
//        }
//
//        try {
//            $result=$bybit->publics()->getTickers();
//            print_r($result);
//        }catch (\Exception $e){
//            print_r($e->getMessage());
//        }
//
//        try {
//            $result=$bybit->publics()->getRecentTradingRecords([
//                'symbol'=>'BTCUSDT',
//                'limit'=>'5',
//            ]);
//            dd($result);
//        }catch (\Exception $e){
//            print_r($e->getMessage());
//        }
//
//        try {
//            $result=$bybit->publics()->getSymbols();
//            print_r($result);
//        }catch (\Exception $e){
//            print_r($e->getMessage());
//        }


        $traders = Trader::all();

        return view('home.index', [
            'traders' => $traders
        ]);
    }
}
