<div class="list_item_featured">
    <h3>
    	<?php $this->displayContentTitle($content); ?>
    	<span class="boss_cat_featured">(
    		<?php $this->displayCategoryTitle($content,1); ?>
    	)</span>
    </h3>
        <div class="list_header_featured">
        	<?php if ($this->isRatingAllowed()||$this->isReviewAllowed()) { ?>
        	<div>
        		<?php if ($this->isRatingAllowed()) {
        			$this->rating->displayVoteResult($content, $this->directory);
        			echo "&nbsp;";
        			$this->rating->displayNumVotes($content);
                                echo "&nbsp;&nbsp;";
        		}
        		?>
        		<?php if ($this->isReviewAllowed()) {		   
        			   $this->comments->displayNumReviews($content, $this->reviews, $this->conf);
        			   echo "<br/><br/>";
        		}
        		?>
        	</div>
        	<?php } ?>
            <div class="tags">
                <?php $this->displayListTags($content); ?>
            </div>
        	<?php $this->displayContentEditDelete($content); ?>
        </div>
        <div class="list_subtitle_featured">
        	<?php
        	if ($this->countFieldsInGroup("ListSubtitle"))
        			$this->loadFieldsInGroup($content,"ListSubtitle"," <br/> ");
            ?>
        </div>
        <div>
            <div class="list_image_featured">
            	<?php
            	if ($this->countFieldsInGroup("ListImage"))
            			$this->loadFieldsInGroup($content,"ListImage"," <br/> ");
                ?>
            </div>
            <div class="list_content_featured">
            	<?php
            	if ($this->countFieldsInGroup("ListDescription"))
            			$this->loadFieldsInGroup($content,"ListDescription"," <br/> ");
                ?>
            </div>
        </div>
    <div class="list_bottom_featured">
        	<?php
        	if ($this->countFieldsInGroup("ListBottom"))
        			$this->loadFieldsInGroup($content,"ListBottom"," <br/> ");
            ?>
        </div>
    <div class="list_footer_featured">
        <div class="list_date">
    	    <?php $this->displayContentDate($content); echo " - "; $this->displayContentBy($content);  ?>
        </div>
        <div class="list_hits">
    	    <?php $this->displayContentHits($content); ?>
        </div>
    </div>
</div>