<div class="modal fade needs-validation" id="newModal" tabindex="-1" aria-labelledby="newModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newModalLabel">{{ __('maratauto.newcar') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="new-form">
                    <div class="form-group mb-2">
                        <label for="state_id">{{ __('maratauto.state') }}</label>
                        <select name="state_id" class="form-control" id="state_id" required>
                            <option value="">{{ __('maratauto.noselect') }}</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="city_id">{{ __('maratauto.city') }}</label>
                        <select name="city_id" class="form-control" id="city_id" required>
                            <option value="">{{ __('maratauto.noselect') }}</option>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="brand_id">{{ __('maratauto.brand') }}</label>
                        <select name="brand_id" class="form-control" id="brand_id" required>
                            <option value="">{{ __('maratauto.noselect') }}</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="volume">{{ __('maratauto.volume') }}</label>
                        <input type="text" name="volume" class="form-control" id="volume" required />
                    </div>
                    <div class="form-group mb-2">
                        <label for="distance">{{ __('maratauto.distance') }}</label>
                        <input type="text" name="distance" class="form-control" id="distance" required />
                    </div>
                    <div class="form-group mb-2">
                        <label for="owners">{{ __('maratauto.owners') }}</label>
                        <input type="text" name="owners" class="form-control" id="owners" required />
                    </div>
                    <div class="form-group mb-2">
                        <label for="price">{{ __('maratauto.price') }}</label>
                        <input type="text" name="price" class="form-control" id="price" required />
                    </div>
                    <div class="form-group mb-2">
                        <label for="content">{{ __('maratauto.content') }}</label>
                        <textarea name="content" class="form-control" id="content" required></textarea>
                    </div>
                    <div class="form-group mb-2 form-group-uploader">
                        <label for="photos">{{ __('maratauto.photos') }}</label>
                        <input type="file" name="photos[]" multiple class="form-control" id="photos" required accept="image/jpeg, image/png, image/gif" />
                        {{--@include('partials.imagesloader')--}}
                    </div>
                    @csrf
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-button">Save changes</button>
            </div>
        </div>
    </div>
</div>
