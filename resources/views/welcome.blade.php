<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Billionaire Advisory</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">

        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.0"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body>

    <div id="app" class="container" style="margin-top: 30px">
        <h2 class="text-center">Stock API [Updating every 20secs]</h2>
        <div style="margin-top: 20px" v-if="data.length > 0">
            <table class="table table-responsive table-bordered">
                <thead>
                <tr>
                    <th scope="col">Stock</th>
                    <th scope="col">Market Price</th>
                    <th scope="col">Holding Values</th>
                    <th scope="col">% Of Portfolio</th>
                    <th scope="col">Est Avg Buy Price</th>
                    <th scope="col">Number of shares</th>
                    <th scope="col">Profit / Loss $</th>
                    <th scope="col">Return %</th>
                    <th scope="col">Trade</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in data">
                    <td class="text-uppercase">@{{ item.nameOfIssuer }}</td>
                    <td :class="is_profit(item.changeInShares) ? 'text-success' : 'text-danger'"><i class="bi " :class="is_profit(item.changeInShares) ? 'bi-arrow-up text-success':'bi-arrow-down text-danger'"></i>$@{{ formatValue(item.currentSharePrice) }}</td>
                    <td>
                        $@{{ formatValue(item.value) }}
                    </td>
                    <td>@{{ formatValue(Math.abs(item.changeInWeightPercentage)) }}</td>
                    <td class="text-success">@{{ formatValue(item.pricePaid) }}</td>
                    <td>@{{ formatValue(item.numberOfShares) }}</td>
                    <td><i class="bi " :class="is_profit(item.changeInShares) ? 'bi-arrow-up text-success':'bi-arrow-down text-danger'"></i>
                       @{{ is_profit(item.changeInShares) ? '':'-' }} @{{ item.changeInValuePercentage }}</td>
                    <td :class="is_profit(item.changeInShares) ? 'text-success':'text-danger'">@{{ formatValue(item.changeInSharesPercentage) }}%</td>
                    <td><button class="btn btn-success">Trade</button> </td>
                </tr>

                </tbody>
            </table>
        </div>
        <div v-else style="margin: 200px">
            <h3 class="text-center">Fetching data ...................</h3>
        </div>
    </div>


    <script src="script.js"></script>

    </body>
</html>
