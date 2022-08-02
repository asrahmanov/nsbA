Vue.component('report', {
    data() {
        return {

            // Болезни
            diseases: [],
            // выбранная болезнь
            diseases_val: 0,


            // Ткани
            tissues: [],
            // выбранная ткань
            tissues_val: 0,


            // Шаблоны
            temp: [],
            temp_value: 0,


            // Ответы зависимот от ткани и болезни
            answer: [],
            answerFilter: [],


            // users Массив уникальный пользоватлей
            users: [],

            // Массив уникальных сайтов
            sites: [],

            // Массив уникальный болезний
            diseasesUnic: [],


            // Массив уникальных тканей
            tissuesUnic: [],


            total: 0,


            // Массива для проверки подсчетов
            // Необъодим для того чтобы считать только уникальные сайты
            // в рамках одного пересечения болезни и ткани
            сheckTotal: [],

            dopQuestion : []


        }
    },

    methods: {

        filtered() {
            this.answerFilter = this.answer.filter(el => {
                if (this.diseases_val == el.diseases_sitecapability_id && this.tissues_val == el.tissues_sitecapability_id) {
                    return el;
                }
            })


        },

        loadTissues() {
            this.tissues = [];
            let url = `/templateSitecapability/TissuesAll`;

            if (this.temp_value != 0) {
                url = `/templateSitecapability/TissuesAll/?template_id=${this.temp_value}`;
            }

            this.$parent.getJson(url)
                .then(data => {
                    let i = 0;
                    for (let key in data.result) {
                        this.tissues.push(data.result[key]);
                    }
                });
        },

        loadAnswer() {
            this.answer = [];
            this.answerFilter = [];
            this.total = 0;
            this.$parent.getJson(`/ManagerSitecapability/GetAllByStatus/?template_id=${this.temp_value}&tissues_val=${this.tissues_val}&diseases_val=${this.diseases_val}`)
                .then(data => {

                    let i = 0;
                    for (let key in data) {
                        this.answer.push(data[key]);
                        this.answerFilter.push(data[key]);




                        let chekTotal = {
                            'site_id': this.answerFilter[i].site_id,
                            'user_id': this.answerFilter[i].user_id,
                            'diseases_sitecapability_id': this.answerFilter[i].diseases_sitecapability_id,
                            'template_id': this.answerFilter[i].template_id,
                            'tissues_sitecapability_id': this.answerFilter[i].tissues_sitecapability_id,
                        }

                        let index_totlal = this.сheckTotal.findIndex(el => {
                            if (el.site_id == data[key].site_id
                                && el.user_id == data[key].user_id
                                && el.diseases_sitecapability_id == data[key].diseases_sitecapability_id
                                && el.template_id == data[key].template_id
                                && el.tissues_sitecapability_id == data[key].tissues_sitecapability_id
                            ) {
                                return el;
                            }
                        })

                        if (index_totlal === -1) {
                            console.log(chekTotal);

                            this.сheckTotal.push(chekTotal);
                            // Суммарный подсчет
                            if(isNaN(data[key].answer*1)){
                                if(data[key].answer == 'Yes') {
                                    this.total += 1;
                                }
                            } else {
                                this.total += data[key].answer*1
                            }

                        }










                    }
                    this.render()
                });
        },

        loadDiseases() {
            this.diseases = [];
            console.log('temp_value',this.temp_value)
            let url = `/templateSitecapability/DiseaseAll`;
            if (this.temp_value != 0) {
                url = `/templateSitecapability/DiseaseAll/?template_id=${this.temp_value}`;
            }

            this.$parent.getJson(url)
                .then(data => {
                    let i = 0;
                    for (let key in data.result) {
                        this.diseases.push(data.result[key]);
                    }
                });
        },


        loadTemp() {
            this.$parent.getJson(`/templateSitecapability/temp`)
                .then(data => {
                    let i = 0;
                    for (let key in data.result) {
                        this.temp.push(data.result[key]);
                    }
                });


        },

        loadQuestionsAnswer() {
            this.$parent.getJson(`/ManagerSiteCapabilityQuestion/getByTemplate/?template_id=${this.temp_value}`)
                .then(data => {
                    let i = 0;
                    for (let key in data.result) {
                        this.dopQuestion.push(data.result[key]);
                    }
                });

            console.log('this.dopQuestion',this.dopQuestion)

        },


        searchAnswerQuestion(site_id,){

           let el = this.dopQuestion.find(el => {
                if (site_id == el.site_id) {
                    return el;
                }
            })

        },



        render() {
            this.users = [];
            this.sites = [];
            this.diseasesUnic = [];
            this.tissuesUnic = [];
            // собираем всех юзеров Начало
            for (let i = 0; i < this.answerFilter.length; i++) {
                let user = {
                    'user_id': this.answerFilter[i].user_id,
                    'fio': this.answerFilter[i].lasttname + ' ' + this.answerFilter[i].firstname
                }

                let index = this.users.findIndex(el => {
                    if (el.user_id == user.user_id) {
                        return el;
                    }
                })

                if (index === -1) {
                    this.users.push(user);
                }
                // собираем всех юзеров конец


                // собираем все сайты

                let site = {
                    'site_id': this.answerFilter[i].site_id,
                    'site_name': this.answerFilter[i].site_name,
                    'user_id': this.answerFilter[i].user_id
                }

                let index_site = this.sites.findIndex(el => {
                    if (el.site_id == site.site_id && el.user_id) {
                        return el;
                    }
                })

                if (index_site === -1) {
                    this.sites.push(site);
                }
                // собираем все сайты конец


                // Собираем уникальыне болезни diseases

                let disease = {
                    'site_id': this.answerFilter[i].site_id,
                    'disease': this.answerFilter[i].disease,
                    'user_id': this.answerFilter[i].user_id,
                    'diseases_sitecapability_id': this.answerFilter[i].diseases_sitecapability_id,
                    'template_id': this.answerFilter[i].template_id,
                    'diseasesid': this.answerFilter[i].diseasesid,
                    'manager_site_capability_id': this.answerFilter[i].manager_site_capability_id,
                }

                let index_disease = this.diseasesUnic.findIndex(el => {
                    if (el.diseasesid == disease.diseasesid
                        && el.user_id == disease.user_id
                        && el.diseases_sitecapability_id == disease.diseases_sitecapability_id
                        && el.site_id == disease.site_id
                         && el.manager_site_capability_id == disease.manager_site_capability_id
                    ) {
                        return el;
                    }
                })

                if (index_disease === -1) {
                    this.diseasesUnic.push(disease);
                }


                // Собираем уникальные ткани
                let tissues = {
                    'site_id': this.answerFilter[i].site_id,
                    'tissues': this.answerFilter[i].tissues,
                    'user_id': this.answerFilter[i].user_id,
                    'tissues_sitecapability_id': this.answerFilter[i].tissues_sitecapability_id,
                    'template_id': this.answerFilter[i].template_id,
                    'tissuesid': this.answerFilter[i].tissuesid,
                     'manager_site_capability_id': this.answerFilter[i].manager_site_capability_id,
                }

                let index_tissues = this.tissuesUnic.findIndex(el => {
                    if (el.tissuesid == tissues.tissuesid
                        && el.user_id == tissues.user_id
                        && el.tissues_sitecapability_id == tissues.tissues_sitecapability_id
                        && el.site_id == tissues.site_id
                            // && el.manager_site_capability_id == tissues.manager_site_capability_id
                    ) {
                        return el;
                    }

                })


                if (index_tissues === -1) {
                    this.tissuesUnic.push(tissues);
                }


            }

        },

        changeTenplate() {
            this.tissues_val = 0;
            this.diseases_val = 0;
            this.loadTissues();
            this.loadDiseases();
        },

        searchAnswer(manager_site_capability_id, diseases_sitecapability_id, tissues_sitecapability_id) {

            let el = this.answer.filter(el => {
                if (el.manager_site_capability_id == manager_site_capability_id
                    && el.diseases_sitecapability_id == diseases_sitecapability_id
                    && el.tissues_sitecapability_id == tissues_sitecapability_id
                ) {

                    return el
                }
            })

            if (el === undefined) {
                return {}
            } else {
                return el
            }

        },


        load() {
            if (this.temp_value == 0) {
                toastr.error('Выберите шаблон');
            } else if (this.diseases_val == 0) {
                toastr.error('Выберите болезнь');
            } else if (this.tissues_val == 0) {
                toastr.error('Выберите ткань');
            } else {
                toastr.success('Загрузка ...');
                this.loadAnswer();
                this.loadQuestionsAnswer();

            }
        },



    },

    mounted() {
        // this.loadTissues();
        // this.loadDiseases();
        // this.loadAnswer();
        this.loadTemp(); // Загрузка шаблонов

    },

    template: `
<div>
<div class="row">
        <div class="col-md-3">
           <select class="form-control" v-model="temp_value" @change="changeTenplate()" >
            <option value="0" >Выберите Шаблон</option>
            <option v-for="item in temp" :value="item.id" >{{ item.name }}</option>
        </select>
        </div>



        <div class="col-md-3" >
            <select class="form-control" v-model="diseases_val" >
            <option value="0" >Выберите болезнь</option>
            <option v-for="item in diseases" :value="item.id">{{ item.disease }} | {{ item.name }}</option>
        </select>
        </div>

        <div class="col-md-3">
           <select class="form-control" v-model="tissues_val" >
            <option value="0" >Выберите Ткань</option>
            <option v-for="item in tissues" :value="item.id">{{ item.tissues }} | {{ item.name }}</option>
        </select>
        </div>
        
        <div class="col-md-3">
          <button class="btn btn-info btn-block" @click="load()">Загрузить</button>
        </div>
 </div>       
           
           
            <div class="row mt-4">
              
            
                
             
              
                <table class="table table-striped table-bordered file-export dataTable" v-for="user in users" >
               
                <thead>
                <tr>
                <th class="relative sticky-col first-col">{{ user.fio }}</th>
                <th v-for="site in sites" v-if="user.user_id == site.user_id">{{ site.site_name }} 
                
                <table class="table table-striped table-bordered file-export dataTable">
                <thead>
                <tr>
                <th>@</th>
                <th v-for="disease in diseasesUnic" v-if="( (disease.site_id == site.site_id) && (user.user_id == disease.user_id) ) "> {{ disease.disease }} </th>
                
                </tr>
                </thead>
                <tbody>
                  <tr v-for="tissue in tissuesUnic" v-if="( (tissue.site_id == site.site_id) && (user.user_id == tissue.user_id) ) ">
                  <td> {{ tissue.tissues }}</td>
                  
                  <td v-for="disease in diseasesUnic" v-if="( (disease.site_id == site.site_id) && (user.user_id == disease.user_id) ) "> 
                        <span v-for="its in searchAnswer(disease.manager_site_capability_id, disease.diseases_sitecapability_id, tissue.tissues_sitecapability_id)">
                        <span style="background-color: #0a9e67" v-if="its.answer == 'Yes'">{{ its.answer }}</span> 
                        <span style="background-color: #0a9e67" v-if="its.answer == 'да'">{{ its.answer }}</span> 
                        <span style="background-color: #f31e26" v-if="its.answer == 'No'">{{ its.answer }}</span> 
                        <span style="background-color: #f31e26" v-if="its.answer == 'нет'">{{ its.answer }}</span>  / {{ its.dates }}<br>
                        </span> 
                  
                  
                  
                  </td>
                  </tr>  
                  
                   <questions-manager 
                      :uid="temp_value"
                      :site_id="site.site_id"
                      :user_id="user.user_id"
                      :admin="true"
                      ></questions-manager> 
                  
                </tbody>
                
                
                
                </table>
                
            
    
              
              
              
            
            
            
        
                
                
                
                
                        
</div>
    <h4 class="mt-4">Менеджеров: {{ users.length }}</h4>
    <h4>Сайтов : {{ sites.length}}</h4>
    <h4>Итого:  {{ total }}</h4>
    

</div>
 `

});

