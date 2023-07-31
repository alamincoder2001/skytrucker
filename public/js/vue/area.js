var app = new Vue({
    el: "#Area",

    data() {
        return {
            area: {
                id: '',
                name: '',
                latitude: '',
                longitude: '',
                zip_code: '',
                camera: ''
            },
            areas: [],
            errors: '',
            OnProgress: false,
            columns: [
                { label: 'SL', field: 'sl', align: 'center', filterable: false },
                { label: 'University Name', field: 'name', align: 'center' },
                // { label: 'Latitude', field: 'latitude', align: 'center' },
                // { label: 'Longitude', field: 'longitude', align: 'center' },
                { label: 'Note', field: 'zip_code', align: 'center' },
                { label: 'Status', field: 'status', align: 'center' },
                { label: 'Action', align: 'center', filterable: false }
            ],
            page: 1,
            per_page: 10,
            filter: ''
        }
    },

    watch: {

    },

    created() {
        this.getAreas();
    },

    methods: {
        getAreas() {
            axios.get('/get_areas')
            .then(res => {
                let r = res.data;
                this.areas = r.areas.map((item, sl) => {
                    item.sl = sl + 1;
                    return item;
                });
            })
        },

        saveArea() {
            this.OnProgress = true;

            let url = "/save_area";
            if(this.area.id != '') {
                url = "/update_area";
            }
            axios.post(url, this.area)
            .then(res => {
                let r = res.data;
                if(r.message) {
                    $.notify(r.message, "success");
                    this.clearForm();
                    this.getAreas();
                    this.OnProgress = false;
                } else {
                    this.OnProgress = false;
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
                this.OnProgress = false;
            })
        },

        editArea(area) {
            Object.keys(this.area).forEach(item => {
                this.area[item] = area[item]
            })
        },

        deleteArea(id) {
            if (confirm('Are You Sure? You Want to Delete this?')) {
                axios.post('/delete_area', {id: id})
                .then(res => {
                    let r = res.data
                    $.notify(r.message, "success");
                    this.getAreas();
                })
            }
        },

        clearForm() {
            this.area =  {
                id: '',
                name: '',
                latitude: '',
                longitude: '',
                zip_code: '',
                camera: ''
            }
            
            this.errors = '';
        }
    }
})