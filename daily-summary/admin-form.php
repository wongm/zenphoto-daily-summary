<?php

printAdminHeader('daily summary');
?>
<link rel="stylesheet" type="text/css" media="screen" href="daily-summary.css" />
<?php
echo '</head>';
?>
<body>
	<?php printLogoAndLinks(); ?>
	<div id="main">
		<?php printTabs(); ?>
		<div id="content">
			<h1>Flag images for daily summary</h1>
			<form id="captionForm" method="post">
				<div id="captionPanel">
<?php 
				displayDailySummaryProcessingResults();
				$sql = "SELECT CAST(i.date AS DATE) AS date, MAX(daily_score) AS daily_score
					FROM " . prefix('images') . " i
					GROUP BY CAST(i.date AS DATE)
					HAVING COALESCE(MAX(daily_score), 0) < 1
					ORDER BY i.date DESC
					LIMIT 0, 5";
				$results = query_full_array($sql);
				
				if (sizeof($results) > 0) 
				{ 
?>
					<script type="text/javascript">
						// <!-- <![CDATA[
						window.onload = function(e){ 
							controls = document.getElementsByClassName('thumbselect');
							for (i = 0; i < controls.length; i++) { 
								updateThumbPreview(controls[i]);
							}
						};
						// ]]> -->
					</script>
					<input name="dayCount" value="<?php echo sizeof($results); ?>" type="hidden" />
					<table class="bordered">
<?php					
					$dayCount = 0;
					foreach($results as $day)
					{
						$dayCount++;
						$date = $day['date'];
						// hack to make photostream display hardcoded number of images
						setOption('photostream_images_per_page', 50, false);
						setCustomPhotostream("date(i.date) = '" . $date . "'", "", "i.hitcounter DESC");
?>
					<tr>
						<td class="dateLabel">
							<label for="standout_<?php echo $dayCount; ?>">
								<?php echo zpFormattedDate("%B %d, %Y", strtotime($date)); ?> - <?php echo getNumPhotostreamImages(); ?> images
							</label>	
							<p><a href="<?php echo html_encode(getSearchURL(null, $date, null, 0, null)); ?>">View all</a></p>
						</td>
						<td class="dateInput">
							<select id="standout_<?php echo $dayCount; ?>" name="standout_<?php echo $dayCount; ?>" class="thumbselect" onchange="updateThumbPreview(this);">
								<option value="">Select</option>
<?php
						$imageID = 0;
						while (next_photostream_image()):
							$imageID++;
?>
								<option value="<?php echo $_zp_current_image->getFileName(); ?>" style="background-image:url('<?php echo getImageThumb() ?>')"><?php printImageTitle(); ?> (<?php echo $_zp_current_image->getFileName() ?>)
								</option>
						<?php endwhile; ?>
								</select>
							</td>
						</tr>
						<?php }	// end foreach ?>
						<tr>
							<td colspan="2">
								<p class="buttons">
									<a href="/zp-core/admin.php">
										<img src="../../zp-core/images/arrow_left_blue_round.png" alt="">
										<strong>Back</strong>
									</a>
									<button type="submit" name="save" value="Save" >
										<img src="../../zp-core/images/pass.png" alt="">
										<strong>Save</strong>
									</button>
								</p>
							</td>
						</tr>
					</table>
					<?php } else { ?>
					<div>
						<p class="buttons">
							<a href="/zp-core/admin.php">
								<img src="../../zp-core/images/arrow_left_blue_round.png" alt="">
								<strong>Back</strong>
							</a>
						</p>
					</div>
					<?php } ?>
					<br class="clearall">
				</div>
			</form>
		</div><!-- content -->
	</div><!-- main -->
	<?php printAdminFooter(); ?>
</body>