Vue.component('ordersDeadline', {
    data() {
        return {
            // массив заявок
            orders: [],
            ordersFilter: [],
            // масив для компаний
            company: [],
            companyFilter: [],
            //  Массив для ответсвенных
            staff: [],
            // Массив для фильтра по users
            users: [],
            // Массив для имен статусовё
            status: [],

            script_id: 0,
            company_name: '',
            dd : new Date,
            dateOne: this.date_one,
            dateTwo: this.date_one,

            visible: false

        }
    },


    methods: {

        getDate() {
            let today = new Date();
            let tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 3);

            let dd = String(today.getDate()).padStart(2, '0');
            let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            let yyyy = today.getFullYear();
            today = yyyy + '-' + mm + '-' + dd;


            let dd2 = String(tomorrow.getDate()).padStart(2, '0');
            let mm2 = String(tomorrow.getMonth() + 1).padStart(2, '0'); //January is 0!
            let yyyy2 = tomorrow.getFullYear();
            tomorrow = yyyy2 + '-' + mm2 + '-' + dd2;


            this.dateOne = today;
            this.dateTwo = tomorrow;


        },

        openModal() {

            $("#modal").modal('show');

        },


        load() {
            let  url = `../orders/GetbyDateDeadLine/?dateOne=${this.dateOne}&dateTwo=${this.dateTwo}`
            this.orders = [];
            this.ordersFilter = [];
            this.statusLog = [];


            this.$parent.getJson(`${url}`)
                .then(data => {
                    for (let key in data.orders) {
                        this.orders.push(data.orders[key]);
                        this.ordersFilter.push(data.orders[key]);
                    }

                    for (let key in data.company) {
                        this.company.push(data.company[key]);
                    }

                    for (let key in data.staff) {
                        this.staff.push(data.staff[key]);
                    }
                    for (let key in data.status) {
                        this.status.push(data.status[key]);
                    }

                }).then(() => {
                // this.filterOrders();
            });
        },


        getStaff(proj_id) {
            let staff = this.staff.find(el => {
                if (el.proj_id == proj_id) {
                    return el
                }
            })

            if (typeof staff == "undefined") {
                return {name: 'Не выставлен'}

            } else {
                return staff
            }
        },


        getUsers() {
            this.$parent.getJson(`../users/GetUsersByRole/?role=1`)
                .then(data => {
                    for (let key in data.result) {
                        this.users.push(data.result[key]);
                    }
                });
        },



    },



    mounted() {

        this.getUsers();
        this.getDate();
        this.load();
    },

    template: `

<div> 

<div class="row">
 <div class="col-md-4 col-sm-12" >
           <label>Filter DeadLine</label><br>
         <input type="date" v-model="dateOne" class="form-control">
        </div>
        
         <div class="col-md-4 col-sm-12" >
           <label>Filter DeadLine</label><br>
         <input type="date" v-model="dateTwo" class="form-control">
        </div>
        
       <div class="col-md-2 col-sm-12" >
         <label>Load</label><br>
         <button class=" btn btn-dark"  @click="load()">Load</button>
        </div>
</div>



<table class="table table-striped table-bordered file-export dataTable" >
    <thead>
    <tr>
        <th>id</th>
        <th>fr date</th>
        <th>DeadLine date</th>
        <th>Internal id</th>
        <th>External id</th>
        <th>Project name</th>
       
    </tr>
    </tr>
    </thead>
    <tbody>
    <tr v-for="item in ordersFilter" :key="item.proj_id">
         <td><a target="_blank" :href="'../../orders/info/?idFR=' +  item.proj_id"  >{{ item.proj_id }}</a></td>
         <td>{{ item.fr_date }}</td>
         <td>{{ item.deadline }}</td>
         <td>{{ item.internal_id }}</td>
         <td>{{ item.external_id }}</td>
         <td>{{ item.project_name }}</td>
    </tr>
    </tbody>
</table>



</div> `

});



