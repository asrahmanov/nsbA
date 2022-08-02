Vue.component('communication', {
    data() {
        return {
            // массив заявок
            orders: [],
            ordersFilter: [],
            // масив для статуса
            statusLog: [],
            //  Массив для ответсвенных
            staff: [],
            // Массив для фильтра по users
            users: [],
            // выбранный в фильтре
            answering_id: this.user_id,

            day: 'Today'
        }
    },

    props: ['user_id'],

    methods: {


        load() {
            this.orders = [];
            this.ordersFilter = [];
            this.statusLog = [];
            let url = '';
            if (this.day == 'Today') {
                url = '../ordersCommunication/GetCommunication'
            } else {
                url = '../ordersCommunication/GetCommunication/?day=Future'
            }

            this.$parent.getJson(`${url}`)
                .then(data => {
                    for (let key in data.orders) {
                        this.orders.push(data.orders[key]);
                        this.ordersFilter.push(data.orders[key]);
                    }

                    for (let key in data.statusLog) {
                        this.statusLog.push(data.statusLog[key]);
                    }

                    for (let key in data.staff) {
                        this.staff.push(data.staff[key]);
                    }
                }).then(() => {
                    this.filterOrders();
            });
        },

        getDateStatusLog(proj_id) {
            return date = this.statusLog.find(el => {
                if (el.proj_id == proj_id) {
                    return el
                }
            })
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

        save(item) {

            let data = {
                communication_comment: item.communication_comment,
                communication_date: item.communication_date,
                proj_id: item.proj_id

            }

            this.$parent.putJson(`../orders/save`, data)
                .then(response => {
                    this.load();
                })
        },

        clear(item) {

            let data = {
                communication_date: '_',
                proj_id: item.proj_id
            }

            this.$parent.putJson(`../orders/save`, data)
                .then(response => {
                    this.load();
                })
        },

        getUsers() {
            this.$parent.getJson(`../users/GetUsersByRole/?role=1`)
                .then(data => {
                    for (let key in data.result) {
                        this.users.push(data.result[key]);
                    }
                });
        },

        filterOrders() {

            let check = this.users.find(el => el.id == this.answering_id)
            if(typeof(check) == 'undefined') {
                this.answering_id = 0
            }

            if (this.answering_id == 0) {
                this.ordersFilter = this.orders
            } else {
                this.ordersFilter = this.orders.filter(el => {
                    if (el.answering_id === this.answering_id) {
                        return true
                    }
                })
            }

        }


    },

    computed: {
        classObject: function (priority) {
            return 'priority_' + priority
        }
    },


    mounted() {

        this.load();
        this.getUsers();
    },

    template: `

<div> 


  <div class="row">
        <div class="col-4 mb-4" >
           <label>Filter</label><br>
           <select class="form-control" v-model="answering_id" @change="filterOrders()">
               <option value="0">All</option>
               <option v-for="user in users" :value="user.id">{{ user.firstname }} {{ user.lasttname}}</option>
           </select>
        </div>
        
         <div class="col-4 mb-4" >
           <label>Filter</label><br>
           <select class="form-control" v-model="day" @change="load()">
               <option value="Today">Today</option>
               <option value="Future">Future</option>
           </select>
        </div>
        </div>


<table class="table table-bordered">
    <thead>
    <tr>
        <th>id</th>
        <th>Date of qoute</th>
        <th>Internal id</th>
        <th>External id</th>
        <th>Title</th>
        <th>Manager that submitted FR </th>
        <th>Comments</th>
        <th>Date communication</th>
        <th>action</th>
    </tr>
    </tr>
    </thead>
    <tbody>
    <tr v-for="item in ordersFilter" :key="item.proj_id" :class="'priority_' + item.priority">
         <td><a :href="'../../orders/info/?idFR=' +  item.proj_id"  >{{ item.proj_id }}</a></td>
         <td>{{ getDateStatusLog(item.proj_id).created_at }}</td>
         <td>{{ item.internal_id }}</td>
         <td>{{ item.external_id }}</td>
         <td>{{ item.project_name }}</td>
         <td>{{ getStaff(item.proj_id).name }}</td>
         <td><textarea  v-model="item.communication_comment" rows="5"/></textarea></td>
         <td><input type="date" v-model="item.communication_date"></td>
         <td><button  class="btn btn-success" @click="save(item)">Save</button>
         <button  class="btn btn-danger" @click="clear(item)">Clear</button></td>
    </tr>
    </tbody>
</table>
</div>


 `

});



