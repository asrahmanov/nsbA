Vue.component('staff', {
    data() {
        return {
            staff: [],
            filteredstaff: [],
            textFilter: '',
            staffVisible: false,
            // поля для добавления струдника
            name: '',
            position: '',
            email: '',
            phone: '',
        }
    },
    props: ['script_id'],
    methods: {
        load() {
            this.staff = [];
            this.filteredstaff = [];
            this.$parent.getJson(`../../../staff/GetByCompany/?script_id=${this.script_id}`)
                .then(data => {
                    let i = 0;
                    for (let key in data.result) {
                        this.staff.push(data.result[key]);
                        this.filteredstaff.push(data.result[key]);
                    }
                });
        },
        save() {
            let data = {
                script_id: this.script_id,
                name: this.name,
                position: this.position,
                email: this.email,
                phone: this.phone
            }
            this.$parent.putJson(`../../../staff/save`, data)
                .then(response => {
                    if (response.result > 0) {
                        this.load();
                        this.staffVisible = false;
                        this.claerParams();
                    } else {
                        toastr.error('Что-то пошло не так :)')
                    }
                })


        },
        claerParams() {
            this.name = '';
            this.position = '';
            this.email = '';
            this.phone = '';
        },
        dell(item) {
            this.$parent.deleteJson(`../../../staff/dell`, {id: item.id})
                .then(data => {
                    if (data.result == true) {
                        toastr.success('Успешно удалено');
                        this.staff.splice(this.staff.indexOf(item), 1)
                        this.filteredstaff.splice(this.filteredstaff.indexOf(item), 1)
                    }
                })
        }
    },
    mounted() {
        this.load();
        // Отображаем новые
    },
    template: `

<div> 
<h2>Сотрудники</h2>
<button class="btn btn-success" @click="staffVisible=!staffVisible">+</button>
<div v-if="staffVisible">
<label for="name">ФИО</label>
<input type="text" class="form-control" v-model="name">

<label for="position">Должность</label>
<input type="text" class="form-control" v-model="position">

<label for="email">email</label>
<input type="text" class="form-control" v-model="email">

<label for="phone">phone</label>
<input type="text" class="form-control" v-model="phone">

<br>
<button class="btn btn-success btn-block" @click="save()">Добавить</button>

</div>
<table class="table table-striped table-bordered file-export dataTable">
    <thead>
    <tr>
        <th class="mobile-none">ФИО</th>
        <th class="mobile-none">Должность</th>
        <th>Email</th>
        <th class="mobile-none">Телефон</th>
        <th>action</th>
    </tr>
    </tr>
    </thead>
    <tbody>
    <tr v-for="item in filteredstaff">
        <td>{{ item.name }}</td>
        <td>{{ item.position }}</td>
        <td>{{ item.email }}</td>
        <td>{{ item.phone }}</td>
        <td><button class="btn btn-danger btn-block" @click="dell(item)">Удалить</button></td>
    </tr>
    </tbody>
</table>       


</div>


 `

});



