import './bootstrap';

function showAlert(message, error){
    const $container = jQuery('#alert-container');
    const $alert = $container.find('.alert');
    $alert.text(message);
    $alert.removeClass('alert-danger');
    $alert.removeClass('alert-success');
    if (error) {
        $alert.addClass('alert-danger');
    } else {
        $alert.addClass('alert-success');
    }
    $container.show();
    setTimeout(function() {
        $container.hide();
    }, 5000);
}

function addCar(data, callback) {
    jQuery.ajax({
        url: '/new',
        method: 'post',
        dataType: 'json',
        data: data,
        processData: false,
        contentType: false,
        success: function(response) {
            showAlert(response.message, response.success !== true);
            if (typeof callback === 'function') {
                callback.call(this, response);
            }
        },
    });
}

function getCitiesByState(state_id) {
    var result = {};
    jQuery.ajax({
        url: '/cities/' + state_id,
        method: 'get',
        dataType: 'json',
        async: false,
        success: function(cities) {
            jQuery.each(cities, function(city_id, city_name){
                result[city_id] = city_name;
            });
        },
    });
    return result;
}

function closeNewForm() {
    const $form = jQuery('#new-form');
    $form.find('#state_id').val('');
    $form.find('#city_id').val('');
    $form.find('#brand_id').val('');
    $form.find('#volume').val('');
    $form.find('#distance').val('');
    $form.find('#owners').val('');
    $form.find('#photos').val('');
    $form.removeClass('was-validated');
    jQuery('#newModal').modal('hide');
}

function tableReload() {
    jQuery('#cars-data-table').DataTable().ajax.reload();
}

jQuery(document).ready(function() {
    jQuery('#save-button').click(function (e) {
        const $form = jQuery('#new-form');
        const formIsValid = $form[0].checkValidity();
        $form.addClass('was-validated');
        if (!formIsValid) {
            return false;
        }
        addCar(new FormData($form[0]), function(response) {
            closeNewForm();
            tableReload();
        });
    });
    jQuery('#state_id,#filter-state-id').change(function(){
        const state_id = jQuery(this).val();
        const stateCities = getCitiesByState(state_id);
        const cityInputId = jQuery(this).data('city-input-id');
        let $city = jQuery(cityInputId ? cityInputId : '#city_id');
        $city.find('.city-option').remove();
        jQuery.each(stateCities, function(city_id, city_name) {
            $city.append('<option value="' + city_id + '" class="city-option">' + city_name + '</option>');
        });
    });
    jQuery('#filters select.form-control').change(function() {
        tableReload();
    });
    jQuery('#filters input.form-control').keyup(function() {
        tableReload();
    });
});
