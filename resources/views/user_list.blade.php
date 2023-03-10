<!-- resources/views/child.blade.php -->

@extends('layouts.app')

@section('title', '로그인 회원목록')

@section('sidebar')
    @parent
@endsection

@section('content')
    <div class="content bg-white">

            <!-- Table Start -->
                    <div class="col-12 bg-white px-4 py-0" >
                        <h6 class="mb-4" style="margin:30px 10px;">회원 목록</h6>
                    </div>

                    <div class="col-12">
                        <div class="bg-white rounded h-100 p-4">

                            <table class="table table-bordered" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th scope="col">유저번호</th>
                                        <th scope="col">이름</th>
                                        <th scope="col">이메일</th>
                                        <th scope="col">소속</th>
                                        <th scope="col">사용 테이블 수</th>
                                    </tr>
                                </thead>
                                <tbody id="data_table">
                                    @forelse($list->data as $data)
                                        <tr>
                                            <td>{{ $data->user_idx }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->email }}</td>
                                            <td>{{ $data->organization }}</td>
                                            <td><a style="cursor:pointer" onclick="go_table_list({{ $data->user_idx }})">{{ $data->table_cnt }}</a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" align="center">조회된 회원이 없습니다.</td>
                                        </tr>
                                    @endforelse   

                                </tbody>
                            </table>
                        </div>
                    </div>


                    <nav aria-label="Page navigation" style="width:100%;">
                        <ul class="pagination" style="width:300px;margin:auto;">

                        @for($i= 1; $i < $list->total_page; $i++)
                            @if($i == $list->page_no)
                                <li class="page-item active"><a class="page-link" href="#" onclick="get_list({{$i}})" value="'{{$i}}'">{{$i}}</a></li>
                            @else
                                <li class="page-item"><a class="page-link" href="#" onclick="get_list({{$i}})" value="'{{$i}}'">{{$i}}</a></li>
                            @endif
                        @endfor

                        </ul>
                    </nav>

                </div>
            </div>
            <!-- Table End -->
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <script>
        $().ready(function(){
            $("#search_type").val($("#temp_search_type").val()).prop("selected", true);
        });
        
        const get_list = function(page_no){
            $url = '/user_list?page_no='+page_no;
            window.location.replace($url);
            
        }

        const go_table_list = function(user_idx){
            $url = '/table_list?search_type=user_idx&search_keyword='+user_idx;
            window.location.replace($url);
            
        }
    </script>

@endsection