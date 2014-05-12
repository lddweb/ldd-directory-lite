<section class="business-directory  cf">

    <nav class="lite-nav above-header center cf">
        <ul><li><a href="{{url}}">Directory Home</a></li></ul>
    </nav>

    <form class="directory-search cf">
        <input type="text" placeholder="Search the directory..." required />
        <button type="submit">Search</button>
    </form>

    <section class="directory-content submit-success">

        <header class="listing-header">
            <a href="" class="post-thumbnail">{{logo}}</a>
            <h2 class="listing-title">Congratulations</h2>
        </header>

        <div class="listing-content">

            <p>Your listing is awaiting review.</p>

            <ul class="submit-review">
                <li><span>Business Name:</span> {{listing.name}}</li>
                <li><span>Website:</span> <a href="{{listing.url}}">{{listing.url}}</a></li>
                <li><span>Your Username:</span> {{listing.username}}</li>
            </ul>

            <p>Please allow up to five business days for your listing to be reviewed and successfully published to our directory.</p>

        </div>


    </section>


</section>
