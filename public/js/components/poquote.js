Vue.component('poquote', {

    data() {
        return {
            url: '/PoQuote/getbyFr', // загрузка квот
            qutes: [],
            filterqedqutes: [],
            urlmySites: '/managerSites/GetQuotableSitesByUser', // Загрузка сайтов
            sites: [],
            site_id: 0,
            value_mount: '',
            days: '',
            price: '',
            comment: '',
            showBlock: false,
            textOtkaz: '',
            // Данные для редактирования
            editId: '',  // ID qoute
            editSiteId: '',  // ID Сайта
            editValue_mount: '',  // Кол-во в месяц
            editDays: '',  // Срок в днях
            editPrice: '',  // Оплата сайту
            editComment: '',  // Комментарий
            editProj_id: '',
            disease_id: 0,
            sample_id: 0,
            disease_id_edit: 0,
            sample_id_edit: 0,
            doctors_payments: [{doc_id: 0, doc_payment: 0, doc_name: 'Выберите доктора'}],
            site_docs: [],
            site_docs_edit: [],
            editDocPayments: [],
            SamplesTable: [],
            editSamplesTable: [],
            editingOldTable: false,
            addSmplBtnShown: false,
            quote_disabled: false,
            btnDisabled: false
        }
    },

    props: [
        'proj_id',
        'mode',
        'order_diseases',
        'diseases_biospecimen_types',
        'biospecimen_types',
        'back_url',
        'quotecreatevisible'
    ],

    methods: {




        // Добавление квоты
        quoteAdd(data, clear = true) {
            if (this.btnDisabled) {
                return null
            } else {
                this.$parent.putJson(`/PoQuote/save`, data).then(datas => {
                    if (data.id === undefined) { // New Quote
                        if (datas.result > 0) {
                            this.doctors_payments.forEach(doc_payment => {
                                this.$parent.putJson('/PoQuoteDoctor/save', {
                                    quote_id: datas.result,
                                    doc_id: doc_payment.doc_id,
                                    doc_payment: doc_payment.doc_payment
                                })
                            })
                            console.log('------>', this.quote_id)
                            this.SamplesTable.filter(s => s.enabled).forEach(disease_biotype => {
                                this.$parent.putJson('/PoQuoteSample/save', {
                                    quote_id: datas.result,
                                    disease_id: disease_biotype.disease_id,
                                    biospecimen_type_id: disease_biotype.sample_id,
                                    mod_id: disease_biotype.mod_id
                                })
                            })

                            console.log('---->', this.disease_id);

                            data.id = datas.result;
                            data.site_name = this.findSite(this.site_id).site_name;
                            data.disease = $("#disease_select_po option:selected").html()
                            data.sample = $("#sample_select option:selected").html()
                            let now = new Date()
                            data['created_at'] = now.getYear() + "-" + now.getMonth() + "-" + now.getDay() + " " +
                                now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();

                            this.qutes.push(data);
                            this.filterqedqutes.push(data);

                            if (clear) {
                                this.doctors_payments = [{doc_id: 0, doc_payment: 0, doc_name: 'Выберите доктора'}]
                                this.SamplesTable = []
                                this.site_id = 0;
                                this.value_mount = '';
                                this.days = '';
                                this.price = '';
                                this.comment = '';
                                this.disease_id = '';
                                this.sample_id = '';
                                tinymce.get("new_comment").setContent("");
                            }


                            toastr.success('Квота добавлена');
                            setTimeout(() => {
                                this.quoteLoad()
                            }, 500)
                        }
                    } else { // Quote is edited
                        if (datas.result) {
                            this.editDocPayments.forEach(doc_payment => {
                                let docPayData = {
                                    quote_id: this.editId,
                                    doc_id: doc_payment.doc_id,
                                    doc_payment: doc_payment.doc_payment
                                }
                                if (doc_payment.id)
                                    docPayData.id = doc_payment.id
                                this.$parent.putJson('/PoQuoteDoctor/save', docPayData)
                            })
                            this.editSamplesTable.filter(s => s.enabled).forEach(disease_biotype => {
                                let data = {
                                    quote_id: this.editId,
                                    disease_id: disease_biotype.disease_id,
                                    biospecimen_type_id: disease_biotype.biospecimen_type_id,
                                    mod_id: disease_biotype.mod_id
                                }
                                if (disease_biotype.db_id)
                                    data.id = disease_biotype.db_id
                                this.$parent.putJson('/PoQuoteSample/save', data)
                            })
                            this.quoteLoad();
                        }
                    }
                })
                this.btnDisabled = true
                setTimeout(() => {
                    this.btnDisabled = false
                }, 1500)
            }
        },

        // Проверка формы на заполнение
        quoteCheck(clear = true) {
            if (this.site_id == 0 || this.site_id == '') {
                toastr.error('Выберите сайт');
            } else if (this.value_mount == 0 || this.value_mount == '') {
                toastr.error('Укажите кол-во в месяц');
            } else if (this.days == 0 || this.days == '') {
                toastr.error('Укажите срок в днях');
            } else if (this.price < 0 || this.price == '') {
                toastr.error('Укажите стоимость оплаты сайту');
            } else if (!this.disease_id) {
                toastr.error('Выберите болезнь');
            } else if (!this.SamplesTable.some(s => s.enabled)) {
                toastr.error('Выберите хотя бы один образец');
            } else if (this.doctors_payments[0].doc_id === 0) {
                toastr.error('Выберите хотя бы одного доктора');
            } else {
                let data = {
                    site_id: this.site_id,
                    value_mount: this.value_mount,
                    days: this.days,
                    price: this.price,
                    comment: tinyMCE.get('new_comment').getContent(),
                    proj_id: this.proj_id,
                    disease_id: this.disease_id/*,
                    sample_id: this.sample_id*/
                }
                this.quoteAdd(data, clear);
            }
        },

        findSite(site_id) {
            return this.sites.find(el => {
                if (el.site_id == site_id) {
                    return el
                }
            })
        },

        quoteDell(quote) {
            this.$parent.deleteJson(`/PoQuote/dell`, {id: quote.id})
                .then(data => {
                    if (data.result == 0) {
                        toastr.success('Успешно удалено');
                        this.filterqedqutes.splice(this.filterqedqutes.indexOf(quote), 1)
                        this.qutes.splice(this.filterqedqutes.indexOf(quote), 1)
                    }
                })
        },

        quoteLoad() {
            this.qutes = [];
            this.filterqedqutes = [];
            this.$parent.getJson(`${this.url}/?proj_id=${this.proj_id}`)
                .then(data => {
                    console.log(data)
                    let i = 0;
                    for (let key in data.result) {
                        this.qutes.push(data.result[key]);
                        this.filterqedqutes.push(data.result[key]);
                    }
                    this.removeLoader();
                });
        },

        show() {
            this.showBlock = !this.showBlock;
        },

        removeLoader() {
            if(document.getElementById('loading')){
                document.getElementById('loading').remove();
            }
        },

        otkaz() {
            if (this.textOtkaz == '') {
                toastr.error('Введите причину отказа');
            } else if (this.filterqedqutes.length > 0) {
                toastr.error('Нельзя отказать отказаться от квотирования когда существует квота');
            } else {
                let data = {
                    'proj_id': this.proj_id,
                    'status_id': 3,
                    'comments': this.textOtkaz
                }
                this.$parent.putJson(`/PoWorksheets/changeStatus`, data)
                    .then(datas => {
                        location.href = '../../PoWorksheets'
                    })
            }
        },

        openModalQuote(itemInfo) {

            $('#quoteModalpo').modal('show');
            this.editId = itemInfo.id
            this.editSiteId = itemInfo.site_id
            this.editValue_mount = itemInfo.value_mount
            this.editDays = itemInfo.days
            this.editPrice = itemInfo.price
            this.disease_id_edit = itemInfo.disease_id
            this.sample_id_edit = itemInfo.sample_id

            console.log('--->',this.sample_id_edit)
            console.log(this.editSiteId)
            console.log('site_id',this.editDays)

            this.setDiseaseSamplesEdit()
            tinyMCE.get('edit_comment').setContent(itemInfo.comment ?? '')
            this.editProj_id = itemInfo.proj_id
            this.$parent.postJson('/CompaniesContacts/getSiteContactsBySiteId/', {site_id: itemInfo.site_id})
                .then(data => {
                    this.site_docs_edit = []
                    data.result.forEach(contact => {
                        this.site_docs_edit.push({
                            id: contact.id,
                            name: `${contact.lastname} ${contact.firstname} ${contact.patronymic}`
                        })
                    })
                })
            this.editSamplesTable = itemInfo.samples_table
            this.editDocPayments = itemInfo.doctor_payments
        },

        editQuote() {
            if (this.editSiteId == 0 || this.editSiteId == '') {
                toastr.error('Выберите сайт');
            } else if (this.editValue_mount == 0 || this.editValue_mount == '') {
                toastr.error('Укажите кол-во в месяц');
            } else if (this.editDays == 0 || this.editDays == '') {
                toastr.error('Укажите срок в днях');
            } else if (this.editPrice < 0 || this.editPrice == '') {
                toastr.error('Укажите стоимость оплаты сайту');
            } else if (!this.disease_id_edit) {
                toastr.error('Выберите болезнь');
            } else if (!this.editSamplesTable.some(s => s.enabled)) {
                toastr.error('Выберите хотя бы один образец');
            } else {
                let data = {
                    'id': this.editId,
                    'site_id': this.editSiteId,
                    'value_mount': this.editValue_mount,
                    'days': this.editDays,
                    'price': this.editPrice,
                    'comment': tinyMCE.get('edit_comment').getContent(),
                    'proj_id': this.editProj_id,
                    'disease_id': $("#disease_select_po_edit").val(),
                    'sample_id': $("#sample_select_edit").val()
                };
                this.quoteAdd(data);
                $('#quoteModalpo').modal('hide');
            }
        },

        getCompaniesContacts() {
            this.$parent.postJson('/CompaniesContacts/getSiteContactsBySiteId/', { site_id: this.site_id })
                .then(data => {
                    this.site_docs = []
                    data.result.forEach(contact => {
                        let disabled = !parseInt(contact.quotable)
                        this.site_docs.push({
                            id: contact.id,
                            name: `${contact.lastname} ${contact.firstname} ${contact.patronymic}`,
                            disabled
                        })
                        this.site_docs_edit.push({
                            id: contact.id,
                            name: `${contact.lastname} ${contact.firstname} ${contact.patronymic}`,
                            disabled
                        })
                    })
                });
        },

        setDiseaseSamples() {
            let diseaseIdSelected = this.order_diseases.filter(el => el.id === this.disease_id)[0]
            this.SamplesTable = []
            this.diseases_biospecimen_types.forEach(bio_type => {
                if (bio_type.disease_id === diseaseIdSelected.disease_id) {
                    bio_type.enabled = true
                    this.SamplesTable.push(bio_type)
                }
            })
            this.addSmplBtnShown = true
        },
        removeDiseaseSample(id) {
            this.SamplesTable.forEach((bio_type, i) => {
                if (bio_type.id === id)
                    this.SamplesTable[i].enabled = false
            })
        },
        restoreDiseaseSample(id) {
            this.SamplesTable.forEach((bio_type, i) => {
                if (bio_type.id === id)
                    this.SamplesTable[i].enabled = true
            })
        },
        openSamplesModal(editing = false) {
            this.editingOldTable = editing
            $('#sampleAddModalpo').modal('show');
        },
        addBioType(i) {
            this.biospecimen_types[i].enabled = true
            let disease
            if (this.disease_id_edit)
                disease = this.order_diseases.filter(el => el.id === this.disease_id_edit)[0]
            else
                disease = this.order_diseases.filter(el => el.id === this.disease_id)[0]
            this.biospecimen_types[i].disease_id = disease.disease_id
            if (this.editingOldTable) {
                delete this.biospecimen_types[i].id
                this.biospecimen_types[i].quote_id = this.editId
                this.editSamplesTable.push(this.biospecimen_types[i])
            } else {
                this.SamplesTable.push(this.biospecimen_types[i])
            }
        },

        setDiseaseSamplesEdit() {
            let diseaseIdSelected = this.order_diseases.filter(el => el.id === this.disease_id_edit)[0]
            this.editSamplesTable = [];

            this.diseases_biospecimen_types.forEach(bio_type => {
                if (bio_type.disease_id === diseaseIdSelected.disease_id) {
                    bio_type.enabled = true
                    this.editSamplesTable.push(bio_type)
                }
            })
        },
        removeEditDiseaseSample(i) {
            let db_id = this.editSamplesTable[i].db_id
            if (db_id)
                this.$parent.putJson('/PoQuoteSample/delete', {id: db_id})
            this.editSamplesTable.splice(i, 1)
        },

        addDocPayment() {
            this.doctors_payments.push({doc_id: 0, doc_payment: 0, doc_name: 'Выберите доктора'})
        },
        removeDocPayment(i) {
            this.doctors_payments.splice(i, 1)
        },

        addDocPaymentEdit() {
            this.editDocPayments.push({doc_id: 0, doc_payment: 0, doc_name: 'Выберите доктора', id: 0})
        },
        removeDocPaymentEdit(i) {
            let db_id = this.editDocPayments[i].id
            if (db_id)
                this.$parent.putJson('/PoQuoteDoctor/dell', {id: db_id})
            this.editDocPayments.splice(i, 1)
        },
        checkArray(){
            return this.filterqedqutes.length > 0
        }

    },

    mounted() {


        this.quoteLoad();

        this.$parent.getJson(`${this.urlmySites}`)
            .then(data => {
                let i = 0;
                for (let key in data.result) {
                    this.sites.push(data.result[key]);
                }
            });
        // $(document).ready(() => {
        //     $("#biotypes_table").dataTable()
        // })
        document.addEventListener('DOMContentLoaded', function () {
            // setTimeout(() => {
                $("#biotypes_table").dataTable()
            // }, 5000)
        })
    },


    template: `
    <div>
   
       <div v-if="mode">
          <div class="quote-form row mt-5">
             <h3>Таблица квот</h3>
             <div style="overflow-y: auto;">
             <table class="table table-striped table-bordered file-export dataTable" >
                <thead>
                   <tr>
                      <th>Дата</th>
                      <th>Сайт</th>
                      <th>Кол-во в месяц</th>
                      <th>Срок в днях</th>
                      <th>Оплата сайту</th>
                      <th>Оплаты доктору</th>
                      <th>Комментарий</th>
                      <th>Болезнь</th>
                      <th>Образец</th>
                   </tr>
                </thead>
                <tbody>
                    <tr v-for="item in filterqedqutes">
                        <td>{{ item.created_at }}</td>
                        <td>{{ item.site_name }}</td>
                        <td>{{ item.value_mount }}</td>
                        <td>{{ item.days }}</td>
                        <td>{{ item.price }}</td>
                        <td>
                        <table>
                            <tr v-for="doc_payment in item.doctor_payments">
                                <td>{{ doc_payment.doc_name }}</td>
                                <td>{{ doc_payment.doc_payment }}</td>
                            </tr>                        
                        </table>
                        </td>
                      <td v-html="item.comment"></td>
                   </tr>
                </tbody>
             </table>
             </div>
             <div style="overflow-y: auto">
             <table class="table table-striped table-bordered file-export dataTable2">
                <thead>
                   <tr>
                      <th>Сайт</th>
                      <th>Кол-во в месяц</th>
                      <th>Срок в днях</th>
                      <th>Оплата сайту</th>
                      <th>ФИО доктора</th>
                      <th>Оплата доктору</th>
                      <th>Комментарий</th>
                      <th>Болезнь</th>
                      <th>Образец</th>
                   </tr>
                </thead>
                <tbody>
                    <tr v-for="item in filterqedqutes">
                        <td>{{ item.site_name }}</td>
                        <td>{{ item.value_mount }}</td>
                        <td>{{ item.days }}</td>
                        <td>{{ item.price }}</td>
                        <td>{{ item.price_doc }}</td>
                        <td>{{ item.doc_name }}</td>
                        <td v-html="item.comment"></td>
                   </tr>
                </tbody>
             </table>
             </div>
          </div>
       </div>
       
       
       <div v-else>
      
        <!--        NEW QUOTES FORM         -->
       <div v-if="quotecreatevisible == 1">
          <button class="btn btn-outline-black" @click="show()">Отказаться Квотировать</button> 
          <div class="quote-form row mb-3 " v-if="showBlock" >
             <div class="col-md-10 col-sm-12">
                <textarea name=""style="width: 100%" rows="3" placeholder="Причина отказа" class="form-control" v-model="textOtkaz"></textarea>
             </div>
             <div class="col-md-2 col-sm-12">
                <button class="btn btn-danger btn-block" @click="otkaz()">Подтвердить отказ</button> 
             </div>
          </div>
          <div class="quote-form row">
             <div class="col-md-3 col-sm-12">
                <label  class="quote-label" for="site_id">Сайт </label>
                <select class="form-control" v-model="site_id" @change="getCompaniesContacts()">
                   <option value="0">Выберите сайт</option>
                   <option v-for="site in sites" :key="site.site_id" :value="site.site_id">{{ site.site_name}}</option>
                </select>
             </div>
             <div class="col-md-3 col-sm-12">
                <label class="quote-label">Кол-во в месяц</label>
                <input class="form-control" type="number" v-model="value_mount">
             </div>
             
             <div class="col-md-3 col-sm-12">
                <label class="quote-label">Срок в днях</label>
                <input class="form-control" type="number" v-model="days">
             </div>
             <div class="col-md-3 col-sm-12">
                <label class="quote-label">Оплата сайту руб.</label>
                <input class="form-control" type="number" v-model="price">
             </div>
             <div class="col-md-3 col-sm-12">
                <label class="quote-label">Болезнь</label>
                <select class="form-control" id="disease_select_po" @change="setDiseaseSamples()" v-model="disease_id">
                    <option value="0">Выберите болезнь</option>
                    <option v-for="disease in order_diseases" :value="disease.id">
                        {{ disease.disease }} ({{ disease.mutation }})</option>
                </select>
             </div>
             <div class="col-md-3 col-sm-12" v-if="addSmplBtnShown" style="padding-top: 28px;">
                <button class="btn btn-info" @click="openSamplesModal()">Добавить свой образец</button>
             </div>
             <div class="col-12" style="margin-top: 32px;">
                <table class="table table-striped table-bordered hidden biotypesDataTable">
                    <thead>
                        <tr>
                            <td>Образец</td>
                            <td>Удалить</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="bio_type in SamplesTable">
                            <td :style="{ color: bio_type.enabled ? 'black' : '#CCC' }">{{bio_type.biospecimen_type}} {{bio_type.biospecimen_type_russian}} {{bio_type.modification}}</td>
                            <td>
                                <button v-if="bio_type.enabled" class="btn btn-sm btn-danger" @click="removeDiseaseSample(bio_type.id)">Удалить</button>
                                <button v-else class="btn btn-sm btn-info" @click="restoreDiseaseSample(bio_type.id)">Восстановить</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>            
             <div class="col-md-6 col-sm-12">
                <label class="quote-label">Комментарий</label>
                <textarea class="form-control" rows="5" id="new_comment"></textarea>
             </div>
             <div class="col-md-12 col-sm-12">
                <div class="row" v-for="(doctor_payment, i) in doctors_payments">
                    <div class="col-5">
                        <label class="quote-label" for="filter_users">ФИО доктора</label>
                        <select v-model="doctor_payment.doc_id" class="form-control contacts_select">
                            <option value="0">Выберите доктора</option>
                            <option :value="doc.id" v-for="doc in site_docs">{{ doc.name }}</option>
                        </select>
                    </div>
                    <div class="col-5">
                        <label class="quote-label" for="filter_users">Оплата доктору руб.</label>
                        <input class="form-control" type="number" v-model="doctor_payment.doc_payment">
                    </div>
                    <div class="col-1" style="margin-top: 28px" v-if="i !== 0">
                        <button class="btn btn-danger" @click="removeDocPayment(i)">-</button>
                    </div>
                    <div class="col-1" style="margin-top: 28px" v-if="i === 0">
                        <button class="btn btn-success" @click="addDocPayment()">+</button>
                    </div>
                </div>   
             </div>
             <div class="col-md-12 col-sm-12" style="text-align: right; margin-top: 10px">
                <button class="btn btn-success" @click="quoteCheck(true)">Квотировать</button>
                <button class="btn btn-dark" @click="quoteCheck(false)">Квотировать (без очистки данных)</button>
             </div>
            
          </div>
          </div>
          
          <!--        QUOTES TABLES         -->
          <div class="quote-form row mt-5">
             <h3>Таблица квот</h3>
             <div style="overflow-y: auto;">
             <table class="table table-striped table-bordered file-export dataTable" v-if="checkArray()">
                <thead>
                   <tr>
                        <th>Дата</th>
                        <th>Сайт</th>
                        <th>Кол-во в месяц</th>
                        <th>Срок в днях</th>
                        <th>Оплата сайту</th>
                        <th>Оплаты доктору</th>
                        <th>Комментарий</th>
                        <th>Болезнь</th>
                        <th>Образец (модификация)</th>
                        <th>Комментарии дир. Операций</th>
                       
                        <th v-if="quotecreatevisible == 1">Редактировать</th>
                        <th v-if="quotecreatevisible == 1">Удалить</th>
                       
                   </tr>
                </thead>
                <tbody>
                   <tr v-for="item in filterqedqutes">
                        <td>{{ item.created_at.split(' ')[0].split('-').reverse().join('.') }}</td>
                        <td>{{ item.site_name }}</td>
                        <td>{{ item.value_mount }}</td>
                        <td>{{ item.days }}</td>
                        <td>{{ item.price }}</td>
                        <td>
                        <table>
                            <tr v-for="doc_payment in item.doctor_payments">
                                <td>{{ doc_payment.doc_name }}</td>
                                <td>{{ doc_payment.doc_payment }}</td>
                            </tr>                        
                        </table>
                        </td>
                        <td v-html="item.comment"></td>
                        <td>{{ item.disease }}</td>
                        <td>
                        <table>
                            <tr v-for="bio_type in item.samples_table">
                                <td>{{bio_type.biospecimen_type}} {{bio_type.biospecimen_type_russian}} {{bio_type.mod}}</td>
                            </tr>
                        </table>
                        </td>
                        <td>{{ item.quote_info }}</td> 
                        <td v-if="quotecreatevisible == 1" v-show="!item.disabled"><button class="btn btn-sm btn-dark" @click="openModalQuote(item)" >Редактировать</button></td>
                        <td v-if="quotecreatevisible == 1" v-show="!item.disabled"><button class="btn btn-sm btn-danger" @click="quoteDell(item)" >Удалить</button></td>
                        <td align="center"  v-show="item.disabled" colspan="2">Согласовано количество:<br> {{ item.quote_value}} </td>
                   </tr>
                </tbody>
             </table>
             </div>
           
        
            <!--        QUOTE EDIT MODAL         -->    
             <div class="modal fade text-left" id="quoteModalpo" role="dialog" aria-hidden="true">
                <div id="modalview">
                   <div class="modal-dialog" role="document" style="min-width: 50%; max-width: 2048px;">
                      <div class="modal-content">
                         <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-road2"></i> <b>Редактирование квоты</b></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                         </div>
                         <div class="modal-body">
                            <fieldset class="form-group">
                               <label  class="quote-label" for="site_id">Сайт </label>
                               <select class="form-control" v-model="editSiteId">
                                  <option value="0">Выберите сайт</option>
                                  <option v-for="site in sites" :key="site.site_id" :value="site.site_id">{{ site.site_name}}</option>
                               </select>
                            </fieldset>
                            <fieldset class="form-group">
                               <label  class="quote-label">Кол-во в месяц</label>
                               <input class="form-control" type="number" v-model="editValue_mount">
                            </fieldset>
                            <fieldset class="form-group">
                               <label class="quote-label" >Срок в днях</label>
                               <input class="form-control" type="number" v-model="editDays">
                            </fieldset>
                            <fieldset class="form-group">
                               <label class="quote-label">Оплата сайту руб.</label>
                               <input class="form-control" type="number" v-model="editPrice">
                            </fieldset>
                            <div class="col-md-12 col-sm-12">
                                <div class="row" v-for="(doctor_payment, i) in editDocPayments">
                                    <div class="col-5">
                                        <label class="quote-label" for="filter_users">ФИО доктора</label>
                                        <select v-model="doctor_payment.doc_id" class="form-control contacts_select">
                                            <option value="0">Выберите доктора</option>
                                            <option :value="doc.id" v-for="doc in site_docs_edit" :disabled="doc.disabled">{{ doc.name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <label class="quote-label" for="filter_users">Оплата доктору руб.</label>
                                        <input class="form-control" type="number" v-model="doctor_payment.doc_payment">
                                    </div>
                                    <div class="col-1" style="margin-top: 28px" v-if="!(i === 0 && editDocPayments.length === 1)">
                                        <button class="btn btn-danger" @click="removeDocPaymentEdit(i)">-</button>
                                    </div>
                                    <div class="col-1" style="margin-top: 28px" v-if="i === 0">
                                        <button class="btn btn-success" @click="addDocPaymentEdit()">+</button>
                                    </div>
                                </div>   
                             </div>
                            <fieldset class="form-group">
                                <label class="quote-label" for="disease_select_po_edit">Болезнь</label>
                                <select class="form-control" @change="setDiseaseSamplesEdit()" id="disease_select_po_edit" v-model="disease_id_edit">
                                    <option v-for="disease in order_diseases" :value="disease.id">
                                        {{ disease.disease }} ({{ disease.mutation }})</option>
                                </select>
                            </fieldset>
                             <div class="col-12" style="margin-top: 32px;">
                                <table class="table table-striped table-bordered hidden">
                                    <thead>
                                        <tr>
                                            <td>Образец</td>
                                            <td>Удалить</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(bio_type, i) in editSamplesTable">
                                            <td>{{bio_type.biospecimen_type}} <b>{{bio_type.biospecimen_type_russian}}</b></td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" @click="removeEditDiseaseSample(i)">-</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button class="btn btn-info" @click="openSamplesModal(true)">Добавить свой образец</button>
                            </div>
                            <fieldset class="form-group">
                               <label class="quote-label" for="filter_users">Комментарий</label>
                               <textarea class="form-control" rows="5" v-model="editComment" id="edit_comment"></textarea>
                            </fieldset>
                         </div>
                         <div class="modal-footer"> 
                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn  btn-success" @click="editQuote()">Save</button>
                         </div>
                      </div>
                   </div>
                </div>
             </div>
             <!--        SAMPLE ADD MODAL         -->
             <div class="modal fade text-left" id="sampleAddModalpo" role="dialog" aria-hidden="true">
                <div id="modalview">
                    <div class="modal-dialog" role="document" style="min-width: 70%; max-width: 2048px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"><b>Добавить образец к квоте</b></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table width="100%" class="table table-striped table-bordered" id="biotypes_table">
                                <thead>
                                    <tr>
                                        <th>Biospecimen Type</th>
                                        <th>Russian name</th>
                                        <th>Добавить</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(biotype, i) in biospecimen_types">
                                        <td>{{ biotype.biospecimen_type}}</td>
                                        <td>{{ biotype.biospecimen_type_russian}}</td>
                                        <td><button type="button" class="btn btn-info" data-dismiss="modal" @click="addBioType(i)">+</button></td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                            <div class="modal-footer"> 
                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Закрыть</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
          </div>
       </div>
       <div  id="loading" class="placeholder-app" style="text-align: center">
          <img src="../../app-assets/img/loaders/rolling.gif" alt="loading" >
       </div>
    </div>
    `

});



