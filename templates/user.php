
<div class="directory-lite">

    <?php ldl_get_header(); ?>

    <?php $listings = ldl_get_listings_by_current_author(); ?>

    <?php if ($listings->have_posts()): ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th style="width: 40px;"></th>
            <th>Title</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php while ($listings->have_posts()): $listings->the_post(); ?>
            <tr>

                    <td><?php echo ldl_get_thumbnail(get_the_ID(), array(32, 32)); ?></td>
                <td><a href="<?php ldl_edit_link(get_the_ID()); ?>"><?php the_title(); ?></a></td>
                <td style="text-align: right;">
                    <a href="<?php ldl_edit_link(get_the_ID()); ?>"><i class="fa fa-pencil-square-o fa-lg fa-fw"></i></a>
                    <a href=""><i class="fa fa-times-circle fa-lg fa-fw"></i></a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php endif; ?>

</div>
