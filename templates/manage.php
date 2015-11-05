
<div class="directory-lite manage-listings">

    <?php ldl_get_header(); ?>

    <?php $listings = ldl_get_listings_by_current_author(); ?>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            Your listing was successfully updated.
        </div>
    <?php endif; ?>

    <?php if ($listings->have_posts()): ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th></th>
                <th>Title</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($listings->have_posts()): $listings->the_post(); ?>
                <tr>

                    <td><div style="width: 40px;"><?php echo ldl_get_thumbnail(get_the_ID(), array(32, 32)); ?></div></td>
                    <td>
                        <strong><a href="<?php the_permalink(get_the_ID()); ?>"><?php the_title(); ?></a></strong><br>
                        <a href="<?php ldl_edit_link(get_the_ID(), 'details'); ?>">Edit Details</a> |
                        <a href="<?php ldl_edit_link(get_the_ID(), 'contact'); ?>">Edit Contact</a> |
                        <a href="<?php ldl_edit_link(get_the_ID(), 'social'); ?>">Edit Social</a> |
                        <a href="<?php ldl_edit_link(get_the_ID(), 'logo'); ?>">Update Logo</a> |
                        <a href="<?php ldl_edit_link(get_the_ID(), 'location'); ?>">Change Location</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h2>No Listings Found</h2>

        <p>It appears you haven't submitted any listings to the directory. If you would like to submit a listing, please <a href="<?php echo ldl_get_submit_link() ?>">go here</a>.</p>
    <?php endif; ?>

</div>

<style>
    .manage-listings tr th:first-child {
        width: 40px;
    }
    .manage-listings tr td:first-child {
        vertical-align: middle;
    }
    .manage-listings tr td > strong {
        font-size: 120%;
    }
</style>