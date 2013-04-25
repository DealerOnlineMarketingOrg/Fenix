<?php
	if (isset($contact)) {
		echo ContactInfoListingTable($contact,$type,$page);
	} else
		echo ContactInfoListingTable();
?>