@extends('layouts.app')
@section('title', 'Messages')

@section('content')
    <div class="container">
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
                        {{-- <div class="col-md-6 col-lg-5 col-xl-4 mb-4 mb-md-0">

                          <div class="p-3">

                            <div class="input-group rounded mb-3">
                              <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search"
                                aria-describedby="search-addon" />
                              <span class="input-group-text border-0" id="search-addon">
                                <i class="fas fa-search"></i>
                              </span>
                            </div>

                            <div data-mdb-perfect-scrollbar="true" style="position: relative; height: 400px">
                              <ul class="list-unstyled mb-0">
                                <li class="p-2 border-bottom">
                                  <a href="#!" class="d-flex justify-content-between">
                                    <div class="d-flex flex-row">
                                      <div>
                                        <img
                                          src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava1-bg.webp"
                                          alt="avatar" class="d-flex align-self-center me-3" width="60">
                                        <span class="badge bg-success badge-dot"></span>
                                      </div>
                                      <div class="pt-1">
                                        <p class="fw-bold mb-0">Marie Horwitz</p>
                                        <p class="small text-muted">Hello, Are you there?</p>
                                      </div>
                                    </div>
                                    <div class="pt-1">
                                      <p class="small text-muted mb-1">Just now</p>
                                      <span class="badge bg-danger rounded-pill float-end">3</span>
                                    </div>
                                  </a>
                                </li>
                                <li class="p-2 border-bottom">
                                  <a href="#!" class="d-flex justify-content-between">
                                    <div class="d-flex flex-row">
                                      <div>
                                        <img
                                          src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava2-bg.webp"
                                          alt="avatar" class="d-flex align-self-center me-3" width="60">
                                        <span class="badge bg-warning badge-dot"></span>
                                      </div>
                                      <div class="pt-1">
                                        <p class="fw-bold mb-0">Alexa Chung</p>
                                        <p class="small text-muted">Lorem ipsum dolor sit.</p>
                                      </div>
                                    </div>
                                    <div class="pt-1">
                                      <p class="small text-muted mb-1">5 mins ago</p>
                                      <span class="badge bg-danger rounded-pill float-end">2</span>
                                    </div>
                                  </a>
                                </li>
                                <li class="p-2 border-bottom">
                                  <a href="#!" class="d-flex justify-content-between">
                                    <div class="d-flex flex-row">
                                      <div>
                                        <img
                                          src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3-bg.webp"
                                          alt="avatar" class="d-flex align-self-center me-3" width="60">
                                        <span class="badge bg-success badge-dot"></span>
                                      </div>
                                      <div class="pt-1">
                                        <p class="fw-bold mb-0">Danny McChain</p>
                                        <p class="small text-muted">Lorem ipsum dolor sit.</p>
                                      </div>
                                    </div>
                                    <div class="pt-1">
                                      <p class="small text-muted mb-1">Yesterday</p>
                                    </div>
                                  </a>
                                </li>
                                <li class="p-2 border-bottom">
                                  <a href="#!" class="d-flex justify-content-between">
                                    <div class="d-flex flex-row">
                                      <div>
                                        <img
                                          src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava4-bg.webp"
                                          alt="avatar" class="d-flex align-self-center me-3" width="60">
                                        <span class="badge bg-danger badge-dot"></span>
                                      </div>
                                      <div class="pt-1">
                                        <p class="fw-bold mb-0">Ashley Olsen</p>
                                        <p class="small text-muted">Lorem ipsum dolor sit.</p>
                                      </div>
                                    </div>
                                    <div class="pt-1">
                                      <p class="small text-muted mb-1">Yesterday</p>
                                    </div>
                                  </a>
                                </li>
                                <li class="p-2 border-bottom">
                                  <a href="#!" class="d-flex justify-content-between">
                                    <div class="d-flex flex-row">
                                      <div>
                                        <img
                                          src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava5-bg.webp"
                                          alt="avatar" class="d-flex align-self-center me-3" width="60">
                                        <span class="badge bg-warning badge-dot"></span>
                                      </div>
                                      <div class="pt-1">
                                        <p class="fw-bold mb-0">Kate Moss</p>
                                        <p class="small text-muted">Lorem ipsum dolor sit.</p>
                                      </div>
                                    </div>
                                    <div class="pt-1">
                                      <p class="small text-muted mb-1">Yesterday</p>
                                    </div>
                                  </a>
                                </li>
                                <li class="p-2">
                                  <a href="#!" class="d-flex justify-content-between">
                                    <div class="d-flex flex-row">
                                      <div>
                                        <img
                                          src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava6-bg.webp"
                                          alt="avatar" class="d-flex align-self-center me-3" width="60">
                                        <span class="badge bg-success badge-dot"></span>
                                      </div>
                                      <div class="pt-1">
                                        <p class="fw-bold mb-0">Ben Smith</p>
                                        <p class="small text-muted">Lorem ipsum dolor sit.</p>
                                      </div>
                                    </div>
                                    <div class="pt-1">
                                      <p class="small text-muted mb-1">Yesterday</p>
                                    </div>
                                  </a>
                                </li>
                              </ul>
                            </div>

                          </div>

                        </div> --}}
                        {{-- <div class="col-md-auto col-lg-7 col-xl-8"> --}}
                        <div>
                          <div class="p-3 overflow-auto chat" data-mdb-perfect-scrollbar="true">
                            @forelse ($messages as $m)
                                @if ($m['from_id'] === Auth::id())
                                    <div class="d-flex flex-row justify-content-end">
                                        <div>
                                            <p class= "bubble small p-2 me-3 mb-1 text-white rounded-3 bg-primary" style="background-color: #f5f6f7;">{{\Illuminate\Support\Facades\Crypt::decrypt($m['message'])}}</p>
                                            <p class="small me-3 mb-3 rounded-3 text-muted">{{$m['created_at']}}</p>
                                        </div>
                                        @if(str_starts_with(Auth::user()['profile_picture'],"https"))
                                            <img class="rounded-circle shadow-1-strong" src={{Auth::user()['profile_picture']}} alt="{{Auth::user()['username']}} avatar" width="60" height="60"/>
                                        @else
                                            <img class="rounded-circle shadow-1-strong" src="{{URL::asset('storage/'.Auth::user()['profile_picture'])}}" alt="{{Auth::user()['username']}} avatar" width="60" height="60"/>
                                        @endif
                                    </div>
                                @else
                                    <div class="d-flex flex-row justify-content-start">
                                        @if(str_starts_with($otherUser['profile_picture'],"https"))
                                            <img class="rounded-circle shadow-1-strong" src={{$otherUser['profile_picture']}} alt="{{$otherUser['username']}} avatar" width="60" height="60"/>
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
                            {{-- <img src={{Auth::user()['profile_picture']}} class="rounded-circle shadow-1-strong me-3"
                              alt="{{Auth::user()['username']}} profile picture" style="width: 60px; height: 60px;"> --}}

                            <form action="{{ route('users.addMessage', $otherUser['id']) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    {{-- <input type="text" class="form-control form-control-lg @error('message') is-invalid @enderror" id="message" name="message" value="{{ old('message') }}" placeholder="Type message here" aria-describedby="basic-addon2"> --}}
                                    <label class="form-label" for="message"></label>
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
                            {{-- <input type="text" class="form-control form-control-lg" id="exampleFormControlInput2"
                                placeholder="Type message">
                            <a class="ms-1 text-muted" href="#!"><i class="fas fa-paperclip"></i></a>
                            <a class="ms-3 text-muted" href="#!"><i class="fas fa-smile"></i></a>
                            <a class="ms-3" href="#!"><i class="fas fa-paper-plane"></i></a> --}}
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
