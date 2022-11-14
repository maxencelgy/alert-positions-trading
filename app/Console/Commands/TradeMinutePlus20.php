<?php

namespace App\Console\Commands;

use App\Models\Positions;
use App\Models\Trader;
use App\Notifications\SendNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;


class TradeMinutePlus20 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:positionsplus20';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        sleep(20);
        // LISTE DES TRADERS EN BDD
        $traders = Trader::all();
        foreach ($traders as $trader) {
            //RECUP LES POSITIONS DU TRADER EN COURS
            $positions = Http::post('https://www.binance.com/bapi/futures/v1/public/future/leaderboard/getOtherPosition', [
                'encryptedUid' => $trader->uid,
                'tradeType' => 'PERPETUAL',
            ]);

            //ON MET LES POSITIONS DU TRADER EN COURS DANS UNE VARIABLE
            $positionsBinance = $positions->json()['data']['otherPositionRetList'];

            // SI LE TRADER A DES POSITIONS EN COURS
            if (!empty($positionsBinance)) {
                //ON PARCOURS TOUTES LES POSITIONS DU TRADER
                foreach ($positionsBinance as $position) {
                    //VARIABLE POUR VERIFIER SI LA POSITION EXISTE DEJA EN BDD
                    $tradeExistant = Positions::where('symbol', $position['symbol'])->where('amount', $position['amount'])->first();
                    // SI LA VARIABLE N'EST PAS VIDE, LA POSITION EXISTE DEJA EN BDD ALORS ON UPDATE SEULEMENT SES VALEURS
                    if (!empty($tradeExistant)) {
                        $tradeExistant->update([
                            'markPrice' => $position['markPrice'],
                            'roe' => $position['roe'],
                            'yellow' => $position['yellow'],
                        ]);
                    } else {
                        // LA VARIABLE EST VIDE ALORS IL FAUT CREER LA POSITION EN BDD
                        // ON VERIFIE SI LE TRADE EST UN SHORT OU LONG
                        if ($position['amount'] < 0) {
                            $type = 'short';
                            $emoji = '🔴';
                        } else {
                            $type = 'long';
                            $emoji = '🟢';
                        }
                        // ENVOYER UNE NOTIFICATION TELEGRAM NOUVEAU TRADE DETECTE
                        Notification::route('telegram', '-801413501')
                            ->notify(new SendNotification(
                                '🚨Nouveau trade détecté!

 👑Trader: ' . Trader::where('uid', $trader->uid)->first()->name . '
 🚀Crypto: ' . $position['symbol'] . '
 ' . $emoji . ' Trade: ' . $type . '

 📈Prix d\'entrée: ' . $position['entryPrice'] . '
 💰Levier: x' . $position['leverage'] . ''

                            ));
                        // CREER LA POSITION EN BDD
                        Positions::create([
                            'symbol' => $position['symbol'],
                            'trader_id' => Trader::where('uid', $trader->uid)->first()->id,
                            'entryPrice' => $position['entryPrice'],
                            'markPrice' => $position['markPrice'],
                            'type' => $type,
                            'roe' => $position['roe'],
                            'leverage' => $position['leverage'],
                            'amount' => $position['amount'],
                            'yellow' => $position['yellow'],
                            'existe' => 1,
                            'updateTime' => $position['updateTime'][0] . '/' . $position['updateTime'][1] . '/' . $position['updateTime'][2] . ' ' . $position['updateTime'][3] . ':' . $position['updateTime'][4] . ':' . $position['updateTime'][5],
                        ]);
                    }
                }
            } else {
                // SI LE TRADER N'A PAS DE POSITIONS EN COURS
                // ON VERIFIE SI IL EN A EN BDD QUI LUI CORRESPONDENT
                $positionsEnBdd = Positions::where('trader_id', $trader->id)->get();
                // SI IL Y A DES POSITIONS EN BDD QUI LUI CORRESPONDENT
                if ($positionsEnBdd->isNotEmpty()) {
                    // ON PARCOURS TOUTES LES POSITIONS EN BDD QUI LUI CORRESPONDENT
                    $positionsEnBdd->each(function ($positionEnBdd) {
                        // SI LA POSITION EN BDD A UN ROE SUPERIEUR A 0 LE TRADE EST GAGNANT
                        if ($positionEnBdd['roe'] * 100 > 0) {
                            $emoji = '✅ GAGNANT';
                        } else {
                            $emoji = '❌ PERDANT';
                        }

                        // ON ENVOIE UNE NOTIF TELEGRAM TRADE CLOTURER
                        Notification::route('telegram', '-801413501')
                            ->notify(new SendNotification(
                                '🔒 cloture
 ' . Trader::where('id', $positionEnBdd->trader_id)->first()->name . '
 Crypto :' . $positionEnBdd['symbol'] . '

 📈Prix de cloture: ' . $positionEnBdd['markPrice'] . '
 💸Profit: ' . $positionEnBdd['roe'] * 100 . '%

  ' . $emoji . ''));
                        // ON SUPPRIME LA POSITION EN BDD
                        $positionEnBdd->delete();
                    });
                }
            }


            // COMPARER LES POSITIONS EN BDD AVEC LES POSITIONS DU TRADER EN COURS POUR VOIR SI IL Y A DES POSITIONS QUI ONT ETE FERMEE
            // VARIABLE POSITIONS EN BDD DU TRADER EN COURS
            $positionsEnBdd = Positions::where('trader_id', $trader->id)->get();
            $positionBinanceBdd = null;
            // SI LE TRADER A DES POSITIONS EN BDD
            if ($positionsEnBdd->isNotEmpty()) {
                // ENSUITE ON PARCOURS TOUTES LES POSITIONS BINANCE DU TRADER EN COURS SI IL Y EN A
                if (!empty($positionsBinance)) {
                    foreach ($positionsBinance as $positionBinance) {
                        // VARIABLE POUR TROUVER LA POSITION EN BDD
                        $posProbable = Positions::where('symbol', $positionBinance['symbol'])->where('updateTime', $positionBinance['updateTime'][0] . '/' . $positionBinance['updateTime'][1] . '/' . $positionBinance['updateTime'][2] . ' ' . $positionBinance['updateTime'][3] . ':' . $positionBinance['updateTime'][4] . ':' . $positionBinance['updateTime'][5])->first();
                        if (isset($posProbable)) {
                            $positionBinanceBdd = $posProbable;
                        } else {
                            $positionBinanceBdd = null;
                        }
                    }
                }
                foreach ($positionsEnBdd as $position) {
                    if ($positionBinanceBdd != null) {
                        $positionBinanceBdd->update([
                            'existe' => 1,
                        ]);
                    } else {
                        $position->update([
                            'existe' => 0,
                        ]);
                    }
                }

                // ON REPARCOURS LES POSITIONS DU TRADER EN BDD
                foreach ($positionsEnBdd as $position) {
                    // SI LA POSITION EXISTE PAS EN BDD LE TRADE EST FERME
                    if ($position->existe == 0) {
                        // SI LA POSITION A UN ROE SUPERIEUR A 0 LE TRADE EST GAGNANT
                        if ($position->roe * 100 > 0) {
                            $emoji = '✅ GAGNANT';
                        } else {
                            $emoji = '❌ PERDANT';
                        }

                        // ON ENVOIE UNE NOTIF TELEGRAM TRADE CLOTURER
                        Notification::route('telegram', '-801413501')
                            ->notify(new SendNotification(
                                '🔒 cloture
 ' . Trader::where('id', $position->trader_id)->first()->name . '
 Crypto :' . $position['symbol'] . '

 📈Prix de cloture: ' . $position['markPrice'] . '
 💸Profit: ' . $position['roe'] * 100 . '%

  ' . $emoji . ''));
                        // ON SUPPRIME LA POSITION EN BDD
                        $position->delete();
                    }
                }
            }
        }

    }
}
