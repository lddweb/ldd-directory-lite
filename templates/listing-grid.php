<div class="col-sm-6 col-md-3 type-grid">
    <div class="thumbnail">
        {$thumbnail}
        <div class="caption text-center">
            <h2 class="listing-title">{$title}</h3>
            <div class="listing-meta">
                {if="!empty($meta.phone)"}<p class="phone"><i class="fa fa-phone"></i> {$meta.phone}</p>{/if}
                {if="!empty($address)"}<p class="address"><i class="fa fa-globe"></i> {$address}</p>{/if}
            </div>
        </div>
    </div>
</div>