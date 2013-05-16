<?php 
if(isset($contact)) { 
 echo LoadUserPhoneNumberTable(false,false,false,$contact); 
}else {
 echo LoadUserPhoneNumberTable(false,$uid); 
}?>