@if(count($CustomerProjects) > 0)
    <select name="idxCoreProject" id="idxCoreProjectSelect" class="select2 select2-offscreen" placeholder="-- Choose Project --" multiple="multiple" tabindex="-1" title="">
        @foreach ($CustomerProjects as $keyp => $valp)
            <option value="{{ $valp->idxCore }}">{{ $valp->projectAlias }}</option>
        @endforeach
    </select>
@else
    <div>Tidak ada data Prefix</div>
@endif
