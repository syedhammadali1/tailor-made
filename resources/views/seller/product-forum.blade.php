@extends('seller.layouts.app')

@section('panel_content')
    <div class="card">
        <div class="modal fade" id="product_forum_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-zoom forum-modal" id="modal-size" role="document">
                <div class="modal-content position-relative">
                    <div class="modal-header">
                        <h5 class="modal-title fw-600 h5">{{ translate('Answer About This Product')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="" action="{{route('seller.forum.add')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                        <input type="hidden" name="seller_id" value="{{ $detailedProduct->user_id }}"> --}}
                        <div class="modal-body gry-bg px-3 pt-3">
                            <input type="hidden" name="forum_id" value="" id="forum_id">
                            <div class="form-group">
                                <textarea required class="form-control" rows="8" name="answer" id="ans_textbox" required placeholder="{{ translate('Your Answer') }}"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary fw-600" data-dismiss="modal">{{ translate('Cancel')}}</button>
                            <button type="submit" class="btn btn-primary fw-600">{{ translate('Send')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Product Forum') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Product')}}</th>
                        <th data-breakpoints="lg">{{ translate('Customer')}}</th>
                     
                        <th data-breakpoints="lg">{{ translate('Question')}}</th>
                        <th data-breakpoints="lg">{{ translate('Answer')}}</th>
                        <th data-breakpoints="lg">{{ translate('Action')}}</th>
                   
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productforum as $key => $value)

                

                        @if($value != null)
                            <tr>
                                <td>
                                    {{ $value->id ?? '-'}}
                                </td>
                                <td>
                                    {{ $value->product->name ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->user->name ?? '-' }}
                                </td>
                              
                                <td>
                                    {{ $value->question ?? '-' }}
                                </td>

                                <td>
                                    {{ $value->answer ?? '-' }}
                                </td>

                                <td>
                                    <div class="col-auto">
                                        <button class="btn btn-sm btn-soft-primary" onclick="show_product_forum_modal( '{{$value->id ?? '-'}}' , '{{$value->answer ?? '-'}}')">{{ translate('Answer')}}</button>
                                    </div>
                                </td>
                             
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
          
        </div>
    </div>

    
@endsection
<script> 

        function show_product_forum_modal(forum_id,answer){

            $('#forum_id').val(forum_id);

            $('#ans_textbox').text(answer);

            @if (Auth::check())
                $('#product_forum_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }


</script>