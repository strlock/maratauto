<script type="text/javascript">
    $(document).ready(function () {
        $('#list-data-table').DataTable({
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
                    render: function (data, type, row) {
                        let images = [];
                        jQuery.each(data, function (i, photoId) {
                            images.push('<img src="photo' + '/' + photoId + '/100" alt="" width="50" />');
                        });
                        return images.join('&nbsp;');
                    }
                },
                { data: 'state' },
                { data: 'city' },
                { data: 'brand' },
                { data: 'volume' },
                { data: 'distance' },
                { data: 'owners' },
                { data: 'date' },
                { data: 'id' },
            ],
        });
    });
</script>

<table id="list-data-table" class="display" style="width:100%">
    <thead>
        <tr>
            <th>{{ __('maratauto.photos') }}</th>
            <th>{{ __('maratauto.state') }}</th>
            <th>{{ __('maratauto.city') }}</th>
            <th>{{ __('maratauto.brand') }}</th>
            <th>{{ __('maratauto.volume') }}</th>
            <th>{{ __('maratauto.distance') }}</th>
            <th>{{ __('maratauto.owners') }}</th>
            <th>{{ __('maratauto.date') }}</th>
            <th>{{ __('maratauto.id') }}</th>
        </tr>
    </thead>
</table>
