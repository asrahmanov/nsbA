Vue.component('chat', {
    data() {
        return {
            message: [],
            messageText: '',
            sender: 'nbs',
            Viewed: '0',
        }
    },
    props: ['proj_id'],
    methods: {
        load() {
            this.message = []
            this.$parent.getJson(`../../Chat/GetbyProjid/?proj_id=${this.proj_id}`).then(data => {
                for (let key in data.result) {
                    this.message.push(data.result[key]);
                }
            })
        },
        send () {
            let data = {
                proj_id: this.proj_id,
                message: this.messageText,
                sender: this.sender,
                Viewed: this.Viewed,
            }
            this.$parent.putJson(`../../../Chat/save`, data)
                .then(response => {
                    if (response.result) {
                        this.messageText = ''
                        this.message.push(response.result);
                    } else {
                        toastr.error('Что-то пошло не так :)')
                    }
                })
        }

    },

    mounted() {
       this.load();
    },
    template: `
 <main class="content">
    <div class="container p-0">

<div class="card">
<div class="row g-0">

<div class="col-12 col-lg-12 col-xl-12">

<div class="position-relative">
<div class="chat-messages p-4"  v-for="item in message">

<div class="chat-message-right pb-4" v-if="item.sender=='nbs'">
<div>
<div class="text-muted small text-nowrap mt-2">{{ item.created_at}}</div>
</div>
<div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
<div class="font-weight-bold mb-1">{{ item.sender }}</div>
{{ item.message }}
</div>
</div>

<div class="chat-message-left pb-4" v-else>
<div>
<div class="text-muted small text-nowrap mt-2">{{ item.created_at}}</div>
</div>
<div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
<div class="font-weight-bold mb-1">{{ item.sender }}</div>
{{ item.message }}
</div>
</div>



</div>
</div>

<div class="flex-grow-0 py-3 px-4 border-top">
<div class="input-group">
<input type="text" class="form-control" v-model="messageText" placeholder="Type your message">
<button class="btn btn-primary" @click="send">Send</button>
</div>
</div>

</div>
</div>
</div>
</div>
</main>
    `
});



