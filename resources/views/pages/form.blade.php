@extends('layouts.master')
@section('title', 'DataTable')
@section('main-content')
<main>
    <div class="container-fluid">
        <div class="heading-title p-2 my-2">
            <span class="my-3 heading "><i class="fas fa-home"></i> <a class="" href="">Home</a> > Input Group</span>
        </div>
        <div class="card my-3">
            <div class="card-header d-flex justify-content-between">
                <div class="table-head"><i class="fas fa-user me-1"></i>Input Group Example</div>
                <a href="" class="btn btn-addnew"> <i class="fa fa-file-alt"></i> view all</a>
            </div>
            <div class="card-body table-card-body">
                <div class="row">
                    <form>
                        <div class="row">
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-1 col-form-label">Name</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control form-control-sm" id="inputName">
                                </div>

                                <label for="inputEmail" class="col-sm-1 col-form-label">Email</label>
                                <div class="col-sm-3">
                                    <input type="email" class="form-control form-control-sm" id="inputEmail">
                                </div>

                                <label for="inputPhone" class="col-sm-1 col-form-label">Phone</label>
                                <div class="col-sm-3">
                                    <input type="email" class="form-control form-control-sm" id="inputPhone">
                                </div>

                                <label for="inputAddress" class="col-sm-1 col-form-label">Address</label>
                                <div class="col-sm-3">
                                    <input type="email" class="form-control form-control-sm" id="inputAddress">
                                </div>

                                <label for="inputArea" class="col-sm-1 col-form-label">Area</label>
                                <div class="col-sm-3">
                                    <input type="email" class="form-control form-control-sm" id="inputArea">
                                </div>

                                <label for="inputSelect" class="col-sm-1 col-form-label">Select</label>
                                <div class="col-sm-3">
                                    <select name="select" class="form-control form-control-sm" id="inputSelect">
                                        <option value="">--- select one ---</option>
                                        <option value="">select-1</option>
                                        <option value="">select-2</option>
                                        <option value="">select-3</option>
                                    </select>
                                </div>

                                <label for="inputDisable" class="col-sm-1 col-form-label">Disable</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control form-control-sm" id="inputDisable">
                                </div>

                                <label for="inputPassword" class="col-sm-1 col-form-label">Password</label>
                                <div class="col-sm-3">
                                    <input type="password" class="form-control form-control-sm" id="inputPassword">
                                </div>

                                <label for="inputCpassword" class="col-sm-1 col-form-label">C-Password</label>
                                <div class="col-sm-3">
                                    <input type="email" class="form-control form-control-sm" id="inputCpassword">
                                </div>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="clearfix">
                            <div class="text-end m-auto">
                                <button type="reset" class="btn btn-dark">Reset</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>    
                </div>
            </div>
        </div>  
    </div>
</main>
@endsection