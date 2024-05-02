@extends('layouts.app')
@section('title', 'Messages')

@section('content')
    <div class="container bg-light p-4">
        @if(Session::has('message_sent'))
            <div class="alert alert-success mb-4" role="alert">
                Message sent!
            </div>
        @endif
        <h1 class="mb-4">Messages with <a href="{{route('users.show', [$otherUser])}}" class="text-decoration-none">{{$otherUser['username']}}</a></h1>
        <section style="background-color: #0D6EFD;">
            <div class="container py-5">
              <div class="row">
                <div class="col-md-12">
                  <div class="card" id="chat2" style="border-radius: 15px;">
                    <div class="card-body">
                      <div class="row">
                        <div>
                          <div class="p-3 overflow-auto chat" data-mdb-perfect-scrollbar="true">
                            @forelse ($messages as $m)
                                @if ($m['from_id'] === Auth::id())
                                    <div class="d-flex flex-row justify-content-end">
                                        <div>
                                            <p class= "bubble small p-2 me-3 mb-1 text-white rounded-3 bg-primary" style="background-color: #f5f6f7;">{{\Illuminate\Support\Facades\Crypt::decrypt($m['message'])}}</p>
                                            <p class="small me-3 mb-3 rounded-3 text-muted">{{$m['created_at']}}</p>
                                        </div>
                                        @if(str_starts_with(Auth::user()['profile_picture'],"placeholder"))
                                            <img class="rounded-circle shadow-1-strong" src={{url(Auth::user()['profile_picture'])}} alt="{{Auth::user()['username']}} avatar" width="60" height="60"/>
                                        @else
                                            <img class="rounded-circle shadow-1-strong" src="{{URL::asset('storage/'.Auth::user()['profile_picture'])}}" alt="{{Auth::user()['username']}} avatar" width="60" height="60"/>
                                        @endif
                                    </div>
                                @else
                                    <div class="d-flex flex-row justify-content-start">
                                        @if(str_starts_with($otherUser['profile_picture'],"placeholder"))
                                            <img class="rounded-circle shadow-1-strong" src={{url($otherUser['profile_picture'])}} alt="{{$otherUser['username']}} avatar" width="60" height="60"/>
                                        @else
                                            <img class="rounded-circle shadow-1-strong" src="{{URL::asset('storage/'.$otherUser['profile_picture'])}}" alt="{{$otherUser['username']}} avatar" width="60" height="60"/>
                                        @endif
                                        <div>
                                            <p class="bubble small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">{{\Illuminate\Support\Facades\Crypt::decrypt($m['message'])}}</p>
                                            <p class="small ms-3 mb-3 rounded-3 text-muted float-end">{{$m['created_at']}}</p>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-warning" role="alert">
                                        No messages yet!
                                    </div>
                                </div>
                            @endforelse
                          </div>
                        </div>
                        <hr>
                          <div class="text-muted justify-content-start align-items-center px-4 mt-1">
                            <form action="{{ route('users.addMessage', $otherUser['id']) }}" method="POST" enctype="multipart/form-data">
                                <label class="form-label d-flex justify-content-end align-items-end pe-3" for="message" id="counter">0 / 2000</label>
                                @csrf
                                <div class="input-group">
                                    <textarea class="form-control form-control-lg @error('message') is-invalid @enderror" id="message" name="message" rows="4"
                                    style="background: #fff;" placeholder="Type message here (max 2000 characters)"></textarea>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane fa-xl"></i></button>
                                    @error('message')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/messageCounter.js')}}"></script>
@endsection
