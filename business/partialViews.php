<?php
class partialViews{

    /**
     * This function is to create the table of notifications of the admin news page
     * Takes in an array of all notifications and a page number for filtering
     */
    function createAdminConsoleNewsTable($notificationArray,$pageNum,$search=0){
        //initialize vars
        $string = "";
        $notiCount =0;
        $checkIndArray = array();
        $correctIndArray = array();

        //pagination stuff to choose which notifications to show
        foreach ($notificationArray as $ind=>$rowArray) {
            $correctInd = false;
            if($_SESSION['authID'] == 3 && $rowArray['sentBy'] == $_SESSION['userID']){
                $correctInd = true;
            }
            if ($_SESSION['authID'] == 4 || $correctInd) {
                array_push($checkIndArray,$ind);
            }
        }
        //max pages is number of notifications / 5
        $maxPages = ceil(count($checkIndArray)/5);
        if ($maxPages < $pageNum) $pageNum = $maxPages;
        $minIndex = ($pageNum*5)-5;
        $maxIndex = ($pageNum*5)-1;

        foreach ($checkIndArray as $arrayInd => $wantedInd) {
            if ($arrayInd >= $minIndex && $arrayInd <= $maxIndex){
                array_push($correctIndArray,$wantedInd);
            }
        }
        //actually create the string for the correct notifications
        foreach ($notificationArray as $ind => $rowArray) {
            //they are are dept head and they were the one who sent the notifcation. allow it to print
            $sentByUser=false;
            if($_SESSION['authID'] == 3 && $rowArray['sentBy'] == $_SESSION['userID']){
                //only show notifications sent by this user
                $sentByUser = true;
            }
            if ((($_SESSION['authID'] == 4 || $sentByUser) && (in_array($ind,$correctIndArray))) || $search) {
                $currNotiID = $rowArray['notificationID'];
                $currTitle = $rowArray['title'];
                $currSurvey = $rowArray['surveyLink'];
                $currBody = $rowArray['body'];
                //split file path using /
                //Take the end of the array becuse it is the name of the file
                $currAttachmentName = end(explode("/",$rowArray['attachment']));
                //echo "Attachment: $currAttachmentName";
                if ($currAttachmentName == ""){
                    $currAttachmentName = "No Attachment";
                }
                if ($currSurvey == ""){
                    $currSurvey = "No Survey";
                }
                $currActiveYN = (intval($rowArray['active']));

$string .= <<<END
                <form class="searchBarDelete" action="../phpScripts/formActions/newsAction.php?id={$currNotiID}?p={$pageNum}" method="post" enctype="multipart/form-data">
                <tr class='collapsed searchBarDelete'>
                    <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td>
                    <td>
                        <input type="text" name="title" disabled value="{$currTitle}">
                    </td>
                    <td>
                        <select disabled name='activeYN' class='disabledDrop'>
                            <option value='1'>Yes</option>
                            <option
END;
if (!$currActiveYN) $string .= " selected ";
$string .= <<<END
                             value='0'>No</option>
                        </select>
                    </td>
                    <td>
                        <i id='notiEditButton' onclick="dropDownModify(this,'noti');" class='fas fa-pencil-alt'></i>
                        <button class="hidden" id='notiSaveEditButton' type= "submit" name="modifyNoti" value="modifyNoti"><i class="fas fa-save"></i></button>
                    </td>
                    <td>
                        <button type="submit" name= "deleteNoti" value="deleteNoti"><i class="fas fa-trash-alt"></i></button>
                    </td>
                </tr>

                <tr class='spacer searchBarDelete'><td></td></tr>

                <tr class='collapsed searchBarDelete' style="display: none">
                    <td colspan='5' class='full'>
                        <h2>Body</h2>
                        <textarea id='bodyContent' name="body" disabled>{$currBody}</textarea>
                        <h2>Survey Link</h2>
                        <input type="text" class='block inputNoIcon' value="{$currSurvey}" disabled name="surveyLink">
                        <h2>Attachment</h2>
END;
if ($currAttachmentName != "No Attachment") $string .= '<button type="submit" name= "removeNotiAttachment" value="removeNotiAttachment"><i class="fas fa-times-circle"></i></button>';
else $string .= '<i class="fas fa-file-upload"></i><input class="hidden" type="file" name= "addNotiAttachment" value="addNotiAttachment">';
$string .= <<<END
                        <span id='attcName'>{$currAttachmentName}</span>
                        <h2>User Ack. Report</h2>
                        <i onclick="location.href='../phpScripts/downloadAckReport.php?id={$currNotiID}'" class="fas fa-download"></i><span>user_report.csv</span>
                    </td>
                </tr>
                <tr class='spacer searchBarDelete'><td></td></tr>
            </form>
END;
            }
        }
        return $string;
    }//end admin console news table

    /**
     * This creates the pagination links for the admin console news page
     */
    function makeAdminConsoleNewsPaginationLinks($pageNum,$notificationArray){
        $totalNumNotis = 0;
        foreach ($notificationArray as $ind=>$rowArray) {
            $correctInd = false;
            if($_SESSION['authID'] == 3 && $rowArray['sentBy'] == $_SESSION['userID']){
                $correctInd = true;
            }
            if ($_SESSION['authID'] == 4 || $correctInd) {
                $totalNumNotis++;
            }
        }
        //max of 5 notifications per page.
        $maxPages = ceil($totalNumNotis/5);
        //if this page is 1 then the previous page is also one
        //if not the pervious page is one less than current
        $prevPage = ($pageNum <= 1) ? (1) : ($pageNum-1);
        //if adding one to the page is more than max then dont add one
        //if not the next page is one more than current
        $nextPage = ($pageNum + 1 > $maxPages) ? ($maxPages) : ($pageNum+1);
        //low index based on page number
        $lowCount = (($pageNum*5)-5) +1;
        //high index based on page number
        $highCount = ($pageNum*5);
        //if the high index is too high set it equal to max
        if ($highCount > $totalNumNotis) $highCount = $totalNumNotis;

        $linkPage = ($_SESSION['authID'] == 4) ? ('adminConsoleNews.php') : ('deptHeadNotiConsole.php');

        if($totalNumNotis == 0){
            $returnString = "<div class='number inline'><span>None</span></div>";
            $returnString .= "<div class='back inline'><i class='fas fa-chevron-left'></i><span>Back</span></div>";
            $returnString .= "<div class='next inline'><span>Next</span><i class='fas fa-chevron-right'></i></div>";
        }
        else {
            $returnString = "<div class='number inline'><span>{$lowCount}-{$highCount} of {$totalNumNotis}</span></div>";
            $returnString .= "<a href='$linkPage?page={$prevPage}'><div class='back inline'><i class='fas fa-chevron-left'></i><span>Back</span></div></a>";
            $returnString .= "<a href='$linkPage?page={$nextPage}'><div class='next inline'><span>Next</span><i class='fas fa-chevron-right'></i></div></a>";
        }
        return $returnString;
    }//end admin console news table pagination links

    function createEmployeeTable($allUserArray,$pageNum,$search=0){
        //pagination stuff
        $maxPages = ceil(count($allUserArray)/5);
        if ($maxPages < $pageNum) $pageNum = $maxPages;
        if ($pageNum <= 1) $pageNum = 1;
        $minIndex = ($pageNum*5)-5;
        $maxIndex = ($pageNum*5)-1;

        $string = '';
        foreach ($allUserArray as $ind => $thisUserArray) {
            $currID = $thisUserArray['userID'];
            $currFName = $thisUserArray['fName'];
            $currLName = $thisUserArray['lName'];
            $currActiveYN = $thisUserArray['activeYN'];
            $currEmail = $thisUserArray['email'];
            $currDeptID = $thisUserArray['deptID'];
            $currAuthID = $thisUserArray['authID'];
            $currPhone = $thisUserArray['phone'];
            if (($currAuthID != 1 && ($minIndex <= $ind && $ind <= $maxIndex)) || $search) {

$string .= <<<END
            <form class="personForm searchBarDelete" action="../phpScripts/formActions/employeeAction.php?id={$currID}?p={$pageNum}" method="post">
                <tr class='collapsed searchBarDelete'>
                    <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td>
                    <td><input type="text" name="fName" disabled value="{$currFName}"></td>
                    <td><input type="text" name="lName" disabled value="{$currLName}"></td>
                    <td>
                        <i id='empEditButton' onclick="dropDownModify(this,'emp');" class='fas fa-pencil-alt'></i>
                        <button class="hidden" id='empSaveEditButton' type= "submit" name="modifyEmp" value="modifyEmp"><i class="fas fa-save" onclick=''></i></button>
                    </td>
                    <td>
                        <button type="submit" name= "deleteEmp" value="deleteEmp"><i class="fas fa-trash-alt"></i></button>
                    </td>
                </tr>
                <tr class='spacer searchBarDelete'><td></td></tr>
                <tr class='collapsed searchBarDelete' style="display: none">
                    <td colspan='3' class='leftUnCollapsed'>
                        <h2>Active</h2>
                        <select disabled name='activeYN' class='disabledDrop'>
                            <option value=1>Yes</option>
                            <option
END;
if (!$currActiveYN) $string .= " selected ";
$string .= <<<END
                        value=0>No</option>

                        </select>

                        <h2>Department</h2>
                        <select disabled name='deptID' class='disabledDrop'>
                            <option
END;
if ($currDeptID == 1) $string .= " selected ";
$string .= <<<END
                            value=1>HR</option>
                            <option
END;
if ($currDeptID == 2) $string .= " selected ";
$string .= <<<END
                            value=2>Admin</option>
                            <option
END;
if ($currDeptID == 3) $string .= " selected ";
$string .= <<<END
                            value=3>Sales</option>
                            <option
END;
if ($currDeptID == 4) $string .= " selected ";
$string .= <<<END
                            value=4>Production</option>
                            <option
END;
if ($currDeptID == 5) $string .= " selected ";
$string .= <<<END
                            value=5>Operations</option>
                            <option
END;
if ($currDeptID == 6) $string .= " selected ";
$string .= <<<END
                            value=6>Food and Beverage</option>
                            <option
END;
if ($currDeptID == 7) $string .= " selected ";
$string .= <<<END
                            value=7>Garage</option>
                        </select>

                        <h2>Email</h2>
                        <input type="text" name="email" class='email' disabled value="{$currEmail}">
                    </td>
                    <td colspan='2' class='rightUnCollapsed'>
                        <h2>Authorization</h2>
                        <select disabled name='authID' class='disabledDrop fullWidth'>
                            <option
END;
if ($currAuthID == 2) $string .= " selected ";
$string .= <<<END
                            value=2>Employee</option>
                            <option
END;
if ($currAuthID == 3) $string .= " selected ";
$string .= <<<END
                            value=3>Depart. Head</option>
                            <option
END;
if ($currAuthID == 4) $string .= " selected ";
$string .= <<<END
                            value=4>Administrator</option>
                        </select>
                        <h2>Phone Number</h2>
                        <input class="phoneMask" type="text" name="phone" disabled value="{$currPhone}">
                    </td>
                </tr>
            </form>
END;
        }
        }//end foreach

        return $string;
    }

    function createEmployeeTablePaginationLinks($allUserArray,$pageNum){
        $totalUsers = count($allUserArray);
        //max of 5 notifications per page.
        $maxPages = ceil($totalUsers/5);
        //if this page is 1 then the previous page is also one
        //if not the pervious page is one less than current
        $prevPage = ($pageNum <= 1) ? (1) : ($pageNum-1);
        //if adding one to the page is more than max then dont add one
        //if not the next page is one more than current
        $nextPage = ($pageNum + 1 > $maxPages) ? ($maxPages) : ($pageNum+1);
        //low index based on page number
        $lowCount = (($pageNum*5)-5) +1;
        //high index based on page number
        $highCount = ($pageNum*5);
        //if the high index is too high set it equal to max
        if ($highCount > $totalUsers) $highCount = $totalUsers;

        $returnString = "<div class='number inline'><span>{$lowCount}-{$highCount} of {$totalUsers}</span></div>";
        $returnString .= "<a href='adminConsoleEmployee.php?page={$prevPage}'><div class='back inline'><i class='fas fa-chevron-left'></i><span>Back</span></div></a>";
        $returnString .= "<a href='adminConsoleEmployee.php?page={$nextPage}'><div class='next inline'><span>Next</span><i class='fas fa-chevron-right'></i></div></a>";
        return $returnString;
    }

    function createPendingUserTable($allUserArray,$pageNum){
        //pagination stuff
        $maxPages = ceil(count($allUserArray)/5);
        if ($maxPages < $pageNum) $pageNum = $maxPages;
        $minIndex = ($pageNum*5)-5;
        $maxIndex = ($pageNum*5)-1;
        $string = '';

        foreach ($allUserArray as $ind => $thisUserArray) {
            $currID = $thisUserArray['userID'];
            $currFName = $thisUserArray['fName'];
            $currLName = $thisUserArray['lName'];
            $currActiveYN = $thisUserArray['activeYN'];
            $currEmail = $thisUserArray['email'];
            $currDeptID = $thisUserArray['deptID'];
            $currAuthID = $thisUserArray['authID'];
            $currPhone = $thisUserArray['phone'];

            if ($minIndex <= $ind && $ind <= $maxIndex) {
$string .= <<<END
                <form class="" action="../phpScripts/formActions/pendingAction.php?id={$currID}" method="post">
                    <tr class='collapsed'>
                        <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td>
                        <td>{$currFName}</td>
                        <td>{$currLName}</td>
                        <td>
                            <button type="submit" name= "confirmPendEmp" value="confirmPendEmp"><i class="fas fa-check-circle"></i></button>
                        </td>
                        <td>
                            <button type="submit" name= "denyPendEmp" value="denyPendEmp"><i class="fas fa-minus-circle"></i></button>
                        </td>
                    </tr>

                    <tr class='spacer'><td></td></tr>
                    <tr class='un-collapsed'>
                        <td colspan="5">
                            <h2>Authorization Level</h2>
                            <select name='pendingAuthID' id='authLevel'>
                                <option value=2>Employee</option>
                                <option value=3>Department Head</option>
                                <option value=4>Admin</option>
                            </select>
                        </td>
                    </tr>
                    <tr class='spacer'><td></td></tr>
                </form>
END;
            }
        }
        return $string;
    }

    function createPendingTablePaginationLinks($allUserArray,$pageNum){
        $totalUsers = count($allUserArray);
        //max of 5 notifications per page.
        $maxPages = ceil($totalUsers/5);
        //if this page is 1 then the previous page is also one
        //if not the pervious page is one less than current
        $prevPage = ($pageNum <= 1) ? (1) : ($pageNum-1);
        //if adding one to the page is more than max then dont add one
        //if not the next page is one more than current
        $nextPage = ($pageNum + 1 > $maxPages) ? ($maxPages) : ($pageNum+1);
        //low index based on page number
        $lowCount = (($pageNum*5)-5) +1;
        //high index based on page number
        $highCount = ($pageNum*5);
        //if the high index is too high set it equal to max
        if ($highCount > $totalUsers) $highCount = $totalUsers;

        if($totalUsers == 0){
            $returnString = "<div class='number inline'><span>None</span></div>";
            $returnString .= "<div class='back inline'><i class='fas fa-chevron-left'></i><span>Back</span></div>";
            $returnString .= "<div class='next inline'><span>Next</span><i class='fas fa-chevron-right'></i></div>";
        }
        else {
            $returnString = "<div class='number inline'><span>{$lowCount}-{$highCount} of {$totalUsers}</span></div>";
            $returnString .= "<a href='adminConsolePending.php?page={$prevPage}'><div class='back inline'><i class='fas fa-chevron-left'></i><span>Back</span></div></a>";
            $returnString .= "<a href='adminConsolePending.php?page={$nextPage}'><div class='next inline'><span>Next</span><i class='fas fa-chevron-right'></i></div></a>";
        }
        return $returnString;
    }

    function createIndividualNotification($notiArray, $imgNum){
        $currTitle = $notiArray[0]['title'];
        $currNotiID = $notiArray[0]['notificationID'];
        $currBody = $notiArray[0]['body'];
        $currSurvey = $notiArray[0]['surveyLink'];
        $timeStamp = $notiArray[0]['postDate'];
        $currAttachmentName = end(explode("/",$notiArray[0]['attachment']));
        //echo "Attachment: $currAttachmentName";
        if ($currAttachmentName == ""){
            $currAttachmentName = "No Attachment";
        }

        $now = new DateTime(null, new DateTimeZone('America/New_York'));
        $dateStamp = new DateTime($timeStamp,new DateTimeZone('America/New_York'));
        $mins = $dateStamp->diff($now)->format("%i");
        $hours = $dateStamp->diff($now)->format("%h");
        $days = $dateStamp->diff($now)->format("%d");
        //less than an hour use mins
        if (intval($hours) < 1){
            $timesig = $mins."m ago";
        }
        else if (intval($days) < 1) {
            //display using hours
            $timesig = $hours."h ago";
        }
        else if (intval($days) >= 1 && intval($days) <= 6) {
            //display using days
            $timesig = $days."d ago";
        }
        else if (intval($days) >= 7){
            //display using weeks
            $timesig = ($days%7)."w ago";
        }

        $string = <<<END
        <div class='imageContainer'>
            <div class='overlay'>
                <img src='../assets/images/{$imgNum}.jpg'>
            </div>
        </div>

        <!-- Content -->
        <div class='container'>
            <h2 class='title'>{$currTitle}</h2>
            <div class='subtitle block'>
END;
        if ($currAttachmentName != "No Attachment"){
            $string .=<<<END
            <i onclick="location.href='../phpScripts/downloadNotiAttachment.php?id={$currNotiID}'" class="fas fa-download inline"></i>
            <span class='inline'>{$currAttachmentName}</span>
END;
        }
        $string .=<<<END
                <i class="far fa-clock inline"></i>
                <span class='inline'>{$timesig}</span>
            </div>
            <span class='copy block'>{$currBody}</span>
END;
        if (strlen($currSurvey) > 2 && $currSurvey != "https://"){;
            $string .= "<a href='{$currSurvey}' target='_blank'><i class='fas fa-link'></i>Survey Link</a>";
        }
$string .= <<<END
            <form action="../phpScripts/notiAck.php?id={$currNotiID}&img={$imgNum}" method="post">
                <button type="submit">I Acknowledge</button>
            </form>
        </div>
END;
    return $string;
    }

    function createLandingNewsTable($notificationArray){
        $string = "";
        $imgNum = 1;
        foreach ($notificationArray as $currNotiArray) {
            //if they are in the correct dept or admin / HR show the notification
            if (in_array($_SESSION['deptID'],str_split($currNotiArray['viewableBy'])) || $_SESSION['deptID'] == 1 || $_SESSION['deptID'] == 2) {
                $currNotiID = $currNotiArray['notificationID'];
                $currTitle = $currNotiArray['title'];
                $currBody = $currNotiArray['body'];
                $timesig = $currNotiArray['time'];
                $webAppYN = $currNotiArray['webAppYN'];
                $activeYN = $currNotiArray['active'];

                $now = new DateTime(null, new DateTimeZone('America/New_York'));
                $dateStamp = new DateTime($timesig,new DateTimeZone('America/New_York'));

                $mins = $dateStamp->diff($now)->format("%i");
                $hours = $dateStamp->diff($now)->format("%h");
                $days = $dateStamp->diff($now)->format("%d");
                if (intval($hours) < 1){
                    $timesig = $mins."m ago";
                }
                else if (intval($days) < 1) {
                    //display using hours
                    $timesig = $hours."h ago";
                }
                else if (intval($days) >= 1 && intval($days) <= 6) {
                    //display using days
                    $timesig = $days."d ago";
                }
                else if (intval($days) >= 7){
                    //display using weeks
                    $timesig = ($days%7)."w ago";
                }

                if ($imgNum  <= 5){
                    $imgNum++;
                }
                else {
                    $imgNum = 1;
                }
                if($webAppYN && $activeYN){
    $string .= <<<END
                        <div class='notifContainer' id='{$currNotiID}'>
                            <div class='overlay'>
                                <img style='background-image:url(../../assets/images/{$imgNum}.jpg);'>
                            </div>

                            <h2 class='title'>{$currTitle}</h2>

                            <div class='subtitle block'>
                                <div class='posted inline'>
                                    <i class="far fa-clock"></i>
                                    <span class='inline'>{$timesig}</span>
                                </div>
                                <a type='submit' class='inline' href='notification.php?id={$currNotiID}&img={$imgNum}'>read more</a>
                            </div>

                            <!-- Admin Feature only -->
                            <button onclick="displayOptions(this);" type="button" class="button
END;
    if ($_SESSION['authID'] < 4) $string .= " hidden";
    $string .= <<<END
    "><i class="far fa-edit"></i></button>
                            <div class='buttonOptions' style="display:none" >
                                <ul class='spaced'>
                                    <li onclick="ajaxDelete({$currNotiID},this,'news')">Delete<i class="fas fa-trash-alt"></i></li>
                                </ul>
                            </div>
                        </div>
END;
                }
            }
            else {
                // code...
            }
        }
        return $string;
    }

    function createVideoPage($videoArray){

        $tableString = "";
        foreach ($videoArray as $ind => $currVid) {
            $currID = $currVid['videoID'];
            $currLink = $currVid['link'];
            $tableString .= "<div class='videoContainer' id='$currID'>";
            if ($_SESSION['authID'] == 4){
                     $tableString .=<<<END
                     <button onclick="displayOptions(this)" type="button" class="button"><i class="far fa-edit"></i></button>
                     <div class='buttonOptions' style="display:none" >
                         <ul class='spaced'>
                             <li onclick="addNewVid()">Create New<i class="fas fa-plus-circle"></i></li>
                             <li onclick="updateVid($currID)">Edit Link<i class='fas fa-pencil-alt'></i></li>
                             <li onclick="deleteVid($currID)">Delete<i class="fas fa-trash-alt"></i>
                             <form id="delform_$currID" method="POST" action="../phpScripts/formActions/videoAction.php?delete=$currID"></li>
                             </form>
                         </ul>
                     </div>
END;
            }
            $tableString .=<<<END
            <div class='overlay'>
                <iframe src="$currLink" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
END;
        }
        return $tableString;
    }

}
?>
