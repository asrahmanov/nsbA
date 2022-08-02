// Компонет для показа заявок без статусов
Vue.component('ordersnotstatus', {
    data() {
        return {
            orders: [],
         }
    },

    methods: {
        load () {
            this.static = [];
            this.$parent.getJson(`../dashboard/GetNotStatus`)
                .then(data => {
                    let i = 0;
                    for (let key in data.result) {
                        this.orders.push(data.result[key]);
                    }
                });
        },

    },

    mounted() {
        this.load();
    },
    template: `
<div> 
<a  v-for="item in orders" :href="'../orders/info/?idFR='+ item.proj_id " target="_blank" class="ml-1">{{ item.internal_id }} </a>
</div>


 `

});



