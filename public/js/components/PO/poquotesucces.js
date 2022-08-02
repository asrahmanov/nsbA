Vue.component('poquotesucces', {

    data() {
        return {
            url: '/PoQuote/GetbyFrAll', // загрузка квот
            qutes: [],
            filterqedqutes: [],
            allQuote: [],
            managerQoute: [],
            diseaseQuote: [],
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
            edit_user_id: '',
            disease_id: 0,
            sample_id: 0,
            disease_id_edit: 0,
            sample_id_edit: 0,
            site_docs: [],
            site_docs_edit: [],
            editDocPayments: [],
            SamplesTable: [],
            editSamplesTable: [],
            editingOldTable: false,
            addSmplBtnShown: false,
            btnManager: true,
            btnDate: true,
            btnSite: true,
            btnCount: true,
            btnCount2: true,
            btnDays: true,
            btnDoctor: true,
            btnPay: true,
            btnComment: true,
            btnDisease: true,
            btnBiospecimen: true,
            ManagerQ: 0,
            DiseaseQ: 0,
            sumQuote: 0,

        }
    },

    props: [
        'proj_id',
        'mode',
        'order_diseases',
        'diseases_biospecimen_types',
        'biospecimen_types',
        'back_url',
        'quotecreatevisible',
        'role',
        'user_id'
    ],

    methods: {
        openModalQuote(itemInfo) {
            $("#quoteModalPo").modal('show');
            this.editId = itemInfo.id
            this.editValue_mount = itemInfo.value_mount
            this.editDays = itemInfo.days
            this.editPrice = itemInfo.price
            this.editProj_id = itemInfo.proj_id
            this.edit_user_id = itemInfo.user_id
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
            this.editDocPayments = itemInfo.doctor_payments
        },
        editQuote() {
            if (this.editValue_mount == 0 || this.editValue_mount == '') {
                toastr.error('Укажите кол-во в месяц');
            } else if (this.editDays == 0 || this.editDays == '') {
                toastr.error('Укажите срок в днях');
            } else if (this.editPrice < 0 || this.editPrice == '') {
                toastr.error('Укажите стоимость оплаты сайту');
            } else {
                let data = {
                    'id': this.editId,
                    'value_mount': this.editValue_mount,
                    'days': this.editDays,
                    'price': this.editPrice,
                    'proj_id': this.editProj_id,
                    'user_id': this.edit_user_id,
                };
                this.quoteAdd(data);
                $("#quoteModalPo").modal('hide');
            }
        },
        quoteAdd(data, clear = true) {
            if (this.btnDisabled) {
                return null
            } else {
                this.$parent.putJson(`/PoQuote/save`, data).then(datas => {
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
                        setTimeout(() => {
                            this.quoteLoad()
                        }, 500)
                    }
                })
                this.btnDisabled = true
                setTimeout(() => {
                    this.btnDisabled = false
                }, 1500)
            }
        },
        // Проверка формы на заполнение
        findSite(site_id) {
            return this.sites.find(el => {
                if (el.site_id == site_id) {
                    return el
                }
            })
        },
        filterQuote() {
            if (this.ManagerQ == 0 && this.DiseaseQ == 0) {
                this.filterqedqutes = this.allQuote
            } else if (this.ManagerQ != 0 && this.DiseaseQ == 0) {
                this.filterqedqutes = this.allQuote.filter(el => {
                    if (el.user_id == this.ManagerQ) {
                        return true
                    }
                });
            } else if (this.ManagerQ == 0 && this.DiseaseQ != 0) {
                this.filterqedqutes = this.allQuote.filter(el => {
                    if (el.disease == this.DiseaseQ) {
                        return true
                    }
                });
            } else if (this.ManagerQ != 0 && this.DiseaseQ != 0) {
                this.filterqedqutes = this.allQuote.filter(el => {
                    if (el.disease == this.DiseaseQ && el.user_id == this.ManagerQ) {
                        return true
                    }
                });
            }
        },

        quoteLoad() {
            this.qutes = [];
            this.filterqedqutes = [];
            this.allQuote = [];
            this.$parent.getJson(`${this.url}/?proj_id=${this.proj_id}`)
                .then(data => {

                    let i = 0;
                    for (let key in data.result) {
                        this.qutes.push(data.result[key]);
                        this.filterqedqutes.push(data.result[key]);
                        this.allQuote.push(data.result[key]);
                        this.sumQuote += data.result[key].quote_value * 1
                        if (this.managerQoute.filter(e => e.fio == data.result[key].lasttname + ' ' + data.result[key].firstname).length > 0) {

                        } else {
                            this.managerQoute.push({
                                fio: data.result[key].lasttname + ' ' + data.result[key].firstname,
                                user_id: data.result[key].user_id
                            });
                        }
                        if (this.diseaseQuote.filter(e => e.disease == data.result[key].disease).length > 0) {

                        } else {
                            this.diseaseQuote.push({disease: data.result[key].disease});
                        }
                    }
                    this.removeLoader();
                });

            console.log('--->', this.filterqedqutes);

        },


        show() {
            this.showBlock = !this.showBlock;
        },

        removeLoader() {
            if (document.getElementById('loading')) {
                document.getElementById('loading').remove();
            }
        },


        getCompaniesContacts() {
            this.$parent.postJson('/CompaniesContacts/getSiteContactsBySiteId/', {site_id: this.site_id})
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

        checkArray() {
            return this.filterqedqutes.length > 0
        },


        successQuote(item) {
            let quoteValue = document.getElementById('val_quote_' + item.id).value;
            let quote_info = document.getElementById('val_quote_info_' + item.id).value;
            this.$parent.putJson('/PoQuote/QuteUpdate', {
                quote_id: item.id,
                quoteValue: quoteValue,
                quote_info: quote_info
            })

            item.quote_value = quoteValue
            item.quote_info = quote_info
            toastr.success('Успешно')

            //this.quoteLoad();
        },

        DisabledQuote(item, disabled) {

            if (disabled == 1) {

                this.successQuote(item); // сохрананение при блокировке полей

                //  Отправка письма менеджеру при согласовании
                this.$parent.postJson('/Mail/SendMailQouteSuccess', {
                        proj_id: item.proj_id,
                        user_id: item.user_id,
                        quote_value: item.quote_value,
                        quote_id: item.id,
                    }
                ).then(data => {
                    console.log('---->', data)
                })
                console.log(item)
            }

            this.$parent.putJson('/PoQuote/QuteUpdate', {
                quote_id: item.id,
                disabled: disabled
            })

            item.disabled = disabled
            //this.quoteLoad();
        },

        toggleOldQuote(id) {
            $(`#oldquote_${id}`).toggleClass('d-none')
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
        checkDocPaymentArray(){
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
        document.addEventListener('DOMContentLoaded', function () {
            $("#biotypes_table").dataTable()
        })
    },

    template: `
    <div>
        <div class="modal fade text-left" id="quoteModalPo" role="dialog" aria-hidden="true">
            <div id="modalview">
                <div class="modal-dialog" role="document" style="min-width: 50%; max-width: 2048px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-road2"></i> <b>Редактирование
                                квоты</b></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <fieldset class="form-group">
                                <label class="quote-label">Кол-во в месяц</label>
                                <input class="form-control" type="number" v-model="editValue_mount">
                            </fieldset>
                            <fieldset class="form-group">
                                <label class="quote-label">Срок в днях</label>
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
                                        <select v-model="doctor_payment.doc_id" class="form-control contacts_select"
                                                 :disabled="user_id != 72">
                                            <option :value="doc.id" v-for="doc in site_docs_edit">
                                                {{ doc.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <label class="quote-label" for="filter_users">Оплата доктору руб.</label>
                                        <input class="form-control" type="number" v-model="doctor_payment.doc_payment" :disabled="user_id != 72">
                                    </div>
                                    <div class="col-1" style="margin-top: 28px" v-if="!(i === 0 && editDocPayments.length === 1) && user_id == 72">
                                        <button class="btn btn-danger" @click="removeDocPaymentEdit(i)">-</button>
                                    </div>
                                    <div class="col-1" style="margin-top: 28px" v-if="i === 0 && user_id == 72">
                                        <button class="btn btn-success" @click="addDocPaymentEdit()">+</button>
                                    </div>
                                </div>
                            </div>
    
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn  btn-success" @click="editQuote()">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="quote-form row">
                <div>
                    <button v-bind:class="{'btn btn-danger': !btnManager, 'btn btn-success': btnManager }"
                            @click="btnManager = !btnManager" style="padding: 5px;">Менеджер
                    </button>
                    <button v-bind:class="{'btn btn-danger': !btnDate, 'btn btn-success': btnDate }"
                            @click="btnDate = !btnDate" style="padding: 5px;">Дата
                    </button>
                    <button v-bind:class="{'btn btn-danger': !btnSite, 'btn btn-success': btnSite }"
                            @click="btnSite = !btnSite" style="padding: 5px;">Сайт
                    </button>
                    <button v-bind:class="{'btn btn-danger': !btnCount, 'btn btn-success': btnCount }"
                            @click="btnCount = !btnCount" style="padding: 5px;">Кол-во
                    </button>
                    <button v-bind:class="{'btn btn-danger': !btnDays, 'btn btn-success': btnDays }"
                            @click="btnDays = !btnDays" style="padding: 5px;">Срок в днях
                    </button>
                    <button v-bind:class="{'btn btn-danger': !btnPay, 'btn btn-success': btnPay }" @click="btnPay = !btnPay"
                            style="padding: 5px;">Оплата
                    </button>
                    <button v-bind:class="{'btn btn-danger': !btnDoctor, 'btn btn-success': btnDoctor }"
                            @click="btnDoctor = !btnDoctor" style="padding: 5px;">Оплаты доктору
                    </button>
                    <button v-bind:class="{'btn btn-danger': !btnComment, 'btn btn-success': btnComment }"
                            @click="btnComment = !btnComment" style="padding: 5px;">Комментарий
                    </button>
                    <button v-bind:class="{'btn btn-danger': !btnDisease, 'btn btn-success': btnDisease }"
                            @click="btnDisease = !btnDisease" style="padding: 5px;">Болезнь
                    </button>
                    <button v-bind:class="{'btn btn-danger': !btnBiospecimen, 'btn btn-success': btnBiospecimen }"
                            @click="btnBiospecimen = !btnBiospecimen" style="padding: 5px;">Образец
                    </button>
                </div>
                <div class="row" style="width: 100%; margin-bottom: 15px;">
                    <div class="col-md-4 col-sm-12 mt-2">
                        <select v-model="ManagerQ" class="form-control " @change="filterQuote()">
                            <option value="0">Выберите менеджера</option>
                            <option :value="manager.user_id" v-for="manager in managerQoute">{{ manager.fio }}</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-12 mt-2">
                        <select v-model="DiseaseQ" class="form-control" @change="filterQuote()">
                            <option value="0">Выберите Болезнь</option>
                            <option :value="d.disease" v-for="d in diseaseQuote">{{ d.disease }}</option>
                        </select>
                    </div>
                </div>
                <div class="row" style="width: 100%"></div>
                <div style="overflow-y: auto;">
                    <h3>Таблица квот</h3>
                    <div>
                        <table class="table table-striped table-bordered file-export dataTable">
                            <thead>
                            <tr>
                                <th v-show="btnManager">Менеджер</th>
                                <th v-show="btnDate">Дата</th>
                                <th v-show="btnSite">Сайт</th>
                                <th v-show="btnCount">Кол-во в месяц</th>
                                <th v-show="btnDays">Срок в днях</th>
                                <th v-show="btnPay">Оплата сайту</th>
                                <th v-show="btnDoctor">Оплаты доктору</th>
                                <th v-show="user_id == '72' || role == '6'">Редактировать</th>
                                <th v-show="btnComment">Комментарий</th>
                                <th v-show="btnDisease">Болезнь</th>
                                <th v-show="btnBiospecimen">Образец (модификация)</th>
                                <th>Кол-во ({{ sumQuote}})</th>
                                <th>Комментарий</th>
                                <th v-show="user_id == '56' || user_id == '55' || role == '6' ||  user_id == '72' ">Действие</th>
                                <th v-show="user_id == '56' || user_id == '55' || role == '6' ||  user_id == '72' ">Действие</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody v-for="item in filterqedqutes">
                            <tr>
                                <td v-show="btnManager">{{item.lasttname }} {{ item.firstname}}</td>
                                <td v-show="btnDate">{{ item.created_at.split(' ')[0].split('-').reverse().join('.') }}</td>
                                <td v-show="btnSite">{{ item.site_id-1 }} {{ item.site_name }}  </td>
                                <td v-show="btnCount">{{ item.value_mount }}</td>
                                <td v-show="btnDays">{{ item.days }}</td>
                                <td v-show="btnPay">{{ item.price }}</td>
                                <td v-show="btnDoctor">
                                    <table>
                                        <tr v-for="doc_payment in item.doctor_payments">
                                            <td>{{ doc_payment.doc_name }}</td>
                                            <td>{{ doc_payment.doc_payment }}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td v-show="user_id == '72' || role == '6'">
                                    <button class="btn btn-sm btn-dark" @click="openModalQuote(item)" >Редактировать</button>
                                </td>
                                <td v-show="btnComment" v-html="item.comment"></td>
                                <td v-show="btnDisease">{{ item.disease }}</td>
                                <td v-show="btnBiospecimen">
                                    <table>
                                        <tr v-for="bio_type in item.samples_table">
                                            <td>{{bio_type.biospecimen_type}} {{bio_type.biospecimen_type_russian}}
                                                {{bio_type.mod}}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <input type="number" class="form-control" min="0" :id="'val_quote_'+ item.id"
                                           :value="item.quote_value">
                                </td>
                                <td style="min-width: 250px;">
                                    <textarea name="" id="" cols="100" rows="10" class="form-control"
                                              :id="'val_quote_info_'+ item.id"
                                              v-if="user_id == '56' || user_id == '55' || role == '6' || user_id == '72' ">{{ item.quote_info}}</textarea>
                                </td>
                                <td v-show="user_id == '56' || user_id == '55' || role == '6' ||  user_id == '72' ">
                                    <button class="btn btn-success" @click="successQuote(item)">Сохранить</button>
                                </td>
                                <td v-show="user_id == '56' || user_id == '55' || role == '6' ||  user_id == '72' " v-if="!item.disabled">
                                    <button class="btn btn-dark" @click="DisabledQuote(item,1)">Одобрить</button>
                                </td>
                                <td v-show="user_id == '56' || user_id == '55' || role == '6' ||  user_id == '72' " v-if="item.disabled">
                                    <button class="btn btn-danger" @click="DisabledQuote(item,0)">Разблокировать</button>
                                </td>
                                <td>
                                    <button class="btn btn-info" v-if="item.old" @click="toggleOldQuote(item.id)">Старая
                                        квота
                                    </button>
                                </td>
                            </tr>
                            <tr style="background-color: lightgrey;" v-if="item.old" :id="'oldquote_' + item.id"
                                class="d-none">
                                <td v-show="btnManager">{{ item.old.lasttname }} {{ item.old.firstname}}</td>
                                <td v-show="btnDate">{{ item.old.created_at.split(' ')[0].split('-').reverse().join('.')
                                    }}
                                </td>
                                <td v-show="btnSite">{{ item.old.site_name }}</td>
                                <td v-show="btnCount">{{ item.old.value_mount }}</td>
                                <td v-show="btnDays">{{ item.old.days }}</td>
                                <td v-show="btnPay">{{ item.old.price }}</td>
                                <td v-show="btnDoctor">
                                    <table>
                                        <tr v-for="doc_payment in item.old.doctor_payments">
                                            <td>{{ doc_payment.doc_name }}</td>
                                            <td>{{ doc_payment.doc_payment }}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td v-show="btnComment" v-html="item.old.comment"></td>
                                <td v-show="btnDisease">{{ item.old.disease }}</td>
                                <td v-show="btnBiospecimen">
                                    <table>
                                        <tr v-for="bio_type in item.old.samples_table">
                                            <td>{{bio_type.biospecimen_type}} {{bio_type.biospecimen_type_russian}}
                                                {{bio_type.mod}}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td colspan="5"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="loading" class="placeholder-app" style="text-align: center">
            <img src="../../../app-assets/img/loaders/rolling.gif" alt="loading">
        </div>
    </div>
    `
});