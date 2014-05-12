<article id="listing-{$id}" class="listing-{$id} type-listing status-{$status} {$nth} cf">

        <div class="col-sm-3">
            {$thumbnail}
        </div>
        <div class="col-sm-9">
            <div class="listing-header row">
                <div class="col-sm-8">
                    <h2 class="listing-title">{$title}</h2>
                    <p class="website">{$meta.website}</p>
                    <div class="listing-summary">
                        {$summary}
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="listing-meta">

                        {if="!empty($meta.phone)"}<p class="phone"><i class="fa fa-phone"></i> {$meta.phone}</p>{/if}
                        {if="!empty($address)"}<p class="address"><i class="fa fa-globe"></i> {$address}</p>{/if}
                    </div>
                </div>
            </div>
        </div>

</article>
