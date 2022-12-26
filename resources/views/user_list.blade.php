<!-- resources/views/child.blade.php -->

@extends('layouts.app')

@section('title', '회원목록')

@section('sidebar')
    @parent
@endsection

@section('content')
    <div class="content bg-white">

            <!-- Table Start -->
                    <div class="col-12 bg-white px-4 py-0" >
                        <h6 class="mb-4" style="margin:30px 10px;">회원 목록</h6>
                    </div>
                    <div class="col-12 bg-white px-4 py-0" style="float:left;">
                        <select class="form-select search_type mb-1" id="search_type" aria-label=".search_type" style="width:auto;float:left;margin-right:10px;">
                            <option selected value="">전체</option>
                            <option value="name">호텔 이름</option>
                        </select>
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
                                        <th scope="col">허용</th>
                                    </tr>
                                </thead>
                                <tbody id="data_table">
                                    @forelse($list->data as $data)
                                        <tr>
                                            <td>{{ $data->user_idx }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->email }}</td>
                                            <td>{{ $data->organization }}</td>
                                            <td></td>
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


                   

                </div>
            </div>
            <!-- Table End -->
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <script>
       /* $().ready(function(){
            $("#search_type").val($("#temp_search_type").val()).prop("selected", true);
        });
        
        const get_list = function(page_no){
            const search_type = $("#search_type").val();
            const start_date = $("#datePicker-start").val();
            const end_date = $("#datePicker-end").val();
            const search_keyword = $("#search_keyword").val();
            $url = '/hotel_list?page_no='+page_no+'&start_date='+start_date+'&end_date='+end_date+'&search_type='+search_type+'&search_keyword='+search_keyword;
            window.location.replace($url);
            
        }*/
    </script>

@endsection