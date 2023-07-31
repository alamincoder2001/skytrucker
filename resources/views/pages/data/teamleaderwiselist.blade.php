@extends('layouts.master')
@section('title', 'Leader Wise Data List')
@section('main-content')
<main>
    <div class="container-fluid" id="dataLists">
        <div class="heading-title p-2 my-2">
            <span class="my-3 heading "><i class="fas fa-tachometer-alt"></i> <a class="" href="{{ route('dashboard') }}">Dashboard</a> > Leader Wise Data List</span>
        </div>
        <div class="row">
            <div class="card my-2">
                <div class="card-body table-card-body pt-3">
                    <form @@submit.prevent="getDataLists">
                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-6 pe-0">
                                <div class="form-group row mb-0">
                                    <v-select v-bind:options="leaders" v-model="leader" label="name"></v-select>
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
                        <a :href="`/data/export/${dateFrom}/${dateTo}/0/${leader.id != null ? leader.id : 0}/0`" class="btn btn-excel shadow-none"><i class="fa fa-download"></i> Excel Export</a>
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
                                    <td>@{{ item.area.name }}</td>
                                    <td>@{{ item.location ?? '--'}}</td>
                                </tr>
                            </tbody>
                        </table>
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
        el: "#dataLists",

        data() {
            return {
                dateFrom: moment().format("YYYY-MM-DD"),
                dateTo: moment().format("YYYY-MM-DD"),
                dataLists: [],

                leaders: [],
                leader: {
                    id: null,
                    name: 'Select Team Leader'
                },

                baseUrl: "{{ asset('') }}",
                imgViewUrl: ''

            }
        },

        async created() {
            this.getUsers();
        },

        methods: {
            async getUsers() {
                await axios.post('/get_users')
                    .then(res => {
                        let r = res.data;
                        this.leaders = r.users.filter(item => item.type == 'team_leader');
                    })
            },

            async getDataLists() {
                let filter = {
                    leaderId: this.leader.id != null || this.leader.id != '' ? this.leader.id : '',
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