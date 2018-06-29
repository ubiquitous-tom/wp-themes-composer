<?php
$sectionTitle = get_query_var('carousel-section');
$carouselItems = get_query_var('carousel-items');
$sectionItems = false;
$section_key = '';

$redefinedKey = array(
    'mystery' => 'Mysteries',
    'drama' => 'Dramas',
    'comedy' => 'Comedies',
    'documentary' => 'Documentaries',
    'onlyacorntv' => 'Only On Acorn TV',
    'recentlywatched' => 'Recently Watched',
    'yourwatchlist' => 'Watchlist'
);

foreach ($redefinedKey as $key => $v) {
   if ($sectionTitle == $v ) {
       $section_key = $key;
   } 
}

if (empty(trim($section_key))) {
   $section_v = str_replace(' ', '', $sectionTitle);
   $section_key = strtolower($section_v);
}

if(!empty($carouselItems)) {
  $sectionItems = new stdClass();
  $sectionItems -> $section_key = $carouselItems;
  set_query_var('carousel-items', '');
}

$allcarousel = ($sectionItems) ? $sectionItems : rljeApiWP_getCarouselItems();

$isBrowsePage = (get_query_var('pagecustom') === 'browse');
$h = 'h4';
if($isBrowsePage) {
  $h = 'h3';
  $disableOrderbyDateAdded = array(
      'recentlywatched' => true,
      'yourwatchlist' => true,
      'mostpopular' => true
  ); 
  if(!isset($disableOrderbyDateAdded[$section_key])) {
    $allcarousel->$section_key = rljeApiWP_orderFranchisesByCreatedDate($allcarousel->$section_key);
  }
}

$baseUrlPath = (function_exists('rljeApiWP_getBaseUrlPath')) ? rljeApiWP_getBaseUrlPath() : '';
if(isset($allcarousel->$section_key) && count($allcarousel->$section_key) > 0) :
?>
<<?= $h; ?> <?= ($section_key === 'mystery' && !$isBrowsePage) ? 'id="third-spotlight"' : ''; ?> class="subnav2"><?php echo $sectionTitle;?></<?= $h; ?>>
<?php 
    if($section_key !== 'mostpopular'):
?>
<div class="view-all hidden-xs">
    <a href="<?= $baseUrlPath.'/browse/'.$section_key; ?>"> View all <span><img width="8" src="https://api.rlje.net/acorn/artwork/size/double-arrows-white?t=Icons"/></span></a>
</div>
<?php 
    endif;
?>
    <div class="carousel carousel-respond-slide slide" id="<?php echo $section_key; ?>" data-interval="false">
        <div class="row">
          <div class="carousel-inner">
                <?php 
                    foreach ($allcarousel->$section_key as $item) :
                      if(!isset($item->href)) {
                        $id = (isset($item->id)) ? $item->id: $item->franchiseID;
                        $item->href = $id;
                      }
                      if(!isset($item->img)) {
                        $img = (isset($item->image)) ? $item->image: $item->href.'_avatar';
                        $item->img = rljeApiWP_getImageUrlFromServices($img);
                      }
                ?>
                <div class="item <?php echo ($item === reset($allcarousel->$section_key)) ? 'active' : ''; ?>">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3" id="avatar">
                      <a href="<?php echo $baseUrlPath.'/'.$item->href; ?>">
                        <img title="<?= $sectionTitle; ?>" alt="<?= $sectionTitle; ?> image" class="wp-post-image" id="avatar-rollover" src="<?php echo $item->img; ?>?w=400&h=225" style="width:100%; height:auto; " />
                      </a>
                    </div>
                </div>
                <?php endforeach; ?>
          </div>
        </div>
        <a class="left carousel-control" href="#<?php echo $section_key; ?>" id="carousel-arrow" data-slide="prev"><img class="carousel-img" src="https://api.rlje.net/acorn/artwork/size/carousel-left?t=Icons"/></a>
        <a class="right carousel-control" href="#<?php echo $section_key; ?>" data-slide="next" id="carousel-arrow"><img class="carousel-img" src="https://api.rlje.net/acorn/artwork/size/carousel-right?t=Icons"/></a> 
    </div>
<?php 
endif;
