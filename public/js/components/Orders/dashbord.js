Vue.component('dashbord', {
    data() {
        return {
            orders: [],
            ordersFilter: [],
            // Массив для статусов ( модальное окно )
            statusArray: [],
            // Массив для квот ( модальное окно )
            quoteArray: [],
            // Массив для пользователей
            users: [],
            // Переменная для хранения выбранного пользователя для фильтрации
            selectedUser: 0,
            // Массив для стандартных статусов поле (fr_status)
            status: [],
            // Массив для всех статусов (status_client, status_manager, status_project, status_lpo, status_ved, status_boss)
            statusAll: [],
            // Массив логов по изменениям fr статусов
            statusLog: [],
            // Массив последних изменений статусов
            getlog: [],

            //  Массив приоритетных компаний
            companyPriority: [],
            frDateOne: localStorage.getItem('frDateOne'), //На три месяца раньше
            frDateTwo: localStorage.getItem('frDateTwo'), //Текущая
            urlSections: window.location.href.split("/"),
            socket: io.connect('https://nbs-platforms.ru:8888'),
            internal_id: '',
            task_offer_id: 0,
            // Массив для анализа
            statusLogArray: [],
        }
    },

    props: ['company', 'user_id', 'role_id'],

    methods: {

        openModelStatus(proj_id) {
            this.$parent.getJson(`../OrdersStatusActions/getByProjId/?proj_id=${proj_id}`)
                .then(data => {
                    this.statusArray = [];
                    // console.log('Статусы', data)
                    $("#statusModal").modal('show');
                    for (let key in data.result) {
                        this.statusArray.push(data.result[key]);
                    }
                });
        },

        getAllStatus() {
            this.$parent.getJson(`../OrdersStatusActions/getAllGroupProj/`)
                .then(data => {
                    this.statusLogArray = [];
                    for (let key in data.result) {
                        this.statusLogArray.push({'proj_id': key, 'data': data.result[key]});
                    }

                });
        },


        compare(a1, a2) {
            return a1.length == a2.length && a1.every((v, i) => v === a2[i])
        },

        getAnaliz(proj_id) {
            let etalon = this.statusLogArray.find(el => {
                if (el.proj_id == proj_id) {
                    return el;
                }
            })

            let Forecasts = {}
            this.statusLogArray.forEach(el => {
                if (el.data != undefined) {
                    if (etalon != undefined) {
                        if (el.data.length > etalon.data.length) {
                            if (this.compare(el.data.slice(0, etalon.data.length), etalon.data)) {
                                let forecast = el.data[etalon.data.length]
                                if (!Forecasts.hasOwnProperty(forecast))
                                    Forecasts[forecast] = 1
                                else
                                    Forecasts[forecast]++
                            }
                        }
                    }
                }
            })

            let mostCount = 0, search
            for (let forecast in Forecasts) {
                if (Forecasts[forecast] > mostCount) {
                    search = forecast
                    mostCount = Forecasts[forecast]
                }
            }
            // console.log('etalon ' ,etalon.data)
            // console.log('Forecasts ' + proj_id, Forecasts)
            // console.log('search', search)
            // console.log('---->')


            if (typeof search === 'undefined') {
                return '-'
            } else {

                let status = this.statusAll.find(el => {
                    if (el.id == search) {
                        return el
                    }
                })

                if (typeof (status) == 'undefined') {
                    return 'Думаю ...';
                } else {
                    return `${status.status_name} (${mostCount})`;
                }

            }


        },


        setLocal() {
            localStorage.setItem('frDateOne', this.frDateOne);
            localStorage.setItem('frDateTwo', this.frDateTwo);
        }
        ,

        clear(){
            this.frDateOne = '2020-01-01';
        },

        load() {
            let urlMethod = 'getAllbyDate';
            if (this.company == 1) {
                urlMethod = 'getAllbyDateCompanyPriority';
            }
            this.orders = [];
            this.ordersFilter = [];
            this.$parent.getJson(`../dashboard/${urlMethod}/?frDateOne=${this.frDateOne}&frDateTwo=${this.frDateTwo}`)
                .then(data => {
                    let i = 0;
                    for (let key in data.result) {
                        this.orders.push(data.result[key]);
                        this.ordersFilter.push(data.result[key]);
                    }
                    this.filterTable();
                });
        }
        ,
        filterTable() {
            this.ordersFilter = this.ordersFilter
                .sort((a, b) => {
                    let date_a = this.findLastDate(a.proj_id),
                        date_b = this.findLastDate(b.proj_id),
                        statusdate_a = date_a ? date_a.statusdate : '',
                        statusdate_b = date_b ? date_b.statusdate : '';
                    return statusdate_a > statusdate_b ? 1 : statusdate_a < statusdate_b ? -1 : 0;
                }).sort((a, b) => {
                    if (a.status_project === '9' && b.status_project !== '9')
                        return -1;
                    else if (a.status_project !== '9' && b.status_project === '9')
                        return 1;
                    else if (a.status_project === '9' && b.status_project === '9')
                        return 0;
                    else if (a.status_boss === '22' && b.status_boss !== '22')
                        return -1;
                    else if (a.status_boss !== '22' && b.status_boss === '22')
                        return 1;
                    else if (a.status_boss === '22' && b.status_boss === '22')
                        return 0;
                    else if (a.status_client === '36' && b.status_client !== '36')
                        return -1;
                    else if (a.status_client !== '36' && b.status_client === '36')
                        return 1;
                    else if (a.status_client === '36' && b.status_client === '36')
                        return 0;
                    else if (a.status_client === '37' && b.status_client !== '37')
                        return -1;
                    else if (a.status_client !== '37' && b.status_client === '37')
                        return 1;
                    else if (a.status_client === '37' && b.status_client === '37')
                        return 0;
                    else
                        return 0;
                });
            if (this.user_id === 5) {
                $(".disablable").prop('disabled', 'disabled');
            }
        }
        ,
        loadUsers() {
            this.$parent.getJson(`../users/getUsersByRole/?role=1`)
                .then(data => {
                    for (let key in data.result) {
                        this.users.push(data.result[key]);
                    }
                })
        }
        ,
        loadStatus() {
            this.$parent.getJson(`../frstatus/getAll/`)
                .then(data => {
                    for (let key in data.result) {
                        this.status.push(data.result[key]);
                    }
                })
        }
        ,
        loadAllStatus() {
            this.$parent.getJson(`../OrdersStatus/getAll/`)
                .then(data => {
                    for (let key in data.result) {
                        this.statusAll.push(data.result[key]);
                    }
                })
        }
        ,
        loadAStatusLog() {
            this.$parent.getJson(`../OrdersStatusActions/getChengeStatus/`)
                .then(data => {
                    for (let key in data.result) {
                        this.statusLog.push(data.result[key]);
                    }
                })
        }
        ,
        loadgetLog() {
            this.getlog = [];
            this.$parent.getJson(`../OrdersStatusActions/getLog/`)
                .then(data => {
                    for (let key in data.result) {
                        this.getlog.push(data.result[key]);
                    }
                })
        }
        ,
        isCheckChangeStatus(item) {
            let el = this.statusLog.findIndex(el => {
                if (el.proj_id == item.proj_id) {
                    return true
                }
            });
            if (el == '-1') {
                return false
            }
            item.color = true;
            return true
        }
        ,
        checkStatus9(item) {
            return item.status_project === '9';
        },

        checkYell(item) {

            return item.priority_fr === '1';
        }

        ,
        checkStatus22(item) {
            return item.status_boss === '22';
        }
        ,
        checkStatus31(item) {
            return item.status_client === '36';
        }
        ,
        checkStatus38(item) {
            return item.status_client === '37';
        }
        ,
        change_users(proj_id) {
            let answering_id = document.getElementById(`user_${proj_id}`).value;
            let data = {
                'proj_id': proj_id,
                'answering_id': answering_id
            };
            this.save(data);
        }
        ,
        change_status(proj_id) {
            let fr_status = document.getElementById(`status_${proj_id}`).value;
            let data = {
                'proj_id': proj_id,
                'fr_status': fr_status
            };
            this.save(data);
        }
        ,
        change_status_client(proj_id) {
            let status_client = document.getElementById(`statusAll_client_${proj_id}`).value;
            let data = {
                'proj_id': proj_id,
                'status_client': status_client
            };
            this.save(data);
        }
        ,
        change_status_manager(proj_id) {
            let status_manager = document.getElementById(`statusAll_manager_${proj_id}`).value;
            let data = {
                'proj_id': proj_id,
                'status_manager': status_manager
            };
            this.save(data);
        }
        ,
        change_status_project(proj_id) {
            let status_project = document.getElementById(`statusAll_project_${proj_id}`).value;
            let data = {
                'proj_id': proj_id,
                'status_project': status_project
            };
            this.save(data);
        }
        ,
        change_status_lpo(proj_id) {
            let status_lpo = document.getElementById(`statusAll_lpo_${proj_id}`).value;
            let data = {
                'proj_id': proj_id,
                'status_lpo': status_lpo
            };
            this.save(data);
        }
        ,
        change_status_ved(proj_id) {
            let status_ved = document.getElementById(`statusAll_ved_${proj_id}`).value;
            let data = {
                'proj_id': proj_id,
                'status_ved': status_ved
            };
            this.save(data);
        }
        ,
        change_status_boss(proj_id) {
            let status_boss = document.getElementById(`statusAll_boss_${proj_id}`).value;
            let data = {
                'proj_id': proj_id,
                'status_boss': status_boss
            };
            this.save(data);
        }
        ,
        loadCompanyPriority() {
            this.$parent.getJson(`../company/getCompanyPriority`)
                .then(data => {
                    for (let key in data.result) {
                        this.companyPriority.push(data.result[key]);
                    }
                })
        }
        ,
        save(data) {
            this.$parent.putJson(`../orders/save`, data)
                .then(response => {
                    toastr.success(response, 'Успешно');
                    this.socket.emit('eventChangeStatusFRTABLE', data.proj_id);
                })
        }
        ,
        findLastDate(proj_id) {
            let el = this.getlog.find(el => {
                if (el.proj_id == proj_id) {
                    return true;
                }
            });
            return el
        }
        ,


        actionStatusPick(status_manager, status_client, status_project, status_lpo, status_boss) {


            // 0
            if (
                status_manager == '5'
                && status_project == '10'
                && status_boss == '21'
                && status_client == '26'
            ) {
                return 'Проверить. Возможно регистрация запроса не завершена или требуется корректировка';
            }


            // 1
            if (status_client === '39') {
                return 'Регистрация запроса не закончена. Требуется ее завершение';
            }

            // 2
            if (status_manager == '2' && status_project == '34' && (status_lpo == '12' || status_lpo == '14' || status_lpo == '15')) {
                return 'Квот от менеджеров не достаточно. Нужно отправить напоминание/персональные задачи';
            }

            // 3
            if (status_client === '38') {
                return 'Запросить у заказчика обратную связь по заданному вопросу';
            }


            // 4
            if (status_manager === '47' && status_project === '34' && (status_lpo == '14' || status_lpo == '15' || status_lpo == '35')) {
                return 'Сообщить заказчику, что выполнение запроса невозможно';
            }

            // 5 v.1
            if (status_manager == '4' &&
                (status_project === '10' || status_project == '34' || status_project == '9' || status_project == '7')
                && (status_lpo === '14' || status_lpo === '15')
                && (status_client != '27' && status_client != '28' && status_client != '29' && status_client != '33' && status_client != '39')
            ) {
                return 'Информации достаточно/можно готовить квоту';
            }

            // 5 v.2
            if (status_manager == '5' && status_project === '9'
                && (status_lpo === '14' || status_lpo === '15')
                && (status_client != '27' && status_client != '28' && status_client != '29' && status_client != '33' && status_client != '39')
            ) {
                return 'Информации достаточно/можно готовить квоту';
            }


            // 5 v.3
            if (status_manager == '3'
                && (status_project == '9' || status_project == '7')
                && (status_client != '27' && status_client != '28' && status_client != '29' && status_client != '33' && status_client != '39')
            ) {
                return 'Информации достаточно/можно готовить квоту';
            }

            // 6
            if (status_boss == '41' || status_boss == '48'
                && (status_client != '27' && status_client != '28' && status_client != '29' && status_client != '33' && status_client != '39')
            ) {
                return 'Квота одобрена ведущим координатором. Можно отправлять на согласование директоров';
            }

            // 7
            if (status_boss == '52'
                && (status_client != '27' && status_client != '28' && status_client != '29' && status_client != '33' && status_client != '39')
            ) {
                return 'Квота получила одобрение директоров. Можно отправлять заказчику';
            }

            // 8
            if (status_manager != '1'
                && status_project != '6'
                && status_boss != '21'
                && status_client == '27'
            ) {
                return 'Запросить обратную связь по посланной квоте';
            }

            // 9
            if (
                status_client == '36'
            ) {
                return 'Ответить на вопрос заказчика';
            }

            // 10
            if (
                (status_manager == '4' || status_manager == '5' || status_manager == '47')
                && (status_project == '9' || status_project == '10' || status_project == '34')
                && status_client == '49'

            ) {
                return 'Требуетс обновить квоту';
            }


            // 11
            if (
                status_manager != '1'
                && status_project == '9'
                && status_boss == '21'
                && status_client == '30'
            ) {
                return 'Инвентори предоставлен. Запросить обратную связь по инвентори';
            }


            // 12.1
            if ((status_manager == '2' || status_manager == '3')
                && (status_project == '7' || status_project == '10')
                && (status_lpo != '35')
                && (status_client != '27' && status_client != '28' && status_client != '29' && status_client != '33' && status_client != '39')
            ) {
                return 'Проверить. Возможно требуется разослать индивидуальные задания менеджерам или напомнить о запрошенном инвентори';
            }


            // 12.2
            if ((status_manager == '5')
                && (status_project == '7')
                && (status_lpo != '35')
                && (status_client != '27' && status_client != '28' && status_client != '29' && status_client != '33' && status_client != '39')
            ) {
                return 'Проверить. Возможно требуется разослать индивидуальные задания менеджерам или напомнить о запрошенном инвентори';
            }


            // 12.3
            if ((status_manager == '4')
                && (status_project == '7')
                && (status_lpo != '35')
                && (status_client != '27' && status_client != '28' && status_client != '29' && status_client != '33' && status_client != '39')
            ) {
                return 'Проверить. Возможно требуется разослать индивидуальные задания менеджерам или напомнить о запрошенном инвентори';
            }

            // 13
            if ((status_manager != '1')
                && (status_project != '6')
                && (status_lpo == '35')
                && (status_client != '27' && status_client != '28' && status_client != '29' && status_client != '33' && status_client != '39')
            ) {
                return 'Проверить. Вопрос не исполним?';
            }


            // 14
            if ((status_manager == '5' || status_manager == '47')
                && (status_project == '34')
            ) {
                return 'Проверить. Вопрос не исполним?';
            }


            // 15
            if ((status_manager == '2' || status_manager == '3')
                && (status_project != '9')
                && (status_boss == '21')
                && (status_client != '27' && status_client != '28' && status_client != '29' && status_client != '33' && status_client != '39')
            ) {
                return 'Проверить. Возможно требуется разослать индивидуальные задания менеджерам';
            }


            // 16
            if ((status_manager == '2' || status_manager == '3')
                && (status_project == '9')
                && (status_lpo == '14' || status_lpo == '15')
                && (status_client != '27' && status_client != '28' && status_client != '29' && status_client != '33' && status_client != '39')
            ) {
                return 'Проверить. Возможно квот менеджеров не требуется или их уже уже достаточно для создания проекта коммерческого предложения';
            }


            // 17
            if (
                (status_manager == '4' || status_manager == '5')
                && (status_project == '9' || status_project == '10' || status_project == '34')
                && (status_lpo == '14' || status_lpo == '15')
                && (status_boss == '40' || status_boss == '44' || status_boss == '45')
                && (status_client != '27' && status_client != '28' && status_client != '29' && status_client != '33' && status_client != '39')

            ) {
                return 'Проверить. Возможно проект коммерческогопредложения уже одобрен';
            }


            return '';
        }
        ,


        findAndReplase(proj_id) {
            let index = this.ordersFilter.findIndex(el => {
                if (el.proj_id == proj_id) {
                    return true;
                }
            });
            if (index != '-1') {
                this.$parent.getJson(`../dashboard/getOneDashbord/?proj_id=${proj_id}`)
                    .then(data => {
                        this.$set(this.ordersFilter, index, data.result);
                    })
            }
            let indexOrders = this.orders.findIndex(el => {
                if (el.proj_id == proj_id) {
                    return true;
                }
            });
            if (indexOrders != '-1') {
                this.$parent.getJson(`../dashboard/getOneDashbord/?proj_id=${proj_id}`)
                    .then(data => {
                        this.$set(this.orders, indexOrders, data.result);
                    })
            }
        }
        ,
        filtered() {
            if (this.selectedUser == 0 && this.internal_id == '') {
                this.ordersFilter = this.orders;
            } else if (this.selectedUser != 0 && this.internal_id == '') {
                this.ordersFilter = this.orders.filter(el => {
                    if (el.answering_id == this.selectedUser) {
                        return true;
                    }
                });
            } else if (this.selectedUser == 0 && this.internal_id != '') {
                let regexp = new RegExp(this.internal_id, 'i');
                this.ordersFilter = this.orders.filter(el => regexp.test(el.internal_id));
            } else {
                let regexp = new RegExp(this.internal_id, 'i');
                this.ordersFilter = this.orders.filter(el => {
                    if (regexp.test(el.internal_id) && (el.answering_id == this.selectedUser)) {
                        return true
                    }
                });
            }
            this.filterTable();
        }
        ,
        openTaskModal(offer_id) {
            this.task_offer_id = offer_id;
            $("#offerTaskModal").modal('show');
            console.log(offer_id)
        }
        ,
        saveTask() {
            let data = {
                offer_id: this.task_offer_id,
                comment: $("#task_text").val(),
                status: $("#task_trigger_status").val()
            }
            this.$parent.putJson('../offerStatusTrigger/save', data)
                .then(response => {
                    toastr.success(response, 'Успешно');
                    $("#offerTaskModal").modal('hide')
                })
        }
        ,

    },


    mounted() {


        this.getAllStatus();
        this.loadgetLog();
        let today = new Date();
        let dd = String(today.getDate()).padStart(2, '0');
        let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        let yyyy = today.getFullYear();
        today = yyyy + '-' + mm + '-' + dd;
        this.frDateTwo = today;
        let d2 = new Date();
        d2.setMonth(d2.getMonth() - 3);
        let dd2 = String(d2.getDate()).padStart(2, '0');
        let mm2 = String(d2.getMonth() + 1).padStart(2, '0'); //January is 0!
        let yyyy2 = d2.getFullYear();
        d2 = yyyy2 + '-' + mm2 + '-' + dd2;
        this.frDateOne = d2;
        this.load();
        this.loadUsers();
        this.loadStatus();
        this.loadAllStatus();
        this.loadAStatusLog();
        this.loadCompanyPriority();

        this.socket.on('reloadDASHBORD', (proj_id) => {
            this.loadgetLog();
            this.statusLog.push({'proj_id': proj_id});
            this.findAndReplase(proj_id);
        })


        // this.load();
    }
    ,

    template: `

<div> 
<div class="row">
    <div class="col-4">
    <label>Дата с </label>
        <input type="date" class="form-control" v-model="frDateOne" >
    </div>
    <div class="col-4">
     <label>Дата до</label>
        <input type="date" class="form-control" v-model="frDateTwo" >
    </div>
        <div class="col-2">
           <label>Очистить</label><br>
        <button  class="btn btn-dark btn-block"  @click="clear()">Очистить</button>
    </div>
    
        <div class="col-2">
           <label>Загрузить</label><br>
        <button  class="btn btn-dark btn-block"  @click="load()">Загрузить</button>
    </div>
</div>




<table class="table table-striped table-bordered file-export dataTable ">
    <thead>
    <tr>
        <th>id</th>
     <th><select name="" class="form-control" @change="filtered()" v-model="selectedUser">
     <option value="0">Все сотрудники</option>
      <option v-for="user in users" :value="user.id" :key="user.id" >{{ user.lasttname }} {{ user.firstname }} {{ user.patronymic }} </option>
     </select>
     </th>
        <th style="display: none">Статус</th>
        <th><input type="text" class="form-control" v-model="internal_id" placeholder="Номер запроса" @input="filtered"></th>
        <th>Название компании</th>
        <th>Proposal Deadline</th>
        <th>Квоты</th>
        <th>Дата статуса</th>
        <th>Клиент</th>
        <th>Сайт менеджеры</th>
        <th>Проектный отдел</th>
        <th>ЛТО</th>
        <th>Отдел ВЭД</th>
        <th>Руководство</th>
        <th v-if="role_id == 1" ></th>
        <th v-if="role_id == 1" >Что делать</th>
        <th v-if="role_id == 1" >Что делать AI</th>
    </tr>
    </tr>
    </thead>
    <tbody>
    <tr v-for="item in ordersFilter" :class="{
        'bg-grey' : (checkStatus38(item) && !checkStatus9(item) && !checkStatus22(item) && !checkStatus31(item)),
        'bg-yellow' : (checkStatus31(item) && !checkStatus9(item) && !checkStatus22(item)),
        'bg-blue' : (checkStatus22(item) && !checkStatus9(item)),
        'bg-red' : checkStatus9(item),
        'yell' : checkYell(item)
        }">
     <td :class="{ 'bg-green': isCheckChangeStatus(item) }" ><a :href="'../orders/info/?idFR='+ item.proj_id " target="_blank" style="color: black">{{ item.proj_id }}</a> </td>
     <td style="min-width: 200px">
     <select name="" :id="'user_'+item.proj_id" class="form-control disablable" @change="change_users(item.proj_id)">
     <option v-for="user in users" :value="user.id" :key="user.id" v-if="user.id == item.answering_id" selected>{{ user.lasttname }} </option>
     <option   v-for="user in users" :value="user.id" :key="user.id" v-if="user.id != item.answering_id">{{ user.lasttname }} </option>
     </select>
     </td>
     <td style="display: none" >
     <select name="" :id="'status_'+item.proj_id" class="form-control" @change="change_status(item.proj_id)" disabled>
      <option v-for="stat in status" :value="stat.fr_status_id" :key="stat.fr_status_id" v-if="stat.fr_status_id == item.fr_status" selected>{{ stat.fr_status_values }}</option>
      <option v-for="stat in status" :value="stat.fr_status_id" :key="stat.fr_status_id" v-if="stat.fr_status_id != item.fr_status" >{{ stat.fr_status_values }}</option>
     </select>
    </td>
     <td>{{ item.internal_id }}</td>
     <td>{{ item.company_name }}</td>
     <td>{{ item.deadline_quote }}</td>
     <td> {{ item.countQuote }}</td>
     <td><button v-if="findLastDate(item.proj_id)" class="" @click="openModelStatus(item.proj_id)">{{ findLastDate(item.proj_id).statusdate }}</button></td>
     
     
     <td style="min-width: 200px">
     <select name="" :id="'statusAll_client_'+item.proj_id" class="form-control disablable" @change="change_status_client(item.proj_id)">
      <option v-for="statAll in statusAll" :value="statAll.id" :key="statAll.id" v-if="statAll.id == item.status_client && statAll.department_id == 7"  selected>{{ statAll.status_name }}</option>
      <option v-for="statAll in statusAll" :value="statAll.id" :key="statAll.id" v-if="statAll.id != item.status_client && statAll.department_id == 7" >{{ statAll.status_name }}</option>
     </select>
    </td>
    
    
      <td style="min-width: 200px">
     <select name="" :id="'statusAll_manager_'+item.proj_id" class="form-control disablable" @change="change_status_manager(item.proj_id)">
      <option v-for="statAll in statusAll" :value="statAll.id" :key="statAll.id" v-if="statAll.id == item.status_manager && statAll.department_id == 2"  selected>{{ statAll.status_name }}</option>
      <option v-for="statAll in statusAll" :value="statAll.id" :key="statAll.id" v-if="statAll.id != item.status_manager && statAll.department_id == 2" >{{ statAll.status_name }}</option>
     </select>
    </td>
    
    
      <td style="min-width: 200px">
     <select name="" :id="'statusAll_project_'+item.proj_id" class="form-control disablable" @change="change_status_project(item.proj_id)">
      <option v-for="statAll in statusAll" :value="statAll.id" :key="statAll.id" v-if="statAll.id == item.status_project && statAll.department_id == 3"  selected>{{ statAll.status_name }}</option>
      <option v-for="statAll in statusAll" :value="statAll.id" :key="statAll.id" v-if="statAll.id != item.status_project && statAll.department_id == 3" >{{ statAll.status_name }}</option>
     </select>
    </td>
    
    
     <td style="min-width: 200px">
     <select name="" :id="'statusAll_lpo_'+item.proj_id" class="form-control disablable" @change="change_status_lpo(item.proj_id)">
      <option v-for="statAll in statusAll" :value="statAll.id" :key="statAll.id" v-if="statAll.id == item.status_lpo && statAll.department_id == 4"  selected>{{ statAll.status_name }}</option>
      <option v-for="statAll in statusAll" :value="statAll.id" :key="statAll.id" v-if="statAll.id != item.status_lpo && statAll.department_id == 4" >{{ statAll.status_name }}</option>
     </select>
    </td>
    
    
    <td style="min-width: 200px">
     <select name="" :id="'statusAll_ved_'+item.proj_id" class="form-control disablable" @change="change_status_ved(item.proj_id)">
      <option v-for="statAll in statusAll" :value="statAll.id" :key="statAll.id" v-if="statAll.id == item.status_ved && statAll.department_id == 5"  selected>{{ statAll.status_name }}</option>
      <option v-for="statAll in statusAll" :value="statAll.id" :key="statAll.id" v-if="statAll.id != item.status_ved && statAll.department_id == 5" >{{ statAll.status_name }}</option>
     </select>
    </td>
            
    <td style="min-width: 200px">
     <select name="" :id="'statusAll_boss_'+item.proj_id" class="form-control disablable" @change="change_status_boss(item.proj_id)">
      <option v-for="statAll in statusAll" :value="statAll.id" :key="statAll.id" v-if="statAll.id == item.status_boss && statAll.department_id == 6"  selected>{{ statAll.status_name }}</option>
      <option v-for="statAll in statusAll" :value="statAll.id" :key="statAll.id" v-if="statAll.id != item.status_boss && statAll.department_id == 6" >{{ statAll.status_name }}</option>
     </select>
    </td>       
           
           <td v-if="role_id == 1">
           <input @click="openTaskModal(item.proj_id)" type="button" class="btn btn-success" value="Новая задача">
    </td>      
    
    <td v-if="role_id == 1">
           <p>{{ actionStatusPick(item.status_manager, item.status_client, item.status_project, item.status_lpo, item.status_boss)}}</p>
    </td> 
    
    <td v-if="role_id == 1">
           <p>{{ getAnaliz(item.proj_id )}}</p>
    </td>
      
    </tr>
    </tbody>
</table>
       
<div class="modal fade text-left" id="offerTaskModal"  role="dialog" aria-labelledby="myModalLabel4" aria-hidden="true" >
    <div id="modalview">
        <div class="modal-dialog modal-xl" role="document" style="width: 80% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-road2"></i> <b>Добавить задачу </b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="task_text">Комментарий</label>
                    <input type="text" class="form-control" id="task_text">
                    <label for="task_trigger_status">Статус</label>
                    <select class="form-control" id="task_trigger_status">
                        <option v-for="statAll in statusAll" :value="statAll.id" :key="statAll.id" v-if="statAll.department_id == 2 && statAll.status_name !== 'Отсутствует'">{{ statAll.status_name }}</option>
                        <option v-for="statAll in statusAll" :value="statAll.id" :key="statAll.id" v-if="statAll.department_id == 3 && statAll.status_name !== 'Отсутствует'">{{ statAll.status_name }}</option>
                        <option v-for="statAll in statusAll" :value="statAll.id" :key="statAll.id" v-if="statAll.department_id == 6 && statAll.status_name !== 'Отсутствует'">{{ statAll.status_name }}</option>
                        <option v-for="statAll in statusAll" :value="statAll.id" :key="statAll.id" v-if="statAll.department_id == 7 && statAll.status_name !== 'Отсутствует'">{{ statAll.status_name }}</option>
                    </select>
                </div>
                <div class="modal-footer"> 
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn  btn-success"  @click="saveTask()">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</div>    


<div class="modal fade text-left" id="statusModal"  role="dialog" aria-labelledby="myModalLabel4" aria-hidden="true" >
    <div id="modalview">
        <div class="modal-dialog modal-xl" role="document" style="width: 80% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-road2"></i> <b>Статусы </b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table-bordered table">
                        <thead>
                            <tr>
                                <th>Департамент</th>
                                <th>Статус</th>
                                <th>Ответственный</th>
                                <th>Дата и время</th>
                            </tr>       
                        </thead>
                        <tbody>
                            <tr v-for="status in statusArray" :key="status.id">
                                <td>{{ status.department_name }}</td>
                                <td>{{ status.status_name }}</td>
                                <td>{{ status.lasttname }} {{ status.firstname }}</td>
                                <td>{{ status.created_at }}</td>
                            </tr>
                        </tbody>
</table>
                
                </div>
                <div class="modal-footer"> 
                </div>
            </div>
        </div>
    </div>
</div>    
</div>`

})
;





