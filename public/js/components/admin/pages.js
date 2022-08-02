Vue.component('pages', {
    data() {
        return {
            url: '/pages/getAll',
            pages: [],
            filteredPages: [],

            newName: '',
            newAlias: '',
            newTemplate: '',

            editName: '',
            editAlias: '',
            editTemplate: '',
            editId: '',

            // Роли
            getRolesUrl: '/role/getAllRoot',
            roles: [],

            // Разрещения
            getAccessUrl: '/access/getAll',
            access: [],

            // Разрещения
            getAccessMenuUrl: '/roleMenu/getAll',
            menu: [],

        }
    },
    methods: {
        doubleScroll(element) {
            var scrollbar = document.createElement('div');
            scrollbar.appendChild(document.createElement('div'));
            scrollbar.style.overflow = 'auto';
            scrollbar.style.overflowY = 'hidden';
            scrollbar.classList.add('scroll-block');
            scrollbar.firstChild.style.width = element.scrollWidth + 'px';
            scrollbar.firstChild.appendChild(document.createTextNode('\xA0'));
            scrollbar.onscroll = function () {
                element.scrollLeft = scrollbar.scrollLeft;
            };
            element.onscroll = function () {
                scrollbar.scrollLeft = element.scrollLeft;
            };
            element.parentNode.insertBefore(scrollbar, element);
        },


        edirPage() {

            let data = {
                'id': this.editId,
                'name': this.editName,
                'alias': this.editAlias,
                'template': this.editTemplate,
            }

            this.save(data);
        },


        addPage() {

            let data = {
                'name': this.newName,
                'alias': this.newAlias,
                'template': this.newTemplate
            }

            this.save(data);
        },


        save(data) {
            this.$parent.putJson(`/pages/save`, data)
                .then(datas => {
                    if (datas.result > 0) {
                        if (data.id === undefined) {
                            data.id = datas.result;

                            this.pages.push(data);
                            this.filteredPages.push(data);

                            this.newAlias = '',
                                this.newName = '';
                            toastr.success('Страница добавлена');
                        } else {

                            this.filteredPages.forEach(el => {
                                if (el.id == data.id) {
                                    el.name = this.editName;
                                    el.alias = this.editAlias;
                                    el.template = this.editTemplate;
                                }

                            })

                            $('#iconModal').modal('hide');

                            toastr.success('Страница изменена');
                        }

                    }
                })

        },


        openModal(id) {

            let element = this.pages.find(el => {
                console.log('el', el)
                if (el.id == id) {
                    return el;
                }
            })

            this.editName = element.name;
            this.editAlias = element.alias;
            this.editTemplate = element.template;
            this.editId = element.id;

            $('#iconModal').modal('show');


        },


        removeLoader() {
            document.getElementById('loading').remove();
            this.doubleScroll(document.querySelector('.table-all_pages'));
        },


        deleted() {
            this.$parent.deleteJson(`/pages/dell`, {id: this.editId})
                .then(data => {
                    if (data.result == 0) {

                        $('#iconModal').modal('hide');


                        this.filteredPages = this.filteredPages.filter(el => {

                            if (el.id != this.editId) {
                                return el;
                            }
                        })

                        this.pages = this.pages.filter(el => {
                            if (el.id != this.editId) {
                                return el;
                            }
                        })

                        toastr.success('Успешно удалено');
                    }
                })

        },


        load() {
            this.$parent.getJson(`${this.url}`)
                .then(data => {
                    let i = 0;
                    for (let key in data) {
                        this.pages.push(data[key]);
                        this.filteredPages.push(data[key]);
                    }
                    this.removeLoader();
                });
        },

        loadRole() {
            this.$parent.getJson(`${this.getRolesUrl}`)
                .then(data => {
                    let i = 0;
                    for (let key in data) {
                        this.roles.push(data[key]);
                    }
                });
        },


        loadRoleMenu() {
            this.$parent.getJson(`${this.getAccessMenuUrl}`)
                .then(data => {
                    let i = 0;
                    for (let key in data) {
                        this.menu.push(data[key]);
                    }
                });
        },

        loadAccess() {
            this.$parent.getJson(`${this.getAccessUrl}`)
                .then(data => {
                    let i = 0;
                    for (let key in data) {
                        this.access.push(data[key]);
                    }
                });
        },

        findCheckAccess(page_id, role_id) {

            let element = this.access.find(el => {
                if (el.page_id == page_id && el.role_id == role_id) {
                    return el;
                }
            })

            if (element) {
                return true;
            } else {
                return false
            }

        },


        findCheckAccessMenu(alias, role_id) {

            let element = this.menu.find(el => {
                if (el.alias == alias && el.role_id == role_id) {
                    return el;
                }
            })

            if (element) {
                return true;
            } else {
                return false
            }

        },


        checkAccess(page_id, role_id) {
            let data = {
                'page_id': page_id,
                'role_id': role_id
            }
            let element = this.findCheckAccess(page_id, role_id);


            if (element) {
                this.deletedAccess(data)
            } else {
                this.addAccess(data);
            }

        },


        checkAccessMenu(alias, role_id, name) {
            let data = {
                'name': name,
                'alias': alias,
                'role_id': role_id,
                'icon': 'ft-grid'
            }
            let element = this.findCheckAccessMenu(alias, role_id);


            if (element) {
                this.deletedAccessMenu(data)
            } else {
                this.addAccessMenu(data);
            }

        },


        addAccess(data) {
            this.$parent.putJson(`/access/save`, data)
                .then(datas => {
                    if (datas.result > 0) {
                        data.id = datas.result;
                        this.$set(this.access, this.access.length, data);
                        toastr.success('Страница добавлена');
                    } else {
                        toastr.error('Ошибка');
                    }
                })


        },

        addAccessMenu(data) {
            this.$parent.putJson(`/roleMenu/save`, data)
                .then(datas => {
                    if (datas.result > 0) {
                        data.id = datas.result;
                        this.$set(this.menu, this.menu.length, data);
                        toastr.success('Страница добавлена');
                    } else {
                        toastr.error('Ошибка');
                    }
                })
        },

        deletedAccess(data) {
            this.$parent.deleteJson(`/access/dell`, data)
                .then(data => {
                    if (data.result == 0) {
                        toastr.success('Успешно удалено');
                        this.access.splice(this.access.indexOf(data), 1)
                    }
                })
        }, deletedAccessMenu(data) {
            this.$parent.deleteJson(`/roleMenu/dell`, data)
                .then(data => {
                    if (data.result == 0) {
                        toastr.success('Успешно удалено');
                        this.menu.splice(this.menu.indexOf(data), 1)
                    }
                })
        }
    },


    async mounted() {
        this.load();
        this.loadRole();
        this.loadAccess();
        this.loadRoleMenu();
    },
    template: `
<div class="row">
<div class="col-md-12 col-sm-12">

<h4>Добавить страницу</h4>

<fieldset class="form-group">
<label for="basicInput">Название страницы</label>
<input type="text" class="form-control" v-model="newName" placeholder="Название страницы">
</fieldset>   

<fieldset class="form-group">
<label for="basicInput">Alias</label>
     <input type="text" class="form-control" v-model="newAlias" placeholder="Alias">
</fieldset>   

<fieldset class="form-group">
<label for="basicInput">Template</label>
     <input type="text" class="form-control" v-model="newTemplate" placeholder="Template">
</fieldset>   
     
<fieldset class="form-group">
     <input type="button" class="btn btn-success " @click="addPage()" value="Добавить">
</fieldset>   
          
   
     
    
</div>
  
  <div class="col-md-12 col-sm-12">
  <h4>Таблица всех страниц</h4>
  <div class="wrapper-table__scrolled table-all_pages">
    <table class="table table-striped table-bordered file-export dataTable table_all-p">
    
    <thead>
    <tr>
        <th class="mobile-none">-</th>
        <th class="mobile-none">Name</th>
        <th class="mobile-none">alias</th>
        <th>template</th>
        
        <th v-for="role in roles"> {{ role.name }}</th>
        
    </tr>
    </thead>
    <tr  id="loading" class="placeholder-app" style="text-align: center">
<td colspan="8"><img src="../../app-assets/img/loaders/rolling.gif" alt="loading" ></td>
</tr>
    <tbody>
    <tr v-for="el in filteredPages">
    <td><button type="button"  class="btn btn-outline-primary btn-block btn-sm" @click="openModal(el.id)"> open </button></td>
    <td class="mobile-none">{{ el.name }}</td>
    <td class="mobile-none">{{ el.alias }}</td>
    <td class="mobile-none">{{ el.template }}</td>
    <th v-for="role in roles"> 
    <label for="">Access</label>
            <input type="checkbox" class="form-control" @input="checkAccess(el.id,role.id)" v-if="findCheckAccess(el.id,role.id)" checked>
            <input type="checkbox" class="form-control" @input="checkAccess(el.id,role.id)" v-else>
    
    <div v-if="el.alias!='НЕТ МЕНЮ'">        
    <label for="">Menu</label>
            <input type="checkbox" class="form-control" @input="checkAccessMenu(el.alias, role.id, el.name)" v-if="findCheckAccessMenu(el.alias,role.id)" checked>
            <input type="checkbox" class="form-control" @input="checkAccessMenu(el.alias, role.id, el.name)" v-else>  
    </div>        
            
    
    </th>
    
    </tr>
    </tbody>
    
    </table>
    </div>
<div class="modal fade text-left" id="iconModal"  role="dialog" aria-labelledby="myModalLabel4"
     aria-hidden="true">
        <div id="modalview" >
           <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-road2"></i> <b>Редактировать - {{ editId }}</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                
               
              <fieldset class="form-group">
<label for="basicInput">Название страницы</label>
<input type="text" class="form-control" v-model='editName' placeholder="Название страницы">
</fieldset>   
<fieldset class="form-group">
<label for="basicInput">Alias</label>
     <input type="text" class="form-control" v-model="editAlias" placeholder="Alias">
</fieldset>   

<fieldset class="form-group">
<label for="basicInput">Template</label>
     <input type="text" class="form-control" v-model="editTemplate" placeholder="Template">
</fieldset> 
                
            </div>
            <div class="modal-footer"> 
            <button type="button" class="btn btn-danger" @click="deleted()">Delete</button>
            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn  btn-success" @click="edirPage()">Edit</button>
            
               
            </div>
        </div>
    </div>
        </div>
                  
          </div>        
</div> 
</div> 
 `

});

