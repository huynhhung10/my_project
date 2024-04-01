@extends('admin_layout')
@section('admin_content')

    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
          <div class="car"></div>
          <div class="card mb-4">
            <div class="card-header"><strong>Danh sách</strong><span class="small ms-1">User</span></div>
            <div class="card-body">
            <a href="{{URL::to('/add-user')}}" class="btn btn-primary active" aria-pressed="true" style="background-color: green; float:right">Thêm user</a>
            <div class="col-md-4">  
                <input type="text" class="form-control" id="inputZip" placeholder="Tìm kiếm">
            </div>

          
                
              <div class="example">
             
                <div class="tab-content rounded-bottom">
                  <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-387">
                  <table class="table">
                      <thead style="background-color: #C9C4C3 ">
                        <tr>
                          <th>Stt</th>
                          <th></th>
                          <th>username</th>
                          <th>email</th>
                        
                          <th>Role</th>
                          <th></th>
                          <th style="float: center; padding: 7px 35px">Thao tác</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th scope="row">1</th>
                          <td><div class="avatar avatar-md"><img class="avatar-img" src="{{('backend/assets/img/avatars/8.jpg')}} " alt="user@email.com"></div></td>
                          <td>Mark</td>
                       
                         
                          <td>@mdo</td>
                          
                          <td>
                          <div class="col-md-6">
                                  <select id="inputState" class="form-select">
                                    <option selected>quyen</option>
                                    <option>...</option>
                                  </select>
                                  
                          </div>
                          <td><button type="button"class="btn btn-success">Cập nhật</button></td>
                          <td>
                            <span class="text-ellipsis">
                            <a href="#" class="btn btn-primary active" role="button" data-coreui-toggle="button" aria-pressed="true" style="background-color: #5DADE2;border: black ">sửa</a>
                            |
                            <a href="#" class="btn btn-primary active" role="button" data-coreui-toggle="button" aria-pressed="true" style="background-color: #E74C3C; border: black">xóa</a>
                            </span>
                          </td>
                        </tr>
                        
                      </tbody>
                    </table>
                  </div>
                </div>

                
              </div>

              
            </div>
            
          </div>
          <nav aria-label="Page navigation example" style="float:right">
            <ul class="pagination">
               <li class="page-item">
                  <a class="page-link" href="#" aria-label="Previous" style="float:right">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                  <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
            </ul>
          </nav>
          
        </div>
        
    </div>

    
@endsection