Vue.component('questions', {
    data() {
        return {
            questionsText: '',
            questions: [],
        }
    },


    props: ['uid'],
    methods: {


        add() {
            if (this.questionsText.length < 2) {
                toastr.error('Заполните поле наименование вопроса')
            } else {
                let data = {
                    'template_id': this.uid,
                    'question': this.questionsText,
                    'users_group': 'all'
                };
                this.save(data);
            }
        },

        save(data) {
            this.$parent.$parent.putJson(`/SiteCapabilityQuestion/save`, data)
                .then(datas => {
                    if (data.id === undefined) {
                        data.id = datas.result;
                        this.questions.push(data);
                        this.questionsText = '';
                        toastr.success('ok');
                    }
                })
        },

        dell(question) {
            this.$parent.$parent.deleteJson(`/SiteCapabilityQuestion/dell`, {id: question.id})
                .then(data => {
                    if (data.result == 0) {
                        this.questions.splice(this.questions.indexOf(question), 1);
                        toastr.success('Удалено');
                    }
                })

        },

        load() {
            this.$parent.$parent.getJson(`/SiteCapabilityQuestion/GetAll/?template_id=${this.uid}`)
                .then(data => {
                    let i = 0;
                    for (let key in data) {
                        this.questions.push(data[key]);
                    }
                });
        }

    },


    mounted() {
        this.load();

    },

    // language=HTML
    template: `
        <section>
            <div class="row">
                <div class="col-md-12">
                    <h2>Доп. вопросы </h2>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <input type="text" class="form-control" v-model="questionsText" placeholder="Наименование вопроса">
                </div>

                <div class="col-md-4">
                    <button class="btn btn-info btn-block" @click="add()">Добавить вопрос</button>
                </div>

            </div>
            
            
            <table class="table table-bordered ">
                <thead>
                    <td>Наиманование</td>
                    <td>Действие</td>
                
                </thead>
                <tbody>
                <tr v-for="item in questions">
                    <td>{{ item.question }}</td>
                    <td><button  class="btn btn-sm btn-danger" @click="dell(item)">Удалить</button></td>
                </tr>
                </tbody>
            </table>
            

        </section>
    `

});

