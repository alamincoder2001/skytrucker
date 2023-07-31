@extends('layouts.master')
@section('title', 'Company Content')
@section('main-content')
<main>
    <div class="container-fluid" id="Content">
        <div class="heading-title p-2 my-2">
            <span class="my-3 heading "><i class="fas fa-tachometer-alt"></i> <a class="" href="{{ route('dashboard') }}">Dashboard</a> > Content</span>
        </div>
        <div class="card my-3">
            <div class="card-header d-flex justify-content-between">
                <div class="table-head"><i class="fas fa-edit me-1"></i>Update Company Content</div>
            </div>
            <div class="card-body table-card-body">
                <form @@submit.prevent="saveContent">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-12">
                            <label for="inputName" class="col-form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" v-model="content.name" class="form-control form-control-sm shadow-none mb-0" id="inputName">

                            <label for="inputPhone" class="col-form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" v-model="content.phone" class="form-control form-control-sm shadow-none mb-0" id="inputPhone">
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                            <label for="inputAddress" class="col-form-label">Address <span class="text-danger">*</span></label>
                            <textarea v-model="content.address" class="form-control form-control-sm shadow-none mb-0" id="" cols="30" rows="4"></textarea>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                            <label for="inputPhone" class="col-form-label">Logo Image</label>
                            <input type="file" ref="image" name="image" class="form-control form-control-sm" id="image" @change="previewImage">
                            <img v-if="imageUrl != '' && imageUrl != null" v-bind:src="imageUrl" width="150" height="60" alt="">
                        </div>
                        <div class="col-md-12">
                            <hr class="my-2">
                        </div>
                        <div class="clearfix">
                            <div class="text-end m-auto">
                                <button type="reset" class="btn btn-reset shadow-none">Reset</button>
                                <button type="submit" v-bind:disabled="OnProgress == true ? true : false" class="btn btn-success shadow-none" class="btn btn-submit shadow-none">Update</button>
                            </div>
                        </div>
                    </div>
                </form>    
            </div>
        </div>  
    </div>
</main>
@endsection
@push('script')
    <script>
        var app = new Vue({
            el: "#Content",
            data() {
                return {
                    content: {
                        id: '',
                        name: '',
                        address: '',
                        phone: ''
                    },
                    imageUrl: '',
				    selectedFile: null,
                    OnProgress: false,
                    baseUrl: "{{ asset('/') }}"
                }
            },
            
            created() {
                this.getContent();
            },

            methods: {
                getImage(url) {
                    return this.baseUrl + url;
                },

                getContent() {
                    axios.get('/get_content').then(res => {
                        let r = res.data;
                        let content =  r.content;
                        this.content.id = content.id;
                        this.content.name = content.name;
                        this.content.address = content.address;
                        this.content.phone = content.phone;
                        this.imageUrl = this.getImage(content.logo);
                    })
                },

                previewImage() {
                    if(event.target.files.length > 0){
                        this.selectedFile = event.target.files[0];
                        this.imageUrl = URL.createObjectURL(this.selectedFile);
                    } else {
                        this.selectedFile = null;
                        this.imageUrl = null;
                    }
                },

                saveContent() {
                    this.OnProgress = true;
                    let formData = new FormData();

                    formData.append('content', JSON.stringify(this.content));
                    formData.append('logo', this.selectedFile);

                    axios.post('/update_content', formData).then(res => {
                        let r = res.data;
                        $.notify(r.message, "success");
                        this.getContent();
                        this.OnProgress = false;
                    })
                }
            }
        })
    </script>
@endpush