<?php
	include_once 'core/init.php';
	$query = isset($_GET['search_query']) ? $_GET['search_query'] :'';
	$f = isset($_GET['from']) ? $_GET['from'] : 0 ;

	$search_query = string($query);
	$from = $f;
	$limit = LMT_PER_PG ;
	$page = isset($_GET['page']) ? $_GET['page'] : 0 ;
	$start = ($page > 1) ? ($page * $limit) - $limit : $page;
	$title = 'Search result for "'. htmlspecialchars($query) .'"';
	include "includes/overall/header.php"; 
?>
<div class="row">
	<div class="container">
		<?php
		if(!empty($search_query))
		{
			$search_user_count = $conn -> query("SELECT id FROM users WHERE firstname LIKE '%$search_query%' || middlename LIKE '%$search_query%' || lastname LIKE '%$search_query%'");
			$search_user = $conn -> query("SELECT id FROM users WHERE firstname LIKE '%$search_query%' || middlename LIKE '%$search_query%' || lastname LIKE '%$search_query%' LIMIT $start, $limit");
			$search_post_count = $conn -> query("SELECT post_id FROM posts WHERE post_title LIKE '%$search_query%' || post_content LIKE '%$search_query%'");
			$search_post = $conn -> query("SELECT post_id FROM posts WHERE post_title LIKE '%$search_query%' || post_content LIKE '%$search_query%' LIMIT $start, $limit");
			// ....
			if($search_user_count -> num_rows <1 && $search_post_count -> num_rows < 1)
			{
				echo '<p>Sorry no result matches you search! <br> <b>Tip:</b> Try another keyword.</p>';
			}
			else
			{
				echo '<h1>Search results for "'. $search_query .'"</h1>';
			}

			if($search_user -> num_rows < 1)
			{
				//echo '<p>No resuolt found for users</p>';
			}
			else
			{
				echo '<div class="">
					<h2>Result for users</h2>';
				while($row = $search_user -> fetch_assoc())
				{
					echo '<div class="pd-lr pd-pd bd-t" style="margin-bottom:-11px;">';
					$user = new User($conn, $row['id']);
					$user->getLink();
					echo '</div>';
				}
				echo '</div>';					
				//display_pagicountry($total_posts, 'post', CURRENT_PAGE_NAME, $page);

				display_pagicountry($search_user_count -> num_rows, 'post', CURRENT_PAGE_NAME, $page);
			}

			if($search_post -> num_rows < 1)
			{
				//echo '<p>No result found for posts</p>';
			}
			else
			{
				echo '<div class="row">
					<p title="for about '. $search_post_count -> num_rows .' results">Result for posts:</p>';
				while($post = $search_post -> fetch_assoc())
				{
					echo '
					<div class="col-sm-4">
						<div class="mg pd">';
					//display_posts($post_id, $detail, $poster_id, $page);
					display_posts($post['post_id'], 'max', '', '');
					echo '
						</div>
					</div>';

				}
				display_pagicountry($search_post_count -> num_rows, 'post', CURRENT_PAGE_NAME, $page);
				echo '</div>';
			}
		}
		?>
	</div>
</div>
<?php include "includes/overall/footer.php"; ?>