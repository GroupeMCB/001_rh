<?php 

$title_for_layout = 'Personnels';
$title_for_page_menu = 'Personnels';
$current_menu = 'Agents par département';
  
echo $this->Session->Flash();

?>
<div class="col-xs-12"> <!-- required for floating -->
      <!-- Nav tabs -->
      <ul class="nav nav-tabs tabs-left">
        <li class="active"><a href="#home" data-toggle="tab">Home</a></li>
        <li><a href="#profile" data-toggle="tab">Profile</a></li>
        <li><a href="#messages" data-toggle="tab">Messages</a></li>
        <li><a href="#settings" data-toggle="tab">Settings</a></li>
      </ul>
    </div>
    <div class="col-xs-12">
      <!-- Tab panes -->
      <div class="tab-content">
        <div class="tab-pane active" id="home"><div id="carbonads" class="col-md-5">
		<span class="carbon-wrap">
		<img src="https://assets.servedby-buysellads.com/p/manage/asset/id/28536" alt="" border="0" height="100" width="130" style="max-width: 130px;">
		</span>
		Derrick HEDIHON - Responsable de département
</div></div>
        <div class="tab-pane" id="profile">Profile Tab.</div>
        <div class="tab-pane" id="messages">Messages Tab.</div>
        <div class="tab-pane" id="settings">Settings Tab.</div>
      </div>
    </div>
