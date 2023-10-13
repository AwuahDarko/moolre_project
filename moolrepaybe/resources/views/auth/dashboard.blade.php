@extends('auth.layouts')

@section('content')
    <div class="dashboard-container" style="background-image: url({{app('url')->asset('public/assets/imh.jpg')}}); background-size: cover;background-position: center;background-repeat: no-repeat; padding: 10px;">
        <div class="inner-container">
            <div class="board-header card padding-3">
            <img src="{{ app('url')->asset('public/assets/Mobile.svg') }}" alt="">
            <div style="display: flex; align-items: center; gap: 5px;">
                <h4 class="no-margin">Merchant Dashboard</h4>
                <a class="logout" href="{{ route('logout') }}">Logout</a>
            </div>
        </div>


        <div class="board-body">
            <div class="sidebar ">
                <ul class="card padding-5 no-margin">
                    <a class="li-item" href="{{ route('dashboard') }}">View Settings</a>
                    <a class="li-item" href="{{route('transactions')}}">View Transactions</a>
                </ul>
            </div>
            <div class="main-box">
                <div class="card padding-2 ">
                <p class="bold-text">Your Account Settings</p>
                <hr>
                <form action="{{ route('settings') }}" method="post">
                    @csrf
                    <div class="in-row">
                        <div class="left">
                            <label for="name">Name</label>
                        </div>
                        <div class="right">
                            <input type="text" name="name" id="name" value="{{ $merchant->name }}"
                                placeholder="Name" readonly>
                        </div>
                    </div>
                    <div class="in-row">
                        <div class="left">
                            <label for="email">Email</label>
                        </div>
                        <div class="right">
                            <input type="email" name="email" id="email" placeholder="Email" readonly
                                value="{{ $merchant->email }}">
                        </div>
                    </div>
                    <div class="in-row">
                        <div class="left">
                            <label for="phone">Phone number</label>
                        </div>
                        <div class="right">
                            <input type="tel" name="phone" id="phone" placeholder="Phone number"
                                value="{{ $merchant->phone }}">
                            @if ($errors->has('phone'))
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="in-row">
                        <div class="left">
                            <label for="domain">Your website domain</label>
                            <small>Will be used whitelist your domain</small>
                        </div>
                        <div class="right">
                            <input type="text" name="domain" id="domain" placeholder="Website eg: mydomain.com"
                                value="{{ $merchant->website }}">
                            @if ($errors->has('domain'))
                                <span class="text-danger">{{ $errors->first('domain') }}</span>
                            @endif
                        </div>
                    </div>
                     <div class="in-row">
                        <div class="left">
                            <label for="callback">Callback URL</label>
                            {{-- <small>Will be used whitelist your domain</small> --}}
                        </div>
                        <div class="right">
                            <input type="text" name="callback" id="callback" placeholder="Enter callback url"
                                value="{{ $merchant->callback }}">
                            @if ($errors->has('callback'))
                                <span class="text-danger">{{ $errors->first('callback') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="in-row">
                        <div class="left">
                            <label for="apiKey">API Key</label>
                            <small>Previous keys will be revoked</small>
                        </div>
                        <div class="right">
                            <button  type="button" id="myBtn">Generate new API Key</button>
                        </div>
                    </div>
                    <input class="margin-5" type="submit" value="Save">
                </form>
            </div>
            </div>
        </div>
        </div>

        <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content ">
                <span class="close">&times;</span>
                <div class="m5"></div>
                <p class="no-margin info-lbl ">Your new API key is</p>
                <div class="copy-box point" id="copy-box">
                    <p class="no-margin" style="margin-top: 5px" id="key-box"></p>
                    <img src="{{ url('/assets/copy.png') }}" alt="copy" width="30px" height="30px">
                </div>
                <small id="copied" hidden>Copied</small>
                <div style="display: flex; justify-content: flex-end; margin-top: 5px;">
                    <button type="button" class="close-btn" id="close-btn">Close</button>
                </div>
            </div>

        </div>
    </div>

    <script defer>
        // Get the modal
        let modal = document.getElementById("myModal");

        // Get the button that opens the modal
        let btn = document.getElementById("myBtn");

        // Get the <span> element that closes the modal
        let span = document.getElementsByClassName("close")[0];

        // When the user clicks on the button, open the modal
        btn.onclick = async function() {
            const options = {
                header: {
                    method: 'GET'
                }
            }
            const response = await fetch(`{{ route('generate.key') }}`, options)
            const data = await response.json();
            document.getElementById('key-box').innerHTML = data['key']
            console.log(data);
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        document.getElementById('copy-box').addEventListener('click', (evt) => {
            const text = document.getElementById('key-box').innerHTML.trim()
            navigator.clipboard.writeText(text).then(function() {
                document.getElementById('copied').hidden = false;

                setTimeout(()=> {
                    document.getElementById('copied').hidden = true;
                }, 2000)
            }, function(err) {
                console.error('Async: Could not copy text: ', err);
            });
        })

        document.getElementById('close-btn').addEventListener('click', (evt) => {
            modal.style.display = "none";
        })
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
@endsection
