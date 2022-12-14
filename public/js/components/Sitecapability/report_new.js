Vue.component('report_new', {
    data() {
        return {
            managers_all: [],
            templates_all: [],
            diseases_all: [],
            sites_all: [],
            tblRows: [],
            tblRowsFiltered: []
        }
    },
    methods: {
        managersInit() {
            this.$parent.getJson('/users/getManagers/').then(man_res => {
                this.managers_all = man_res.result
            })
        },
        templatesInit() {
            this.$parent.getJson('/templateSitecapability/GetAll/').then(templates_res => {
                this.templates_all = templates_res
            })
        },
        diseasesInit() {
            this.$parent.getJson('/DiseasesSitecapability/GetAll/').then(diseases_res => {
                this.diseases_all = diseases_res.result
            })
        },
        sitesInit() {
            this.$parent.getJson('/sites/getOptions/').then(sites_res => {
                this.sites_all = sites_res
            })
        },
        loadRows() {
            this.$parent.postJson('/ManagerSitecapability/GetForReport/').then(res => {
                this.tblRows = res
                this.tblRowsFiltered = res
            })
        },
        filterRows() {
            let manager_filter = document.getElementById('manager_filter').value,
                status_filter = document.getElementById('work_status_filter').value,
                answer_filter = document.getElementById('answer_filter').value,
                template_filter = document.getElementById('template_filter').value,
                disease_filter = document.getElementById('disease_filter').value,
                site_filter = document.getElementById('site_filter').value
            this.tblRowsFiltered =
                this.tblRows
                .filter(row => {
                    return row.user_id === manager_filter || manager_filter === '0'
                })
                .filter(row => {
                    return row.work_status === status_filter || status_filter === '0'
                })
                .filter(row => {
                    return row.answer === answer_filter || answer_filter === '0'
                })
                .filter(row => {
                    return row.template_id === template_filter || template_filter === '0'
                })
                .filter(row => {
                    return row.disease_id === disease_filter || disease_filter === '0'
                })
                .filter(row => {
                    return row.site_id === site_filter || site_filter === '0'
                })
        }
    },
    mounted() {
        this.loadRows()
        this.managersInit()
        this.templatesInit()
        this.diseasesInit()
        this.sitesInit()
    },
    template: `
        <div>
            <div class="row">
                <div class="col-6">
                    <label for="manager_filter">??????????????????</label>
                    <select id="manager_filter" @change="filterRows()" class="form-control">
                        <option value="0">??????????</option>
                        <option v-for="Man in managers_all" :value="Man.id">{{ Man.lasttname + ' ' + Man.firstname }}</option>
                    </select>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="work_status_filter">?????????????? ???????????? ??????????</label>
                        <select id="work_status_filter" class="form-control" @change="filterRows()">
                            <option value="0">??????????</option>
                            <option value="1">????????????????</option>
                            <option value="2">???????????????? ????????????????</option>
                            <option value="3">???? ????????????????</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="answer_filter">?????????????? ????????????????</label>
                        <select id="answer_filter" class="form-control" @change="filterRows()">
                            <option value="0">Any</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="template_filter">???????????? ??????????????????????</label>
                        <select id="template_filter" class="form-control" @change="filterRows()">
                            <option value="0">??????????</option>
                            <option v-for="temp in templates_all" :value="temp.id">{{ temp.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="disease_filter">??????????????????????</label>
                        <select id="disease_filter" class="form-control" @change="filterRows()">
                            <option value="0">??????????</option>
                            <option v-for="disease in diseases_all" :value="disease.id">{{ disease.disease }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="site_filter">??????????</label>
                        <select id="site_filter" class="form-control" @change="filterRows()">
                            <option value="0">??????????</option>
                            <option v-for="site in sites_all" :value="site.site_id">{{ site.site_code }} - {{ site.site_name }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>????????????????</th>
                            <th>????????????</th>
                            <th>?????????????? ??????????????</th>
                            <th>???????????? ??????????????????????</th>
                            <th>??????????????????????</th>
                            <th>????????</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in tblRowsFiltered">
                            <td>{{ row.fio }}</td>
                            <td>{{ row.work_status === '1' ? '????????????????' : 
                                row.work_status === '2' ? '???????????????? ????????????????' : 
                                row.work_status === '3' ? '???? ????????????????' : '' }}</td>
                            <td>{{ row.answer }}</td>
                            <td>{{ row.template }}</td>
                            <td>{{ row.disease }}</td>
                            <td>{{ row.site_name }}</td>
                        </tr>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>`
});

