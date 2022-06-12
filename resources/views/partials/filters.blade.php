<div id="filters" class="d-flex justify-content-between mb-3 mt-1 flex-wrap">
    <div class="form-group d-inline-block">
        <select id="filter-state-id" data-city-input-id="#filter-city-id" class="form-control">
            <option value="">{{ __('maratauto.noselect') }}</option>
            @foreach($stateOptions as $stateId => $stateName)
                <option value="{{ $stateId }}">{{ $stateName }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group d-inline-block">
        <select id="filter-city-id" class="form-control">
            <option value="">{{ __('maratauto.noselect') }}</option>
        </select>
    </div>
    <div class="form-group d-inline-block">
        <select id="filter-brand-id" class="form-control">
            <option value="">{{ __('maratauto.noselect') }}</option>
            @foreach($brandOptions as $brandId => $brandName)
                <option value="{{ $brandId }}">{{ $brandName }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group d-inline-block">
        <input type="text" id="filter-volume-from" placeholder="{{ __('maratauto.volumefrom') }}" class="form-control" />
    </div>
    <div class="form-group d-inline-block">
        <input type="text" id="filter-volume-to" placeholder="{{ __('maratauto.volumeto') }}" class="form-control" />
    </div>
    <div class="form-group d-inline-block">
        <input type="text" id="filter-distance-from" placeholder="{{ __('maratauto.distancefrom') }}" class="form-control" />
    </div>
    <div class="form-group d-inline-block">
        <input type="text" id="filter-distance-to" placeholder="{{ __('maratauto.distanceto') }}" class="form-control" />
    </div>
    <div class="form-group d-inline-block">
        <input type="text" id="filter-owners-from" placeholder="{{ __('maratauto.ownersfrom') }}" class="form-control" />
    </div>
    <div class="form-group d-inline-block">
        <input type="text" id="filter-owners-to" placeholder="{{ __('maratauto.ownersto') }}" class="form-control" />
    </div>
</div>
