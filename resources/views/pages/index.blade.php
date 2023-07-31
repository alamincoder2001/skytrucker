@extends('layouts.master')
@section('title', 'Home')
@section('main-content')
<main>
    <div class="container-fluid">
        <div class="heading-title p-2 my-2">
            <span class="my-3 heading "><i class="fas fa-home"></i> <a class="" href="">Home</a> > Dashboard</span>
        </div>
        <div class="row mt-3 d-flex justify-content-center">
            {{-- <div class="dashboard-logo text-center pt-3 pb-4">
                <img class="border p-2" style="height: 100px;" src="{{ asset('images/dashboard.png') }}" alt="">
        </div> --}}

        @php
        $access = App\Models\UserAccess::where('user_id', auth()->user()->id)
        ->pluck('permissions')
        ->toArray();
        @endphp
        <div class="col-12">
            <div class="row">
                <div class="col-xl-2 col-md-6">
                    <div class="card mb-2 dashboard-card-topper">
                        <a href="">
                            <div class="card-body mx-auto text-center">
                                <i class="fas fa-list" style="font-size: 30px;background: #1c8dff;padding: 7px;color: white;border-radius:50%;"></i>
                                <div class=" d-flex justify-content-center align-items-center">
                                    {{count($areas)}}
                                </div>
                                <p class="dashboard-card-topper-text text-center">Total Areas</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-2 col-md-6">
                    <div class="card mb-2 dashboard-card-topper">
                        <a href="">
                            <div class="card-body mx-auto text-center">
                                <i class="fas fa-users" style="font-size: 30px;background: #1c8dff;padding: 7px;color: white;border-radius:50%;"></i>
                                <div class="d-flex justify-content-center align-items-center">
                                    {{count($users)}}
                                </div>
                                <p class="dashboard-card-topper-text text-center">Total Users</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-2 col-md-6">
                    <div class="card mb-2 dashboard-card-topper">
                        <a href="">
                            <div class="card-body mx-auto text-center">
                                <i class="fas fa-table" style="font-size: 30px;background: #1c8dff;padding: 7px;color: white;border-radius:50%;"></i>
                                <div class=" d-flex justify-content-center align-items-center">
                                    {{count($todayDataList)}}
                                </div>
                                <p class="dashboard-card-topper-text text-center">Today Data</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card mb-2 dashboard-card-topper">
                        <a href="">
                            <div class="card-body mx-auto text-center">
                                <i class="fas fa-table" style="font-size: 30px;background: #1c8dff;padding: 7px;color: white;border-radius:50%;"></i>
                                <div class=" d-flex justify-content-center align-items-center">
                                    {{count($monthDataList)}}
                                </div>
                                <p class="dashboard-card-topper-text text-center">Monthly Data</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card mb-2 dashboard-card-topper">
                        <a href="">
                            <div class="card-body mx-auto text-center">
                                <i class="fas fa-table" style="font-size: 30px;background: #1c8dff;padding: 7px;color: white;border-radius:50%;"></i>
                                <div class=" d-flex justify-content-center align-items-center">
                                    {{count($yearDataList)}}
                                </div>
                                <p class="dashboard-card-topper-text text-center">Yearly Data</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <hr>
        </div>

        @if(in_array('activities.data entry', $access))
        <div class="col-xl-3 col-md-6">
            <div class="card mb-3 dashboard-card">
                <a href="{{ route('data.index') }}">
                    <div class="card-body mx-auto">
                        <div class=" d-flex justify-content-center align-items-center">
                            <i class="fas fa-address-card fa-2x"></i>
                        </div>
                        <p class="dashboard-card-text text-center">Data Entry</p>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if(in_array('activities.data list', $access))
        <div class="col-xl-3 col-md-6">
            <div class="card mb-3 dashboard-card">
                <a href="{{ route('data.list') }}">
                    <div class="card-body mx-auto">
                        <div class=" d-flex justify-content-center align-items-center">
                            <i class="far fa-list-alt fa-2x"></i>
                        </div>
                        <p class="dashboard-card-text text-center">Data List</p>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if(in_array('activities.picture entry', $access))
        <div class="col-xl-3 col-md-6">
            <div class="card mb-3 dashboard-card">
                <a href="{{ route('take.picture') }}">
                    <div class="card-body mx-auto">
                        <div class=" d-flex justify-content-center align-items-center">
                            <i class="far fa-image fa-2x"></i>
                        </div>
                        <p class="dashboard-card-text text-center">Take Picture</p>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if(in_array('activities.picture list', $access))
        <div class="col-xl-3 col-md-6">
            <div class="card mb-3 dashboard-card">
                <a href="{{ route('picture.list') }}">
                    <div class="card-body mx-auto">
                        <div class=" d-flex justify-content-center align-items-center">
                            <i class="far fa-list-alt fa-2x"></i>
                        </div>
                        <p class="dashboard-card-text text-center">Picture List</p>
                    </div>
                </a>
            </div>
        </div>
        @endif

        {{-- @if(in_array('settings.company content', $access)) --}}
        <div class="col-xl-3 col-md-6">
            <div class="card mb-3 dashboard-card">
                <a href="https://ipc-eu.ismartlife.me/login" target="_blank">
                    <div class="card-body mx-auto">
                        <div class=" d-flex justify-content-center align-items-center">
                            <i class="fas fa-camera fa-2x"></i>
                        </div>
                        <p class="dashboard-card-text text-center">IP Camera</p>
                    </div>
                </a>
            </div>
        </div>
        {{-- @endif --}}

        @if(in_array('settings.area entry', $access))
        <div class="col-xl-3 col-md-6">
            <div class="card mb-3 dashboard-card">
                <a href="{{ asset('public/software/skytracker-app.apk') }}" download>
                    <div class="card-body mx-auto">
                        <div class=" d-flex justify-content-center align-items-center">
                            <i class="fas fa-mobile-alt fa-2x"></i>
                        </div>
                        <p class="dashboard-card-text text-center">Sky Tracker App</p>
                    </div>
                </a>
            </div>
        </div>
        @endif

        <!--@if(in_array('settings.company content', $access))-->
        <!--<div class="col-xl-3 col-md-6">-->
        <!--    <div class="card mb-3 dashboard-card">-->
        <!--        <a href="{{ asset('public/software/EzvizStudioSetups.exe') }}" download>-->
        <!--            <div class="card-body mx-auto">-->
        <!--                <div class=" d-flex justify-content-center align-items-center">-->
        <!--                    <i class="fas fa-camera fa-2x"></i>-->
        <!--                </div>-->
        <!--                <p class="dashboard-card-text text-center">Camera App Download</p>-->
        <!--            </div>-->
        <!--        </a>-->
        <!--    </div>-->
        <!--</div>-->
        <!--@endif-->

        @if(in_array('administration.user register', $access))
        <div class="col-xl-3 col-md-6">
            <div class="card mb-3 dashboard-card">
                <a href="{{ route('user.registration') }}">
                    <div class="card-body mx-auto">
                        <div class=" d-flex justify-content-center align-items-center">
                            <i class="fas fa-user-plus fa-2x"></i>
                        </div>
                        <p class="dashboard-card-text text-center">User Registration</p>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if(in_array('administration.user list', $access))
        <div class="col-xl-3 col-md-6">
            <div class="card mb-3 dashboard-card">
                <a href="{{ route('user.list') }}">
                    <div class="card-body mx-auto">
                        <div class=" d-flex justify-content-center align-items-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <p class="dashboard-card-text text-center">Users list</p>
                    </div>
                </a>
            </div>
        </div>
        @endif

        <div class="col-xl-3 col-md-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <div class="card mb-3 dashboard-card">
                    <div class="card-body mx-auto">
                        <div class=" d-flex justify-content-center align-items-center">
                            <i class="fa fa-sign-out-alt fa-2x"></i>
                        </div>
                        <p class="dashboard-card-text">
                            <button type="submit" style="border:none; background: none;font-weight: 500;">Sign Out</button>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
</main>
<script>
    function RunFile() {
        WshShell = new ActiveXObject("WScript.Shell");
        WshShell.Run("C:/windows/system32/calc.exe", 1, false);
    }
</script>
@endsection