Vue.component('vacation', {
    data() {
        return {
            vacation: [],
            filterVacation: [],
            textFilter: '',
            date_start: '',
            date_end: '',
            comment: '',
        }
    },

    methods: {
        load() {
            this.$parent.getJson(`../vacation/getMy`)
                .then(data => {
                    for (let key in data.result) {
                        this.vacation.push(data.result[key]);
                        this.filterVacation.push(data.result[key]);
                    }});
        },

        filter(value) {
            this.filterVacation = this.vacation.filter(el => {
                if(el.sample == value ) {
                    return true
                }
            });
        },

        add() {

            if (this.date_start == '') {
                toastr.error('Заполните дату начала отпуска ');
            }
            else if (this.date_end == '') {
                toastr.error('Заполните дату окончания отпуска ');
            } else if (this.comment == '') {
                toastr.error('Заполните комментарий');
            }
            else {

                let data = {
                    'date_start': this.date_start,
                    'date_end': this.date_end,
                    'comment': this.comment,
                }
                this.save(data);
            }
        },

        // Сохроняем ткань
        save(data) {
            this.$parent.putJson(`/vacation/Save`, data)
                .then(datas => {
                    if (data.id === undefined) {
                        data.id = datas.result;
                        this.vacation.push(data);
                        this.filterVacation.push(data);
                        toastr.success('ok');
                    }
                    this.date_start = '';
                    this.date_end = '';
                    this.comment = '';
                })

        },



    },

    mounted() {
        this.load();
    },

    template: `

<div> 

<table class="table table-striped table-bordered file-export dataTable">
    <thead>
    <tr>
        <th class="mobile-none">Дата начала</th>
        <th>Дата окончания</th>
        <th class="mobile-none">Комментарий</th>
        <th class="mobile-none">Действие</th>
    </tr>
    </thead>
    
    <tbody>
    <tr>
        <td><input type="date" class="form-control" v-model="date_start"></td>
        <td><input type="date" class="form-control" v-model="date_end"></td>
        <td><input type="text" class="form-control" v-model="comment"></td>
        <td><b target="_blank"  class="btn btn-success btn-block" @click="add()">Добавить</b></td>
    </tr>
    </tbody>
</table>  

<br>
<h3>Таблица отпусков</h3>
<table class="table table-striped table-bordered file-export dataTable">
    <thead>
    <tr>
        <th class="mobile-none">Дата начала</th>
        <th>Дата окончания</th>
        <th class="mobile-none">Комментарий</th>
<!--        <th class="mobile-none">Действие</th>-->
    </tr>
    </thead>
    <tbody>
 
    <tr v-for="item in filterVacation">
        <td>{{ item.date_start }}</td>
        <td>{{ item.date_end }}</td>
        <td>{{ item.comment }}</td>
<!--        <td><button  target="_blank"  class="btn btn-danger btn-block" >Удалить</button></td>-->
    </tr>
    </tbody>
</table>       



</div>


 `

});



