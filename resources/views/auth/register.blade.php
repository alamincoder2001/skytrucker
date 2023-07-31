@extends('layouts.master')
@section('title', 'User Registration')
@section('main-content')  
<main>
    <div class="container-fluid" id="Registration">
        <div class="heading-title p-2 my-2">
            <span class="my-3 heading "><i class="fas fa-tachometer-alt"></i> <a class="" href="{{ route('dashboard') }}">Dashboard</a> > User Registration</span>
        </div>
        <div class="row">
            <div class="card my-2">
                <div class="card-header">
                    <div class="table-head"><i class="fas fa-user-plus me-1"></i> Add New User</div>
                </div>
                
                <div class="card-body table-card-body">
                    <form @submit.prevent="saveData">
                        <div class="row">
                            <div style="display: none;" v-bind:style="{display: errors != '' ? '' : 'none'}">
                                <div v-for="(errorArray, idx) in errors" :key="idx">
                                    <div v-for="(allErrors, idx) in errorArray" :key="idx">
                                        <span class="text-white badge bg-danger">@{{ allErrors}} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="inputName" class="col-md-4">Name <span class="text-danger">*</span></label>
                                    <div class="col-md-8 ps-0">
                                        <input type="text" min="0" v-model="user.name" class="form-control form-control-md" id="inputName">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputUsername" class="col-md-4">Username <span class="text-danger">*</span></label>
                                    <div class="col-md-8 ps-0">
                                        <input type="text" v-model="user.username" class="form-control form-control-md" id="inputUsername">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="type" class="col-md-4">User Type<span class="text-danger">*</span></label>
                                    <div class="col-md-8 ps-0">
                                        <select v-model="user.type" @@change="typeOnChange();" id="type" class="form-select form-control shadow-none">
                                            <option value="">-- User Type --</option>
                                            <option value="admin">Admin</option>
                                            <option value="team_leader">Team leader</option>
                                            <option value="bp">BP</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" style="display: none;" :style="{ display: user.type == 'bp' ? '' : 'none' }">
                                    <label for="leader" class="col-md-4 pe-0">Team Leader <span class="text-danger">*</span></label>
                                    <div class="col-md-8 ps-0 pb-1">
                                        <v-select v-bind:options="filterUsers" id="leader" v-model="leader" label="name"></v-select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                {{-- <div class="form-group row">
                                    <label for="inputEmail" class="col-md-4">Email <span class="text-danger">*</span></label>
                                    <div class="col-md-8 ps-0">
                                        <input type="email" v-model="user.email" class="form-control form-control-md" id="inputEmail">
                                    </div>
                                </div> --}}
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-md-4">Password <span class="text-danger">*</span></label>
                                    <div class="col-md-8 ps-0">
                                        <input type="password" v-model="user.password" class="form-control form-control-md" id="inputPassword">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="type" class="col-md-4">Select Area</label>
                                    <div class="col-md-8 ps-0">
                                        <v-select v-bind:options="areas" id="area" v-model="area" label="name" placeholder="Select Area"></v-select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                
                                <div class="form-group row">
                                    <label for="inputImage" class="col-md-4">Image</label>
                                    <div class="col-md-8 ps-0">
                                        <input type="file" class="form-control form-control-sm" id="inputImage" @@change="onChangeMainImage" ref="image">
                                        <div class="mt-1">
                                            <img :src="image" alt="" style="height: 70px;width:80px;" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <hr class="my-2">
                                <div class="clearfix">
                                    <div class="text-end m-auto">
                                        <button type="reset" class="btn btn-reset">Reset</button>
                                        <button type="submit" class="btn btn-success" v-if="user.id == null" :disabled="onProcess ? true : false">Submit</button>
                                        <button type="submit" class="btn btn-success" v-else :disabled="onProcess ? true : false"> Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>    
                </div>
            </div> 
        </div>
        <div class="row">
            <div class="card my-1">
                <div class="card-header d-flex justify-content-between">
                    <div class="table-head"><i class="fas fa-users me-1"></i>All User List</div>
                    <a href="{{ route('dashboard') }}" class="btn btn-addnew"> <i class="fas fa-tachometer-alt"></i> Dashboard</a>
                </div>
                <div class="card-body table-card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="text-center bg-light">
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Team Leader</th>
                                {{-- <th>Email</th> --}}
                                <th>Area</th>
                                <th>UserName</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr v-for="(item, i) in users" :key="i">
                                <td>@{{ item.sl }}</td>
                                <td>@{{ item.name }}</td>
                                <td>
                                    <span v-if="item.type == 'admin'">Admin</span>
                                    <span v-else-if="item.type == 'team_leader'">Team Leader</span>
                                    <span v-else>BP</span>
                                </td>
                                <td>@{{ item.team_leader ? item.team_leader.name : '-' }}</td>
                                {{-- <td>@{{ item.email }}</td> --}}
                                <td>@{{ item.area ? item.area.name : '-' }}</td>
                                <td>@{{ item.username }}</td>
                                <td class="text-center">
                                    @if (Auth::user()->type == 'admin')
                                    <button v-if="item.id != 1" class="btn btn-edit" @@click.prevent="editUser(item)"><i class="fas fa-pencil-alt"></i></button>
                                    <button v-if="item.id != 1" @@click.prevent="deleteUser(item.id)" class="btn btn-delete"><i class="fa fa-trash"></i></button>

                                    <a v-if="item.id != 1" :href="`/user/permission/${item.id}`"><i class="fas fa-user-edit"></i></a>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@push('script')
    <script src="{{ asset('js/vue/register.js') }}"></script>
@endpush