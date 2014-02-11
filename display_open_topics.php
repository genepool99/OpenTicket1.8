<?php
/*********************************************************************
    display_open_topics.php

    Displays a block of the last X number of open tickets.

    Neil Tozier <tmib@tmib.net>
    Copyright (c)  2010-2013
    For use with osTicket version 1.7ST (http://www.osticket.com)
	
	Modified by Avi Solomon, 2014
	Now works with osTicket 1.8-git

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See osTickets's LICENSE.TXT for details.
**********************************************************************/

// The maximum amount of open tickets that you want to display.
$limit ='10';

$query = "SELECT *
			 FROM ost_ticket
			 WHERE status = 'open'
			 ORDER BY created DESC
			 LIMIT 0,$limit";



if($result=db_query($query)){
	$num = db_num_rows($result);
}

echo "<!-- Number of rows found = {$num} -->";

if ($num >> 0) {
?>

<table border-color=#BFBFBF border=0 cell-spacing=2><tr style='background-color: #BFBFBF;'>
	<td id='openticks-a'><b>Name</b></td>
	<td id='openticks-a'><b>Topic</b></td>
	<td id='openticks-a'><b>Issue</b></td>
	<td id='openticks-a'><b>Priority</b></td>
	<td id='openticks-a'><b>Opened on</b></td>
	<td id='openticks-b'><b>Last Update</b></td></tr>
	<br>

<?php
$i = 0;
while($row = db_fetch_array($result)){
	
	$sql = "SELECT name FROM osticket.ost_user where id = '{$row['user_id']}';";
	$subResult=db_query($sql);
	if($detailRow = db_fetch_array($subResult)){
		$name = $detailRow['name'];
	}
	else {
		$name = "Unknown";
	}
	
	$sql = "SELECT subject, priority FROM osticket.ost_ticket__cdata where ticket_id = '{$row['ticket_id']}';";
	$subResult=db_query($sql);
	if($detailRow = db_fetch_array($subResult)){
		$subject = $detailRow['subject'];
		$priority = $detailRow['priority'];
	}
	else {
		$subject = "Unknown";
		$priority = "Unknown";
	}
	
	$sql = "SELECT topic FROM osticket.ost_help_topic where topic_id = '{$row['topic_id']}';";
	$subResult=db_query($sql);
	if($detailRow = db_fetch_array($subResult)){
		$topic = $detailRow['topic'];
	}
	else {
		$topic = "Unknown";
	}
	
	$created = $row['created'];
	$updated = $row['updated'];
	
	if ($updated == '0000-00-00 00:00:00') {
	  $updated = 'no update yet';
	}
	else {
		$phpdate = strtotime( $updated );
		$updated = date( 'Y-m-d H:i:s', $phpdate );
	}
	 
	// change row back ground color to make more readable
	if(($i % 2) == 1)  //odd
		{$bgcolour = '#F6F6F6';}
	else   //even
		{$bgcolour = '#FEFEFE';}
		
	$phpdate = strtotime( $created );
	$created = date( 'Y-m-d', $phpdate );
	 
	echo "<tr align=center>"
			."<td BGCOLOR=$bgcolour id='openticks-a super-centered' nowrap> &nbsp; $name &nbsp; </td>"
			."<td BGCOLOR=$bgcolour id='openticks-a super-centered' nowrap> &nbsp; $topic &nbsp; </td>"
			."<td BGCOLOR=$bgcolour id='openticks-a super-centered' nowrap> &nbsp; $subject &nbsp; </td>"			
			."<td BGCOLOR=$bgcolour id='openticks-a super-centered' nowrap> &nbsp; $priority &nbsp; </td>"
			."<td BGCOLOR=$bgcolour id='openticks-a super-centered'> &nbsp; $created &nbsp; </td>"
			."<td BGCOLOR=$bgcolour id='openticks-b super-centered'> &nbsp; $updated &nbsp; </td></tr>";
	 
 	$i++;
}
echo "</table>";
}

else {
 echo "<p style='text-align:center;'><span id='msg_warning'>There are no tickets open at this time.</span></p>";
}
?>