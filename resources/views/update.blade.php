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
        <h3 class="text-center">Update Stock API [Updating every 10secs]</h3>
        <button class="btn" :class="changer ? 'btn-success' : 'btn-danger'">Price value updated</button>
        <div style="margin-top: 20px" v-if="new_data.length > 0">
            <table class="table table-responsive table-bordered">
                <thead>
                <tr>
                    <th scope="col"></th>
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
                <tr v-for="item in new_data">
                    <td><img :src="item.image" height="50" width="50"/></td>
                    <td class="text-uppercase">
                        @{{ item.companyName }}</td>
                    <td :class="bgColor(item.symbol,item.price)"><i class="bi " :class="is_arrow(item.symbol, item.price)"></i>$@{{ formatValue(item.price) }}</td>
                    <td>
                        $@{{ formatValue(item.price) }}
                    </td>
                    <td></td><td></td><td></td><td></td><td></td>
{{--                    <td>@{{ formatValue(Math.abs(item.changeInWeightPercentage)) }}</td>--}}
{{--                    <td class="text-success">@{{ formatValue(item.pricePaid) }}</td>--}}
{{--                    <td>@{{ formatValue(item.numberOfShares) }}</td>--}}
{{--                    <td><i class="bi " :class="is_profit(item.currentSharePrice) ? 'bi-arrow-up text-success':'bi-arrow-down text-danger'"></i>--}}
{{--                     @{{ item.changeInValuePercentage }}</td>--}}
{{--                    <td :class="is_profit(item.currentSharePrice) ? 'text-success':'text-danger'">@{{ formatValue(item.changeInSharesPercentage) }}%</td>--}}
{{--                    --}}
                    <td><button class="btn btn-success">Trade</button> </td>
                </tr>

                </tbody>
            </table>
        </div>
        <div v-else style="margin: 200px">
            <h3 class="text-center">Fetching data ...................</h3>
        </div>
    </div>


    <script>
        const api_key = "{{ env('API_KEY') }}"
        document.addEventListener('DOMContentLoaded', function () {
            new Vue({
                el: '#app',
                data: {
                    data : [],
                    updated:false,
                    oldData : [],
                    new_data : [],
                    stock : ['AAPL','BAC','KO','AXP','VZ','MCO','USB'],
                    changer : false, // to simulate a prices change since we are working with static data
                    endpoint : 'https://financialmodelingprep.com/api/v4/hedge-fund-portfolio-holdings?cik=0001067983&date=2020-12-31&start=0&count=25&apikey='+api_key
                },
                mounted() {
                    this.timer = setInterval(() => {
                        this.getData()
                    }, 10000)
                    this.getData();
                },
                methods: {
                    getData(){
                        let newData = []
                        if(this.data.length > 0){
                            this.oldData = this.data;
                        }
                        this.stock.forEach(function (i) {
                                axios.get('https://financialmodelingprep.com/api/v3/profile/'+i+'?apikey='+api_key).then((response) => {
                                    newData.push(response.data[0])
                                });
                        })
                        this.data = newData;
                        this.changer = !this.changer
                    },
                    formatValue (value) {
                        const val = (value / 1).toFixed(2).replace('.', '.')
                        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
                    },
                    is_profit(s,val){
                        const  f_val = localStorage.getItem(s)
                        return f_val > val;
                    },
                    bgColor(s, val){
                        const  f_val = localStorage.getItem(s)
                        if(f_val != null && f_val != val && this.oldData.length > 0){
                            if(val > f_val){
                                return 'bg-success text-white'
                            }else {
                                return 'bg-danger text-white'
                            }
                        }else {
                            return 'text-black'
                        }
                    },
                    is_arrow(s, val){
                        const  f_val = localStorage.getItem(s)
                        if(f_val != null && f_val != val && this.oldData.length > 0){
                            if(val > f_val){
                                return 'bi-arrow-up text-white'
                            }else {
                                return 'bi-arrow-down text-white'
                            }
                        }else {
                            return ' '
                        }
                    }
                },
                beforeDestroy() {
                    clearInterval(this.timer)
                },
                watch: {
                    oldData: {
                        handler(){
                            this.oldData.forEach(function(item){
                                localStorage.setItem(item.symbol, item.price)
                            });
                            if(this.data.length === this.stock.length){
                                this.new_data = this.data
                            }
                        },
                    },
                    data: {
                        handler(){
                            if(this.data.length === this.stock.length){
                                this.new_data = this.data
                            }
                        },
                    },
                }
            })
        })
    </script>
    </body>
</html>
