<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>TRADING</title>
</head>
<body class="bg-gray-700 text-white">
<div style="max-width: 1200px; margin: 0 auto;">
    <h1 class="mt-[2rem] mb-[4rem] text-center text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
        LES POSITIONS DES MEILLEURS TRADERS EN COURS</h1>

    @foreach($traders as $trader)
        <h2 class="font-extrabold border-b border-gray-200  text-2xl mb-[1rem]">{{ $trader->name }}</h2>
        @foreach(\App\Models\Positions::where('trader_id', $trader->id)->get() as $position)
            <div
                class="block w-[100%] p-6 mr-4 flex items-center justify-between mb-4  bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                {{ $position->symbol  }}
                <p class="ml-4">Levier : {{ $position->leverage }}</p>
                @if($position->roe * 100 > 0)
                    <p class="text-green-400">
                        gains :
                        {{ $position->roe *100 }}%
                    </p>
                @else

                    <p class="text-red-800">
                        gains :
                        {{ $position->roe *100 }}%
                    </p>
                @endif
                <a href="https://www.binance.com/en/futures-activity/leaderboard/user?encryptedUid={{ $trader->uid }}"
                   class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                   target="_blank">Voir sur binance</a>
            </div>
        @endforeach
    @endforeach

</div>
</body>
</html>
