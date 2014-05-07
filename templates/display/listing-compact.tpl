        <article id="listing-{$id}" class="listing-{$id} type-listing status-{$status} {$nth} cf">
            {$featured}

            <header class="listing-header">
                <h2 class="listing-title">{$title}</h2>
                <div class="listing-meta">
                    <p class="website">{$meta.website}</p>
                    <p class="phone" style="font-size: 100%; display: none;">{$meta.phone}</p>
                    {if="$address!=''"}<p class="address" style="font-size: 100%;">{$address}</p>{/if}
                </div>
            </header>

            <div class="listing-summary">
                {$summary}
            </div>
        </article>