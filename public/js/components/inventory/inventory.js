Vue.component('inventory', {
    data() {
        return {

            inventory: [],
            filterinventory: [],


            // siteFilter - для поиск сайта
            textFilter: '',
            internal_id: '',
            proj_id: '',


        }
    },

    methods: {


        load() {
            this.$parent.getJson(`../invertory/getAll`)
                .then(data => {
                    let i = 0;
                    for (let key in data.result) {
                        this.inventory.push(data.result[key]);
                        this.filterinventory.push(data.result[key]);
                    }
                    this.filter('all');

                //     setTimeout(() => {
                //     $("#table").dataTable()
                //     }, 500)
                //
                // });
        },

        filter(value) {


            if(value == 'all') {
                this.filterinventory = this.inventory;
            } else {
                this.filterinventory = this.inventory.filter(el => {
                    if(el.sample == value ) {
                        return true
                    }
                });
            }


        },

        filterInternal() {
            let regexp = new RegExp(this.proj_id, 'i');
            this.filterinventory = this.inventory.filter(el => regexp.test(el.proj_id));
        },

        statusText(val) {

            if(val == '0') {
                return 'Новая'
            }

            if(val == '1') {
                return 'Обработан'
            }

            if(val == '2') {
                return 'Отказ'
            }
        },


    },



    mounted() {
        this.load();
        // Отображаем новые





    },

    template: `

<div> 

<button class="btn btn-dark" @click="filter(0)">Новые</button>
<button class="btn btn-outline-info ml-1" @click="filter(1)">Обработаны</button>
<button class="btn btn-outline-danger ml-1" @click="filter(2)">Отказы</button>
<button class="btn btn-outline-dark ml-1" @click="filter('all')">ВСЕ</button>

<table class="table table-striped table-bordered file-export dataTable" id="table">
    <thead>
    
    <tr>
        <th class="mobile-none">№</th>
        <th class="mobile-none">Date</th>
        <th><input type="text" placeholder="proj id" class="form-control" v-model="proj_id" @input="filterInternal()"></th>
        <th class="mobile-none">Internal Id</th>
        <th class="mobile-none">External Id</th>
        <th class="mobile-none">project name</th>
        <th class="mobile-none">deadline</th>
        <th class="mobile-none">Status</th>
        <th>action</th>
    </tr>
       
    </tr>
    </thead>
    <tbody>
    <tr v-for="item in filterinventory">
        <td>{{ item.id }}</td>
        <td>{{ item.fr_date }}</td>
        <td>{{ item.proj_id }}</td>
        <td>{{ item.internal_id }}</td>
        <td>{{ item.external_id }}</td>
        <td>{{ item.project_name }}</td>
        <td>{{ item.deadline }}</td>
        <td>{{ statusText(item.sample) }}</td>
        <td><a  target="_blank"  :href="'../orders/info/?idFR='+ item.proj_id" class="btn btn-success btn-block" >Открыть</a></td>
    </tr>
    </tbody>
</table>       




</div>


 `

});



