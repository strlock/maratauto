<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#cars-data-table').DataTable({
            ajax: {
                url: '/cars',
                data: function(data){
                    data.state_id = jQuery('#filter-state-id').val();
                    data.city_id = jQuery('#filter-city-id').val();
                    data.brand_id = jQuery('#filter-brand-id').val();
                    data.volume_from = jQuery('#filter-volume-from').val();
                    data.volume_to = jQuery('#filter-volume-to').val();
                    data.distance_from = jQuery('#filter-distance-from').val();
                    data.distance_to = jQuery('#filter-distance-to').val();
                    data.owners_from = jQuery('#filter-owners-from').val();
                    data.owners_to = jQuery('#filter-owners-to').val();
                },
            },
            processing: true,
            serverSide: true,
            ordering: false,
            columns: [
                {
                    data: 'photos',
                    width: 200,
                    render: function (data, type, row) {
                        if (!Array.isArray(data)) {
                            return '';
                        }
                        if (data.length <= 1) {
                            return '<div class="d-flex justify-content-center align-items-center"><img src="photo' + '/' + data[0] + '/200" class="d-block w-100" alt="" /></div>';
                        }
                        let carouselHtml = '<div id="car-carousel-' + row.id + '" class="carousel slide car-carousel" data-bs-ride="carousel"><div class="carousel-inner">';
                        jQuery.each(data, function (i, photoId) {
                            carouselHtml += '<div class="carousel-item' + (i === 0 ? ' active' : '') + '"><div class="d-flex justify-content-center align-items-center"><img src="photo' + '/' + photoId + '/200" class="d-block w-100" alt="" /></div></div>';
                        });
                        carouselHtml += '</div>';
                        carouselHtml += '<button class="carousel-control-prev" type="button" data-bs-target="#car-carousel-' + row.id + '" data-bs-slide="prev">' +
                                            '<span class="carousel-control-prev-icon" aria-hidden="true"></span>' +
                                        '</button>';
                        carouselHtml += '<button class="carousel-control-next" type="button" data-bs-target="#car-carousel-' + row.id + '" data-bs-slide="next">' +
                                            '<span class="carousel-control-next-icon" aria-hidden="true"></span>' +
                                        '</button>';
                        carouselHtml += '</div>';
                        return carouselHtml;
                    }
                },
                {
                    data: 'info',
                    render: function (data, type, row) {
                        return '<div class="car-info">' +
                                   '<div><b>{{ __('maratauto.state') }}:</b> ' + row.state + '</div>' +
                                   '<div><b>{{ __('maratauto.city') }}:</b> ' + row.city + '</div>' +
                                   '<div><b>{{ __('maratauto.brand') }}:</b> ' + row.brand + '</div>' +
                                   '<div><b>{{ __('maratauto.volume') }}:</b> ' + row.volume + '</div>' +
                                   '<div><b>{{ __('maratauto.distance') }}:</b> ' + row.distance + '</div>' +
                                   '<div><b>{{ __('maratauto.owners') }}:</b> ' + row.owners + '</div>' +
                               '</div>';
                    },
                },
                {data: 'content'},
                {data: 'price', width: 100},
                {data: 'date', width: 150},
            ],
            columnDefs : [
                {
                    targets: 0,
                    className : 'dt-body-center'
                },
                {
                    targets: 1,
                    className : 'dt-body-left'
                },
                {
                    targets: 2,
                    className : 'dt-body-center'
                },
                {
                    targets: 3,
                    className : 'dt-body-center'
                },
                {
                    targets: 4,
                    className : 'dt-body-center'
                },
            ],
            initComplete: function (settings, json) {
                jQuery('.car-carousel').each(function (key, element) {
                    new bootstrap.Carousel(element, {
                        interval: 99999,
                    });
                });
            },
        });
    });
</script>

<table id="cars-data-table" class="display" style="width:100%">
    <thead>
        <tr>
            <th>{{ __('maratauto.photos') }}</th>
            <th class="info">{{ __('maratauto.information') }}</th>
            <th class="content">{{ __('maratauto.content') }}</th>
            <th class="price">{{ __('maratauto.price') }}</th>
            <th class="date">{{ __('maratauto.date') }}</th>
        </tr>
    </thead>
</table>
