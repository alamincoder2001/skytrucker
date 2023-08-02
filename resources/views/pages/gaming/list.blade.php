@extends('layouts.master')
@section('title', 'My BL Gaming List')
@section('main-content')
<main>
    <div class="container-fluid" id="gamingList">
        <div class="heading-title p-2 my-2">
            <span class="my-3 heading "><i class="fas fa-tachometer-alt"></i> <a class="" href="{{ route('dashboard') }}">Dashboard</a> > My BL Gaming List</span>
        </div>

        <div class="row">
            <div class="card my-2">
                <div class="card-body table-card-body pt-3">
                    <form  @@submit.prevent="getPictuerLists">
                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-6">
                                <div class="form-group row mb-0">
                                    <label for="type" class="col-sm-3 col-form-label">By Area</label>
                                    <div class="col-sm-9">
                                        <v-select v-bind:options="areas" v-model="area" label="name"></v-select>
                                    </div>
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

        <div class="row" style="display: none;" :style="{ display: gamings.length > 0 ? '' : 'none' }">
            <div class="card my-2">
                <div class="card-header d-flex justify-content-between">
                    <div class="table-head">
                        <i class="fas fa-table me-1"></i> My BL Gaming List
                    </div>
                    <div class="float-right">
                        <button type="button" class="btn btn-print shadow-none" @@click.prevent="print"><i class="fa fa-print"></i> Print</button>
                    </div>
                </div>
                <div class="card-body table-card-body"> 
                    <div class="table-responsive" id="RecordTable">
                        <table class="record-table table table-hover">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Area</th>
                                    <th>My BL</th>
                                    <th>Gift</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, sl) in gamings">
                                    <td>@{{ sl + 1 }}</td>
                                    <td>@{{ item.created_at | formatDateTime('DD-MM-YYYY h:mm A') }}</td>
                                    <td>@{{ item.name }}</td>
                                    <td>@{{ item.mobile }}</td>
                                    <td>@{{ item.area == null ? 'n/a':item.area.name }}</td>
                                    <td>@{{ item.my_bl }}</td>
                                    <td>@{{ item.gift }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>  
                </div>
            </div> 
        </div>
        <!-- Image View Modal -->
        <div class="modal fade" id="imgViewModal" tabindex="-1" aria-labelledby="imgViewModalLabel" aria-hidden="true">
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
        </div>
    </div>
</main>
@endsection

@push('script')
<script>
    Vue.component('v-select', VueSelect.VueSelect);
    var app = new Vue({
        el: "#gamingList",

        data() {
            return {
                dateFrom: moment().format("YYYY-MM-DD"),
                dateTo: moment().format("YYYY-MM-DD"),
                areas: [],
                area: {
                    id: null,
                    name: 'select area'
                },
                gamings: [],

                baseUrl: "{{ asset('') }}",
                imgViewUrl: ''
            }
        },

        filters: {
            formatDateTime(dt, format) {
                return dt == '' || dt == null ? '' : moment(dt).format(format);
            }
        },


        created() {
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

            async getPictuerLists() {

                let filter = {
                    areaId: this.area.id != null || this.area.id != '' ? this.area.id : '',
                    dateFrom: this.dateFrom,
                    dateTo: this.dateTo,
                }

                await axios.post('/get_gamings', filter)
                .then(res => {
                    let r = res.data;
                    this.gamings = r.gaming.map((item, sl) => {
                        item.sl = sl + 1
                        return item;
                    });
                })
            },

            imageView(item) {
                this.imgViewUrl = this.baseUrl + item;
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
                                    <h3>My BL Gaming Lists</h3>
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

                reportWindow.focus();
                await new Promise(resolve => setTimeout(resolve, 1000));
                reportWindow.print();
                reportWindow.close();
            }
        }
    })
</script>
@endpush