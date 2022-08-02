Vue.component('SelectUsersADDSitecapability', {
    data() {
        return {
            url: '/templateSitecapability/getAll',
            sitecapabilityTemplate: [],
            filteredsitecapabilityTemplate: [],
            newName: '',
            editName:'',
            editId:'',
        }
    },


    props: ['uid'],

    methods: {
        sendToManager(id){
            this.$parent.getJson(`ManagerSitecapability/GenerateSitecapability/?id=${id}`)
                .then(data => {
                    if(data.bad > 0){
                        toastr.error('Ошибок',data.bad)
                    }
                    if(data.good > 0){
                        toastr.success('Успешно',data.good)
                    }

                    console.log(data)
                });
        },
        add() {
            let data = {
                'name': this.newName,
            }
            this.save(data);
        },
        removeLoader() {
            document.getElementById('loading').remove();
        },
        save(data){
            this.$parent.putJson(`/templateSitecapability/save`, data)
                .then(datas => {
                    if(datas.result > 0) {
                        if(data.id === undefined) {
                            data.id = datas.result;

                            this.sitecapabilityTemplate.push(data);
                            this.filteredsitecapabilityTemplate.push(data);

                            this.newName = '';
                            toastr.success('Шаблон добавлена');
                        }else {

                            this.filteredsitecapabilityTemplate.forEach(el => {
                                if(el.id == data.id) {
                                    el.name = this.editName;
                                }
                            })

                            $('#iconModal').modal('hide');

                            toastr.success('Шаблон изменен');
                        }
                    }
                })
        },

        load() {
            this.$parent.getJson(`${this.url}`)
                .then(data => {
                    let i = 0;
                    for (let key in data) {
                        this.sitecapabilityTemplate.push(data[key]);
                        this.filteredsitecapabilityTemplate.push(data[key]);
                    }
                    this.removeLoader();
                });
        },
    },

    mounted() {
            this.load();
        },

    template: `
<div class="row">
<div class="col-md-12 col-sm-12">

<h4>Добавить Шаблон</h4>

<fieldset class="form-group">
<label for="basicInput">Название шаблона</label>
<input type="text" class="form-control" v-model="newName" placeholder="Название шаблона">
</fieldset>   
     
<fieldset class="form-group">
     <input type="button" class="btn btn-success " @click="add()" value="Добавить">
</fieldset>   
          
     
    
</div>
  <div class="col-md-12 col-sm-12">
  <h4>Таблица всех шаблонов</h4>
<table class="table table-striped table-bordered file-export dataTable">
    <thead>
    <tr>
        <th>-</th>
        <th>Name</th>
    </tr>
    </thead>
    <tr  id="loading" class="placeholder-app" style="text-align: center">
<td colspan="8"><img src="../../app-assets/img/loaders/rolling.gif" alt="loading"></td>
</tr>
    <tbody>
    <tr v-for="el in filteredsitecapabilityTemplate">
    <td>
    <a :href="'/templateSitecapability/edit/?id=' + el.id"  class="btn btn-primary btn-block btn-sm""> edit </a>
    <button class="btn btn-success btn-block btn-sm" @click="sendToManager(el.id)"> Send to Manager </button>
    </td>
    <td>{{ el.name }}</td>
    </tr>
    </tbody>
    </table>
 
</div> 
</div>
 `

});

