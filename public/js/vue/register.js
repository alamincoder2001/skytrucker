Vue.component('v-select', VueSelect.VueSelect);
var app = new Vue({
    el: "#Registration",
    data() {
        return {
            user: {
                id: null,
                name: '',
                // email: '',
                type: '',
                team_leader_id: null,
                area_id: null,
                username: '',
                password: ''
            },
            imageFile: null,
            image: '',
            users: [],
            filterUsers: [],
            leader: null,
            areas: [],
            area: null,
            errors: '',
            onProcess: false,
        }
    },

    created() {
        this.getUser();
        this.getArea();
    },

    methods: {
        onChangeMainImage() {
            if(event.target.files == undefined || event.target.files.length < 1) {
                this.imageFile = null;
                this.image = '';
                return;
            }

            this.imageFile = event.target.files[0];
            this.image = URL.createObjectURL(event.target.files[0]);
        },

        typeOnChange() {
            if(this.user.type == 'bp') {
                let users = this.users.filter(item => item.type == 'team_leader');
                this.filterUsers = users;
            } 
        },

        getUser() {
            axios.post('/get_users')
            .then(res => {
                let r = res.data;
                this.users = r.users.map((item, sl) => {
                    item.sl = sl + 1
                    return item;
                });
                this.filterUsers = r.users;
            })
        },

        getArea() {
            axios.get('/get_areas')
            .then(res => {
                let r = res.data;
                this.areas = r.areas;
            })
        },

        saveData() {
            this.errors = [];

            if(this.user.type != 'admin' && this.area == null) {
                alert('Please select area');
                return;
            }

            let fd = new FormData();

            this.user.team_leader_id = this.leader != null ? this.leader.id : '';
            this.user.area_id = this.area != null ? this.area.id :  '';

            Object.keys(this.user).map((k) => {
                fd.append(k, this.user[k])
            })
            if (this.imageFile) fd.append('image', this.imageFile)

            this.onProcess = true;

            let url = '/save_user';
            if(this.user.id != null) {
                url = '/update_user';
            }
            
            axios.post(url , fd)
            .then(res => {
                let r = res.data;
                if(r.message) {
                    $.notify(r.message, "success");
                    this.resetForm();
                    this.getUser();
                    this.onProcess = false;
                }
            })
            .catch(err => {
                let error = err.response.data.error;
                this.errors = error;
                if(error) {
                    setTimeout( () => {
                        this.errors = '';
                    }, 5000);
                }
                this.onProcess = false;
            })
        },
        
        editUser(user) {
            Object.keys(this.user).forEach(item => {
                this.user[item] = user[item]
            })

            if(user.type == 'bp') {
                this.leader = {
                    id: user.team_leader.id,
                    name: user.team_leader.name,
                }
            }

            this.area = {
                id: user.area_id,
                name: user.area.name
            }

            this.image = user.image
            this.user.password = ''
        },

        deleteUser(id) {
            if (confirm('Are You Sure? You Want to Delete this?')) {
                axios.post('/delete_user', {id: id})
                .then(res => {
                    let r = res.data
                    alert(r.message);
                    this.getUser();
                })
            }
        },

        resetForm() {
            this.user = {
                id: null,
                name: '',
                // email: '',
                type: '',
                team_leader_id: null,
                area_id: null,
                username: '',
                password: ''
            }
            this.image = '';
            this.$refs.image.value = '';
            this.role = null;
            this.area = null;
        }
    }
})