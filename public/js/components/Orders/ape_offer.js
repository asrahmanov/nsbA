Vue.component('ape_offer', {
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
            offer: [], // основной массив данных шаблона
            courier: [], //  Массив курьеров
            shipping: [], // Массив Courier shipping fee
            container: [], // Массив с контейнерами для транспортировки
            version: '',

            urlSections: window.location.href.split("/"),
            //socket: io.connect('https://nbs-platforms.ru:8888'),

            visibledBlock: false, // Блок для добавление новой позиции

            // Добавлени новой строчки
            species: '',
            hbs: '',

            description: '',
            // Доп поля ы
            Indication: '',

            descriptionRow: 1,
            descriptionArray: [[]],
            inclusionCriteriaRow: 1,
            inclusionCriteriaArray: [[]],
            exclusionCriteriaRow: 1,
            exclusionCriteriaArray: [[]],
            associatedInformationRow: 1,
            associatedInformationArray: [[]],
            listOfSamplesRow: 1,
            listOfSamplesArray: [[]],

            timelinesArray: [[]],

            newlines_1: 1,
            newlines_2: 1,
            newlines_id: 0,

            quantity: 1,
            sumPrice: 0,
            totalPrice: 0,

            associated_information1: '',
            associated_information2: '',
            // list_of_samples: '',

            incoterms: '',

            shippingInfo: '',

            offerArray: [], // {index, species, hbs , description , quantity , sumPrice, totalPrice}


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
        }
    },

    props: ['proj_id', 'script_id', 'company_users', 'currency'],

    methods: {
        getOffer() {
            return new Promise(resolve =>{
                    this.$parent.getJson(`../../offerApe/getbyId/?proj_id=${this.proj_id}`)
                        .then(data => {
                            this.offer = data.result[0];
                            this.offer_date = data.result[0].date_offer;
                            this.validd_date = data.result[0].date_valid;
                            this.user = data.result[0].user_id;
                            this.turnaround = data.result[0].turnaround;
                            this.conditions = data.result[0].storage;
                            this.incoterms = data.result[0].incoterms;
                            this.shippingInfo = data.result[0].shipping;
                            this.courierSelect = data.result[0].courier_id;
                            this.loadShopiping();
                            this.estimated = data.result[0].estimated;
                            this.shipping_fee = data.result[0].shipping_id;
                            this.shipping_fee_value = data.result[0].count_shipping;
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
            });
        },
        getOfferItems(offer_id) {
            return new Promise(resolve => {
                this.$parent.getJson(`../../offerApeItem/getByOfferId/?offer_id=${offer_id}`)
                    .then(data => {
                        resolve(data);
                    });
            });
        },
        getTimelines(offer_id) {
            return new Promise(resolve => {
                this.$parent.getJson(`../../offerApeItemTimeline/getByOfferItemId/?offer_ape_item_id=${offer_id}`)
                    .then(timelines_res => {
                        resolve(timelines_res.result);
                        // let sumPrice = timelines.reduce((acc, curVal) => {
                        //     return acc + +curVal.price;
                        // }, 0);
                    });
            });
        },
        getInclusionCriteria(offer_id) {
            return new Promise(resolve => {
                this.$parent.getJson(`../../offerApeItemInclusionCriteria/getByOfferItemId/?offer_ape_item_id=${offer_id}`)
                    .then(inclusion_criterias_res => {
                        resolve(inclusion_criterias_res.result);
                    });
            });
        },
        getExclusionCriteria(offer_id) {
            return new Promise(resolve => {
                this.$parent.getJson(`../../offerApeItemExclusionCriteria/getByOfferItemId/?offer_ape_item_id=${offer_id}`)
                    .then(exclusion_criterias_res => {
                        resolve(exclusion_criterias_res.result)
                    });
            });
        },
        getassociatedInformation(offer_id) {
            return new Promise(resolve => {
                this.$parent.getJson(`../../offerApeItemAssociatedInformation/getByOfferItemId/?offer_ape_item_id=${offer_id}`)
                    .then(associated_information_res => {
                        resolve(associated_information_res.result);
                    });
            });
        },
        getListOfSamples(offer_id) {
            return new Promise(resolve => {
                this.$parent.getJson(`../../offerApeItemListOfSamples/getByOfferItemId/?offer_ape_item_id=${offer_id}`)
                    .then(list_of_samples_res => {
                        resolve(list_of_samples_res.result);
                    });
            });
        },


        async importOffer() {
            this.offer = await this.getOffer();
            this.$parent.getJson(`../../offerOption/getByOfferId/?offer_id=${this.offer.id}`)
                .then(data => {
                    this.newlines_1 = data.result[0] ? data.result[0].newlines_1 : 1;
                    this.newlines_2 = data.result[0] ? data.result[0].newlines_2 : 1;
                    this.newlines_id = data.result[0] ? data.result[0].id : 0;
                    console.log(data);
                });
            let data = await this.getOfferItems(this.offer.id);
            this.descriptionArray = data ? data.result : [[]];
            this.descriptionRow = this.descriptionArray.length;
            if (data.result.length) {
                for (let index in this.descriptionArray) {
                    let offer_item_id = this.descriptionArray[index].id;
                    let timelinesArray = await this.getTimelines(offer_item_id);
                    let sumPrice = timelinesArray.reduce((acc, curVal) => {
                        return acc + +curVal.price;
                    }, 0);
                    let inclusionCriteriaArray = await this.getInclusionCriteria(offer_item_id);
                    let exclusionCriteriaArray = await this.getExclusionCriteria(offer_item_id);
                    let associatedInformationArray = await this.getassociatedInformation(offer_item_id);
                    let listOfSamplesArray = await this.getListOfSamples(offer_item_id);
                    let description = `<b>Species:</b><br>${this.descriptionArray[index].species}<br>`;
                    for (let i = 0; i < timelinesArray.length; i++) {
                        let factTimline = '';
                        let nameTimleine = 'Timeline ' + (i + 1);
                        if (this.descriptionRow === 1) {
                            nameTimleine = 'Timeline';
                        }
                        let timeline = timelinesArray[i].timeline;
                        if (['', null].indexOf(timeline) !== -1) {
                            if (i === 1) {
                                factTimline = factTimline = `<b>${nameTimleine}:</b><br>`
                            } else {
                                factTimline = '';
                            }
                        } else {
                            factTimline = `<b>${nameTimleine}:</b><br>${timeline}<br>`
                        }
                        let samples = timelinesArray[i].samples;
                        let type_of_collection = timelinesArray[i].type_of_collection;
                        let processing_details = timelinesArray[i].processing_details;
                        description += `
                             ${factTimline}
                             <b>Samples:</b><br>
                             ${samples}<br>
                             <b>Type of collection:</b><br>
                             ${type_of_collection}<br>
                             ${processing_details ? `<b>Processing details:</b><br>${processing_details}<br>` : ''}`;
                    }
                    let InclusionCriteriaHeader = false;
                    for (let i = 0; i < inclusionCriteriaArray.length; i++) {
                        let inclusion_criteria = inclusionCriteriaArray[i];
                        if (['', null].indexOf(inclusion_criteria) === -1) {
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
                        if (['', null].indexOf(exclusion_criteria) === -1) {
                            if (!ExclusionCriteriaHeader) {
                                description += `<b>Exclusion criteria:</b><br>`
                                ExclusionCriteriaHeader = true;
                            }
                            description += `${exclusion_criteria.exclusion_criteria}<br>`;
                        }
                    }
                    let associatedInformationHeader = false;
                    for (let i = 0; i < associatedInformationArray.length; i++) {
                        let associated_information = associatedInformationArray[i];
                        if (['', null].indexOf(associated_information) === -1) {
                            if (!associatedInformationHeader) {
                                description += `<b>Associated information:</b><br>`
                                associatedInformationHeader = true;
                            }
                            description += `${associated_information.associated_information}<br>`;
                        }
                    }
                    let ListOfSamplesHeader = false;
                    for (let i = 0; i < listOfSamplesArray.length; i++) {
                        let list_of_samples = listOfSamplesArray[i];
                        if (['', null].indexOf(list_of_samples) === -1) {
                            if (!ListOfSamplesHeader) {
                                description += `<b>List of samples:</b><br>`
                                ListOfSamplesHeader = true;
                            }
                            description += `${list_of_samples.list_of_samples}<br>`;
                        }
                    }
                    let obj = {
                        proj_id: this.proj_id,
                        index: this.descriptionArray[index].id,
                        species: this.descriptionArray[index].species,
                        hbs: this.descriptionArray[index].hbs_type,
                        quantity: this.descriptionArray[index].quantity,
                        timelines: timelinesArray,
                        inclusion_criterias: inclusionCriteriaArray,
                        exclusion_criterias: exclusionCriteriaArray,
                        associated_information: associatedInformationArray,
                        list_of_samples: listOfSamplesArray,
                        sumPrice: sumPrice,
                        totalPrice: sumPrice * this.descriptionArray[index].quantity,
                        description: description,
                        timelinesArray: timelinesArray,

                    }
                    this.offerArray.push(obj);
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
                                this.export_permit_price = 330;
                                // this.export_permit_price = el.price_usd;
                            } else {
                                this.export_permit_price = 330;
                                // this.export_permit_price = el.price_euro;
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

        },

        checkCountRow(action) {
            if (action == '+') {
                this.descriptionRow++;
                this.descriptionArray.push([]);
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
        checkCountAssociatedInformationRow(action) {
            if (action == '+') {
                this.associatedInformationRow++;
                this.associatedInformationArray.push([]);
            } else {
                if (this.associatedInformationRow > 0) {
                    this.associatedInformationRow--;
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
            let offer_ape_item_id = this.offer_item_id;
            let TimelinesArr = [], InclusionArr = [], ExclusionArr = [], ClinicalArr = [], ListOfSamplesArr = [];
            this.description = `<b>Indication:</b><br>${this.species}<br>`;

            for (let i = 0; i < this.descriptionArray.length; i++) {
                let factTimline = '';
                let nameTimleine = 'Timeline ' + i + 1;
                if (this.descriptionRow === 1) {
                    nameTimleine = 'Timeline';
                }
                let timeline = document.getElementById('timelineApe_' + i).value;
                if (timeline === '') {
                    if (i === 1) {
                        factTimline = factTimline = `<b>${nameTimleine}:</b><br>`
                    } else {
                        factTimline = '';
                    }
                } else {
                    factTimline = `<b>${nameTimleine}:</b><br>
                                 ${timeline}<br>`
                }
                let samples = document.getElementById('samplesApe_' + i).value;
                let type_of_collection = document.getElementById('type_of_collectionApe_' + i).value;
                let processing_details = document.getElementById('processing_detailsApe_' + i).value;
                let price = document.getElementById('priceApe_' + i).value;
                let id = document.getElementById('timelineidApe_' + i).value;
                this.sumPrice = +this.sumPrice + price * 1;
                this.description += `
                                 ${factTimline}
                                 <b>Samples:</b><br>
                                 ${samples}<br>
                                 <b>Type of collection:</b><br>
                                 ${type_of_collection}<br>
                                 ${processing_details ? `<b>Processing details:</b><br>${processing_details}<br>` : ''}`;
                let TimelineData = {offer_ape_item_id, timeline, samples, type_of_collection, processing_details, price};
                if (id) {
                    TimelineData.id = id;
                }
                let TimelineIndex = TimelinesArr.length;
                TimelinesArr.push(TimelineData);
                this.$parent.putJson(`../../../offerApeItemTimeline/save`, TimelineData)
                    .then(response => {
                        toastr.success('Сохранение таймлайна... ', response);
                        if (!id){
                            $(`#timelineidApe_${i}`).val(response);
                            TimelinesArr[TimelineIndex].id = response;
                        }
                    });
            }
            let InclusionCriteriaHeader = false;
            for (let i = 0; i < this.inclusionCriteriaArray.length; i++) {
                if (document.getElementById('inclusion_criteriaApe_' + i) !== null) {
                    let inclusion_criteria = document.getElementById('inclusion_criteriaApe_' + i).value;
                    if (inclusion_criteria !== '') {
                        if (!InclusionCriteriaHeader) {
                            this.description += `<b>Inclusion criteria:</b><br>`
                            InclusionCriteriaHeader = true;
                        }
                        this.description += `${inclusion_criteria}<br>`;
                        let id = document.getElementById('inclusionidApe_' + i).value;
                        let InclusionCriteriaData = {offer_ape_item_id, inclusion_criteria};
                        if (id) {
                            InclusionCriteriaData.id = id;
                        }
                        let InclusionIndex = InclusionArr.length;
                        InclusionArr.push(InclusionCriteriaData);
                        this.$parent.putJson(`../../../offerApeItemInclusionCriteria/save`, InclusionCriteriaData)
                            .then(response => {
                                if (!id) {
                                    $(`#inclusionidApe_${i}`).val(response);
                                    InclusionArr[InclusionIndex].id = response;
                                }
                            });
                    }
                }
            }
            let ExclusionCriteriaHeader = false;
            for (let i = 0; i < this.exclusionCriteriaArray.length; i++) {
                if (document.getElementById('exclusion_criteriaApe_' + i) !== null) {
                    let exclusion_criteria = document.getElementById('exclusion_criteriaApe_' + i).value;
                    if (exclusion_criteria !== '') {
                        if (!ExclusionCriteriaHeader) {
                            this.description += `<b>Exclusion criteria:</b><br>`
                            ExclusionCriteriaHeader = true;
                        }
                        this.description += `${exclusion_criteria}<br>`;
                        let id = document.getElementById('exclusionidApe_' + i).value;
                        let ExclusionCriteriaData = {offer_ape_item_id, exclusion_criteria};
                        if (id) {
                            ExclusionCriteriaData.id = id;
                        }
                        let ExclusionIndex = ExclusionArr.length;
                        ExclusionArr.push(ExclusionCriteriaData);
                        this.$parent.putJson(`../../../offerApeItemExclusionCriteria/save`, ExclusionCriteriaData)
                            .then(response => {
                                if (!id) {
                                    $(`#exclusionidApe_${i}`).val(response);
                                    ExclusionArr[ExclusionIndex].id = response;
                                }
                            });
                    }
                }
            }
            let associatedInformationHeader = false;
            for (let i = 0; i < this.associatedInformationArray.length; i++) {
                if (document.getElementById('associated_informationApe_' + i) !== null) {
                    let associated_information = document.getElementById('associated_informationApe_' + i).value;
                    if (associated_information !== '') {
                        if (!associatedInformationHeader) {
                            this.description += `<b>Associated information:</b><br>`
                            associatedInformationHeader = true;
                        }
                        this.description += `${associated_information}<br>`;
                        let id = document.getElementById('clinicalidApe_' + i).value;
                        let associatedInformationData = {offer_ape_item_id, associated_information};
                        if (id) {
                            associatedInformationData.id = id;
                        }
                        let ClinicalIndex = ClinicalArr.length;
                        ClinicalArr.push(associatedInformationData);
                        this.$parent.putJson(`../../../offerApeItemAssociatedInformation/save`, associatedInformationData)
                            .then(response => {
                                if (!id) {
                                    $(`#clinicalidApe_${i}`).val(response);
                                    ClinicalArr[ClinicalIndex].id = response;
                                }
                            });
                    }
                }
            }
            let ListOfSamplesHeader = false;
            for (let i = 0; i < this.listOfSamplesArray.length; i++) {
                if (document.getElementById('list_of_samplesApe_' + i) !== null) {
                    let list_of_samples = document.getElementById('list_of_samplesApe_' + i).value;
                    if (list_of_samples !== '') {
                        if (!ListOfSamplesHeader) {
                            this.description += `<b>List of samples:</b><br>`
                            ListOfSamplesHeader = true;
                        }
                        this.description += `${list_of_samples}<br>`;
                        let id = document.getElementById('list_of_samples_idApe_' + i).value;
                        let ListOfSamplesData = {offer_ape_item_id, list_of_samples};
                        if (id) {
                            ListOfSamplesData.id = id;
                        }
                        let ListOfSamplesIndex = ListOfSamplesArr.length;
                        ListOfSamplesArr.push(ListOfSamplesData);
                        this.$parent.putJson(`../../../offerApeItemListOfSamples/save`, ListOfSamplesData)
                            .then(response => {
                                if (!id) {
                                    $(`#list_of_samples_idApe_${i}`).val(response);
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
            obj.proj_id = this.proj_id
            obj.index = this.offer_item_id
            obj.species = this.species
            obj.hbs = this.hbs
            obj.quantity = +this.quantity
            obj.sumPrice = +this.sumPrice
            obj.totalPrice = +this.sumPrice * this.quantity
            obj.description = this.description
            obj.timelines = TimelinesArr;
            obj.inclusion_criterias = InclusionArr;
            obj.exclusion_criterias = ExclusionArr;
            obj.associated_information = ClinicalArr;
            obj.list_of_samples = ListOfSamplesArr;
            // }
            let data = {
                id: this.offer_item_id,
                offer_id: this.offer.id,
                species: this.species,
                hbs_type: this.hbs,
                quantity: this.quantity
            }

            this.$parent.putJson(`../../../offerApeItem/save`, data)
                .then(response => {
                    toastr.success('Создание ряда ... ', response);
                    this.offer_item_id = response;
                });

            if (keyToReplace === -1) {
                this.offerArray.push(obj);
            }

            this.clearArray();
            $('#iconModalApe').modal('hide');
            this.totalSum();
        },

        deleteRow() {
            let id = this.offer_item_id;
            $('#iconModalApe').modal('hide');
            this.$parent.putJson(`../../../offerApeItem/delete`, {id})
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
            this.$parent.putJson(`../../../offerApeItem/save`, data)
                .then(response => {
                    toastr.success('Создание ряда ... ', response);
                    this.offer_item_id = response;
                    $(".timelineidApe, .inclusionidApe, .exclusionidApe, .clinicalidApe, .list_of_samples_idApe").val('')
                    for (let i = 0; i < this.descriptionArray.length; i++) {
                        document.getElementById('timelineidApe_' + i).value = '';
                    }
                    for (let i = 0; i < this.inclusionCriteriaArray.length; i++) {
                        document.getElementById('inclusionidApe_' + i).value = '';
                    }
                    for (let i = 1; i < this.exclusionCriteriaArray.length; i++) {
                        document.getElementById('exclusionidApe_' + i).value = '';
                    }
                    for (let i = 1; i < this.associatedInformationArray.length; i++) {
                        document.getElementById('clinicalidApe_' + i).value = '';
                    }
                    for (let i = 1; i < this.listOfSamplesArray.length; i++) {
                        document.getElementById('list_of_samples_idApe_' + i).value = '';
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
            this.species = '';
            this.hbs = '';
            this.quantity = 1;
            this.sumPrice = 0;
            this.description = 0;
            this.descriptionArray = [[]];
            this.inclusionCriteriaArray = [[]];
            this.exclusionCriteriaArray = [[]];
            this.associatedInformationArray = [[]];
            this.listOfSamplesArray = [[]];
            this.descriptionRow = 1;
            this.inclusionCriteriaRow = 1;
            this.exclusionCriteriaRow = 1;
            this.associatedInformationRow = 1;
            this.listOfSamplesRow = 1;
        },

        addInitData() {
            this.associatedInformationArray = [
                {associated_information: 'Age, gender'},
                {associated_information: 'treatment naive'},
                {associated_information: 'Date of collection'},
                {associated_information: 'Pathogens negativity testing'},
            ];
            this.associatedInformationRow = 4;
        },
        saveDescription() {
            let el = this.offerArray.find(el => {
                if (el.index = this.editDescriptionId) {
                    return true
                }
            })

            if (el !== undefined) {
                el.description = tinymce.get('eidtDescription').getContent();
                $('#iconModalApeEdit').modal('hide');
            }
        },

        openModal() {
            $('#iconModalApe').modal('show');
        },

        addOfferIttem() {
            this.clearArray();
            this.addInitData();
            $("#clone_btn").hide();
            this.openModal();
            let data = {
                offer_id: this.offer.id,
            }

            this.$parent.putJson(`../../../offerApeItem/save`, data)
                .then(response => {
                    toastr.success('Создание ряда ... ', response);
                    this.offer_item_id = response;
                });
        },

        openEdit(item) {
            $('#iconModalApeEdit').modal('show');
            this.editDescriptionId = item.index + 1;
            tinymce.get("eidtDescription").setContent(item.description);
        },

        openEditOfferItem(item) {
            $("#clone_btn").show();
            this.offer_item_id = item.index;
            this.species = item.species;
            this.hbs = item.hbs;
            this.quantity = item.quantity;
            this.list_of_samples = item.list_of_samples;
            this.descriptionArray = item.timelines;
            this.inclusionCriteriaArray = item.inclusion_criterias;
            this.exclusionCriteriaArray = item.exclusion_criterias;
            this.associatedInformationArray = item.associated_information;
            this.listOfSamplesArray = item.list_of_samples;
            console.log(this.descriptionArray)
            // this.clearArray();
            this.openModal();
        },

        saveOffer() {
            // Основой набор данных
            // TODO добавить фиксации цены досавки на момент сохранения
            let data = {
                id: this.offer.id,
                date_offer: this.offer_date,
                scripts_staff_id: this.offer.scripts_staff_id,
                date_valid: this.validd_date,
                user_id: this.user,
                turnaround: this.turnaround,
                storage: this.conditions,

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

            this.$parent.putJson(`../../../offerApe/save`, data)
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
                scripts_staff_id: this.offer.scripts_staff_id,
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
                table_body: document.getElementById('ape_table_body').innerHTML,
                newlines_1: this.newlines_1,
                newlines_2: this.newlines_2
            }

            this.$parent.putJson(`../../../offer/generateApePdf`, data)
                .then(response => {
                    location.href = "../../../orders/info/?idFR=" + this.proj_id;
                })


        },

        // saveTimline() {
        //
        // },
        deleteTimeline(index) {
            this.$parent.putJson(`../../../offerApeItemTimeline/delete`, {id: this.descriptionArray[index].id});
            this.descriptionArray.splice(index, 1);
            this.descriptionRow--;
        },
        deleteInclusionCriteria(index) {
            this.$parent.putJson(`../../../offerApeItemInclusionCriteria/delete`, {id: this.inclusionCriteriaArray[index].id});
            this.inclusionCriteriaArray.splice(index, 1);
            this.inclusionCriteriaRow--;
        },
        deleteExclusionCriteria(index) {
            this.$parent.putJson(`../../../offerApeItemExclusionCriteria/delete`, {id: this.exclusionCriteriaArray[index].id});
            this.exclusionCriteriaArray.splice(index, 1);
            this.exclusionCriteriaRow--;
        },
        deleteassociatedInformation(index) {
            this.$parent.putJson(`../../../offerApeItemAssociatedInformation/delete`, {id: this.associatedInformationArray[index].id});
            this.associatedInformationArray.splice(index, 1);
            this.associatedInformationRow--;
        },
        deleteListOfSamples(index) {
            this.$parent.putJson(`../../../offerApeItemListOfSamples/delete`, {id: this.listOfSamplesArray[index].id});
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
        }
    },

    mounted() {
        this.load();
        // this.socket.on('reloadDASHBORD', (proj_id) => {
        //     this.load();
        // })

        this.loadShopiping();
        setTimeout(() => {
            this.totalShipping();
        }, 3000)

    },

    template: `
<div> 

<div class="modal fade text-left" id="iconModalApe"  role="dialog" aria-labelledby="myModalLabel4" 
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
                                        <label for="species">Species</label>
<!--                                        <input type="text" v-model="species" class="form-control">-->
                                        <select id="species" v-model="species" class="form-control">
                                            <option value="Rhesus macaque (Macaca mulatta)">Rhesus macaque (Macaca mulatta)</option>
                                            <option value="Cynomolgous macaque (Macaca fascicularis)">Cynomolgous macaque (Macaca fascicularis)</option>
                                            <option value="Hamadryas baboon (Papio hamadryas)">Hamadryas baboon (Papio hamadryas)</option>
                                            <option value="Anubis baboon (Papio anubis)">Anubis baboon (Papio anubis)</option>
                                            <option value="Green monkey (Chloro cebusaethiops)">Green monkey (Chloro cebusaethiops)</option>                                      
                                            <option value="Non-human primates">Non-human primates</option>                                      
                                        </select>
                                    </div> 
                                    <div class="col-4">
                                        <label for="hbs">Samples Type</label>
                                        <input type="text" v-model="hbs" class="form-control">
                                    </div>
                                      <div class="col-4">
                                         <label for="Indication">Quantity</label>
                                         <input type="number" v-model="quantity" class="form-control" @input="sumTotal()">
                                    </div>                                      
                                </div>
                                <br>
                                    <h4>Description</h4>
                                   <div class="col-2">
                                         <button class="btn btn-dark mt-3" @click="checkCountRow('+')">+</button>
                                      <!--   <button class="btn btn-danger  mt-3" @click="checkCountRow()">-</button> -->
                                         </div>
<!--                                 <div class="row" v-for="index in descriptionRow">-->
                                 <div class="row" v-for="(item, index) in descriptionArray">
                                    <input type="hidden" v-model="item.id" :id="'timelineidApe_'+ index" class="timelineidApe">
                                     <div class="col-2">
                                         <label>Timeline</label>
                                         <input type="text" class="form-control"
                                            :id="'timelineApe_'+ index"
                                            v-model="item.timeline"> 
                                     </div> 
                                       <div class="col-2">
                                         <label>Samples</label>
                                         <input type="text" class="form-control"
                                            :id="'samplesApe_' + index"
                                            v-model="item.samples"> 
                                       </div>  
                                       <div class="col-2">
                                         <label>Type of collection</label>
                                         <input type="text" class="form-control"
                                            :id="'type_of_collectionApe_' + index"
                                            v-model="item.type_of_collection">
                                       </div>  
                                       <div class="col-2">
                                         <label for="Indication">Processing details:</label>
                                         <input type="text" class="form-control"
                                            :id="'processing_detailsApe_' + index"
                                            v-model="item.processing_details">
                                         </div>
                                         <div class="col-2">
                                            <label for="Indication">Price {{ currency }}</label>
                                            <input type="number" class="form-control sumTotal"
                                                :id="'priceApe_' + index"
                                                @input="sumTotal()"
                                                v-model="item.price"
                                                >
                                         </div>
                                         
<!--                                         <div class="col-1">-->
<!--                                            <label for="Indication">save</label>-->
<!--                                            <input type="button" class="form-control btn btn-success" value="save" @click="saveTimline()">-->
<!--                                         </div>-->
                                          
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
                                            <input type="hidden"  class="inclusionidApe"
                                                :id="'inclusionidApe_' + inclusion_index"
                                                v-model="inclusion_item.id">
                                            <div class="row">
                                                <div class="col-9">
                                                    <input type="text" class="form-control"
                                                        :id="'inclusion_criteriaApe_' + inclusion_index"
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
                                            <input type="hidden"  class="exclusionidApe"
                                                :id="'exclusionidApe_' + exclusion_index"
                                                v-model="exclusion_item.id">
                                            <div class="row">
                                                <div class="col-9">
                                                     <input type="text" class="form-control"
                                                        :id="'exclusion_criteriaApe_' + exclusion_index"
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
                                         <label>Associated information:</label><br>
                                         <button class="btn btn-dark mt-3"
                                            @click="checkCountAssociatedInformationRow('+')">+</button>
                                         <div v-for="(clinical_item, clinical_index) in associatedInformationArray">
                                            <input type="hidden"  class="clinicalidApe"
                                                :id="'clinicalidApe_' + clinical_index"
                                                v-model="clinical_item.id">
                                            <div class="row">
                                                <div class="col-9">
                                                    <input type="text" class="form-control"
                                                        :id="'associated_informationApe_' + clinical_index"
                                                        v-model="clinical_item.associated_information">
                                                </div>
                                                <div class="col-3">
                                                    <input type="button" value="-" class="form-control btn btn-danger"
                                                        @click="deleteassociatedInformation(clinical_index)">
                                                </div>
                                            </div>
                                        </div>
                                  </div> 
                                   <div class="col-3">
                                         <label>List of samples:</label><br>
                                        <button class="btn btn-dark mt-3"
                                            @click="checkCountListOfSamplesRow('+')">+</button>
                                         <div v-for="(list_of_samples_item, list_of_samples_index) in listOfSamplesArray">
                                            <input type="hidden" class="list_of_samples_idApe"
                                                :id="'list_of_samples_idApe_' + list_of_samples_index"
                                                v-model="list_of_samples_item.id">
                                            <div class="row">
                                                <div class="col-9">
                                                    <input type="text" class="form-control"
                                                        :id="'list_of_samplesApe_' + list_of_samples_index"
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
            <button type="button" class="btn btn-outline-blue" @click="cloneRow()" id="clone_btn">Clone</button>
            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn  btn-success"  @click="addRow()">Save</button>
               
            </div>
        </div>
    </div>
        </div>
                  
          </div>        
<div class="modal fade text-left" id="iconModalApeEdit"  role="dialog" aria-labelledby="myModalLabel4" 
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
                                        <input type="date" v-model="offer_date" class="form-control">
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
                                        <td width="12%"><b>Attention to</b></td>
                                        <td style="text-align: left;">
                                            <select  v-model="offer.scripts_staff_id" class="form-control">
                                                <option value="0">Веберите менеджера</option>
                                                <option  v-for="item in compamyStaff" :value="item.id">{{ item.name }} {{ item.position }}</option>
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
                                        <td style="text-align: left;"><input type="date" v-model="validd_date" class="form-control"></td>
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
                               
                                   <div id="ape_table_body" style="width: 100%;">
                                  <table width="100%" class="offer__table" cellpadding="0" cellspacing="0" >
                                    <thead  style="">
                                    <tr class="xl90"  style="page-break-after:avoid">
                                        <th>#</th>
                                        <th>Species</th>
                                        <th>Samples Type</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Price({{currency}})</th>
                                        <th>Total Price({{currency}})</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="xl91" v-for="(item, index) in offerArray">
                                        <td style="text-align: center; padding: 10px; vertical-align: middle;" valign="center" @dblclick="openEditOfferItem(item)">{{ index + 1 }}</td>
                                        <td style="text-align: center; padding: 10px; vertical-align: middle;" valign="center" align="cen">{{ item.species }}</td>
                                        <td style="text-align: center; padding: 10px; vertical-align: middle;" valign="center">{{ item.hbs }}</td>
                                        <td style="text-align: left; padding: 10px; vertical-align: middle;" valign="center" v-html="item.description" @dblclick="openEdit(item)"></td>              
                                        <td style="text-align: center; vertical-align: middle;" valign="center">{{ item.quantity }}</td>
                                        <td style="text-align: center; vertical-align: middle;" valign="center">{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(item.sumPrice)}}</td>
                                        <td style="text-align: center; vertical-align: middle;" valign="center">{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: currency }).format(item.totalPrice)}}</td>
                                     
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
                                        <td style="text-align: left; padding-left: 5px"><b>CITES Export permit </b></td>
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



