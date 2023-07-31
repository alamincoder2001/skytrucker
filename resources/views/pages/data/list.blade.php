@extends('layouts.master')
@section('title', 'Data List')
@section('main-content')  
<main>
    <div class="container-fluid" id="dataLists">
        <div class="heading-title p-2 my-2">
            <span class="my-3 heading "><i class="fas fa-tachometer-alt"></i> <a class="" href="{{ route('dashboard') }}">Dashboard</a> > Data List</span>
        </div>
        <div class="row">
            <div class="card my-2">
                <div class="card-body table-card-body pt-3">
                    <form  @@submit.prevent="getDataLists">
                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-6">
                                <div class="form-group row">
                                    <label for="type" class="col-sm-4 col-form-label">Search Type</label>
                                    <div class="col-sm-8">
                                        <select v-model="type" id="type" class="form-select form-control mb-0 shadow-none" @@change="onChangeSearchType">
                                            <option value="">All</option>
                                            <option value="area">By Area</option>
                                            <option value="team_leader">Team Leader</option>
                                            <option value="bp">By BP</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2 col-md-4 col-6 px-0" style="display: none;" :style="{ display: type == 'team_leader' ? '' : 'none' }">
                                <div class="form-group row mb-0">
                                    <v-select v-bind:options="leaders" v-model="leader" label="name"></v-select>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-4 col-6 px-0" style="display: none;" :style="{ display: type == 'bp' ? '' : 'none' }">
                                <div class="form-group row mb-0">
                                    <v-select v-bind:options="users" v-model="added" label="name"></v-select>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-4 col-6" style="display: none;" :style="{ display: type == 'area' ? '' : 'none' }">
                                <div class="form-group row mb-0">
                                    <v-select v-bind:options="areas" v-model="area" label="name"></v-select>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-6">
                                <div class="form-group row">
                                    <label for="type" class="col-sm-3 col-form-label">From</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control mb-0 shadow-none" v-model="dateFrom">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-6">
                                <div class="form-group row ">
                                    <label for="type" class="col-sm-3 col-form-label">Date To</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control mb-0 shadow-none" v-model="dateTo">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-3">
                                <button class="btn btn-danger btn-sm shadow-none">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row" style="display: none;" :style="{ display: dataLists.length > 0 ? '' : 'none' }">
            <div class="card my-2">
                <div class="card-header d-flex justify-content-between">
                    <div class="table-head">
                        <i class="fas fa-table me-1"></i> Users List
                    </div>
                    <div class="float-right">
                        <a href="{{ route('data.export') }}" class="btn btn-excel shadow-none"><i class="fa fa-download"></i> Excel Export</a>
                        <button type="button" class="btn btn-print shadow-none" @@click.prevent="print"><i class="fa fa-print"></i> Print</button>
                    </div>
                </div>
                <div class="card-body table-card-body"> 
                    <div class="table-responsive" id="RecordTable">
                        <table class="record-table table table-hover">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Mobile No.</th>
                                    <th>New Sim</th>
                                    <th>Gift</th>
                                    <th>BL App</th>
                                    <th>Gift</th>
                                    <th>Toffee</th>
                                    <th>Gift</th>
                                    <th>Sell Package</th>
                                    <th>Sell(Amount)</th>
                                    <th>Recharge </th>
                                    <th>Recharge(Amount)</th>
                                    <th>Voice Upsell</th>
                                    <th>Voice(Amount)</th>
                                    <th>Area</th>
                                    <th>Location</th>
                                    {{-- <th>Image</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, sl) in dataLists">
                                    <td>@{{ sl + 1 }}</td>
                                    <td>@{{ item.name }}</td>
                                    <td>@{{ item.mobile }}</td>
                                    <td>
                                        <span class="badge bg-success" v-if="item.new_sim == 'yes'">Yes</span>
                                        <span class="badge bg-danger" v-else>No</span>
                                    </td>
                                    <td>@{{ item.new_sim_gift ?? '--'}}</td>
                                    <td>
                                        <span class="badge bg-success" v-if="item.app_install == 'yes'">Yes</span>
                                        <span class="badge bg-danger" v-else>No</span>
                                    </td>
                                    <td>@{{ item.app_install_gift ?? '--' }}</td>
                                    <td>
                                        <span class="badge bg-success" v-if="item.toffee == 'yes'">Yes</span>
                                        <span class="badge bg-danger" v-else>No</span>
                                    </td>
                                    <td>@{{ item.toffee_gift ?? '--' }}</td>
                                    <td>
                                        <span class="badge bg-success" v-if="item.sell_package == 'yes'">Yes</span>
                                        <span class="badge bg-danger" v-else>No</span>
                                    </td>
                                    <td>@{{ item.sell_gb ?? '--' }}</td>
                                    <td>
                                        <span class="badge bg-success" v-if="item.recharge_package == 'yes'">Yes</span>
                                        <span class="badge bg-danger" v-else>No</span>
                                    </td>
                                    <td>@{{ item.recharge_amount ?? '--' }}</td>
                                    <td>
                                        <span class="badge bg-success" v-if="item.voice == 'yes'">Yes</span>
                                        <span class="badge bg-danger" v-else>No</span>
                                    </td>
                                    <td>@{{ item.voice_amount ?? '--' }}</td>
                                    {{-- <td>
                                        <span class="badge bg-success" v-if="item.gift == 'yes'">Yes</span>
                                        <span class="badge bg-danger" v-else>No</span>
                                    </td>
                                    <td>@{{ item.gift_name ?? '--' }}</td> --}}
                                    <td>@{{ item.area.name }}</td>
                                    <td>@{{ item.location ?? '--'}}</td>
                                    {{-- <td>
                                        <img v-if="item.image == null || item.image == ''" src="{{ asset('images/no-profile.png') }}" style="height: 40px; width:40px;" alt="">
                                        <a v-else @@click="imageView(item.image)" type="button" data-bs-toggle="modal" data-bs-target="#imgViewModal" alt="">
                                            <img :src="baseUrl+item.image" style="height: 40px; width:40px;">
                                        </a>
                                    </td> --}}
                                </tr>
                            </tbody>
                        </table>
                    </div>  
                </div>
            </div> 
        </div>
        <!-- Image View Modal -->
        {{-- <div class="modal fade" id="imgViewModal" tabindex="-1" aria-labelledby="imgViewModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <img style="width: 100%; height: 450px;" :src="imgViewUrl" alt="">
                    </div>
                    <div class="modal-footer py-1">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</main>
@endsection

@push('script')
    <script>
        Vue.component('v-select', VueSelect.VueSelect);
        var app = new Vue({
            el: "#dataLists",

            data() {
                return {
                    dateFrom: moment().format("YYYY-MM-DD"),
                    dateTo: moment().format("YYYY-MM-DD"),
                    type: '',
                    dataLists: [],

                    users: [],
                    added: {
                        id: null,
                        name: 'Select BP'
                    },

                    leaders: [],
                    leader: {
                        id: null,
                        name: 'Select Team Leader'
                    },

                    areas: [],
                    area: {
                        id: null,
                        name: 'select area'
                    },

                    baseUrl: "{{ asset('') }}",
                    imgViewUrl: ''

                }
            },

            created() {
                this.getUsers();
                this.getAreas();
            },

            methods: {
                async getUsers() {
                    await axios.post('/get_users')
                    .then(res => {
                        let r = res.data;
                        this.users = r.users.filter(item => item.type == 'bp');
                        this.leaders = r.users.filter(item => item.type == 'team_leader');
                    })
                }, 

                getAreas() {
                    axios.get('/get_areas')
                    .then(res => {
                        let r = res.data;
                        this.areas = r.areas;
                    })
                },

                onChangeSearchType() {
                    if(this.type == 'bp') {
                        this.area.id = null;
                        this.leader.id = null;

                    } else if(this.type == 'team_leader') {
                        this.area.id = '';
                        this.added.id = null;

                    } else if(this.type == 'area') {
                        this.leader.id = null;
                        this.added.id = null;

                    } else {
                        this.area.id = null;
                        this.leader.id = null;
                        this.added.id = null;
                    }
                },

                async getDataLists() {
                    if(this.type == 'added_by' && (this.added.id == null || this.added.id == '')) {
                        $.notify('Please select a user!', "error");
                        return;
                    }

                    if(this.type == 'team_leader' && (this.leader.id == null || this.leader.id == '')) {
                        $.notify('Please select team leader!', "error");
                        return;
                    }

                    if(this.type == 'area' && (this.area.id == null || this.area.id == '')) {
                        $.notify('Please select area!', "error");
                        return;
                    }

                    let filter = {
                        leaderId: this.leader.id != null || this.leader.id != '' ? this.leader.id : '',
                        bpId: this.added.id != null || this.added.id != '' ? this.added.id : '',
                        areaId: this.area.id != null || this.area.id != '' ? this.area.id : '',
                        dateFrom: this.dateFrom,
                        dateTo: this.dateTo,
                    }

                    await axios.post('/data_list', filter)
                    .then(res => {
                        let r = res.data;
                        this.dataLists = r.dataLists.map((item, sl) => {
                            item.sl = sl + 1
                            return item;
                        });
                    })
                },

                // imageView(item) {
                //     this.imgViewUrl = this.baseUrl + item;
                // },

                async print() {
                    let RecordTable = `
                    <!DOCTYPE html>
                    <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <meta http-equiv="X-UA-Compatible" content="ie=edge">
                            <title>Invoice</title>
                            <link rel="stylesheet" href="{{ asset('css/bootstrap-4.min.css') }}" />
                        </head>
                        <body>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <h3>Data Lists</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        ${document.querySelector('#RecordTable').innerHTML}
                                    </div>
                                </div>
                            </div>
                        </body>
                    </html>
                    `;

                    var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);

                    reportWindow.document.head.innerHTML += `
                        <style>
                            .record-table{
                                width: 100%;
                                border-collapse: collapse;
                                border: 1px solid #ccc;
                            }
                            .record-table th, .record-table td{
                                padding: 3px 3px !important;
                                border: 1px solid #ccc;
                            }
                            .record-table td {
                                font-size: 15px !important;
                            }
                            .record-table th{
                                text-align: center;
                                font-size: 16px !important;
                            }
                        </style>
                    `;
                    reportWindow.document.body.innerHTML += RecordTable;

                    let rows = reportWindow.document.querySelectorAll('.record-table tr');
					rows.forEach(row => {
						row.lastChild.remove();
					})

                    reportWindow.focus();
                    await new Promise(resolve => setTimeout(resolve, 1000));
                    reportWindow.print();
                    reportWindow.close();
                }
            }
        })
    </script>
@endpush