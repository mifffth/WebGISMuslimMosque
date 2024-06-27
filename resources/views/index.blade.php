@extends('template')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <style>
    html,
    body {
        height: 100%;
        width: 100%;
    }

    #map {
        height: calc(100vh - 56px);
        width: 100%;
        margin: 0;
    }
</style>

@endsection

@section('content')
    <div id="map" style="width: 100vw; height: 100vh; margin: 0"></div>

    <!-- Modal Create Point -->
<div class="modal fade" id="PointModal" tabindex="-1" aria-labelledby="PointModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="PointModalLabel">Create Point</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('store-point') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="toponim" class="form-label">Toponim</label>
                        <input type="text" class="form-control" id="toponim" name="toponim"
                            placeholder="Fill point toponim">
                    </div>
                    <div class="mb-3">
                        <label for="objek" class="form-label">Objek</label>
                        <textarea class="form-control" id="objek" name="objek" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="geom" class="form-label">Geometry</label>
                        <textarea class="form-control" id="geom_point" name="geom" rows="1" readonly></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image_point" name="image"
                            onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0])">
                    </div>
                    <div class="mb-3">
                        <img src="" alt="Preview" id="preview-image-point" class="img-thumbnail"
                            width="400">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://unpkg.com/terraformer@1.0.7/terraformer.js"></script>
    <script src="https://unpkg.com/terraformer-wkt-parser@1.1.2/terraformer-wkt-parser.js"></script>
    <script>
        // Map
        var map = L.map('map').setView([-7.7711315251194835, 110.37765787782111], 15);

        //Basemap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        /* Digitize Function */
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: {
                position: 'topleft',
                polyline: false,
                polygon: false,
                rectangle: false,
                circle: false,
                marker: true,
                circlemarker: false
            },
            edit: false
        });

        map.addControl(drawControl);

        map.on('draw:created', function(e) {
            var type = e.layerType,
                layer = e.layer;

            console.log(type);

            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = Terraformer.WKT.convert(drawnJSONObject.geometry);

            console.log(drawnJSONObject);
            console.log(objectGeometry);

            if (type === 'polyline') {
                $("#geom_polyline").val(objectGeometry);
                $("#PolylineModal").modal('show');
            } else if (type === 'polygon' || type === 'rectangle') {
                $("#geom_polygon").val(objectGeometry);
                $("#PolygonModal").modal('show');
            } else if (type === 'marker') {
                $("#geom_point").val(objectGeometry);
                $("#PointModal").modal('show');
            } else {
                console.log('undefined');
            }

            drawnItems.addLayer(layer);
        });

       /* GeoJSON Point */
var point = L.geoJson(null, {
    onEachFeature: function(feature, layer) {
        var popupContent = "Toponim: " + feature.properties.toponim + "<br>" +
            "Objek: " + feature.properties.objek + "<br>" +
            "Photo: <img src='{{ asset('storage/images/') }}/" + feature.properties.image +
            "' class='img-thumbnail' alt='...'>" + "<br>" +

            "<div class='d-flex flex-row mt-3'>" +
            "<a href='{{ url('edit-point') }}/" + feature.properties.id + "' class='btn btn-sm btn-warning me-2'><i class='fa-solid fa-edit'></i></a>" +

            "<form action='{{ url('delete-point') }}/" + feature.properties.id + "' method='POST'>" +
            '{{ csrf_field() }}' +
            '{{ method_field('DELETE') }}' + "<button type='submit' class='btn btn-sm btn-danger' onClick='return confirm(\"Hapus kah?\")'><i class='fa-solid fa-trash-can'></i> Delete</button>" +
            "</form>" +
            "</div>";
        layer.on({
            click: function(e) {
                point.bindPopup(popupContent);
            },
            mouseover: function(e) {
                point.bindTooltip(feature.properties.toponim);
            },
        });
    },
});
$.getJSON("{{ route('api.points') }}", function(data) {
    point.addData(data);
    map.addLayer(point);
});


        /* Layer Control */
        var overlayMaps = {
            "Points": point
        };
        var layerControl = L.control.layers(null, overlayMaps, {
            collapsed: false
        }).addTo(map);
    </script>
@endsection

</body>

</html>