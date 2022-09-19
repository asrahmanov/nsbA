Vue.component('offer', {
    data() {
        return {

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


            eidtDescription: '',
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

            offer_item_id: 0,

            hbs_text: '',

            text_offer: 'The attached quote includes samples from our in-stock collection. Our proposal includes reservation of samples for 2 weeks free of charge. After this period, unless we receive from you written request for extension of reservation, samples will become available to any other interested customer. Please note that some charged may apply if reservation is extended for a prolonged period of time.'
        }
    },

    props: [
        'proj_id', 'script_id', 'company_users', 'currency', 'order_diseases',
        'order_samples', 'offer', 'offer_items'
    ],

    computed: {
        sample_options() {
            return this.order_samples.map(s => {
                return {
                    text: s.biospecimen_type + ' ' + (s.modification ? s.modification : ''),
                    value: s.biospecimen_type_id,
                    disease_id: s.disease_id,
                    modification: s.modification ? s.modification : ''
                }
            })
        },


        checkRadio () {


            if (this.text_offer == 'The attached quote includes samples from our in-stock collection. Our proposal includes reservation of samples for 2 weeks free of charge. After this period, unless we receive from you written request for extension of reservation, samples will become available to any other interested customer. Please note that some charges may be applied if reservation is extended for a prolonged period of time.') {
                return false;
            }
            
            if (this.text_offer == 'The attached quote includes samples from our in-stock collection. Samples offered to you have not been reserved and can be offered to any other interested customer.') {
                return false;
            }

            if (this.text_offer == 'Unfortunately, sample’s reservation cannot be applied in this case due to high interest expressed by other customers. We will have to accept the first PO received for these samples.') {
                return false;
            }

            return true


        }
    },

    methods: {
        splitString (n,str){
            let arr = str?.split(' ');
            let result=[]
            let subStr=arr[0]
            for(let i = 1; i < arr.length; i++){
                let word = arr[i]
                if(subStr.length + word.length + 1 <= n){
                    subStr = subStr + ' ' + word
                }
                else{
                    result.push(subStr);
                    subStr = word
                }
            }
            if(subStr.length){result.push(subStr)}
            return result
        },

        splaceStr(anyString,n){

            if(n == 2) {
                let array = this.splitString(2000, anyString)
                return array[1]
            }

            if(n == 1) {
                let array = this.splitString(2000, anyString)
                return array[0]
            }


        },
        getOffer() {
            return new Promise(resolve => {

                let data = {result: [this.offer]}
                this.offer_date = data.result[0].date_offer;
                this.validd_date = data.result[0].date_valid;
                this.user = data.result[0].user_id;
                this.turnaround = data.result[0].turnaround;
                this.conditions = data.result[0].storage;
                this.incoterms = data.result[0].incoterms;
                this.shippingInfo = data.result[0].shipping;
                this.courierSelect = data.result[0].courier_id;
                this.text_offer = data.result[0].text_offer;

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
                resolve(this.offer_items);
            });
        },
        getTimelines(offer_id) {
            return new Promise(resolve => {
                this.$parent.getJson(`../../offerItemTimeline/getByOfferItemId/?offer_item_id=${offer_id}`)
                    .then(timelines_res => {
                        resolve(timelines_res.result);
                    });
            });
        },
        getInclusionCriteria(offer_id) {
            return new Promise(resolve => {
                this.$parent.getJson(`../../offerItemInclusionCriteria/getByOfferItemId/?offer_item_id=${offer_id}`)
                    .then(inclusion_criterias_res => {
                        resolve(inclusion_criterias_res.result);
                    });
            });
        },
        getExclusionCriteria(offer_id) {
            return new Promise(resolve => {
                this.$parent.getJson(`../../offerItemExclusionCriteria/getByOfferItemId/?offer_item_id=${offer_id}`)
                    .then(exclusion_criterias_res => {
                        resolve(exclusion_criterias_res.result)
                    });
            });
        },
        getClinicalInformation(offer_id) {
            return new Promise(resolve => {
                this.$parent.getJson(`../../offerItemClinicalInformation/getByOfferItemId/?offer_item_id=${offer_id}`)
                    .then(clinical_information_res => {
                        resolve(clinical_information_res.result);
                    });
            });
        },
        getListOfSamples(offer_id) {
            return new Promise(resolve => {
                this.$parent.getJson(`../../offerItemListOfSamples/getByOfferItemId/?offer_item_id=${offer_id}`)
                    .then(list_of_samples_res => {
                        resolve(list_of_samples_res.result);
                    });
            });
        },


        async importOffer() {
            // this.offer = await this.getOffer();
            await this.getOffer();
            // this.$parent.getJson(`../../offerOption/getByOfferId/?offer_id=${this.offer.id}`)
            //     .then(data => {
            //         this.newlines_1 = data.result[0] ? data.result[0].newlines_1 : 1;
            //         this.newlines_2 = data.result[0] ? data.result[0].newlines_2 : 1;
            //         this.newlines_id = data.result[0] ? data.result[0].id : 0;
            //     });
            let data = await this.getOfferItems(this.offer.id)
            this.descriptionArray = data.result.length ? data.result : [[]]
            this.descriptionRow = this.descriptionArray.length
            if (data.result.length) {
                for (let index in this.descriptionArray) {
                    // this.descriptionArray.forEach(async (item, index) => {
                    let offer_item_id = this.descriptionArray[index].id;
                    let disease_id = this.descriptionArray[index].disease_id
                    let timelinesArray = this.descriptionArray[index].timelines/*await this.getTimelines(offer_item_id);*/
                    let sumPrice = timelinesArray.reduce((acc, curVal) => {
                        return acc + +curVal.price;
                    }, 0);
                    let inclusionCriteriaArray = this.descriptionArray[index].inclusion_criteria/*await this.getInclusionCriteria(offer_item_id);*/
                    let exclusionCriteriaArray = this.descriptionArray[index].exclusion_criteria/*await this.getExclusionCriteria(offer_item_id);*/
                    let clinicalInformationArray = this.descriptionArray[index].clinical_information/*await this.getClinicalInformation(offer_item_id);*/
                    let listOfSamplesArray = this.descriptionArray[index].list_of_samples/*await this.getListOfSamples(offer_item_id);*/
                    let description = ''
                    this.hbs_text = ''
                    description += ' <b>Samples:</b><br>'
                    for (let i = 0; i < timelinesArray.length; i++) {
                        let nameTimleine = '' + (i + 1);
                        if (this.descriptionRow === 1) {
                            nameTimleine = '';
                        }
                        let samples = timelinesArray[i].samples;
                        let sample_name = timelinesArray[i].sample_name;
                        description += `
                                 ${sample_name} ${samples ? samples : ''}<br>`;
                        this.hbs_text += sample_name + ' + '
                    }
                    this.hbs_text = this.hbs_text.substr(0, this.hbs_text.length - 3)
                    let specific_requirements = this.descriptionArray[index].specific_requirements;
                    if (['', null, undefined].indexOf(specific_requirements) === -1) {
                        description += `<b>Specific Requirements:</b><br>${specific_requirements}<br>`
                    }
                    let ethnicity = this.descriptionArray[index].ethnicity;
                    if (['', null, undefined].indexOf(ethnicity) === -1) {
                        description += `<b>Ethnicity:</b><br>${ethnicity}<br>`
                    }
                    let InclusionCriteriaHeader = false;
                    for (let i = 0; i < inclusionCriteriaArray.length; i++) {
                        let inclusion_criteria = inclusionCriteriaArray[i];
                        if (['', null, undefined].indexOf(inclusion_criteria) === -1) {
                            if (!InclusionCriteriaHeader) {
                                description += `<b>Inclusion criteria:</b><br>`
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
                                description += `<b>Exclusion criteria:</b><br>`
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
                                description += `<b>Associated Data Required:</b><br>`
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
                                description += `<b>List of case IDs:</b><br>`
                                ListOfSamplesHeader = true;
                            }
                            description += `${list_of_samples.list_of_samples}<br>`;
                        }
                    }
                    let type_of_collection = this.descriptionArray[index].type_of_collection;
                    let processing_details = this.descriptionArray[index].processing_details;
                    let turnaround_time = this.descriptionArray[index].turnaround_time;

                    description += `
                             ${type_of_collection ? `<b>Type of collection:</b><br>${type_of_collection}<br>` : ''}
                             ${processing_details ? `<b>Processing details:</b><br>${processing_details}<br>` : ''}
                             ${turnaround_time ? `<b>Turnaround time:</b><br>${turnaround_time}<br>` : ''}
                             `
                    let obj = {
                        proj_id: this.proj_id,
                        index: this.descriptionArray[index].id,
                        disease: this.descriptionArray[index].disease,
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
                        hbs_text: this.hbs_text
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
                    let sample_id = $(`#samplesid_${this.descriptionRow - 2}`).val(),
                        target_id = $(`#samplesid_${this.descriptionRow - 1}`).attr('id'),
                        index = target_id.split('_')[1],
                        modification = $(`#samplesid_${this.descriptionRow - 2} option:selected`).attr('modification')
                    $(`#samplesid_${index}`).val(sample_id)
                    $(`#samples_${index}`).val(modification)
                }, 2000)
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
            let offer_item_id = this.offer_item_id;
            let TimelinesArr = [], InclusionArr = [], ExclusionArr = [], ClinicalArr = [], ListOfSamplesArr = [];
            this.description = ``; //<b>Indication:</b><br>${this.disease}<br>

            this.description += ' <b>Samples:</b><br>'
            this.hbs_text = ''
            for (let i = 0; i < this.descriptionArray.length; i++) {
                let price = document.getElementById('price_' + i).value;
                let id = document.getElementById('timelineid_' + i).value;
                let sample_id = document.getElementById('samplesid_' + i).value;
                let sample_name = $(`#samplesid_${i} option:selected`).html();
                let samples = document.getElementById('samples_' + i).value;
                this.sumPrice = +this.sumPrice + price * 1;
                this.description += `
                                 ${sample_name}<br>`;
                let TimelineData = {
                    offer_item_id,
                    samples,
                    price,
                    sample_id,
                    samples
                };
                if (id) {
                    TimelineData.id = id;
                }
                let TimelineIndex = TimelinesArr.length;
                TimelinesArr.push(TimelineData);
                this.$parent.putJson(`../../../offerItemTimeline/save`, TimelineData)
                    .then(response => {
                        toastr.success('Сохранение таймлайна... ', response);
                        if (!id) {
                            $(`#timelineid_${i}`).val(response);
                            TimelinesArr[TimelineIndex].id = response;
                        }
                    });
                this.hbs_text += sample_name + ' + '
            }

            // item.timelines.forEach(timeline => {
            //
            // })
            this.hbs_text = this.hbs_text.substr(0, this.hbs_text.length - 3)
            this.hbs = `${this.hbs_text}<br>`
            let type_of_collection = this.type_of_collection;
            let processing_details = this.processing_details;
            let turnaround_time = this.turnaround_time;
            this.description += `${type_of_collection ? `<b>Type of collection:</b><br>${type_of_collection}<br>` : ''}
                                 ${processing_details ? `<b>Processing details:</b><br>${processing_details}<br>` : ''}
                                 ${turnaround_time ? `<b>Turnaround time:</b><br>${turnaround_time}<br>` : ''}`;
            let specific_requirements = this.specific_requirements;
            if (['', null, undefined].indexOf(specific_requirements) === -1) {
                this.description += `<b>Specific Requirements:</b><br>${specific_requirements}<br>`
            }
            let ethnicity = this.ethnicity;
            if (['', null, undefined].indexOf(ethnicity) === -1) {
                this.description += `<b>Ethnicity:</b><br>${ethnicity}<br>`
            }
            let InclusionCriteriaHeader = false;
            for (let i = 0; i < this.inclusionCriteriaArray.length; i++) {
                if (document.getElementById('inclusion_criteria_' + i) !== null) {
                    let inclusion_criteria = document.getElementById('inclusion_criteria_' + i).value;
                    if (inclusion_criteria !== '') {
                        if (!InclusionCriteriaHeader) {
                            this.description += `<b>Inclusion criteria:</b><br>`
                            InclusionCriteriaHeader = true;
                        }
                        this.description += `${inclusion_criteria}<br>`;
                        let id = document.getElementById('inclusionid_' + i).value;
                        let InclusionCriteriaData = {offer_item_id, inclusion_criteria};
                        if (id) {
                            InclusionCriteriaData.id = id;
                        }
                        let InclusionIndex = InclusionArr.length;
                        InclusionArr.push(InclusionCriteriaData);
                        this.$parent.putJson(`../../../offerItemInclusionCriteria/save`, InclusionCriteriaData)
                            .then(response => {
                                if (!id) {
                                    $(`#inclusionid_${i}`).val(response);
                                    InclusionArr[InclusionIndex].id = response;
                                }
                            });
                    }
                }
            }
            let ExclusionCriteriaHeader = false;
            for (let i = 0; i < this.exclusionCriteriaArray.length; i++) {
                if (document.getElementById('exclusion_criteria_' + i) !== null) {
                    let exclusion_criteria = document.getElementById('exclusion_criteria_' + i).value;
                    if (exclusion_criteria !== '') {
                        if (!ExclusionCriteriaHeader) {
                            this.description += `<b>Exclusion criteria:</b><br>`
                            ExclusionCriteriaHeader = true;
                        }
                        this.description += `${exclusion_criteria}<br>`;
                        let id = document.getElementById('exclusionid_' + i).value;
                        let ExclusionCriteriaData = {offer_item_id, exclusion_criteria};
                        if (id) {
                            ExclusionCriteriaData.id = id;
                        }
                        let ExclusionIndex = ExclusionArr.length;
                        ExclusionArr.push(ExclusionCriteriaData);
                        this.$parent.putJson(`../../../offerItemExclusionCriteria/save`, ExclusionCriteriaData)
                            .then(response => {
                                if (!id) {
                                    $(`#exclusionid_${i}`).val(response);
                                    ExclusionArr[ExclusionIndex].id = response;
                                }
                            });
                    }
                }
            }
            let ClinicalInformationHeader = false;
            for (let i = 0; i < this.clinicalInformationArray.length; i++) {
                if (document.getElementById('clinical_information_' + i) !== null) {
                    let clinical_information = document.getElementById('clinical_information_' + i).value;
                    if (clinical_information !== '') {
                        if (!ClinicalInformationHeader) {
                            this.description += `<b>Associated Data Required:</b><br>`
                            ClinicalInformationHeader = true;
                        }
                        this.description += `${clinical_information}<br>`;
                        let id = document.getElementById('clinicalid_' + i).value;
                        let ClinicalInformationData = {offer_item_id, clinical_information};
                        if (id) {
                            ClinicalInformationData.id = id;
                        }
                        let ClinicalIndex = ClinicalArr.length;
                        ClinicalArr.push(ClinicalInformationData);
                        this.$parent.putJson(`../../../offerItemClinicalInformation/save`, ClinicalInformationData)
                            .then(response => {
                                if (!id) {
                                    $(`#clinicalid_${i}`).val(response);
                                    ClinicalArr[ClinicalIndex].id = response;
                                }
                            });
                    }
                }
            }
            let ListOfSamplesHeader = false;
            for (let i = 0; i < this.listOfSamplesArray.length; i++) {
                if (document.getElementById('list_of_samples_' + i) !== null) {
                    let list_of_samples = document.getElementById('list_of_samples_' + i).value;
                    if (list_of_samples !== '') {
                        if (!ListOfSamplesHeader) {
                            this.description += `<b>List of case IDs:</b><br>`
                            ListOfSamplesHeader = true;
                        }
                        this.description += `${list_of_samples}<br>`;
                        let id = document.getElementById('list_of_samples_id_' + i).value;
                        let ListOfSamplesData = {offer_item_id, list_of_samples};
                        if (id) {
                            ListOfSamplesData.id = id;
                        }
                        let ListOfSamplesIndex = ListOfSamplesArr.length;
                        ListOfSamplesArr.push(ListOfSamplesData);
                        this.$parent.putJson(`../../../offerItemListOfSamples/save`, ListOfSamplesData)
                            .then(response => {
                                if (!id) {
                                    $(`#list_of_samples_id_${i}`).val(response);
                                    ListOfSamplesArr[ListOfSamplesIndex].id = response;
                                }
                            });
                    }
                }
            }

            let obj, keyToReplace = -1;
            for (let key in this.offerArray) {
                if (this.offerArray[key].index === this.offer_item_id) {
                    obj = this.offerArray[key];
                    keyToReplace = key;
                }
            }
            if (!obj) {
                obj = {};
            }
            // obj = {
            let disease_id = $("#order_diseases option:selected").data('id')
            obj.proj_id = this.proj_id
            obj.index = this.offer_item_id
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
                id: this.offer_item_id,
                offer_id: this.offer.id,
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

            this.$parent.putJson(`../../../offerItem/save`, data)
                .then(response => {
                    toastr.success('Создание ряда ... ', response);
                    this.offer_item_id = response;
                });

            if (keyToReplace === -1) {
                this.offerArray.push(obj);
            }

            this.clearArray();
            $('#iconModal').modal('hide');
            this.totalSum();
        },

        deleteRow() {
            let id = this.offer_item_id;
            $('#iconModal').modal('hide');
            this.$parent.putJson(`../../../offerItem/delete`, {id})
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
                offer_id: this.offer.id,
            }

            this.$parent.putJson(`../../../offerItem/save`, data)
                .then(response => {
                    toastr.success('Создание ряда ... ', response);
                    this.offer_item_id = response;
                    $(".timelineid, .inclusionid, .exclusionid, .clinicalid, .list_of_samples_id").val('')
                    for (let i = 0; i < this.descriptionArray.length; i++) {
                        document.getElementById('timelineid_' + i).value = '';
                    }
                    for (let i = 0; i < this.inclusionCriteriaArray.length; i++) {
                        document.getElementById('inclusionid_' + i).value = '';
                    }
                    for (let i = 1; i < this.exclusionCriteriaArray.length; i++) {
                        document.getElementById('exclusionid_' + i).value = '';
                    }
                    for (let i = 1; i < this.clinicalInformationArray.length; i++) {
                        document.getElementById('clinicalid_' + i).value = '';
                    }
                    for (let i = 1; i < this.listOfSamplesArray.length; i++) {
                        document.getElementById('list_of_samples_id_' + i).value = '';
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

        addInitData() {
            // this.clinicalInformationArray = [
            //     {clinical_information: 'Age, gender, ethnicity'},
            //     {clinical_information: 'Confirmation of diagnosis'},
            //     {clinical_information: 'Treatment information if any'},
            //     {clinical_information: 'Date of collection'},
            //     {clinical_information: 'HIV/HBV/HCV tested with negative result'},
            // ];
            // this.clinicalInformationRow = 1;
        },
        saveDescription() {
            let el = this.offerArray.find(el => {
                if (el.index = this.editDescriptionId) {
                    return true
                }
            })

            if (el !== undefined) {
                el.description = tinymce.get('eidtDescription').getContent();
                $('#iconModalEdit').modal('hide');
            }
        },

        openModal() {
            $('#iconModal').modal('show');
        },

        addOfferIttem() {
            this.clearArray();
            this.addInitData();
            this.openModal();
            let data = {
                offer_id: this.offer.id,
            }

            this.$parent.putJson(`../../../offerItem/save`, data)
                .then(response => {
                    toastr.success('Создание ряда ... ', response);
                    this.offer_item_id = response;
                });
        },

        openEdit(item) {
            $('#iconModalEdit').modal('show');
            this.editDescriptionId = item.index + 1;
            tinymce.get("eidtDescription").setContent(item.description);
        },

        openEditOfferItem(item) {
            this.offer_item_id = item.index;
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
                    // let order_samples_filtered = this.order_samples.filter((el) => {
                    //     return parseInt(el.disease_id) === parseInt(item.disease_id) &&
                    //         parseInt(el.biospecimen_type_id) === parseInt(timeline.sample_id)
                    // })
                    let order_samples_filtered = this.order_samples.filter((el) => {
                        return parseInt(el.id) === parseInt(item.id)
                    })
                    if (order_samples_filtered.length)
                        timelinesArr[index]['samples'] = order_samples_filtered[0]['modification']
                    else
                        timelinesArr[index]['samples'] = ''
                    // timelinesArr[index]['modification'] =
                    // console.log(timelinesArr[index]['modification'])
                    // let disease_id = timeline.disease_id, sample_id = timeline.sample_id,
                    //     Mod = this.order_samples.filter(os => {
                    //         return parseInt(os.disease_id) === parseInt(disease_id) && parseInt(os.biospecimen_type_id) === parseInt(sample_id)
                    //     })[0]
                }

                setTimeout(() => {
                    // $(`#samples_${index}`).val(timelinesArr[index]['modification'])
                    $(`#samples_${index}`).val(timelinesArr[index]['samples'])
                    $(`#samplesid_${index} option[value="${timelinesArr[index]['sample_id']}"][modification="${timelinesArr[index]['samples']}"]`).prop('selected', true)
                    // $(`#samplesid_${index} option[modification="${timelinesArr[index]['samples']}"]`).prop('selected', true)
                }, 2000)
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
                id: this.offer.id,
                date_offer: this.offer_date,
                scripts_staff_id: this.staff,
                date_valid: this.validd_date,
                user_id: this.user,
                turnaround: this.turnaround,
                storage: this.conditions,
                text_offer: this.text_offer,

                incoterms: this.incoterms,
                shipping: this.shippingInfo,
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
            }

            this.$parent.putJson(`../../../offer/save`, data)
                .then(response => {
                    toastr.success('Сохранение ... ');
                })

        },

        saveNewLines() {
            let data = {
                id: this.newlines_id,
                offer_id: this.offer.id,
                newlines_1: this.newlines_1,
                newlines_2: this.newlines_2
            }
            this.$parent.putJson(`../../../offerOption/save`, data);
        },

        generate() {
            this.loader = true;

            let data = {
                id: this.offer.id,
                date_offer: this.offer_date,
                document: this.project.internal_id,
                distribution: this.company.company_name,
                scripts_staff_id: this.staff,
                client_id: this.company.script,
                date_valid: this.validd_date,
                user_id: this.user,
                turnaround: this.turnaround,
                storage: this.conditions,
                currency: this.currency,
                totalPrice: new Intl.NumberFormat('ru-RU', {
                    style: 'currency',
                    currency: this.currency
                }).format(this.totalPrice),

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
                total_shipping: new Intl.NumberFormat('ru-RU', {
                    style: 'currency',
                    currency: this.currency
                }).format(this.totalShipping_value),
                payment_terms: this.company.payment_terms,
                version: this.version,
                table_body: document.getElementById('table_body').innerHTML,
                newlines_1: this.newlines_1,
                newlines_2: this.newlines_2,
                text_offer: this.text_offer,
            }

            this.$parent.putJson(`../../../offer/generatePdf`, data)
                .then(response => {
                    // location.href = "../../../orders/info/?idFR=" + this.proj_id;
                })


        },

        // saveTimline() {
        //
        // },
        deleteTimeline(index) {
            this.$parent.putJson(`../../../offerItemTimeline/delete`, {id: this.descriptionArray[index].id});
            this.descriptionArray.splice(index, 1);
            this.descriptionRow--;
        },
        deleteInclusionCriteria(index) {
            this.$parent.putJson(`../../../offerItemInclusionCriteria/delete`, {id: this.inclusionCriteriaArray[index].id});
            this.inclusionCriteriaArray.splice(index, 1);
            this.inclusionCriteriaRow--;
        },
        deleteExclusionCriteria(index) {
            this.$parent.putJson(`../../../offerItemExclusionCriteria/delete`, {id: this.exclusionCriteriaArray[index].id});
            this.exclusionCriteriaArray.splice(index, 1);
            this.exclusionCriteriaRow--;
        },
        deleteClinicalInformation(index) {
            this.$parent.putJson(`../../../offerItemClinicalInformation/delete`, {id: this.clinicalInformationArray[index].id});
            this.clinicalInformationArray.splice(index, 1);
            this.clinicalInformationRow--;
        },
        deleteListOfSamples(index) {
            this.$parent.putJson(`../../../offerItemListOfSamples/delete`, {id: this.listOfSamplesArray[index].id});
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
            let id = $("#order_diseases option:selected").attr('data-id')
            $(".order_samples").empty()
            this.order_samples.forEach(order_sample => {
                if (parseInt(order_sample.disease_id) === parseInt(id)) {
                    $(".order_samples").append(`
                        <option value="${order_sample.biospecimen_type_id}" id="${order_sample.disease_id}_${order_sample.biospecimen_type_id}"
                            modification="${order_sample.modification || ''}">${order_sample.biospecimen_type} ${order_sample.modification || ''}</option>`)
                }
            })
            $(".order_samples").each(function (timeline_index) {
                let target_id = $(this).attr('id'),
                    index = target_id.split('_')[1],
                    modification = $(`#samplesid_${index} option:selected`).attr('modification')
                $(`#samples_${index}`).val(modification)
            })
        },
        sampleModification($event) {
            let val = $("#order_diseases").val(), option = $(`#order_diseases [value="${val}"]`),
                disease_id = option.data('id'), sample_id = $($event.currentTarget).val(),
                target_id = $($event.currentTarget).attr('id'), index = target_id.split('_')[1],
                modification = $(`#samplesid_${index} option:selected`).attr('modification'),
                Mod = this.order_samples.filter(os => {
                    return parseInt(os.disease_id) === parseInt(disease_id) &&
                        parseInt(os.biospecimen_type_id) === parseInt(sample_id) &&
                        os.modification === modification
                })[0]
            if (!!Mod)
                $(`#samples_${index}`).val(Mod.modification)
            else {
                $(`#samples_${index}`).val('')
            }
        },
        calcValidDate() {
            let d = new Date(this.offer_date)

            Date.prototype.yyyymmdd = function () {
                var mm = this.getMonth() + 1; // getMonth() is zero-based
                var dd = this.getDate();

                return [
                    (dd > 9 ? '' : '0') + dd,
                    (mm > 9 ? '' : '0') + mm,
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

<div class="modal fade text-left" id="iconModal"  role="dialog" aria-labelledby="myModalLabel4" 
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
                                        <label for="disease">Disease</label>
                                        <select id="order_diseases" v-model="disease" class="form-control" @change="samplesOptions($event)">
                                            <option v-for="order_disease in order_diseases" :value="order_disease.disease"
                                                                        :data-id="order_disease.disease_id"
                                                                        >{{ order_disease.disease }} ({{ order_disease.mutation }})</option>
                                        </select>
                                    </div> 
                                    <div class="col-4" style="display: none;">
                                        <label for="hbs">HBS Type</label>
                                        <input type="text" class="form-control" :value="hbs_text">
                                    </div>
                                      <div class="col-4">
                                         <label for="Indication">Quantity</label>
                                         <input type="number" v-model="quantity" class="form-control" @input="sumTotal()">
                                    </div>
                                    <div class="col-4">
                                         <label>Type of collection</label>
                                         <input type="text" class="form-control" v-model="type_of_collection">
                                       </div>                                        
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                            <label for="specific_requirements">Specific Requirements</label>
                                            <input type="text" v-model="specific_requirements" class="form-control" id="specific_requirements">                                
                                    </div>
                                    <div class="col-4">
                                            <label for="ethnicity">Ethnicity</label>
                                            <select id="ethnicity" v-model="ethnicity" class="form-control">
                                                <option value=""></option>
                                                <option value="Any">Any</option>
                                                <option value="Caucasian">Caucasian</option>
                                                <option value="Asian">Asian</option>
                                            </select>       
                                    </div>
                                    <div class="col-4">
                                         <label for="Indication">Processing details:</label>
                                         <input type="text" class="form-control" v-model="processing_details">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                    <label for="Indication">Turnaround time:</label>
                                    <input type="text" class="form-control" v-model="turnaround_time">
                                    </div>
                                </div>
                                <br>
                                    <h4>Description</h4>
                                   <div class="col-2">
                                         <button class="btn btn-dark mt-3" @click="checkCountRow('+')">+</button>
                                         </div>
                                 <div class="row" v-for="(item, index) in descriptionArray">
                                    <input type="hidden" v-model="item.id" :id="'timelineid_'+ index" class="timelineid">
                                     <div class="col-1" style="display:none;">
                                         <label>Timeline</label>
                                         <input type="text" class="form-control"
                                            :id="'timeline_'+ index"
                                            v-model="item.timeline"> 
                                     </div> 
                                       <div class="col-6">
                                         <label>Sample</label>
                                        <select class="form-control order_samples"
                                            :id="'samplesid_' + index"
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
                                        <label>Modification</label>
                                        <input type="text" class="form-control" disabled
                                            :id="'samples_' + index"> 
                                       </div>  
                                       
                                         <div class="col-2">
                                            <label for="Indication">Price {{ currency }}</label>
                                            <input type="number" class="form-control sumTotal"
                                                :id="'price_' + index"
                                                @input="sumTotal()"
                                                v-model="item.price"
                                                >
                                         </div>
                                          <div class="col-1">
                                            <label for="Indication">delete</label>
                                            <input type="button" class="form-control btn btn-danger" value="del" @click="deleteTimeline(index)">
                                         </div>
                                    </div>
                                        <div class="row">
                                        <div class="col-10"></div>
                                        <div class="col-2">{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(totlalAll) }}</div>
                                        </div> 
                                    <hr>
                                        <div class="row" >
                                 <div class="col-3">
                                         <label>Inclusion criteria</label><br>
                                         <button class="btn btn-dark mt-3"
                                            @click="checkCountInclusionCriteriaRow('+')">+</button>
                                         <div v-for="(inclusion_item, inclusion_index) in inclusionCriteriaArray">
                                            <input type="hidden"  class="inclusionid"
                                                :id="'inclusionid_' + inclusion_index"
                                                v-model="inclusion_item.id">
                                            <div class="row">
                                                <div class="col-9">
                                                    <input type="text" class="form-control"
                                                        :id="'inclusion_criteria_' + inclusion_index"
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
                                         <label>Exclusion criteria:</label><br>
                                         <button class="btn btn-dark mt-3"
                                            @click="checkCountExclusionCriteriaRow('+')">+</button>
                                         <div v-for="(exclusion_item, exclusion_index) in exclusionCriteriaArray">
                                            <input type="hidden"  class="exclusionid"
                                                :id="'exclusionid_' + exclusion_index"
                                                v-model="exclusion_item.id"> 
                                            <div class="row">
                                                <div class="col-9">
                                                     <input type="text" class="form-control"
                                                        :id="'exclusion_criteria_' + exclusion_index"
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
                                         <label>Clinical information:</label><br>
                                         <button class="btn btn-dark mt-3"
                                            @click="checkCountClinicalInformationRow('+')">+</button>
                                         <div v-for="(clinical_item, clinical_index) in clinicalInformationArray">
                                            <input type="hidden"  class="clinicalid"
                                                :id="'clinicalid_' + clinical_index"
                                                v-model="clinical_item.id">
                                            <div class="row">
                                                <div class="col-9">
                                                    <input type="text" class="form-control"
                                                        :id="'clinical_information_' + clinical_index"
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
                                         <label>List of case IDs:</label><br>
                                        <button class="btn btn-dark mt-3"
                                            @click="checkCountListOfSamplesRow('+')">+</button>
                                         <div v-for="(list_of_samples_item, list_of_samples_index) in listOfSamplesArray">
                                            <input type="hidden" class="list_of_samples_id"
                                                :id="'list_of_samples_id_' + list_of_samples_index"
                                                v-model="list_of_samples_item.id">
                                            <div class="row">
                                                <div class="col-9">
                                                    <input type="text" class="form-control"
                                                        :id="'list_of_samples_' + list_of_samples_index"
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
<div class="modal fade text-left" id="iconModalEdit"  role="dialog" aria-labelledby="myModalLabel4" 
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
            <textarea name="" id="eidtDescription" cols="30" rows="30"> </textarea>
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
                                      <tr>
                                        <td width="12%"><b>Version</b></td>
                                        <td style="text-align: left;">
                                        <input type="text" v-model="version" class="form-control">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="12%"><b>Date</b></td>
                                        <td style="text-align: left;">
                                        <input type="date" v-model="offer_date" class="form-control" @change="calcValidDate()">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="12%" height="30px"><b>Document #</b></td>
                                        <td style="text-align: left; padding: 7px">{{ project.internal_id }}</td>
                                    </tr>
                                    <tr>
                                        <td width="12%"><b>Distribution</b></td>
                                        <td style="text-align: left;">{{ company.company_name }}</td>
                                    </tr>
                                    <tr>
                                        <td width="12%">
                                            <b>Attention to</b>
                                        </td>
                                        <td style="text-align: left;">
                                            <select  v-model="staff" class="form-control">
                                            <option value="0">Веберите менеджера</option>
                                            <option  v-for="item in compamyStaff" :value="item.id">  {{ item.name }} {{ item.position }}</option>
                                        </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="12%"><b>Client ID</b></td>
                                        <td style="text-align: left;">{{ company.script }}</td>
                                    </tr>
<!--                                    <tr>-->
<!--                                        <td width="12%">&nbsp;</td>-->
<!--                                        <td style="text-align: left;">&nbsp;</td>-->
<!--                                    </tr>-->

                                    <tr>
                                        <td width="12%"><b>Valid for</b></td>
                                        <td style="text-align: left;"><input type="date" v-model="validd_date" class="form-control" id="valid_date"></td>
                                    </tr>
                                    <tr>
                                        <td width="12%"><b>Issued by</b></td>
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
                               
                                    <div id="table_body" style="width: 100%;" >
                                      <table width="100%" class="offer__table" cellpadding="0" cellspacing="0">
                                        <thead  style="">
                                        <tr class="xl90">
                                            <th>Item</th>
                                            <th>Disease</th>
    <!--                                        <th style='page-break-inside: auto'>HBS Type</th>-->
                                            <th>Description</th>
                                            <th>Quantity</th>
                                            <th>Price({{currency}})</th>
                                            <th>Total Price({{currency}})</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="xl91" v-for="(item, index) in offerArray" v-if="item.description.length < 2000">
                                            <td style="text-align: center; padding: 10px; vertical-align: middle; border: 0.5pt solid #000;" valign="center" @dblclick="openEditOfferItem(item)">{{ index + 1 }}</td>
                                            <td style="text-align: center; padding: 10px; vertical-align: middle; border: 0.5pt solid #000;" valign="center" align="cen">
                                            {{ item.disease }}
                                            </td>
    <!--                                        <td style="text-align: center; padding: 10px; vertical-align: middle;" valign="center">{{ item.hbs_text }}</td>-->
                                            <td  v-if="item.description.length > 2000" style="text-align: left; padding: 10px; vertical-align: middle; border: 0.5pt solid #000;" valign="center" v-html="splaceStr(item.description, 1)" @dblclick="openEdit(item)"></td>              
                                            <td v-else style="text-align: left; padding: 10px; vertical-align: middle; border: 0.5pt solid #000;" valign="center" v-html="item.description" @dblclick="openEdit(item)"></td>              
                                            
                                            <td style="text-align: center; vertical-align: middle; border: 0.5pt solid #000;" valign="center">{{ item.quantity }}</td>
                                            <td style="text-align: center; vertical-align: middle; border: 0.5pt solid #000;" valign="center">{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(item.sumPrice)}}</td>
                                            <td style="text-align: center; vertical-align: middle; border: 0.5pt solid #000;" valign="center">{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(item.totalPrice)}}</td>
                                        </tr>
                                        
                                          <tr class="xl91" v-for="(item, index) in offerArray" v-if="item.description.length > 2000" >
                                            <td style="text-align: center; padding: 10px; vertical-align: middle; border: 0.5pt solid #000;" valign="center" @dblclick="openEditOfferItem(item)">{{ index + 1 }}</td>
                                            <td style="text-align: center; padding: 10px; vertical-align: middle; border: 0.5pt solid #000;" valign="center" align="cen">
                                            {{ item.disease }}
                                            </td>
    <!--                                        <td style="text-align: center; padding: 10px; vertical-align: middle;" valign="center">{{ item.hbs_text }}</td>-->
                                            <td  v-if="item.description.length > 2000" style="text-align: left; padding: 10px; vertical-align: middle; border-bottom: 0" valign="center" v-html="splaceStr(item.description, 1)" @dblclick="openEdit(item)"></td>              
                                            <td v-else style="text-align: left; padding: 10px; vertical-align: middle; border: 0.5pt solid #000;" valign="center" v-html="splaceStr(item.description, 1)" @dblclick="openEdit(item)"></td>              
                                            
                                            <td style="text-align: center; vertical-align: middle; border: 0.5pt solid #000;" valign="center">{{ item.quantity }}</td>
                                            <td style="text-align: center; vertical-align: middle; border: 0.5pt solid #000;" valign="center">{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(item.sumPrice)}}</td>
                                            <td style="text-align: center; vertical-align: middle; border: 0.5pt solid #000;" valign="center">{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(item.totalPrice)}}</td>
                                        </tr>
                                        
                                         <tr class="xl91" v-for="(item, index) in offerArray" v-if="item.description.length > 2000" style="border-top: 0">
                                            <td style="text-align: center; padding: 10px; vertical-align: middle; border: 0.5pt solid #000;" valign="center" ></td>
                                            <td style="text-align: center; padding: 10px; vertical-align: middle; border: 0.5pt solid #000;" valign="center" align="cen">
                                           
                                            </td>
    <!--                                        <td style="text-align: center; padding: 10px; vertical-align: middle;" valign="center">{{ item.hbs_text }}</td>-->
                                            <td style="text-align: left; padding: 10px; vertical-align: middle; border: 0.5pt solid #000;" valign="center" v-html="splaceStr(item.description, 2)" ></td>              
                                            <td style="text-align: center; vertical-align: middle; border: 0.5pt solid #000;" valign="center"></td>
                                            <td style="text-align: center; vertical-align: middle; border: 0.5pt solid #000;" valign="center"> </td>
                                            <td style="text-align: center; vertical-align: middle; border: 0.5pt solid #000;" valign="center"> </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                 <br>
                                </div>
                                
                                
                                <br>
                                <table width="100%" >
                                    <tbody>
                                    <tr>
                                        <td width="12%"><b>Turnaround time</b></td>
                                        <td style="text-align: right;"><input type="text" class="form-control" v-model="turnaround"></td>
                                        <td style="text-align: right; padding-right: 10px"><b>TOTAL per biospecimens
                                                procurement service ({{currency}}) </b></td>
                                        <td class="xl90" style="text-align: right; padding-right: 10px; ">{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(totalPrice)}}</td>
                                    </tr>
                                    <tr>
                                        
                                        <td width="12%"><b>Storage conditions</b></td>
                                        <td style="text-align: right;"><input type="text" class="form-control" v-model="conditions"></td>
                                        <td style="text-align: right; padding-right: 10px"></td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table><br>
                                <div class="row">
                                    <div class="col-3">
                                        <input type="number" class="form-control" id="newlines_1" 
                                            v-model="newlines_1"
                                            @change="saveNewLines()">
                                    </div>
                                    <div class="col-9">
                                        <label for="newlines_1">Переводов строк</label>
                                    </div>
                                </div>
                                <table width="70%" cellpadding="0" cellspacing="0" style="margin-top: 25px">
                                    <tbody>
                                    <tr class="xl90">
                                        <td colspan="2"><b>Shipping & Handling requirements</b></td>
                                    </tr>
                                    <tr class="xl91">
                                        
                                        <td style="text-align: left; padding-left: 5px"><b>Incoterms</b></td>
                                        <td style="text-align: left; padding-left: 5px">
                                        <select name="" id="" class="form-control" v-model="incoterms">
                                            <option value="DAP">DAP</option>
                                            <option value="EXW">EXW</option>
                                            <option value="FCA">FCA</option>
                                            <option value="FAS">FAS</option>
                                            <option value="FOB">FOB</option>
                                            <option value="CFR">CFR</option>
                                            <option value="CIF">CIF</option>
                                            <option value="CPT">CPT</option>
                                            <option value="CIP">CIP</option>
                                            <option value="DPU">DPU</option>
                                            <option value="DDP">DDP</option>
                                        </select>
                                        </td>
                                    </tr>
                                    <tr class="xl91">
                                        
                                        <td style="text-align: left; padding-left: 5px"><b>Shipping conditions</b></td>
                                        <td style="text-align: left; padding-left: 5px">
                                        <select name="" id="" class="form-control" v-model="shippingInfo">
                                            <option value="DRY ICE">DRY ICE</option>
                                            <option value="AMBIENT">AMBIENT</option>
                                            <option value="(+2, +8С)">(+2, +8С)</option>
                                            <option value="AMBIENT and DRY ICE">AMBIENT and DRY ICE</option>
                                            <option value="DRY ICE and (+2, +8С)">DRY ICE and (+2, +8С)</option>
                                            <option value="AMBIENT and (+2, +8С)">AMBIENT and (+2, +8С)</option>
                                        </select>
                                        </td>
                                    </tr>
                                    <tr class="xl91">
                                        <td style="text-align: left; padding-left: 5px"><b>Courier</b></td>
                                        <td style="text-align: left; padding-left: 5px">
                                        
                                         <select name="" id="" class="form-control" v-model="courierSelect" @change="loadShopiping()">
                                            <option  v-for="item in courier" :value="item.id">{{ item.courier_name}}</option>
                                        </select>
</td>
                                    </tr>
                                    <tr class="xl91">
                                        <td style="text-align: left; padding-left: 5px"><b>Estimated number of
                                                shipments</b></td>
                                        <td style="text-align: left; padding-left: 5px">
                                        <input type="number" v-model="estimated" class="form-control">
</td>
                                    </tr>
                                    </tbody>
                                </table>

                                <br>
                                <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 25px">
                                    <tbody>
                                    <tr class="xl90">
                                        <td><b>Shipping & Handling expense items</b></td>
                                        <td></td>
                                        <td>Quantity</td>
                                        <td>Price ({{ currency }})</td>
                                        <td>Total price ({{ currency }})</td>
                                    </tr>
                                    
                                      <tr class="xl91">
                                        <td style="text-align: left; padding-left: 5px"><b>Courier shipping fee </b></td>
                                        <td style="text-align: left; padding-left: 5px">
                                         <select name="" v-model="shipping_fee" class="form-control" @change="changeShoppping()">
                                         <option v-for="item in shipping" :value="item.id">{{ item.shipping_name }}</option>
                                       
                                        </select>   

                                        <td style="text-align: center; padding-left: 5px">
                                        <input type="number" v-model="shipping_fee_value" class="form-control" @input="totalShipping()">
                                        </td>
                                        <td style="text-align: right; padding-left: 5px">     {{
                                          new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(shipping_fee_price)
                                          }}</td>
                                        <td  style="text-align: right; padding-left: 5px">{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(shipping_fee_price*shipping_fee_value)  }}</td>
                                        
                                    </tr>
                                   
                                    <tr class="xl91">
                                        <td style="text-align: left; padding-left: 5px"><b>Export permit </b></td>
                                        <td style="text-align: left; padding-left: 5px">
                                         <select name="" v-model="export_permit" class="form-control" @change="totalShipping()">
                                         <option value="no">no</option>
                                         <option value="yes">yes</option>
                                        </select>   

                                        <td style="text-align: center; padding-left: 5px">
                                        <input type="number" v-model="export_permit_value" class="form-control" @input="totalShipping()">
                                        </td>
                                        <td style="text-align: right; padding-left: 5px">     {{
                                          new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(export_permit_price)
                                          
                                          }}</td>
                                        <td v-if="export_permit== 'yes'" style="text-align: right; padding-left: 5px">{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(export_permit_price*export_permit_value)  }}</td>
                                         <td v-else style="text-align: right; padding-left: 5px">{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(0)  }}    </td>
                                    </tr>
                                    
                                      <tr class="xl91">
                                        <td style="text-align: left; padding-left: 5px"><b>Export сustoms clearance (broker's fee) </b></td>
                                        <td style="text-align: left; padding-left: 5px">
                                         <select name="" v-model="customs_clearance" class="form-control" @change="totalShipping()">
                                         <option value="no">no</option>
                                         <option value="yes">yes</option>
                                        </select>   

                                        <td style="text-align: center; padding-left: 5px">
                                        <input type="number" v-model="customs_clearance_value" class="form-control" @input="totalShipping()">
                                        </td>
                                        <td style="text-align: right; padding-left: 5px">     {{
                                          new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(customs_clearance_price)
                                          
                                          }}</td>
                                        <td v-if="customs_clearance == 'yes'" style="text-align: right; padding-left: 5px">{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(customs_clearance_price*customs_clearance_value)  }}</td>
                                         <td v-else style="text-align: right; padding-left: 5px">{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(0)  }}    </td>
                                    </tr>
                                   
                                    
                                     <tr class="xl91">
                                        <td style="text-align: left; padding-left: 5px"><b>Thermologger</b></td>
                                        <td style="text-align: left; padding-left: 5px">
                                         <select name="" v-model="thermologger" class="form-control" @change="totalShipping()">
                                         <option value="no">no</option>
                                         <option value="yes">yes</option>
                                         <option value="optional">optional</option>
                                        </select>   

                                        <td style="text-align: center; padding-left: 5px">
                                        <input type="number" v-model="thermologger_value" class="form-control" @input="totalShipping()">
                                        </td>
                                        <td style="text-align: right; padding-left: 5px">     {{
                                          new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(thermologger_price)
                                          
                                          }}</td>
                                        <td v-if="thermologger == 'yes'" style="text-align: right; padding-left: 5px">{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(thermologger_price*thermologger_value)  }}</td>
                                         <td v-else style="text-align: right; padding-left: 5px">{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(0)  }}    </td>
                                    </tr>
                                    
                                    
                                     <tr class="xl91">
                                        <td style="text-align: left; padding-left: 5px"><b>Packaging & handling</b></td>
                                        <td style="text-align: left; padding-left: 5px">
                                         <select name="" v-model="packaging" class="form-control" @change="totalShipping()">
                                         <option value="no">no</option>
                                         <option value="yes">yes</option>
                                        </select>   

                                        <td style="text-align: center; padding-left: 5px">
                                        <input type="number" v-model="packaging_value" class="form-control" @input="totalShipping()">
                                        </td>
                                        <td style="text-align: right; padding-left: 5px">     {{
                                          new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(packaging_price)
                                          
                                          }}</td>
                                        <td v-if="packaging == 'yes'" style="text-align: right; padding-left: 5px">{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(packaging_price*packaging_value)  }}</td>
                                         <td v-else style="text-align: right; padding-left: 5px">{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(0)  }}    </td>
                                    </tr>
                                    
                                    
                                 
                               
                                    </tbody>
                                </table>
                                <br>

                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td><b>&nbsp;</b></td>
                                        <td style="text-align: right;">&nbsp;</td>
                                        <td style="text-align: right; padding-right: 10px"><b>ESTIMATE of total shipment
                                                costs ({{ currency }})</b></td>
                                        <td class="xl90" style="text-align: right; padding-right: 10px; "> {{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(totalShipping_value)}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-3">
                                        <input type="number" class="form-control" id="newlines_2"
                                            v-model="newlines_2"
                                            @change="saveNewLines()">
                                    </div>
                                    <div class="col-9">
                                        <label for="newlines_2">Переводов строк</label>
                                    </div>
                                </div>
                                <br>
                                <table width="100%" style="margin-top: 25px">
                                    <tbody>
                                    <tr class="xl90">
                                        <td colspan="2"><b>Payment policy </b></td>
                                    </tr>
                                    <tr class="xl91">
                                        <td style="text-align: left; padding-left: 5px"><b>Payment terms</b></td>
                                        <td style="text-align: left; padding-left: 5px">{{ company.payment_terms }} </td>
                                    </tr>
                                    <tr class="xl91">
                                        <td style="text-align: left; padding-left: 5px"><b>Special terms</b></td>
                                        <td style="text-align: left; padding-left: 5px">Actually incurred shipping cost
                                            will be invoiced and will depend on the weight and dimensions of shipping
                                            container(s), shipping destination and other conditions.
                                        </td>
                                    </tr>
                                    <tr class="xl91">
                                        <td style="text-align: left; padding-left: 5px"><b>&nbsp;</b></td>
                                        <td style="text-align: left; padding-left: 5px">Estimated shipping costs are calculated on the assumption of one shipment per purchase order.</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <br>
                                <br>

   
       
       
       <div style="
       display: flex;
       flex-direction: column;
        ">
        <br>
      <div class="custom-control custom-radio">
                            <input type="radio" id="text_offer_1" v-model="text_offer" value="The attached quote includes samples from our in-stock collection. Our proposal includes reservation of samples for 2 weeks free of charge. After this period, unless we receive from you written request for extension of reservation, samples will become available to any other interested customer. Please note that some charges may be applied if reservation is extended for a prolonged period of time." class="custom-control-input" >
                            <label class="custom-control-label" for="text_offer_1">The attached quote includes samples from our in-stock collection. Our proposal includes reservation of samples for 2 weeks free of charge. After this period, unless we receive from you written request for extension of reservation, samples will become available to any other interested customer. Please note that some charges may be applied if reservation is extended for a prolonged period of time.</label>
       </div>
          <br>
        <div class="custom-control custom-radio ">
                            <input type="radio" id="text_offer_2" v-model="text_offer"  value="The attached quote includes samples from our in-stock collection. Samples offered to you have not been reserved and can be offered to any other interested customer."  name="text_offer" class="custom-control-input" >
                            <label class="custom-control-label" for="text_offer_2">The attached quote includes samples from our in-stock collection. Samples offered to you have not been reserved and can be offered to any other interested customer.</label>
       </div> 
       <br>
        <div class="custom-control custom-radio ">
                            <input type="radio"  id="text_offer_3" v-model="text_offer"   value="Unfortunately, sample’s reservation cannot be applied in this case due to high interest expressed by other customers. We will have to accept the first PO received for these samples."  class="custom-control-input">
                            <label class="custom-control-label" for="text_offer_3">Unfortunately, sample’s reservation cannot be applied in this case due to high interest expressed by other customers. We will have to accept the first PO received for these samples.</label>
       </div> 
        
        <br>
        <div class="custom-control custom-radio ">
                            <input type="radio"  id="text_offer_4"  v-model="text_offer"   value="" name="text_offer" class="custom-control-input" >
                            <label class="custom-control-label"  for="text_offer_4">Custom</label>
       </div>
       
       
       <div v-if="checkRadio">
       <br>
       <textarea name="" id="" cols="30" rows="10" class="form-control" v-model="text_offer"></textarea>
</div>

        </div>
       
       
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


