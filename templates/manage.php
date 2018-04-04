<?php
/*
* File version: 2
*/
?>
<div class="directory-lite manage-listings bootstrap-wrapper">

    <?php ldl_get_header(); ?>

    <?php $listings = ldl_get_listings_by_current_author(); ?>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php _e( 'Your listing was successfully updated.', 'ldd-directory-lite' ); ?>
        </div>
    <?php endif; ?>

    <?php if ($listings->have_posts()): ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th></th>
                <th><?php _e( 'Title', 'ldd-directory-lite' ); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php while ($listings->have_posts()): $listings->the_post(); ?>
                <tr>

                    <td><div style="width: 40px;"><?php echo ldl_get_thumbnail(get_the_ID(), array(32, 32)); ?></div></td>
                    <td>
                        <strong><a href="<?php the_permalink(get_the_ID()); ?>"><?php the_title(); ?></a></strong><br>
                        <a href="<?php ldl_edit_link(get_the_ID(), 'details'); ?>"><?php _e( 'Edit Details', 'ldd-directory-lite' ); ?></a> |
                        <a href="<?php ldl_edit_link(get_the_ID(), 'contact'); ?>"><?php _e( 'Edit Contact', 'ldd-directory-lite' ); ?></a> |
                        <a href="<?php ldl_edit_link(get_the_ID(), 'social'); ?>"><?php _e( 'Edit Social', 'ldd-directory-lite' ); ?></a> |
                        <a href="<?php ldl_edit_link(get_the_ID(), 'logo'); ?>"><?php _e( 'Update Logo', 'ldd-directory-lite' ); ?></a> |
                        <a href="<?php ldl_edit_link(get_the_ID(), 'location'); ?>"><?php _e( 'Change Location', 'ldd-directory-lite' ); ?></a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h2><?php _e( 'No Listings Found', 'ldd-directory-lite' ); ?></h2>

        <p><?php printf( __( "It appears you haven't submitted any listings to the directory. If you would like to submit a listing, please <a href='%s'>go here</a>.</p>", 'ldd-directory-lite' ), ldl_get_submit_link() ); ?>
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