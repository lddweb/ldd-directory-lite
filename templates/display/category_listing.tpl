        <article id="listing-{{id}}" class="listing-{{id}} type-listing status-{{status}} {{nth}} cf">
            {{featured}}

            <header class="entry-header">
                <h2 class="entry-title listing-title">{{title}}</h2>
                <div class="entry-meta">
                    <p class="website">{{meta.website}}</p>
                    <p class="phone" style="font-size: 100%; display: none;">{{meta.phone}}</p>
                    <p class="address" style="font-size: 100%;">{{meta.address_one}},<br>{{meta.city}}, {{meta.subdivision}} {{meta.post_code}}</p>
                </div><!-- .entry-meta -->
            </header><!-- .entry-header -->

            <div class="entry-summary">
                {{summary}}
            </div><!-- .entry-summary -->
        </article><!-- #listing-## -->