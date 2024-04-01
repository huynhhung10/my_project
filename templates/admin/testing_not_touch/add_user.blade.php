@extends('admin_layout')
@section('admin_content')
<div class="body flex-grow-1 px-3">
        <div class="container-lg">
          <div class="car"></div>
            <div class="card mb-4">
                <div class="card-header"><strong>Thêm</strong><span class="small ms-1">Người dùng</span></div>
                    <div class="card-body">
                        <form class="row g-3" style="padding:20px 20px;">
                            <div class="col-md-4">
                                <label for="inputEmail4" class="form-label">Tên người dùng</label>
                                <input type="text" class="form-control" id="inputAddress">
                            </div>
                            <div class="col-md-4">
                                <label for="inputPassword4" class="form-label">Email</label>
                                <input type="email" class="form-control" id="inputAddress">
                            </div>

                            <div class="col-md-4">
                                <label for="inputPassword4" class="form-label">SĐT</label>
                                <input type="text" class="form-control" id="inputAddress">
                            </div>

                            <div class="col-md-6">
                                <label for="inputPassword4" class="form-label">Username</label>
                                <input type="text" class="form-control" id="inputAddress">
                            </div>
                            <div class="col-md-6">
                                <label for="inputPassword4" class="form-label">Password</label>
                                <input type="password" class="form-control" id="inputAddress">
                            </div>
                            <label for="inputAddress" class="form-label">Avatar</label>
                            <div class="input-group mb-3">
                                
                             
                                <input type="file" class="form-control" id="inputGroupFile01">
                            </div>
                        
                               
                            <div class="col-md-4">
                                <label for="inputState" class="form-label">Quyền</label>
                                <select id="inputState" class="form-select">
                                <option selected>Choose...</option>
                                <option>...</option>
                                </select>
                            </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary" style="background-color: green">Thêm user</button>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection