<?php
$page=$form_data['page'];
/*
	First Previous 1 2 3 ... 22 23 24 25 26 [27] 28 29 30 31 32 ... 48 49 50 Next Last
*/
// Number of page links in the begin and end of whole range
$count_out = ( ! empty($config['count_out'])) ? (int) $config['count_out'] : 1;
// Number of page links on each side of current page
$count_in = 4;
// Beginning group of pages: $n1...$n2
$n2 = min($count_out, $page->total);
// Ending group of pages: $n7...$n8
$n7 = max(1, $page->total - $count_out + 1);

// Middle group of pages: $n4...$n5
$n4 = max($n2 , $page->current - $count_in);
$n5 = min($n7 , $page->current + $count_in);

// Links to display as array(page => content)
$links = array();

// Generate links data in accordance with calculated numbers
for ($i = $n4; $i <= $n5; $i++)
{
    $links[$i] = $i;
}
?>
<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
    <div class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" >
        <?php if($page->first){?>
            <a href="<?php echo $page->first; ?>"  class="first ui-corner-tl ui-corner-bl fg-button ui-button ui-state-default">首页</a>
        <?php }?>
        <?php if($page->previous){?>
            <a href="<?php echo $page->previous; ?>" class="previous fg-button ui-button ui-state-default">上一页</a>
        <?php }?>
        <span>
      <?php
      if(!empty($links)){
          foreach ($links as $number => $content){
              if($number == $page->current){
                  ?><a class="fg-button ui-button ui-state-default ui-state-disabled"><?php echo $content ?></a>
              <?php }else{ ?>
                  <a href="<?php echo $page->baseurl.'&page='.$content;?>" class="fg-button ui-button ui-state-default"><?php echo $content ?></a>
              <?php }
          }
      } ?>
      </span>
        <?php if($page->next){?>
            <a href="<?php echo $page->next; ?>" class="next fg-button ui-button ui-state-default">下一页</a>
        <?php } ?>
        <?php if($page->last){?>
            <a href="<?php echo $page->last; ?>" class="last ui-corner-tr ui-corner-br fg-button ui-button ui-state-default">最后一页</a>
        <?php }?>
    </div>
</div>

<!-- .pagination -->
