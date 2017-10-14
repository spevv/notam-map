<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ICAO Map</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            margin: 0;
        }

        .content {
            text-align: center;
        }

        #map {
            height: calc(100vh - 81px);
            width: 100%;
        }

        .from {
            margin: 30px 0;
        }
    </style>
</head>
<body>
<div class="full-height">
    <div class="content">
        <div class="from">
            {!! Form::open(['route' => ['icao'], 'method' => 'POST', 'class' => 'f']) !!}

            {{ Form::label('icao', trans('ICAO')) }}
            {{ Form::text('icao', $icao, ['minlength' => 4, 'maxlength' => 4, 'size' => 4]) }}
            {{ Form::submit(trans('Show'), ['class' => 'btn']) }}
            @if ($errors->has('icao'))
                {{ $errors->first('icao') }}
            @endif
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div id="map"></div>
<script>
  function initMap () {
    var locations = {!! $points  !!};
    var map = new google.maps.Map(document.getElementById('map'), {zoom: 8, center: new google.maps.LatLng(51, 0)});
    setMarkers(map, locations);
  }

  function setMarkers (map, locations) {
    var image = '{{ url('/images/rsz_1warning-icon-th.png') }}';
    for (var i = 0; i < locations.length; i++) {
      var marker = new google.maps.Marker({
        map: map,
        position: new google.maps.LatLng(locations[i].lat, locations[i].lng),
        icon: image
      });
      map.setCenter(marker.getPosition());

      google.maps.event.addListener(marker, 'click', (function (marker, content, infowindow) {
        return function () {
          infowindow.setContent(content);
          infowindow.open(map, marker);
        };
      })(marker, locations[i].content, new google.maps.InfoWindow()));
    }
  }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABMQ87ULgzbEQC1qsCVLbabkcD3zXETqA&callback=initMap">
</script>
</body>
</html>
