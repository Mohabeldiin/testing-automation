<?php
/**
 * Template for displaying instructor of course within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/loop/course/instructor.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.1
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$course  = LP_Global::course();

$user_id = get_the_author_meta( 'ID' );
// $user    = LP_User_Factory::get_user( $user_id );
//if ( $profile_picture_src = $user->get_upload_profile_src(40) ) {
// 	if($profile_picture_src !== false){
//		$avatar = '<img src="'. esc_url($profile_picture_src) .'" alt="author-avatar" title="author-avatar">';
//	}else{
//		$avatar = get_avatar( $user_id, 40 );
//	}
//} else {
//	$avatar = get_avatar( $user_id, 40 );
//}

?>

<div class="course-author" itemscope itemtype="http://schema.org/Person">
	<?php echo get_avatar( $user_id, 50 ); ?>
	<div class="author-contain">
		<div class="value" itemprop="name">
			<?php
            if($course){
	            echo $course->get_instructor_html();
            }
            ?>
		</div>
	</div>
</div>