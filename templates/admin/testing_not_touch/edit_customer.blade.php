@extends('admin_layout')
@section('admin_content')
<div class="body flex-grow-1 px-3">
        <div class="container-lg">
          <div class="car"></div>
            <div class="card mb-4">
                <div class="card-header"><strong>Sửa</strong><span class="small ms-1">khách hàng</span></div>
                    <div class="card-body">
                        <form  action="{{URL::to('/admin/posteditcustomer')}}" method="post" enctype="multipart/form-data" class="row g-3" style="padding:20px 20px;">
                            @csrf
                            <input type="hidden" value="{{$customer->customer_id}}" name="customer_id">
                            <div class="col-md-4">
                                <label for="inputEmail4" class="form-label">Tên khách hàng</label>
                                <input type="text" name="customer_name" value="{{$customer->customer_name}}" class="form-control" id="customer_name">
                            </div>
                            <div class="col-md-4">
                                <label for="inputPassword4" class="form-label">Email</label>
                                <input type="email" name="email" value="{{$customer->email}}" class="form-control" id="Email">
                            </div>

                            <div class="col-md-4">
                                <label for="inputPassword4" class="form-label">SĐT</label>
                                <input type="text" name="customer_phone" value="{{$customer->customer_phone}}" class="form-control" id="Phone">
                            </div>

                            <div class="col-md-6">
                                <label for="inputPassword4" class="form-label">Username</label>
                                <input type="text" name="customer_username" value="{{$customer->customer_username}}" class="form-control" id="Username">
                            </div>
                            <div class="col-md-6">
                                <label for="inputPassword4" class="form-label">Password</label>
                                <input type="password" name="password" value="{{$customer->password}}" class="form-control" id="Pass">
                            </div>
                            <label for="inputAddress" class="form-label">Avatar</label>
                            <img src="{{asset('/backend/assets/img/avatars/')}}/{{$customer->customer_avatar}}" alt="{{$customer->customer_avatar}}" class="avatar-img" style="width: 70px">
                            <div class="input-group mb-3">
                                
                               
                                <input type="file" name="avatar" class="form-control" id="avatar">
                            </div>

                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" @if($customer->status == 1) checked  @endif id="gridCheck" name="status">
                                <label class="form-check-label" for="gridCheck">
                                    Khóa
                                </label>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary" style="background-color: green">Sửa khách hàng</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>

  $('.btn-primary').on('click', function (event) {
    event.preventDefault();
    const url = $(this).attr('href');
    swal({
        title: 'Bạn có muốn lưu thay đổi?',
        text: 'This record and it`s details will be permanantly deleted!',
        icon: 'warning',
        buttons: ["Cancel", "Yes!"],
    }).then(function(value) {
        if (value) {
            window.location.href = url;
        }
    });
});
  
</script>