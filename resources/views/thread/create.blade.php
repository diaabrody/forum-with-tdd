@extends("layouts.app")
@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="errors row">
             <div class="alert alert-danger col-md-12">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
             </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="card">
                    <div class="card-header">Create a New Thread</div>
                    <div class="card-body">
                        <form action="/threads" method="post">
                            @csrf
                            <div class="form-group">
                                    <label for="title">Title:</label>
                                    <input required type="text" class="form-control" id="title" name="title">
                            </div>
                                <div class="form-group">
                                    <label for="body">Body:</label>
                                    <textarea required class="form-control" id="body" name="body"  rows="8"></textarea>
                                </div>
                            <div class="form-group">
                                <label for="channel_id">channels:</label>
                                <select name="channel_id" id="channel_id"  class="form-control" required>
                                    <option value="">select One</option>
                                    @foreach(App\Channel::all() as $channel)
                                        <option value="{{$channel->id}}">{{$channel->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                          <input type="submit"  class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
