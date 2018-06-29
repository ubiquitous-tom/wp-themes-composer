<?php
global $wp_query;

$baseUrlPath = (function_exists('rljeApiWP_getBaseUrlPath')) ? rljeApiWP_getBaseUrlPath() : '';
$seasonName = isset($wp_query->query_vars['seasonName']) ? $wp_query->query_vars['seasonName'] : null;
$seasons = $wp_query->query_vars['seasons'];
$franchiseId = $wp_query->query_vars['franchise_id'];

if (count($seasons) > 1) :
?>

<!-- Drop Down Series Filter -->
<div id="cover">
    <div id="options">
        <a><?= (isset($seasonName)) ? $seasonName : 'Filter By Series'; ?></a>
        <span id="clicker">
            <img src="https://api.rlje.net/acorn/artwork/size/dropdown-arrow?t=Icons" width="13" style="opacity:.7"/>
        </span>
    </div>
    <ul id="drop-select" class="closed">
      <?php for($i=0; $i<count($seasons); $i++) :?>
      <a href="<?= $baseUrlPath.'/'.$franchiseId.'/'.rljeApiWP_convertSeasonNameToURL($seasons[$i]->name); ?>"><li><?= $seasons[$i]->name; ?></li></a>
      <?php endfor; ?>
      <a href="/<?= $franchiseId; ?>"><li>View All</li></a>
    </ul>
</div>

<?php 
endif;
