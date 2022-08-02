Vue.component('laboratory', {
    data() {
        return {
            laboratory: [],
            filterlaboratory: [],
            // siteFilter - для поиск сайта
            textFilter: '',
            proj_id: '',
        }
    },

    methods: {
        load() {
            this.$parent.getJson(`../laboratory/getAll`)
                .then(data => {
                    let i = 0;
                    for (let key in data.result) {
                        this.laboratory.push(data.result[key]);
                        this.filterlaboratory.push(data.result[key]);
                    }
                    this.filter(0);
                });
        },

        filter(value) {
            this.filterlaboratory = this.laboratory.filter(el => {
                if(el.sample == value ) {
                    return true
                }
            });
        },

        filterInternal() {
            let regexp = new RegExp(this.proj_id, 'i');
            this.filterlaboratory = this.laboratory.filter(el => regexp.test(el.proj_id));
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

<table class="table table-striped table-bordered file-export dataTable">
    <thead>
    
    <tr>
        <th class="mobile-none">№</th>
        <th class="mobile-none">Date</th>
        <th><input type="text" placeholder="proj id" class="form-control" v-model="proj_id" @input="filterInternal()"></th>
        <th class="mobile-none">Internal Id</th>
        <th class="mobile-none">External Id</th>
        <th class="mobile-none">Project name</th>
        <th class="mobile-none">Deadline</th>
        <th>action</th>
    </tr>
       
    </tr>
    </thead>
    <tbody>
    <tr v-for="item in filterlaboratory">
        <td>{{ item.id }}</td>
        <td>{{ item.fr_date }}</td>
        <td>{{ item.proj_id }}</td>
        <td>{{ item.internal_id }}</td>
        <td>{{ item.external_id }}</td>
        <td>{{ item.project_name }}</td>
        <td>{{ item.deadline }}</td>
        <td><a  target="_blank"  :href="'../orders/info/?idFR='+ item.proj_id" class="btn btn-success btn-block" >Открыть</a></td>
    </tr>
    </tbody>
</table>       



</div>


 `

});



