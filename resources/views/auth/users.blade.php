@extends('layouts.master')
@section('title', 'User List')
@section('main-content')  
<main>
    <div class="container-fluid" id="userList">
        <div class="heading-title p-2 my-2">
            <span class="my-3 heading "><i class="fas fa-tachometer-alt"></i> <a class="" href="{{ route('dashboard') }}">Dashboard</a> > User List</span>
        </div>
        <div class="row">
            <div class="card my-2">
                <div class="card-body table-card-body pt-3">
                    <form  @@submit.prevent="getUserList">
                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-6">
                                <div class="form-group row">
                                    <label for="type" class="col-sm-4 col-form-label">Search Type</label>
                                    <div class="col-sm-8">
                                        <select v-model="type" id="type" class="form-select form-control mb-0 shadow-none" @@change="onChangeSearchType">
                                            <option value="">All</option>
                                            <option value="added_by">Added By</option>
                                            <option value="team_leader">Team Leader</option>
                                            <option value="user_type">User Type</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-6 px-0" style="display: none;" :style="{ display: type == 'added_by' ? '' : 'none' }">
                                <div class="form-group row mb-0">
                                    <v-select v-bind:options="users" v-model="added" label="name"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-6 px-0" style="display: none;" :style="{ display: type == 'team_leader' ? '' : 'none' }">
                                <div class="form-group row mb-0">
                                    <v-select v-bind:options="leaders" v-model="leader" label="name"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-6" style="display: none;" :style="{ display: type == 'user_type' ? '' : 'none' }">
                                <div class="form-group row mb-0">
                                    <select v-model="userType" class="form-select form-control shadow-none">
                                        <option value="">-- User Type --</option>
                                        <option value="admin">Admin</option>
                                        <option value="team_leader">Team leader</option>
                                        <option value="bp">BP</option>
                                    </select>
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

        <div class="row" style="display: none;" :style="{ display: userList.length > 0 ? '' : 'none' }">
            <div class="card my-2">
                <div class="card-header d-flex justify-content-between">
                    <div class="table-head">
                        <i class="fas fa-table me-1"></i> Users List
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
                                    <th>Name</th>
                                    <th>E-mail</th>
                                    <th>User Type</th>
                                    <th>Team Leader</th>
                                    <th>Username</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(user, sl) in userList">
                                    <td>@{{ sl + 1 }}</td>
                                    <td>@{{ user.name }}</td>
                                    <td>@{{ user.email }}</td>
                                    <td>@{{ user.type }}</td>
                                    <td>@{{ user.team_leader ? user.team_leader.name : '' }}</td>
                                    <td>@{{ user.username }}</td>
                                    <td>
                                        <img v-if="user.image != null" :src="baseUrl+user.image" style="height: 40px; width:40px;" alt="">

                                        <img v-else src="{{ asset('images/no-profile.png') }}" style="height: 40px; width:40px;" alt="">
                                    </td>
                                    <td>
                                        @if (Auth::user()->type == 'admin')
                                            <button class="btn btn-delete shadow-none" @@click.prevent="deleteUser(user.id)"><i class="fa fa-trash"></i></button>
                                        @endif
                                    </td>
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
            el: "#userList",

            data() {
                return {
                    type: '',
                    userType: '',
                    userList: [],
                    users: [],
                    added: {
                        id: null,
                        name: 'select user'
                    },
                    leaders: [],
                    leader: {
                        id: null,
                        name: 'Team Leader'
                    },

                    baseUrl: "{{ asset('/') }}"
                }
            },

            created() {
                this.getUsers();
            },

            methods: {
                async getUsers() {
                    await axios.post('/get_users')
                    .then(res => {
                        let r = res.data;
                        this.users = r.users;
                        this.leaders = r.users.filter(item => item.type == 'team_leader');
                    })
                }, 

                onChangeSearchType() {
                    if(this.type == 'added_by') {
                        this.userType = '';
                        this.leader.id = null;

                    } else if(this.type == 'team_leader') {
                        this.userType = '';
                        this.added.id = null;

                    } else if(this.type == 'user_type') {
                        this.leader.id = null;
                        this.added.id = null;

                    } else {
                        this.userType = '';
                        this.leader.id = null;
                        this.added.id = null;
                    }
                },

                async getUserList() {
                    if(this.type == 'added_by' && (this.added.id == null || this.added.id == '')) {
                        $.notify('Please select a user!', "error");
                        return;
                    }

                    if(this.type == 'team_leader' && (this.leader.id == null || this.leader.id == '')) {
                        $.notify('Please select team leader!', "error");
                        return;
                    }

                    if(this.type == 'user_type' && (this.userType == null || this.userType == '')) {
                        $.notify('Please select user type!', "error");
                        return;
                    }

                    let filter = {
                        leaderId: this.leader.id != null || this.leader.id != '' ? this.leader.id : '',
                        addedBy: this.added.id != null || this.added.id != '' ? this.added.id : '',
                        userType: this.userType
                    }

                    await axios.post('/get_users', filter)
                    .then(res => {
                        let r = res.data;
                        this.userList = r.users.map((item, sl) => {
                            item.sl = sl + 1
                            return item;
                        });
                    })
                },

                deleteUser(id) {
                    if (confirm('Are You Sure? You Want to Delete this?')) {
                        axios.post('/delete_user', {id: id})
                        .then(res => {
                            let r = res.data
                            alert(r.message);
                            this.getUserList();
                        })
                    }
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
                                        <h3>Users List</h3>
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