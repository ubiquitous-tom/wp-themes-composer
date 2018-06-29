<?php 
    if(!empty($_COOKIE['ATVSessionCookie']) && rljeApiWP_isUserActive($_COOKIE['ATVSessionCookie'])):
        $watchSpotlightItems = apply_filters('atv_get_watch_spotlight_items', 'recentlyWatched');
        if(0 < count($watchSpotlightItems)):
?>
<!-- RECENTLY WATCHED || WATCHLIST SPOTLIGHT-->
<div class="col-md-12">
    <?php 
        set_query_var('carousel-items', $watchSpotlightItems);
        get_template_part('partials/section-generic-carousel');
    ?>
</div>
<?php 
        endif;
    endif;
?>
<!-- MOST POPULAR SERIES SPOTLIGHT-->
<div class="col-md-12">
    <?php 
        set_query_var('carousel-section', 'Most Popular');
        get_template_part('partials/section-generic-carousel');
    ?>
</div>
<!-- ONLY ON ACORN TV SPOTLIGHT -->
<div class="col-md-12">
    <?php 
        set_query_var('carousel-section', 'Only On Acorn TV');
        $onlyAcornTV = rljeApiWP_getItemsByCategoryOrCollection('exclusive');
        set_query_var('carousel-items', $onlyAcornTV);
        get_template_part('partials/section-generic-carousel');
    ?>
</div>
<!-- BRITISH MYSTERY SPOTLIGHT -->
<div class="col-md-12">
    <?php 
        set_query_var('carousel-section', 'Mysteries');
        $mysteryItems = rljeApiWP_getItemsByCategoryOrCollection('mystery');
        set_query_var('carousel-items', $mysteryItems);
        get_template_part('partials/section-generic-carousel');
    ?>
</div>

<!-- CLASSIC DRAMA SPOTLIGHT -->
<div class="col-md-12">
    <?php 
        set_query_var('carousel-section', 'Dramas');
        $dramaItems = rljeApiWP_getItemsByCategoryOrCollection('drama');
        set_query_var('carousel-items', $dramaItems);
        get_template_part('partials/section-generic-carousel');
    ?>
</div>

<!-- COMEDY SPOTLIGHT-->
<div class="col-md-12">
    <?php 
        set_query_var('carousel-section', 'Comedies');
        $comedyItems = rljeApiWP_getItemsByCategoryOrCollection('comedy');
        set_query_var('carousel-items', $comedyItems);
        get_template_part('partials/section-generic-carousel');
    ?>
</div>

<!-- DOCUMENTARY SPOTLIGHT-->
<div class="col-md-12">
    <?php 
        set_query_var('carousel-section', 'Documentaries');
        $documentaryItems = rljeApiWP_getItemsByCategoryOrCollection('documentary');
        set_query_var('carousel-items', $documentaryItems);
        get_template_part('partials/section-generic-carousel');
    ?>
</div>

<!-- FOREIGN LANGUAGE SPOTLIGHT-->
<div class="col-md-12">
    <?php 
        set_query_var('carousel-section', 'Foreign Language');
        $foreignItems = rljeApiWP_getItemsByCategoryOrCollection('foreign%20language');
        set_query_var('carousel-items', $foreignItems);
        get_template_part('partials/section-generic-carousel');
    ?>
</div>

<!-- FEATURE FILM SPOTLIGHT-->
<div class="col-md-12">
    <?php 
        set_query_var('carousel-section', 'Feature Film');
        $featureItems = rljeApiWP_getItemsByCategoryOrCollection('feature%20film');
        set_query_var('carousel-items', $featureItems);
        get_template_part('partials/section-generic-carousel');
    ?>
</div>