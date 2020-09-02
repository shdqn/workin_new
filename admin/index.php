<?php

session_start();
include("includes/db.php");
include("../functions/mailer.php");
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login','_self');</script>";
}

if((time() - $_SESSION['loggedin_time']) > 9800){
	echo "<script>window.open('logout?session_expired','_self');</script>";
}

if(!$_SESSION['adminLanguage']){
	$_SESSION['adminLanguage'] = 1;
}

$adminLanguage = $_SESSION['adminLanguage'];
$row_language = $db->select("languages",array("id"=>$adminLanguage))->fetch();
$currentLanguage = $row_language->title;
$admin_email = $_SESSION['admin_email'];

$get_admin = $db->query("select * from admins where admin_email=:a_email OR admin_user_name=:a_user_name",array("a_email"=>$admin_email,"a_user_name"=>$admin_email));
$row_admin = $get_admin->fetch();
$admin_id = $row_admin->admin_id;
$login_admin_id = $row_admin->admin_id;
$admin_name = $row_admin->admin_name;
$admin_user_name = $row_admin->admin_user_name;
$admin_image = $row_admin->admin_image;
$admin_country = $row_admin->admin_country;
$admin_job = $row_admin->admin_job;
$admin_contact = $row_admin->admin_contact;
$admin_about = $row_admin->admin_about;

$get_rights = $db->select("admin_rights",array("admin_id" => $admin_id));
$row_rights = $get_rights->fetch();
$a_settings = $row_rights->settings;
$a_plugins = $row_rights->plugins;
$a_pages = $row_rights->pages;
$a_blog = $row_rights->blog;
$a_feedback = $row_rights->feedback;
$a_video_schedules = $row_rights->video_schedules;
$a_proposals = $row_rights->proposals;
$a_accounting = $row_rights->accounting;
$a_payouts = $row_rights->payouts;
$a_reports = $row_rights->reports;
$a_inbox = $row_rights->inbox;
$a_reviews = $row_rights->reviews;
$a_buyer_requests = $row_rights->buyer_requests;
$a_restricted_words = $row_rights->restricted_words;
$a_alerts = $row_rights->notifications;
$a_cats = $row_rights->cats;
$a_delivery_times = $row_rights->delivery_times;
$a_seller_languages = $row_rights->seller_languages;
$a_seller_skills = $row_rights->seller_skills;
$a_seller_levels = $row_rights->seller_levels;
$a_customer_support = $row_rights->customer_support;
$a_coupons = $row_rights->coupons;
$a_slides = $row_rights->slides;
$a_sellers = $row_rights->sellers;
$a_slides = $row_rights->slides;
$a_terms = $row_rights->terms;
$a_orders = $row_rights->orders;
$a_referrals = $row_rights->referrals;
$a_files = $row_rights->files;
$a_knowledge_bank = $row_rights->knowledge_bank;
$a_currencies = $row_rights->currencies;
$a_languages = $row_rights->languages;
$a_admins = $row_rights->admins;

$count_sellers = $db->count("sellers");
$count_notifications = $db->count("admin_notifications",array("status" => "unread"));
$count_orders = $db->count("orders",array("order_active" => "yes"));
$count_proposals = $db->count("proposals",array("proposal_status" => "pending"));
$count_support_tickets = $db->count("support_tickets",array("status" => "open"));
$count_requests = $db->count("buyer_requests",array("request_status" => "pending"));
$count_referrals = $db->count("referrals",array("status" => "pending"));
$count_proposals_referrals = $db->count("proposal_referrals",array("status" => "pending"));

function autoLoader($className){
  require_once("../functions/$className.php");
}
spl_autoload_register('autoLoader');

$core = new Core;
$paymentGateway = $core->checkPlugin("paymentGateway");
$videoPlugin = $core->checkPlugin("videoPlugin");

?>
<!DOCTYPE html>
<html class="no-js">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Admin Panel - Control Your Entire Site.</title>
	<meta name="description" content="With the GigToDoScript admin panel, controlling your website has never been eassier.">
	<meta name="author" content="GigToDoScript">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" href="apple-icon.png">
	<link rel="stylesheet" href="assets/css/normalize.css">
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link href="../styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/themify-icons.css">
	<link rel="stylesheet" href="assets/css/flag-icon.min.css">
	<link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
	<link rel="stylesheet" href="assets/scss/style.css">
	<?php if(!empty($site_favicon)){ ?>
		<link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
	<?php } ?>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800'rel='stylesheet'type='text/css'>
	<link rel="stylesheet" href="assets/css/sweat_alert.css" >
	<script type="text/javascript" src="assets/js/ie.js"></script>
	<script type="text/javascript" src="assets/js/sweat_alert.js"></script>
	<script src="../js/jquery.min.js"></script>
	<script>
	
	function alert_error(text){
		Swal('',text,'error');
	}

	function alert_success(text,url){
	  swal({
	  type: 'success',
	  timer : 3000,
	  text: text,
	  onOpen: function(){
	    swal.showLoading()
	  }
	  }).then(function(){
	  	if(url != ""){
			window.open(url,'_self');
		}
	  });
	}
	
	function alert_error(text,url){
	  swal({
		type: 'error',
		timer: 3000,
		text: text,
		onOpen: function(){
		 swal.showLoading()
		}
	  }).then(function(){
	  	if(url != ""){
			window.open(url,'_self');
		}
	  });
	}
	
	function alert_confirm(text,url){
		swal({
		  text: text,
		  type: 'warning',
		  showCancelButton: true 
		}).then((result) => {
			if(result.value){
			  if(url != ""){ window.open(url,'_self'); }
			}
		});
	}

	</script>
</head>
<body>
  <script src="assets/js/minimal.js"></script>
  <!-- Left Panel -->
  <aside id="left-panel" class="left-panel">
		<nav class="navbar navbar-expand-sm navbar-default">
		  <div class="navbar-header">
		    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu">
		        <i class="fa fa-bars"></i>
		    </button>
		    <a class="navbar-brand" href="index?dashboard">
		    <?= $site_name; ?> <span class="badge badge-success p-2 font-weight-bold">ADMIN</span>
			</a>
		    <a class="navbar-brand hidden" href="./"><span class="badge badge-success pt-2 pb-2">A</span></a>
		  </div>
		  <div id="main-menu" class="main-menu collapse navbar-collapse">
		      <?php include("includes/sidebar.php"); ?>
		  </div>
		</nav>
  </aside>
  <!-- Left Panel -->

  <!-- Right Panel -->
  <div id="right-panel" class="right-panel">

	<!-- Header-->
	<header id="header" class="header">
		<div class="header-menu"><?php include("includes/admin_header.php"); ?></div>
	</header>
  	<!-- Header-->

	<?php

	if(isset($_GET['dashboard'])){
		include("dashboard.php");
	}

	if($a_admins == 1){
	}

	if($a_settings == 1){
		if(isset($_GET['general_settings'])){
		include("general_settings.php");
		}
		if(isset($_GET['insert_card'])){
		include("insert_card.php");
		}
		if(isset($_GET['delete_card'])){
		include("delete_card.php");
		}
		if(isset($_GET['edit_card'])){
		include("edit_card.php");
		}
		if(isset($_GET['insert_box'])){
		include("insert_box.php");
		}
		if(isset($_GET['delete_box'])){
		include("delete_box.php");
		}
		if(isset($_GET['edit_box'])){
		include("edit_box.php");
		}
		if(isset($_GET['layout_settings'])){
		include("layout_settings.php");
		}
		if(isset($_GET['edit_link'])){
		include("edit_link.php");
		}
		if(isset($_GET['delete_link'])){
		include("delete_link.php");
		}
		if(isset($_GET['payment_settings'])){
		include("payment_settings.php");
		}
		if(isset($_GET['get_provider_id'])){
		include("get_provider_id.php");
		}
		if(isset($_GET['insert_home_slide'])){
		include("insert_home_slide.php");
		}
		if(isset($_GET['delete_home_slide'])){
		include("delete_home_slide.php");
		}
		if(isset($_GET['edit_home_slide'])){
		include("edit_home_slide.php");
		}
		if(isset($_GET['mail_settings'])){
		include("mail_settings.php");
		}
		if(isset($_GET['email_templates'])){
		include("email_templates.php");
		}
		if(isset($_GET['api_settings'])){
		include("api_settings.php");
		}
		if(isset($_GET['app_update'])){
		include("app_update.php");
		}
	}

	/// Plugins

	if($a_plugins == 1){
		if(isset($_GET['plugins'])){
			include("plugins.php");
		}
		if(isset($_GET['update_plugin'])){
			include("update_plugin.php");
		}
		if(isset($_GET['add_plugin'])){
			include("add_plugin.php");
		}
		if(isset($_GET['delete_plugin'])){
			include("delete_plugin.php");
		}
		if(isset($_GET['activate_plugin'])){
			include("activate_plugin.php");
		}
		if(isset($_GET['deactivate_plugin'])){
			include("deactivate_plugin.php");
		}
	}

	if($a_pages == 1){

		if(isset($_GET['pages'])){
			include("pages.php");
		}
		if(isset($_GET['insert_page'])){
			include("insert_page.php");
		}
		if(isset($_GET['edit_page'])){
			include("edit_page.php");
		}
		if(isset($_GET['delete_page'])){
			include("delete_page.php");
		}

	}

	// Posts
	if($a_blog == 1){
		if(isset($_GET['post_categories'])){
			include("blog/categories/view.php");
		}
		if(isset($_GET['edit_post_cat'])){
			include("blog/categories/edit.php");
		}
		if(isset($_GET['delete_post_cat'])){
			include("blog/categories/delete.php");
		}
		if(isset($_GET['posts'])){
			include("blog/posts/view.php");
		}
		if(isset($_GET['insert_post'])){
			include("blog/posts/insert.php");
		}
		if(isset($_GET['edit_post'])){
			include("blog/posts/edit.php");
		}
		if(isset($_GET['delete_post'])){
			include("blog/posts/delete.php");
		}

		if(isset($_GET['post_comments'])){
			include("blog/comments/view.php");
		}

		if(isset($_GET['delete_post_comment'])){
			include("blog/comments/delete.php");
		}

	}

	if($a_feedback == 1){
		
		if(isset($_GET['ideas'])){
			include("feedback/ideas/view.php");
		}

		if(isset($_GET['delete_idea'])){
			include("feedback/ideas/delete.php");
		}

		if(isset($_GET['comments'])){
			include("feedback/comments/view.php");
		}

		if(isset($_GET['delete_comment'])){
			include("feedback/comments/delete.php");
		}

	}

	if($videoPlugin == 1 & $a_video_schedules){
		
		if(isset($_GET['insert_schedule'])){
			include("../plugins/videoPlugin/admin/insert_schedule.php");
		}
		if(isset($_GET['view_schedules'])){
			include("../plugins/videoPlugin/admin/view_schedules.php");
		}
		if(isset($_GET['edit_schedule'])){
			include("../plugins/videoPlugin/admin/edit_schedule.php");
		}
		if(isset($_GET['delete_schedule'])){
			include("../plugins/videoPlugin/admin/delete_schedule.php");
		}

	}

	if($a_proposals == 1){

		if(isset($_GET['view_proposals'])){
		include("view_proposals.php");
		}
		if(isset($_GET['view_proposals_active'])){
		include("view_proposals_active.php");
		}
		if(isset($_GET['view_proposals_featured'])){
		include("view_proposals_featured.php");
		}
		if(isset($_GET['view_proposals_pending'])){
		include("view_proposals_pending.php");
		}
		if(isset($_GET['view_proposals_paused'])){
		include("view_proposals_paused.php");
		}
		if(isset($_GET['view_proposals_trash'])){
		include("view_proposals_trash.php");
		}
		if(isset($_GET['pause_proposal'])){
		include("pause_proposal.php");
		}
		if(isset($_GET['feature_proposal'])){
		include("feature_proposal.php");
		}
		if(isset($_GET['remove_feature_proposal'])){
		include("remove_feature_proposal.php");
		}
		if(isset($_GET['toprated_proposal'])){
		include("toprated_proposal.php");
		}
		if(isset($_GET['removetoprated_proposal'])){
		include("removetoprated_proposal.php");
		}
		if(isset($_GET['unpause_proposal'])){
		include("unpause_proposal.php");
		}
		if(isset($_GET['move_to_trash'])){
		include("move_to_trash.php");
		}
		if(isset($_GET['decline_proposal'])){
		include("decline_proposal.php");
		}
		if(isset($_GET['approve_proposal'])){
		include("approve_proposal.php");
		}
		if(isset($_GET['submit_modification'])){
			include("submit_modification.php");
		}
		if(isset($_GET['restore_proposal'])){
			include("restore_proposal.php");
		}
		if(isset($_GET['delete_proposal'])){
			include("delete_proposal.php");
		}

	}

	if($a_accounting == 1){

		if(isset($_GET['sales'])){
			include("sales.php");
		}
		if(isset($_GET['expenses'])){
			include("expenses.php");
		}
		if(isset($_GET['delete_expense'])){
			include("delete_expense.php");
		}

	}

	if($a_payouts == 1){
		if(isset($_GET['payouts'])){
			include("payouts.php");
		}
		if(isset($_GET['approve_payout'])){
			include("approve_payout.php");
		}

		if($paymentGateway == 1){
			if(isset($_GET['approve_moneygram'])){
				include("../plugins/paymentGateway/admin/approve_moneygram.php");
			}
		}
	
		if(isset($_GET['decline_payout'])){
			include("decline_payout.php");
		}
		if(isset($_GET['completed_transactions'])){
			include("completed_transactions.php");
		}

	}

	if($a_reports == 1){

		if(isset($_GET['order_reports'])){
			include("order_reports.php");
		}
		if(isset($_GET['message_reports'])){
			include("message_reports.php");
		}
		if(isset($_GET['proposal_reports'])){
			include("proposal_reports.php");
		}
		if(isset($_GET['delete_order_report'])){
			include("delete_order_report.php");
		}
		if(isset($_GET['delete_message_report'])){
			include("delete_message_report.php");
		}
		if(isset($_GET['delete_proposal_report'])){
			include("delete_proposal_report.php");
		}

	}

	if($a_inbox == 1){

		if(isset($_GET['inbox_conversations'])){
			include("inbox_conversations.php");
		}
		if(isset($_GET['single_inbox_message'])){
			include("single_inbox_message.php");
		}

	}

	if($a_reviews == 1){

		if(isset($_GET['insert_review'])){
			include("insert_review.php");
		}
		if(isset($_GET['view_buyer_reviews'])){
			include("view_buyer_reviews.php");
		}
		if(isset($_GET['delete_buyer_review'])){
			include("delete_buyer_review.php");
		}
		if(isset($_GET['view_seller_reviews'])){
			include("view_seller_reviews.php");
		}
		if(isset($_GET['delete_seller_review'])){
			include("delete_seller_review.php");
		}

	}

	if($a_buyer_requests == 1){

		if(isset($_GET['buyer_requests'])){
			include("buyer_requests.php");
		}
		if(isset($_GET['delete_request'])){
			include("delete_request.php");
		}
		if(isset($_GET['approve_request'])){
			include("approve_request.php");
		}
		if(isset($_GET['unapprove_request'])){
			include("unapprove_request.php");
		}

	}

	if($a_restricted_words == 1){

		if(isset($_GET['insert_word'])){
			include("insert_word.php");
		}
		if(isset($_GET['view_words'])){
			include("view_words.php");
		}
		if(isset($_GET['delete_word'])){
			include("delete_word.php");
		}
		if(isset($_GET['edit_word'])){
			include("edit_word.php");
		}

	}

	if($a_alerts == 1){

		if(isset($_GET['view_notifications'])){
			include("view_notifications.php");
		}
		if(isset($_GET['delete_notification'])){
			include("delete_notification.php");
		}

	}

	if($a_cats == 1){

		if(isset($_GET['insert_cat'])){
			include("insert_cat.php");
		}
		if(isset($_GET['view_cats'])){
			include("view_cats.php");
		}
		if(isset($_GET['delete_cat'])){
			include("delete_cat.php");
		}
		if(isset($_GET['edit_cat'])){
			include("edit_cat.php");
		}
		if(isset($_GET['insert_child_cat'])){
			include("insert_child_cat.php");
		}
		if(isset($_GET['view_child_cats'])){
			include("view_child_cats.php");
		}
		if(isset($_GET['delete_child_cat'])){
			include("delete_child_cat.php");
		}
		if(isset($_GET['edit_child_cat'])){
			include("edit_child_cat.php");
		}

	}

	if($a_delivery_times == 1){

		if(isset($_GET['insert_delivery_time'])){
			include("insert_delivery_time.php");
		}
		if(isset($_GET['view_delivery_times'])){
			include("view_delivery_times.php");
		}
		if(isset($_GET['delete_delivery_time'])){
			include("delete_delivery_time.php");
		}
		if(isset($_GET['edit_delivery_time'])){
			include("edit_delivery_time.php");
		}

	}

	if($a_seller_languages == 1){

		if(isset($_GET['insert_seller_language'])){
			include("insert_seller_language.php");
		}
		if(isset($_GET['view_seller_languages'])){
			include("view_seller_languages.php");
		}
		if(isset($_GET['delete_seller_language'])){
			include("delete_seller_language.php");
		}
		if(isset($_GET['edit_seller_language'])){
			include("edit_seller_language.php");
		}

	}

	if($a_seller_skills == 1){

		if(isset($_GET['insert_seller_skill'])){
			include("insert_seller_skill.php");
		}
		if(isset($_GET['view_seller_skills'])){
			include("view_seller_skills.php");
		}
		if(isset($_GET['delete_seller_skill'])){
			include("delete_seller_skill.php");
		}
		if(isset($_GET['edit_seller_skill'])){
			include("edit_seller_skill.php");
		}

	}

	if($a_seller_levels == 1){

		if(isset($_GET['view_seller_levels'])){
			include("view_seller_levels.php");
		}
		if(isset($_GET['edit_seller_level'])){
			include("edit_seller_level.php");
		}

	}

	if($a_customer_support == 1){

		if(isset($_GET['customer_support_settings'])){
			include("customer_support_settings.php");
		}
		if(isset($_GET['view_support_requests'])){
			include("view_support_requests.php");
		}
		if(isset($_GET['single_request'])){
			include("single_request.php");
		}
		if(isset($_GET['insert_enquiry_type'])){
			include("insert_enquiry_type.php");
		}
		if(isset($_GET['view_enquiry_types'])){
			include("view_enquiry_types.php");
		}
		if(isset($_GET['delete_enquiry_type'])){
			include("delete_enquiry_type.php");
		}
		if(isset($_GET['edit_enquiry_type'])){
			include("edit_enquiry_type.php");
		}

	}

	if($a_coupons == 1){

		if(isset($_GET['insert_coupon'])){
			include("insert_coupon.php");
		}
		if(isset($_GET['view_coupons'])){
			include("view_coupons.php");
		}
		if(isset($_GET['delete_coupon'])){
			include("delete_coupon.php");
		}
		if(isset($_GET['edit_coupon'])){
			include("edit_coupon.php");
		}

	}

	if($a_slides == 1){

		if(isset($_GET['insert_slide'])){
			include("insert_slide.php");
		}
		if(isset($_GET['view_slides'])){
			include("view_slides.php");
		}
		if(isset($_GET['delete_slide'])){
			include("delete_slide.php");
		}
		if(isset($_GET['edit_slide'])){
			include("edit_slide.php");
		}

	}

	if($a_terms == 1){

		if(isset($_GET['insert_term'])){
			include("insert_term.php");
		}
		if(isset($_GET['view_terms'])){
			include("view_terms.php");
		}
		if(isset($_GET['delete_term'])){
			include("delete_term.php");
		}
		if(isset($_GET['edit_term'])){
			include("edit_term.php");
		}

	}


	if($a_sellers == 1){

		if(isset($_GET['view_sellers'])){
			include("view_sellers.php");
		}
		if(isset($_GET['single_seller'])){
			include("single_seller.php");
		}
		if(isset($_GET['seller_login'])){
			include("seller_login.php");
		}
		if(isset($_GET['update_balance'])){
			include("update_balance.php");
		}
		if(isset($_GET['unblock_seller'])){
			include("unblock_seller.php");
		}
		if(isset($_GET['ban_seller'])){
			include("ban_seller.php");
		}
		if(isset($_GET['verify_email'])){
			include("verify_email.php");
		}

	}

	if($a_orders == 1){

		if(isset($_GET['view_orders'])){
			include("view_orders.php");
		}
		if(isset($_GET['filter_orders'])){
			include("filter_orders.php");
		}
		if(isset($_GET['single_order'])){
			include("single_order.php");
		}
		if(isset($_GET['cancel_order'])){
			include("cancel_order.php");
		}

	}

	if($a_referrals == 1){

		if(isset($_GET['view_referrals'])){
			include("view_referrals.php");
		}
		if(isset($_GET['approve_referral'])){
			include("approve_referral.php");
		}
		if(isset($_GET['decline_referral'])){
			include("decline_referral.php");
		}
		if(isset($_GET['view_proposal_referrals'])){
			include("view_proposal_referrals.php");
		}
		if(isset($_GET['approve_proposal_referral'])){
			include("approve_proposal_referral.php");
		}
		if(isset($_GET['decline_proposal_referral'])){
			include("decline_proposal_referral.php");
		}

	}


	if($a_files == 1){

		if(isset($_GET['view_proposals_files'])){
			include("view_proposals_files.php");
		}
		if(isset($_GET['view_s3_proposals_files'])){
			include("view_s3_proposals_files.php");
		}
		if(isset($_GET['delete_proposal_file'])){
			include("delete_proposal_file.php");
		}
		if(isset($_GET['view_inbox_files'])){
			include("view_inbox_files.php");
		}
		if(isset($_GET['inbox_files_pagination'])){
			include("inbox_files_pagination.php");
		}
		if(isset($_GET['delete_inbox_file'])){
			include("delete_inbox_file.php");
		}
		if(isset($_GET['view_order_files'])){
			include("view_order_files.php");
		}
		if(isset($_GET['order_files_pagination'])){
			include("order_files_pagination.php");
		}
		if(isset($_GET['delete_order_file'])){
			include("delete_order_file.php");
		}

	}

	if($a_knowledge_bank == 1){

		if(isset($_GET['insert_article'])){
			include("insert_article.php");
		}
		if(isset($_GET['view_articles'])){
			include("view_articles.php");
		}
		if(isset($_GET['delete_article'])){
			include("delete_article.php");
		}
		if(isset($_GET['edit_article'])){
			include("edit_article.php");
		}
		if(isset($_GET['insert_article_cat'])){
			include("insert_article_cat.php");
		}
		if(isset($_GET['view_article_cats'])){
		include("view_article_cats.php");
		}
		if(isset($_GET['delete_article_cat'])){
		include("delete_article_cat.php");
		}
		if(isset($_GET['edit_article_cat'])){
		include("edit_article_cat.php");
		}

	}

	if($a_currencies == 1){

		if(isset($_GET['insert_currency'])){
			include("insert_currency.php");
		}

		if(isset($_GET['view_currencies'])){
			include("view_currencies.php");
		}

		if(isset($_GET['edit_currency'])){
			include("edit_currency.php");
		}

		if(isset($_GET['delete_currency'])){
			include("delete_currency.php");
		}

	}

	if(isset($_GET['change_language'])){
		include("change_language.php");
	}

	if($a_languages == 1){

		if(isset($_GET['insert_language'])){
		include("insert_language.php");
		}
		if(isset($_GET['edit_language'])){
		include("edit_language.php");
		}
		if(isset($_GET['delete_language'])){
		include("delete_language.php");
		}
		if(isset($_GET['language_settings'])){
		include("language_settings.php");
		}
		if(isset($_GET['view_languages'])){
		include("view_languages.php");
		}

	}

	if(isset($_GET['view_withdrawals'])){
		include("view_withdrawals.php");
	}

	if($a_admins == 1){

		if(isset($_GET['admin_logs'])){
			include("admin_logs.php");
		}
		if(isset($_GET['delete_log'])){
			include("delete_log.php");
		}
		if(isset($_GET['delete_all_logs'])){
			include("delete_all_logs.php");
		}
		if(isset($_GET['insert_user'])){
			include("insert_user.php");
		}
		if(isset($_GET['view_users'])){
			include("view_users.php");
		}
		if(isset($_GET['delete_user'])){
			include("delete_user.php");
		}
		if(isset($_GET['edit_rights'])){
			include("edit_rights.php");
		}

	}

	if(isset($_GET['user_profile'])){
		include("user_profile.php");
	}

?>
<div class="container clearfix">
<div class="row">
<div id="languagePanel" class="bg-light col-md-12 p-2 pb-0 mb-0"><!--- languagePanel Starts --->
	<div class="row">
	<div class="col-md-6"><!--- col-md-6 Starts --->
	<p class="col-form-label font-weight-normal mb-0 pb-0">Current Selected Language: <strong><?= $currentLanguage; ?></strong></p>
	</div><!--- col-md-6 Ends --->
	<div class="col-md-6 float-right"><!--- col-md-6 Starts --->
	<div class="form-group row mb-0 pb-0"><!--- form-group row Starts --->
		<label class="col-md-2"></label>
		<label class="col-md-4 col-form-label"> Change Language: </label>
		<div class="col-md-6">
		<select id="languageSelect" class="form-control">
			<?php
			$get_languages = $db->select("languages");
			while($row_languages = $get_languages->fetch()){
			$id = $row_languages->id;
			$title = $row_languages->title;
			?>
			<option data-url="<?= "$site_url/admin/index?change_language=$id"; ?>" <?php if($id == $_SESSION["adminLanguage"]){ echo "selected"; } ?>>
			<?= $title; ?>
			</option>
	    	<?php } ?>
		</select>
		</div>
	</div><!--- form-group row Ends --->
	</div><!--- col-md-6 Ends -->
	</div>
</div><!--- languagePanel Ends --->
</div>
</div>

<br><br><br>
<script>
$(document).ready(function(){
	$("#languageSelect").change(function(){
		var url = $("#languageSelect option:selected").data("url");
		window.location.href = url;
	});
});
</script>
</div><!-- Right Panel -->

<script src="assets/js/popper.min.js"></script>
<script src="assets/js/plugins.js"></script>
</body>
</html>