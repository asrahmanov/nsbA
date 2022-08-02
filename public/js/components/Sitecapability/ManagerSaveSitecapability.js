Vue.component('sitecapabilitysave', {
    data() {
        return {
            url: '../../templateSitecapability/getAll',
            // Болезини поиск
            mkb: [],
            // Ткани поиск
            tissues: [],

            // Основноывй
            sitecapabilityTemplate: [],


            sitecapabilityType: [],

            // Прошлые ответы
            answer: [],
            answerHistory: [],

            newName: '',

            // Ткани первый столбцы
            tableTissues: [],

            // Болезни вопросы
            tableDiseases: [],

            mkbName: '',
            tissuesName: '',


        }
    },
    props: [
        'uid',
        'manager_site_capability_id',
        'site_id',
        'user_id'
    ],
    methods: {

        // Поиск для болезний
        searchMkb() {
            if (this.mkbName.length >= 5) {
                this.loadMKB(this.mkbName);
            }
        },


        // Поиск для тканей
        searchTissues() {
            if (this.tissuesName.length >= 2) {
                this.loadTissues(this.tissuesName);
            }
        },


        // Метод добавление ткани
        addTissues() {
            if (this.tissuesName == '') {
                toastr.error('Заполните имя');
            } else {

                let data = {
                    'template_id': this.uid,
                    'tissues': this.tissuesName,
                    'sorting': 2,
                    'users_group': this.user_id
                }
                this.saveTissues(data);
            }
        },

        // Сохраняем ткань
        saveTissues(data) {
            this.$parent.putJson(`../../TemplateSitecapability/SaveTissues`, data)
                .then(datas => {
                    if (data.id === undefined) {
                        data.id = datas.result;
                        this.tableTissues.push(data);
                        this.tissuesName = '';
                        toastr.success('ok');
                    }
                })
        },

        // Метод болезни ткани
        addDiseases() {
            if (this.mkbName == '') {
                toastr.error('Заполните имя');
            } else {

                let data = {
                    'template_id': this.uid,
                    'disease': this.mkbName,
                    'sorting': 2,
                    'users_group': this.user_id
                }
                this.saveDiseases(data);
            }
        },

        // Сохроняем ткань
        saveDiseases(data) {
            this.$parent.putJson(`../../TemplateSitecapability/SaveDiseases`, data)
                .then(datas => {
                    if (data.id === undefined) {
                        data.id = datas.result;
                        this.tableDiseases.push(data);
                        this.mkbName = '';
                        toastr.success('ok');
                    }
                })
        },

        // Сохроняем работу
        saveWork(status_id) {
            let data = {
                'status_id': status_id,
                'id': this.manager_site_capability_id
            }
            this.$parent.putJson(`../../ManagerSitecapability/Save`, data)
                .then(datas => {
                    if (datas.result === true) {
                        location.href = '../../ManagerSitecapability'
                    }
                })
        },


        loadType() {
            this.$parent.getJson(`/SiteCapabilityType/GetAll/?template_id=${this.uid}`)
                .then(data => {
                    let i = 0;
                    for (let key in data) {
                        this.sitecapabilityType.push(data[key]);
                    }
                });
        },

        loadAnswer() {
            this.$parent.getJson(`/ManagerSitecapability/GetMyAnser/?manager_site_capability_id=${this.manager_site_capability_id}`)
                .then(data => {
                    let i = 0;
                    for (let key in data) {
                        this.answer.push(data[key]);
                    }
                });
        },


        loadAnswerHistory() {
            this.$parent.getJson(`/ManagerSitecapability/GetMyAnserHistory/?site_id=${this.site_id}`)
                .then(data => {
                    let i = 0;
                    for (let key in data) {
                        this.answerHistory.push(data[key]);
                    }
                });
        },


        loadMKB(text) {
            this.mkb = [];
            this.$parent.getJson(`../../MKB/GetAlls/?text=${text}`)
                .then(data => {
                    let i = 0;
                    for (let key in data) {
                        this.mkb.push(data[key]);
                    }
                });
        },

        loadTissues(text) {
            this.tissues = [];
            this.$parent.getJson(`../../tissues/GetAlls/?text=${text}`)
                .then(data => {
                    let i = 0;
                    for (let key in data) {
                        this.tissues.push(data[key]);
                    }
                });
        },


        loadTableTissues() {
            this.$parent.getJson(`../../templateSitecapability/getTableTissues/?id=${this.uid}`)
                .then(data => {
                    let i = 0;
                    for (let key in data) {
                        this.tableTissues.push(data[key]);
                    }
                });
        },


        loadTableDiseases() {
            this.$parent.getJson(`../../templateSitecapability/getTableDiseases/?id=${this.uid}`)
                .then(data => {
                    let i = 0;
                    for (let key in data) {
                        this.tableDiseases.push(data[key]);
                    }
                });
        },

        deleteDisease(diseases) {
            // this.$parent.deleteJson(`../../templateSitecapability/DeleteDisease`, {id: diseases.id})
            //     .then(data => {
            //         if (data.result == 0) {
            //             this.tableDiseases.splice(this.tableDiseases.indexOf(diseases), 1)
            //             toastr.success('Удалено')
            //         }
            //     })

        },


        deleteTissues(tissues) {
            // this.$parent.deleteJson(`../../templateSitecapability/DeleteTissues`, {id: tissues.id})
            //     .then(data => {
            //         if (data.result == 0) {
            //             this.tableTissues.splice(this.tableTissues.indexOf(tissues), 1)
            //         }
            //     })

        },

        checkAnswer(tissues_id, diseases_id) {
            let el = this.answer[0].find(el => {

                if (el.tissues_sitecapability_id == tissues_id && el.diseases_sitecapability_id == diseases_id) {
                    return el
                }

            })
            if (el === undefined) {
                return {}
            } else {
                return el
            }

        },
        searchType(tissues_id, diseases_id) {

            let el = this.sitecapabilityType.find(el => {
                if ((el.tissues_id == tissues_id) && (el.diseases_id == diseases_id)) {
                    return el
                }
            })

            if (el === undefined) {
                return {}
            } else {
                return el
            }
        },

        saveValue(tissues_id, diseases_id) {

            let answer = document.getElementById('select_' + tissues_id + '_' + diseases_id).value;

            if (answer == '') {
                console.log('Не заполнено')
            } else {


                // console.log(this.uid)
                // console.log(this.manager_site_capability_id)


                let data = {
                    'manager_site_capability_id': this.manager_site_capability_id,
                    'tissues_sitecapability_id': tissues_id,
                    'diseases_sitecapability_id': diseases_id,
                    'answer': answer,
                }

                //
                let index = this.answer[0].findIndex(el => {
                    if (el.tissues_sitecapability_id == tissues_id && el.diseases_sitecapability_id == diseases_id) {
                        return el;
                    }
                })


                if (index === -1) {


                } else {
                    let id = this.checkAnswer(tissues_id, diseases_id).id;
                    data.id = id;
                    this.$set(this.answer[0], index, data);
                }


                this.$parent.postJson(`/ManagerSitecapability/SaveAnswer`, data)
                    .then(datas => {
                        if (data.id === undefined) {
                            data.id = datas.result;
                            this.answer[0].push(data);
                            toastr.success('ok');
                        } else {

                        }
                    })


            }
            // тут

        }


    },

    mounted() {
        // this.load();
        this.loadAnswer();
        this.loadTableTissues();
        this.loadTableDiseases();
        this.loadType()


    },

    template: `
<div class="row">
            <div class="col-md-6 col-sm-12">
            <h4>Добавить  ткань</h4>
            
            <fieldset class="form-group">
            <input type="text" class="form-control" v-model="tissuesName" placeholder="Наменование ткани" @input="searchTissues()" list="tissues">
              <datalist id='tissues'>
                <option  v-for="el in tissues">{{ el.name }}</option>
            </datalist>
            </fieldset>   
                 
            <fieldset class="form-group">
                 <input type="button" class="btn btn-success " @click="addTissues()" value="Добавить ткань">
            </fieldset>   
            </div>
            
            <div class="col-md-6 col-sm-12">
            
            <h4>Добавить болезнь</h4>
            
            <fieldset class="form-group">
            <input type="text" class="form-control" v-model="mkbName"  @input='searchMkb' placeholder="Наименование болезни" list='mkb'>
            <datalist id='mkb'>
                <option  v-for="el in mkb">{{ el.name }}</option>
            </datalist>
            </fieldset>   
                 
            <fieldset class="form-group">
                 <input type="button" class="btn btn-success " @click="addDiseases()" value="Добавить болезнь">
            </fieldset>   
            </div>
                      <div class="col-md-12 col-sm-12">
                      <h4>Вид шаблона</h4>
                      
                      
                   <div class="wrapper_table">

                      
                       <table class="table table-striped table-bordered file-export dataTable">
                      <thead>
                      <tr>
                      <th class="relative sticky-col first-col">Ткани</th>
                       <th v-for="diseases in tableDiseases" class="relative"> {{ diseases.disease }} </th>
                      </tr>
                      </thead> 
                      <tbody>
                      <tr v-for="tissues in tableTissues">
                        <th class="relative sticky-col first-col" style="min-width: 200px"> {{ tissues.tissues }}</th>
                        <th v-for="diseases in tableDiseases" style="min-width: 200px"> 
                        
                        <div v-if="( (diseases.users_group === 'all') && (tissues.users_group === 'all')  )">    
                        <small class="content-sub-header" v-if="searchType(tissues.id,diseases.id)">{{  searchType(tissues.id,diseases.id).placeholdervalue }}</small>
                          
                        <select v-if="searchType(tissues.id,diseases.id).typevalue == 'Boolean'" :id="'select_' + tissues.id + '_' + diseases.id "  class="form-control" @change="saveValue(tissues.id, diseases.id)" >
                        
                        <option value="0">Выберите</option>
                        <option value="Yes" v-if="checkAnswer(tissues.id,diseases.id).answer == 'Yes'" selected>Да</option>
                        <option value="Yes" v-else>Да</option>
                        
                        
                        <option value="No" v-if="checkAnswer(tissues.id,diseases.id).answer == 'No'" selected>Нет</option>
                        <option value="No" v-else>Нет</option>
                        </select>
                      
                        
                        <div class="input-group" v-else>
                        <input type="text" :value="checkAnswer(tissues.id,diseases.id).answer"  :id="'select_' + tissues.id + '_' + diseases.id " class="form-control" placeholder="Text" v-if="searchType(tissues.id,diseases.id).typevalue == 'Text'" @blur="saveValue(tissues.id, diseases.id)">
                        <input type="Number" :value="checkAnswer(tissues.id,diseases.id).answer" :id="'select_' + tissues.id + '_' + diseases.id " class="form-control" placeholder="Number" v-if="searchType(tissues.id,diseases.id).typevalue == 'Number'" @blur="saveValue(tissues.id, diseases.id)">
                       
                        <div class="input-group-append" v-if="searchType(tissues.id,diseases.id).typevalue == 'Text'|| searchType(tissues.id,diseases.id).typevalue == 'Number'">
                          <span class="input-group-btn" id="button-addon2">
                            <button class="btn btn-info" type="button" >
                              <i class="ft-save"></i>
                            </button>
                          </span>
                        </div>
                      </div>    
                  
                        </div> 
                        
                        <div v-else>
                       
                                                 
                          
                          <div class="input-group">
                         <input type="text" :value="checkAnswer(tissues.id,diseases.id).answer"  :id="'select_' + tissues.id + '_' + diseases.id " class="form-control" placeholder="Text" @blur="saveValue(tissues.id, diseases.id)" >

                        <div class="input-group-append">
                          <span class="input-group-btn" id="button-addon2">
                            <button class="btn btn-info" type="button" >
                              <i class="ft-save"></i>
                            </button>
                          </span>
                        </div>
                      </div>
                          
                        </div>
                           
                       </th>
                      </tr>
                      </tbody>
                       </table>
                       
                      <questions-manager 
                      :uid="uid"
                      :manager_site_capability_id="manager_site_capability_id"
                      :site_id="site_id"
                      :user_id="user_id"
                      admin=false 
                      ></questions-manager> 
        
                       
                     </div>
                     <button class="btn btn-danger" @click="saveWork(3)">Не подходит</button>
                     <button class="btn btn-success" @click="saveWork(2)">Завершить</button>
                     
                    </div> 
</div>
 `

});

