Vue.component('manager-list-site-capability', {
    data() {
        return {
            capability: [],
            filtercapability: [],


        }
    },


    methods: {
        removeLoader() {
            document.getElementById('loading').remove();
        },

        filter(status) {
            this.tableText = status;
            this.filtercapability = this.capability.filter(el => {
                if(el.status_id == status){
                    return true
                }
            });
        },


        saveWork(status_id,item) {
            let data = {
                'status_id': status_id,
                'id' : item.id
            }
            this.$parent.putJson(`../../ManagerSitecapability/Save`, data)
                .then(datas => {
                    if(datas.result === true){
                        item.status_id = status_id
                        this.filter(1)
                        //  this.capability.splice(this.capability.indexOf(item), 1)
                        //  this.filtercapability.splice(this.filtercapability.indexOf(item), 1)
                        //
                        //
                        // let prod = Object.assign({status_id: 3}, item);
                        // this.capability.push(prod);
                        // this.filtercapability.push(prod);
                    }
                })
        },

        load() {
            this.$parent.getJson(`ManagerSitecapability/getCapabilitybyUsers`)
                .then(data => {
                    for (let key in data.capability) {
                        this.capability.push(data.capability[key]);
                        this.filtercapability.push(data.capability[key]);
                    }
                    this.removeLoader();
                    this.filter(1)
                });
        }

    },

    mounted() {
       this.load()

    },
    template: `

<div> 
<div class="row">
<div class="col-md-12">


<button class="btn btn-outline-black" @click="filter('1')">Новые</button>
<button class="btn btn-outline-info ml-1" @click="filter('2')">Обработаны</button>
<button class="btn btn-outline-danger ml-1" @click="filter('3')">Не подходят</button>
</div>
</div>

<table class="table table-striped table-bordered file-export dataTable">
    <thead>
    <tr>
        <th>Шаблон</th>
        <th>Сайт</th>
        <th>Дата создания</th>
        <th>Статус</th>
        <th>Действие</th>

    </tr>
    </thead>
    <tbody>
    <tr v-for="item in filtercapability">
    <td>{{ item.template_name }}</td>
    <td>{{ item.site_name }}</td>
    <td>{{ item.created_at }}</td>
    <td v-if="item.status_id == 2">Отработан</td>
    <td v-if="item.status_id == 1">Новый</td>
    <td v-if="item.status_id == 3">Не подходит</td>
    <td v-if="item.status_id == 1">
    <a :href="'ManagerSitecapability/ManagerEdit/?id=' + item.id" class="btn btn-success">OPEN</a> 
    <button  class="btn btn-danger" @click="saveWork(3,item)">Не подходит</button> 
    </td>
    <td v-else>
    
    <a :href="'ManagerSitecapability/ManagerEdit/?id=' + item.id" class="btn btn-success">EDIT</a> 
    </td>
    </tr>
    </tbody>
</table>       






<div  id="loading" class="placeholder-app" style="text-align: center">
<img src="../../app-assets/img/loaders/rolling.gif" alt="loading" >
</div>

</div> 
 `

});



