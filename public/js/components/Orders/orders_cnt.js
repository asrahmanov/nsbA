Vue.component('orders_cnt', {
    data() {
        return {
            disease_categories: [],
            disease_category_selected: '0',
            disease_groups: [],
            disease_group_selected: '0',
            diseases: [],
            disease_selected: '0',
            orders_by_id: {},
            scripts_by_id: {},
            staff_by_id: {},
            tblRows: [],
            dt: {}
        }
    },
    methods: {
        onCategorySelected() {
            this.disease_group_selected = '0'
            this.disease_selected = '0'
            if (this.disease_category_selected !== '0')
                this.getData()
        },
        onGroupSelected() {
            this.disease_selected = '0'
            if (this.disease_category_selected !== '0')
                this.getData()
        },
        onDiseaseSelected() {
            if (this.disease_category_selected !== '0')
                this.getData()
        },
        getData() {
            let catId = this.disease_category_selected,
                groupId = this.disease_group_selected,
                dId = this.disease_selected
            this.tblRows = [];
            this.$parent.getJson(`../orders/countByDiseases/?category_id=${catId}&group_id=${groupId}&disease_id=${dId}`)
                .then(data => {
                    data.result.forEach(order_disease => {
                        let Order = this.orders_by_id[order_disease.order_id]
                        if (Order) {
                            Order.attention_to = isNaN(parseInt(Order.attention_to)) ? 0 : parseInt(Order.attention_to)
                            let found = this.tblRows.find(row => {
                                return parseInt(row.fr_script_id) === parseInt(Order.fr_script_id) && row.attention_to === Order.attention_to
                            })
                            if (!found) {
                                let site_name = this.scripts_by_id[Order.fr_script_id]?.company_name,
                                    staff_name = this.staff_by_id[Order.attention_to]?.name,
                                    staff_email = this.staff_by_id[Order.attention_to]?.email
                                this.tblRows.push({
                                    order_id: Order.proj_id,
                                    fr_script_id: Order.fr_script_id,
                                    site_name,
                                    attention_to: Order.attention_to,
                                    staff_name,
                                    staff_email,
                                    count: 1
                                })
                            } else {
                                this.tblRows.filter(row => {
                                    return parseInt(row.fr_script_id) === parseInt(Order.fr_script_id) && row.attention_to === Order.attention_to
                                })[0]['count']++
                            }
                        } else {
                        }
                    })
                })
        },
    },
    watch: {
        tblRows() {
            this.dt.destroy();
            // $("#reportTable tbody").html(`<tr>
            //     <td colSpan="4" style="text-align: center;">
            //         <span style="width:100%;"><img src="../app-assets/img/loaders/rolling.gif" alt="loading"></span>
            //     </td>
            // </tr>`)

            this.$nextTick(() => {
                this.dt = $("#reportTable").DataTable({
                    dom: 'Bfrtip',
                    buttons: [ 'excel' ],
                    processing: true,
                    language: {
                        sEmptyTable: '<span style="width:100%;"><img src="../app-assets/img/loaders/rolling.gif" alt="loading" ></span>'
                    }
                })
            })
        }
    },
    mounted() {
        window.addEventListener('load', () => {
            this.dt = $("#reportTable").DataTable({
                dom: 'Bfrtip',
                buttons: [ 'excel' ],
                processing: true,
                language: {
                    sEmptyTable: 'Выберите категорию',
                    sLoadingRecords: '<span style="width:100%;"><img src="../app-assets/img/loaders/rolling.gif" alt="loading" ></span>'
                }
            })
        })
        this.$parent.getJson('../../orders/getForCount').then(data => {
            data.orders.forEach(order => this.orders_by_id[order.proj_id] = order)
            data.company.forEach(script => this.scripts_by_id[script.script_id] = script)
        })
        this.$parent.getJson('../../staff/getAll').then(data => {
            data.result.forEach(staffItem => this.staff_by_id[staffItem.id] = staffItem)
        })
        this.$parent.getJson('../diseaseCategory/getAll').then(data => this.disease_categories = data.result)
        this.$parent.getJson('../diseaseGroup/getAll').then(data => this.disease_groups = data.result)
        this.$parent.getJson('../disease/getAll').then(data => this.diseases = data.result)
    },
    template: `
    <div>
        <div class="row">
            <div class="col-4">
                <select class="form-control" id="disease_category"
                    @change="onCategorySelected()"
                    v-model="disease_category_selected">
                        <option value="0" disabled>Все категории</option>
                        <option
                            v-for="item in disease_categories"
                            :value="item.id">{{ item.category_name }}</option>
                </select>
            </div>
            <div class="col-4">
                <select class="form-control" id="disease_group"
                    @change="onGroupSelected()"
                    v-model="disease_group_selected"
                    :disabled="disease_category_selected === '0'">
                        <option value="0">Все группы</option>
                        <option 
                            v-for="item in disease_groups"
                            v-if="item.category_id === disease_category_selected"
                            :value="item.id">{{ item.group_name }}</option>
                </select>
            </div>
            <div class="col-4">
                <select class="form-control" id="disease_select"
                    @change="onDiseaseSelected()"
                    v-model="disease_selected"
                    :disabled="disease_group_selected === '0'">
                        <option value="0">Все болезни</option>
                        <option
                            v-for="item in diseases"
                            v-if="item.group_id === disease_group_selected"
                            :value="item.id">{{ item.disease_name }}</option>
                </select>
            </div>
        </div><br><br><br>
        <table class="table table-striped table-bordered file-export" id="reportTable">
            <thead>
                <tr>
                    <th>Сайт</th>
                    <th>Контакт</th>
                    <th>e-mail</th>
                    <th>Кол-во заявок</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in tblRows">
                    <td>{{ row.site_name }}</td>
                    <td>{{ row.staff_name }}</td>
                    <td>{{ row.staff_email }}</td>
                    <td>{{ row.count }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    `
});



