const api_key = "{{ env('API_KEY') }}"
document.addEventListener('DOMContentLoaded', function () {
    new Vue({
        el: '#app',
        data: {
            data : [],
            changer : false, // to simulate a prices change since we are working with static data
            endpoint : 'https://financialmodelingprep.com/api/v4/hedge-fund-portfolio-holdings?cik=0001067983&date=2020-12-31&start=0&count=25&apikey='+api_key
        },
        mounted() {
            this.timer = setInterval(() => {
                this.getData()
            }, 20000)
            this.getData();
        },
        methods: {
            getData(){
                axios.get(this.endpoint).then((response) => {
                    this.data = response.data[0]["2020-12-31"];
                    // this.changer = !this.changer
                })
            },

            formatValue (value) {
                const val = (value / 1).toFixed(2).replace('.', '.')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
            },
            is_profit(val){
                if(this.changer){
                    return val > 0;
                }else{
                    return val <= 0;
                }
            }
        },
        beforeDestroy() {
            clearInterval(this.timer)
        }
    })
})
