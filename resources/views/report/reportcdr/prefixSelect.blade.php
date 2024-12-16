@if(count($ProjectPrefixSrvs) > 0)
    <select name="idxCorePrefix" id="idxCorePrefixSelect" class="select2 select2-offscreen" placeholder="-- Choose Prefix --" multiple="multiple" tabindex="-1" title="">
        @foreach ($ProjectPrefixSrvs as $keyp => $valp)
            <option value="{{ $valp->idxCore }}">{{ $valp->prefixNumber }}</option>
        @endforeach
    </select>
@else
    <div>Tidak ada data Prefix</div>
@endif
