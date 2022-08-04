Vue.component('sites', {
    data() {
        return {
            url: '/sites/getSitesAll', // загрузка квот
            sites: [],
            filtersites: [],




            // siteFilter - для поиск сайта
            siteFilter: '',
        }
    },

    methods: {

        findSite(site_id) {
           return  this.sites.find(el => {
                if(el.site_id == site_id){
                    return el
                }
            })

        },


        load() {
            this.$parent.getJson(`${this.url}`)
                .then(data => {
                    let i = 0;
                    console.log(data.result)
                    for (let key in data.result) {
                        this.sites.push(data.result[key]);
                        this.filtersites.push(data.result[key]);
                    }
                    this.removeLoader();
                });
        },

        filter() {
            let regexp = new RegExp(this.siteFilter, 'i');
            this.filtersites = this.sites.filter(el => regexp.test(el.site_name));
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
        <th>I-BIOS№</th>
        <th><input type="text" class="form-control" placeholder="Сайт" v-model="siteFilter" @input="filter()"></th>
        <th>Тип</th>
        <th>Город</th>
        <th>Регуляторика</th>
        <th>Срок действия</th>
        <th>ЛЭК/НЭК одобрение</th>
        <th>Статус</th>
        <th>Особенности</th>
        <th>Уровень лаборатории </th>
        <th>Кто процессирует</th>
        <th>Ответственный за процессинг</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="item in filtersites">
        <td>{{ item.site_code }}</td>
        <td>{{ item.site_name }}</td>
        <td>{{ item.type_name }}</td>
        <td>{{ item.city_name }}</td>
        <td>{{ item.contract }}</td>
        <td>{{ item.term_validity }}</td>
        <td>{{ item.approved }}</td>
        <td>{{ item.site_status }}</td>
        <td>{{ item.irb_approval }}</td>
        <td>{{ item.level }}</td>
        <td>{{ item.processing }}</td>
        <td>{{ item.misc }}</td>
    </tr>
    </tbody>
</table>       




</div>


 `

});



