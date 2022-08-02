Vue.component('templatesitecapability', {
    data() {
        return {
            url: '/templateSitecapability/getAll',
            sitecapabilityTemplate: [],
            filteredsitecapabilityTemplate: [],
            newName: '',
            editName: '',
            editId: '',
        }
    },
    methods: {
        sendToManager(id) {
            this.$parent.getJson(`ManagerSitecapability/GenerateSitecapability/?id=${id}`)
                .then(data => {
                    if (data.bad > 0) {
                        toastr.error('Ошибок', data.bad)
                    }
                    if (data.good > 0) {
                        toastr.success('Успешно', data.good)
                    }
                });
        },

        sendToDepart(id) {
            this.$parent.getJson(`ManagerSitecapability/GenerateSitecapabilityByDepartament/?id=${id}`)
                .then(data => {
                    if (data.bad > 0) {
                        toastr.error('Ошибок', data.bad)
                    }
                    if (data.good > 0) {
                        toastr.success('Успешно', data.good)
                    }
                });
        },
        add() {
            if (this.newName.length < 2) {
                toastr.error('Заполните наименование шаблона')
            } else {
                let data = {
                    'name': this.newName,
                }
                this.save(data);
            }

        },
        removeLoader() {
            document.getElementById('loading').remove();
        },
        save(data) {
            this.$parent.putJson(`/templateSitecapability/save`, data)
                .then(datas => {
                    if (datas.result > 0) {
                        if (data.id === undefined) {
                            data.id = datas.result;

                            this.sitecapabilityTemplate.push(data);
                            this.filteredsitecapabilityTemplate.push(data);

                            this.newName = '';
                            toastr.success('Шаблон добавлена');
                        } else {

                            this.filteredsitecapabilityTemplate.forEach(el => {
                                if (el.id == data.id) {
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
<label for="basicInput">Наименование шаблона</label>
<input type="text" class="form-control" v-model="newName" placeholder="Наименование шаблона">
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
    <div class="row">
    <div class="col-md-6">
        <a :href="'/templateSitecapability/edit/?id=' + el.id"  class="btn btn-block btn-primary  btn-sm"">edit</a>
    </div> 
    <div class="col-md-6">
        <button class="btn btn-success  btn-block btn-sm" @click="sendToManager(el.id)"> Send to Manager </button>
    </div>  
    
    </div>
    </td>
    <td>
    <div class="col-md-12">
        <button class="btn btn-success  btn-block btn-sm" @click="sendToDepart(el.id)"> Send to HeadManager </button>
    </div>
</td>
    <td>{{ el.name }}</td>
    </tr>
    </tbody>
    </table>
 
</div> 
</div>
 `

});

