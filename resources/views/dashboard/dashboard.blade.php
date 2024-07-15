@extends('layouts.presensi')
@section('content')
<div class="section" id="user-section">
    <div id="user-detail">
        {{-- <div class="avatar">
            <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
        </div> --}}
        <div id="user-info">
            <h2 id="user-name">Hi, {{ Auth::guard('karyawan')->user()->nama_lengkap }}</h2>
            <span id="user-role">{{  Auth::guard('karyawan')->user()->jabatan }}</span>
        </div>
        <div class="section">
            <form method="GET" action="{{ route('logout') }}">
                @csrf
                <button type="submit" id="btnlogout" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </div>
</div>

<style>
    #clock {
        font-size: 2em;
        margin-top: 10px;
        color: #333;
    }

     #map {
        height: 200px;
    }

    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="section" id="menu-section">
    <div class="card">
        <div class="card-body text-center">
            <div class="list-menu">
                <div class="item-menu text-center">
                    <div class="menu-name">
                        <span class="text-center">Pukul</span>
                        <div id="clock"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section mt-2" id="presence-section">
    <div class="col" style="margin-top: 60px">
        {{-- <div class="text-center">Lokasi Anda Sekarang</div> --}}
        <div id="map"></div>
    </div>
    <div class="todaypresence">
        <div class="row">
            <div class="col-6">
                <div class="card gradasigreen">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                            @if ($presensihariini != null)
                            @php
                                $path = Storage::url('uploads/absensi/' . $presensihariini->foto_in);
                            @endphp
                            <img src="{{ url($path) }}" class="imaged w48 ">
                            @else
                            <ion-icon name="camera"></ion-icon>
                            @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Masuk</h4>
                                <span>{{ $presensihariini != null ? $presensihariini->jam_in : 'Belum Absen'}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card gradasired">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                @if ($presensihariini != null && $presensihariini->jam_out != null)
                            @php
                                $path = Storage::url('uploads/absensi/' . $presensihariini->foto_out);
                            @endphp
                            <img src="{{ url($path) }}" class="imaged w48 ">
                            @else
                            <ion-icon name="camera"></ion-icon>
                            @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Pulang</h4>
                                <span>{{ $presensihariini != null && $presensihariini->jam_out != null ? $presensihariini->jam_out : 'Belum Absen'}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="presencetab mt-2">
        <div class="tab-pane fade show active" id="pilled" role="tabpanel">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                        Presensi
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content mt-2" style="margin-bottom:100px;">
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                <ul class="listview image-listview">
                    @foreach ($historibulanini as $d)
                    <li>
                        <div class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="finger-print-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <div>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }}</div>
                                <span class="badge badge-success">{{ $d->jam_in }}</span>
                                <span class="badge badge-danger">{{ $d->jam_out }}</span>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection

@push('myscript')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk memperbarui jam
        function updateClock() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();
            var ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12;
            hours = hours ? hours : 12; // Jam '0' harus menjadi '12'
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            var strTime = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
            document.getElementById('clock').textContent = strTime;
        }

        // Memperbarui jam setiap detik
        setInterval(updateClock, 1000);
        updateClock(); // Memanggil fungsi sekali untuk menampilkan jam segera

        // Memeriksa apakah Geolocation API tersedia
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;

                // Inisialisasi peta dengan lokasi pengguna
                var map = L.map('map').setView([lat, lon], 17);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                var circle = L.circle([-3.295706079276831, 114.58193378493202], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: 100
                }).addTo(map);

                // Menambahkan marker pada lokasi pengguna
                var marker = L.marker([lat, lon]).addTo(map)
                    .bindPopup('Lokasi Anda Sekarang')
                    .openPopup();
            }, function(error) {
                console.error('Error occurred. Error code: ' + error.code);
            });
        } else {
            console.log('Geolocation is not supported by this browser.');
        }
    });

</script>
@endpush
