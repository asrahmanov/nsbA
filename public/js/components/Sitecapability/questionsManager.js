Vue.component('questions-manager', {
    data() {
        return {
            questions: [], // Вопросы
            questionsAnswer: [], // Ответы
        }
    },

    props: ['uid', 'manager_site_capability_id', 'site_id', 'user_id', 'admin'],

    methods: {
        finsAnswer(id) {

            let el = this.questionsAnswer.find(el => {
                if (el.question_id == id) {
                    return el
                }
            });

            if (el === undefined) {
                return ''
            } else {
                return el.question;
            }
        },
        add(x) {
            let data = {
                'template_id': this.uid,
                'question': $(`#input_${x}`).val(),
                'question_id': x,
                'manager_site_capability_id': this.manager_site_capability_id,
                'site_id': this.site_id,
                'user_id': this.user_id,
            };

            let index = this.questionsAnswer.findIndex(el => {
                if (el.site_id == data.site_id
                    && el.manager_site_capability_id == data.manager_site_capability_id
                    && el.question_id == data.question_id
                ) {
                    return el;
                }
            });


            if (index === -1) {

            } else {

                data.id = this.questionsAnswer[index].id;
                this.$set(this.questionsAnswer, index, data);
            }
            console.log(data);
            this.save(data);
        },


        save(data) {
            this.$parent.$parent.putJson(`/ManagerSiteCapabilityQuestion/save`, data)
                .then(datas => {
                    if (data.id === undefined) {
                        data.id = datas.result;
                        this.questionsAnswer.push(data);
                        this.questionsText = '';
                        toastr.success('ok');
                    }
                })
        },

        dell(question) {
            this.$parent.$parent.deleteJson(`/ManagerSiteCapabilityQuestion/dell`, {id: question.id})
                .then(data => {
                    if (data.result == 0) {
                        this.questions.splice(this.questions.indexOf(question), 1);
                        toastr.success('Удалено');
                    }
                })
        },

        loadQuestions() {
            this.$parent.$parent.getJson(`/SiteCapabilityQuestion/GetAll/?template_id=${this.uid}`)
                .then(data => {
                    let i = 0;
                    for (let key in data) {
                        this.questions.push(data[key]);
                    }
                });
        },

        loadAnswer() {
            if (!this.admin) {
                this.$parent.$parent.getJson(`/ManagerSiteCapabilityQuestion/GetAll/?template_id=${this.uid}
                &manager_site_capability_id=${this.manager_site_capability_id}
                &site_id=${this.site_id}
            `).then(data => {
                        let i = 0;
                        for (let key in data) {
                            this.questionsAnswer.push(data[key]);
                        }
                    });
            } else {
                this.$parent.$parent.getJson(`/ManagerSiteCapabilityQuestion/GetAllAdmin/?template_id=${this.uid}
                &site_id=${this.site_id}
                &user_id=${this.user_id}
            `).then(data => {
                    let i = 0;
                    for (let key in data) {
                        this.questionsAnswer.push(data[key]);
                    }
                });
            }

        }
    },


    mounted() {
        this.loadQuestions();
        this.loadAnswer();
    }
    ,

// language=HTML
    template: `
        <section>
            <div class="row" v-if="admin ==  'false'">
                <div class="col-md-12">
                    <h2 >Доп. вопросы </h2>
                </div>
            </div>


            <table class="table table-bordered ">
                <thead>
                <td>Вопрос</td>
                <td>Ответ</td>

                </thead>
                <tbody>
                <tr v-for="item in questions">
                    <td>{{ item.question }}</td>
                    <td>
                        <div class="input-group" v-if="admin ==  'false'">
                            <input type="text" class="form-control" :value="finsAnswer(item.id)"
                                   placeholder="Введите ответ" :id="'input_' + item.id"
                                   @blur="add(item.id)">

                            <div class="input-group-append">
                          <span class="input-group-btn" id="button-addon2">
                            <button class="btn btn-info" type="button">
                              <i class="ft-save"></i>
                            </button>
                          </span>
                            </div>
                        </div>
                        <span v-else> {{ finsAnswer(item.id) }}</span>
                    </td>
                </tr>
                </tbody>
            </table>


        </section>
    `

})
;

