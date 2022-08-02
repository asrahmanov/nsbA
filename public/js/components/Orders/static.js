Vue.component('static', {
    data() {
        return {
            static: [],
            staticGood: [],


            // Массив цветов

            colors: ['badge bg-primary float-right','badge bg-info float-right','badge bg-warning float-right','badge bg-success float-right','badge bg-danger float-right'],

            // Массив для пользователей

            users: [],

            // Массив логов по изменениям fr статусов

            statusLog: [],



         urlSections : window.location.href.split("/"),
         socket : io.connect('https://nbs-platforms.ru:8888'),


        }
    },



    methods: {

        load () {
            this.static = [];
            this.$parent.getJson(`../dashboard/static`)
                .then(data => {
                    let i = 0;
                    for (let key in data.result) {
                        this.static.push(data.result[key]);
                    }
                });
        },


    },


    mounted() {
        this.load();
        this.socket.on('reloadDASHBORD', (proj_id) => {
            this.load();
        })
    },

    template: `
<div> 
        <ul class="list-group">
          <li class="list-group-item" v-for="item in static">
           <span class="badge bg-success float-right ml-2" v-if="item.good > 0">{{ item.good }}</span> 
           <span class="badge bg-success float-right ml-2" v-else>0</span> 
           <span class="badge bg-danger float-right">{{ item.all }}</span> {{ item.fio }}
          </li>
        </ul>
</div>


 `

});



