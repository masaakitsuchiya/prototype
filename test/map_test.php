<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Info windows</title>
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
  <script>
  var address = encodeURIComponent("東京都杉並区高円寺南6－14");
  var google_geolocation_api = "https://maps.googleapis.com/maps/api/geocode/json?address=" + address + "&key=AIzaSyC2ZQj8dGnc9KjzFs1peNKU8JIRHvTQofM";
  var plat;
  var plon;
  $.getJSON(google_geolocation_api, function(json){
    // console.dir(json);
    plat = json.results["0"].geometry.location.lat;
    plon = json.results["0"].geometry.location.lng;
  });
    //GoogleMapsAPIのURLパラメータにコールバック関数としてinitMap()を実行
    //Main:位置情報を取得する処理 //getCurrentPosition :or: watchPosition
    // function init(){
    //   navigator.geolocation.getCurrentPosition(mapsInit, mapsError, set);
    // }

    //1．位置情報の取得に成功した時の処理
    function mapsInit(position) {
        //lat=緯度、lon=経度 を取得
        // var plat = position.coords.latitude;
        // var plon = position.coords.longitude;
//        $("#map").html("緯度"+lat+",  "+"経度"+lon);
        var uluru = {lat: plat, lng: plon};

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: uluru
        });

        var contentString = '<div style="width:300px; color:red;">' + address + '</div>';

        // ここでinfowindowを作成
        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });

        //プロットを置く（マーカー）
        var marker = new google.maps.Marker({
            position: uluru,
            map: map,
            title: 'Uluru (Ayers Rock)'
        });

        //プロットをクリックしたらinfowindowを表示
        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });
    };

    //2． 位置情報の取得に失敗した場合の処理
    function mapsError(error) {
      var e = "";
      if (error.code == 1) { //1＝位置情報取得が許可されてない（ブラウザの設定）
        e = "位置情報が許可されてません";
      }
      if (error.code == 2) { //2＝現在地を特定できない
        e = "現在位置を特定できません";
      }
      if (error.code == 3) { //3＝位置情報を取得する前にタイムアウトになった場合
        e = "位置情報を取得する前にタイムアウトになりました";
      }
      alert("エラー：" + e);
    };

    //3.位置情報取得オプション
    var set ={
      enableHighAccuracy: true, //より高精度な位置を求める
      maximumAge: 20000,        //最後の現在地情報取得が20秒以内であればその情報を再利用する設定
      timeout: 10000            //10秒以内に現在地情報を取得できなければ、処理を終了
    };


// This example displays a marker at the center of Australia.
// When the user clicks the marker, an info window opens.


  </script>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2ZQj8dGnc9KjzFs1peNKU8JIRHvTQofM&callback=initMAP"></script>
</html>
