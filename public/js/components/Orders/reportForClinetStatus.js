Vue.component('reportforclinetstatus', {
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

            dateOne: '',
            dateTwo: '',

            visible: false

        }
    },

    // props: ['user_id'],

    methods: {

        buferCopy(){
            const elToBeCopied = document.getElementById('table');
                let range, sel;

                // Ensure that range and selection are supported by the browsers
                if (document.createRange && window.getSelection) {

                    range = document.createRange();
                    sel = window.getSelection();
                    // unselect any element in the page
                    sel.removeAllRanges();

                    try {
                        range.selectNodeContents(elToBeCopied);
                        sel.addRange(range);
                    } catch (e) {
                        range.selectNode(elToBeCopied);
                        sel.addRange(range);
                    }

                    document.execCommand('copy');
                }

                sel.removeAllRanges();


            toastr.success('Element Copied!')

        },

        openModal() {

            $("#modal").modal('show');

        },

        clearsFilter() {
            this.company_name = ''
            this.filterOrders()
        },

        getStatus(id) {
            return status = this.status.find(el => el.id === id);
        },

        load() {
            let url = '../orders/GetAll'
            if(this.dateOne == '' || this.dateTwo == '') {
                 url = '../orders/GetAll'
            } else {
                 url = `../orders/GetbyDate/?dateOne=${this.dateOne}&dateTwo=${this.dateTwo}`
            }

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
                this.filterOrders();
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

        filterOrders(item) {
            if (typeof (item) != "undefined") {
                this.company_name = item.company_name
            }

            if (this.company_name == '') {
                this.ordersFilter = this.orders
            } else {
                let regexp = new RegExp(this.company_name, 'i');
                this.ordersFilter = this.orders.filter(el => regexp.test(el.company_name));
            }

            this.visible = false;
        },

        filterCompany() {

            if (this.company_name == '') {
                this.companyFilter = this.company
            } else {
                let regexp = new RegExp(this.company_name, 'i');
                this.companyFilter = this.company.filter(el => regexp.test(el.company_name));
            }

        },

        getStatusforClient(item) {
            // 1
            if (
                (item.status_manager == 2 || item.status_manager == 3 || item.status_manager == 5)
                && (item.status_project == 7 || item.status_project == 10)
                && (item.status_client != 28 && item.status_client != 29 && item.status_client != 33 && item.status_client != 49 && item.status_client != 38 && item.status_client != 39)
            ) {
                return (
                    {
                        ru: 'Собирается информация о доступности образцов и скорости набора',
                        en: 'Feasibility/collection rate evaluation in progress'

                    })
            }

            //2
            if (
                (item.status_manager == 2 || item.status_manager == 3 || item.status_manager == 5)
                && (item.status_project == 7 || item.status_project == 10 || item.status_project == 11)
                && (item.status_client == 49)
            ) {
                return (
                    {
                        ru: 'Изучается возможность обновления квоты в соответствии с новыми требованиями заказчика',
                        en: 'New or amended requirments are received. Feasibility evaluation in progress'

                    })
            }

            // 3
            if (
                (item.status_manager != 1 && item.status_project == 9)
                && (item.status_client != 49 && item.status_client != 36 && item.status_client != 3 && item.status_client != 27)
            ) {
                return (
                    {
                        ru: 'Требуемые образцы доступны,  готовится квота',
                        en: 'The required samples are available. Proposal  in progress'

                    })
            }

            // 3
            if ((item.status_manager == 4 || item.status_project != 6)
                && (item.status_client != 49 && item.status_client != 36 && item.status_client != 38 && item.status_client != 27)
            ) {
                return (
                    {
                        ru: 'Требуемые образцы доступны,  готовится квота',
                        en: 'The required samples are available. Proposal  in progress'

                    })
            }


            // 4
            if ((item.status_client == 38)) {
                return (
                    {
                        ru: 'Есть вопросы к заказчику',
                        en: 'Questions sent to the Customer'

                    })
            }

            // 5
            if (
                (item.status_manager != 1)
                && (item.status_project != 6)
                && (item.status_boss != 21)
                && (item.status_client == 27)
            ) {
                return (
                    {
                        ru: 'Квота отослана заказчику, ожидается обратная связь',
                        en: 'The quote is provided. A feedback is much appreciated'

                    })
            }

            // 6
            if ((item.status_manager != 1)
                && (item.status_project != 6)
                && (item.status_boss == 44)

            ) {
                return (
                    {
                        ru: 'Квота проходит внутреннее одобрение',
                        en: 'The quote is under internal approval'

                    })
            }
            // 7
            if ((item.status_manager != 1)
                && (item.status_project == 7)
                && (item.status_client == 51)
            ) {
                return (
                    {
                        ru: 'Готовится инвентори',
                        en: 'The inventory will be provided soon'

                    })
            }

            // 8
            if ((item.status_manager != 1)
                && (item.status_project == 8)
                && (item.status_boss == 21)
                && (item.status_client == 30)
            ) {
                return (
                    {
                        ru: 'Инвентори предоставлен. Запросить обратную связь по инвентори',
                        en: 'The inventory was provided. A feedback is much appreciated'

                    })
            }

            return (
                {
                    ru: '-',
                    en: `-`

                })

        }


    },



    mounted() {

        this.load();
        this.getUsers();
        this.filterCompany();
    },

    template: `

<div> 

<div class="row">
 <div class="col-md-4 col-sm-12" >
           <label>Filter date</label><br>
         <input type="date" v-model="dateOne" class="form-control">
        </div>
        
         <div class="col-md-4 col-sm-12" >
           <label>Filter date</label><br>
         <input type="date" v-model="dateTwo" class="form-control">
        </div>
        
       <div class="col-md-2 col-sm-12" >
         <label>Load</label><br>
         <button class=" btn btn-dark"  @click="load()">Load</button>
        </div>
</div>

  <div class="row">
        <div class="col-md-4 col-sm-12" >
           <input  v-model="company_name" @input="filterCompany"  @focus="visible=true"   class="form-control"/>
            
            <div  
            @focus="visible=true" @blur="visible=false"
            v-if="visible" style=" 
            position: absolute; 
            width: 90%;    
            max-height: 300px;
            background-color: #fff;
            border: 1px solid #000;
            overflow-y: auto;
            padding: 0.375rem 0.75rem;
            
            ">
            <ul>
            <li v-for="item in companyFilter" :value="item.company_name">
            <input type="radio"  @click='filterOrders(item)' name="company"  v-if="item.company_name!=company_name"  />
            <input type="radio"  @click='filterOrders(item)' name="company"  checked v-if="item.company_name==company_name"   />
            {{ item.company_name }} -  {{ item.script}}</li>
</ul>
</div>

        </div>
        
        
       <div class="col-md-2 col-sm-12" >
         <button class=" btn btn-dark btn-block"  @click="clearsFilter()">Clear</button>
        </div>    
        <div class="col-md-2 col-sm-12" >
         <button class=" btn btn-info btn-block"  @click="openModal()">Open</button>
        </div>
<!--        -->
<!--         <div class="col-md-4 col-sm-12" >-->
<!--           <label>Filter date</label><br>-->
<!--         <input type="date" v-model="dateTwo" class="form-control">-->
<!--        </div>-->
<!--        -->

  </div>


<table class="table table-striped table-bordered file-export dataTable" >
    <thead>
    <tr>
        <th>id</th>
        <th>fr date</th>
        <th>Internal id</th>
        <th>External id</th>
        <th>Title</th>
        <th>Manager that submitted FR </th>
        <th>Status</th>
        <th>Руководоство</th>
        <th>Менеджер</th>
        <th>Проектный</th>
        <th>ЛТО</th>
        <th>ВЭД</th>
        <th>Клиент</th>
    </tr>
    </tr>
    </thead>
    <tbody>
    <tr v-for="item in ordersFilter" :key="item.proj_id">
         <td><a :href="'../../orders/info/?idFR=' +  item.proj_id"  >{{ item.proj_id }}</a></td>
         <td>{{ item.fr_date }}</td>
         <td>{{ item.internal_id }}</td>
         <td>{{ item.external_id }}</td>
         <td>{{ item.project_name }}</td>
         <td>{{ getStaff(item.proj_id).name }}</td>
         <td>{{ getStatusforClient(item).en }}</td>
         <td>{{ getStatus(item.status_boss).status_name }}</td>
         <td>{{ getStatus(item.status_manager).status_name }}</td>
         <td>{{ getStatus(item.status_project).status_name }}</td>
         <td>{{ getStatus(item.status_lpo).status_name }}</td>
         <td>{{ getStatus(item.status_ved).status_name }}</td>
         <td>{{ getStatus(item.status_client).status_name }}</td>
    </tr>
    </tbody>
</table>


<div class="modal fade text-left" id="modal"  role="dialog" aria-labelledby="myModalLabel4" aria-hidden="true" >
    <div id="modalview">
        <div class="modal-dialog modal-xl" role="document" style="width: 80% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-road2"></i> <b>Report for Client </b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                
                <table class="table" id="table" style="
                font-family: Arial;
                font-size: 15px;
                border: 1px solid #000;
                width: 100%;
                ">
                <thead style="
                background-color: #4074b0;
                color: #fff;
                
                
                ">
                    <th style="text-align: center; vertical-align: center; border: 1px solid #000;"> Date of request</th>
                    <th style="text-align: center; vertical-align: center; border: 1px solid #000;" >Internal id</th>
                    <th style="text-align: center; vertical-align: center; border: 1px solid #000;">External id</th>
                    <th style="text-align: center; vertical-align: center; border: 1px solid #000;">Title</th>
                    <th style="text-align: center; vertical-align: center; border: 1px solid #000;">Manager that submitted FR </th>
                    <th style="text-align: center; vertical-align: center; border: 1px solid #000;">Status</th>
                    </thead>
                    <tbody>
                     <tr v-for="(item , index) in ordersFilter" :key="item.proj_id" style="
                     border: 1px solid #000;
                     
                     "
                     :class="{'tdStyle': index % 2 === 0, 'tdBlue': index % 2 !== 0 }">
                     <td style="text-align: center; vertical-align: center; border: 1px solid #000;">{{ item.fr_date }}</td>
                     <td style="text-align: center; vertical-align: center; border: 1px solid #000;">{{ item.internal_id }}</td>
                     <td style="text-align: center; vertical-align: center; border: 1px solid #000;">{{ item.external_id }}</td>
                     <td style="text-align: center; vertical-align: center; border: 1px solid #000;">{{ item.project_name }}</td>
                     <td style="text-align: center; vertical-align: center; border: 1px solid #000;">{{ getStaff(item.proj_id).name }}</td>
                     <td style="text-align: center; vertical-align: center; border: 1px solid #000;">{{ getStatusforClient(item).en }}</td>
                     </tr>
</tbody>
                </table>
                   
                </div>
                <div class="modal-footer"> 
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn grey btn-info" data-dismiss="modal" @click="buferCopy()">Copy</button>
                </div>
            </div>
        </div>
    </div>
</div>    



</div> `

});



