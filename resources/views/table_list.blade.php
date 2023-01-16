<!-- resources/views/child.blade.php -->

@extends('layouts.app')

@section('title', '테이블 목록')

@section('sidebar')
    @parent
@endsection

@section('content')
    <div class="content bg-white">

            <!-- Table Start -->
                    <div class="col-12 bg-white px-4 py-0" >
                        <h6 class="mb-4" style="margin:30px 10px;">테이블 사용 현황</h6>
                    </div>

                    <div class="col-12 bg-white px-4 py-0" style="float:left;">
                        <select class="form-select search_type mb-1" id="search_type" aria-label=".search_type" style="width:auto;float:left;margin-right:10px;">
                            <option selected value="">전체</option>
                            <option value="name">테이블 이름</option>
                            <option value="user_idx">사용자 번호</option>
                        </select>
                        <input type="hidden" id="temp_search_type" value="{{$list->search_type}}"/>
                        <input class="form-control border-1" id="search_keyword" type="search" placeholder="Search" value="{{$list->search_keyword}}" style="width:30%;float:left;margin-right:\10px;">
                        <button type="button" class="btn btn-outline-secondary m-2" id="btn_search" style="width:auto;float:left;margin:0px;" onclick="get_list(1)" >검색</button>
                    </div>

                    <div class="col-12">
                        <div class="bg-white rounded h-100 p-4">

                            <table class="table table-bordered" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th scope="col">테이블번호</th>
                                        <th scope="col">테이블 이름</th>
                                        <th scope="col">필드 수</th>
                                        <th scope="col">레코드 수</th>
                                        <th scope="col">사용자</th>
                                    </tr>
                                </thead>
                                
                                <tbody id="data_table">
                                    @forelse($list->data as $data)
                                        <tr>
                                            <td>{{ $data->table_idx }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->field_count }}</td>
                                            <td>{{ $data->record_count }}</td>
                                            <td>{{ $data->user_cnt }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" align="center">조회된 테이블이 없습니다.</td>
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
            $("#sale").val("{{$list->sale}}").prop("selected", true);
        });
        
        const get_list = function(page_no){
            const search_type = $("#search_type").val();
            const search_keyword = $("#search_keyword").val();
            
            $url = '/table_list?page_no='+page_no+'search_type='+search_type+'&search_keyword='+search_keyword;
            
            window.location.replace($url);
            
        }
    </script>

@endsection