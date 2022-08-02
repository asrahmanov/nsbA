 Vue.component('poworksheets', {
    data() {
        return {
            url: '/poWorksheets/getMy',
            worksheets: [],
            filteredWorksheets: [],

            viewsHeader: '',

            project_details: '',
            feas_russian: '',
            comments: '',
            worksheets_id: '',

            // Статус
            tableText: 'Новый',

            // Для поиска нужной задачи
            internal_id: ''
        }
    },
    methods: {


        openModal(proj_id) {

            let element = this.worksheets.find(el => {
                if (el.proj_id == proj_id) {
                    return el;
                }
            })

            window.open(
                'poWorksheets/quote/?id=' + element.worksheets_id,
                '_blank' // <- This is what makes it open in a new window.
            );

        },


        removeLoader() {
            document.getElementById('loading').remove();
        },

        loadWorksheets() {
            this.$parent.getJson(`${this.url}/?status_id=` + this.status_id)
                .then(data => {
                    let i = 0;
                    for (let key in data.result) {
                        this.worksheets.push(data.result[key]);
                        this.filteredWorksheets.push(data.result[key]);
                    }
                    this.removeLoader();
                    this.filter('Новый');
                });
        },

        filter(status = '') {
            this.tableText = status;
            this.internal_id = '';
            this.filteredWorksheets = this.worksheets.filter(el => {
                if (el.worksheets_status == status) {
                    return true
                }
            });

        },

        filterInternal() {
            let regexp = new RegExp(this.internal_id, 'i');
            this.filteredWorksheets = this.worksheets.filter(el => regexp.test(el.proj_id));
        }
    },

    mounted() {
        this.loadWorksheets();
    },
    template: `

<div> 
      
<div class="row">
<div class="col-md-12">


<button class="btn btn-outline-black" @click="filter('Новый')">Новые</button>
<button class="btn btn-outline-info ml-1" @click="filter('Обработан')">Обработаны</button>
<button class="btn btn-outline-danger ml-1" @click="filter('Отказ')">Отказы</button>
</div>
</div>
   <h2>{{ tableText }}</h2>
<table class="table table-striped table-bordered file-export dataTable">
    
    <thead>
    <tr>
        <th class="mobile-none">№</th>
        <th class="mobile-none">status</th>
        <th class="mobile-none">Date</th>
        <th><input type="text" placeholder="Internal Id" class="form-control" v-model="internal_id" @input="filterInternal()"></th>
        <th class="mobile-none">External Id</th>
        <th class="mobile-none">project name</th>
        <th class="mobile-none">deadline</th>
        <th>action</th>
    </tr>
    </thead>
    <tr  id="loading" class="placeholder-app" style="text-align: center">
<td colspan="8"><img src="../../app-assets/img/loaders/rolling.gif" alt="loading" ></td>
</tr>
    <tbody>
    <tr v-for="el in filteredWorksheets">
    <td class="mobile-none">{{ el.proj_id }}</td>
    <td class="mobile-none">{{ el.worksheets_status }}</td>
    <td class="mobile-none">{{ el.fr_date }}</td>
    <td >{{ el.proj_id }}</td>
    <td class="mobile-none">{{ el.external_id }}</td>
    <td class="mobile-none">{{ el.project_name }}</td>
    <td class="mobile-none">{{ el.deadline }}</td>
    <td>
    
    <button type="button"  class="btn btn-outline-primary btn-block btn-sm" @click="openModal(el.proj_id)"> open </button>
    
    </td>
    
    </tr>
    </tbody>
    
    </table>

        <div id="modalview" style="display: none">
           <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-road2"></i> <b>{{ viewsHeader }}</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                
                
                <div v-if="project_details">
                <h5><b>Original text</b></h5>
                <div v-html="project_details"></div>
                 <hr>
                </div>
                
                <div v-if="feas_russian">
                <h5><b>Russian text</b></h5>
                <div v-html="feas_russian"></div>
                <hr>
                </div>
                
                <div v-if="comments">
                <h5><b>Comments</b></h5>
                <div v-html="comments"></div>
                <hr>
                </div>
                
               
                <div id="otkaz" class="n-block">
                <h5>Причина отказа</h5>
                <textarea name="" id="otkaz_text" class="form-control" cols="30" rows="10"></textarea>
                 <hr>
                </div>  
                

            </div>
            <div class="modal-footer"> 
            <a  target="_blank"  :href="'worksheets/quote/?id='+ worksheets_id" class="btn btn-success" >Квотировать</a>
            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
               
            </div>
        </div>
    </div>
        </div>
                  
                  


</div> 
 `

});



