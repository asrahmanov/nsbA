Vue.component('offer_ru', {
    data() {
        return {

            // Текстовые переменные для название полей в таблице
            zabolevanie: 'Заболевание',
            opis: 'Описание комплекса услуг',
            kol: 'Количество',
            cenaEtapa: 'Цена этапа',
            obstoimost: 'Общая стоимость услуг (RUB)',
            // Текстовые переменные для название полей в таблице

            //
            // Тип доставки
            typeDelivery: 0,

            project: {},
            company: {},
            compamyStaff: [],
            staff: 0, // selectred staff
            offer_date: '', // DATE
            validd_date: '', // DATE
            user: '',

            users: [],
            // offer: [], // основной массив данных шаблона
            courier: [], //  Массив курьеров
            shipping: [], // Массив Courier shipping fee
            container: [], // Массив с контейнерами для транспортировки
            version: '',

            urlSections: window.location.href.split("/"),
            //socket: io.connect('https://crm.i-bios.com:8888'),

            visibledBlock: false, // Блок для добавление новой позиции

            // Добавлени новой строчки
            disease: '',
            hbs: '',
            specific_requirements: '',
            ethnicity: 'Any',
            disease_id: 0,
            type_of_collection: '',
            processing_details: '',
            turnaround_time: '',

            description: '',
            // Доп поля ы
            Indication: '',

            descriptionRow: 1,
            descriptionArray: [[]],
            inclusionCriteriaRow: 1,
            inclusionCriteriaArray: [[]],
            exclusionCriteriaRow: 1,
            exclusionCriteriaArray: [[]],
            clinicalInformationRow: 1,
            clinicalInformationArray: [[]],
            listOfSamplesRow: 1,
            listOfSamplesArray: [[]],

            timelinesArray: [[]],

            newlines_1: 1,
            newlines_2: 1,
            newlines_id: 0,

            quantity: 1,
            sumPrice: 0,
            totalPrice: 0,

            clinical_information1: '',
            clinical_information2: '',
            // list_of_samples: '',

            incoterms: '',

            shippingInfo: '',

            offerArray: [], // {index, disease, specific_requirements, ethnicity, hbs , description , quantity , sumPrice, totalPrice}


            eidtDescriptionru: '',
            editDescriptionId: '',

            turnaround: '',
            conditions: '',


            courierSelect: 1,
            estimated: 0,

            totlalAll: 0,


            shipping_fee: 0,

            loader: false,


            // Набор перменных для нижней таблицы
            shipping_fee_value: 0,
            shipping_fee_price: 0,

            export_permit_value: 0,
            export_permit: '',
            export_permit_price: 0,

            customs_clearance_value: 0,
            customs_clearance: '',
            customs_clearance_price: 0,

            thermologger_value: 0,
            thermologger: '',
            thermologger_price: 0,

            packaging_value: 0,
            packaging: '',
            packaging_price: 0,


            totalShipping_value: 0,

            offer_ru_item_id: 0,

            hbs_text: '',

            ru_shipping: '',
            ru_gmc: '',
            ru_capacity: '',
            ru_thermal_mode: '',
            ru_count: 0,
            ru_total: 0
        }
    },

    props: [
        'proj_id', 'script_id', 'company_users', 'currency', 'order_diseases',
        'order_samples', 'offer_ru', 'offer_ru_items'
    ],

    computed:{
        sample_options(){
            return this.order_samples.map(s => {
                return {
                    text: s.biospecimen_type + ' ' + (s.modification ? s.modification : ''),
                    value: s.biospecimen_type_id,
                    disease_id: s.disease_id,
                    modification: s.modification ? s.modification : ''
                }
            })
        }
    },

    methods: {
        getOffer() {
            return new Promise(resolve => {
                let data = { result: [ this.offer_ru ] }
                this.offer_date = data.result[0].date_offer;
                this.validd_date = data.result[0].date_valid;
                this.user = data.result[0].user_id;
                this.turnaround = data.result[0].turnaround;
                this.conditions = data.result[0].storage;
                this.ru_shipping = data.result[0].ru_shipping;
                this.ru_gmc = data.result[0].ru_gmc;
                this.ru_capacity = data.result[0].ru_capacity;
                this.ru_thermal_mode = data.result[0].ru_thermal_mode;
                this.ru_count = data.result[0].ru_count;
                this.ru_total = data.result[0].ru_total;
                this.incoterms = data.result[0].incoterms;
                this.shippingInfo = data.result[0].shipping;
                this.courierSelect = data.result[0].courier_id;
                this.typeDelivery = data.result[0].typeDelivery;


                if (data.result[0].zabolevanie !== null) {
                    this.zabolevanie = data.result[0].zabolevanie;
                }

                if (data.result[0].kol !== null) {
                    this.kol = data.result[0].kol;
                }

                if (data.result[0].opis !== null) {
                    this.opis =  data.result[0].opis;
                }

                if (data.result[0].cenaEtapa !== null) {
                    this.cenaEtapa = data.result[0].cenaEtapa;
                }

                if (data.result[0].obstoimost !== null) {
                    this.obstoimost = data.result[0].obstoimost;

                }


                if (data.result[0].scripts_staff_id !== null) {
                    this.staff = data.result[0].scripts_staff_id;
                }


                this.loadShopiping();

                this.shipping_fee = data.result[0].shipping_id;
                this.shipping_fee_value = data.result[0].count_shipping;
                this.estimated = data.result[0].estimated;

                this.export_permit_value = data.result[0].count_export_permit;
                this.export_permit = data.result[0].export_permit;
                this.customs_clearance_value = data.result[0].count_customs_clearance;
                this.customs_clearance = data.result[0].customs_clearance;
                this.thermologger_value = data.result[0].count_thermologger;
                this.thermologger = data.result[0].thermologger;
                this.packaging_value = data.result[0].count_packaging;
                this.packaging = data.result[0].packaging;
                this.totalShipping();
                resolve(data.result[0]);
            });
            // });
        },
        getOfferItems(offer_id) {
            return new Promise(resolve => {
                resolve(this.offer_ru_items);
            });
        },
        getTimelines(offer_id) {
            return new Promise(resolve => {
                this.$parent.getJson(`../../offerRuItemTimeline/getByOfferRuItemId/?offer_ru_item_id=${offer_id}`)
                    .then(timelines_res => {
                        resolve(timelines_res.result);
                    });
            });
        },
        getInclusionCriteria(offer_id) {
            return new Promise(resolve => {
                this.$parent.getJson(`../../offerRuItemInclusionCriteria/getByOfferRuItemId/?offer_ru_item_id=${offer_id}`)
                    .then(inclusion_criterias_res => {
                        resolve(inclusion_criterias_res.result);
                    });
            });
        },
        getExclusionCriteria(offer_id) {
            return new Promise(resolve => {
                this.$parent.getJson(`../../offerRuItemExclusionCriteria/getByOfferRuItemId/?offer_ru_item_id=${offer_id}`)
                    .then(exclusion_criterias_res => {
                        resolve(exclusion_criterias_res.result)
                    });
            });
        },
        getClinicalInformation(offer_id) {
            return new Promise(resolve => {
                this.$parent.getJson(`../../offerRuItemClinicalInformation/getByOfferRuItemId/?offer_ru_item_id=${offer_id}`)
                    .then(clinical_information_res => {
                        resolve(clinical_information_res.result);
                    });
            });
        },
        getListOfSamples(offer_id) {
            return new Promise(resolve => {
                this.$parent.getJson(`../../offerRuItemListOfSamples/getByOfferRuItemId/?offer_ru_item_id=${offer_id}`)
                    .then(list_of_samples_res => {
                        resolve(list_of_samples_res.result);
                    });
            });
        },


        async importOffer() {
            await this.getOffer();
            let data = await this.getOfferItems(this.offer_ru.id)
            this.descriptionArray = data.result.length ? data.result : [[]]
            this.descriptionRow = this.descriptionArray.length
            if (data.result.length) {
                for (let index in this.descriptionArray) {
                    let offer_ru_item_id = this.descriptionArray[index].id;
                    let disease_id = this.descriptionArray[index].disease_id
                    let timelinesArray = this.descriptionArray[index].timelines/*await this.getTimelines(offer_ru_item_id);*/
                    let sumPrice = timelinesArray.reduce((acc, curVal) => {
                        return acc + +curVal.price;
                    }, 0);
                    let inclusionCriteriaArray = this.descriptionArray[index].inclusion_criteria/*await this.getInclusionCriteria(offer_ru_item_id);*/
                    let exclusionCriteriaArray = this.descriptionArray[index].exclusion_criteria/*await this.getExclusionCriteria(offer_ru_item_id);*/
                    let clinicalInformationArray = this.descriptionArray[index].clinical_information/*await this.getClinicalInformation(offer_ru_item_id);*/
                    let listOfSamplesArray = this.descriptionArray[index].list_of_samples/*await this.getListOfSamples(offer_ru_item_id);*/
                    let description = ''
                    this.hbs_text = ''
                    description += ' <b>Описание образцов/этапа проекта:</b><br>'
                    for (let i = 0; i < timelinesArray.length; i++) {
                        let factTimline = '';
                        let nameTimleine = '' + (i + 1);
                        if (this.descriptionRow === 1) {
                            nameTimleine = '';
                        }
                        let samples = timelinesArray[i].samples;
                        let sample_name = timelinesArray[i].sample_name_rus;
                        description += `
                                 ${sample_name} ${samples ? samples : ''}<br>`;
                        this.hbs_text += sample_name + ' + '
                    }
                    this.hbs_text = this.hbs_text.substr(0, this.hbs_text.length - 3)
                    let specific_requirements = this.descriptionArray[index].specific_requirements;
                    if (['', null, undefined].indexOf(specific_requirements) === -1) {
                        description += `<b>Особые требования:</b><br>${specific_requirements}<br>`
                    }
                    let ethnicity = this.descriptionArray[index].ethnicity;
                    if (['', null, undefined].indexOf(ethnicity) === -1) {
                        description += `<b>Раса:</b><br>${ethnicity}<br>`
                    }

                    let type_of_collection = this.descriptionArray[index].type_of_collection;
                    let processing_details = this.descriptionArray[index].processing_details;
                    let turnaround_time = this.descriptionArray[index].turnaround_time;
                    description += `
                             ${type_of_collection ? `<b>Тип коллекции:</b><br>${type_of_collection}<br>` : ''}
                             ${processing_details ? `<b>Процессинг:</b><br>${processing_details}<br>` : ''}
                             ${turnaround_time ? `<b>Сроки выполнения:</b><br>${turnaround_time}<br>` : ''}
                             `

                    let InclusionCriteriaHeader = false;
                    for (let i = 0; i < inclusionCriteriaArray.length; i++) {
                        let inclusion_criteria = inclusionCriteriaArray[i];
                        if (['', null, undefined].indexOf(inclusion_criteria) === -1) {
                            if (!InclusionCriteriaHeader) {
                                description += `<b>Критерии включения:</b><br>`
                                InclusionCriteriaHeader = true;
                            }
                            description += `${inclusion_criteria.inclusion_criteria}<br>`;
                        }
                    }
                    let ExclusionCriteriaHeader = false;
                    for (let i = 0; i < exclusionCriteriaArray.length; i++) {
                        let exclusion_criteria = exclusionCriteriaArray[i];
                        if (['', null, undefined].indexOf(exclusion_criteria) === -1) {
                            if (!ExclusionCriteriaHeader) {
                                description += `<b>Критерии исключения:</b><br>`
                                ExclusionCriteriaHeader = true;
                            }
                            description += `${exclusion_criteria.exclusion_criteria}<br>`;
                        }
                    }
                    let ClinicalInformationHeader = false;
                    for (let i = 0; i < clinicalInformationArray.length; i++) {
                        let clinical_information = clinicalInformationArray[i];
                        if (['', null, undefined].indexOf(clinical_information) === -1) {
                            if (!ClinicalInformationHeader) {
                                description += `<b>Клиническая информация:</b><br>`
                                ClinicalInformationHeader = true;
                            }
                            description += `${clinical_information.clinical_information}<br>`;
                        }
                    }
                    let ListOfSamplesHeader = false;
                    for (let i = 0; i < listOfSamplesArray.length; i++) {
                        let list_of_samples = listOfSamplesArray[i];
                        if (['', null, undefined].indexOf(list_of_samples) === -1) {
                            if (!ListOfSamplesHeader) {
                                description += `<b>Список клинических случаев:</b><br>`
                                ListOfSamplesHeader = true;
                            }
                            description += `${list_of_samples.list_of_samples}<br>`;
                        }
                    }



                    let obj = {
                        proj_id: this.proj_id,
                        index: this.descriptionArray[index].id,
                        disease: this.descriptionArray[index].disease,
                        disease_rus: this.descriptionArray[index].disease_name_russian,
                        specific_requirements: this.descriptionArray[index].specific_requirements,
                        type_of_collection: this.descriptionArray[index].type_of_collection,
                        processing_details: this.descriptionArray[index].processing_details,
                        turnaround_time: this.descriptionArray[index].turnaround_time,
                        ethnicity: this.descriptionArray[index].ethnicity,
                        hbs: this.descriptionArray[index].hbs_type,
                        quantity: this.descriptionArray[index].quantity,
                        timelines: timelinesArray,
                        inclusion_criterias: inclusionCriteriaArray,
                        exclusion_criterias: exclusionCriteriaArray,
                        clinical_information: clinicalInformationArray,
                        list_of_samples: listOfSamplesArray,
                        sumPrice: sumPrice,
                        totalPrice: sumPrice * this.descriptionArray[index].quantity,
                        description: description,
                        timelinesArray: timelinesArray,
                        disease_id: disease_id,
                        hbs_text: this.hbs_text,
                        typeDelivery: this.typeDelivery
                    }
                    this.offerArray.push(obj);
                    // })
                }
            }
            this.totalSum();
        },

        load() {
            this.static = [];
            this.$parent.getJson(`../../orders/GetOneProject/?proj_id=${this.proj_id}`)
                .then(data => {
                    this.project = data.result;
                    //this.user.id = this.project.answering_id;
                });
            this.importOffer();
            this.$parent.getJson(`../../company/GetOne/?id=${this.script_id}`)
                .then(data => {
                    this.company = data.result;
                });
            this.$parent.getJson(`../../staff/GetByCompany/?script_id=${this.script_id}`)
                .then(data => {
                    for (let key in data.result) {
                        this.compamyStaff.push(data.result[key]);
                    }
                });
            this.$parent.getJson(`../../users/GetUsersByRole/?role=1`)
                .then(data => {
                    for (let key in data.result) {
                        this.users.push(data.result[key]);
                    }
                });
            this.$parent.getJson(`../../courier/getAll`)
                .then(data => {
                    for (let key in data.result) {
                        this.courier.push(data.result[key]);
                    }
                });
            this.$parent.getJson(`../../container/getAll`)
                .then(data => {
                    this.container = data.result;
                    this.container.forEach(el => {
                        if (el.id == 1) {
                            if (this.currency === 'USD') {
                                this.thermologger_price = el.price_usd;
                            } else {
                                this.thermologger_price = el.price_euro;
                            }
                        }
                        if (el.id == 2) {
                            if (this.currency === 'USD') {
                                this.export_permit_price = el.price_usd;
                            } else {
                                this.export_permit_price = el.price_euro;
                            }
                        }
                        if (el.id == 3) {
                            if (this.currency === 'USD') {
                                this.customs_clearance_price = el.price_usd;
                            } else {
                                this.customs_clearance_price = el.price_euro;
                            }
                        }
                        if (el.id == 4) {
                            if (this.currency === 'USD') {
                                this.packaging_price = el.price_usd;
                            } else {
                                this.packaging_price = el.price_euro;
                            }
                        }
                    });
                });
            // this.$parent.getJSON('')
        },

        checkCountRow(action) {
            if (action == '+') {
                this.descriptionRow = this.descriptionArray.length
                this.descriptionArray.push([])
                setTimeout(() => {
                    let sample_id = $(`#samplesidru_${this.descriptionRow - 2}`).val(),
                        target_id = $(`#samplesidru_${this.descriptionRow - 1}`).attr('id'),
                        index = target_id.split('_')[1],
                        modification = $(`#samplesidru_${this.descriptionRow - 2} option:selected`).attr('modification')
                    $(`#samplesidru_${index}`).val(sample_id)
                    $(`#samplesru_${index}`).val(modification)
                }, 1000)
                this.descriptionRow++;
            } else {
                if (this.descriptionRow == 1) {
                    toastr.error('Timeline обязателен для заполнения');
                } else {
                    this.descriptionRow--;
                }
            }
        },
        checkCountInclusionCriteriaRow(action) {
            if (action == '+') {
                this.inclusionCriteriaRow++;
                this.inclusionCriteriaArray.push([]);
            } else {
                if (this.inclusionCriteriaRow > 0) {
                    this.inclusionCriteriaRow--;
                }
            }
        },
        checkCountExclusionCriteriaRow(action) {
            if (action == '+') {
                this.exclusionCriteriaRow++;
                this.exclusionCriteriaArray.push([]);
            } else {
                if (this.exclusionCriteriaRow > 0) {
                    this.exclusionCriteriaRow--;
                }
            }
        },
        checkCountClinicalInformationRow(action) {
            if (action == '+') {
                this.clinicalInformationRow++;
                this.clinicalInformationArray.push([]);
            } else {
                if (this.clinicalInformationRow > 0) {
                    this.clinicalInformationRow--;
                }
            }
        },
        checkCountListOfSamplesRow(action) {
            if (action == '+') {
                this.listOfSamplesRow++;
                this.listOfSamplesArray.push([]);
            } else {
                if (this.listOfSamplesRow > 0) {
                    this.listOfSamplesRow--;
                }
            }
        },

        addRow() {
            let offer_ru_item_id = this.offer_ru_item_id
            let TimelinesArr = [], InclusionArr = [], ExclusionArr = [], ClinicalArr = [], ListOfSamplesArr = []
            this.description = ``

            this.description += ' <b>Описание образцов/этапа проекта:</b><br>'
            this.hbs_text = ''
            for (let i = 0; i < this.descriptionArray.length; i++) {
                let price = document.getElementById('priceru_' + i).value;
                let id = document.getElementById('timelineidru_' + i).value;
                let sample_id = document.getElementById('samplesidru_' + i).value;
                let sample_name = $(`#samplesidru_${i} option:selected`).html();
                let samples = document.getElementById('samplesru_' + i).value;
                this.sumPrice = +this.sumPrice + price * 1;
                this.description += `
                                 ${sample_name}<br>`;
                let TimelineData = {
                    offer_ru_item_id,
                    samples,
                    price,
                    sample_id
                };
                if (id) {
                    TimelineData.id = id;
                }
                let TimelineIndex = TimelinesArr.length;
                TimelinesArr.push(TimelineData);
                this.$parent.putJson(`../../../offerRuItemTimeline/save`, TimelineData)
                    .then(response => {
                        toastr.success('Сохранение таймлайна... ', response);
                        if (!id) {
                            $(`#timelineidru_${i}`).val(response);
                            TimelinesArr[TimelineIndex].id = response;
                        }
                    });
                this.hbs_text += sample_name + ' + '
            }

            this.hbs_text = this.hbs_text.substr(0, this.hbs_text.length - 3)
            this.hbs = `${this.hbs_text}<br>`

            let specific_requirements = this.specific_requirements;
            if (['', null, undefined].indexOf(specific_requirements) === -1) {
                this.description += `<b>Особые требования:</b><br>${specific_requirements}<br>`
            }
            let ethnicity = this.ethnicity;
            if (['', null, undefined].indexOf(ethnicity) === -1) {
                this.description += `<b>Раса:</b><br>${ethnicity}<br>`
            }

            let type_of_collection = this.type_of_collection;
            let processing_details = this.processing_details;
            let turnaround_time = this.turnaround_time;
            this.description += `${type_of_collection ? `<b>Тип коллекции:</b><br>${type_of_collection}<br>` : ''}
                                 ${processing_details ? `<b>Процессинг:</b><br>${processing_details}<br>` : ''}
                                 ${turnaround_time ? `<b>Сроки выполнения:</b><br>${turnaround_time}<br>` : ''}`;

            let InclusionCriteriaHeader = false;
            for (let i = 0; i < this.inclusionCriteriaArray.length; i++) {
                if (document.getElementById('inclusion_criteriaru_' + i) !== null) {
                    let inclusion_criteria = document.getElementById('inclusion_criteriaru_' + i).value;
                    if (inclusion_criteria !== '') {
                        if (!InclusionCriteriaHeader) {
                            this.description += `<b>Критерии включения:</b><br>`
                            InclusionCriteriaHeader = true;
                        }
                        this.description += `${inclusion_criteria}<br>`;
                        let id = document.getElementById('inclusionidru_' + i).value;
                        let InclusionCriteriaData = {offer_ru_item_id, inclusion_criteria};
                        if (id) {
                            InclusionCriteriaData.id = id;
                        }
                        let InclusionIndex = InclusionArr.length;
                        InclusionArr.push(InclusionCriteriaData);
                        this.$parent.putJson(`../../../offerRuItemInclusionCriteria/save`, InclusionCriteriaData)
                            .then(response => {
                                if (!id) {
                                    $(`#inclusionidru_${i}`).val(response);
                                    InclusionArr[InclusionIndex].id = response;
                                }
                            });
                    }
                }
            }
            let ExclusionCriteriaHeader = false;
            for (let i = 0; i < this.exclusionCriteriaArray.length; i++) {
                if (document.getElementById('exclusion_criteriaru_' + i) !== null) {
                    let exclusion_criteria = document.getElementById('exclusion_criteriaru_' + i).value;
                    if (exclusion_criteria !== '') {
                        if (!ExclusionCriteriaHeader) {
                            this.description += `<b>Критерии исключения:</b><br>`
                            ExclusionCriteriaHeader = true;
                        }
                        this.description += `${exclusion_criteria}<br>`;
                        let id = document.getElementById('exclusionidru_' + i).value;
                        let ExclusionCriteriaData = {offer_ru_item_id, exclusion_criteria};
                        if (id) {
                            ExclusionCriteriaData.id = id;
                        }
                        let ExclusionIndex = ExclusionArr.length;
                        ExclusionArr.push(ExclusionCriteriaData);
                        this.$parent.putJson(`../../../offerRuItemExclusionCriteria/save`, ExclusionCriteriaData)
                            .then(response => {
                                if (!id) {
                                    $(`#exclusionidru_${i}`).val(response);
                                    ExclusionArr[ExclusionIndex].id = response;
                                }
                            });
                    }
                }
            }
            let ClinicalInformationHeader = false;
            for (let i = 0; i < this.clinicalInformationArray.length; i++) {
                if (document.getElementById('clinical_informationru_' + i) !== null) {
                    let clinical_information = document.getElementById('clinical_informationru_' + i).value;
                    if (clinical_information !== '') {
                        if (!ClinicalInformationHeader) {
                            this.description += `<b>Клиническая информация:</b><br>`
                            ClinicalInformationHeader = true;
                        }
                        this.description += `${clinical_information}<br>`;
                        let id = document.getElementById('clinicalidru_' + i).value;
                        let ClinicalInformationData = {offer_ru_item_id, clinical_information};
                        if (id) {
                            ClinicalInformationData.id = id;
                        }
                        let ClinicalIndex = ClinicalArr.length;
                        ClinicalArr.push(ClinicalInformationData);
                        this.$parent.putJson(`../../../offerRuItemClinicalInformation/save`, ClinicalInformationData)
                            .then(response => {
                                if (!id) {
                                    $(`#clinicalidru_${i}`).val(response);
                                    ClinicalArr[ClinicalIndex].id = response;
                                }
                            });
                    }
                }
            }
            let ListOfSamplesHeader = false;
            for (let i = 0; i < this.listOfSamplesArray.length; i++) {
                if (document.getElementById('list_of_samplesru_' + i) !== null) {
                    let list_of_samples = document.getElementById('list_of_samplesru_' + i).value;
                    if (list_of_samples !== '') {
                        if (!ListOfSamplesHeader) {
                            this.description += `<b>Список клинических случаев:</b><br>`
                            ListOfSamplesHeader = true;
                        }
                        this.description += `${list_of_samples}<br>`;
                        let id = document.getElementById('list_of_samples_idru_' + i).value;
                        let ListOfSamplesData = {offer_ru_item_id, list_of_samples};
                        if (id) {
                            ListOfSamplesData.id = id;
                        }
                        let ListOfSamplesIndex = ListOfSamplesArr.length;
                        ListOfSamplesArr.push(ListOfSamplesData);
                        this.$parent.putJson(`../../../offerRuItemListOfSamples/save`, ListOfSamplesData)
                            .then(response => {
                                if (!id) {
                                    $(`#list_of_samples_idru_${i}`).val(response);
                                    ListOfSamplesArr[ListOfSamplesIndex].id = response;
                                }
                            });
                    }
                }
            }

            let obj, keyToReplace = -1;
            for (let key in this.offerArray) {
                if (this.offerArray[key].index === this.offer_ru_item_id) {
                    obj = this.offerArray[key];
                    keyToReplace = key;
                }
            }
            if (!obj) {
                obj = {};
            }
            // obj = {
            let disease_id = $("#order_diseasesru option:selected").data('id')
            obj.proj_id = this.proj_id
            obj.index = this.offer_ru_item_id
            obj.disease = this.disease
            obj.hbs = this.hbs
            obj.quantity = +this.quantity
            obj.specific_requirements = this.specific_requirements
            obj.type_of_collection = this.type_of_collection
            obj.processing_details = this.processing_details
            obj.turnaround_time = this.turnaround_time
            obj.ethnicity = this.ethnicity
            obj.disease_id = disease_id
            obj.sumPrice = +this.sumPrice
            obj.totalPrice = +this.sumPrice * this.quantity
            obj.description = this.description
            obj.timelines = TimelinesArr;
            obj.inclusion_criterias = InclusionArr;
            obj.exclusion_criterias = ExclusionArr;
            obj.clinical_information = ClinicalArr;
            obj.list_of_samples = ListOfSamplesArr;
            obj.hbs_text = this.hbs_text;
            obj.timelinesArray = TimelinesArr;
            // }
            let data = {
                id: this.offer_ru_item_id,
                offer_ru_id: this.offer_ru.id,
                disease: this.disease,
                hbs_type: this.hbs,
                quantity: this.quantity,
                specific_requirements: this.specific_requirements,
                type_of_collection: this.type_of_collection,
                processing_details: this.processing_details,
                turnaround_time: this.turnaround_time,
                ethnicity: this.ethnicity,
                disease_id: disease_id

            }

            this.$parent.putJson(`../../../offerRuItem/save`, data)
                .then(response => {
                    toastr.success('Создание ряда ... ', response);
                    this.offer_ru_item_id = response;
                });

            if (keyToReplace === -1) {
                this.offerArray.push(obj);
            }

            this.clearArray();
            $('#iconModalru').modal('hide');
            this.totalSum();
        },

        deleteRow() {
            let id = this.offer_ru_item_id;
            $('#iconModalru').modal('hide');
            this.$parent.putJson(`../../../offerRuItem/delete`, {id})
                .then(response => {
                    toastr.success('Удаление ряда ... ', response);

                    let deletedKey = -1;
                    for (let key in this.offerArray) {
                        if (this.offerArray[key].index == id) {
                            deletedKey = key;
                        }
                    }
                    if (deletedKey !== -1) {
                        this.offerArray.splice(deletedKey, 1);
                    }
                });
        },

        cloneRow() {
            let data = {
                offer_ru_id: this.offer_ru.id,
            }

            this.$parent.putJson(`../../../offerRuItem/save`, data)
                .then(response => {
                    toastr.success('Создание ряда ... ', response);
                    this.offer_ru_item_id = response;
                    $(".timelineidru, .inclusionidru, .exclusionidru, .clinicalidru, .list_of_samples_idru").val('')
                    for (let i = 0; i < this.descriptionArray.length; i++) {
                        document.getElementById('timelineidru_' + i).value = '';
                    }
                    for (let i = 0; i < this.inclusionCriteriaArray.length; i++) {
                        document.getElementById('inclusionidru_' + i).value = '';
                    }
                    for (let i = 1; i < this.exclusionCriteriaArray.length; i++) {
                        document.getElementById('exclusionidru_' + i).value = '';
                    }
                    for (let i = 1; i < this.clinicalInformationArray.length; i++) {
                        document.getElementById('clinicalidru_' + i).value = '';
                    }
                    for (let i = 1; i < this.listOfSamplesArray.length; i++) {
                        document.getElementById('list_of_samples_idru_' + i).value = '';
                    }
                    this.addRow();
                });
        },

        totalSum() {
            this.totalPrice = 0;
            this.offerArray.forEach(el => {
                this.totalPrice = this.totalPrice + el.totalPrice;
            })
        },

        clearArray() {
            this.disease = '';
            this.hbs = '';
            this.specific_requirements = '';
            this.type_of_collection = '';
            this.processing_details = '';
            this.turnaround_time = '';
            this.ethnicity = '';
            this.quantity = 1;
            this.sumPrice = 0;
            this.description = 0;
            this.descriptionArray = [[]];
            this.inclusionCriteriaArray = [[]];
            this.exclusionCriteriaArray = [[]];
            this.clinicalInformationArray = [[]];
            this.listOfSamplesArray = [[]];
            this.descriptionRow = 1;
            this.inclusionCriteriaRow = 1;
            this.exclusionCriteriaRow = 1;
            this.clinicalInformationRow = 1;
            this.listOfSamplesRow = 1;
        },

        saveDescription() {
            let el = this.offerArray.find(el => {
                if (el.index = this.editDescriptionId) {
                    return true
                }
            })

            if (el !== undefined) {
                el.description = tinymce.get('eidtDescriptionru').getContent();
                $('#iconModalEditru').modal('hide');
            }
        },

        openModal() {
            $('#iconModalru').modal('show');
        },

        addOfferIttem() {
            this.clearArray();
            this.openModal();
            let data = {
                offer_ru_id: this.offer_ru.id,
            }

            this.$parent.putJson(`../../../offerRuItem/save`, data)
                .then(response => {
                    toastr.success('Создание ряда ... ', response);
                    this.offer_ru_item_id = response;
                });
        },

        openEdit(item) {
            $('#iconModalEditru').modal('show');
            this.editDescriptionId = item.index + 1;
            tinymce.get("eidtDescriptionru").setContent(item.description);
        },

        openEditOfferItem(item) {
            this.offer_ru_item_id = item.index;
            this.disease = item.disease;
            this.disease_id = item.disease_id;
            this.hbs = item.hbs;
            this.specific_requirements = item.specific_requirements;
            this.type_of_collection = item.type_of_collection;
            this.processing_details = item.processing_details;
            this.turnaround_time = item.turnaround_time;
            this.ethnicity = item.ethnicity;
            this.quantity = item.quantity;
            this.list_of_samples = item.list_of_samples;
            item.timelines.forEach((timeline, index, timelinesArr) => {
                if (!timelinesArr[index]['samples']) {
                    let order_samples_filtered = this.order_samples.filter((el) => {
                        return parseInt(el.id) === parseInt(item.id)
                    })
                    if (order_samples_filtered.length)
                        timelinesArr[index]['samples'] = order_samples_filtered[0]['modification']
                    else
                        timelinesArr[index]['samples'] = ''
                }
                setTimeout(() => {
                    $(`#samplesru_${index}`).val(timelinesArr[index]['samples'])
                    $(`#samplesidru_${index} option[value="${timelinesArr[index]['sample_id']}"][modification="${timelinesArr[index]['samples']}"]`).prop('selected', true)
                }, 1000)
            })
            this.descriptionArray = item.timelines;
            this.inclusionCriteriaArray = item.inclusion_criterias;
            this.exclusionCriteriaArray = item.exclusion_criterias;
            this.clinicalInformationArray = item.clinical_information;
            this.listOfSamplesArray = item.list_of_samples;
            this.openModal();
        },

        saveOffer() {
            // Основой набор данных
            // TODO добавить фиксации цены досавки на момент сохранения
            let data = {
                id: this.offer_ru.id,
                date_offer: this.offer_date,
                scripts_staff_id: this.staff,
                date_valid: this.validd_date,
                user_id: this.user,
                turnaround: this.turnaround,
                storage: this.conditions,

                incoterms: this.incoterms,
                courier_id: this.courierSelect,
                estimated: this.estimated,

                shipping_id: this.shipping_fee,
                count_shipping: this.shipping_fee_value,

                export_permit: this.export_permit,
                count_export_permit: this.export_permit_value,

                customs_clearance: this.customs_clearance,
                count_customs_clearance: this.customs_clearance_value,

                thermologger: this.thermologger,
                count_thermologger: this.thermologger_value,

                packaging: this.packaging,
                count_packaging: this.packaging_value,

                ru_shipping: this.ru_shipping,
                ru_gmc: this.ru_gmc,
                ru_capacity: this.ru_capacity,
                ru_thermal_mode: this.ru_thermal_mode,
                ru_count: this.ru_count,
                ru_total: this.ru_total,

                zabolevanie: this.zabolevanie,
                opis: this.opis,
                kol: this.kol,
                cenaEtapa: this.cenaEtapa,
                obstoimost: this.obstoimost,
                typeDelivery: this.typeDelivery

            }

            this.$parent.putJson(`../../../offerRu/save`, data)
                .then(response => {
                    toastr.success('Сохранение ... ');
                })

        },

        generate() {
            this.loader = true;
            let itog = (this.ru_total *1 ) + (this.totalPrice * 1);
            let totalPrice;
            if(isNaN(Number(this.ru_total))) {
                totalPrice = new Intl.NumberFormat('ru-RU').format(this.totalPrice * 1);
            } else {
                totalPrice = new Intl.NumberFormat('ru-RU').format(this.totalPrice + this.ru_total * 1);
            }


            let data = {
                id: this.offer_ru.id,
                date_offer: this.offer_date,
                document: this.project.internal_id,
                distribution: this.company.company_name,
                scripts_staff_id: this.staff,
                client_id: this.company.script,
                date_valid: this.validd_date,
                user_id: this.user,
                turnaround: this.turnaround,
                storage: this.conditions,
                ru_shipping: this.ru_shipping,
                ru_gmc: this.ru_gmc,
                ru_capacity: this.ru_capacity,
                ru_thermal_mode: this.ru_thermal_mode,
                ru_count: this.ru_count,
                ru_total: this.ru_total,
                currency: this.currency,
                totalPrice: totalPrice,
                incoterms: this.incoterms,
                shipping: this.shippingInfo,
                courier_id: this.courierSelect,
                estimated: this.estimated,
                shipping_id: this.shipping_fee,
                count_shipping: this.shipping_fee_value,
                shipping_fee_price: this.shipping_fee_price,
                export_permit: this.export_permit,
                count_export_permit: this.export_permit_value,
                export_permit_price: this.export_permit_price,
                customs_clearance: this.customs_clearance,
                count_customs_clearance: this.customs_clearance_value,
                customs_clearance_price: this.customs_clearance_price,
                thermologger: this.thermologger,
                count_thermologger: this.thermologger_value,
                thermologger_price: this.thermologger_price,
                packaging: this.packaging,
                count_packaging: this.packaging_value,
                packaging_price: this.packaging_price,
                proj_id: this.proj_id,
                total_shipping: new Intl.NumberFormat('ru-RU').format(this.totalShipping_value),
                payment_terms: this.company.payment_terms,
                version: this.version,
                table_body: document.getElementById('table_bodyru').innerHTML,
                newlines_1: this.newlines_1,
                newlines_2: this.newlines_2,

                zabolevanie: this.zabolevanie,
                opis: this.opis,
                kol: this.kol,
                cenaEtapa: this.cenaEtapa,
                obstoimost: this.obstoimost,
                typeDelivery: this.typeDelivery
            }

            this.$parent.putJson(`../../../offerRu/generatePdf`, data)
                .then(response => {
                    location.href = "../../../orders/info/?idFR=" + this.proj_id;
                })


        },

        // saveTimline() {
        //
        // },
        deleteTimeline(index) {
            this.$parent.putJson(`../../../offerRuItemTimeline/delete`, {id: this.descriptionArray[index].id});
            this.descriptionArray.splice(index, 1);
            this.descriptionRow--;
        },
        deleteInclusionCriteria(index) {
            this.$parent.putJson(`../../../offerRuItemInclusionCriteria/delete`, {id: this.inclusionCriteriaArray[index].id});
            this.inclusionCriteriaArray.splice(index, 1);
            this.inclusionCriteriaRow--;
        },
        deleteExclusionCriteria(index) {
            this.$parent.putJson(`../../../offerRuItemExclusionCriteria/delete`, {id: this.exclusionCriteriaArray[index].id});
            this.exclusionCriteriaArray.splice(index, 1);
            this.exclusionCriteriaRow--;
        },
        deleteClinicalInformation(index) {
            this.$parent.putJson(`../../../offerRuItemClinicalInformation/delete`, {id: this.clinicalInformationArray[index].id});
            this.clinicalInformationArray.splice(index, 1);
            this.clinicalInformationRow--;
        },
        deleteListOfSamples(index) {
            this.$parent.putJson(`../../../offerRuItemListOfSamples/delete`, {id: this.listOfSamplesArray[index].id});
            this.listOfSamplesArray.splice(index, 1);
            this.listOfSamplesRow--;
        },

        sumTotal() {
            let sum = document.querySelectorAll('.sumTotal');
            let totalSum = 0;
            sum.forEach(el => {
                totalSum += el.value * 1;
            });

            this.totlalAll = (totalSum * this.quantity) + this.totalPrice;
        },

        loadShopiping() {

            this.shipping = [];
            this.$parent.getJson(`../../shipping/getAllbyId/?id=${this.courierSelect}`)
                .then(data => {
                    for (let key in data.result) {
                        this.shipping.push(data.result[key]);
                        if (data.result[key].id == this.shipping_fee) {
                            if (this.currency == 'USD') {
                                this.shipping_fee_price = data.result[key].price_usd;
                            } else {
                                this.shipping_fee_price = data.result[key].price_euro;
                            }
                        }
                    }
                });


        },

        changeShoppping() {
            this.shipping.forEach(el => {
                if (el.id == this.shipping_fee) {
                    if (this.currency == 'USD') {
                        this.shipping_fee_price = el.price_usd;
                    } else {
                        this.shipping_fee_price = el.price_euro;
                    }
                    this.totalShipping();
                }
            })


        },

        totalShipping() {

            let exports = 0;
            let customs = 0;
            let thermologgers = 0;
            let packagings = 0;


            if (this.export_permit == 'yes') {
                exports = this.export_permit_value * this.export_permit_price;
            }

            if (this.customs_clearance == 'yes') {
                customs = this.customs_clearance_value * this.customs_clearance_price;
            }

            if (this.thermologger == 'yes') {
                thermologgers = this.thermologger_value * this.thermologger_price;
            }

            if (this.packaging == 'yes') {
                packagings = this.packaging_value * this.packaging_price;
            }

            this.totalShipping_value =
                (this.shipping_fee_price * this.shipping_fee_value)
                + exports + customs + thermologgers + packagings
        },
        samplesOptions($event) {
            let id = $("#order_diseasesru option:selected").attr('data-id')
            $(".order_samples").empty()
            this.order_samples.forEach(order_sample => {
                if (parseInt(order_sample.disease_id) === parseInt(id)) {
                    $(".order_samples").append(`
                        <option value="${order_sample.biospecimen_type_id}" id="${order_sample.disease_id}_${order_sample.biospecimen_type_id}"
                            modification="${order_sample.modification}">${order_sample.biospecimen_type} ${order_sample.modification}</option>`)
                }
            })
            $(".order_samples").each(function( timeline_index ) {
                let target_id = $(this).attr('id'),
                    index = target_id.split('_')[1],
                    modification = $(`#samplesidru_${index} option:selected`).attr('modification')
                $(`#samplesru_${index}`).val(modification)
            })
        },
        sampleModification($event) {
            let val = $("#order_diseasesru").val(), option = $(`#order_diseasesru [value="${val}"]`),
                disease_id = option.data('id'), sample_id = $($event.currentTarget).val(),
                target_id = $($event.currentTarget).attr('id'), index = target_id.split('_')[1],
                modification = $(`#samplesidru_${index} option:selected`).attr('modification'),
                Mod = this.order_samples.filter(os => {
                    return parseInt(os.disease_id) === parseInt(disease_id) &&
                        parseInt(os.biospecimen_type_id) === parseInt(sample_id) &&
                        os.modification === modification
                })[0]
            if (!!Mod)
                $(`#samplesru_${index}`).val(Mod.modification)
            else {
                $(`#samplesru_${index}`).val('')
            }
        },
        calcValidDate() {
            let d = new Date(this.offer_date)

            Date.prototype.yyyymmdd = function() {
                var mm = this.getMonth() + 1; // getMonth() is zero-based
                var dd = this.getDate();

                return [
                    (dd>9 ? '' : '0') + dd,
                    (mm>9 ? '' : '0') + mm,
                    this.getFullYear()
                ].join('.');
            }

            d.setMonth(d.getMonth() + 3)
            this.validd_date = d.yyyymmdd().split('.').reverse().join('-')/*.yyyymmdd()*/
            // $("#valid_date").val()
        }
    },

    mounted() {
        this.load();

        //this.loadShopiping();
        setTimeout(() => {
            this.totalShipping();
        }, 3000)
    },

    template: `
<div> 

<div class="modal fade text-left" id="iconModalru"  role="dialog" aria-labelledby="myModalLabel4" 
     aria-hidden="true" >
        <div id="modalview">
           <div class="modal-dialog modal-xl" role="document" style="width: 80% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-road2"></i> <b>Создать</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                
             <div>
                                <div class="row" >
                                    <div class="col-4">
                                        <label for="disease">Заболевание</label>
                                        <select id="order_diseasesru" v-model="disease" class="form-control" @change="samplesOptions($event)">
                                            <option v-for="order_disease in order_diseases" :value="order_disease.disease_name_russian"
                                                                        :data-id="order_disease.disease_id"
                                                                        >{{ order_disease.disease_name_russian }} ({{ order_disease.mutation }})</option>
                                        </select>
                                    </div> 
                                    <div class="col-4" style="display: none;">
                                        <label for="hbs">HBS Type</label>
                                        <input type="text" class="form-control" :value="hbs_text">
                                    </div>
                                      <div class="col-4">
                                         <label for="Indication">Количество</label>
                                         <input type="number" v-model="quantity" class="form-control" @input="sumTotal()">
                                    </div>
                                    <div class="col-4">
                                         <label>Тип коллекции</label>
                                         <input type="text" class="form-control" v-model="type_of_collection">
                                       </div>                                        
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                            <label for="specific_requirements">Особые требования</label>
                                            <input type="text" v-model="specific_requirements" class="form-control" id="specific_requirements">                                
                                    </div>
                                    <div class="col-4">
                                            <label for="ethnicity">Раса</label>
                                            <select id="ethnicity" v-model="ethnicity" class="form-control">
                                                <option value=""></option>
                                                <option value="Любой">Любой</option>
                                                <option value="Европеоид">Европеоид</option>
                                                <option value="Азиатский">Азиатский</option>
                                            </select>       
                                    </div>
                                    <div class="col-4">
                                         <label for="Indication">Процессинг:</label>
                                         <input type="text" class="form-control" v-model="processing_details">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                    <label for="Indication">Сроки выполнения:</label>
                                    <input type="text" class="form-control" v-model="turnaround_time">
                                    </div>
                                </div>
                                <br>
                                    <h4>Описание</h4>
                                   <div class="col-2">
                                         <button class="btn btn-dark mt-3" @click="checkCountRow('+')">+</button>
                                         </div>
                                 <div class="row" v-for="(item, index) in descriptionArray">
                                    <input type="hidden" v-model="item.id" :id="'timelineidru_'+ index" class="timelineidru">
                                     <div class="col-1" style="display:none;">
                                         <label>Timeline</label>
                                         <input type="text" class="form-control"
                                            :id="'timeline_'+ index"
                                            v-model="item.timeline"> 
                                     </div> 
                                       <div class="col-6">
                                         <label>Описание образцов/этапа проекта</label>
                                        <select class="form-control order_samples"
                                            :id="'samplesidru_' + index"
                                            @change="sampleModification($event)"
                                            >
                                            <option 
                                                v-for="option in sample_options"
                                                v-if="option.disease_id == disease_id"
                                                :value="option.value"
                                                :modification="option.modification"
                                                >{{ option.text }}</option>
                                        </select>
                                       </div>
                                       <div class="col-3">
                                        <label>Модификация</label>
                                        <input type="text" class="form-control" disabled
                                            :id="'samplesru_' + index"> 
                                       </div>  
                                       
                                         <div class="col-2">
                                            <label for="Indication">Цена</label>
                                            <input type="number" class="form-control sumTotal"
                                                :id="'priceru_' + index"
                                                @input="sumTotal()"
                                                v-model="item.price"
                                                >
                                         </div>
                                          <div class="col-1">
                                            <label for="Indication">Удалить</label>
                                            <input type="button" class="form-control btn btn-danger" value="del" @click="deleteTimeline(index)">
                                         </div>
                                    </div>
                                        <div class="row">
                                        <div class="col-10"></div>
                                        <div class="col-2">{{ new Intl.NumberFormat('ru-RU').format(totlalAll) }}</div>
                                        </div> 
                                    <hr>
                                        <div class="row" >
                                 <div class="col-3">
                                         <label>Критерии включения</label><br>
                                         <button class="btn btn-dark mt-3"
                                            @click="checkCountInclusionCriteriaRow('+')">+</button>
                                         <div v-for="(inclusion_item, inclusion_index) in inclusionCriteriaArray">
                                            <input type="hidden"  class="inclusionidru"
                                                :id="'inclusionidru_' + inclusion_index"
                                                v-model="inclusion_item.id">
                                            <div class="row">
                                                <div class="col-9">
                                                    <input type="text" class="form-control"
                                                        :id="'inclusion_criteriaru_' + inclusion_index"
                                                        v-model="inclusion_item.inclusion_criteria">
                                                </div>
                                                <div class="col-3">
                                                    <input type="button" value="-" class="form-control btn btn-danger"
                                                        @click="deleteInclusionCriteria(inclusion_index)">
                                                </div>
                                            </div>
                                        </div>
                                  </div>
                                  <div class="col-3">
                                         <label>Критерии исключения:</label><br>
                                         <button class="btn btn-dark mt-3"
                                            @click="checkCountExclusionCriteriaRow('+')">+</button>
                                         <div v-for="(exclusion_item, exclusion_index) in exclusionCriteriaArray">
                                            <input type="hidden"  class="exclusionidru"
                                                :id="'exclusionidru_' + exclusion_index"
                                                v-model="exclusion_item.id"> 
                                            <div class="row">
                                                <div class="col-9">
                                                     <input type="text" class="form-control"
                                                        :id="'exclusion_criteriaru_' + exclusion_index"
                                                        v-model="exclusion_item.exclusion_criteria">
                                                </div>
                                                <div class="col-3">
                                                    <input type="button" value="-" class="form-control btn btn-danger"
                                                        @click="deleteExclusionCriteria(exclusion_index)">
                                                </div>
                                            </div>
                                        </div>
                                  </div> 
                                  <div class="col-3">
                                         <label>Клиническая информация:</label><br>
                                         <button class="btn btn-dark mt-3"
                                            @click="checkCountClinicalInformationRow('+')">+</button>
                                         <div v-for="(clinical_item, clinical_index) in clinicalInformationArray">
                                            <input type="hidden"  class="clinicalidru"
                                                :id="'clinicalidru_' + clinical_index"
                                                v-model="clinical_item.id">
                                            <div class="row">
                                                <div class="col-9">
                                                    <input type="text" class="form-control"
                                                        :id="'clinical_informationru_' + clinical_index"
                                                        v-model="clinical_item.clinical_information">
                                                </div>
                                                <div class="col-3">
                                                    <input type="button" value="-" class="form-control btn btn-danger"
                                                        @click="deleteClinicalInformation(clinical_index)">
                                                </div>
                                            </div>
                                        </div>
                                  </div> 
                                   <div class="col-3">
                                         <label>Список клинических случаев:</label><br>
                                        <button class="btn btn-dark mt-3"
                                            @click="checkCountListOfSamplesRow('+')">+</button>
                                         <div v-for="(list_of_samples_item, list_of_samples_index) in listOfSamplesArray">
                                            <input type="hidden" class="list_of_samples_idru"
                                                :id="'list_of_samples_idru_' + list_of_samples_index"
                                                v-model="list_of_samples_item.id">
                                            <div class="row">
                                                <div class="col-9">
                                                    <input type="text" class="form-control"
                                                        :id="'list_of_samplesru_' + list_of_samples_index"
                                                        v-model="list_of_samples_item.list_of_samples">
                                                </div>
                                                <div class="col-3">
                                                    <input type="button" value="-" class="form-control btn btn-danger"
                                                        @click="deleteListOfSamples(list_of_samples_index)">
                                                </div>
                                            </div>
                                        </div>
                                  </div>
                                 </div>
                                </div>
                
            </div>
            <div class="modal-footer"> 
            <button type="button" class="btn btn-outline-red" @click="deleteRow()">Delete</button>
            <button type="button" class="btn btn-outline-blue" @click="cloneRow()">Clone</button>
            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn  btn-success"  @click="addRow()">Save</button>
               
            </div>
        </div>
    </div>
        </div>
                  
          </div>        
<div class="modal fade text-left" id="iconModalEditru"  role="dialog" aria-labelledby="myModalLabel4" 
     aria-hidden="true" >
        <div id="modalview">
           <div class="modal-dialog modal-xl" role="document" style="width: 80% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-road2"></i> <b>Редактировать {{ editDescriptionId }} </b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
            <textarea name="" id="eidtDescriptionru" cols="30" rows="30"> </textarea>
            </div>
            <div class="modal-footer"> 
            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn  btn-success"  @click="saveDescription()">Сохранить</button>
               
            </div>
        </div>
    </div>
        </div>
          </div>    

<div class="card-body card-dashboard " id="html"  style=" display: flex; justify-content: center; align-items: center" v-if="loader == false">
                 <div class="container">
                <div class="row">
                <div class="col-12">
            
                                <table width="100%">
                                    <thead>
<!--                                      <tr>-->
<!--                                        <td width="12%"><b>Version</b></td>-->
<!--                                        <td style="text-align: left;">-->
<!--                                        <input type="text" v-model="version" class="form-control">-->
<!--                                        </td>-->
<!--                                    </tr>-->
<!--                                    <tr>-->
                                        <td width="12%"><b>Дата</b></td>
                                        <td style="text-align: left;">
                                        <input type="date" v-model="offer_date" class="form-control" @change="calcValidDate()">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="12%" height="30px"><b>№ запроса</b></td>
                                        <td style="text-align: left; padding: 7px">{{ project.internal_id }}</td>
                                    </tr>
                                    <tr>
                                        <td width="12%"><b>Заказчик</b></td>
                                        <td style="text-align: left;">{{ company.company_name }}</td>
                                    </tr>
                                    <tr>
                                        <td width="12%">
                                            <b>Представитель заказчика</b>
                                        </td>
                                        <td style="text-align: left;">
                                            <select  v-model="staff" class="form-control">
                                            <option value="0">Веберите представителя</option>
                                            <option  v-for="item in compamyStaff" :value="item.id">  {{ item.name }} {{ item.position }}</option>
                                        </select>
                                        </td>
                                    </tr>
<!--                                    <tr>-->
<!--                                        <td width="12%"><b>Client ID</b></td>-->
<!--                                        <td style="text-align: left;">{{ company.script }}</td>-->
<!--                                    </tr>-->
                                    <tr>
                                        <td width="12%"><b>Коммерческое предложение действует до</b></td>
                                        <td style="text-align: left;"><input type="date" v-model="validd_date" class="form-control" id="valid_date"></td>
                                    </tr>
                                    <tr>
                                        <td width="12%"><b>Представитель исполнителя</b></td>
                                        <td style="text-align: left;">
                                            <select  v-model="user" class="form-control">
                                            <option value="0">Выберите ответственного</option>
                                            <option v-for="item in users" :value="item.id">{{ item.lasttname }} {{ item.firstname }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
    
                                <br>
                                <button class="btn btn-success" @click="addOfferIttem()">+</button>
                                </div>
                                
 <br>   
                               
                                   <div  style="width: 100%;">
                                  <table width="100%" class="offer__table" cellpadding="0" cellspacing="0" >
                                    <thead  style="">
                                    <tr class="xl90"  style="page-break-after:avoid">
                                        <th>№</th>
                                        <th><input type="text" class="form-control" v-model="zabolevanie"></th>
                                        <th><input type="text" class="form-control" v-model="opis"></th>
                                        <th><input type="text" class="form-control" v-model="kol"></th>
                                        <th><input type="text" class="form-control" v-model="cenaEtapa"></th>
                                        <th><input type="text" class="form-control" v-model="obstoimost"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="table_bodyru">
                                    <tr class="xl91" v-for="(item, index) in offerArray">
                                        <td style="text-align: center; padding: 10px; vertical-align: middle;" valign="center" @dblclick="openEditOfferItem(item)">{{ index + 1 }}</td>
                                        <td style="text-align: center; padding: 10px; vertical-align: middle;" valign="center" align="cen">
                                       {{ item.disease }}
                                        </td>
                                        <td style="text-align: left; padding: 10px; vertical-align: middle;" valign="center" v-html="item.description" @dblclick="openEdit(item)"></td>              
                                        <td style="text-align: center; vertical-align: middle;" valign="center">{{ item.quantity }}</td>
                                        <td style="text-align: center; vertical-align: middle;" valign="center">{{ new Intl.NumberFormat('ru-RU').format(item.sumPrice)}}</td>
                                        <td style="text-align: center; vertical-align: middle;" valign="center">{{ new Intl.NumberFormat('ru-RU').format(item.totalPrice)}}</td>
                                     
                                    </tr>
                                    </tbody>
                                </table>
                                 <br>
                                </div>
                                <br>
                                <table width="100%" >
                                    <tbody>
                                    <tr>
                                        <td><b>Срок выполнения проекта</b></td>
                                        <td style="text-align: right;"><input type="text" class="form-control" v-model="turnaround"></td>
                                        <td style="text-align: right; padding-right: 10px"></td>
                                        <td class="xl90" style="text-align: right; padding-right: 10px; ">{{ new Intl.NumberFormat('ru-RU').format(totalPrice)}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Условия хранения</b></td>
                                        <td style="text-align: right;"><input type="text" class="form-control" v-model="conditions"></td>
                                    </tr>
                                    <tr>
                                    <td><b>Выберите тип доставки </b></td>
                                    <td><select class="form-control" v-model="typeDelivery">
                                    <option value="0">Вообще нет никакой таблицы и никакой строчки</option>
                                    <option value="1">Доставку осущевляет НБС</option>
                                    <option value="2">Доставка включена в стоимость этапа</option>
                                    <option value="3">Доставка оплачивается заказчиком и не включена в этап</option>
                                    </select></td>
                                    </tr>
                                    </table>
                                    <br>
                                   <table width="100%" style="margin-top: 10px" v-if="typeDelivery==1">
                                    <tr>
                                        <td><b>Доставка</b></td>
                                        <td style="text-align: right;"><input type="text" class="form-control" v-model="ru_shipping"></td>
                                    </tr>
                                    <tr>
                                        <td><b>Курьер</b></td>
                                        <td style="text-align: right;"><input type="text" class="form-control" v-model="ru_gmc"></td>
                                    </tr>
                                    <tr>
                                        <td><b>Объем контейнера/коробки</b></td>
                                        <td style="text-align: right;"><input type="text" class="form-control" v-model="ru_capacity"></td>
                                    </tr>
                                    <tr>
                                        <td><b>Температурный режим</b></td>
                                        <td style="text-align: right;"><input type="text" class="form-control" v-model="ru_thermal_mode"></td>
                                    </tr>
                                    <tr>
                                        <td><b>Количество отправок</b></td>
                                        <td style="text-align: right;"><input type="text" class="form-control" v-model="ru_count"></td>
                                    </tr>
                                    <tr>
                                        <td><b>Общая стоимость услуг</b></td>
                                        <td style="text-align: right;"><input type="text" class="form-control" v-model="ru_total"></td>
                                    </tr>
                                    </tbody>
                                </table><br>
                                    <div v-if="typeDelivery==2" style="width: 100%; margin-top: 20px;">Доставка включена в стоимость этапа.</div>
                                    <div v-if="typeDelivery==3" style="width: 100%; margin-top: 20px;">Доставка осуществляется заказчиком самостоятельно</div>
                                
                                <br>
                                <br>

       <div class="row">
       <div class="col-12">
       <div style="display: flex; justify-content: center; align-items: center"> 
       <div>
        <button class="btn btn-danger btn-block" @click="generate()" style="margin-top: 25px">GENERATE PDF</button>
        <button class="btn btn-success btn-block" @click="saveOffer()" style="margin-top: 25px">SAVE</button>

        </div>
        </div>
        </div>
</div>
</div>
                           </div>
</div>


<div  class="placeholder-app" style="text-align: center" v-if="loader != false">
<img src="../../../app-assets/img/loaders/rolling.gif" alt="loading" >
</div>

</div>


</div>

 `

});


