@extends('layouts.master')
@section('title', 'My BL Gaming')
@section('main-content')
<main>
    <div class="container-fluid" id="blGaming">
        <div class="heading-title p-2 my-2">
            <span class="my-3 heading "><i class="fas fa-tachometer-alt"></i> <a class="" href="{{ route('dashboard') }}">Dashboard</a> > My BL Gaming</span>
        </div>
        <div class="row">
            <div class="col-md-4 col-12 offset-md-4">
                <div class="card my-2">
                    <div class="card-header d-flex justify-content-between">
                        <div class="table-head"><i class="fas fa-gamepad"></i> My BL Gaming</div>
                    </div>
                    <div class="card-body table-card-body">
                        <form @@submit.prevent="saveGaming">
                            <div class="row">
                                <div style="display: none;" v-bind:style="{display: errors != '' ? '' : 'none'}">
                                    <div v-for="(errorArray, idx) in errors" :key="idx">
                                        <div v-for="(allErrors, idx) in errorArray" :key="idx">
                                            <span class="text-white badge bg-danger">@{{ allErrors}} </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-12">
                                    <label  for="name" class="col-form-label pt-0">Name <span class="text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control form-control-sm" v-model="gaming.name">

                                    <label  for="mobile" class="col-form-label pt-0">Mobile No. <span class="text-danger">*</span></label>
                                    <input type="text" id="mobile" class="form-control form-control-sm" v-model="gaming.mobile">

                                    <label  for="mobile" class="col-form-label pt-0">Area <span class="text-danger">*</span></label>
                                    <v-select v-bind:options="areas" id="area" v-model="area" label="name"></v-select>

                                    <label  for="my_bl" class="col-form-label pt-2">My BL <span class="text-danger">*</span></label>
                                    <select class="form-control form-select shadow-none" v-model="gaming.my_bl" id="my_bl" >
                                        <option value="">-- select --</option>
                                        <option value="My BL Game">My BL Game</option>
                                        <option value="My BL Quize">My BL Quize</option>
                                        <option value="My BL Music">My BL Music</option>
                                    </select>

                                    <label  for="my_bl" class="col-form-label pt-0">Gift Item </label>
                                    <select class="form-control form-select shadow-none" v-model="gaming.gift" id="my_bl" >
                                        <option value="">-- select --</option>
                                        <option value="T-shirt">T-shirt</option>
                                        <option value="Pop Socket">Pop Socket</option>
                                        <option value="Banbana">Banbana</option>
                                        <option value="Key-ring">Key-ring</option>
                                    </select>

                                    <hr class="my-2">
                                </div>
                                <div class="clearfix">
                                    <div class="text-end m-auto">
                                        <button type="reset" class="btn btn-reset shadow-none">Reset</button>
                                        <button style="display: none;" :style="{ display: gaming.id == '' ? '' : 'none' }" type="submit" v-bind:disabled="onProcess == true ? true : false" class="btn btn-submit shadow-none">Save</button>
                                        <button style="display: none;" :style="{ display: gaming.id != '' ? '' : 'none' }" type="submit" v-bind:disabled="onProcess == true ? true : false" class="btn btn-success shadow-none" class="btn btn-submit shadow-none">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>    
                    </div>
                </div>  
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card my-2">
                    <div class="card-header d-flex justify-content-between">
                        <div class="table-head">
                            <i class="fas fa-table me-1"></i> My BL Gaming List
                        </div>
                        <div class="float-right">
                            <input style="font-size: 12px; height: 26px;" type="text" class="form-control filter-box mb-0" v-model="filter" placeholder="Search..">
                        </div>
                    </div>
                    <div class="card-body table-card-body">
                        <div class="table-responsive">
                            <table class="table table-hover text-center" width="100%" cellspacing="0">
                                <tbody>
                                    <datatable :columns="columns" :data="gamings" :filter-by="filter">
                                        <template scope="{ row }">
                                            <tr class="text-center">
                                                <td>@{{ row.sl }}</td>
                                                <td>@{{ row.name }}</td>
                                                <td>@{{ row.mobile }}</td>
                                                <td>@{{ row.area.name }}</td>
                                                <td>@{{ row.my_bl }}</td>
                                                <td>@{{ row.gift }}</td>
                                                <td>
                                                    @if (Auth::user()->type == 'admin')
                                                    <button class="btn btn-delete" @@click.prevent="deleteGaming(row.id)"><i class="fa fa-trash"></i></button>
                                                    @endif
                                                </td>
                                            </tr>
                                        </template>
                                    </datatable>
                                    <datatable-pager v-model="page" type="abbreviated" :per-page="per_page"></datatable-pager>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('script')
    <script>
        Vue.component('v-select', VueSelect.VueSelect);
        var app = new Vue({
            el: "#blGaming",

            data() {
                return {
                    gaming: {
                        id: '',
                        name: '',
                        mobile: '',
                        area_id: null,
                        my_bl: '',
                        gift: ''
                    },

                    areas: [],
                    area: null,
                   
                    errors: '',
                    onProcess: false,

                    gamings: [],
                    columns: [
                        { label: 'SL', field: 'sl', align: 'center', filterable: false },
                        { label: 'Name', field: 'name', align: 'center' },
                        { label: 'Mobile', field: 'mobile', align: 'center' },
                        { label: 'Area', field: 'area.name', align: 'center' },
                        { label: 'My BL', field: 'my_bl', align: 'center' },
                        { label: 'Gift', field: 'gift', align: 'center' },
                        { label: 'Action', align: 'center', filterable: false }
                    ],
                    page: 1,
                    per_page: 10,
                    filter: '',
                }
            },

            filters: {
                formatDateTime(dt, format) {
                    return dt == '' || dt == null ? '' : moment(dt).format(format);
                }
            },

            created() {
                this.getGamings();
                this.getAreas();
            },

            methods: {
                getAreas() {
                    axios.get('/get_areas')
                    .then(res => {
                        let r = res.data;
                        this.areas = r.areas;
                    })
                },

                getGamings() {
                    axios.post('/get_gamings')
                    .then(res => {
                        let r = res.data;
                        this.gamings = r.gaming.map((item, sl) => {
                            item.sl = sl + 1
                            return item;
                        });
                    })
                },

                saveGaming() {
                    this.errors = [];

                    this.onProcess = true;

                    let url = '/save_gaming';
                    if(this.gaming.id != '') {
                        url = '/update_gaming';
                    }

                    this.gaming.area_id = this.area.id;
                    
                    axios.post(url , this.gaming)
                    .then(res => {
                        let r = res.data;
                        if(r.message) {
                            $.notify(r.message, "success");
                            this.resetForm();
                            this.getGamings();
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

                deleteGaming(id) {
                    if (confirm('Are You Sure? You Want to Delete this?')) {
                        axios.post('/delete_gaming', {id: id})
                        .then(res => {
                            let r = res.data
                            alert(r.message);
                            this.getGamings();
                        })
                    }
                },

                resetForm() {
                    this.gaming = {
                        id: '',
                        name: '',
                        mobile: '',
                        area_id: null,
                        my_bl: '',
                        gift: ''
                    }
                }
            }
        })
    </script>
@endpush