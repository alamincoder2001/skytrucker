@extends('layouts.master')
@section('title', 'User Access')
@section('main-content')
<div class="content">
    <div class="container-fluid">
        <div class="heading-title p-2 my-2">
            <span class="my-3 heading "><i class="fas fa-tachometer-alt"></i> <a class="" href="{{ route('dashboard') }}">Dashboard</a> > User Permission</span>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card my-2">
                    <div class="card-header d-flex justify-content-between">
                        <div class="table-head"><i class="fas fa-user-edit"></i> Add User Permission</div>
                        {{-- <a href="{{ route('user.registration') }}" class="btn btn-info px-3">
                            Users List
                        </a> --}}
                    </div>
                    <div class="card-body table-card-body">

                        @if (session('message'))
                            <div class="alert alert-{{ session('type') }}">{{ session('message') }}</div>
                        @endif

                        <form action="{{ route('store.permission') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="text-center">User Name: <?= $user->name ?> </label>
                                </div>
                            </div>
                            <input type="hidden" name="user_id" value="<?= $user->id ?>">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="2">
                                        <input type="checkbox" id="all" value="1">
                                        <label for="all">All</label>
                                    </td>
                                </tr>
                                @foreach ($group_name as $group)
                                    <tr>
                                        <td>
                                            <input type="checkbox" id="role-{{ $group }}"
                                                value="{{ $group }}" onclick="selectGroup(this.id)">
                                            <label for="role-{{ $group }}">{{ $group }}</label>
                                        </td>
                                        <td class="role-{{ $group }}">
                                            @foreach ($permissions as $item)
                                                @if ($group == $item->group_name)
                                                    <input type="checkbox" name="permissions[]"
                                                        id="{{ $item->permissions }}" value="{{ $item->id }}"
                                                        {{ in_array($item->permissions, $userAccess) ? 'checked' : '' }}>
                                                    <label
                                                        for="{{ $item->permissions }}">{{ $item->permissions }}</label><br>
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <div class="text-end">
                                <button type="submit" class="btn btn-submit shadow-none">Save Permissions</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('script')
    <script>
        $('#all').on('click', function() {
            if ($(this).is(':checked')) {
                $('input[type=checkbox').prop('checked', true);
            } else {
                $('input[type=checkbox').prop('checked', false);
            }
        })

        function selectGroup(className) {
            const checkbox = $('.' + className + ' input');
            console.log(checkbox);
            if ($('#' + className).is(':checked')) {
                checkbox.prop('checked', true);
            } else {
                checkbox.prop('checked', false);
            }
        }
    </script>
@endpush
