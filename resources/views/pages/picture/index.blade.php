@extends('layouts.master')
@section('title', 'Take Picture')
@section('main-content')
<main>
    <div class="container-fluid" id="takePicture">
        <div class="heading-title p-2 my-2">
            <span class="my-3 heading "><i class="fas fa-tachometer-alt"></i> <a class="" href="{{ route('dashboard') }}">Dashboard</a> > Take Picture</span>
        </div>
        <div class="row">
            <div class="col-md-4 col-12 offset-md-4">
                <div class="card my-2">
                    <div class="card-header d-flex justify-content-between">
                        <div class="table-head"><i class="fas fa-image"></i> Take New Picture</div>
                    </div>
                    <div class="card-body table-card-body">
                        <form @@submit.prevent="savePicture">
                            <div class="row">
                                <div style="display: none;" v-bind:style="{display: errors != '' ? '' : 'none'}">
                                    <div v-for="(errorArray, idx) in errors" :key="idx">
                                        <div v-for="(allErrors, idx) in errorArray" :key="idx">
                                            <span class="text-white badge bg-danger">@{{ allErrors}} </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-12">
                                    <label  for="inputImage" class="col-form-label pt-0">Image <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control form-control-sm" id="inputImage" @@change="onChangeMainImage" ref="image">
                                    <div class="mt-1">
                                        <img :src="image" alt="" style="height: 70px;width:80px;" />
                                    </div>
                                    <hr class="my-2">
                                </div>
                                <div class="clearfix">
                                    <div class="text-end m-auto">
                                        <button type="reset" class="btn btn-reset shadow-none">Reset</button>
                                        <button style="display: none;" :style="{ display: picture.id == '' ? '' : 'none' }" type="submit" v-bind:disabled="onProcess == true ? true : false" class="btn btn-submit shadow-none">Save</button>
                                        <button style="display: none;" :style="{ display: picture.id != '' ? '' : 'none' }" type="submit" v-bind:disabled="onProcess == true ? true : false" class="btn btn-success shadow-none" class="btn btn-submit shadow-none">Update</button>
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
                            <i class="fas fa-table me-1"></i> Picture List
                        </div>
                        <div class="float-right">
                            <input style="font-size: 12px; height: 26px;" type="text" class="form-control filter-box mb-0" v-model="filter" placeholder="Search..">
                        </div>
                    </div>
                    <div class="card-body table-card-body">
                        <div class="table-responsive">
                            <table class="table table-hover text-center" width="100%" cellspacing="0">
                                <tbody>
                                    <datatable :columns="columns" :data="pictures" :filter-by="filter">
                                        <template scope="{ row }">
                                            <tr class="text-center">
                                                <td>@{{ row.sl }}</td>
                                                <td>@{{ row.user.name }}</td>
                                                <td>@{{ row.created_at | formatDateTime('DD-MM-YYYY h:mm A') }}</td>
                                                <td>@{{ row.latitude }}</td>
                                                <td>@{{ row.longitude }}</td>
                                                <td>
                                                    <img v-if="row.image == null || row.image == ''" src="{{ asset('images/no-profile.png') }}" style="height: 40px; width:40px;" alt="">
                                                    <a v-else @@click="imageView(row.image)" type="button" data-bs-toggle="modal" data-bs-target="#imgViewModal" alt="">
                                                        <img :src="baseUrl+row.image" style="height: 40px; width:50px;">
                                                    </a>
                                                </td>
                                                <td>
                                                    @if (Auth::user()->type == 'admin')
                                                    <button class="btn btn-delete" @@click.prevent="deletePicture(row.id)"><i class="fa fa-trash"></i></button>
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
            el: "#takePicture",

            data() {
                return {
                    picture: {
                        id: ''
                    },
                    imageFile: null,
                    image: '',
                    errors: '',
                    onProcess: false,

                    pictures: [],
                    columns: [
                        { label: 'SL', field: 'sl', align: 'center', filterable: false },
                        { label: 'Added By', field: 'user.name', align: 'center' },
                        { label: 'Date & Time', field: 'created_at', align: 'center' },
                        { label: 'Latitude', field: 'latitude', align: 'center' },
                        { label: 'Longitude', field: 'longitude', align: 'center' },
                        { label: 'Image', field: 'image', align: 'center' },
                        { label: 'Action', align: 'center', filterable: false }
                    ],
                    page: 1,
                    per_page: 10,
                    filter: '',

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
                this.getPictures();

                if(this.image == '') {
                    this.image = this.baseUrl + 'images/no-img.jpg';
                }
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

                getPictures() {
                    axios.post('/get_pictures')
                    .then(res => {
                        let r = res.data;
                        this.pictures = r.pictures.map((item, sl) => {
                            item.sl = sl + 1
                            return item;
                        });
                    })
                },

                imageView(item) {
                    this.imgViewUrl = this.baseUrl + item;
                },

                savePicture() {
                    this.errors = [];

                    let fd = new FormData();
                    Object.keys(this.picture).map((k) => {
                        fd.append(k, this.picture[k])
                    })
                    if (this.imageFile) fd.append('image', this.imageFile)

                    this.onProcess = true;

                    let url = '/save_picture';
                    if(this.picture.id != '') {
                        url = '/update_picture';
                    }
                    
                    axios.post(url , fd)
                    .then(res => {
                        let r = res.data;
                        if(r.message) {
                            $.notify(r.message, "success");
                            this.resetForm();
                            this.getPictures();
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

                deletePicture(id) {
                    if (confirm('Are You Sure? You Want to Delete this?')) {
                        axios.post('/delete_picture', {id: id})
                        .then(res => {
                            let r = res.data
                            alert(r.message);
                            this.getPictures();
                        })
                    }
                },

                resetForm() {
                    this.picture = {
                        id: ''
                    }
                    this.image = '';
                    this.imageFile = '';
                    this.$refs.image.value = '';
                }
            }
        })
    </script>
@endpush