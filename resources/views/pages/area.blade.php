@extends('layouts.master')
@section('title', 'Area Entry')
@section('main-content')
<main>
    <div class="container-fluid" id="Area">
        <div class="heading-title p-2 my-2">
            <span class="my-3 heading "><i class="fas fa-tachometer-alt"></i> <a class="" href="{{ route('dashboard') }}">Dashboard</a> > Area Entry</span>
        </div>
        <div class="row">
            <div class="col-md-4 col-12">
                <div class="card my-2">
                    <div class="card-header d-flex justify-content-between">
                        <div class="table-head"><i class="fas fa-location-arrow"></i> Add New Area</div>
                    </div>
                    <div class="card-body table-card-body">
                        <form @@submit.prevent="saveArea">
                            <div class="row">
                                <div style="display: none;" v-bind:style="{display: errors != '' ? '' : 'none'}">
                                    <div v-for="(errorArray, idx) in errors" :key="idx">
                                        <div v-for="(allErrors, idx) in errorArray" :key="idx">
                                            <span class="text-white badge bg-danger">@{{ allErrors}} </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-12">
                                    <label for="inputName" class="col-form-label pt-0">University Name <span class="text-danger">*</span></label>
                                    <input type="text" v-model="area.name" class="form-control form-control-sm shadow-none mb-0" id="inputName" placeholder="Enter name">
        
                                    {{-- <label for="latitude" class="col-form-label">Latitude</label>
                                    <input type="text" v-model="area.latitude" class="form-control form-control-sm shadow-none mb-0" id="latitude" placeholder="Latitude">
    
                                    <label for="longitude" class="col-form-label">Longitude </label>
                                    <input type="text" v-model="area.longitude" class="form-control form-control-sm shadow-none mb-0" id="longitude" placeholder="Longitude"> --}}

                                    <label for="zip_code" class="col-form-label">Note</label>
                                    <input type="text" v-model="area.zip_code" class="form-control form-control-sm shadow-none mb-0" id="zip_code" placeholder="Note">

                                    <label for="camera" class="col-form-label">Camera Access</label>
                                    <textarea v-model="area.camera" class="form-control" id="camera" cols="30" rows="2" placeholder="Camera Access"></textarea>

                                    <hr class="my-2">
                                </div>
                                <div class="clearfix">
                                    <div class="text-end m-auto">
                                        <button type="reset" class="btn btn-reset shadow-none">Reset</button>
                                        <button style="display: none;" :style="{ display: area.id == '' ? '' : 'none' }" type="submit" v-bind:disabled="OnProgress == true ? true : false" class="btn btn-submit shadow-none">Save</button>
                                        <button style="display: none;" :style="{ display: area.id != '' ? '' : 'none' }" type="submit" v-bind:disabled="OnProgress == true ? true : false" class="btn btn-success shadow-none" class="btn btn-submit shadow-none">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>    
                    </div>
                </div>  
            </div>
            <div class="col-md-8 col-12">
                <div class="card my-2">
                    <div class="card-header d-flex justify-content-between">
                        <div class="table-head">
                            <i class="fas fa-table me-1"></i> Area List
                        </div>
                        <div class="float-right">
                            <input style="font-size: 12px; height: 26px;" type="text" class="form-control filter-box mb-0" v-model="filter" placeholder="Search..">
                        </div>
                    </div>
                    <div class="card-body table-card-body">
                        <div class="table-responsive">
                            <table class="table table-hover text-center" width="100%" cellspacing="0">
                                <tbody>
                                    <datatable :columns="columns" :data="areas" :filter-by="filter">
                                        <template scope="{ row }">
                                            <tr class="text-center">
                                                <td>@{{ row.sl }}</td>
                                                <td>@{{ row.name }}</td>
                                                {{-- <td>@{{ row.latitude }}</td>
                                                <td>@{{ row.longitude }}</td> --}}
                                                <td>@{{ row.zip_code }}</td>
                                                <td v-if="row.status == 'a'"><span class="badge bg-info">Active</span></td>
                                                <td v-else><span class="badge bg-danger">Inactive</span></td>
                                                <td>
                                                    @if (Auth::user()->type == 'admin')
                                                    <button class="btn btn-edit" @@click.prevent="editArea(row)"><i class="fas fa-pencil-alt"></i></button>
                                                    <button class="btn btn-delete" @@click.prevent="deleteArea(row.id)"><i class="fa fa-trash"></i></button>
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
    <script src="{{ asset('js/vue/area.js') }}"></script>
@endpush