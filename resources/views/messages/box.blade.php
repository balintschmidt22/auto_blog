@extends('layouts.app')
@section('title', 'Messages')

@section('content')
    <div class="container bg-light p-4">
        <h1 class="mb-4">Messages</h1>
        <section style="background-color: #0D6EFD;">
            <div class="container py-5">
              <div class="row">
                <div class="col-md-12">
                  <div class="card" id="chat2" style="border-radius: 15px;">
                    <div class="card-body">
                      <div class="row">

                        <div class="col-md-6 col-lg-6 col-xl-6 mb-4 mb-md-0">
                          <div class="p-3">
                            <h2>Received - {{count($received)}}</h2>
                            <div class="p-3 overflow-auto box" data-mdb-perfect-scrollbar="true" id="messages">
                              <ul class="list-unstyled mb-0">
                                @forelse ($received as $r)
                                    <li class="p-2 border-bottom">
                                        <div class="d-flex flex-row">
                                            <div>
                                                @if(str_starts_with(App\Models\User::find($r['from_id'])['profile_picture'],"placeholder"))
                                                    <img class="rounded-circle shadow-1-strong d-flex align-self-center me-3" src={{url(App\Models\User::find($r['from_id'])['profile_picture'])}} alt="{{App\Models\User::find($r['from_id'])['username']}} avatar" width="60" height="60"/>
                                                @else
                                                    <img class="rounded-circle shadow-1-strong d-flex align-self-center me-3" src="{{URL::asset('storage/'.App\Models\User::find($r['from_id'])['profile_picture'])}}" alt="{{App\Models\User::find($r['from_id'])['username']}} avatar" width="60" height="60"/>
                                                @endif
                                            </div>
                                            <div class="pt-1">
                                                <p class="fw-bold mb-0"><a href="{{route('users.show', [$r['from_id']])}}" class="text-decoration-none">{{App\Models\User::find($r['from_id'])['username']}}</a></p>
                                                <a href="{{route('users.message', ['id'=>$r['from_id']])}}" class="d-flex text-decoration-none">
                                                    <p class="small text-muted bubble three-lines">{{\Illuminate\Support\Facades\Crypt::decrypt($r['message'])}}</p>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="pt-1">
                                            <p class="small text-muted mb-0">{{$r['updated_at']}}</p>
                                        </div>
                                    </li>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-warning" role="alert">
                                            Received message box is currently empty!
                                        </div>
                                    </div>
                                @endforelse
                              </ul>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-6 col-xl-6 mb-4 mb-md-0">
                            <div class="p-3">
                                <h2>Sent - {{count($sent)}}</h2>
                                <div class="p-3 overflow-auto box" data-mdb-perfect-scrollbar="true" id="messages">
                                  <ul class="list-unstyled mb-0">
                                    @forelse ($sent as $s)
                                        <li class="p-2 border-bottom">
                                            <div class="d-flex flex-row">
                                                <div>
                                                    @if(str_starts_with(App\Models\User::find($s['to_id'])['profile_picture'],"placeholder"))
                                                        <img class="rounded-circle shadow-1-strong d-flex align-self-center me-3" src={{url(App\Models\User::find($s['to_id'])['profile_picture'])}} alt="{{App\Models\User::find($s['to_id'])['username']}} avatar" width="60" height="60"/>
                                                    @else
                                                        <img class="rounded-circle shadow-1-strong d-flex align-self-center me-3" src="{{URL::asset('storage/'.App\Models\User::find($s['to_id'])['profile_picture'])}}" alt="{{App\Models\User::find($s['to_id'])['username']}} avatar" width="60" height="60"/>
                                                    @endif
                                                </div>
                                                <div class="pt-1">
                                                    <p class="fw-bold mb-0"><a href="{{route('users.show', [$s['to_id']])}}" class="text-decoration-none">{{App\Models\User::find($s['to_id'])['username']}}</a></p>
                                                    <a href="{{route('users.message', ['id'=>$s['to_id']])}}" class="d-flex text-decoration-none">
                                                        <p class="small text-muted bubble three-lines">{{\Illuminate\Support\Facades\Crypt::decrypt($s['message'])}}</p>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="pt-1">
                                                <p class="small text-muted mb-0">{{$s['updated_at']}}</p>
                                            </div>
                                        </li>
                                    @empty
                                        <div class="col-12">
                                            <div class="alert alert-warning" role="alert">
                                                Sent message box is currently empty!
                                            </div>
                                        </div>
                                    @endforelse
                                  </ul>
                                </div>
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
