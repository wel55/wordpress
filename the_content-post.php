<?php the_content();
				$my_postid = 1; //This is page id or post id
				$content_post = get_post($my_postid);
				$content = $content_post->post_content;
				echo $content;
?>