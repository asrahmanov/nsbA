Vue.component('score', {
    data() {
        return {
            deadLine_dead: "",
            create_date: "",
            button_zadasha_prinita: true,
            button_zadasha_ok: true,
            button_zadasha_dop_vremya: true,
            button_neviznogno: true
        }

    },
    props: ['ticket_id', 'proj_id'],

    methods: {



        closeQuote() {
            if (window.confirm("Отказаться от квотирования ?")) {
                this.$parent.getJson(`../../worksheets/Close/?proj_id=${this.proj_id}`)
                    .then(data => {

                    });
            }
        },

        getInfobyTicketId() {
            this.$parent.getJson(`../../newTickets/getOne/?ticket_id=${this.ticket_id}`)
                .then(data => {
                    this.deadLine_dead = data.deadline;
                    this.create_date = data.created_at;
                });
        },


        checkScore() {
            this.$parent.getJson(`../../ticketsScore/getInfobyTicketId/?ticket_id=${this.ticket_id}`)
                .then(data => {
                    for (let key in data) {
                        console.log('---->',data[key].action);
                        if (data[key].action == 'Задача принята') {
                            this.button_zadasha_prinita = false
                        }
                        if (data[key].action == 'Задача выполнена') {
                            // Если задача выполнена скрываем все кнопки
                            this.button_zadasha_ok = false;
                            this.button_zadasha_prinita = false;
                            this.button_zadasha_dop_vremya = false;
                            this.button_neviznogno = false
                        }

                        if (data[key].action.indexOf('Для выполнения требуется больше времени') != -1) {
                            this.button_zadasha_dop_vremya = false
                        }

                        if (data[key].action == 'Выполнение невозможно') {
                            // Если  Выполнение невозможно скрываем все кнопки
                            this.button_zadasha_ok = false;
                            this.button_zadasha_prinita = false;
                            this.button_zadasha_dop_vremya = false;
                            this.button_neviznogno = false
                        }
                    }

                    // Если задача не была принята , принимаем задачу автоматически
                     setTimeout(() => {
                         if(this.button_zadasha_prinita){
                             this.action('Задача принята');
                         }
                     }, 1000)
                });


        },

        checkCountDay(date) {
            let date1 = new Date(date);
            let date2 = new Date();
            let diff = new Date(date2.getTime() - date1.getTime());
            return (diff.getUTCDate() - 1)
        },

        checkCountDayDeadline(date) {
            let date1 = new Date(date);
            let date2 = new Date();
            let diff = new Date(date1.getTime() - date2.getTime());
            return (diff.getUTCDate() - 1)
        },

        sendMessage(messege) {
            let data = {
                ticket_id: this.ticket_id,
                message: messege
            }


                $.post({url: '/newTicketChats/save/', data}).done(() => {
                    if(messege == 'Задача принята') {
                        location.reload()
                    } else {
                        location.href = '/newTickets/index/'
                    }
                })




        },



        action(name) {
            let day;

            switch (name) {
                case 'Задача принята' :
                    day = this.checkCountDay(this.create_date);
                    // Если прошло менне 24 часов после поставновки задачи
                    if (day <= 1) {
                        this.save(name, 10, 'Ответил в первые 24 часа + 10 Баллов');
                    } else {
                        this.save(name, 0, 'Не ответил в первые 24 часа 0  Баллов');
                    }
                    this.button_zadasha_prinita = false
                    this.sendMessage(name);
                    break;


                case 'Задача выполнена' :
                    day = this.checkCountDayDeadline(this.deadLine_dead);
                    //  Уложился ли в дату дедлайна
                    if (day != 27) {
                        this.save(name, 5, 'Уложидся в дату дедлайна +5  Баллов');
                    } else {
                        this.save(name, 0, 'Не уложился в дату дедлайна 0 - Баллов');
                    }
                    this.button_zadasha_ok = false;
                    this.button_zadasha_prinita = false;
                    this.button_zadasha_dop_vremya = false;
                    this.button_neviznogno = false

                    this.sendMessage(name);
                    break;


                case 'Для выполнения требуется больше времени' :
                    day = this.checkCountDayDeadline(this.deadLine_dead);

                    let dateNewDeadLine = prompt('Введите дату число/месяц/год')

                    //  Уложился ли в дату дедлайна
                    if (day != 27) {
                        this.save(`${name} ${dateNewDeadLine}`, 5, 'Уложидся в дату дедлайна');
                    } else {
                        this.save(`${name} ${dateNewDeadLine}`, 0, 'Не уложился в дату дедлайна');
                    }
                    this.sendMessage(`${name} ${dateNewDeadLine}`);
                    this.button_zadasha_dop_vremya = false;
                    break;


                case 'Выполнение невозможно' :
                    day = this.checkCountDayDeadline(this.deadLine_dead);
                    //  Уложился ли в дату дедлайна
                    if (day != 27) {
                        this.save(name, 5, 'Уложидся в дату дедлайна');
                    } else {
                        this.save(name, 0, 'Не уложился в дату дедлайна');
                    }
                    this.button_zadasha_ok = false;
                    this.button_zadasha_prinita = false;
                    this.button_zadasha_dop_vremya = false;
                    this.button_neviznogno = false;
                    this.closeQuote();
                    this.sendMessage(name);
                    break;
            }


        },


        save(name, score, message) {

            let data = {
                ticket_id: this.ticket_id,
                score: score,
                action: name,
            }

            if(document.getElementById('new_chat_message')) {
                let NewMsg = tinymce.get('new_chat_message').getContent();
                if (NewMsg != '') {
                    this.sendMessage(NewMsg)
                }
            }

            this.$parent.putJson(`/ticketsScore/create`, data)
                .then(result => {
                    toastr.success(message, score);
                    location.href = '/newTickets/index/'
                })

        },


    },

    mounted() {
        this.getInfobyTicketId();
        this.checkScore();
    },

    template: `
                <div class="row">
                    <div class="col-md-12">
                        <input type="button" value="Задача принята" text="Задача принята" class="btn btn-info" @click="action('Задача принята')" v-if="button_zadasha_prinita">
                        <div v-if="!button_zadasha_prinita">
                        <input type="button" value="Задача выполнена" text="Задача выполнена" class="btn btn-success" @click="action('Задача выполнена')" v-if="button_zadasha_ok">
                        <input type="button" value="Для выполнения требуется больше времени" text="Для выполнения требуется больше времени" class="btn btn-warning" @click="action('Для выполнения требуется больше времени')" v-if="button_zadasha_dop_vremya">
                        <input type="button" value="Выполнение невозможно" text="Выполнение невозможно" class="btn btn-danger" @click="action('Выполнение невозможно')" v-if="button_neviznogno">
                   </div>
                    </div> 
                </div>    
               `

});



