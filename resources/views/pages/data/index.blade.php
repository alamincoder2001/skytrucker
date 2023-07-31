@extends('layouts.master')
@section('title', 'Data Entry')
@section('main-content')
<main>
    <div class="container-fluid" id="DataEntry">
        <div class="heading-title p-2 my-2">
            <span class="my-3 heading "><i class="fas fa-tachometer-alt"></i> <a class="" href="{{ route('dashboard') }}">Dashboard</a> > Data Entry</span>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-12 mx-auto">
                <div class="card my-2">
                    <div class="card-header d-flex justify-content-between">
                        <div style="display: none;" :style="{ display: dataPendign == false ? '' : 'none'}" class="table-head"><i class="fas fa-plus-circle"></i> Add New Data</div>
                        {{-- <div style="display: none;" :style="{ display: dataPendign == true ? '' : 'none'}" class="table-head"><i class="fas fa-plus-circle"></i> Verify Phone</div> --}}
                    </div>
                    <div class="card-body table-card-body">
                        <form @@submit.prevent="saveData" style="display: none;" :style="{ display: dataPendign == false ? '' : 'none'}">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-12">
                                    <div class="form-group row">
                                        <label class="col-md-4 col-5" for="name">Enter Name</label>
                                        <div class="col-md-8 col-7">
                                            <input type="text" v-model="data.name" class="form-control" id="name" placeholder="Enter Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-5" for="mobile">Mobile Number</label>
                                        <div class="col-md-8 col-7">
                                            <input type="text" v-model="data.mobile" class="form-control" id="mobile" placeholder="Mobile Number">
                                        </div>
                                    </div>
                                    <div class="form-group row pb-2">
                                        <label class="col-md-4 col-6" for="sim">Buy New Sim?</label>
                                        <div class="col-md-8 col-6">
                                            <label for="sim_yes">
                                                <input type="radio" v-model="data.new_sim" value="yes" id="sim_yes"> Yes
                                            </label>&nbsp;&nbsp;&nbsp;
                                            <label for="sim_no">
                                                <input type="radio" v-model="data.new_sim" value="no" id="sim_no"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row pb-2" style="display: none;" :style="{ display: data.new_sim == 'yes' ? '' : 'none' }">
                                        <label class="col-md-4 col-5" for="new_sim_gift">Gift Item?</label>
                                        <div class="col-md-8 col-7">
                                            <label for="sim_gift_yes">
                                                <input type="radio" v-model="data.new_sim_gift" value="yes" id="sim_gift_yes"> Yes
                                            </label>&nbsp;&nbsp;&nbsp;
                                            <label for="sim_gift_no">
                                                <input type="radio" v-model="data.new_sim_gift" value="no" id="sim_gift_no"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row pb-2">
                                        <label class="col-md-4 col-6" for="blApp">MyBL App Install?</label>
                                        <div class="col-md-8 col-6">
                                            <label for="bl_app_yes">
                                                <input type="radio" v-model="data.app_install" value="yes" id="bl_app_yes"> Yes
                                            </label>&nbsp;&nbsp;&nbsp;
                                            <label for="bl_app_no">
                                                <input type="radio" v-model="data.app_install" value="no" id="bl_app_no"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row pb-2" style="display: none;" :style="{ display: data.app_install == 'yes' ? '' : 'none' }">
                                        <label class="col-md-4 col-5" for="app_install_gift">Gift Item?</label>
                                        <div class="col-md-8 col-7">
                                            <label for="app_gift_yes">
                                                <input type="radio" v-model="data.app_install_gift" value="yes" id="app_gift_yes"> Yes
                                            </label>&nbsp;&nbsp;&nbsp;
                                            <label for="app_gift_no">
                                                <input type="radio" v-model="data.app_install_gift" value="no" id="app_gift_no"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row pb-2">
                                        <label class="col-md-4 col-6" for="toffee">Toffee App Install?</label>
                                        <div class="col-md-8 col-6">
                                            <label for="toffee_yes">
                                                <input type="radio" v-model="data.toffee" value="yes" id="toffee_yes"> Yes
                                            </label>&nbsp;&nbsp;&nbsp;
                                            <label for="toffee_no">
                                                <input type="radio" v-model="data.toffee" value="no" id="toffee_no"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row pb-2" style="display: none;" :style="{ display: data.toffee == 'yes' ? '' : 'none' }">
                                        <label class="col-md-4 col-5" for="toffee_gift">Gift Item?</label>
                                        <div class="col-md-8 col-7">
                                            <label for="toffee_gift_yes">
                                                <input type="radio" v-model="data.toffee_gift" value="yes" id="toffee_gift_yes"> Yes
                                            </label>&nbsp;&nbsp;&nbsp;
                                            <label for="toffee_gift_no">
                                                <input type="radio" v-model="data.toffee_gift" value="no" id="toffee_gift_no"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row pb-2">
                                        <label class="col-md-4 col-6" for="data_package">Data Sell Package?</label>
                                        <div class="col-md-8 col-6">
                                            <label for="data_package_yes">
                                                <input type="radio" v-model="data.sell_package" value="yes" id="data_package_yes"> Yes
                                            </label>&nbsp;&nbsp;&nbsp;
                                            <label for="data_package_no">
                                                <input type="radio" v-model="data.sell_package" value="no" id="data_package_no"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row" style="display: none;" :style="{ display: data.sell_package == 'yes' ? '' : 'none' }">
                                        <label class="col-md-4 col-5" for="sell_gb">Data Sell (Amount)</label>
                                        <div class="col-md-8 col-7">
                                            <input type="text" v-model="data.sell_gb" class="form-control" id="sell_gb" placeholder="Amount">
                                        </div>
                                    </div>
                                    <div class="form-group row pb-2">
                                        <label class="col-md-4 col-6" for="recharge">Recharge Package?</label>
                                        <div class="col-md-8 col-6">
                                            <label for="recharge_yes">
                                                <input type="radio" v-model="data.recharge_package" value="yes" id="recharge_yes"> Yes
                                            </label>&nbsp;&nbsp;&nbsp;
                                            <label for="recharge_no">
                                                <input type="radio" v-model="data.recharge_package" value="no" id="recharge_no"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row" style="display: none;" :style="{ display: data.recharge_package == 'yes' ? '' : 'none' }">
                                        <label class="col-md-4 col-5" for="amount">Recharge (Amount)</label>
                                        <div class="col-md-8 col-7">
                                            <input type="text" v-model="data.recharge_amount" class="form-control" id="amount"  placeholder="Amount">
                                        </div>
                                    </div>

                                    <div class="form-group row pb-2">
                                        <label class="col-md-4 col-6" for="voice">Voice Upsell?</label>
                                        <div class="col-md-8 col-6">
                                            <label for="voice_yes">
                                                <input type="radio" v-model="data.voice" value="yes" id="voice_yes"> Yes
                                            </label>&nbsp;&nbsp;&nbsp;
                                            <label for="voice_no">
                                                <input type="radio" v-model="data.voice" value="no" id="voice_no"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row" style="display: none;" :style="{ display: data.voice == 'yes' ? '' : 'none' }">
                                        <label class="col-md-4 col-5" for="voice_amount">Voice (Amount)</label>
                                        <div class="col-md-8 col-7">
                                            <input type="text" v-model="data.voice_amount" class="form-control" id="voice_amount"  placeholder="Amount">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-4 col-5" for="area">Select Area</label>
                                        <div class="col-md-8 col-7 mb-2">
                                            <v-select v-bind:options="areas" id="area" v-model="area" label="name"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-5" for="location">Location</label>
                                        <div class="col-md-8 col-7">
                                            <input type="text" v-model="data.location" class="form-control" id="location"  placeholder="Location">
                                        </div>
                                    </div>
                                    <div class="form-group row pb-2">
                                        <label class="col-md-12 col-12" for="program"><i class="fas fa-dot-circle"></i> How do you feel about this program of Banglalink?</label>
                                        <div class="col-md-12 col-12" style="padding-left: 30px;">
                                            <label for="prog_very_happy" class="cust-size">
                                                <input type="radio" v-model="data.program" value="Very happy" id="prog_very_happy"> Very happy 
                                            </label> <br>
                                            <label for="prog_some_happy" class="cust-size">
                                                <input type="radio" v-model="data.program" value="Somewhat happy" id="prog_some_happy"> Somewhat happy
                                            </label> <br>
                                            <label for="prog_neither_happy" class="cust-size">
                                                <input type="radio" v-model="data.program" value="Neither happy or unhappy" id="prog_neither_happy"> Neither happy or unhappy 
                                            </label> <br>
                                            <label for="prog_unhappy" class="cust-size">
                                                <input type="radio" v-model="data.program" value="Somewhat unhappy" id="prog_unhappy"> Somewhat unhappy
                                            </label> <br>
                                            <label for="prog_very_unhappy" class="cust-size">
                                                <input type="radio" v-model="data.program" value="Very unhappy" id="prog_very_unhappy"> Very unhappy
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row pb-1">
                                        <label class="col-md-12 col-12" for="4g_experience"><i class="fas fa-dot-circle"></i> How do you like about BL 4G experience ? </label>
                                        <div class="col-md-12 col-12" style="padding-left: 30px;">
                                            <label for="expe_very_happy" class="cust-size">
                                                <input type="radio" v-model="data.experience" value="Very happy" id="expe_very_happy"> Very happy 
                                            </label> <br>
                                            <label for="expe_some_happy" class="cust-size">
                                                <input type="radio" v-model="data.experience" value="Somewhat happy" id="expe_some_happy"> Somewhat happy
                                            </label> <br>
                                            <label for="expe_neither_happy" class="cust-size">
                                                <input type="radio" v-model="data.experience" value="Neither happy or unhappy" id="expe_neither_happy"> Neither happy or unhappy 
                                            </label> <br>
                                            <label for="expe_unhappy" class="cust-size">
                                                <input type="radio" v-model="data.experience" value="Somewhat unhappy" id="expe_unhappy"> Somewhat unhappy
                                            </label> <br>
                                            <label for="expe_very_unhappy" class="cust-size">
                                                <input type="radio" v-model="data.experience" value="Very unhappy" id="expe_very_unhappy"> Very unhappy
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row pb-1">
                                        <label class="col-md-12 col-12" for="app_experience"><i class="fas fa-dot-circle"></i> How do you like about My BL app experience ?</label>
                                        <div class="col-md-12 col-12" style="padding-left: 30px;">
                                            <label for="appexpe_very_happy" class="cust-size">
                                                <input type="radio" v-model="data.app_experience" value="Very happy" id="appexpe_very_happy"> Very happy 
                                            </label> <br>
                                            <label for="appexpe_some_happy" class="cust-size">
                                                <input type="radio" v-model="data.app_experience" value="Somewhat happy" id="appexpe_some_happy"> Somewhat happy
                                            </label> <br>
                                            <label for="appexpe_neither_happy" class="cust-size">
                                                <input type="radio" v-model="data.app_experience" value="Neither happy or unhappy" id="appexpe_neither_happy"> Neither happy or unhappy 
                                            </label> <br>
                                            <label for="appexpe_unhappy" class="cust-size">
                                                <input type="radio" v-model="data.app_experience" value="Somewhat unhappy" id="appexpe_unhappy"> Somewhat unhappy
                                            </label> <br>
                                            <label for="appexpe_very_unhappy" class="cust-size">
                                                <input type="radio" v-model="data.app_experience" value="Very unhappy" id="appexpe_very_unhappy"> Very unhappy
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row pb-1">
                                        <label class="col-md-12 col-12" for="gaming"><i class="fas fa-dot-circle"></i> How do you like about BL gaming experience ?</label>
                                        <div class="col-md-12 col-12" style="padding-left: 30px;">
                                            <label for="gaming_very_happy" class="cust-size">
                                                <input type="radio" v-model="data.gaming" value="Very happy" id="gaming_very_happy"> Very happy 
                                            </label> <br>
                                            <label for="gaming_some_happy" class="cust-size">
                                                <input type="radio" v-model="data.gaming" value="Somewhat happy" id="gaming_some_happy"> Somewhat happy
                                            </label> <br>
                                            <label for="gaming_neither_happy" class="cust-size">
                                                <input type="radio" v-model="data.gaming" value="Neither happy or unhappy" id="gaming_neither_happy"> Neither happy or unhappy 
                                            </label> <br>
                                            <label for="gaming_unhappy" class="cust-size">
                                                <input type="radio" v-model="data.gaming" value="Somewhat unhappy" id="gaming_unhappy"> Somewhat unhappy
                                            </label> <br>
                                            <label for="gaming_very_unhappy" class="cust-size">
                                                <input type="radio" v-model="data.gaming" value="Very unhappy" id="gaming_very_unhappy"> Very unhappy
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row pb-1">
                                        <label class="col-md-12 col-12 pb-1" for="event"><i class="fas fa-dot-circle"></i> What do you think, what other features can be added to this type of event?</label>
                                        <div class="col-md-12 col-12">
                                            <textarea class="form-control" v-model="data.event" id="event" cols="30" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row pb-1">
                                        <label class="col-md-12 col-12 pb-1" for="service"><i class="fas fa-dot-circle"></i> Are you satisfied with Banglalink’s overall service?</label>
                                        <div class="col-md-12 col-12" style="padding-left: 30px;">
                                            <label for="very_satisfied" class="cust-size">
                                                <input type="radio" v-model="data.service" value="Very satisfied" id="very_satisfied"> Very satisfied 
                                            </label> <br>
                                            <label for="satisfied" class="cust-size">
                                                <input type="radio" v-model="data.service" value="Satisfied" id="satisfied"> Satisfied
                                            </label> <br>
                                            <label for="dissatisfied" class="cust-size">
                                                <input type="radio" v-model="data.service" value="Disssatisfied" id="dissatisfied"> Disssatisfied 
                                            </label> <br>
                                            <label for="very_dissatisfied" class="cust-size">
                                                <input type="radio" v-model="data.service" value="Very dissatisfied" id="very_dissatisfied"> Very dissatisfied
                                            </label> <br>
                                        </div>
                                    </div>
                                    <div class="form-group row pb-1">
                                        <label class="col-md-12 col-12 pb-1" for="future"><i class="fas fa-dot-circle"></i> Where do you want to see Bangllink as a brand in the future?</label>
                                        <div class="col-md-12 col-12">
                                            <textarea class="form-control" v-model="data.future" id="future" cols="30" rows="2"></textarea>
                                        </div>
                                    </div>
                                    {{-- <div class="form-group row">
                                        <label class="col-md-4 col-5" for="image">Image</label>
                                        <div class="col-md-8 col-7">
                                            <input type="file" class="form-control form-control-sm" id="inputImage" @@change="onChangeMainImage" ref="image">
                                            <div class="mt-1" v-if="image">
                                                <img :src="image" alt="" style="height: 100px;width:100px;" />
                                            </div>
                                        </div>
                                    </div> --}}
                                    <hr class="my-2">
                                    <div class="clearfix">
                                        <div class="text-end m-auto">
                                            <button type="reset" class="btn btn-reset shadow-none">Reset</button>
                                            <button style="display: none;" :style="{ display: data.id == '' ? '' : 'none' }" type="submit" v-bind:disabled="OnProgress == true ? true : false" class="btn btn-success shadow-none" class="btn btn-submit shadow-none">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{-- <form @@submit.prevent="phoneVerify" style="display: none;" :style="{ display: dataPendign == true ? '' : 'none'}">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="text-center text-warning" style="font-size: 17px;">OTP Sent to your number @{{ otpMobile }}, Please verify using this 4 digit OTP!</p>
                                </div>
                                <div class="col-lg-7 col-md-7 col-12 mx-auto">
                                    <div class="form-group row">
                                        <label class="col-md-4 col-5" for="code">OTP Code <span class="text-danger">*</span></label>
                                        <div class="col-md-8 col-7">
                                            <input type="text" v-model="code" class="form-control" id="code" placeholder="Enter Code">
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="clearfix">
                                        <div class="text-end m-auto">
                                            <button type="reset" class="btn btn-reset shadow-none">Reset</button>
                                            <button type="submit" v-bind:disabled="OnProgress == true ? true : false" class="btn btn-success shadow-none" class="btn btn-submit shadow-none">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form> --}}
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
            el: '#DataEntry',

            data() {
                return {
                    data: {
                        id: '',
                        name: '',
                        mobile: '019',
                        new_sim: '',
                        new_sim_gift: '',
                        app_install: '',
                        app_install_gift: '',
                        toffee: '',
                        toffee_gift: '',
                        sell_package: '',
                        sell_gb: '',
                        recharge_package: '',
                        recharge_amount: '',
                        voice: '',
                        voice_amount: '',
                        // gift: '',
                        // gift_name: '',
                        area_id: '',
                        location: '',
                        program: '',
                        experience: '',
                        app_experience: '',
                        gaming: '',
                        event: '',
                        service: '',
                        future: '',
                    },

                    // imageFile: null,
                    // image: '',

                    areas: [],
                    area: {
                        id: null,
                        name: 'select area'
                    },
                    OnProgress: false,

                    // otpMobile: '',
                    // code:'',
                    dataPendign: false,
                }
            },

            created() {
                this.getAreas();
            },

            methods: {
                // onChangeMainImage() {
                //     if(event.target.files == undefined || event.target.files.length < 1) {
                //         this.imageFile = null;
                //         this.image = '';
                //         return;
                //     }

                //     this.imageFile = event.target.files[0];
                //     this.image = URL.createObjectURL(event.target.files[0]);
                // },
                getAreas() {
                    axios.get('/get_areas')
                    .then(res => {
                        let r = res.data;
                        this.areas = r.areas;
                    })
                },

                saveData() {
                    if(this.area.id == null) {
                        $.notify('Please Select a Area!', "error");
                        return;
                    }
                    
                    if(this.area.id == null) {
                        $.notify('Please Select a Area!', "error");
                        return;
                    }

                    let fd = new FormData();

                    this.data.area_id = this.area.id;
                    this.data.new_sim_gift = this.data.new_sim == 'yes' && this.data.new_sim_gift == '' ? 'no' : '';

                    Object.keys(this.data).map((k) => {
                        fd.append(k, this.data[k])
                    })
                    // if (this.imageFile) fd.append('image', this.imageFile)

                    this.OnProgress = true;

                    let url = "/save_data";
                    if(this.data.id != '') {
                        url = "/update_data";
                    }

                    axios.post(url, fd)
                    .then(res => {
                        let r = res.data;
                        if(r.message) {
                            console.log(r);
                            $.notify(r.message, "success");
                            this.clearForm();
                            // this.otpMobile = r.mobile;
                            // this.dataPendign = true;
                            this.OnProgress = false;
                        } else if(r.error) {
                            $.notify(r.error, "error");
                            this.OnProgress = false;
                        } else {
                            this.OnProgress = false;
                        }
                    })
                    .catch(err => {
                        let errors = err.response.data.error;
                        if(errors) {
                            errors.forEach(value => {
                                $.notify(value, "error");
                            })
                        }
                        this.OnProgress = false;
                    })
                },

                // phoneVerify() {
                //     if(this.code == '') {
                //         $.notify('Please Enter Your 4 Digit OTP Code!', "error");
                //         return;
                //     }

                //     this.OnProgress = true;

                //     axios.post('/phone_verify_process', {code: this.code})
                //     .then(res => {
                //         let r = res.data;
                //         if(r.message) {
                //             $.notify(r.message, "success");
                //             this.dataPendign = false;
                //             this.code = '';
                //             this.otpMobile = '';
                //             this.OnProgress = false;
                //         } else if(r.error) {
                //             $.notify(r.error, "error");
                //             this.OnProgress = false;
                //         } else {
                //             this.OnProgress = false;
                //         }
                //     })
                //     .catch(err => {
                //         let errors = err.response.data.error;
                //         if(errors) {
                //             errors.forEach(value => {
                //                 $.notify(value, "error");
                //             })
                //         }
                //         this.OnProgress = false;
                //     })
                // },

                clearForm() {
                    this.data = {
                        id: '',
                        name: '',
                        mobile: '019',
                        new_sim: '',
                        new_sim_gift: '',
                        app_install: '',
                        app_install_gift: '',
                        toffee: '',
                        toffee_gift: '',
                        sell_package: '',
                        sell_gb: '',
                        recharge_package: '',
                        recharge_amount: '',
                        voice: '',
                        voice_amount: '',
                        // gift: '',
                        // gift_name: '',
                        area_id: '',
                        location: '',
                        program: '',
                        experience: '',
                        app_experience: '',
                        gaming: '',
                        event: '',
                        service: '',
                        future: '',
                    }
                    
                    // this.image = '';
                    // this.$refs.image.value = '';
                    this.area = {
                        id: null,
                        name: 'select area'
                    }
                }
            }
        })
    </script>
@endpush