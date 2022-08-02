

Vue.component('sitecapability', {
    components: {

    },

    data() {
        return {
            url: '/templateSitecapability/getAll',
            // Болезини поиск
            mkb: [],
            // Ткани поиск
            tissues: [],

            // Основноывй
            sitecapabilityTemplate: [],


            sitecapabilityType: [],

            newName: '',

            // Ткани первый столбцы
            tableTissues: [],

            // Болезни вопросы
            tableDiseases: [],

            mkbName: '',
            tissuesName: '',

            typeValue: 'select'
        }
    },
    props: ['uid'],
    methods: {






        // Метод добавление ткани
        addTissues() {
            if (this.tissuesName == '') {
                toastr.error('Заполните имя');
            } else {

                let data = {
                    'template_id': this.uid,
                    'tissues': this.tissuesName,
                    'sorting': 1,
                    'users_group': 'all',
                }
                this.saveTissues(data);
            }
        },

        // Сохроняем ткань
        saveTissues(data) {


            this.$parent.putJson(`/TemplateSitecapability/SaveTissues`, data)
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
                    'sorting': 1,
                    'users_group': 'all',
                }
                this.saveDiseases(data);
            }
        },

        // Сохроняем ткань
        saveDiseases(data) {
            this.$parent.putJson(`/TemplateSitecapability/SaveDiseases`, data)
                .then(datas => {
                    if (data.id === undefined) {
                        data.id = datas.result;
                        this.tableDiseases.push(data);
                        this.mkbName = '';
                        toastr.success('ok');
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
                    console.log('this.sitecapabilityType', this.sitecapabilityType)
                });
        },


        loadMKB() {
            this.mkb = [];
            this.$parent.getJson(`../../disease/getAll/`)
                .then(data => {
                    let i = 0;
                    for (let key in data.result) {
                        this.mkb.push(data.result[key]);
                    }
                });
        },

        loadTissues() {
            this.tissues = [];
            this.$parent.getJson(`../../biospecimenType/getAll/`)
                .then(data => {
                    let i = 0;
                    for (let key in data.result) {
                        this.tissues.push(data.result[key]);
                    }
                });
        },


        loadTableTissues() {
            this.$parent.getJson(`/templateSitecapability/GetTableTissuesAdmin/?id=${this.uid}`)
                .then(data => {
                    let i = 0;
                    for (let key in data) {
                        this.tableTissues.push(data[key]);
                    }
                });
        },


        loadTableDiseases() {
            this.$parent.getJson(`/templateSitecapability/getTableDiseasesAdmin/?id=${this.uid}`)
                .then(data => {
                    let i = 0;
                    for (let key in data) {
                        this.tableDiseases.push(data[key]);
                    }
                });
        },

        deleteDisease(diseases) {
            this.$parent.deleteJson(`/templateSitecapability/DeleteDisease`, {id: diseases.id})
                .then(data => {
                    if (data.result == 0) {
                        this.tableDiseases.splice(this.tableDiseases.indexOf(diseases), 1)
                        toastr.success('Удалено')
                    }
                })

        },


        deleteTissues(tissues) {
            this.$parent.deleteJson(`/templateSitecapability/DeleteTissues`, {id: tissues.id})
                .then(data => {
                    if (data.result == 0) {
                        this.tableTissues.splice(this.tableTissues.indexOf(tissues), 1)
                    }
                })

        },


        searchType(tissues_id, diseases_id) {

            return el = this.sitecapabilityType.find(el => {
                if ((el.tissues_id == tissues_id) && (el.diseases_id == diseases_id)) {
                    return el
                }
            })

        },


        addType(tissues_id, diseases_id) {
            let data = {
                'tissues_id': tissues_id,
                'diseases_id': diseases_id,
                'template_id': this.uid,
                'typevalue': 'Number',
            }
            this.$parent.putJson(`/SiteCapabilityType/Save`, data)
                .then(datas => {
                    if (data.id === undefined) {
                        data.id = datas.result;
                        this.sitecapabilityType.push(data);
                        toastr.success('ok');
                    }
                })
        },


        changeType(tissues_id, diseases_id) {

            let type_id = document.getElementById('type_id_' + tissues_id + '_' + diseases_id).value;
            let typevalue = document.getElementById('select_' + tissues_id + '_' + diseases_id).value;
            let placeholdervalue = document.getElementById('placeholdervalue_' + tissues_id + '_' + diseases_id).value;

            let data = {
                'id': type_id,
                'tissues_id': tissues_id,
                'diseases_id': diseases_id,
                'template_id': this.uid,
                'typevalue': typevalue,
                'placeholdervalue': placeholdervalue
            }

            let index = this.sitecapabilityType.findIndex(el => {
                if (el.id == type_id) {
                    return el;
                }
            })

            this.$set(this.sitecapabilityType, index, data);

            this.$parent.postJson(`/SiteCapabilityType/Save`, data)


        }


    },

    mounted() {
        this.loadTableTissues();
        this.loadTableDiseases();
        this.loadType();
        this.loadMKB();
        this.loadTissues();
    },

    template: `
<div class="row">

            <div class="col-md-6 col-sm-12">
            <h4>Добавить  ткань</h4>
            
            <fieldset class="form-group">
            <input type="text" class="form-control" v-model="tissuesName" placeholder="Наменование ткани"  list="tissues">
              <datalist id='tissues'>
                <option  v-for="el in tissues">{{ el.biospecimen_type }}</option>
            </datalist>
            <br>
      
           
            </fieldset>   
                 
            <fieldset class="form-group">
                 <input type="button" class="btn btn-success " @click="addTissues()" value="Добавить ткань">
                 
            </fieldset>   
            </div>
            
            <div class="col-md-6 col-sm-12">
            
            <h4>Добавить болезнь</h4>
            
            <fieldset class="form-group">
            <input type="text" class="form-control" v-model="mkbName"  placeholder="Наименование болезни" list='mkb'>
            <datalist id='mkb'>
                <option  v-for="el in mkb">{{ el.disease_name_russian }} {{ el.disease_name_russian_old }}</option>
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
                       <th v-for="diseases in tableDiseases" class="relative"> {{ diseases.disease }} <i class="fa ft-delete r0" @click="deleteDisease(diseases)"></i></th>
                      </tr>
                      </thead> 
                      <tbody>
                      <tr v-for="tissues in tableTissues">
                      <th class="relative sticky-col first-col" > <i class="fa ft-delete r0" @click="deleteTissues(tissues)"></i> {{ tissues.tissues }}</th>
                       <th v-for="diseases in tableDiseases" style="min-width: 200px" > 
                        
                        
                        <div v-if="searchType(tissues.id,diseases.id)">
                        
                        <select name="" :id="'select_' + tissues.id + '_' + diseases.id "  class="form-control" @change="changeType(tissues.id, diseases.id)">
                        
                        <option value="Number" v-if="searchType(tissues.id,diseases.id).typevalue == 'Number'" selected>Number</option>
                        <option value="Number" v-else>Number</option>
                        
                        <option value="Boolean" v-if="searchType(tissues.id,diseases.id).typevalue == 'Boolean'" selected>Boolean</option>
                        <option value="Boolean" v-else>Boolean</option>
                        
                        <option value="Text" v-if="searchType(tissues.id,diseases.id).typevalue == 'Text'" selected>Text</option>
                        <option value="Text" v-else>Text</option>
                        
                        </select>
                        <input type="text"  :id="'placeholdervalue_' + tissues.id + '_' + diseases.id " class="form-control" placeholder="Описание" :value="searchType(tissues.id,diseases.id).placeholdervalue" @input="changeType(tissues.id, diseases.id)">
                        <input type="hidden"  :id="'type_id_' + tissues.id + '_' + diseases.id " class="form-control" placeholder="Описание" :value="searchType(tissues.id,diseases.id).id">
                        
                        </div>
                        
                        
                        <div v-else>
                        <button class="btn btn-dark btn-sm btn-block" @click="addType(tissues.id,diseases.id)">Добавить</button>
                        
                        </div>
                       
                       </th>
                      </tr>
                      </tbody>
                       </table>
                       
                       </div>
                       <br>
                        <questions :uid="uid"></questions>
                       
                       
                 
                    </div> 
</div>
 `

});

