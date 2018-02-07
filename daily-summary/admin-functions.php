<?php

function initDailySummaryData()
{
	//TODO
}

function saveDailySummary()
{
    if (!isset($_POST["save"]) && !isset($_POST["dayCount"]))
        return;

    global $completedActionMessages;
    $completedActionMessages = [];
    
    $dayCount = $_POST["dayCount"];
    
    for ($imageID = 1; $imageID <= $dayCount; $imageID++)
    {
        $completedActionMessage = saveDailySummaryForImage($imageID);
        
        if (strlen($completedActionMessage) > 0) {
            $completedActionMessages[] = $completedActionMessage;
        }
    }
    
    $completedActionMessage = postSaveAction();
    if (strlen($completedActionMessage) > 0) {
        $completedActionMessages[] = $completedActionMessage;
    }
}

function postSaveAction()
{
    //TODO
}

function saveDailySummaryForImage($imageID)
{
    if (!isset($_POST["standout_" . $imageID])) {
        return;
    }

    $filename = $_POST["standout_" . $imageID];  	
	if ($filename == "") {
		return;
	}
	
	$updateSql = "UPDATE " . prefix('images') . " i " . 
		" SET i.daily_score = 1 " .
		" WHERE i.filename = '" . $filename . "'";
	query_full_array($updateSql);
    return "Flagged image: $filename";
}

function displayDailySummaryProcessingResults()
{
    if (getNumPhotostreamImages() == 0) {
        echo "<div class=\"messagebox\">No images to caption!</div>";
    }
    
    global $completedActionMessages;
    
    if (!isset($_POST["save"])) {
        return;
    }
    
    echo "<div class=\"messagebox fade-message\">";
    
    if (sizeof($completedActionMessages) == 0) {
        echo "No images updated!";
    }
    
    foreach ($completedActionMessages AS $message)
    {
        echo "$message<br>";
    }
    echo "</div>";
}

?>